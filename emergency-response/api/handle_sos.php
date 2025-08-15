<?php
// Prevents any accidental output
ob_start();
header('Content-Type: application/json');
require_once '../includes/functions.inc.php';
require_once '../db/config.php';

session_start();

// Make sure nothing has been outputted
ob_end_clean(); 

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$userId = $_SESSION['user_id'] ?? null;
$latitude = $data['latitude'] ?? null;
$longitude = $data['longitude'] ?? null;

if (!$latitude || !$longitude) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Location data required']);
    exit;
}

try {
    // Ensure no output interference
    ob_start();

    // Insert incident
    $stmt = $conn->prepare("INSERT INTO incidents (user_id, latitude, longitude) VALUES (?, ?, ?)");
    $stmt->bind_param("idd", $userId, $latitude, $longitude);
    $stmt->execute();

    ob_end_clean(); // Clear unwanted output

    echo json_encode(['success' => true, 'message' => 'Emergency alert processed']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to process emergency: ' . $e->getMessage()]);
}

// ❌ REMOVE this duplicate block of code
// $data = json_decode(file_get_contents('php://input'), true);
// if (isset($data['latitude']) && isset($data['longitude'])) {
//     echo json_encode(["status" => "success", "message" => "SOS received"]);
// } else {
//     echo json_encode(["status" => "error", "message" => "Invalid data"]);
// }
?>

