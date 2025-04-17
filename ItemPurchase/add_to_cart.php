session_start();
if (!isset($_SESSION['userID'])) {
    header("Location: /login.php");
    exit;
}

$user_id = $_SESSION['userID'];
$item_id = $_POST['item_id'];

$conn = new mysqli("localhost", "root", "4702", "listings");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Check if already in cart
$check_sql = "SELECT * FROM cart WHERE user_id = ? AND listing_id = ?";
$stmt = $conn->prepare($check_sql);
$stmt->bind_param("ii", $user_id, $item_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Add to cart
    $insert_sql = "INSERT INTO cart (user_id, listing_id) VALUES (?, ?)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("ii", $user_id, $item_id);
    $stmt->execute();
    $msg = "Item added to cart!";
} else {
    $msg = "Item is already in your cart.";
}

$conn->close();
header("Location: /item.php?id=$item_id&msg=" . urlencode($msg));
exit;
