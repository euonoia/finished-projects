<?php
    include('db/connect.php');
    if(isset($_POST['submit'])){
    $file_name = $_FILES['image']['name'];
    $tempname = $_FILES['image']['tmp_name'];
    $folder = 'image/'.$file_name;

    $query = mysqli_query($conn,"INSERT into images (file) VALUES ('$file_name')");

    if(move_uploaded_file($tempname, $folder)){
        echo "<script>alert('Image uploaded successfully');</script>";
    }
    else{
        echo "<script>alert('Upload failed');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/logi.png" type="image/x-icon">
    <title>ParkEase</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="style.css">
    <title>Customer Registration</title>
    <style>
        
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
          <div class="bck">
            <a href="index.php">
                <span class="material-symbols-outlined">
                    chevron_left
                </span>
            </a>
            <h2 class="mb-4">REGISTRATION</h2>
          </div>
            <form action="db/register.php" id="register" method="POST" class="needs-validation" enctype="multipart/form-data" novalidate>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="studentId" class="form-label">Student ID:</label>
                        <input type="text" class="form-control" id="studentNumber" name="studentNumber" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="regis" class="form-label">Plate Registration:</label>
                        <input type="text" class="form-control" id="plate" name="plate" required oninput="this.value = this.value.replace(/\s+/g, '-');">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="firstname" class="form-label">Firstname:</label>
                        <input type="text" class="form-control" id="firstname" name="firstname" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="lastname" class="form-label">Lastname:</label>
                        <input type="text" class="form-control" id="lastname" name="lastname" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="vehicleType" class="form-label">Type of Vehicle:</label>
                    <select class="form-select" id="vehicleType" name="vehicleType" required>
                        <option value="" selected disabled>Choose</option>
                        <option value="Car">Car</option>
                        <option value="Motorcycle">Motorcycle</option>
                        <option value="Truck">Truck</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="customerType" class="form-label">Customer Type:</label>
                    <select class="form-select" id="customerType" name="customerType" required>
                        <option value="" selected disabled>Choose</option>
                        <option value="Student">Student</option>
                        <option value="Staff">Staff</option>
                        <option value="PWD">PWD</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="contact" class="form-label">Contact number:</label>
                    <input type="text" class="form-control" id="contact" name="contact" required>
                </div>
                <input type="hidden" id="subscriptionType" name="subscriptionType" value="regular">
                <button type="submit" name="register" class="btn btn-primary">Register</button>
            </form>
        </div>

        <div class="form-container mt-4" style="margin-left: auto;">
             <div class="mb-3">

                          <form  method="POST" enctype="multipart/form-data">
                              <label class="form-label" for="picture">Already bought premium? upload your receipt here</label>
                              <input type="file" name="image" required>
                              <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                          </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>