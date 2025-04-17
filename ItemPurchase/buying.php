<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "4702";
$dbname = "listings";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$isAdmin = false;
$user_id = $_SESSION['userID'] ?? 0;
if ($user_id) {
    $admin_check = $conn->query("SELECT isAdmin FROM users WHERE id = $user_id");
    if ($admin_check && $admin_check->num_rows > 0) {
        $isAdmin = $admin_check->fetch_assoc()['isAdmin'] == 1;
    }
}

$item_id = $_GET['id'] ?? 0;
$item_sql = "SELECT l.*, u.username FROM listings l JOIN users u ON l.user_id = u.id WHERE l.id = ?";
$stmt = $conn->prepare($item_sql);
$stmt->bind_param("i", $item_id);
$stmt->execute();
$item_result = $stmt->get_result();
$item = $item_result->fetch_assoc();

if (!$item) die("Item not found.");

$img_sql = "SELECT image_path FROM listing_images WHERE listing_id = $item_id ORDER BY display_order ASC";
$images_result = $conn->query($img_sql);
$images = [];
while ($row = $images_result->fetch_assoc()) {
    $images[] = $row['image_path'];
}
$main_img = $images[0] ?? 'https://via.placeholder.com/300';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CougarBids - Item Page</title>
    <link rel="stylesheet" href="../ItemPurchase/buying.css">
    <script src="../ItemPurchase/buying.js"></script>
</head>
<body>
<div id="topBar">
    <div id="logo">
        <button onclick="location.href='/cis444project-CougarBids/listings/listings.php'">CougarBids</button>
    </div>
    <div id="profile">
        <button onclick="location.href='settings.html'" id="profileButton"></button>
        <img src="profile.png" alt="profile" width="50" height="50">
    </div>
</div>

<div class="container">
    <div class="image-gallery">
        <div class="thumbnail-container">
            <?php foreach ($images as $img): ?>
                <img src="<?= htmlspecialchars($img) ?>" alt="Thumbnail" onclick="updateMainImage(this.src)">
            <?php endforeach; ?>
        </div>
        <div class="main-image-container">
            <img id="mainImage" src="<?= htmlspecialchars($main_img) ?>" alt="Main Image">
        </div>
    </div>

    <div class="item-details">
        <h1><?= htmlspecialchars($item['name']) ?></h1>
        <div class="seller-info">
            <p><?= nl2br(htmlspecialchars($item['description'])) ?></p>
            <p><strong>Seller: <?= htmlspecialchars($item['username']) ?></strong></p>
            <p>Category: <?= htmlspecialchars($item['category']) ?></p>
        </div>
        <div class="price-box">
            <p><strong>$<?= number_format($item['price'], 2) ?></strong></p>
        </div>

        <div class="buttons">
            <form action="buy_now.php" method="POST">
                <input type="hidden" name="item_id" value="<?= $item_id ?>">
                <button type="submit" class="buy">Buy Now</button>
            </form>
            <form action="make_offer.php" method="POST">
                <input type="hidden" name="item_id" value="<?= $item_id ?>">
                <button type="submit" class="offer">Offer</button>
            </form>
            <form action="add_to_cart.php" method="POST">
                <input type="hidden" name="item_id" value="<?= $item_id ?>">
                <button type="submit" class="add-to-cart">Add to Cart</button>
            </form>
        </div>
    </div>
</div>

<script>
    function updateMainImage(src) {
        document.getElementById('mainImage').src = src;
    }
</script>
</body>
</html>

