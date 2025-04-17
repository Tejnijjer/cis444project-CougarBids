<?php
session_start();
if (!isset($_SESSION['userID'])) {
    header("Location: /login.php");
    exit;
}

$item_id = $_POST['item_id'];
$user_id = $_SESSION['userID'];

// Redirect to a dummy "purchase" page for now
header("Location: /purchase_confirm.php?item_id=$item_id");
exit;
