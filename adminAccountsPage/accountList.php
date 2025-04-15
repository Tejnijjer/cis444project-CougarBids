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
</div>
<table id="table">
    <tr>
        <th>User ID</th>
        <th>Name</th>
        <th>Password</th>
        <th>Admin</th>
    </tr>
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
    // Default SQL to get all listings
    $sql = "SELECT id, name, id, name, password, isAdmin FROM users";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "
            <div class='userTable'>
                 
                    <tr>
                        <td>" . $row["id"] . "</td>
                        <td>" . htmlspecialchars($row["name"]) . "</td>
                        <td>" . htmlspecialchars($row["password"]) . "</td>
                        <td>" . ($row["isAdmin"] ? "Yes" : "No") . "</td>
                        <td> <button class='delete_account_button' id=" . $row["id"] . ">Delete</button></td>
                    </tr>
                
            </div>";
    }
} else {

        echo "<div class='no-results'>No users available</div>";

}
$conn->close();

?>
</table>
</body>
</html>
