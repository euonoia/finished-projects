<?php
require_once '../db/config.php';
require_once '../includes/functions.inc.php';
session_start();

header('Content-Type: application/json');

if (!isAdmin()) {
    echo json_encode([]);
    exit;
}

$adminId = $_SESSION['admin_id'];

$stmt = $conn->prepare("SELECT id, sender_id, message, created_at FROM messages WHERE receiver_id = ? AND sender_id != ? ORDER BY id DESC LIMIT 1");
$stmt->bind_param("ii", $adminId, $adminId);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode($row);
} else {
    echo json_encode([]);
}
