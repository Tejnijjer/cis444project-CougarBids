<?php

$servername = "localhost";
$username = "root";
$password = "4702";
$dbname = "listings";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]));
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $conn->real_escape_string($_POST['id']);
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['desc']);
    $price = $conn->real_escape_string($_POST['price']);
    $category = isset($_POST['cat']) ? $conn->real_escape_string($_POST['cat']) : '';
    $caption = isset($_POST['imageCaption']) ? $conn->real_escape_string($_POST['imageCaption']) : '';

    $sql = "UPDATE listings SET 
                name = '$title', 
                description = '$description', 
                price = '$price', 
                category = '$category',
                created_at = NOW()
            WHERE id = $id";

    if (!$conn->query($sql)) {
        echo json_encode(['success' => false, 'message' => 'Error updating listing: ' . $conn->error]);
        $conn->close();
        exit;
    }

    if (isset($_FILES['newImage']) && $_FILES['newImage']['error'] === UPLOAD_ERR_OK) {
        $img_result = $conn->query("SELECT id, image_path FROM listing_images WHERE listing_id = $id LIMIT 1");
        if ($img_result && $img_result->num_rows > 0) {
            $img_row = $img_result->fetch_assoc();
            $existingImageId = $img_row['id'];
            $existingImagePath = '../listings/'.$img_row['image_path'];

            if (file_exists($existingImagePath)) {
                unlink($existingImagePath);
            }

            $conn->query("DELETE FROM listing_images WHERE id = $existingImageId");
        }

        $uploadDir = "'../listings/listingImages/listing_$id/";
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = time() . '_' . basename($_FILES['newImage']['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['newImage']['tmp_name'], $targetPath)) {
            $caption = isset($_POST['imageCaption']) ? $conn->real_escape_string($_POST['imageCaption']) : '';
            $conn->query("INSERT INTO listing_images (listing_id, image_path, caption, display_order) 
                      VALUES ($id, '$targetPath', '$caption', 0)");
        }
    }

    echo json_encode(['success' => true, 'message' => 'Listing updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid data submitted']);
}

$conn->close();

