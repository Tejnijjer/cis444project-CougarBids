<?php
// get_listing_details.php - Script to fetch listing details for editing

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

// Check if ID is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $listing_id = $conn->real_escape_string($_GET['id']);

    // SQL to get the listing details
    $sql = "SELECT id, name, user_id, description, price, created_at FROM listings WHERE id = $listing_id";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // Get the listing data
        $listing = $result->fetch_assoc();
        echo json_encode(['success' => true, 'listing' => $listing]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Listing not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid listing ID']);
}

$conn->close();
