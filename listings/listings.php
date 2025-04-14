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
    <!-- Modified search bar with form -->
    <div id="searchBar">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
            <label for="searchInput"></label>
            <input type="text" id="searchInput" name="search" placeholder="Search for items"
                   value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit" id="searchButton"></button>
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
    <div id="toggle">
        <label class="switch" id="toggleSwitch">
            <input type="checkbox">
            <span class="slider round"></span>
        </label>
        <div id="toggleText">
            Admin Mode(Temp)
        </div>
    </div>
    <div id="addListing">
        <button id="addListingButton">
            Create Listing
        </button>
    </div>
</div>
<div id="listings">

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

    // Check if search parameter exists
    $search_term = "";
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search_term = $conn->real_escape_string($_GET['search']);
        // SQL with search filter
        $sql = "SELECT id, name, user_id, description, price, created_at FROM listings 
                WHERE name LIKE '%$search_term%'";
    } else {
        // Default SQL to get all listings
        $sql = "SELECT id, name, user_id, description, price, created_at FROM listings";
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "
            <div class='item_box' id='" . $row["id"] . "'>
                <div class='item-image'>
                    <img src=\"https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ3vrTUU3CKbUDThpm8aZzFXdTmai6PodNfXA&s\" alt=\"Item \">
                </div>
                <div class='itemDescription'>
                    " . htmlspecialchars($row["name"]) . "
                </div>
                <div class='item-price'>
                  $" . htmlspecialchars($row["price"]) . "
                </div>
                <button class='edit-button' >
                <img src='edit.png' alt='Edit' class='admin-edit-icon'>
                </button>
                <button class='delete-button'>
                <img src='delete.png' alt='Edit' class='admin-delete-icon'>
                </button>
            </div>";
        }
    } else {
        if (!empty($search_term)) {
            echo "<div class='no-results'>No listings found matching '$search_term'</div>";
        } else {
            echo "<div class='no-results'>No listings available</div>";
        }
    }
    $conn->close();
    ?>
</div>

<div id="createListingModal" class="modal">
    <!-- Modal content remains the same -->
    <div class="modal-content">
        <div class="modal-header">
            <div class="logo">Create Listing</div>
            <button class="close-btn" onclick="closeCreateListing()">&times;</button>
        </div>

        <form id="createForm">
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
                <label>Upload Images</label>
                <div class="image-upload">
                    Click to upload images
                    <input type="file" style="display:none;" multiple accept="image/*">
                </div>
            </div>
            <button name="SubmitButton" type="submit" class="submit-btn">Create Listing</button>
        </form>
    </div>
</div>
<div id="editListingModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="logo">Edit Listing</div>
            <button class="close-btn" onclick="closeEditListing()">&times;</button>
        </div>

        <form id="editForm">
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
                <label>Upload Images</label>
                <div class="image-upload">
                    Click to upload images
                    <input type="file" style="display:none;" multiple accept="image/*">
                </div>
            </div>
            <button name="SubmitButton" type="submit" class="submit-btn">Update Listing</button>
        </form>
    </div>
</div>

</body>
</html>
