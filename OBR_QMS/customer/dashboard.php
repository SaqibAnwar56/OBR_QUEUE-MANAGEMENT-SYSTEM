<?php
session_start();
include 'db.php';
if (!isset($_SESSION['cust_id'])) { header('Location: login.php'); exit; }

// ── LOGGED-IN CUSTOMER DATA ───────────────────────────────────
// All queries use phone (UNIQUE) for queue, and exact name for orders
$cid   = (int)$_SESSION['cust_id'];
$name  = $_SESSION['cust_name'];
$phone = $_SESSION['cust_phone'];

// ── MY ACTIVE TOKEN (only THIS customer's phone) ──────────────
$aq = $conn->prepare("SELECT * FROM queue WHERE phone=? AND status='waiting' ORDER BY id DESC LIMIT 1");
$aq->bind_param("s", $phone); $aq->execute();
$active = $aq->get_result()->fetch_assoc();

// ── MY SERVING TOKEN (only THIS customer's phone) ─────────────
$sq = $conn->prepare("SELECT * FROM queue WHERE phone=? AND status='serving' ORDER BY id DESC LIMIT 1");
$sq->bind_param("s", $phone); $sq->execute();
$serving_tok = $sq->get_result()->fetch_assoc();

$my_token = $active ?? $serving_tok;

// ── MY POSITION IN QUEUE ──────────────────────────────────────
$position = 0; $ahead = 0;
if ($active) {
    // Count how many waiting tokens have a lower ID (joined before me)
    $pq = $conn->prepare("SELECT COUNT(*) as c FROM queue WHERE status='waiting' AND id <= ?");
    $pq->bind_param("i", $active['id']); $pq->execute();
    $position = (int)$pq->get_result()->fetch_assoc()['c'];
    $ahead    = max(0, $position - 1);
}

// ── GLOBAL STATS (restaurant-wide) ───────────────────────────
$tw  = (int)$conn->query("SELECT COUNT(*) as c FROM queue WHERE status='waiting'")->fetch_assoc()['c'];
$std = (int)$conn->query("SELECT COUNT(*) as c FROM queue WHERE (status='served' OR status='Served') AND DATE(created_at)=CURDATE()")->fetch_assoc()['c'];

// ── MY VISITS — only THIS customer's phone ────────────────────
$tv_q = $conn->prepare("SELECT COUNT(*) as c FROM queue WHERE phone=? AND status IN ('served','Served','cancelled')");
$tv_q->bind_param("s", $phone); $tv_q->execute();
$total_visits = (int)$tv_q->get_result()->fetch_assoc()['c'];

// ── MY RECENT ORDERS — only THIS customer's name ──────────────
// orders table only has customer_name (no customer_id) — use exact name match
$n   = $conn->real_escape_string($name);
$oq  = $conn->query("SELECT * FROM orders WHERE customer_name='$n' ORDER BY id DESC LIMIT 5");
$recent_orders = [];
if ($oq) while ($row = $oq->fetch_assoc()) $recent_orders[] = $row;

// Attach items to each order
foreach ($recent_orders as &$o) {
    $iq = $conn->query("
        SELECT oi.quantity,
               d.name  AS dish_name,
               d.price,
               (oi.quantity * d.price) AS subtotal
        FROM   order_items oi
        LEFT JOIN dishes d ON d.id = oi.dish_id
        WHERE  oi.order_id = {$o['id']}
    ");
    $o['items'] = [];
    if ($iq) while ($it = $iq->fetch_assoc()) $o['items'][] = $it;
}
unset($o);

// ── MY QUEUE HISTORY — only THIS customer's phone ─────────────
// Uses phone (UNIQUE) — guaranteed to show only this customer
$hq = $conn->prepare("SELECT * FROM queue WHERE phone=? ORDER BY id DESC LIMIT 10");
$hq->bind_param("s", $phone); $hq->execute();
$history = $hq->get_result()->fetch_all(MYSQLI_ASSOC);

// ── MY FEEDBACK COUNT — only THIS customer's name ─────────────
$fq = $conn->prepare("SELECT COUNT(*) as c FROM customer_feedback WHERE name=?");
$fq->bind_param("s", $name); $fq->execute();
$fb_count = (int)$fq->get_result()->fetch_assoc()['c'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>My Dashboard — OBR Restaurant</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/customer.css">
</head>
<body class="inner-page">
<?php include 'nav.php'; ?>

<div class="dash-wrap">

  <!-- ══ SIDEBAR ══════════════════════════════════════════════ -->
  <aside class="dash-side">
    <div class="dash-profile">
      <div class="dash-ava"><?= strtoupper(substr($name, 0, 1)) ?></div>
      <h3><?= htmlspecialchars($name) ?></h3>
      <p><?= htmlspecialchars($phone) ?></p>
      <?php if ($my_token): ?>
      <div style="margin-top:.6rem">
        <span class="stag <?= $my_token['status'] ?>">
          <?= $my_token['status'] === 'serving' ? '🔔 Being Served' : '⏳ In Queue' ?>
        </span>
      </div>
      <?php endif; ?>
    </div>
    <nav class="dash-nav">
      <a href="dashboard.php"    class="dlink active">📊 Dashboard</a>
      <a href="get_token.php"    class="dlink">🎫 Get Token</a>
      <a href="queue_status.php" class="dlink">📡 Live Queue</a>
      <a href="menu.php"         class="dlink">🍽️ Menu &amp; Order</a>
      <a href="orders.php"       class="dlink">
        📦 My Orders
        <?php if (!empty($recent_orders)): ?>
        <span style="background:var(--gold);color:var(--bg);font-size:.6rem;
                     padding:.1rem .45rem;border-radius:50px;margin-left:auto">
          <?= count($recent_orders) ?>
        </span>
        <?php endif; ?>
      </a>
      <a href="feedback.php" class="dlink">
        💬 Feedback
        <?php if ($fb_count > 0): ?>
        <span style="background:var(--gold-dim);color:var(--gold);font-size:.6rem;
                     padding:.1rem .45rem;border-radius:50px;margin-left:auto">
          <?= $fb_count ?>
        </span>
        <?php endif; ?>
      </a>
      <a href="logout.php" class="dlink dlink-logout">🚪 Logout</a>
    </nav>
  </aside>

  <!-- ══ MAIN ═════════════════════════════════════════════════ -->
  <main class="dash-main">

    <!-- Alerts -->
    <?php if (isset($_GET['welcome'])): ?>
    <div class="alert ok">🎉 Welcome, <?= htmlspecialchars(explode(' ',$name)[0]) ?>! Get your queue token to start dining.</div>
    <?php endif; ?>
    <?php if (isset($_GET['cancelled'])): ?>
    <div class="alert info">ℹ Your token was cancelled. Get a new one anytime.</div>
    <?php endif; ?>
    <?php if (isset($_GET['fb'])): ?>
    <div class="alert ok">✅ Thank you! Your feedback was sent to admin.</div>
    <?php endif; ?>

    <!-- Greeting -->
    <div class="dash-hdr">
      <h2>Welcome back, <em><?= htmlspecialchars(explode(' ',$name)[0]) ?></em></h2>
      <p><?= date('l, d F Y') ?> &nbsp;·&nbsp; <?= htmlspecialchars($phone) ?></p>
    </div>

    <!-- ── STAT CARDS ── -->
    <div class="dash-stats">
      <div class="ds-card">
        <div class="ds-ico">🎫</div>
        <div>
          <span class="ds-num">
            <?= $my_token ? '#'.str_pad($my_token['token_number'],3,'0',STR_PAD_LEFT) : '—' ?>
          </span>
          <span class="ds-lbl">My Token</span>
        </div>
      </div>
      <div class="ds-card">
        <div class="ds-ico">⏳</div>
        <div>
          <span class="ds-num"><?= $tw ?></span>
          <span class="ds-lbl">Waiting Now</span>
        </div>
      </div>
      <div class="ds-card">
        <div class="ds-ico">✅</div>
        <div>
          <span class="ds-num"><?= $std ?></span>
          <span class="ds-lbl">Served Today</span>
        </div>
      </div>
      <div class="ds-card">
        <div class="ds-ico">🏅</div>
        <div>
          <span class="ds-num"><?= $total_visits ?></span>
          <span class="ds-lbl">My Visits</span>
        </div>
      </div>
    </div>

    <!-- ── TOKEN STATUS CARD ── -->
    <?php if ($serving_tok && !$active): ?>
    <!-- Being served -->
    <div class="tok-card serving-c">
      <div>
        <p class="tc-lbl">Your Token</p>
        <div class="tc-num">#<?= str_pad($serving_tok['token_number'],3,'0',STR_PAD_LEFT) ?></div>
        <span class="stag serving">🔔 Now Serving</span>
      </div>
      <div>
        <div class="serve-box">
          <div class="bell">🔔</div>
          <h4>Your Table is Ready!</h4>
          <p>Please proceed to the reception counter now.</p>
          <a href="feedback.php" class="btn-gold sm" style="margin-top:.6rem">⭐ Rate Your Visit</a>
        </div>
      </div>
    </div>

    <?php elseif ($active): ?>
    <!-- Waiting in queue -->
    <div class="tok-card waiting-c">
      <div>
        <p class="tc-lbl">Your Token</p>
        <div class="tc-num">#<?= str_pad($active['token_number'],3,'0',STR_PAD_LEFT) ?></div>
        <span class="stag waiting">⏳ Waiting</span>
        <?php if ($active['guests'] > 1): ?>
        <p style="color:var(--text-s);font-size:.83rem;margin-top:.5rem">
          👥 <?= $active['guests'] ?> guests
        </p>
        <?php endif; ?>
        <?php if (!empty($active['special_request'])): ?>
        <p style="color:var(--text-m);font-size:.75rem;margin-top:.3rem">
          📝 <?= htmlspecialchars(mb_substr($active['special_request'],0,40)) ?>…
        </p>
        <?php endif; ?>
      </div>
      <div class="tc-right">
        <p class="tc-lbl">Your Position</p>
        <div class="tc-pos"><?= $position ?></div>
        <p class="tc-sub"><?= $ahead ?> people ahead of you</p>
        <p class="tc-est">Estimated wait: ~<?= $ahead * 8 ?> min</p>
        <div style="display:flex;gap:.5rem;justify-content:center;flex-wrap:wrap;margin-top:1rem">
          <a href="menu.php" class="btn-gold sm">🍽️ Pre-order</a>
          <form method="POST" action="cancel_token.php"
                onsubmit="return confirm('Cancel your token? You will lose your spot.')">
            <input type="hidden" name="token_id" value="<?= $active['id'] ?>">
            <button type="submit" class="btn-red">Cancel Token</button>
          </form>
        </div>
      </div>
    </div>

    <?php else: ?>
    <!-- No token -->
    <div class="no-tok-card">
      <div class="nt-ico">🎫</div>
      <h3>No Active Token</h3>
      <p>You don't have an active queue token. Get one to reserve your spot!</p>
      <a href="get_token.php" class="btn-gold" style="margin-top:1rem">Get Queue Token Now</a>
    </div>
    <?php endif; ?>

    <!-- ── MY RECENT ORDERS (only this customer) ── -->
    <?php if (!empty($recent_orders)): ?>
    <div class="dash-sec">
      <h3 class="dash-sec-hd">My Recent Orders</h3>
      <?php foreach ($recent_orders as $o):
        $item_count = count($o['items']);
      ?>
      <div class="order-card" style="margin-bottom:1rem">
        <div class="order-card-hdr">
          <div class="order-id">Order #<?= str_pad($o['id'],4,'0',STR_PAD_LEFT) ?></div>
          <div class="order-meta">
            <h4><?= $item_count ?> item<?= $item_count !== 1 ? 's' : '' ?></h4>
            <p><?= date('d M Y, h:i A', strtotime($o['created_at'])) ?></p>
          </div>
          <span class="stag confirmed">✅ Placed</span>
          <div class="order-amt">Rs. <?= number_format($o['total_amount'], 0) ?></div>
        </div>
        <?php if (!empty($o['items'])): ?>
        <div class="order-card-body">
          <?php foreach ($o['items'] as $it): ?>
          <div class="order-item">
            <span class="order-item-name"><?= htmlspecialchars($it['dish_name'] ?? 'Item') ?></span>
            <span class="order-item-qty">×<?= $it['quantity'] ?></span>
            <?php if (!empty($it['subtotal'])): ?>
            <span class="order-item-sub">Rs. <?= number_format($it['subtotal'], 0) ?></span>
            <?php endif; ?>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
      <a href="orders.php" class="btn-outline sm">View All My Orders →</a>
    </div>
    <?php endif; ?>

    <!-- ── MY QUEUE HISTORY (only this customer's phone) ── -->
    <?php if (!empty($history)): ?>
    <div class="dash-sec">
      <h3 class="dash-sec-hd">
        My Queue History
        <span style="font-size:.75rem;color:var(--text-m);font-weight:300;margin-left:.5rem">
          — <?= htmlspecialchars($phone) ?>
        </span>
      </h3>
      <div class="tbl-wrap">
        <table class="dash-tbl">
          <thead>
            <tr>
              <th>Token</th>
              <th>Guests</th>
              <th>Special Request</th>
              <th>Date &amp; Time</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($history as $h): ?>
          <tr>
            <td><strong>#<?= str_pad($h['token_number'],3,'0',STR_PAD_LEFT) ?></strong></td>
            <td><?= $h['guests'] ?? 1 ?></td>
            <td style="color:var(--text-m);font-size:.83rem">
              <?= !empty($h['special_request'])
                    ? htmlspecialchars(mb_substr($h['special_request'],0,35))
                    : '—' ?>
            </td>
            <td><?= date('d M Y, h:i A', strtotime($h['created_at'])) ?></td>
            <td>
              <span class="stag <?= strtolower($h['status']) ?>">
                <?= ucfirst($h['status']) ?>
              </span>
            </td>
          </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php else: ?>
    <!-- No history yet -->
    <div class="dash-sec">
      <h3 class="dash-sec-hd">My Queue History</h3>
      <div style="background:var(--bg2);border:1px dashed var(--border-s);border-radius:var(--r);
                  padding:2.5rem;text-align:center;color:var(--text-m)">
        <p style="font-size:1.5rem;margin-bottom:.5rem">🎫</p>
        <p style="font-size:.9rem">No queue history yet.</p>
        <a href="get_token.php" class="btn-gold sm" style="margin-top:1rem;display:inline-flex">
          Get Your First Token
        </a>
      </div>
    </div>
    <?php endif; ?>

    <!-- ── QUICK ACTIONS ── -->
    <div class="dash-sec">
      <h3 class="dash-sec-hd">Quick Actions</h3>
      <div class="quick-grid">
        <a href="get_token.php"    class="quick-card">
          <div class="qc-ico">🎫</div><h4>Get Token</h4><p>Join the queue</p>
        </a>
        <a href="queue_status.php" class="quick-card">
          <div class="qc-ico">📡</div><h4>Live Queue</h4><p>Track positions</p>
        </a>
        <a href="menu.php"         class="quick-card">
          <div class="qc-ico">🍽️</div><h4>Order Food</h4><p>Browse &amp; order</p>
        </a>
        <a href="feedback.php"     class="quick-card">
          <div class="qc-ico">💬</div><h4>Feedback</h4><p>Rate your visit</p>
        </a>
      </div>
    </div>

  </main>
</div>

<script src="../assets/js/customer.js"></script>
<?php if ($active): ?>
<!-- Auto-refresh every 30s while in queue to update position -->
<script>setTimeout(function(){ location.reload(); }, 30000);</script>
<?php endif; ?>
</body>
</html>
