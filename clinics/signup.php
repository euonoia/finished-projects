<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rxcel</title>
    <link rel="icon" href="img/cropped.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">
   
</head>
<body>
  
      <a href="index.php">
          <img src="img/removedbg.png" alt="Rxcel Logo" style="width:200px;height:auto;margin: 10px;">
      </a>
    </div>
    <div class="container" id="signup" style="display:none;">
      <h1 class="form-title">Register</h1>
      <form method="post" action="register.php">
        <div class="input-group">
          <label for="fName">
            <span class="icon-label">
              <i class="fas fa-user"></i>
              <span>First Name</span>
            </span>
          </label>
          <input type="text" name="fName" id="fName" placeholder="First Name" required>
        </div>
        <div class="input-group">
          <label for="lName">
            <span class="icon-label">
              <i class="fas fa-user"></i>
              <span>Last Name</span>
            </span>
          </label>
          <input type="text" name="lName" id="lName" placeholder="Last Name" required>
        </div>
        <div class="input-group">
          <label for="email">
            <span class="icon-label">
              <i class="fas fa-envelope"></i>
              <span>Email</span>
            </span>
          </label>
          <input type="email" name="email" id="email" placeholder="Email" required>
        </div>
        <div class="input-group">
          <label for="password">
            <span class="icon-label">
              <i class="fas fa-lock"></i>
              <span>Password</span>
            </span>
          </label>
          <input type="password" name="password" id="password" placeholder="Password" required>
        </div>
        <input type="submit" class="btn" value="Sign Up" name="signUp">
      </form>
 
     
      <div class="links">
        <p>Already Have Account ?</p>
        <button id="signInButton">Sign In</button>
      </div>
    </div>


    <div class="container" id="signIn">
        <h1 class="form-title">Sign In</h1>
        <form method="post" action="register.php">
          <div class="input-group">
            <label for="email">
              <span class="icon-label">
                <i class="fas fa-envelope"></i>
                <span>Email</span>
              </span>
            </label>
            <input type="email" name="email" id="email" placeholder="Email" required>
          </div>
          <div class="input-group">
            <label for="password">
              <span class="icon-label">
                <i class="fas fa-lock"></i>
                <span>Password</span>
              </span>
            </label>
            <input type="password" name="password" id="password" placeholder="Password" required>
          </div>
          <input type="submit" class="btn" value="Sign In" name="signIn">
        </form>
        <div class="links">
          <p>Don't have account yet?</p>
          <button id="signUpButton">Sign Up</button>
        </div>
      </div>
      <script src="script.js"></script>
</body>
</html>
