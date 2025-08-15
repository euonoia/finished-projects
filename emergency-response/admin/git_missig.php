<?php
require_once '../db/config.php';
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

$user_id = $_SESSION['user_id'] ?? $_SESSION['admin_id'] ?? null;
$contact_id = isset($_GET['contact_id']) ? intval($_GET['contact_id']) : 0;

if (!$user_id || !$contact_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
    exit;
}

$stmt = $conn->prepare("
    SELECT * FROM messages 
    WHERE (sender_id = ? AND receiver_id = ?) 
       OR (sender_id = ? AND receiver_id = ?)
    ORDER BY sent_at ASC
");

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'SQL error']);
    exit;
}

$stmt->bind_param("iiii", $user_id, $contact_id, $contact_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

header('Content-Type: application/json');
echo json_encode($messages);
