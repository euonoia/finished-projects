<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "clinic";

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection error: " . $conn->connect_error);
}

return $conn;
?>