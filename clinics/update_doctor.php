<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
include("connect.php");

$data = json_decode(file_get_contents("php://input"), true);

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($data['appointmentId'], $data['doctorId'])
) {
    $conn = new mysqli('localhost', 'root', '', 'clinic');
    if ($conn->connect_error) {
        echo json_encode(['success' => false, 'error' => 'DB connection error']);
        exit;
    }
    $id = intval($data['appointmentId']);
    $doctor = intval($data['doctorId']);
    $sql = "UPDATE appointments SET doctor_id=$doctor WHERE id=$id";
    if ($conn->query($sql)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
    exit;
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit;
}
?>