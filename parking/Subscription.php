<?php
session_start();
include("db/connect.php");
include("db/config.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/logi.png" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="bahay/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css">
    <title>Subscription</title>
    <style>
         table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 5px 10px gray;
            overflow: hidden;
        }
        thead{
            box-shadow: 0 5px 10px gray;
        }
        
        td{
            padding: 1rem ;
        }
        th {
            padding: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.1rem;
            font-size: 0.7rem;
            font-weight: 900;
        }
        
        tbody tr:nth-child(even) {
            background-color:white;
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
            padding: 4px;
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
            background-color:rgb(84, 139, 178); 
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
                .receipt-container {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 20px;
                    justify-content: center;
                }
                .receipt-item {
                    position: relative;
                    margin: 10px;
                    width: 200px;
                    height: 280px;
                    overflow: hidden;
                    border-radius: 10px;
                    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                }
                .receipt-image {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                }
                .receipt-overlay {
                    position: absolute;
                    bottom: 0;
                    left: 0;
                    right: 0;
                    background-color: rgba(0,0,0,0.7);
                    overflow: hidden;
                    width: 100%;
                    height: 0;
                    transition: .5s ease;
                }
                .receipt-item:hover .receipt-overlay {
                    height: 100%;
                }
                .view-btn {
                    color: white;
                    font-size: 16px;
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    text-align: center;
                    background-color: #4CAF50;
                    padding: 12px 24px;
                    border: none;
                    cursor: pointer;
                    border-radius: 5px;
                    margin: 5px;
                }
                  
    </style>
</head>
<body>
            <button class="sidebar-menu-button">
            <span class="material-symbols-rounded">menu</span>
            </button>
    <aside class="sidebar">
           
        <header class="sidebar-header">
           <a href="dashboard.php" class="header-logo">
            <img src="images/logi.PNG" alt="Rxecll">
           </a>
            <button class="sidebar-toggler">
            <span class="material-symbols-rounded">chevron_left</span>
            </button>
        </header>
        <nav class="sidebar-nav">
                <ul class="nav-list primary-nav">
                    <li class="nav-item">
                        <a href="dashboard.php" class="nav-link">
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
                                <a href="add-admin.php" class="nav-link dropdown-link">Add admin</a>
                            </li>
                            <li class="nav-item">
                                <a href="history.php" class="nav-link dropdown-link">History</a>
                            </li>
                            <li class="nav-item">
                                <a href="Subscription.php" class="nav-link dropdown-link">Subscription</a>
                            </li>
                            <li class="nav-item">
                                <a href="parking-slot.php" class="nav-link dropdown-link">Parking Slot</a>
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
            <h1 class="main-title">SUBSCRIPTION</h1>
         
        </header>

        <section class="content">
            <div class="main-content">

       
             <div class="customer" id="customer">
             
            <div id="search">
            <label for="searchInput" class="search-label">Search:</label>
            <input type="text" id="searchInput" class="search-input" placeholder="">
            <button class="admin" onclick="showadmin()">OPEN RECEIPT</button>
            <br>
            </div>

            <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Student Number</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Plate</th>
                <th>Contact</th>
                <th>Subscription</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="patientTable">
        <?php 
        $sql = "SELECT * from customer";
        $query = $dbh->prepare($sql);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        $cnt = 1;
        if ($query->rowCount() > 0) {
            foreach ($results as $result) { 
            $subscriptionColor = ($result->subscriptionType == 'premium') ? 'green' : (($result->subscriptionType == 'regular') ? 'blue' : 'gray');
            ?>
            <tr>
                <td><?php echo htmlentities($cnt); ?></td>
                <td><?php echo htmlentities($result->studentNumber); ?></td>
                <td><?php echo htmlentities($result->firstname); ?></td>
                <td><?php echo htmlentities($result->lastname); ?></td>
                <td><?php echo htmlentities($result->plate); ?></td>
                <td><?php echo htmlentities($result->contact); ?></td>
                <td style="color: <?php echo $subscriptionColor; ?>;"><?php echo htmlentities($result->subscriptionType); ?></td>
                <td>
                        <button class="edit-btn" data-id="<?php echo htmlentities($result->id); ?>"
                                data-studentnumber="<?php echo htmlentities($result->studentNumber); ?>"
                                data-firstname="<?php echo htmlentities($result->firstname); ?>"
                                data-lastname="<?php echo htmlentities($result->lastname); ?>"
                                data-plate="<?php echo htmlentities($result->plate); ?>"
                                data-contact="<?php echo htmlentities($result->contact); ?>"
                                data-subscriptiontype="<?php echo htmlentities($result->subscriptionType); ?>">
                            <i class="ri-edit-line" title="Edit Record"></i>
                        </button>
                    </td>
                </tr>
        <?php $cnt++; } } ?>
        </tbody>
    </table>

            </div>
            
            <div class="admin" id="admin">
            <div id="search">
            <label for="searchInput" class="search-label">Search:</label>
            <input type="text" id="searchInput" class="search-input" placeholder="">
            <button class="customer" onclick="showcustomer()">CHANGE SUBSCRIPTION</button>
            <br>
            </div>
            <div class="receipt-container">
                
                <?php
                    $res = mysqli_query($conn, "SELECT images.*, customer.studentNumber FROM images LEFT JOIN customer ON images.student_id = customer.id");
                    while ($row = mysqli_fetch_array($res)) {
                ?>
                <div class="receipt-item">
                    <img src="image/<?php echo $row['file']; ?>" alt="Receipt" class="receipt-image">
                    <div class="receipt-overlay">
                        <button class="view-btn" onclick="expandImage(this)">View</button>
                       
                    </div>
                </div>
                <?php } ?>
            </div>

           

            </div>
   
            </div>
        </section>
</main>
<div id="editModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2 id="modalTitle">Edit Subscription</h2>
    <form id="editForm">
      <input type="hidden" id="editId">
      <div class="input-group">
        <label for="editSubscriptionType">Subscription Type:</label>
        <input type="text" id="editSubscriptionType" name="subscriptionType" required>
      </div>
      <button type="submit" class="button">Save Changes</button>    
    <!-- IF WANT TO ADD DELETE BUTTON <button type="button" id="deleteButton" class="button" style="background-color: red;">Delete</button> -->
    </form>
  </div>
</div>
<script>
                        function expandImage(button) {
                            var img = button.closest('.receipt-item').querySelector('.receipt-image');
                            var expandedImg = document.createElement('img');
                            expandedImg.src = img.src;
                            expandedImg.style.position = 'fixed';
                            expandedImg.style.top = '50%';
                            expandedImg.style.left = '50%';
                            expandedImg.style.transform = 'translate(-50%, -50%)';
                            expandedImg.style.maxWidth = '90%';
                            expandedImg.style.maxHeight = '90%';
                            expandedImg.style.zIndex = '1000';
                            expandedImg.onclick = function() { this.remove(); };
                            document.body.appendChild(expandedImg);
                        }

    const customer = document.getElementById('customer');
         const admin = document.getElementById('admin');

         admin.style.display = "none";

         function showadmin() {
            admin.style.display = "";
            customer.style.display = "none";
        }
        function showcustomer() {
            admin.style.display = "none";
            customer.style.display = "";
        }
    // Modal
    
    var modal = document.getElementById("editModal");
    var btns = document.getElementsByClassName("edit-btn");
    var span = document.getElementsByClassName("close")[0];

    for (var i = 0; i < btns.length; i++) {
        btns[i].onclick = function() {
            modal.style.display = "block";
            document.getElementById("editId").value = this.getAttribute("data-id");
            document.getElementById("editSubscriptionType").value = this.getAttribute("data-subscriptiontype");
        }
    }

    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Handle form submission
    document.getElementById("editForm").onsubmit = function(event) {
        event.preventDefault();
        var id = document.getElementById("editId").value;
        var subscriptionType = document.getElementById("editSubscriptionType").value;

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "update-subscription.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById("modalTitle").textContent = 'Saved Changes';
                setTimeout(() => {
                    document.getElementById("modalTitle").textContent = 'Edit Subscription';
                    modal.style.display = "none";
                    location.reload();
                }, 2000);
            }
        };
        xhr.send("id=" + id + "&subscriptionType=" + subscriptionType);
    };

    // Handle delete button click
    document.getElementById("deleteButton").addEventListener("click", function() {
        var id = document.getElementById("editId").value;

        if (confirm("Are you sure you want to delete this record?")) {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "delete-patient.php?id=" + id, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    alert(xhr.responseText);
                    modal.style.display = "none";
                    location.reload();
                }
            };
            xhr.send();
        }
    });
</script>

</script>
    <script src="bahay/script.js"></script>
</body>
</html>