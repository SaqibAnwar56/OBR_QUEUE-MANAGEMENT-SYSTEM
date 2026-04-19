<?php
session_start();
include "../includes/db.php";

if(!isset($_SESSION['admin'])){
    header("Location: index.php");
    exit;
}

if(isset($_GET['id']) && isset($_GET['status'])){
    $id = intval($_GET['id']);
    $status = $_GET['status'];

    $conn->query("UPDATE queue SET status='$status' WHERE id=$id");
}

header("Location: view_queue.php");
exit;
?>