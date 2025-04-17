
<?php

$host = "localhost";
$user = "root";
$pass = ""; // If you never set one
$dbname = "cougarbids";
$port = 3307;

$conn = new mysqli($host, $user, $pass, $dbname, $port);

if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

echo "✅ MySQL connection successful!";
?>
