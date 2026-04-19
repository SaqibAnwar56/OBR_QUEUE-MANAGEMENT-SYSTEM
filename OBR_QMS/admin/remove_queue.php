<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

include 'db.php';

$id = $_GET['id'];

$conn->query("DELETE FROM queue WHERE id=$id");

header("Location: view_queue.php");
exit();
?>