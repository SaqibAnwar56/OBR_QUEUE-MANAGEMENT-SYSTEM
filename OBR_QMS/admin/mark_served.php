<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

include 'db.php';

$id = $_GET['id'];

$conn->query("UPDATE queue SET status='Served' WHERE id=$id");

header("Location: view_queue.php");
exit();
?>