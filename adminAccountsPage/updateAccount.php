<?php
$servername = "localhost";
$username = "root";
$password = "4702";
$dbname = "listings";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$id = intval($_POST['id']);
$newUsername = $conn->real_escape_string($_POST['username']);
$isAdmin = intval($_POST['isAdmin']);

$sql = "UPDATE users SET username='$newUsername', isAdmin=$isAdmin WHERE id=$id";
$conn->query($sql);
$conn->close();
?>
