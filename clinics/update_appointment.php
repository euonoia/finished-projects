<?php
// filepath: c:\xampp\htdocs\clinics\update_appointment.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("connect.php");
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(['success' => false, 'error' => 'No input data received']);
    exit;
}

// Handle comment insert FIRST
if (isset($data['action']) && $data['action'] === 'add_comment' && isset($data['id'], $data['comment'])) {
    $comment = $data['comment'];
    $id = $data['id'];
    try {
        $sql = "UPDATE appointments SET comments=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            echo json_encode(['success' => false, 'error' => $conn->error]);
            exit;
        }
        $stmt->bind_param("si", $comment, $id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

if (!isset($data['id'], $data['name'], $data['email'], $data['phone'], $data['date'])) {
    echo json_encode(['success' => false, 'error' => 'Missing fields']);
    exit;
}

$doctor_id = isset($data['doctor_id']) && $data['doctor_id'] !== '' ? $data['doctor_id'] : null;
$diagnosis = isset($data['diagnosis']) ? $data['diagnosis'] : null;

try {
    $sql = "UPDATE appointments SET name=?, email=?, phone=?, date=?, doctor_id=?, diagnosis=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", 
        $data['name'],
        $data['email'],
        $data['phone'],
        $data['date'],
        $doctor_id,
        $diagnosis,
        $data['id']
    );
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>