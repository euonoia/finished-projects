
<?php
include("connect.php");
include("config.php");

// Kunin ang data mula sa AJAX request
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['appointmentId']) && isset($data['doctorId'])) {
    $appointmentId = $data['appointmentId'];
    $doctorId = $data['doctorId'];

    // I-update ang doctor_id sa appointments table
    $sql = "UPDATE appointments SET doctor_id = :doctorId WHERE id = :appointmentId";
    $query = $dbh->prepare($sql);
    $query->bindParam(':doctorId', $doctorId, PDO::PARAM_INT);
    $query->bindParam(':appointmentId', $appointmentId, PDO::PARAM_INT);

    if ($query->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
}
?>