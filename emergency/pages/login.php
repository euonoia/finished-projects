<?php
require_once '../includes/functions.inc.php';
require_once '../db/config.php';

session_start();

if (isLoggedIn()) {
    redirect('dashboard.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (verifyPassword($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            redirect('dashboard.php');
        } else {
            $error = "Invalid username or password";
        }
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="login">
        <form method="POST">
            <div class="container">
                <h1 class="form-title">Login</h1>
                <div class="form-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" placeholder="Username" required>
                    <label for="username">Username</label>
                </div>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" required>
                    <label for="password">Password</label>
                </div>
                <input type="submit" class="btn" value="Login">
                <div class="links">
                    <p>Don't have an account?</p>
                    <a href="register.php">Register here</a>
                </div>
            </div>
        </form>
    </div>
</body>

</html>