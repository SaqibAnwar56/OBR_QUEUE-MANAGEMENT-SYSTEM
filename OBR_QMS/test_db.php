<?php
$conn = mysqli_connect('localhost', 'root', '');
if (!$conn) {
    die("Can't connect: " . mysqli_connect_error());
}

$result = mysqli_query($conn, "SHOW DATABASES");
echo "<h3>All your databases:</h3><ul>";
while ($row = mysqli_fetch_row($result)) {
    echo "<li>" . $row[0] . "</li>";
}
echo "</ul>";
?>