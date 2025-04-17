<?php
$servername = "localhost";
$username = "root";
$password = "4702";
$dbname = "listings";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$listingId = isset($_POST["id"]) ? intval($_POST["id"]) : 0;
if ($listingId <= 0) {
    die("Invalid ID");
}

$imageQuery = "SELECT image_path FROM listing_images WHERE listing_id = $listingId";
$imageResult = $conn->query($imageQuery);

if ($imageResult && $imageResult->num_rows > 0) {
    while ($row = $imageResult->fetch_assoc()) {
        $imagePath = $row['image_path'];
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }
}

$folderPath = "listingImages/listing_$listingId/";
if (is_dir($folderPath)) {
    @rmdir($folderPath);
}

$conn->query("DELETE FROM listing_images WHERE listing_id = $listingId");

if ($conn->query("DELETE FROM listings WHERE id = $listingId") === TRUE) {
    echo "Listing deleted successfully.";
} else {
    echo "Error deleting listing: " . $conn->error;
}

$conn->close();

