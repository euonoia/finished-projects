<?php
// Include database connection
include 'config.php';

// Check if the patient ID is provided
if (isset($_POST['id'])) {
    $id = intval($_POST['id']); // Sanitize input
    $sql = "DELETE FROM patients WHERE id = ?";
    $stmt = $dbh->prepare($sql);
    if ($stmt->execute([$id])) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "invalid";
}
?>