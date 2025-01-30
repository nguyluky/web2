<?php

include("../env.php");

// Create connection
$conn = new mysqli($mysql_url, $mysql_user_name, $mysql_password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>