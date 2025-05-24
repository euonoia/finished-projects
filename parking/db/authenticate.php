<?php
include('connect.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="parking/images/logi.png" type="image/x-icon">
    <title>Login</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
        <div class="bck">
            <a href="/parking/index.php">
                <span class="material-symbols-outlined">
                    chevron_left
                </span>
            </a>
            <h2>Admin login</h2>
          </div>
        <form method="POST" action="part2.php"id="signIn">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <br>
            <input type="submit" class="btn" value="Sign In" name="signIn">
        </form>
        </div>
    </div>
</body>
</html>