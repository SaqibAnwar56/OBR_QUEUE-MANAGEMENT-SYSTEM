<?php
$host = "localhost";        // XAMPP server
$user = "root";             // default XAMPP user
$password = "";             // default no password
$database = "obr_qms";      // your database name

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// echo "Database connected successfully";
?>