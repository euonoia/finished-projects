<?php
session_start();
include("connect.php");
include("config.php");

$sql = "SELECT * FROM appointments";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_OBJ);

// Fetch doctors
$doctorStmt = $dbh->prepare("SELECT * FROM doctors");
$doctorStmt->execute();
$doctors = $doctorStmt->fetchAll(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="img/cropped.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/logi.png" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="bahay/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <title>Appointment</title>
    <style>
        table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 50px;
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
            z-index: 1000;
            width: 100vw;
            height: 100vh;
            background: rgba(0,0,0,0.3);
            justify-content: center;
            align-items: center;
            transition: background 0.2s;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
          background: #fff;
          border-radius: 20px;
          padding: 32px 40px;
          box-shadow: 0 8px 32px rgba(0,0,0,0.18);
          text-align: center;
          position: relative;
          min-width: 320px;
          animation: fadeIn 0.3s;
        }

        .modal-content .success-icon {
            font-size: 48px;
            color: #4caf50;
            margin-bottom: 12px;
            display: block;
        }

        .edit-modal-content {
    max-width: 350px;
    width: 100%;
    padding: 32px 28px 24px 28px;
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.18);
    background: #fff;
    position: relative;
    text-align: left;
}

.modal-title {
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 18px;
    text-align: center;
}

.modal-group {
    display: flex;
    flex-direction: column;
    margin-bottom: 16px;
}

.modal-group label {
    font-size: 1rem;
    margin-bottom: 6px;
    color: #222;
    font-weight: 500;
}

.modal-group input[type="text"],
.modal-group input[type="email"],
.modal-group input[type="date"],
.modal-group input[type="time"] {
    padding: 10px 12px;
    border: 1.5px solid #d1d5db;
    border-radius: 8px;
    font-size: 1rem;
    background: #f7f9fa;
    transition: border 0.2s;
}

.modal-group input:focus {
    border: 1.5px solid #43a047;
    outline: none;
    background: #fff;
}

.modal-save-btn {
    width: 100%;
    background: #43a047;
    color: #fff;
    border-radius: 8px;
    padding: 12px 0;
    font-size: 1.1rem;
    font-weight: bold;
    margin-top: 10px;
    border: none;
    transition: background 0.2s;
}

.modal-save-btn:hover {
    background: #388e3c;
    color: #fff;
}

        .close {
            position: absolute;
            top: 18px;
            right: 24px;
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.2s;
        }
        .close:hover {
            color: #333;
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
        .main-content{
    padding: 20px;
    margin: 0;
    width: 100%;
    height: 74vh;
    border-radius: 20px;
    box-shadow: 0 2px 24px;
    overflow: hidden;
 }  

 /* Fade in animation */
@keyframes fadeIn {
    from { transform: translateY(-30px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}
    /* Modern doctor dropdown */
    .doctor-select {
    padding: 8px 12px;
    border: 1.5px solid #d1d5db;
    border-radius: 8px;
    font-size: 1rem;
    background: #f7f9fa;
    transition: border 0.2s;
    min-width: 150px;
}

.doctor-select:focus {
    border: 1.5px solid #43a047;
    outline: none;
    background: #fff;
}
.edit-icon {
    color: #43a047;
    font-size: 1.2rem;
    transition: color 0.2s, transform 0.2s;
    vertical-align: middle;
}
.edit-btn:hover .edit-icon {
    color: #2e7031;
    transform: scale(1.15);
}
@media (max-width: 600px) {
    .edit-icon {
        font-size: 1.2rem;
    }
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
            <h1 class="main-title">APPOINTMENT</h1>
         
        </header>


        <section class="content">
            <div class="main-content">
           


                <div class="search-container">
                    <label for="searchInput" class="search-label">Search:</label>
                    <input type="text" id="searchInput" class="search-input" placeholder="">
                    <br>
                </div>

                <script>
                    document.getElementById('searchInput').addEventListener('input', function() {
                        const searchValue = this.value.toLowerCase();
                        const rows = document.querySelectorAll('table tbody tr');

                        rows.forEach(row => {
                            const name = row.cells[1].textContent.toLowerCase();
                            const email = row.cells[2].textContent.toLowerCase();
                            const number = row.cells[3].textContent.toLowerCase();

                            if (name.includes(searchValue) || email.includes(searchValue) || number.includes(searchValue)) {
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                        });
                       });
                </script>

                <table>
                   <thead>
                <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Number</th>
                <th>Date</th>
                <th>Student ID</th>
                <th>Doctor</th>
                <th>Diagnosis</th>
                <th>Action</th>
</tr>
</thead>
<tbody id="editableTableBody">
<?php $cnt = 1; foreach ($results as $result) { ?>
<tr>
    <td><?php echo htmlentities($cnt); ?></td>
    <td><?php echo htmlentities($result->name); ?></td>
    <td><?php echo htmlentities($result->email); ?></td>
    <td><?php echo htmlentities($result->phone); ?></td>
    <td><?php echo date('Y-m-d', strtotime($result->date)); ?></td>
    <td><?php echo htmlentities($result->studentId); ?></td>
    <td>
        <?php
        $doctorName = '';
        foreach ($doctors as $doctor) {
            if ($doctor->id == $result->doctor_id) {
                $doctorName = htmlentities($doctor->name);
                break;
            }
        }
        echo $doctorName;
        ?>
    </td>
       <td><?php echo htmlentities($result->Diagnosis); ?></td>

    <td>
        <button class="edit-btn"
            data-id="<?php echo $result->id; ?>"
            data-name="<?php echo htmlentities($result->name); ?>"
            data-email="<?php echo htmlentities($result->email); ?>"
            data-phone="<?php echo htmlentities($result->phone); ?>"
            data-date="<?php echo date('Y-m-d', strtotime($result->date)); ?>"
            data-doctor="<?php echo $result->doctor_id; ?>"
            data-diagnosis="<?php echo htmlentities($result->Diagnosis ?? ''); ?>"
            style="background:none;border:none;cursor:pointer;padding:0 4px;vertical-align:middle;display:inline-flex;align-items:center;">
            <i class="fa fa-pen edit-icon"></i>
        </button>
        <button class="comment-btn" data-id="<?php echo $result->id; ?>" style="background:none;border:none;cursor:pointer;padding:0 4px;vertical-align:middle;display:inline-flex;align-items:center;">
            <i class="fa fa-comment" style="color:#43a047;font-size:1.2rem;"></i>
        </button>
        <button class="delete-btn" data-id="<?php echo $result->id; ?>" style="background:none;border:none;cursor:pointer;padding:0 4px;vertical-align:middle;display:inline-flex;align-items:center;">
            <i class="fa fa-trash" style="color:#e53935;font-size:1.2rem;"></i>
        </button>
    </td>
    </tr>
    <?php $cnt++; } ?>
    </tbody>
 </table>

                <!-- Edit Modal -->
<div id="editModal" class="modal">
  <div class="modal-content edit-modal-content">
    <span class="close" id="closeEditModalBtn">&times;</span>
    <h2 class="modal-title">Edit Appointment</h2>
    <form id="editForm">
      <input type="hidden" id="editId" name="id">
      <div class="modal-group">
        <label for="editName">Name:</label>
        <input type="text" id="editName" name="name" required>
      </div>
      <div class="modal-group">
        <label for="editEmail">Email:</label>
        <input type="email" id="editEmail" name="email" required>
      </div>
      <div class="modal-group">
        <label for="editPhone">Number:</label>
        <input type="text" id="editPhone" name="phone" required>
      </div>
      <div class="modal-group">
        <label for="editDate">Date:</label>
        <input type="date" id="editDate" name="date" required>
      </div>
      <div class="modal-group">
        <label for="editDoctor">Assign Doctor:</label>
        <select id="editDoctor" name="doctor_id" class="doctor-select">
          <option value="">Select Doctor</option>
          <?php foreach ($doctors as $doctor) { ?>
            <option value="<?php echo $doctor->id; ?>">
              <?php echo htmlentities($doctor->name); ?>
            </option>
          <?php } ?>
        </select>
      </div>
      <div class="modal-group">
        <label for="editDiagnosis">Diagnosis:</label>
        <input type="text" id="editDiagnosis" name="diagnosis">
      </div>
      <button type="submit" class="button modal-save-btn">Save</button>
    </form>
  </div>
</div>
                <!-- Success Modal -->
                <div id="successModal" class="modal">
                  <div class="modal-content">
                    <span class="close" id="closeModalBtn">&times;</span>
                    <span class="success-icon"><i class="fa fa-check-circle"></i></span>
                    <h2 style="margin-top:0;">Saved Changes</h2>
                  </div>
                </div>

                <!-- Delete Confirmation Modal -->
                <div id="deleteModal" class="modal">
                  <div class="modal-content edit-modal-content" style="max-width: 350px; text-align: center;">
                    <span class="close" id="closeDeleteModalBtn">&times;</span>
                    <div style="font-size: 2.5rem; color: #e53935; margin-bottom: 12px;">
                      <i class="fa fa-trash"></i>
                    </div>
                    <h2 class="modal-title" style="font-size: 1.3rem; margin-bottom: 12px;">Delete Appointment?</h2>
                    <p style="color: #555; margin-bottom: 24px;">Are you sure you want to delete this appointment? This action cannot be undone.</p>
                    <button id="confirmDeleteBtn" class="button" style="background: #e53935; margin-right: 10px;">Delete</button>
                    <button id="cancelDeleteBtn" class="button" style="background: #f7f9fa; color: #222; border: 1px solid #ccc;">Cancel</button>
                  </div>
                </div>


                </div>
        </section>
</main>
<script src="bahay/script.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    // Close modal when clicking on close button or outside the modal
    document.getElementById('closeModalBtn').onclick = function() {
        document.getElementById('successModal').classList.remove('show');
    };
    window.onclick = function(event) {
        const modal = document.getElementById('successModal');
        if (event.target == modal) {
            modal.classList.remove('show');
        }
    };

    // Edit button logic
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('editId').value = this.dataset.id;
            document.getElementById('editName').value = this.dataset.name;
            document.getElementById('editEmail').value = this.dataset.email;
            document.getElementById('editPhone').value = this.dataset.phone;
            document.getElementById('editDate').value = this.dataset.date;
            document.getElementById('editDoctor').value = this.dataset.doctor || '';
            document.getElementById('editDiagnosis').value = this.dataset.diagnosis || '';
            document.getElementById('editModal').classList.add('show');
        });
    });

    // Close edit modal
    document.getElementById('closeEditModalBtn').onclick = function() {
        document.getElementById('editModal').classList.remove('show');
    };
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('editModal');
        if (event.target == modal) {
            modal.classList.remove('show');
        }
    });

    // Save changes from modal
    document.getElementById('editForm').onsubmit = function(e) {
        e.preventDefault();

        let dateValue = document.getElementById('editDate').value;
        // Convert DD/MM/YYYY to YYYY-MM-DD if needed
        if (dateValue.includes('/')) {
            const [day, month, year] = dateValue.split('/');  // Correct order for DD/MM/YYYY
            dateValue = `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
        }

        const formData = {
            id: document.getElementById('editId').value,
            name: document.getElementById('editName').value,
            email: document.getElementById('editEmail').value,
            phone: document.getElementById('editPhone').value,
            date: dateValue,
            doctor_id: document.getElementById('editDoctor').value,
            diagnosis: document.getElementById('editDiagnosis').value
        };

        const saveBtn = document.querySelector('.modal-save-btn');
        saveBtn.disabled = true;
        saveBtn.textContent = "Saving...";

        fetch('update_appointment.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData)
        })
        .then(res => res.json())
        .then(data => {
            saveBtn.disabled = false;
            saveBtn.textContent = "Save";
            if (data.success) {
                document.getElementById('editModal').classList.remove('show');
                document.getElementById('successModal').querySelector('h2').textContent = "Saved Changes";
                document.getElementById('successModal').classList.add('show');
                setTimeout(() => location.reload(), 1200);
            } else {
                alert("Failed to update appointment: " + (data.error || ""));
            }
        })
        .catch(() => {
            saveBtn.disabled = false;
            saveBtn.textContent = "Save";
            alert("Network error. Please try again.");
        });
    };

    // Comment button logic - show a modal form instead of prompt
    document.querySelectorAll('.comment-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const appointmentId = this.getAttribute('data-id');
            // Create modal if not exists
            let commentModal = document.getElementById('commentModal');
            if (!commentModal) {
                commentModal = document.createElement('div');
                commentModal.id = 'commentModal';
                commentModal.className = 'modal';
                commentModal.innerHTML = `
                  <div class="modal-content edit-modal-content">
                    <span class="close" id="closeCommentModalBtn">&times;</span>
                    <h2 class="modal-title">Add Comment</h2>
                    <form id="commentForm">
                      <input type="hidden" id="commentAppointmentId" name="id">
                      <div class="modal-group">
                        <label for="commentText">Comment:</label>
                        <textarea id="commentText" name="comment" rows="4" style="width:100%;padding:10px;border-radius:8px;border:1.5px solid #d1d5db;background:#f7f9fa;font-size:1rem;" required></textarea>
                      </div>
                      <button type="submit" class="button modal-save-btn">Save Comment</button>
                    </form>
                  </div>
                `;
                document.body.appendChild(commentModal);

                // Close logic
                document.getElementById('closeCommentModalBtn').onclick = function() {
                    commentModal.classList.remove('show');
                };
                window.addEventListener('click', function(event) {
                    if (event.target == commentModal) {
                        commentModal.classList.remove('show');
                    }
                });

                // Submit logic
                document.getElementById('commentForm').onsubmit = function(e) {
                    e.preventDefault();
                    const comment = document.getElementById('commentText').value.trim();
                    const id = document.getElementById('commentAppointmentId').value;
                    if (comment === '') return;
                    const saveBtn = this.querySelector('.modal-save-btn');
                    saveBtn.disabled = true;
                    saveBtn.textContent = "Saving...";
                    fetch('update_appointment.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: id, comment: comment, action: 'add_comment' })
                    })
                    .then(res => res.json())
                    .then(data => {
                        saveBtn.disabled = false;
                        saveBtn.textContent = "Save Comment";
                        if (data.success) {
                            commentModal.classList.remove('show');
                            document.getElementById('successModal').querySelector('h2').textContent = "Comment Saved";
                            document.getElementById('successModal').classList.add('show');
                            setTimeout(() => location.reload(), 1200);
                        } else {
                            alert('Failed to save comment.');
                        }
                    })
                    .catch(() => {
                        saveBtn.disabled = false;
                        saveBtn.textContent = "Save Comment";
                        alert('Network error. Please try again.');
                    });
                };
            }
            // Set appointment id and clear textarea
            document.getElementById('commentAppointmentId').value = appointmentId;
            document.getElementById('commentText').value = '';
            commentModal.classList.add('show');
        });
    });

    // Delete button logic
    let deleteAppointmentId = null;
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            deleteAppointmentId = this.dataset.id;
            document.getElementById('deleteModal').classList.add('show');
        });
    });

    // Close delete modal
    document.getElementById('closeDeleteModalBtn').onclick = function() {
        document.getElementById('deleteModal').classList.remove('show');
    };
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('deleteModal');
        if (event.target == modal) {
            modal.classList.remove('show');
        }
    });

    // Confirm delete
    document.getElementById('confirmDeleteBtn').onclick = function() {
        fetch('delete-appointment.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: deleteAppointmentId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById('deleteModal').classList.remove('show');
                document.getElementById('successModal').querySelector('h2').textContent = "Appointment Deleted";
                document.getElementById('successModal').classList.add('show');
                setTimeout(() => location.reload(), 1200);
            } else {
                alert('Failed to delete appointment.');
            }
        })
        .catch(() => {
            alert('Network error. Please try again.');
        });
    };

});
    document.addEventListener('DOMContentLoaded', function() {
        const closeModalBtn = document.getElementById('closeModalBtn');
        const successModal = document.getElementById('successModal');

        closeModalBtn.onclick = function() {
            successModal.classList.remove('show');
        };

        window.onclick = function(event) {
            if (event.target == successModal) {
                successModal.classList.remove('show');
            }
        };
    });
  
</script>


</body>
</html>

