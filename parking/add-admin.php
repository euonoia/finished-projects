<?php
session_start();
include("db/connect.php");
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
    <title>Add admin</title>
    <style>
        .main-content{
    padding: 20px;
    margin: 0;
    width: 100%;
    height: 74vh;
    border-radius: 20px;
    box-shadow: 0 2px 24px;
    overflow: hidden;
 } 
    h2{
        font-size: 1.5rem;
        font-weight: 500;
        color: #4f4f50;
        text-align: center;
    } 
    .container {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
    }
    .form-container{
        width: 80%;
        max-width: 400px;
        padding: 15px;
        border-radius: 8px;
       
        background-color: #f9f9f9;
        height: 450px;
    }
    
    label{
        font-size: 13px;
        font-weight: 500;
        color: #4f4f50;
    }
    input{
        padding: 1px;
        border-radius: 7px;
        border: 1px solid #ccc;
        font-size: 13px;
        color: #4f4f50;
        background-color: #fff;
        transition: all 0.3s ease;
    }
    input:focus{
        outline: none;
        border-color: #000;
    }
    .btn{
        padding: 7px 14px;
        border-radius: 7px;
        border: none;
        background-color: #001845;
        color: white;
        font-size: 13px;
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    .admin-table th,
    .admin-table td {
        padding: 10px;
        text-align: left;
    }
    
    .admin-table th {
        background-color: #f2f2f2;
        font-weight: bold;
    }
    
    .admin-table tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    .admin-table tr:hover {
        background-color: #e6e6e6;
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
            <img src="images/logi.png" alt="logi">
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
            <h1 class="main-title">ADMIN</h1>
         
        </header>

        <section class="content">
            <div class="main-content">
           
                <div class="container" id="signup" >
                    
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM users";
                        $result = mysqli_query($conn, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['firstname'] . "</td>";
                            echo "<td>" . $row['lastname'] . "</td>";
                            echo "<td>" . $row['email'] . "</td>";
                            
                        }
                        ?>
                    </tbody>
                </table>

            <div class="form-container">
            <form method="post" action="db/part2.php">
                <div class="input-group">
                    <h2>Add Admin</h2>
                <i class="fas fa-user"></i>
                <label for="fname">First Name</label>
                <input type="text" name="fName" id="fName" placeholder="First Name" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <label for="lName">Last Name</label>
                    <input type="text" name="lName" id="lName" placeholder="Last Name" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Password" required>
                    </div>
            <input class="btn" type="submit" value="Submit" name="signUp">
        </div>
      </form>
</div>


                </div>
        </section>
</main>
   

    <script src="bahay/script.js"></script>
</body>
</html>