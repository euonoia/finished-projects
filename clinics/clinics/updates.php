<?php //PATIENT HISTORY SIDEBAR
session_start();
include("connect.php");
include("config.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="sidebarnav.css">
    <!--=============== REMIXICONS ===============-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css">
    <!--=============== CSS ===============-->
    <link rel="stylesheet" href="sidebar/css/styles.css">
    <title>Appointment</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: white;
        }
        tbody tr:nth-child(even) {
            background-color: white;
        }
    </style>
</head>
<body>
<!--=============== HEADER ===============-->
<header class="header" id="header">
   <div class="header__container">
      <button class="header__toggle" id="header-toggle">
         <i class="ri-menu-line"></i>
      </button>
   </div>
</header>

<!--=============== SIDEBAR ===============-->
<nav class="sidebar" id="sidebar">
<div class="sidebar__container">
      <div class="sidebar__user">
      <div class="sidebar__img">
            <img src="img/cropped.png" alt="image">
         </div>

         <!--================SIDEBAR INFO============-->
         <div class="sidebar__info">
         <?php 
       if(isset($_SESSION['email'])){
        $email=$_SESSION['email'];
        $query=mysqli_query($conn, "SELECT users.* FROM `users` WHERE users.email='$email'");
        while($row=mysqli_fetch_array($query)){
            echo ucwords($row['firstname']).' '.ucwords($row['lastname']);
        }
       }
       ?>
         </div>
      </div>

      <div class="sidebar__content">
         <div>
            <h3 class="sidebar__title">MANAGE</h3>

            <div class="sidebar__list">
               <a href="homepage.php" class="sidebar__link ">
                  <i class="ri-pie-chart-2-fill"></i>
                  <span>Dashboard</span>
               </a>
               
               <div class="dropdown">
                   <button class="dropdown-button">  <i class="ri-archive-line"></i>
                                        <span>Tools</span>
                    </button>
                   <div class="dropdown-content">
                       <a href="updates.php">Appointment</a>
                       <a href="records.php">Patient Records</a>
                   </div>
               </div>

               <button>
               <i class="ri-moon-clear-fill sidebar__link sidebar__theme" id="theme-button">
               <span>Theme</span>
                </i>
               </button>

               <a href="logout.php">
               <button class="sidebar__link">
               <i class="ri-logout-box-r-fill"></i>
               <span>Log Out</span>
               </button>
               </a>
            
            </div>
         </div>
      </div>

      </div>
   </div>
</nav>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <title>Appointment Booking</title>
   <style>
  
  * {
    box-sizing: border-box; 
    }
      body {
    margin: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg,rgb(2, 13, 60),rgb(21, 15, 132));
    color: #333;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 15px;
  }
  .container {
    background: white;
    border-radius: 10px;
    padding: 10px 25px;
    max-width: 500px;
    width: 100%;
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
  }
  h1 {
    margin-bottom: 14px;
    font-weight: 700;
    color:rgb(6, 15, 75);
    text-align: center;
  }
  form {
    display: flex;
    flex-direction: column;
  }
  label {
    font-weight: 600;
    margin-top: 12px;
    margin-bottom: 6px;
    font-size: 0.9rem;
  }
  input, select {
    padding: 10px 12px;
    font-size: 1rem;
    border: 1.8px solid #ccc;
    border-radius: 8px;
    transition: border-color 0.3s ease;
  }
  input:focus, select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 8pxrgba(2, 18, 85, 0.53);
  }
  input[type="submit"] {
    margin-top: 20px;
    background:rgb(35, 10, 125);
    color: white;
    font-weight: 700;
    cursor: pointer;
    border: none;
    transition: background-color 0.3s ease;
  }
  input[type="submit"]:hover {
    background:rgb(43, 16, 137);
  }
  .confirmation {
    text-align: center;
    padding: 20px 10px;
    color:rgb(57, 4, 156);
  }
 
  @media screen and (max-width: 400px) {
    .container {
      padding: 15px 18px;
      max-width: 100%;
    }
  }
</style>
</head>
<body>
  <div class="container">
    <h1>Manage Appointment</h1>
    <form id="appointmentForm" novalidate>
      <label for="name">Patient Name</label>
      <input type="text" id="name" name="name" placeholder="Your full name" required minlength="2" />
      
      <label for="email">Email</label>
      <input type="email" id="email" name="email" placeholder="you@example.com" required />
      
      <label for="phone">Phone Number</label>
      <input type="tel" id="phone" name="phone" placeholder="e.g. +639123456789 or 09123456789" required
        pattern="^(\+63|0)9\d{9}$" />
      
      <label for="date">Appointment Date</label>
      <input type="date" id="date" name="date" required min="" />
      
      <label for="time">Appointment Time</label>
      <input type="time" id="time" name="time" required />
      
      <input type="submit" value="Book Appointment" />
    </form>
    <div id="confirmationMessage" class="confirmation" style="display:none;"></div>
  </div>
<script>
  // Set min date of the date input to today
  const dateInput = document.getElementById('date');
  const today = new Date().toISOString().split('T')[0];
  dateInput.setAttribute('min', today);

  const form = document.getElementById('appointmentForm');
  const confirmation = document.getElementById('confirmationMessage');

  form.addEventListener('submit', function(event) {
    event.preventDefault();
      
    if (!form.checkValidity()) {
      form.reportValidity();
      return;
    }

    // phone number validation pattern: starts with +63 or 0 followed by 9, then 9 digits
    const phoneInput = form.phone;
    let phoneValue = phoneInput.value.trim();

    // Remove spaces, dashes, parentheses for checking
    const cleanedPhone = phoneValue.replace(/[\s\-()]/g, '');

    // Regex to validate
    const phPattern = /^(\+63|0)9\d{9}$/;

    if (!phPattern.test(cleanedPhone)) {
      alert('Please enter a valid Philippine phone number starting with +63 or 09 followed by 9 digits. Example: +639123456789 or 09123456789');
      phoneInput.focus();
      return;
    }

    // Normalize phone number display format for confirmation - keep input as is

    // Collect form data
    const name = form.name.value.trim();
    const email = form.email.value.trim();
    const phone = phoneInput.value.trim();
    const date = form.date.value;
    const time = form.time.value;

    // Display confirmation message
    confirmation.style.display = 'block';
    confirmation.innerHTML = `
      <h2>Appointment Booked!</h2>
      <p>Thank you, <strong>${name}</strong>.<br/>
      We have received your appointment request for <strong>${date}</strong> at <strong>${time}</strong>.<br/>
      We will contact you shortly at <strong>${email}</strong> or <strong>${phone}</strong>.</p>
    `;
    
    // Reset form
    form.reset();
    // Reset min date again in case user submits before midnight
    dateInput.setAttribute('min', new Date().toISOString().split('T')[0]);
  });
</script>
</body>
</html>
<!--=============== MAIN JS ===============-->
<script src="sidebar/js/main.js"></script>
</body>
</html>