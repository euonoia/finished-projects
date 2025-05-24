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
       <link rel="icon" href="img/cropped.png" type="image/x-icon">

<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<title>Appointment Booking</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<style>
  * {
    box-sizing: border-box;
  }
  body {
    margin: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, rgb(179, 179, 179), rgb(21, 15, 132));
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
    max-width: 1000px;
    width: 100%;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    position: relative;
    border: 1px solid rgba(0, 0, 0, 0.1); /* Added border */
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2), 0 0 10px rgba(0, 0, 0, 0.1); /* Added outer shadow */
  }
  .back-button {
    background: #ccc;
    color: #333;
    font-weight: 700;
    cursor: pointer;
    border: none;
    border-radius: 10px;
    padding: 10px 18px;
    margin-bottom: 20px;
    transition: background-color 0.3s ease;
    display: inline-flex;
    align-items: center;
    font-size: 1rem;
    user-select: none;
    position: absolute;
    top: 30px;
    left: 40px; /* Adjusted to position the button on the side */
  }
  .back-button:hover {
    background: #bbb;
  }
  .back-icon {
    display: inline-block;
    width: 20px; /* Increased width */
    height: 22px; /* Increased height */
    margin-right: 5px; /* Adjusted spacing */
    fill: currentColor;
  }
 h1 {
    background-color: #001845; /* Updated color to match updates.php header */
    padding: 10px 10px; /* Increased padding for better spacing */
    border-radius: 8px; /* Slightly rounded corners */
    margin-top: 10px; /* Added margin to separate it from the top */
    text-align: center; /* Ensure text is centered */
    color: white; /* Changed text color to white */
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
    background: green;
    color: white;
    font-weight: 700;
    cursor: pointer;
    border: none;
    transition: background-color 0.3s ease;
  }
  input[type="submit"]:hover {
    background: darkgreen;
  }
  .confirmation {
    text-align: center;
    padding: 20px 10px;
    color: rgb(4, 156, 24);
  }
  .phone-wrapper {
  display: flex;
  align-items: center;
  margin-bottom: 10px;
}
.phone-prefix {
  background: #f0f0f0;
  border: 1.8px solid #ccc;
  border-right: none;
  border-radius: 8px 0 0 8px;
  padding: 10px 10px;
  font-size: 1rem;
  color: #333;
}
.phone-wrapper input[type="tel"] {
  border-radius: 0 8px 8px 0;
  border-left: none;
  flex: 1;
}

/* Hide number input spinners for Student ID field (all browsers) */
  #studentId::-webkit-outer-spin-button,
  #studentId::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }
  #studentId[type="number"] {
    -moz-appearance: textfield;
    appearance: textfield;
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
    <a class="back-button" href="index.php">
    <svg class="back-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
      <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
    </svg>
   
  </a>
    <h1> APPOINTMENT</h1>
   <form id="appointmentForm" novalidate method="post" action="appointment.php">
      <label for="studentId">Student ID</label>
      <input type="number" id="studentId" name="studentId" placeholder="Enter your Student ID" required />
      <label for="name">Patient Name</label>
      <input type="text" id="name" name="name" placeholder="Your full name" required minlength="2" />
     
      <label for="email">Email</label>
      <input type="email" id="email" name="email" placeholder="you@example.com" required />
     
      <label for="phone">Phone Number</label>
      <div class="phone-wrapper">
        <span class="phone-prefix">+63</span>
        <input type="tel" id="phone" name="phone" maxlength="10" pattern="9\d{9}" placeholder="9123456789" required />
      </div>
     
      <label for="date">Appointment Date</label>
      <input type="date" id="date" name="date" required min="" />
     
      <label for="time">Appointment Time</label>
      <input type="text" id="time" name="time" required />
     
      <input type="submit" value="Book Appointment" />
    </form>

         

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Collect form data
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $phone = '+63' . mysqli_real_escape_string($conn, $_POST['phone']);
        $date = mysqli_real_escape_string($conn, $_POST['date']);
        $studentId = mysqli_real_escape_string($conn, $_POST['studentId']);
        $time = mysqli_real_escape_string($conn, $_POST['time']);

        // Convert 12-hour format to 24-hour if needed
        if (preg_match('/(AM|PM)$/i', $time)) {
            $time = date("H:i", strtotime($time));
        }

        if ($time < '08:00' || $time > '17:00') {
            echo "Time must be between 08:00 and 17:00.";
            exit;
        }

        // Insert data into the database
        $sql = "INSERT INTO appointments (name, email, phone, date, studentId, time) VALUES ('$name', '$email', '$phone', '$date', '$studentId', '$time')";

        if (mysqli_query($conn, $sql)) {
            echo "Appointment successfully booked!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
        exit; // Stop further output
    }
    ?>

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
      
    // Check each required field and set custom validity message if empty
    const requiredFields = ['name', 'email', 'phone', 'date', 'time'];
    let isValid = true;

    requiredFields.forEach(fieldId => {
      const field = document.getElementById(fieldId);
      if (!field.value.trim()) {
        field.setCustomValidity(`Please fill out the ${fieldId} field.`);
        field.reportValidity();
        isValid = false;
      } else {
        field.setCustomValidity('');
      }
    });

    if (!isValid) {
      return;
    }

    // phone number validation pattern: starts with +63 or 0 followed by 9, then 9 digits
    const phoneInput = document.getElementById('phone');
    const phoneValue = phoneInput.value.trim();
    if (!/^9\d{9}$/.test(phoneValue)) {
      alert('Please enter a valid phone number after +63 (e.g., 9123456789).');
      phoneInput.focus();
      return;
    }

    // Time validation
    const timeValue = document.getElementById('time').value.trim();
function to24Hour(timeStr) {
  // Example input: "08:00 AM" or "04:30 PM"
  const [time, modifier] = timeStr.split(' ');
  let [hours, minutes] = time.split(':');
  hours = parseInt(hours, 10);
  if (modifier === 'PM' && hours !== 12) hours += 12;
  if (modifier === 'AM' && hours === 12) hours = 0;
  return `${hours.toString().padStart(2, '0')}:${minutes}`;
}
const time24 = to24Hour(timeValue);
if (time24 < '08:00' || time24 > '17:00') {
      alert('Please select a time between 08:00 and 17:00.');
      document.getElementById('time').focus();
      return;
    }

    // Collect form data
    const formData = new FormData(form);

    // Send data to the server using Fetch API
    fetch('appointment.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.text())
    .then(data => {
      if (data.includes('Appointment successfully booked!')) {
        // Display confirmation message
        confirmation.style.display = 'block';
        confirmation.innerHTML = `
          <h2>Appointment Booked!</h2>
          <p>Thank you, <strong>${formData.get('name')}</strong>.<br/>
          We have received your appointment request for <strong>${formData.get('date')}</strong> at <strong>${formData.get('time')}</strong>.<br/>
          We will contact you shortly at <strong>${formData.get('email')}</strong> or <strong>${formData.get('phone')}</strong>.</p>
        `;

        // Reset form
        form.reset();
        // Reset min date again in case user submits before midnight
        dateInput.setAttribute('min', new Date().toISOString().split('T')[0]);
      } else {
        alert('Error booking appointment: ' + data);
      }
    })
    .catch(error => {
      alert('An error occurred: ' + error.message);
    });
  });

  flatpickr("#time", {
  enableTime: true,
  noCalendar: true,
  dateFormat: "h:i K", // 12-hour format with AM/PM in the text field
  minTime: "08:00",
  maxTime: "17:00",
  time_24hr: false, // Show AM/PM selector
  allowInput: false // Prevent manual typing
});

document.querySelectorAll('.doctor-select').forEach(select => {
  select.addEventListener('change', function() {
    const appointmentId = this.dataset.appointmentId;
    const doctor = this.value;

    fetch('updates.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: `appointment_id=${appointmentId}&doctor=${doctor}`
    })
    .then(response => response.text())
    .then(data => {
      if (data.trim() === 'success') {
        alert('Doctor assigned successfully!');
      } else {
        alert('An error occurred: ' + data);
      }
    });
  });
});
</script>

