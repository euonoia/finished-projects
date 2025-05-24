<?php
include ("connect.php");

if (isset($_POST['quantity']) && is_numeric($_POST['quantity'])) {
    $quantity = intval($_POST['quantity']);
    
    // Get the current count of parking slots
    $result = $conn->query("SELECT COUNT(*) as slot_count FROM parking_slots");
    $row = $result->fetch_assoc();
    $current_count = $row['slot_count'];
    
    for ($i = 1; $i <= $quantity; $i++) {
        $new_slot_number = $current_count + $i;
        $sql = "INSERT INTO parking_slots (slotnumber, status) VALUES ('$new_slot_number', 'available')";
        if (!$conn->query($sql)) {
            echo "Error: " . $conn->error;
            exit;
        }
    }
    echo "Successfully added $quantity parking slots. The last slot number is $new_slot_number.";
    exit;
} else {
    echo "Invalid quantity.";
}
?>