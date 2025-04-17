<?php
require 'db.php';

$user = $_POST['username'];
$pass = $_POST['password'];
$action = $_POST['action'];
$is_admin = isset($_POST['is_admin']) ? 1 : 0;

if ($action === "register") {
    // Register new user
    $hashed = password_hash($pass, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (username, password, is_admin) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $user, $hashed, $is_admin);
    
    if ($stmt->execute()) {
        echo "✅ Account created successfully! <a href='index.html'>Back to login</a>";
    } else {
        echo "❌ Error: " . $stmt->error;
    }
    $stmt->close();

} elseif ($action === "login") {
    // Login existing user
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($pass, $row['password'])) {
            echo $row['is_admin']
                ? "👑 Welcome, admin $user!"
                : "✅ Login successful. Welcome, $user!";
        } else {
            echo "❌ Invalid password.";
        }
    } else {
        echo "❌ User not found.";
    }
    $stmt->close();
}

$conn->close();
?>
