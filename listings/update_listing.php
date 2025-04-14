<?php
// update_listing.php - Script to update listing details

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "4702";
$dbname = "listings";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]));
}

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    // Get and sanitize form data
    $id = $conn->real_escape_string($_POST['id']);
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['desc']);
    $price = $conn->real_escape_string($_POST['price']);
    $category = isset($_POST['cat']) ? $conn->real_escape_string($_POST['cat']) : '';

    // Update the listing in the database
    $sql = "UPDATE listings SET 
            name = '$title', 
            description = '$description', 
            price = '$price'";

    // Add category if your database has this column
    // If not, you can modify your database to add this column
    // $sql .= ", category = '$category'";

    $sql .= " WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true, 'message' => 'Listing updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating listing: ' . $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid data submitted']);
}

$conn->close();
?>
