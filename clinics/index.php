<?php
include 'connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rxcel</title>
     <link rel="icon" href="img/cropped.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
    rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" 
    crossorigin="anonymous">
    <link rel="stylesheet" href="index/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body>
<a href="signup.php" class="logo">
    <img src="img/removedbg.png" alt="Rxcel Logo" style="width:200px;height:auto;margin: 10px;">
</a>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-7 mb-4">
            <div class="appointment bg-white shadow-sm p-4 rounded-4">
                <!-- Walk-ins -->
<h2 class="mb-4"><i class="bi bi-person-lines-fill me-2"></i>Walk-ins</h2>
                <form action="symptoms.php" id="symptoms" method="POST"> 
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="studentID" class="form-label mt-2">Student ID</label>
                            <input type="number" name="studentId" id="studentId" class="form-control" placeholder="" required>
                            <label for="firstName" class="form-label">Firstname</label>
                            <input type="text" name="firstName" id="firstName" class="form-control" placeholder="" required>
                            <label for="gender" class="form-label mt-2">Gender</label>
                            <select name="gender" id="gender" class="form-select" required>
                                 <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="age" class="form-label">Age</label>
                            <input type="number" name="age" id="age" class="form-control" placeholder="" required>
                              <label for="lastName" class="form-label mt-2">Lastname</label>
                            <input type="text" name="lastName" id="lastName" class="form-control" placeholder="" required>

                               
                            <label for="condition" class="form-label mt-2">Symptoms</label>
                            <input type="text" name="condition" id="condition" class="form-control" placeholder="e.g fever, headache" required>
                        </div>
                        <div class="col-12">
                            <label for="date" class="form-label mt-2">Date Today</label>
                            <input type="date" name="date" id="date" class="form-control text-center" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-success w-100"name="symptoms" id="submit">Submit</button>
                            <?php
                            if (isset($_GET['success']) && $_GET['success'] == '1') {
                                echo "<script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        var walkinModal = new bootstrap.Modal(document.getElementById('walkinSuccessModal'));
                                        walkinModal.show();
                                    });
                                </script>";
                            }
                            ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-12 col-lg-5 d-flex flex-column align-items-center">
            <div class="appointment-1 bg-white shadow-sm p-4 rounded-4 w-100 mb-4">
                <!-- Appointment -->
<h2><i class="bi bi-calendar-check me-2"></i>Appointment</h2>
                <div id="schedule" class="d-flex justify-content-center">
                    <a href="appointment.php" class="btn btn-success w-75">Book A Schedule</a>
                </div>
            </div>
            <div class="appointment-1 bg-white shadow-sm p-4 rounded-4 w-100">
                <!-- Print Certificate -->
             <div class="print-section">
               <h2 class="section-title"><i class="bi bi-printer me-2"></i>Print Certificate</h2>
            <div class="print">
                <form id="printForm" action="print.php" method="GET" class="print-form">
                    <input type="number" 
                        name="id" 
                        id="printStudentID" 
                        class="form-control print-input" 
                        placeholder="Enter your Student ID" 
                        required
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    <button type="submit" class="btn btn-success print-btn">
                        <i class="bi bi-printer"></i> Print
                    </button>
                </form>
                <div id="printError" class="error-message">Please enter a valid Student ID</div>
            </div>
    </div>
</div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="walkinSuccessModal" tabindex="-1" aria-labelledby="walkinSuccessModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4">
      <div class="modal-header border-0">
        <h5 class="modal-title" id="walkinSuccessModalLabel"><i class="bi bi-check-circle-fill text-success me-2"></i>Submission Successful</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <p>Your walk-in information has been submitted!</p>
      </div>
      <div class="modal-footer border-0 justify-content-center">
        <button type="button" class="btn btn-success px-4" data-bs-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" 
crossorigin="anonymous"></script>

 
</body>
<footer>CLINICAL MANAGEMENT SYSTEM</footer>
</html>