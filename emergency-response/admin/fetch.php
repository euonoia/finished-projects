<?php
include("db/config.php");
// Fetch the latest sender IDs
$sql = "SELECT * FROM messsages ORDER BY id DESC LIMIT 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

// Return the latest sender ID in JSON format
echo json_encode($row);
