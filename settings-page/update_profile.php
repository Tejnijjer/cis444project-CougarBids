
<?php

session_start();
include 'db_connect.php';

// test user ID
$user_id = $_SESSION['userID'] ?? 1;

$name = $conn->real_escape_string($_POST['name']);
$email = $conn->real_escape_string($_POST['email']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$credit_card = $conn->real_escape_string($_POST['credit_card']);
$address = $conn->real_escape_string($_POST['address']);
$card_name = $conn->real_escape_string($_POST['card_name']);

// update user info
$user_sql = "UPDATE users 
             SET username = '$name', email = '$email', password = '$password' 
             WHERE id = $user_id";

if ($conn->query($user_sql) !== TRUE) {
    die("Error updating user: " . $conn->error);
}

// billing record
$check_sql = "SELECT id 
              FROM billing_info 
              WHERE user_id = $user_id";

$result = $conn->query($check_sql);

// [ IF ] update billing ---- [ ELSE ] new billing info
if ($result->num_rows > 0) {
    $billing_sql = "UPDATE billing_info
                    SET card_number_hash = '" .md5($credit_card) . "', billing_address = '$address', card_name = '$card_name'
                    WHERE user_id = $user_id";
} else {
    $billing_sql = "INSERT INTO billing_info (user_id, card_number_hash, billing_address, card_name)
                    VALUES ($user_id, '" . md5($credit_card) . "', '$address', '$card_name')";
}

if ($conn->query($billing_sql) !== TRUE) {
    die("ERROR updating billing info: " . $conn->error);
}

$conn->close();
echo "Profile Updated !!!";
?>