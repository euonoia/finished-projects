<?php
// Include database connection
include 'connection.php';

// Check if the patient ID is provided
if (isset($_GET['id'])) {
    $id =$_GET['id']    ; // Sanitize input

    // Prepare the DELETE query
    $sql = "DELETE FROM patients WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    // Execute the query
    if ($stmt->execute()) {
        echo "Patient record deleted successfully.";
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "No patient ID provided.";
}
?>