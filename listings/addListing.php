<?php
//check if form was submitted
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

    $sql = "INSERT INTO listings (name, user_id, description,price,category)
VALUES ('".$_POST["title"]."', '1','".$_POST["desc"]."','".$_POST["price"]."','".$_POST["cat"]."')";

    if ($conn->query($sql) === TRUE) {
//echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
?>
