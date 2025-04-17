<?php
$servername = "localhost";
$username = "root";
$password = "4702";
$dbname = "listings";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sanitize input
$listingId = isset($_POST["id"]) ? intval($_POST["id"]) : 0;
if ($listingId <= 0) {
    die("Invalid ID");
}

// 1. Get all image paths associated with the listing
$imageQuery = "SELECT image_path FROM listing_images WHERE listing_id = $listingId";
$imageResult = $conn->query($imageQuery);

if ($imageResult && $imageResult->num_rows > 0) {
    while ($row = $imageResult->fetch_assoc()) {
        $imagePath = $row['image_path'];
        if (file_exists($imagePath)) {
            unlink($imagePath); // Delete each image file
        }
    }
}

// 2. Delete the folder (if empty)
$folderPath = "listingImages/listing_$listingId/";
if (is_dir($folderPath)) {
    @rmdir($folderPath); // Try to remove the folder
}

// 3. Delete from listing_images table
$conn->query("DELETE FROM listing_images WHERE listing_id = $listingId");

// 4. Delete from listings table
if ($conn->query("DELETE FROM listings WHERE id = $listingId") === TRUE) {
    echo "Listing deleted successfully.";
} else {
    echo "Error deleting listing: " . $conn->error;
}

$conn->close();
?>
