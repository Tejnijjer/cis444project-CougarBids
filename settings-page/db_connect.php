
<?php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "cougarbids";
$port = 3307;

$conn = new mysqli($host, $user, $pass, $dbname, $port);

if ($conn->connect_error) {
    die("Connection FAILED: " . $conn->connect_error);
}

?>