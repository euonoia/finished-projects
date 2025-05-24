<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $condition = $_POST['condition'];
    $date = $_POST['date'];

    // Prepare the SQL statement
    $stmt = $conn->prepare("UPDATE patients SET firstName = ?, lastName = ?, age = ?, gender = ?, `condition` = ?, date = ? WHERE id = ?");
    $stmt->bind_param("ssisssi", $firstName, $lastName, $age, $gender, $condition, $date, $id);

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