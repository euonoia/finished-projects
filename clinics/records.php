<?php
session_start();
include("connect.php");
include("config.php");

// Fetch doctors for dropdown (MOVE THIS TO THE TOP)
$doctor_sql = "SELECT * FROM doctors";
$doctor_query = $dbh->prepare($doctor_sql);
$doctor_query->execute();
$doctors = $doctor_query->fetchAll(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="img/cropped.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="bahay/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css">
    <title>Walk In</title>
    <style>
        body, input, select, button {
    font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
}
.main-title, h2#modalTitle {
    font-size: 2rem;
    font-weight: 700;
    letter-spacing: 1px;
}
        table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }
            th, td {
                border: 1px solid black;
                padding: 10px;
                text-align: left;
            }
            th {
                background-color: var(--body-color);
            }
            tbody tr:nth-child(even) {
                background: #f7f9fa;
            }
            tbody tr:hover {
                background: #e6f7ff;
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

        /* Modern modal content */
.modal-content {
    background: #fff;
    border-radius: 24px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.15);
    padding: 28px 28px 20px 28px; /* reduced vertical padding */
    max-width: 420px;
    width: 95%;
    margin: 0 auto;
    margin-top: 60px; /* less top margin */
    animation: fadeIn 0.3s;
    border: 1px solid #e3e8ee;
    position: relative;
}

        @keyframes fadeIn {
    from { opacity: 0; transform: translateY(40px);}
    to { opacity: 1; transform: translateY(0);}
}
        /* Modern input group */
.input-group {
    margin-bottom: 10px; /* less margin */
    display: flex;
    flex-direction: column;
}

.input-group label {
    font-size: 1rem;
    font-weight: 500;
    margin-bottom: 6px;
    color: #333;
    letter-spacing: 0.5px;
}

.input-group input,
.input-group select {
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 1rem;
    background: #f7f9fa;
    transition: border 0.2s;
    margin-bottom: 2px;
}

.input-group input:focus,
.input-group select:focus {
    border: 1.5px solid #43a047;
    outline: none;
    background: #fff;
}
        /* Save button */
.button {
    margin-top: 10px;
    padding: 10px 24px;
    font-size: 1rem;
    margin-top: 12px;
    box-shadow: 0 2px 8px rgba(76,175,80,0.13);
    border-radius: 8px;
    font-size: 1.1rem;
    padding: 12px 28px;
    font-weight: 600;
}
        /* Modal title */
#modalTitle {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 12px; /* less margin */
    color: #222;
    letter-spacing: 1px;
}
        /* Close button */
.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    border: none;
    background: none;
    position: absolute;
    right: 24px;
    top: 18px;
    cursor: pointer;
    transition: color 0.2s;
}
.close:hover,
.close:focus {
    color: #e53935;
    text-decoration: none;
}
        .edit-btn, .delete-btn {
    background: none;
    border: none;
    cursor: pointer;
    margin-right: 6px;
    font-size: 18px;
    color: #388e3c;
    transition: color 0.2s;
    padding: 4px 6px;
}
.delete-btn {
    color: #e53935;
}
.edit-btn:hover {
    color: #1976d2;
}
.delete-btn:hover {
    color: #b71c1c;
}
        .edit-btn {         
            cursor: pointer;
        }
        .edit-btn img, .delete-btn img,
.edit-btn svg, .delete-btn svg {
    width: 1.5px;
    height: 1.5px;
    vertical-align: middle;
}
.edit-btn i, .delete-btn i {
    font-size: 18px;
    vertical-align: middle;
}
#deleteModal .button {
    min-width: 90px;
    box-shadow: none;
    font-weight: 600;
}
#deleteModal .button:hover {
    opacity: 0.9;
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
                            <a class="nav-link dropdown-title">Manage</a>
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
            <div class="main-content" style="padding: 0;">
            
            <div class="search-container" style="margin-bottom: 20px; display: flex; align-items: center; justify-content: flex-start; width: 100%; padding-left: 32px; padding-top: 20px;">
                <label for="searchInput" class="search-label" style="font-size: 16px; margin-right: 10px;">Search:</label>
                <input type="text" id="searchInput" class="search-input" placeholder="" style="flex: 1 1 300px; max-width: 400px; min-width: 180px; padding: 10px; font-size: 16px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

    <div style="overflow-x:auto; overflow-y:auto; width:100%; max-height:60vh; padding: 0 32px;">
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
        if ($results && count($results) > 0) {
            foreach ($results as $result) { ?>
                <tr>
                    <td><?php echo htmlentities($cnt); ?></td>
                    <td><?php echo htmlentities(trim($result->firstName)); ?></td>
                    <td><?php echo htmlentities(trim($result->lastName)); ?></td>
                    <td><?php echo htmlentities($result->studentID); ?></td>
                    <td><?php echo htmlentities($result->age); ?></td>
                    <td><?php echo htmlentities(trim($result->gender)); ?></td>
                    <td><?php echo htmlentities(trim($result->condition)); ?></td>
                    <td><?php echo htmlentities($result->date); ?></td>
                    <td>
                        <button class="edit-btn"
    data-id="<?php echo htmlentities($result->id); ?>"
    data-firstname="<?php echo htmlentities(trim($result->firstName)); ?>"
    data-lastname="<?php echo htmlentities(trim($result->lastName)); ?>"
    data-age="<?php echo htmlentities($result->age); ?>"
    data-gender="<?php echo htmlentities(trim($result->gender)); ?>"
    data-condition="<?php echo htmlentities(trim($result->condition)); ?>"
    data-date="<?php echo htmlentities($result->date); ?>"
    data-doctor_id="<?php echo htmlentities($result->doctor_id); ?>">
    <i class="fa fa-pen" title="Edit"></i>
</button>
<button class="delete-btn" data-id="<?php echo htmlentities($result->id); ?>">
    <i class="fa fa-trash" title="Delete"></i>
</button>
                    </td>
                </tr>
        <?php $cnt++; } } else { ?>
            <tr><td colspan="10" style="text-align:center; color:#888;">No patient records found.</td></tr>
        <?php } ?>
        </tbody>
    </table>
            </div>
            </div>
</main>
<div id="editModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2 id="modalTitle">Edit Patient Record</h2>
<hr style="border: none; border-top: 1.5px solid #e3e8ee; margin-bottom: 18px;">
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
      <div class="input-group">
        <label for="editAssignedDoctor">Assigned Doctor: </label>
        <select id="editAssignedDoctor" name="doctor_id" required>
            <option value="">&#128104;&#8205;&#9877;&#65039; Select Doctor</option>
            <?php foreach ($doctors as $doc): ?>
                <option value="<?php echo $doc->id; ?>">
                    &#128104;&#8205;&#9877;&#65039; <?php echo htmlentities($doc->name); ?>
                </option>
            <?php endforeach; ?>
        </select>
      </div>
      <button type="submit" class="button">Save Changes</button>
    </form>
  </div>
</div>
<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal">
  <div class="modal-content" style="max-width: 350px; text-align: center;">
    <button class="close" type="button" id="closeDeleteModal">&times;</button>
    <div style="font-size: 2.5rem; color: #e53935; margin-bottom: 12px;">
      <i class="fa fa-trash"></i>
    </div>
    <h2 style="font-size: 1.3rem; margin-bottom: 12px;">Delete Patient Record?</h2>
    <p style="color: #555; margin-bottom: 24px;">Are you sure you want to delete this patient record? This action cannot be undone.</p>
    <button id="confirmDeleteBtn" class="button" style="background: #e53935; margin-right: 10px;">Delete</button>
    <button id="cancelDeleteBtn" class="button" style="background: #f7f9fa; color: #222; border: 1px solid #ccc;">Cancel</button>
  </div>
</div>
<script src="bahay/script.js"></script>
<script>
    


// Search functionality (only one event listener)
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
        document.getElementById("editAssignedDoctor").value = this.getAttribute("data-doctor_id");
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
    var doctor_id = document.getElementById("editAssignedDoctor").value;

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
    xhr.send("id=" + id + "&firstName=" + firstName + "&lastName=" + lastName + "&age=" + age + "&gender=" + gender + "&condition=" + condition + "&date=" + date + "&doctor_id=" + doctor_id);
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

// Delete functionality
let deleteModal = document.getElementById("deleteModal");
let confirmDeleteBtn = document.getElementById("confirmDeleteBtn");
let cancelDeleteBtn = document.getElementById("cancelDeleteBtn");
let closeDeleteModal = document.getElementById("closeDeleteModal");
let rowToDelete = null;
let deleteId = null;

document.querySelectorAll('.delete-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        deleteId = this.getAttribute('data-id');
        rowToDelete = this.closest('tr');
        deleteModal.style.display = "block";
    });
});

confirmDeleteBtn.onclick = function() {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "delete-patient.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            if (xhr.responseText.trim() === "success") {
                rowToDelete.remove();
            } else {
                alert("Failed to delete record.");
            }
            deleteModal.style.display = "none";
        }
    };
    xhr.send("id=" + deleteId);
};

cancelDeleteBtn.onclick = closeDeleteModal.onclick = function() {
    deleteModal.style.display = "none";
};

window.onclick = function(event) {
    if (event.target == deleteModal) {
        deleteModal.style.display = "none";
    }
};


</script>
    <script src="bahay/script.js"></script>
</body>
</html>