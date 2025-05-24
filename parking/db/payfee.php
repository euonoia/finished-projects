<?php
include('connect.php'); 

if(isset($_POST['pay'])) {
    $amount = htmlspecialchars($_POST['amount']);
    // Insert query
    $sql = "INSERT INTO amount VALUES ('$amount')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("d", $amount);

    if ($stmt->execute()) {
        echo "Amount inserted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
