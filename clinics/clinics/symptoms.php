<?php
    include ("connect.php");
    if(isset($_POST['symptoms'])){
        // Sanitize and capitalize the first letter of names
        $firstName = ucwords(htmlspecialchars($_POST["firstName"]));
        $lastName = ucwords(htmlspecialchars($_POST["lastName"]));
        $studentID = htmlspecialchars($_POST["studentID"]);
        $age = htmlspecialchars($_POST["age"]);
        $gender = htmlspecialchars($_POST["gender"]);
        $condition = htmlspecialchars($_POST["condition"]);
        $date = htmlspecialchars($_POST["date"]);

        $sql = "INSERT INTO `patients`(`firstName`, `lastName`,`studentID`, `age`, `gender`, `condition`, `date`)
                VALUES ('$firstName', '$lastName','$studentID', '$age', '$gender', '$condition', '$date')";
        if ($conn->query($sql) == TRUE) {
            header("Location: index.php");
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    $conn->close();
?>