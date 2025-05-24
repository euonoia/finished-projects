<?php 

include 'connect.php';

if(isset($_POST['signUp'])){
    $firstName = $_POST['fName'];
    $lastName = $_POST['lName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password = md5($password);
  
    // Check if email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        echo "Email Address Already Exists!";
    } else {
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $firstName, $lastName, $email, $password);
        if($stmt->execute()){
            header("location: authenticate.php");
        } else {
            echo "Error: " . $stmt->error;
        }
    }
    $stmt->close();
}

if(isset($_POST['signIn'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password = md5($password);

    // Check user credentials
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        session_start();
        $row = $result->fetch_assoc();
        $id = $row['id'];
        $stored_password = $row['password'];

        if ($password === $stored_password) {
            // Insert login history
            $insertStmt = $conn->prepare("INSERT INTO user_history (firstname, id, login_time) VALUES (?, ?, NOW())");
            $insertStmt->bind_param("si", $row['firstname'], $id);
            $insertStmt->execute();
            $_SESSION['email'] = $row['email'];
            header("Location: /parking/dashboard.php");
            exit();
    } else {
        echo "Not Found, Incorrect Email or Password";
    }
    $stmt->close();
}};
?>