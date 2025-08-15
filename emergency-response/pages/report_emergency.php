<?php
require_once '../db/config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$userId = $_SESSION['user_id'];
$type = $_POST['type'] ?? '';
$description = $_POST['description'] ?? '';
$latitude = $_POST['latitude'] ?? null;
$longitude = $_POST['longitude'] ?? null;
$imagePath = '';
$address = null;

// Handle optional image upload
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $targetDir = "../uploads/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
    $newFileName = "emergency_" . time() . "_" . uniqid() . "." . $imageFileType;
    $imagePath = $targetDir . $newFileName;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)) {
        $imagePath = str_replace("../", "", $imagePath); // Store relative path
    } else {
        echo json_encode(['success' => false, 'message' => 'Error uploading file']);
        exit();
    }
}

// Function to get address from lat/lng
function getAddressFromCoordinates($lat, $lng) {
    $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat=$lat&lon=$lng";

    $opts = [
        "http" => [
            "method" => "GET",
            "header" => "User-Agent: EmergencyReportApp/1.0\r\n"
        ]
    ];
    $context = stream_context_create($opts);
    $response = file_get_contents($url, false, $context);
    if ($response === FALSE) return null;

    $data = json_decode($response, true);
    return $data['display_name'] ?? null;
}

if ($latitude && $longitude) {
    $address = getAddressFromCoordinates($latitude, $longitude);
}

// Insert into DB
$stmt = $conn->prepare("INSERT INTO emergency_reports (user_id, type, description, image, latitude, longitude, location) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssdds", $userId, $type, $description, $imagePath, $latitude, $longitude, $address);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true, 'address' => $address]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>

