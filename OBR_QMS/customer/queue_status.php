<?php
session_start();
include 'db.php';

// ALL active queue rows — EXACTLY what admin sees in view_queue.php
$result=$conn->query("SELECT * FROM queue WHERE status='waiting' OR status='serving' ORDER BY token_number ASC");
$queue=[];
if($result) while($r=$result->fetch_assoc()) $queue[]=$r;

$serving_row=null; $waiting_count=0;
foreach($queue as $q){
    if($q['status']==='serving') $serving_row=$q;
    if($q['status']==='waiting') $waiting_count++;
}

$served_today=(int)$conn->query("SELECT COUNT(*) as c FROM queue WHERE (status='served' OR status='Served') AND DATE(created_at)=CURDATE()")->fetch_assoc()['c'];
$last_served=$conn->query("SELECT * FROM queue WHERE (status='served' OR status='Served') ORDER BY id DESC LIMIT 1")->fetch_assoc();

$my_token=null;
if(isset($_SESSION['cust_phone'])){
    $mt=$conn->prepare("SELECT * FROM queue WHERE phone=? AND status='waiting' LIMIT 1");
    $mt->bind_param("s",$_SESSION['cust_phone']); $mt->execute();
    $my_token=$mt->get_result()->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Live Queue Status — OBR Restaurant</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/customer.css">
</head>
<body class="inner-page">
<?php include 'nav.php'; ?>

<div class="queue-page">
  <div class="queue-wrap">
    <div style="padding:2rem 0 1.5rem">
      <p class="sec-tag center">Real-Time Updates</p>
      <h2 class="center">Live Queue <em>Status</em></h2>
      <p class="center" style="color:var(--text-s);font-size:.88rem;font-weight:300;margin-top:.4rem">
        Auto-refreshes every 20 seconds &middot; <?= date('h:i A') ?>
      </p>
    </div>

    <!-- Stats row -->
    <div class="q-stats">
      <div class="qs-card"><div class="qs-n green"><?= $waiting_count ?></div><div class="qs-lbl">Currently Waiting</div></div>
      <div class="qs-card highlight">
        <div class="qs-n gold"><?= $serving_row?'#'.str_pad($serving_row['token_number'],3,'0',STR_PAD_LEFT):'—' ?></div>
        <div class="qs-lbl">Now Serving</div>
      </div>
      <div class="qs-card"><div class="qs-n"><?= $served_today ?></div><div class="qs-lbl">Served Today</div></div>
      <div class="qs-card">
        <div class="qs-n"><?= $last_served?'#'.str_pad($last_served['token_number'],3,'0',STR_PAD_LEFT):'—' ?></div>
        <div class="qs-lbl">Last Served</div>
      </div>
    </div>

    <!-- NOW SERVING banner -->
    <?php if($serving_row): ?>
    <div class="serve-banner">
      <div class="sb-bell">🔔</div>
      <div class="sb-body">
        <p class="sb-label">NOW CALLING</p>
        <h2>#<?= str_pad($serving_row['token_number'],3,'0',STR_PAD_LEFT) ?></h2>
        <p><?= htmlspecialchars(explode(' ',$serving_row['name'])[0]) ?> &middot; <?= $serving_row['guests']??1 ?> guests &middot; Please come to the counter</p>
      </div>
    </div>
    <?php endif; ?>

    <!-- My token bar -->
    <?php if($my_token): ?>
    <div class="my-tok-bar">
      <span>🎫 Your Token:</span>
      <strong>#<?= str_pad($my_token['token_number'],3,'0',STR_PAD_LEFT) ?></strong>
      <span class="stag waiting">Waiting</span>
      <a href="dashboard.php" class="btn-outline sm" style="margin-left:auto">Dashboard</a>
    </div>
    <?php endif; ?>

    <!-- Queue list -->
    <div class="queue-box">
      <div class="qb-hdr">
        <h3>Queue List</h3>
        <span>🔄 Refreshing in <span id="cntDown">20</span>s</span>
      </div>
      <?php if(!empty($queue)): ?>
      <div class="q-thead"><span>Pos</span><span>Token</span><span>Name</span><span>Guests</span><span>Joined</span><span>Status</span></div>
      <?php foreach($queue as $idx=>$q):
        $is_mine=isset($_SESSION['cust_phone'])&&$q['phone']===$_SESSION['cust_phone'];
        $parts=explode(' ',$q['name']);
        $display=$parts[0].(isset($parts[1])?' '.strtoupper(substr($parts[1],0,1)).'.':'');
      ?>
      <div class="q-row <?= $q['status']==='serving'?'row-serving':'' ?> <?= $is_mine?'row-mine':'' ?>">
        <span class="q-pos"><?= $idx+1 ?></span>
        <span class="q-tok">#<?= str_pad($q['token_number'],3,'0',STR_PAD_LEFT) ?></span>
        <span><?= $is_mine?'<strong style="color:var(--gold)">👤 You</strong>':htmlspecialchars($display) ?></span>
        <span>👥 <?= $q['guests']??1 ?></span>
        <span class="q-time"><?= date('h:i A',strtotime($q['created_at'])) ?></span>
        <span><span class="stag <?= $q['status'] ?>"><?= ucfirst($q['status']) ?></span></span>
      </div>
      <?php endforeach; ?>
      <?php else: ?>
      <div class="eq-wrap">
        <div class="eq-ico">✅</div>
        <h3>Queue is Empty!</h3>
        <p>No one is waiting right now — walk right in!</p>
        <?php if(isset($_SESSION['cust_id'])): ?><a href="get_token.php" class="btn-gold">Get a Token</a>
        <?php else: ?><a href="register.php" class="btn-gold">Register &amp; Get Token</a><?php endif; ?>
      </div>
      <?php endif; ?>
    </div>

    <?php if(!isset($_SESSION['cust_id'])): ?>
    <div class="center" style="margin-top:3rem;padding:2rem;background:var(--bg2);border-radius:var(--r);border:1px solid var(--border-s)">
      <p style="color:var(--text-s);margin-bottom:1rem">Want to join the digital queue? Register free in 30 seconds.</p>
      <div style="display:flex;gap:.8rem;justify-content:center;flex-wrap:wrap">
        <a href="register.php" class="btn-gold">Register Free</a>
        <a href="login.php" class="btn-outline">Login</a>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>

<footer class="footer" style="margin-top:4rem">
  <div class="footer-bottom"><p>&copy; <?= date('Y') ?> OBR Restaurant. All rights reserved.</p></div>
</footer>

<script src="../assets/js/customer.js"></script>
<script>
var c=20, el=document.getElementById('cntDown');
setInterval(function(){ c--; if(el) el.textContent=c; if(c<=0) location.reload(); }, 1000);
</script>
</body></html>