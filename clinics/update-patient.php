<?php
include("connect.php");
include("config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $condition = $_POST['condition'];
    $date = $_POST['date'];
    $doctor_id = $_POST['doctor_id'];

    // Prepare the SQL statement
    $stmt = $conn->prepare("UPDATE patients SET firstName = ?, lastName = ?, age = ?, gender = ?, `condition` = ?, date = ?, doctor_id = ? WHERE id = ?");
    $stmt->bind_param("ssisssii", $firstName, $lastName, $age, $gender, $condition, $date, $doctor_id, $id);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Patient record updated successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>