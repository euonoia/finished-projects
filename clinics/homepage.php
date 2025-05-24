<?php
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
    <title>Dashboard</title>
    <style>
       table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color:
        }
        tbody tr:nth-child(even) {
            background-color:
        }
        /* Search bar styles */
        .search-container {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        .search-label {
            font-size: 16px;
            margin-right: 10px;
        }
        .search-input {
            width: 20%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 0px;
        }

        .modal-content {
          background-color: var(--body-color);
          box-shadow: 0 2px 24px var(--shadow-color);
          padding: 30px;  
          border-radius: 30px;
           max-width: 680px;
           font-family: ; width: 80%;
           margin: 0 auto;
          margin-top: 200px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        /* Button styles */
        .button {
            background-color:rgb(84, 178, 84); 
            border: none;
            border-radius: 5px;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            font-weight: bold;
            margin: 4px 2px;
            margin-bottom: 2px;
            transition-duration: 0.4s;
            cursor: pointer;
        }

        .button:hover {
            color: rgba(4, 4, 4, 0.53);
            border: 2px solidrgb(238, 250, 233);
        }

        .button:active {
            background-color:rgba(255, 255, 255, 0.53);
            box-shadow: 0 5px #666;
            transform: translateY(4px);
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
               <a href="#" class="sidebar__link active_link">
                  <i class="ri-pie-chart-2-fill"></i>
                  <span>Dashboard</span>
               </a>

               <a href="updates.php" class="sidebar__link">
                  <i class="ri-archive-line"></i>
                  <span>Appointment</span>
               </a>

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

<!--=============== MAIN ===============--> 
<main class="main container" id="main"> 
    <h2>Patient Records</h2> <br>

    <!-- Search Bar -->
    <div class="search-container">
        <label for="searchInput" class="search-label">Search:</label>
        <input type="text" id="searchInput" class="search-input" placeholder="">
        <br>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Student_ID</th>
                <th>Age</th>
                <th>Condition</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="patientTable">
        <?php 
        $sql = "SELECT * from patients";
        $query = $dbh->prepare($sql);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        $cnt = 1;
        if ($query->rowCount() > 0) {
            foreach ($results as $result) { ?>
                <tr>
                    <td><?php echo htmlentities($cnt); ?></td>
                    <td><?php echo htmlentities($result->firstName); ?></td>
                    <td><?php echo htmlentities($result->lastName); ?></td>
                    <td><?php echo htmlentities($result->studentID); ?></td>
                    <td><?php echo htmlentities($result->age); ?></td>
                    <td><?php echo htmlentities($result->gender); ?></td>
                    <td><?php echo htmlentities($result->condition); ?></td>
                    <td><?php echo htmlentities($result->date); ?></td>
                    <td>
                        <button class="edit-btn" data-id="<?php echo htmlentities($result->id); ?>" data-firstname="<?php echo htmlentities($result->firstName); ?>" data-lastname="<?php echo htmlentities($result->lastName); ?>" data-age="<?php echo htmlentities($result->age); ?>" data-gender="<?php echo htmlentities($result->gender); ?>" data-condition="<?php echo htmlentities($result->condition); ?>" data-date="<?php echo htmlentities($result->date); ?>"><i class="ri-edit-line" title="Edit Record"></i></button>
                    </td>
                </tr>
        <?php $cnt++; } } ?>
        </tbody>
    </table>
</main>

<!-- Modal Structure -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2 id="modalTitle">Edit Patient Record</h2>
    <form id="editForm">
      <input type="hidden" id="editId">
      <div class="input-group">
        <label for="editFirstName">First Name: </label>
        <input type="text" id="editFirstName" name="firstName" required>
      </div>
      <div class="input-group">
        <label for="editLastName">Last Name: </label>
        <input type="text" id="editLastName" name="lastName" required>
      </div>
      <div class="input-group">
        <label for="editAge">Age: </label>
        <input type="number" id="editAge" name="age" required>
      </div>
      <div class="input-group">
        <label for="editGender">Gender: </label>
        <input type="text" id="editGender" name="gender" required>
      </div>
      <div class="input-group">
        <label for="editCondition">Condition: </label>
        <input type="text" id="editCondition" name="condition" required>
      </div>
      <div class="input-group">
        <label for="editDate">Date: </label>
        <input type="date" id="editDate" name="date" required>
      </div>
      <button type="submit" class="button">Save Changes</button>
    </form>
  </div>
</div>

<!--=============== MAIN JS ===============-->
<script src="sidebar/js/main.js"></script>
<script>
    // Get the modal
    var modal = document.getElementById("editModal");

    // Get the button that opens the modal
    var btns = document.getElementsByClassName("edit-btn");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks the button, open the modal 
    for (var i = 0; i < btns.length; i++) {
        btns[i].onclick = function() {
            modal.style.display = "block";
            document.getElementById("editId").value = this.getAttribute("data-id");
            document.getElementById("editFirstName").value = this.getAttribute("data-firstname");
            document.getElementById("editLastName").value = this.getAttribute("data-lastname");
            document.getElementById("editAge").value = this.getAttribute("data-age");
            document.getElementById("editGender").value = this.getAttribute("data-gender");
            document.getElementById("editCondition").value = this.getAttribute("data-condition");
            document.getElementById("editDate").value = this.getAttribute("data-date");
        }
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Handle form submission
    document.getElementById("editForm").onsubmit = function(event) {
        event.preventDefault();
        var id = document.getElementById("editId").value;
        var firstName = document.getElementById("editFirstName").value;
        var lastName = document.getElementById("editLastName").value;
        var age = document.getElementById("editAge").value;
        var gender = document.getElementById("editGender").value;
        var condition = document.getElementById("editCondition").value;
        var date = document.getElementById("editDate").value;

        // Perform AJAX request to update the patient record
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "update-patient.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById("modalTitle").textContent = 'Saved Changes';
                setTimeout(() => {
                    document.getElementById("modalTitle").textContent = 'Edit Patient Record';
                    modal.style.display = "none";
                    location.reload(); // Reload the page to reflect changes
                }, 2000);
            }
        };
        xhr.send("id=" + id + "&firstName=" + firstName + "&lastName=" + lastName + "&age=" + age + "&gender=" + gender + "&condition=" + condition + "&date=" + date);
    };
    
    // Add interactive button effect
    document.querySelector('.button').addEventListener('click', function() {
        this.classList.add('button:active');
        setTimeout(() => {
            this.classList.remove('button:active');
        }, 200);
    });

    // Search functionality
    document.getElementById("searchInput").addEventListener("keyup", function() {
        var filter = this.value.toUpperCase();
        var table = document.getElementById("patientTable");
        var rows = table.getElementsByTagName("tr");

        for (var i = 0; i < rows.length; i++) {
            var cells = rows[i].getElementsByTagName("td");
            var match = false;

            for (var j = 0; j < cells.length; j++) {
                if (cells[j]) {
                    var textValue = cells[j].textContent || cells[j].innerText;
                    if (textValue.toUpperCase().indexOf(filter) > -1) {
                        match = true;
                        break;
                    }
                }
            }

            rows[i].style.display = match ? "" : "none";
        }
    });
</script>
</body>
</html>