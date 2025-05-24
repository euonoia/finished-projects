<?php
include 'db/connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $subscriptionType = $_POST['subscriptionType'];
  
    // Prepare the SQL statement
    $stmt = $conn->prepare("UPDATE customer SET subscriptionType = ? WHERE id = ?");
    $stmt->bind_param("si", $subscriptionType, $id);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Subscription type updated successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>