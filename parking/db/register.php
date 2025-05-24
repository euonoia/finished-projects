<?php
    include('connect.php');
    if(isset($_POST['register'])) {
        $studentNumber = htmlspecialchars($_POST['studentNumber']);
        $firstname = htmlspecialchars($_POST['firstname']);
        $lastname = htmlspecialchars($_POST['lastname']);
        $vehicleType = htmlspecialchars($_POST['vehicleType']);
        $plate = htmlspecialchars($_POST['plate']);
        $customerType = htmlspecialchars($_POST['customerType']);
        $subscriptionType = htmlspecialchars($_POST['subscriptionType']);
        $contact = htmlspecialchars($_POST['contact']);
    
    
        $sql = "INSERT INTO `customer`(`studentNumber`,`firstname`, `lastname`, `vehicleType`, `plate`, `customerType`, `subscriptionType`, `contact`) 
                VALUES ('$studentNumber','$firstname', '$lastname', '$vehicleType', '$plate', '$customerType', '$subscriptionType', '$contact')";
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
            header("Location: /parking/index.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    $conn->close();
    ?>