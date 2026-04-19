<?php
// OBR Restaurant QMS — Database Connection
// Matches admin panel exactly: obr_qms
$conn = new mysqli("localhost", "root", "", "obr_qms");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8");
?>
