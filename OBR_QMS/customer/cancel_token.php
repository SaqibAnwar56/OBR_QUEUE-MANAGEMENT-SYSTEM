<?php
session_start();
include 'db.php';
if (!isset($_SESSION['cust_id'])) { header('Location: login.php'); exit; }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tid   = (int)($_POST['token_id'] ?? 0);
    $phone = $conn->real_escape_string($_SESSION['cust_phone']);
    // Cancel — admin will see status as 'cancelled' in their queue view
    $conn->query("UPDATE queue SET status='cancelled', updated_at=NOW() WHERE id=$tid AND phone='$phone' AND status='waiting'");
}
header('Location: dashboard.php?cancelled=1'); exit;
?>
