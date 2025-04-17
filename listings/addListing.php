<?php
// File: addListing.php - Handles new listing creation with single image upload

$servername = "localhost";
$username = "root";
$password = "4702";
$dbname = "listings";

// Create DB connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]));
}

$response = ['success' => false, 'message' => 'An error occurred'];

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize input
    $title = isset($_POST['title']) ? $conn->real_escape_string($_POST['title']) : '';
    $description = isset($_POST['desc']) ? $conn->real_escape_string($_POST['desc']) : '';
    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
    $category = isset($_POST['cat']) ? $conn->real_escape_string($_POST['cat']) : '';

    // Example: Replace this with actual logged-in user ID later
    $user_id = 1;

    // Insert into listings table
    $sql = "INSERT INTO listings (name, user_id, description, price, category, created_at)
            VALUES ('$title', '$user_id', '$description', '$price', '$category', NOW())";

    if ($conn->query($sql) === TRUE) {
        $new_listing_id = $conn->insert_id;

        // === Image Handling ===
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = "listingImages/listing_" . $new_listing_id . "/";

            // Create directory if it doesn't exist
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            // Generate unique file name
            $original_name = basename($_FILES['image']['name']);
            $extension = pathinfo($original_name, PATHINFO_EXTENSION);
            $file_name = "img_" . time() . "." . $extension;
            $target_file = $upload_dir . $file_name;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $caption = isset($_POST['imageMeta_0']) ? $conn->real_escape_string($_POST['imageMeta_0']) : '';

                $img_sql = "INSERT INTO listing_images (listing_id, image_path, caption, display_order)
                            VALUES ('$new_listing_id', '$target_file', '$caption', 0)";
                $conn->query($img_sql);
            }
        }

        $response = [
            'success' => true,
            'message' => 'Listing created successfully',
            'listing_id' => $new_listing_id
        ];
    } else {
        $response = ['success' => false, 'message' => "Database error: " . $conn->error];
    }
} else {
    $response = ['success' => false, 'message' => 'Invalid request method'];
}

// Close DB connection and return JSON
$conn->close();
header('Content-Type: application/json');
echo json_encode($response);
?>
