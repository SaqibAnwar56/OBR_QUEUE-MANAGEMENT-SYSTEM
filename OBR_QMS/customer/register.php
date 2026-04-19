<?php
session_start();
include 'db.php';
if (isset($_SESSION['cust_id'])) { header('Location: dashboard.php'); exit; }

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name']    ?? '');
    $phone   = trim($_POST['phone']   ?? '');
    $email   = trim($_POST['email']   ?? '');
    $pass    = $_POST['password']     ?? '';
    $confirm = $_POST['confirm']      ?? '';

    if (!$name || !$phone || !$pass)  $err = 'Name, phone and password are required.';
    elseif ($pass !== $confirm)        $err = 'Passwords do not match.';
    elseif (strlen($pass) < 6)        $err = 'Password must be at least 6 characters.';
    else {
        $chk = $conn->prepare("SELECT id FROM customers WHERE phone=?");
        $chk->bind_param("s", $phone); $chk->execute(); $chk->store_result();
        if ($chk->num_rows > 0) {
            $err = 'This phone number is already registered. Please login.';
        } else {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $st   = $conn->prepare("INSERT INTO customers (name, phone, email, password, created_at) VALUES (?,?,?,?,NOW())");
            $st->bind_param("ssss", $name, $phone, $email, $hash);
            if ($st->execute()) {
                $_SESSION['cust_id']    = $conn->insert_id;
                $_SESSION['cust_name']  = $name;
                $_SESSION['cust_phone'] = $phone;
                $_SESSION['cust_email'] = $email;
                // Notify admin
                $msg = $conn->real_escape_string("New customer registered: $name ($phone)");
                $conn->query("INSERT INTO notifications (message, is_read, created_at) VALUES ('$msg', 0, NOW())");
                header('Location: dashboard.php?welcome=1'); exit;
            } else {
                $err = 'Registration failed: ' . $conn->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Register — OBR Restaurant</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/customer.css">
</head>
<body class="auth-page">
<div class="auth-wrap">
  <div class="auth-left">
    <img src="../assets/images/outside.webp" alt="OBR Restaurant"
         onerror="this.src='https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=900&q=80'">
    <div class="auth-left-over">
      <a href="index.php" class="nav-logo"><span>OBR</span> Restaurant</a>
      <p class="auth-left-quote">"An unforgettable dining experience begins with a single reservation."</p>
    </div>
  </div>
  <div class="auth-right">
    <div class="auth-box">
      <p class="sec-tag">Join Us Today</p>
      <h2>Create Account</h2>
      <p class="auth-hint">Register free to join the queue, order food online, and track your token live.</p>
      <?php if ($err): ?><div class="alert err">⚠ <?= htmlspecialchars($err) ?></div><?php endif; ?>
      <form method="POST" class="aform">
        <div class="fg"><label>Full Name *</label><input type="text" name="name" placeholder="Enter Your Name" value="<?= htmlspecialchars($_POST['name']??'') ?>" required autofocus></div>
        <div class="fg"><label>Phone Number *</label><input type="tel" name="phone" placeholder="03XX-XXXXXXX" value="<?= htmlspecialchars($_POST['phone']??'') ?>" required></div>
        <div class="fg"><label>Email <span class="opt">(Optional)</span></label><input type="email" name="email" placeholder="you@email.com" value="<?= htmlspecialchars($_POST['email']??'') ?>"></div>
        <div class="frow">
          <div class="fg"><label>Password *</label><div class="pw-wrap"><input type="password" name="password" id="pw1" placeholder="Min. 6 characters" required><button type="button" class="pw-eye" onclick="togglePw('pw1',this)">👁</button></div></div>
          <div class="fg"><label>Confirm *</label><input type="password" name="confirm" placeholder="Repeat password" required></div>
        </div>
        <button type="submit" class="btn-gold full">Create My Account Free</button>
      </form>
      <div class="auth-divider"><span>or</span></div>
      <a href="menu.php" class="btn-outline full" style="justify-content:center">Browse Menu Without Registering</a>
      <a href="queue_status.php" class="btn-outline full" style="justify-content:center;margin-top:.5rem">View Live Queue (No Login)</a>
      <p class="auth-switch">Already have an account? <a href="login.php">Login here</a></p>
      <a href="index.php" class="auth-back">← Back to Home</a>
    </div>
  </div>
</div>
<script src="../assets/js/customer.js"></script>
</body></html>
