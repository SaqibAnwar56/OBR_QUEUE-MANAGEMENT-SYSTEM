<?php
// OBR Restaurant QMS — Database Connection
$conn = new mysqli("127.0.0.1", "root", "", "obr_qms");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>
