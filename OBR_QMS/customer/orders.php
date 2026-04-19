<?php
/**
 * OBR Restaurant — orders.php
 * Customer order history page.
 * URL: customer/orders.php  (browser GET only)
 */
session_start();
include 'db.php';

if (!isset($_SESSION['cust_id'])) {
    header('Location: login.php');
    exit;
}

$cust_name  = $_SESSION['cust_name'];
$cust_phone = $_SESSION['cust_phone'];

// ── All orders for this customer ─────────────────────────────
$n      = $conn->real_escape_string($cust_name);
$oq     = $conn->query("SELECT * FROM orders WHERE customer_name='$n' ORDER BY id DESC");
$orders = [];
if ($oq) while ($row = $oq->fetch_assoc()) $orders[] = $row;

// ── Items per order (JOIN dishes for name + price) ────────────
foreach ($orders as &$o) {
    $iq = $conn->query("
        SELECT oi.quantity,
               d.id    AS dish_id,
               d.name  AS dish_name,
               d.price,
               (oi.quantity * d.price) AS subtotal
        FROM   order_items oi
        LEFT JOIN dishes d ON d.id = oi.dish_id
        WHERE  oi.order_id = {$o['id']}
        ORDER  BY oi.id ASC
    ");
    $o['items'] = [];
    if ($iq) while ($it = $iq->fetch_assoc()) $o['items'][] = $it;
}
unset($o);

// ── Summary stats ─────────────────────────────────────────────
$total_orders      = count($orders);
$total_spent       = (float)array_sum(array_column($orders, 'total_amount'));
$orders_this_month = 0;
foreach ($orders as $o) {
    if (date('Y-m', strtotime($o['created_at'])) === date('Y-m')) $orders_this_month++;
}
$avg_order = $total_orders > 0 ? $total_spent / $total_orders : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>My Orders — OBR Restaurant</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/customer.css">
</head>
<body class="inner-page">
<?php include 'nav.php'; ?>

<div class="dash-wrap">

  <!-- Sidebar -->
  <aside class="dash-side">
    <div class="dash-profile">
      <div class="dash-ava"><?= strtoupper(substr($cust_name, 0, 1)) ?></div>
      <h3><?= htmlspecialchars($cust_name) ?></h3>
      <p><?= htmlspecialchars($cust_phone) ?></p>
    </div>
    <nav class="dash-nav">
      <a href="dashboard.php"    class="dlink">📊 Dashboard</a>
      <a href="get_token.php"    class="dlink">🎫 Get Token</a>
      <a href="queue_status.php" class="dlink">📡 Live Queue</a>
      <a href="menu.php"         class="dlink">🍽️ Menu &amp; Order</a>
      <a href="orders.php"       class="dlink active">📦 My Orders</a>
      <a href="feedback.php"     class="dlink">💬 Feedback</a>
      <a href="logout.php"       class="dlink dlink-logout">🚪 Logout</a>
    </nav>
  </aside>

  <!-- Main -->
  <main class="dash-main">

    <div class="dash-hdr">
      <h2>My <em>Orders</em></h2>
      <p>All food orders placed online — visible in admin daily sales report</p>
    </div>

    <?php if (isset($_GET['placed'])): ?>
    <div class="alert ok">
      ✅ Order #<?= (int)$_GET['placed'] ?> placed successfully! Admin has been notified.
    </div>
    <?php endif; ?>

    <!-- Stats (only if has orders) -->
    <?php if ($total_orders > 0): ?>
    <div class="dash-stats" style="margin-bottom:2rem">
      <div class="ds-card">
        <div class="ds-ico">📦</div>
        <div>
          <span class="ds-num"><?= $total_orders ?></span>
          <span class="ds-lbl">Total Orders</span>
        </div>
      </div>
      <div class="ds-card">
        <div class="ds-ico">💰</div>
        <div>
          <span class="ds-num" style="font-size:1.3rem">Rs.<?= number_format($total_spent, 0) ?></span>
          <span class="ds-lbl">Total Spent</span>
        </div>
      </div>
      <div class="ds-card">
        <div class="ds-ico">📅</div>
        <div>
          <span class="ds-num"><?= $orders_this_month ?></span>
          <span class="ds-lbl">This Month</span>
        </div>
      </div>
      <div class="ds-card">
        <div class="ds-ico">⭐</div>
        <div>
          <span class="ds-num" style="font-size:1.3rem">Rs.<?= number_format($avg_order, 0) ?></span>
          <span class="ds-lbl">Avg Order</span>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- Empty state -->
    <?php if (empty($orders)): ?>
    <div class="no-tok-card">
      <div class="nt-ico">📦</div>
      <h3>No Orders Yet</h3>
      <p>You haven't placed any food orders yet.<br>Browse our menu and order online!</p>
      <a href="menu.php" class="btn-gold" style="margin-top:1.2rem">🍽️ Browse Menu &amp; Order</a>
    </div>

    <?php else: ?>

    <!-- Order cards -->
    <?php foreach ($orders as $o):
      $item_count = count($o['items']);
      $ord_total  = (float)$o['total_amount'];
    ?>
    <div class="order-card" style="margin-bottom:1.2rem">

      <!-- Header -->
      <div class="order-card-hdr">
        <div class="order-id">📦 Order #<?= str_pad($o['id'], 4, '0', STR_PAD_LEFT) ?></div>
        <div class="order-meta">
          <h4><?= $item_count ?> item<?= $item_count !== 1 ? 's' : '' ?></h4>
          <p><?= date('d M Y, h:i A', strtotime($o['created_at'])) ?></p>
        </div>
        <?php
          $pm = $o['payment_method'] ?? 'cash';
          $ps = $o['payment_status'] ?? 'unpaid';
          $pm_icons = ['cash'=>'💵','easypaisa'=>'📱','jazzcash'=>'🎵','hbl'=>'🏦'];
          $pm_icon  = $pm_icons[$pm] ?? '💳';
          $pm_label = ucfirst($pm);
          if($ps === 'paid'):
        ?><span class="stag confirmed" style="align-self:center"><?= $pm_icon ?> Paid</span>
        <?php elseif($ps === 'pending_verification'): ?>
        <span class="stag serving" style="align-self:center;font-size:.72rem"><?= $pm_icon ?> <?= $pm_label ?> — Verifying</span>
        <?php else: ?>
        <span class="stag waiting" style="align-self:center;font-size:.72rem"><?= $pm_icon ?> <?= $pm_label === 'Cash' ? 'Pay at Counter' : $pm_label ?></span>
        <?php endif; ?>
        <div class="order-amt">Rs. <?= number_format($ord_total, 0) ?></div>
      </div>

      <!-- Items list -->
      <div class="order-card-body">

        <?php if (!empty($o['items'])): ?>
          <?php foreach ($o['items'] as $it):
            $unit_price = (float)($it['price']    ?? 0);
            $line_total = (float)($it['subtotal'] ?? $unit_price * $it['quantity']);
          ?>
          <div class="order-item">
            <span class="order-item-name">
              <?= htmlspecialchars($it['dish_name'] ?? 'Item') ?>
            </span>
            <span class="order-item-qty">×<?= (int)$it['quantity'] ?></span>
            <?php if ($unit_price > 0): ?>
            <span style="font-size:.78rem;color:var(--text-m)">
              @ Rs.<?= number_format($unit_price, 0) ?>
            </span>
            <span class="order-item-sub">
              Rs. <?= number_format($line_total, 0) ?>
            </span>
            <?php endif; ?>
          </div>
          <?php endforeach; ?>

          <!-- Order total -->
          <div style="display:flex;justify-content:flex-end;align-items:center;
                      gap:.6rem;padding-top:.8rem;margin-top:.6rem;
                      border-top:1px solid var(--border-s)">
            <span style="font-size:.72rem;color:var(--text-m);
                         text-transform:uppercase;letter-spacing:.1em">Order Total</span>
            <span style="font-family:'Cormorant Garamond',serif;
                         font-size:1.4rem;color:var(--gold);font-weight:600">
              Rs. <?= number_format($ord_total, 0) ?>
            </span>
          </div>

        <?php else: ?>
          <p style="font-size:.83rem;color:var(--text-m);padding:.4rem 0">
            ℹ️ Dish details not available.
            Total: <strong style="color:var(--gold)">Rs. <?= number_format($ord_total, 0) ?></strong>
          </p>
        <?php endif; ?>

        <!-- Payment info -->
        <?php
          $pm2 = $o['payment_method'] ?? 'cash';
          $ps2 = $o['payment_status'] ?? 'unpaid';
          $txn2 = $o['transaction_id'] ?? '';
          $pm_icons2 = ['cash'=>'💵','easypaisa'=>'📱','jazzcash'=>'🎵','hbl'=>'🏦'];
          $ico2 = $pm_icons2[$pm2] ?? '💳';
        ?>
        <div style="margin-top:.8rem;padding:.7rem .9rem;background:rgba(255,255,255,.03);border-radius:var(--rs);border:1px solid var(--border-s);font-size:.8rem;display:flex;align-items:center;gap:.8rem;flex-wrap:wrap">
          <span><?= $ico2 ?> <strong><?= ucfirst($pm2) ?></strong></span>
          <?php if($ps2 === 'paid'): ?>
            <span style="color:var(--green)">✅ Payment Confirmed</span>
          <?php elseif($ps2 === 'pending_verification'): ?>
            <span style="color:var(--gold)">⏳ Awaiting admin verification</span>
            <?php if($txn2): ?><span style="color:var(--text-m)">TXN: <?= htmlspecialchars($txn2) ?></span><?php endif; ?>
          <?php else: ?>
            <span style="color:var(--text-m)"><?= $pm2==='cash'?'Pay at counter':'Unpaid' ?></span>
          <?php endif; ?>
        </div>

        <!-- Actions -->
        <div style="margin-top:1rem;display:flex;gap:.7rem;flex-wrap:wrap">
          <a href="menu.php"     class="btn-gold sm">🍽️ Order Again</a>
          <a href="feedback.php" class="btn-outline sm">💬 Leave Feedback</a>
        </div>

      </div>
    </div>
    <?php endforeach; ?>

    <!-- Bottom CTA -->
    <div style="text-align:center;padding:2rem 0 1rem;
                margin-top:1rem;border-top:1px solid var(--border-s)">
      <p style="color:var(--text-m);margin-bottom:1rem;font-size:.9rem">
        Want to order more food?
      </p>
      <a href="menu.php" class="btn-gold">🍽️ Browse Menu &amp; Order</a>
    </div>

    <?php endif; ?>

  </main>
</div>

<script src="../assets/js/customer.js"></script>
</body>
</html>