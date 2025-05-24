<?php
// Fetch Student ID from the URL
$student_id = $_GET['id']; // Get Student ID from the URL (e.g., print.php?id=123)

// Database connection
$conn = new mysqli('localhost', 'root', '', 'clinic'); // Update with your database credentials

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Try to fetch from patients table first
$query_patient = "SELECT * FROM patients WHERE studentID = '$student_id'";
$result_patient = $conn->query($query_patient);

if ($result_patient && $result_patient->num_rows > 0) {
    $row = $result_patient->fetch_assoc();
    $student_name = $row['firstName'] . ' ' . $row['lastName'];
    $student_age = $row['age'];
    $diagnosis = $row['condition'];
    $date_issued = date('F d, Y'); // Current date
    $assigned_doctor = '';
    $comments = '';
} else {
    // If not found in patients, try appointments
    $query_appt = "SELECT * FROM appointments WHERE studentId = '$student_id' ORDER BY date DESC, time DESC LIMIT 1";
    $result_appt = $conn->query($query_appt);
    if ($result_appt && $result_appt->num_rows > 0) {
        $row = $result_appt->fetch_assoc();
        $name = isset($row['name']) ? $row['name'] : '';
        $Diagnosis = isset($row['Diagnosis']) ? $row['Diagnosis'] : '';
        $date_issued = $row['date'];
        $assigned_doctor = isset($row['assigned_doctor']) ? $row['assigned_doctor'] : '';
        $comments = $row['comments'];
    } else {
        die("No record found for the given Student ID.");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Certificate</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
    rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" 
    crossorigin="anonymous">
    <style>
        body{  
            font-family: Arial, sans-serif;
            margin: 0px; 
            padding: 0px;
        }
        a{
            text-decoration: none;
        }
        .fa-arrow-left{
            
            font-size: 20px;
            color: black;
            margin: 20px;
        }
        .fa-arrow-left:hover{
            color: #4CAF50;
        }
        .header{
            display: flex;
            flex-direction: row;
            justify-content: end;
            box-shadow: 0 2px 24px ;
            margin: 10px;
            padding: 15px;
            border-radius: 10px;
        }
        .container{
            display: flex;
            justify-content: space-evenly;
            margin-top: 40px;
            
        }
        .logo{
            display: flex;
            flex-direction: row;
            justify-content: space-evenly;
            margin: 0;
            padding: 0;
        }
        .logo img{
           width: 150px;
            margin: 5px;
        }
        .logo p{
            font-size: 10px;
        }
        .print{
            border-radius: 10px;
            width: 100%;
            display: flex;
            gap: 5px;
            margin-right: 10px;
            flex-direction: row;
            justify-content: space-between;
          
        }
        button{
            border-radius: 10px;
            margin: 5px;
            padding: 10px;
            width: 100%;
            text-align: center;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover{
            background-color:rgb(54, 128, 57);
        }
        .notif{
            display: flex;
            justify-content: center;
            flex-direction: column;
            margin-top:10px;
            padding: 0;
            font-size: 15px;
        }
        .certificate {
            width: 100%;
            justify-content: center;
            /* Remove the border and padding below */
            /* border: 2px solid; */
            /* padding: 20px; */
            text-align: center;
            background: transparent; /* Optional: ensure no background */
            height: auto; /* Let the card define its own height */
        }
        
        h2 { 
            text-decoration: underline; 
            font-size: 20px;
        }
        .details { 
            font-size: 15px;
            text-align: left; 
            margin-top: 20px; 
        }
        .signature { 
            font-size: 15px;
            margin-top: 10px; 
            text-align: right; 
        }
        .certificate-card {
            max-width: 500px;
            margin: 40px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            padding: 32px 40px;
            font-family: 'Segoe UI', Arial, sans-serif;
            border: 2.5px solid #bbb;
        }
        .certificate-header {
            text-align: center;
        }
        .logo-img {
            width: 120px;
            margin-bottom: 8px;
        }
        .address p {
            font-size: 12px;
            color: #555;
            margin: 0;
        }
        .details-grid {
            display: grid;
            grid-template-columns: auto auto;
            gap: 4px 8px; /* Less gap between columns */
            margin: 24px 0;
            font-size: 15px;
        }
        .signature-section {
            margin-top: 32px;
            text-align: right;
            font-size: 15px;
        }
        @media print {
            #no-print { display: none; } /* Hide the print button during printing */
        }
        @media (max-width: 600px) {
    .certificate-card {
        padding: 16px 8px;
    }
}
    </style>
</head>
<body>

    <div class="header" id="no-print">
        <div class="print">
                 
                        <a href="index.php">
                            <i class="fa-solid fa-arrow-left"></i>
                        </a> 
                    <div id="no-print">
                                    <button onclick="window.print()">Print Cerificate</button>  
                    </div>
                            </div>
        </div>
    <div class="container">
        <?php if (isset($row) && isset($row['firstName'])) { ?>
        <!-- Patient Certificate (from patients table) -->
        <div class="row">
            <div class="col-12 col-md-7 col-lg-12 ">
            <div class="certi">
                        <div id="patient-certificate" class="certificate">
                            <div class="certificate-card">
                                <div class="certificate-header">
                                    <img src="img/logo.png" alt="Clinic Logo" class="logo-img">
                                    <div class="address">
                                        <p>#1071 Brgy. Kaligayahan, Quirino Highway Novaliches Quezon City</p>
                                        <p>Contact: 8442-8601 | 8518-8050</p>
                                    </div>
                                </div>
                                <hr>
                                <h2>MEDICAL CERTIFICATE</h2>
                                <div class="details-grid">
                                    <div><strong>Student ID:</strong></div><div><?php echo $student_id; ?></div>
                                    <div><strong>Student Name:</strong></div><div><?php echo $student_name; ?></div>
                                    <div><strong>Age:</strong></div><div><?php echo $student_age; ?></div>
                                    <div><strong>Date Issued:</strong></div><div><?php echo $date_issued; ?></div>
                                    <div><strong>Symptoms:</strong></div><div><?php echo $diagnosis; ?></div>
                                </div>
                                <div class="notif">
                                    <p><em>This is to certify that the above-named student was seen at our clinic and is advised to rest as per the diagnosis provided.</em></p>
                                </div>
                                <div class="signature-section">
                                    <div><strong>Issued by:</strong> ________________________</div>
                                    <div><strong>Signature:</strong> ________________________</div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
        <?php } ?>
        <!-- Appointment Certificate (from appointments table) -->
        <?php if (!isset($row['firstName'])) { ?>
        <div class="row">
            <div class="col-12 col-md-7 col-lg-12 ">
            <div class="certi">
                        <div id="certificate" class="certificate">
                            <div class="certificate-card">
                                <div class="certificate-header">
                                    <img src="img/logo.png" alt="Clinic Logo" class="logo-img">
                                    <div class="address">
                                        <p>#1071 Brgy. Kaligayahan, Quirino Highway Novaliches Quezon City</p>
                                        <p>Contact: 8442-8601 | 8518-8050</p>
                                    </div>
                                </div>
                                <hr>
                                <h2>MEDICAL CERTIFICATE</h2>
                                <div class="details-grid">
                                    <div><strong>Student ID:</strong></div><div><?php echo $student_id; ?></div>
                                    <div><strong>Student Name:</strong></div><div><?php echo $name; ?></div>
                                    <div><strong>Date Issued:</strong></div><div><?php echo $date_issued; ?></div>
                                    <div><strong>Diagnosis:</strong></div><div><?php echo $Diagnosis; ?></div>
                                    <?php if (!empty($comments)) { ?>
                                    <div><strong>Comments:</strong></div><div><?php echo htmlspecialchars($comments); ?></div>
                                    <?php } ?>
                                </div>
                                <div class="notif">
                                    <p><em>This is to certify that the above-named student was seen at our clinic and is advised to rest as per the diagnosis provided.</em></p>
                                </div>
                                <div class="signature-section">
                                    <div><strong>Issued by:</strong> <?php echo $assigned_doctor ? $assigned_doctor : '________________________'; ?></div>
                                    <div><strong>Signature:</strong> ________________________</div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" 
    crossorigin="anonymous"></script>
</body>
</html>