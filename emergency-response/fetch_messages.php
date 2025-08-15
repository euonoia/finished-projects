<?php
include("db/config.php");

// Validate and sanitize inputs
session_start();
if (!isset($_POST['from_user_id']) || !isset($_POST['to_user_id']) || !isset($_POST['message_text'])) {
    die(json_encode(['error' => 'Missing required parameters']));
}

$from_user_id = mysqli_real_escape_string($connection, $_POST['from_user_id']);
$to_user_id = mysqli_real_escape_string($connection, $_POST['to_user_id']);
$message_text = mysqli_real_escape_string($connection, $_POST['message_text']);

// Insert new message using prepared statement
$insert_stmt = $connection->prepare("INSERT INTO mossiges (from_user_id, to_user_id, message_text) VALUES (?, ?, ?)");
$insert_stmt->bind_param("iis", $from_user_id, $to_user_id, $message_text);
$insert_stmt->execute();

// Fetch messages using prepared statement
$select_stmt = $connection->prepare("SELECT * FROM messages WHERE (to_user_id = ? OR from_user_id = ?) ORDER BY timestamp DESC");
$select_stmt->bind_param("ii", $to_user_id, $to_user_id);
$select_stmt->execute();
$result = $select_stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $message = htmlspecialchars($row['message_text'], ENT_QUOTES, 'UTF-8');
    echo '<div class="message">' . $message . '</div>';
}
