<?php
// delete-appointment.php
include 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['id'])) {
        $id = intval($data['id']);
        $sql = "DELETE FROM appointments WHERE id = ?";
        $stmt = $dbh->prepare($sql);
        if ($stmt->execute([$id])) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'DB error']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'No ID provided']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
