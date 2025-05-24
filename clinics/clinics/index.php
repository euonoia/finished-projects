<?php
include 'connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rxcel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
    rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" 
    crossorigin="anonymous">
    <link rel="stylesheet" href="index/style.css">
</head>
<body>
<a href="signup.php">
    <img src="img/logo.png" alt="Rxcel Logo" style="width:200px;height:auto;margin: 10px;">
</a>
<div class="container">  
    <div class="row">  
        <div class="col-12 col-md-7 col-lg-7">
            <div class="appointment">
                <h2>Walk-ins</h2>
                
                <form action="symptoms.php" id="symptoms" method="POST">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-9 col-md-12 col-lg-6 col-xxl-6 ">
                                <div class="ro_1">
                                    <label for="firstName">Firstname:</label>
                                    <input type="text" name="firstName" id="ucwords.firstName" placeholder="Enter your Firstname" required>
                
                                    <label for="lastName">Surname:</label>
                                    <input type="text" name="lastName" id="ucwords.lastName" placeholder="Enter your Lastname" required>

                                    <label for="studentID">Student ID:</label>
                                    <input type="number" name="studentID" id="studentID" min="1" placeholder="Enter your Student ID" required>
                                </div>
                            </div>
                            <div class="col-xs-9 col-md-12 col-lg-6 col-xxl-6">
                                <div class="ro_1">
                                    <label for="email">Age:</label>
                                    <input type="number" name="age" id="age" placeholder="Enter your Age" required>
                                    
                                    <label for="Gender">Gender:</label>
                                    <select name="gender">
                                        <option name="gender" id="male" value="male">Male</option>
                                        <option name="gender" id="female" value="female">Female</option>
                                    </select>
                                    
                                    <label for="departure-date">Condition:</label>
                                    <input type="text" name="condition" id="condition" placeholder="Enter your Condition" required>
                                </div>
                            </div>
                            <label for="date" style="padding-top:3px;text-align: center;">Date Today:</label>
                            <input type="date" name="date" id="date" style="text-align:center; width: 100%;" required>
                            <button name="symptoms" type="submit">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-12 col-md-5 col-lg-5 justify-content-center">
            <div class="appointment-1">
                <h2>Appointment</h2>
                <div id="schedule">
                    <a href="appointment.php">
                        <button>Book A Schedule</button>
                    </a>
                </div>
            </div>
            <div class="appointment-1">
                <h2>Print Certificate</h2>
                <div class="print">
                    <!-- Form to input Student ID and redirect to print.php -->
                    <form action="print.php" method="GET">
                        <input type="number" name="id" id="studentID" placeholder="Enter your Student ID" required>
                        <button style="width:80px;" type="submit">Print</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" 
crossorigin="anonymous"></script>
</body>
</html>