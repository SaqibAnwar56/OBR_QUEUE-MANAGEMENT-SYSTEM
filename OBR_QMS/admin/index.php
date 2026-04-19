<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>
</head>
<body>
<h1>Welcome, <?php echo $_SESSION['admin_username']; ?>!</h1>
<a href="logout.php">Logout</a>
</body>
</html>