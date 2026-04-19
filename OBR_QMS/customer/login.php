<?php
session_start();
include 'db.php';
if (isset($_SESSION['cust_id'])) { header('Location: dashboard.php'); exit; }

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = trim($_POST['phone']    ?? '');
    $pass  = $_POST['password']      ?? '';
    if (!$phone || !$pass) {
        $err = 'Phone number and password are required.';
    } else {
        $st = $conn->prepare("SELECT * FROM customers WHERE phone=? LIMIT 1");
        $st->bind_param("s", $phone); $st->execute();
        $c = $st->get_result()->fetch_assoc();
        if ($c && password_verify($pass, $c['password'])) {
            $_SESSION['cust_id']    = $c['id'];
            $_SESSION['cust_name']  = $c['name'];
            $_SESSION['cust_phone'] = $c['phone'];
            $_SESSION['cust_email'] = $c['email'] ?? '';
            $redir = $_GET['redirect'] ?? 'dashboard.php';
            header('Location: ' . $redir); exit;
        } else {
            $err = 'Invalid phone number or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Login — OBR Restaurant</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/customer.css">
</head>
<body class="auth-page">
<div class="auth-wrap">
  <div class="auth-left">
    <img src="../assets/images/NIGHT VIEW.JPEG" alt="OBR Restaurant Night"
         onerror="this.src='../assets/images/night.jpg'">
    <div class="auth-left-over">
      <a href="index.php" class="nav-logo"><span>OBR</span> Restaurant</a>
      <p class="auth-left-quote">"Good food is the foundation of genuine happiness."</p>
    </div>
  </div>
  <div class="auth-right">
    <div class="auth-box">
      <p class="sec-tag">Welcome Back</p>
      <h2>Login</h2>
      <p class="auth-hint">Access your account to manage your queue token and orders.</p>
      <?php if ($err): ?><div class="alert err">⚠ <?= htmlspecialchars($err) ?></div><?php endif; ?>
      <?php if (isset($_GET['msg']) && $_GET['msg'] === 'out'): ?><div class="alert ok">✓ Logged out successfully.</div><?php endif; ?>
      <form method="POST" class="aform">
        <div class="fg">
          <label>Phone Number</label>
          <input type="tel" name="phone" placeholder="03XX-XXXXXXX" value="<?= htmlspecialchars($_POST['phone']??'') ?>" required autofocus>
        </div>
        <div class="fg">
          <label>Password</label>
          <div class="pw-wrap">
            <input type="password" name="password" id="pwInp" placeholder="Your password" required>
            <button type="button" class="pw-eye" onclick="togglePw('pwInp',this)">👁</button>
          </div>
        </div>
        <button type="submit" class="btn-gold full">Login to My Account</button>
      </form>
      <div class="auth-divider"><span>or continue without account</span></div>
      <a href="menu.php" class="btn-outline full" style="justify-content:center">Browse Menu as Guest</a>
      <a href="queue_status.php" class="btn-outline full" style="justify-content:center;margin-top:.5rem">View Live Queue</a>
      <p class="auth-switch">New here? <a href="register.php">Create free account</a></p>
      <a href="index.php" class="auth-back">← Back to Home</a>
    </div>
  </div>
</div>
<script src="../assets/js/customer.js"></script>
</body></html>
