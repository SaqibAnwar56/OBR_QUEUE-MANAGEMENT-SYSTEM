<?php
$conn = new mysqli("localhost","root","","obr_qms");

$id = $_GET['id'];
$conn->query("UPDATE queue SET status='served' WHERE id='$id'");

header("Location: view_notifications.php");
exit;
?>