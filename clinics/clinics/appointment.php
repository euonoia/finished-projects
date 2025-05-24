<?php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "appointments";

$conn = new mysqli('localhost', 'root', '', 'clinic'); // Update with your database credentials



if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT * FROM appointments ORDER BY date ASC, time ASC";
$result = $conn->query($sql);
?>

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
    padding: 10px 25px 25px 25px;
    max-width: 800px;
    width: 100%;
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    position: relative;
  }
  .back-button {
    background: #ccc;
    color: #333;
    font-weight: 700;
    cursor: pointer;
    border: none;
    border-radius: 8px;
    padding: 10px 18px;
    margin-bottom: 20px;
    transition: background-color 0.3s ease;
    display: inline-flex;
    align-items: center;
    font-size: 1rem;
    user-select: none;
  }
  .back-button:hover {
    background: #bbb;
  }
  .back-icon {
    display: inline-block;
    width: 15px;
    height: 11px;
    margin-right: 3px;
    fill: currentColor;
  }
  h1 {
    margin-bottom: 14px;
    font-weight: 700;
    color: rgb(6, 15, 75);
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
    box-shadow: 0 0 8px rgba(2, 18, 85, 0.53);
  }
  input[type="submit"] {
    margin-top: 20px;
    background: rgb(35, 10, 125);
    color: white;
    font-weight: 700;
    cursor: pointer;
    border: none;
    transition: background-color 0.3s ease;
  }
  input[type="submit"]:hover {
    background: rgb(43, 16, 137);
  }
  .confirmation {
    text-align: center;
    padding: 20px 10px;
    color: rgb(57, 4, 156);
  }
 
  @media screen and (max-width: 400px) {
    .container {
      padding: 15px 18px 18px 18px;
      max-width: 100%;
    }
  }
</style>
</head>
<body>
  <div class="container">
    <button class="back-button" onclick="window.history.back();">
      <svg class="back-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" >
        <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
      </svg>
      Back
    </button>
    <h1> Appointment</h1>
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
      alert('Please enter a valid phone number starting with +63 or 09 followed by 9 digits. Example: +639123456789 or 09123456789');
      phoneInput.focus();
      return;
    }

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