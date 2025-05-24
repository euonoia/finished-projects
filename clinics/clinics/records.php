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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="bahay/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css">
    <title>Document</title>
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
            background-color: var(--body-color);
        }
        tbody tr:nth-child(even) {
            background-color: var(--body-color);
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
          background-color: white;
          box-shadow: 0 2px 24px var(--shadow-color);
          padding: 30px;  
          border-radius: 30px;
           max-width: 680px;
           width: 80%;
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
        .edit-btn {         
            cursor: pointer;
        }
    </style>
</head>
<body>
            <button class="sidebar-menu-button">
            <span class="material-symbols-rounded">menu</span>
            </button>
    <aside class="sidebar">
           
        <header class="sidebar-header">
           <a href="#" class="header-logo">
            <img src="img/cropped.PNG" alt="Rxecll">
           </a>
            <button class="sidebar-toggler">
            <span class="material-symbols-rounded">chevron_left</span>
            </button>
        </header>
        <nav class="sidebar-nav">
                <ul class="nav-list primary-nav">
                    <li class="nav-item">
                        <a href="bahay.php" class="nav-link">
                            <span class="material-symbols-rounded">home</span>
                            <span class="nav-label">Dashboard</span>
                        </a>
                        </li> 
                        <li class="nav-item dropdown-container">
                        <a href="bahay.php" class="nav-link dropdown-toggle">
                            <span class="material-symbols-rounded">calendar_today</span>
                            <span class="nav-label">Tools</span>
                            <span class="dropdown-icon material-symbols-rounded">keyboard_arrow_down</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="nav-item">
                                <a  class="nav-link dropdown-title">Manage</a>
                            </li>
                            <li class="nav-item">
                                <a href="updates.php" class="nav-link dropdown-link">Appointment</a>
                            </li>
                            <li class="nav-item">
                                <a href="records.php" class="nav-link dropdown-link">Data</a>
                            </li>
                        </ul>
                        </li>
                    
                </ul>
                <ul class="nav-list secondary-nav">
                        <li class="nav-item">
                        <a href="logout.php" class="nav-link">
                            <span class="material-symbols-rounded">logout</span>
                            <span class="nav-label">logout</span>
                        </a>
                        </li>
                </ul>
        </nav>
    </aside>
    <main class="main">
        <header class="main-header">
            <h1 class="main-title">PATIENTS</h1>
         
        </header>

        <section class="content">
            <div class="main-content">
           
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
                <th>Gender</th>
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
                        <button class="edit-btn" data-id="<?php echo htmlentities($result->id); ?>
                        " data-firstname="<?php echo htmlentities($result->firstName); ?>
                        " data-lastname="<?php echo htmlentities($result->lastName); ?>
                        " data-age="<?php echo htmlentities($result->age); ?>
                        " data-gender="<?php echo htmlentities($result->gender); ?>
                        " data-condition="<?php echo htmlentities($result->condition); ?>
                        " data-date="<?php echo htmlentities($result->date); ?>">
                        <i class="ri-edit-line" title="Edit Record"></i></button>
                    </td>
                </tr>
        <?php $cnt++; } } ?>
        </tbody>
    </table>
            </div>
            </div>
</main>
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
        <input type="number" id="editAge" name="age" min="1" required>
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
<script>
    //modal
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
    <script src="bahay/script.js"></script>
</body>
</html>