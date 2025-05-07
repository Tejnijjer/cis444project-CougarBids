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
    <div id="logo">
        <button id="logoButton" onclick=goToHome()></button>
        CougarBids
    </div>
    <div id="adminText">
         Admin Panel
    </div>
    <div id="search">
        <hr id="top_line">
    </div>
    <button onclick="window.location.href='../listings/listings.php'" id="backButton">
        Go Back
    </button>
</div>
<table id="table">
    <tr>
        <th>User ID</th>
        <th>Name</th>

        <th>Admin</th>
    </tr>
<?php

$servername = "localhost";
$username = "root";
$password = "4702";
$dbname = "listings";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


    $sql = "SELECT id,username, password, isAdmin FROM users";

$result = $conn->query($sql);

if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {
        echo "
            <div class='userTable'>
                 
                    <tr>
                        <td>" . $row["id"] . "</td>
                        <td>" . htmlspecialchars($row["username"]) . "</td>
                      
                        <td>" . ($row["isAdmin"] ? "Yes" : "No") . "</td>
                        <td> <button class='delete_account_button' id=" . $row["id"] . ">Delete</button></td>
                        <td>
                            <button class='edit_account_button' 
                            data-id='" . $row["id"] . "'
                            data-username='" . htmlspecialchars($row["username"]) . "'
                            data-admin='" . $row["isAdmin"] . "'>
                            Edit
                            </button>
</td>

                    </tr>
                
            </div>";
    }
} else {

        echo "<div class='no-results'>No users available</div>";

}
$conn->close();

?>
</table>
<div id="editAccountModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="logo">Edit Account</div>
            <button class="close-btn" onclick="closeEditAccount()">&times;</button>
        </div>

        <form id="editAccountForm">
            <input type="hidden" id="editUserId" name="id">

            <div class="form-group">
                <label for="editUsername">Username</label>
                <input type="text" name="username" id="editUsername" placeholder="Enter username" required>
            </div>

            <div class="form-group">
                <label for="editIsAdmin">Admin Status</label>
                <select name="isAdmin" class="category-select" id="editIsAdmin" required>
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
            </div>

            <button type="submit" class="submit-btn">Save Changes</button>
        </form>
    </div>
</div>

</body>

</html>
