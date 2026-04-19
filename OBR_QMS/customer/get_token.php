<?php
session_start();
include 'db.php';
if (!isset($_SESSION['cust_id'])) { header('Location: login.php'); exit; }

$name  = $_SESSION['cust_name'];
$phone = $_SESSION['cust_phone'];
$err   = '';
$new   = null;

// Check for ANY active token — waiting OR serving (prevents duplicate tokens)
$p2  = $conn->real_escape_string($phone);
$chk = $conn->query("SELECT * FROM queue WHERE phone='$p2' AND status IN ('waiting','serving') ORDER BY id DESC LIMIT 1");
$active = $chk ? $chk->fetch_assoc() : null;

// Create token only when none exists
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$active) {
    $guests = max(1, intval($_POST['guests'] ?? 1));
    $req    = $conn->real_escape_string(trim($_POST['special_request'] ?? ''));
    $mx     = $conn->query("SELECT MAX(token_number) as m FROM queue");
    $tok    = ($mx->fetch_assoc()['m'] ?? 0) + 1;
    $n      = $conn->real_escape_string($name);

    $sql = "INSERT INTO queue (name, phone, token_number, guests, special_request, status, created_at)
            VALUES ('$n', '$p2', $tok, $guests, '$req', 'waiting', NOW())";
    if ($conn->query($sql)) {
        $qid = $conn->insert_id;
        $pos = (int)$conn->query("SELECT COUNT(*) as c FROM queue WHERE status='waiting'")->fetch_assoc()['c'];
        $new = ['id'=>$qid,'token_number'=>$tok,'position'=>$pos,'guests'=>$guests,'est'=>max(0,$pos-1)*8];
        $msg = $conn->real_escape_string("New queue token #$tok — $name ($phone), $guests guest(s)");
        $conn->query("INSERT INTO notifications (message, is_read, created_at) VALUES ('$msg', 0, NOW())");
    } else {
        $err = 'Could not generate token: ' . $conn->error;
    }
}

$cur_status = $active ? strtolower($active['status']) : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Get Queue Token — OBR Restaurant</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/customer.css">
</head>
<body class="inner-page">
<?php include 'nav.php'; ?>
<div class="token-page">
  <div class="token-wrap">

  <?php if ($new): ?>
  <!-- TOKEN JUST GENERATED -->
  <div class="tok-success">
    <div class="tok-check">✓</div>
    <p class="sec-tag" style="text-align:center">Token Generated!</p>
    <h2 style="text-align:center;margin-bottom:.4rem">You're in the Queue!</h2>
    <p style="text-align:center;color:var(--text-s);font-weight:300;margin-bottom:1.5rem">
      Your spot is confirmed. Wait for admin to accept your token — then you can order food!
    </p>
    <div class="big-tok">
      <span class="bt-lbl">YOUR TOKEN NUMBER</span>
      <div class="bt-num">#<?= str_pad($new['token_number'],3,'0',STR_PAD_LEFT) ?></div>
    </div>
    <div class="tok-details">
      <div class="td-box"><span>Position</span><strong><?= $new['position'] ?></strong></div>
      <div class="td-box"><span>Guests</span><strong><?= $new['guests'] ?></strong></div>
      <div class="td-box"><span>Est. Wait</span><strong>~<?= $new['est'] ?> min</strong></div>
    </div>
    <p class="tok-tip" style="text-align:center;background:rgba(200,169,110,.08);border:1px solid var(--border);border-radius:var(--r);padding:1rem;margin:1.2rem 0">
      ⏳ <strong>Ordering is locked</strong> until admin accepts your token by clicking ▶ Serve.
      Once accepted, go to menu and place your food order!
    </p>
    <div style="display:flex;gap:.8rem;justify-content:center;flex-wrap:wrap">
      <a href="queue_status.php" class="btn-gold">📡 Track Live Queue</a>
      <a href="menu.php"         class="btn-outline">🍽️ Browse Menu</a>
      <a href="dashboard.php"    class="btn-outline">My Dashboard</a>
    </div>
  </div>

  <?php elseif ($active && $cur_status === 'waiting'): ?>
  <!-- TOKEN EXISTS — WAITING -->
  <div class="tok-success warn-card">
    <div class="tok-check warn-ico">⏳</div>
    <h2 style="text-align:center;margin-bottom:.4rem">Waiting for Admin</h2>
    <p style="text-align:center;color:var(--text-s);font-weight:300;margin-bottom:1.5rem">
      Your token is in queue. Admin hasn't accepted it yet — ordering is locked.
    </p>
    <div class="big-tok">
      <span class="bt-lbl">YOUR CURRENT TOKEN</span>
      <div class="bt-num">#<?= str_pad($active['token_number'],3,'0',STR_PAD_LEFT) ?></div>
    </div>
    <div style="text-align:center;margin:1rem 0">
      <span class="stag waiting">⏳ Waiting — Ordering Locked 🔒</span>
    </div>
    <div style="display:flex;gap:.8rem;justify-content:center;flex-wrap:wrap">
      <a href="queue_status.php" class="btn-gold">📡 Track Queue</a>
      <a href="menu.php"         class="btn-outline">🍽️ Browse Menu</a>
      <a href="dashboard.php"    class="btn-outline">Dashboard</a>
    </div>
  </div>

  <?php elseif ($active && $cur_status === 'serving'): ?>
  <!-- TOKEN ACCEPTED — ORDER UNLOCKED -->
  <div class="tok-success" style="border-color:var(--green)">
    <div class="tok-check" style="background:rgba(77,179,126,.12);color:var(--green)">🔔</div>
    <p class="sec-tag" style="text-align:center;color:var(--green)">Admin Accepted Your Token!</p>
    <h2 style="text-align:center;margin-bottom:.4rem">Order Food Now!</h2>
    <p style="text-align:center;color:var(--text-s);font-weight:300;margin-bottom:1.5rem">
      Your token is accepted. Go to menu and place your food order!
    </p>
    <div class="big-tok" style="border:1px solid rgba(77,179,126,.4)">
      <span class="bt-lbl">YOUR TOKEN</span>
      <div class="bt-num" style="color:var(--green)">#<?= str_pad($active['token_number'],3,'0',STR_PAD_LEFT) ?></div>
    </div>
    <div style="text-align:center;margin:1rem 0">
      <span class="stag serving">🔔 Accepted — Ordering Unlocked ✅</span>
    </div>
    <div style="display:flex;gap:.8rem;justify-content:center;flex-wrap:wrap">
      <a href="menu.php"         class="btn-gold">🍽️ Place Food Order Now</a>
      <a href="queue_status.php" class="btn-outline">📡 Live Queue</a>
      <a href="dashboard.php"    class="btn-outline">Dashboard</a>
    </div>
  </div>

  <?php else: ?>
  <!-- NO TOKEN — SHOW FORM -->
  <div class="tok-form-card">
    <p class="sec-tag" style="text-align:center">Reserve Your Spot</p>
    <h2 style="text-align:center;margin-bottom:.4rem">Get Queue Token</h2>
    <p style="text-align:center;color:var(--text-s);font-weight:300;margin-bottom:2rem">
      Join the digital queue. Once admin accepts your token, you can order food from the menu.
    </p>
    <?php if ($err): ?><div class="alert err">⚠ <?= htmlspecialchars($err) ?></div><?php endif; ?>
    <div class="cust-bar">
      <span>👤 <?= htmlspecialchars($name) ?></span>
      <span>📞 <?= htmlspecialchars($phone) ?></span>
    </div>
    <form method="POST" class="aform" style="margin-top:1.5rem">
      <div class="fg">
        <label>Number of Guests</label>
        <div class="guest-ctrl">
          <button type="button" onclick="gc(-1)">−</button>
          <input type="number" name="guests" id="gcInp" value="1" min="1" max="20" readonly>
          <button type="button" onclick="gc(1)">+</button>
        </div>
      </div>
      <div class="fg">
        <label>Special Request <span class="opt">(Optional)</span></label>
        <textarea name="special_request" placeholder="Dietary restrictions, birthday, window seat..." rows="3"></textarea>
      </div>
      <button type="submit" class="btn-gold full">🎫 Generate My Token</button>
    </form>
  </div>
  <?php endif; ?>

  </div>
</div>
<script src="../assets/js/customer.js"></script>
<script>function gc(v){var i=document.getElementById('gcInp');i.value=Math.min(20,Math.max(1,parseInt(i.value)+v));}</script>
</body>
</html>
