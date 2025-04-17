
<?php

session_start();
include 'db_connect.php';

// UNCOMMENT AFTER TEST
$user_id = $_SESSION['userID'] ?? 1;

// TEST USER - manually test delete
// $user_id = $_SESSION['userID'] = 1;

// delete billing info
$conn->query("DELETE FROM billing_info WHERE user_id = $user_id");

// delete user account
$user_delete_sql = "DELETE FROM users WHERE id = $user_id";

if ($conn->query($user_delete_sql) === TRUE) {
    echo "Account Deleted !!!";
} else {
    echo "ERROR Deleting Account !!!" . $conn->error;
}

$conn->close();
?>