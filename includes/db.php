<?php
$conn = new mysqli(hostname: 'localhost', username: 'root', password: '', database: 'record_management');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

