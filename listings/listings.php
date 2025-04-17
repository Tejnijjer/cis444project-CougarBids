<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "4702";
$dbname = "listings";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$isAdmin = false;
$user_id = isset($_SESSION['userID']) ? intval($_SESSION['userID']) : 0;

if ($user_id > 0) {
    $admin_check = $conn->query("SELECT isAdmin FROM users WHERE id = $user_id LIMIT 1");
    if ($admin_check && $admin_check->num_rows > 0) {
        $row = $admin_check->fetch_assoc();
        $isAdmin = $row['isAdmin'] == 1;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <title>CougarBids</title>
    <script src="script.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
<div id="topBar">
    <div id="searchBar">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET" id="searchForm">
            <div class="search-container">
                <input type="text" id="searchInput" name="search" placeholder="Search for items"
                       value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit" id="searchButton"></button>
            </div>

            <div class="category-container">
                <label for="categoryFilter">Filter by Category:</label>
                <select name="categoryFilter" id="categoryFilter" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    <option value="electronics" <?php if(isset($_GET['categoryFilter']) && $_GET['categoryFilter'] == 'electronics') echo 'selected'; ?>>Electronics</option>
                    <option value="clothing" <?php if(isset($_GET['categoryFilter']) && $_GET['categoryFilter'] == 'clothing') echo 'selected'; ?>>Clothing</option>
                    <option value="books" <?php if(isset($_GET['categoryFilter']) && $_GET['categoryFilter'] == 'books') echo 'selected'; ?>>Books</option>
                    <option value="furniture" <?php if(isset($_GET['categoryFilter']) && $_GET['categoryFilter'] == 'furniture') echo 'selected'; ?>>Furniture</option>
                    <option value="other" <?php if(isset($_GET['categoryFilter']) && $_GET['categoryFilter'] == 'other') echo 'selected'; ?>>Other</option>
                </select>
            </div>
        </form>
    </div>

    <div id="logo">
        <button id="logoButton"></button>
        CougarBids
    </div>
    <div id="profile">
        <button id="profileButton"></button>
        <img src="profile.png" alt="profile" width="50" height="50">
    </div>
    <div id="search">
        <hr id="top_line">
    </div>
    <div id="addListing">
        <button id="addListingButton">
            Create Listing
        </button>

    </div>
    <?php if ($isAdmin): ?>
        <button onclick="window.location.href='../adminAccountsPage/accountList.php'" id="accountListButton">
            Manage Accounts
        </button>
    <?php endif; ?>

</div>
<div id="listings">
    <?php
    $search_term = "";
    $category_filter = isset($_GET['categoryFilter']) ? $conn->real_escape_string($_GET['categoryFilter']) : "";

    $conditions = [];
    if (!empty($_GET['search'])) {
        $search_term = $conn->real_escape_string($_GET['search']);
        $conditions[] = "name LIKE '%$search_term%'";
    }
    if (!empty($category_filter)) {
        $conditions[] = "category = '$category_filter'";
    }

    $condition_sql = count($conditions) > 0 ? "WHERE " . implode(" AND ", $conditions) : "";

    $sql = "SELECT id, name, user_id, description, price, category, created_at FROM listings $condition_sql";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $listingId = $row["id"];

            $img_sql = "SELECT image_path FROM listing_images WHERE listing_id = '$listingId' ORDER BY display_order ASC LIMIT 1";
            $img_result = $conn->query($img_sql);
            $img_row = $img_result && $img_result->num_rows > 0 ? $img_result->fetch_assoc() : null;

            $img_src = $img_row ? $img_row["image_path"] : "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ3vrTUU3CKbUDThpm8aZzFXdTmai6PodNfXA&s";

            echo "
        <div class='item_box' id='" . $listingId . "' onclick='goToListing(" . $listingId . ")'>
            <div class='item-image'>
                <img src='" . htmlspecialchars($img_src) . "' alt='Item'>
            </div>
            <div class='itemDescription'>
                " . htmlspecialchars($row["name"]) . "
            </div>
            <div class='item-price'>
                $" . htmlspecialchars($row["price"]) . "
            </div>";

            if ($isAdmin) {
                echo "<button class='edit-button'>
                    <img src='edit.png' alt='Edit' class='admin-edit-icon'>
                  </button>
                  <button class='delete-button'>
                    <img src='delete.png' alt='Delete' class='admin-delete-icon'>
                  </button>";
            }

        echo "</div>";
    }
    } else {
        echo "<div class='no-results'>No listings " . (!empty($search_term) ? "found matching '$search_term'" : "available") . "</div>";
    }

    $conn->close();
    ?>
    <div id="createListingModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="logo">Create Listing</div>
            <button class="close-btn" onclick="closeCreateListing()">&times;</button>
        </div>

        <form id="createForm" enctype="multipart/form-data">
            <input type="hidden" name="folderName" value="listingImages">
            <input type="hidden" id="listingId" name="listingId">
            <div class="form-group">
                <label for="title">Listing Title</label>
                <input type="text" name="title" id="title" placeholder="Enter listing title" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="desc" id="description" placeholder="Describe your item..." required></textarea>
            </div>

            <div class="form-group">
                <label for="price">Price</label>
                <input name="price" type="number" id="price" placeholder="$0.00" step="0.01" min="0" required>
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <select name="cat" class="category-select" id="category">
                    <option value="">Select a category</option>
                    <option value="electronics">Electronics</option>
                    <option value="clothing">Clothing</option>
                    <option value="books">Books</option>
                    <option value="furniture">Furniture</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label>Upload Image</label>
                <div class="image-upload" id="imageUploadBox">
                    <p>Click or drag an image here</p>
                    <input type="file" id="imageInput" name="image" accept="image/*" style="display:none;">
                </div>
                <div id="imagePreviewContainer" class="image-preview-container"></div>
            </div>
            <button name="SubmitButton" type="submit" class="submit-btn">Create Listing</button>
        </form>
    </div>
</div>
<div id="editListingModal" class="modal">
    <input type="file" id="editImageInput" name="newImage" accept="image/*" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <div class="logo">Edit Listing</div>
            <button class="close-btn" onclick="closeEditListing()">&times;</button>
        </div>

        <form id="editForm" enctype="multipart/form-data">
            <input type="hidden" id="editListingId" name="id" value="">
            <div class="form-group">
                <label for="editTitle">Listing Title</label>
                <input type="text" name="title" id="editTitle" required>
            </div>

            <div class="form-group">
                <label for="editDescription">Description</label>
                <textarea name="desc" id="editDescription" required></textarea>
            </div>

            <div class="form-group">
                <label for="editPrice">Price</label>
                <input name="price" type="number" id="editPrice" step="0.01" min="0" required>
            </div>

            <div class="form-group">
                <label for="editCategory">Category</label>
                <select name="cat" class="category-select" id="editCategory">
                    <option value="">Select a category</option>
                    <option value="electronics">Electronics</option>
                    <option value="clothing">Clothing</option>
                    <option value="books">Books</option>
                    <option value="furniture">Furniture</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label>Upload Image</label>
                <div class="image-upload" id="editImageUploadBox">
                    <p>Click or drag an image here</p>
                    <input type="file" id="editImageInput" name="newImage" accept="image/*" style="display:none;">
                </div>
                <div id="editImagePreviewContainer" class="image-preview-container"></div>
            </div>
            <button name="SubmitButton" type="submit" class="submit-btn">Update Listing</button>
        </form>
    </div>
</div>

</body>
</html>
