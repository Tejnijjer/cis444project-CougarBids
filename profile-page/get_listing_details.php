<?php



$servername = "localhost";
$username = "root";
$password = "4702";
$dbname = "listings";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]));
}


if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $listing_id = $conn->real_escape_string($_GET['id']);


    $sql = "SELECT id, name, user_id, description, price, category, created_at FROM listings WHERE id = $listing_id";

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $listing = $result->fetch_assoc();


        $img_sql = "SELECT id, image_path, caption FROM listing_images 
                WHERE listing_id = $listing_id ORDER BY display_order ASC LIMIT 1";
        $img_result = $conn->query($img_sql);
        $image = null;

        if ($img_result && $img_result->num_rows > 0) {
            $image = $img_result->fetch_assoc();
        }

        $listing['image'] = $image;

        echo json_encode(['success' => true, 'listing' => $listing]);
    }


} else {
    echo json_encode(['success' => false, 'message' => 'Invalid listing ID']);
}

$conn->close();
