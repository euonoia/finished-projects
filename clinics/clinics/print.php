<?php
// Fetch Student ID from the URL
$student_id = $_GET['id']; // Get Student ID from the URL (e.g., print.php?id=123)

// Database connection
$conn = new mysqli('localhost', 'root', '', 'clinic'); // Update with your database credentials

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch student data
$query = "SELECT * FROM patients WHERE studentID = '$student_id'";
$result = $conn->query($query);

// Check if the student exists
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $student_name = $row['firstName'] . ' ' . $row['lastName'];
    $student_age = $row['age'];
    $diagnosis = $row['condition'];
    $date_issued = date('F d, Y'); // Current date
} else {
    die("No record found for the given Student ID.");
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
            height: 500px;
            border: 2px solid;
            padding: 20px; 
            text-align: center; 
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
       
        @media print {
            #no-print { display: none; } /* Hide the print button during printing */
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
        <div class="row">
            <div class="col-12 col-md-7 col-lg-12 ">
            <div class="certi">
                        <div id="certificate" class="certificate">
                            <div class="logo">
                                <!-- Replace RXCEL text with the logo -->
                                <img src="img/logo.png" alt="Clinic Logo">
                                <div class="address">
                                <p>#1071 Brgy. Kaligayahan, Quirino Highway Novaliches Quezon City</p>
                                <p>Contact: 8442-8601 | 8518-8050</p>
                                </div>
                            </div>
                        <hr>
                        <h2>MEDICAL CERTIFICATE</h2>
                        
                        <div class="details">
                            <p><strong>Student Name:</strong> <?php echo $student_name; ?></p>
                            <p><strong>Age:</strong> <?php echo $student_age; ?></p>
                            <p><strong>Date Issued:</strong> <?php echo $date_issued; ?></p>
                            <p><strong>Diagnosis:</strong> <?php echo $diagnosis; ?></p>
                        </div>

                        <div class="notif">
                        <p><em>This is to certify that the above-named student was seen at our clinic and is advised</em></p>
                            <p><em>to rest as per the diagnosis provided.</em></p>
                        </div>
                        <div class="signature">
                            <p><strong>Issued by:</strong> Clinic Staff </p>
                            <p><strong>Signature:</strong> ________________________</p>
                        </div>
                    </div>
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