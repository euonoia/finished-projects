<?php
require_once '../db/config.php';
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Prioritize user_id over admin_id to ensure correct sender
$sender_id = $_SESSION['user_id'] ?? null;
$receiver_id = $_POST['receiver_id'] ?? null;
$message = trim($_POST['message'] ?? '');

if (!$sender_id || !$receiver_id || $message === '') {
    http_response_code(400);
    echo "Missing fields or not logged in.";
    exit;
}

$stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
if (!$stmt) {
    http_response_code(500);
    echo "SQL prepare failed.";
    exit;
}
$stmt->bind_param("iis", $sender_id, $receiver_id, $message);
if ($stmt->execute()) {
    echo "Message sent";
} else {
    http_response_code(500);
    echo "Message failed to send.";
}
