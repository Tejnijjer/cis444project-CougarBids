 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CougarBids - CSUSM Student Marketplace</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="/cis444project-CougarBids/listings/style.css">
</head>
<body>
    <header class="main-header">
        <div class="logo-container">
            <h1 class="logo">Cougar<span>Bids</span></h1>
            <p class="logo-subtitle">CSUSM Student Marketplace</p>
            <button class="logo-button" onclick="window.location.href='/cis444project-CougarBids/listings/listings.php'"></button>
        </div>
        
        <div class="profile-section">
            <div class="profile-dropdown">
                <button class="profile-toggle">
                    <img src="user.png" alt="Profile" class="profile-pic">
                    <span class="profile-name">
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

                        $sql = "SELECT username FROM users WHERE id = '".$_SESSION["userID"]."'";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo $row["username"];
                            }
                        }
                        ?>

                    </span>
                    <i class="dropdown-arrow">▼</i>
                </button>
                <div class="dropdown-menu">
                    <a href="#" class="dropdown-item">Edit Profile</a>
                    <a href="/cis444project-CougarBids/settings-page/settings.html" class="dropdown-item">Account Settings</a>
                    <a href="/cis444project-CougarBids/login/login.html" class="dropdown-item">Sign Out</a>
                </div>
            </div>
        </div>
    </header>

    <div class="tabs">
        <button class="tab-button active" onclick="showTab('listed')">My Items</button>
        <button class="tab-button" onclick="showTab('liked')">Saved Items</button>
    </div>

    <div id="listed" class="tab-content active">
        <h2>Items I'm Selling</h2>
        <div class="items">
            <?php
            $sql = "SELECT * FROM listings WHERE user_id = '".$_SESSION["userID"]."'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {

                    $img_sql = "SELECT image_path FROM listing_images WHERE listing_id = " . htmlspecialchars($row["id"]) . " ORDER BY display_order LIMIT 1";
                    $img_result = $conn->query($img_sql);
                    $img_row = $img_result && $img_result->num_rows > 0 ? $img_result->fetch_assoc() : null;

                    $img_src = $img_row ? '../listings/'.$img_row["image_path"] : "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ3vrTUU3CKbUDThpm8aZzFXdTmai6PodNfXA&s";

                    echo "<div class='item' id=" . htmlspecialchars($row["id"]) . ">
                <h3>" . htmlspecialchars($row["name"]) . " </h3>
                <p>$" . htmlspecialchars($row["price"]) . "</p>
                <p>" . htmlspecialchars($row["description"]) . "</p>
                 <div class='item-image'>
                <img src='" . htmlspecialchars($img_src) . "' alt='Item'>
            </div>
                <button class='edit_button'>
                    <img src='edit.png' alt='Edit' class='edit_icon'>
                  </button>
                  <button class='delete_button'>
                    <img src='delete.png' alt='Delete' class='delete_icon'>
                  </button>
                  </div>
";
                }
            }
            ?>
        </div>

    </div>

    <div id="liked" class="tab-content">
        <h2>Saved Items</h2>
        <div class="items">
            <?php

            $sql = "SELECT listing_id FROM favorites WHERE user_id = '".$_SESSION["userID"]."'";
            $result = $conn->query($sql);
            foreach ($result as $row) {
                $listing_id = $row["listing_id"];
                $img_sql = "SELECT image_path FROM listing_images WHERE listing_id = '$listing_id' ORDER BY display_order ASC LIMIT 1";
                $img_result = $conn->query($img_sql);
                $img_row = $img_result && $img_result->num_rows > 0 ? $img_result->fetch_assoc() : null;

                $img_src = $img_row ? $img_row["image_path"] : "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ3vrTUU3CKbUDThpm8aZzFXdTmai6PodNfXA&s";
                $sql2 = "SELECT * FROM listings WHERE id = '$listing_id'";
                $result2 = $conn->query($sql2);
                if ($result2->num_rows > 0) {
                    while ($row2 = $result2->fetch_assoc()) {
                        echo "<div class='item'>
                <h3>" . htmlspecialchars($row2["name"]) . " </h3>
                <p>$" . htmlspecialchars($row2["price"]) . "</p>
                <p>" . htmlspecialchars($row2["description"]) . "</p>
            
";
                    }
                }
            }
            ?>
        </div>
    </div>

    <script src="script.js"></script>
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