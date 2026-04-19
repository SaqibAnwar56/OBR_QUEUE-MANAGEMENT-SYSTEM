<?php
session_start();
include 'db.php';

if (!isset($_SESSION['cust_id'])) {
    header('Location: login.php?redirect=feedback.php'); exit;
}

$cid   = (int)$_SESSION['cust_id'];
$name  = $_SESSION['cust_name'];
$email = $_SESSION['cust_email'] ?? '';
$phone = $_SESSION['cust_phone'];
$err   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating  = (int)($_POST['rating']  ?? 0);
    $fb_type = trim($_POST['fb_type']  ?? 'General');
    $message = trim($_POST['message']  ?? '');

    if ($rating < 1 || $rating > 5) {
        $err = 'Please select a star rating (1 to 5 stars).';
    } elseif (empty($message)) {
        $err = 'Please write your feedback message.';
    } else {
        $n  = $conn->real_escape_string($name);
        $e  = $conn->real_escape_string($email);
        $ft = $conn->real_escape_string($fb_type);
        $m  = $conn->real_escape_string($message);

        // INSERT into customer_feedback — EXACT table admin reads in view_feedback.php
        $sql = "INSERT INTO customer_feedback (name, email, feedback_type, message, created_at) VALUES ('$n', '$e', '$ft', '$m', NOW())";

        if ($conn->query($sql)) {
            // Notify admin
            $notif = $conn->real_escape_string("New feedback from $name — Rating: {$rating}★ — Type: $fb_type");
            $conn->query("INSERT INTO notifications (message, is_read, created_at) VALUES ('$notif', 0, NOW())");
            header('Location: dashboard.php?fb=1'); exit;
        } else {
            $err = 'Could not submit: ' . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Leave Feedback — OBR Restaurant</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/customer.css">
</head>
<body class="inner-page">
<?php include 'nav.php'; ?>
<div class="fb-page">
  <div class="fb-card">
    <p class="sec-tag">Share Your Experience</p>
    <h2>Leave <em>Feedback</em></h2>
    <p class="fb-hint">Your feedback goes directly to our admin and helps us improve every day!</p>
    <?php if ($err): ?><div class="alert err">⚠ <?= htmlspecialchars($err) ?></div><?php endif; ?>
    <div class="fb-user">
      <div class="fb-ava"><?= strtoupper(substr($name,0,1)) ?></div>
      <div><strong><?= htmlspecialchars($name) ?></strong><small><?= htmlspecialchars($email ?: $phone) ?></small></div>
    </div>
    <form method="POST" class="aform">
      <div class="fg">
        <label>Your Rating *</label>
        <div class="star-row">
          <?php for ($i=5; $i>=1; $i--): ?>
          <input type="radio" name="rating" id="s<?=$i?>" value="<?=$i?>" <?= (isset($_POST['rating'])&&(int)$_POST['rating']===$i)?'checked':'' ?> required>
          <label for="s<?=$i?>">★</label>
          <?php endfor; ?>
        </div>
        <div class="star-labels"><span>1 — Poor</span><span>3 — Good</span><span>5 — Excellent</span></div>
      </div>
      <div class="fg">
        <label>Feedback Category</label>
        <select name="fb_type">
          <option value="General">General Experience</option>
          <option value="Food Quality">Food Quality</option>
          <option value="Service">Service &amp; Staff</option>
          <option value="Ambience">Ambience &amp; Decor</option>
          <option value="Queue">Queue Experience</option>
          <option value="Pricing">Pricing &amp; Value</option>
          <option value="Suggestion">Suggestion</option>
          <option value="Complaint">Complaint</option>
        </select>
      </div>
      <div class="fg">
        <label>Your Message *</label>
        <textarea name="message" rows="5" required placeholder="Tell us about your experience — what did you love? What can we improve?"><?= htmlspecialchars($_POST['message']??'') ?></textarea>
      </div>
      <button type="submit" class="btn-gold full">✅ Submit Feedback to Admin</button>
      <a href="dashboard.php" class="auth-back" style="text-align:center;display:block;margin-top:.8rem">Skip for now</a>
    </form>
  </div>
</div>
<script src="../assets/js/customer.js"></script>
</body></html>
