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
    <title>History</title>
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
        button.customer {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 5px 10px;
            font-size: 12px;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button.customer:hover {
            background-color: #45a049;
        }
        button.admin {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 5px 10px;
            font-size: 12px;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button.admin:hover {
            background-color: #45a049;
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
                                <a href="Subscription.php" class="nav-link dropdown-link">subscription</a>
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
            <h1 class="main-title">HISTORY</h1>
         
        </header>
        <section class="content">
            <div class="main-content">
        
           
            <div class="customer" id="customer">
             
            <div id="search">
            <label for="searchInput" class="search-label">Search:</label>
            <input type="text" id="searchInput" class="search-input" placeholder="">
            <button class="admin" onclick="showadmin()">CUSTOMER</button>
            <br>
            </div>

            <table>
                <thead>
                <tr>
                    <th scope="col">Student Number</th>
                    <th scope="col">Firstname</th>
                    <th scope="col">Lastname</th>
                    <th scope="col">Plate Number</th>
                    <th scope="col">Vehicle Type</th>
                    <th scope="col">Customer Type</th>
                    <th scope="col">Subscription Type</th>
                    <th scope="col">Time in</th>
                    <th scope="col">Time out</th>
                </tr>
                </thead>
                <tbody id="customerTable">
                <?php 
                    // Nagpreprare ng data sa customer para makita history haha
                    $stmt = $conn->prepare("
                    SELECT 
                        customer.studentNumber AS studentNumber,
                        customer.firstname AS firstname,
                        customer.lastname AS lastname,
                        customer.plate AS plate,
                        customer.vehicleType AS vehicleType,
                        customer.customerType AS customerType,
                        customer.subscriptionType AS subscriptionType,
                        login_history.login_time AS lastLoginTime,
                        (SELECT logout_time FROM logout_history 
                         WHERE id = customer.id AND logout_time > login_history.login_time
                         ORDER BY logout_time ASC LIMIT 1) AS lastLogoutTime
                    FROM 
                        customer
                    LEFT JOIN 
                        login_history
                    ON 
                        customer.id = login_history.id
                    ORDER BY login_history.login_time DESC
                    ");
                    $stmt->execute();

                    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

                    if (count($result) > 0) {
                    foreach ($result as $row) { 
                        $studentNumber = $row['studentNumber'];
                        $firstname = $row['firstname'];
                        $lastname = $row['lastname'];
                        $plate = $row['plate'];
                        $vehicleType = $row['vehicleType'];
                        $customerType = $row['customerType'];
                        $subscriptionType = $row['subscriptionType'];
                        $lastLoginTime = $row['lastLoginTime']; 
                        $lastLogoutTime = $row['lastLogoutTime'];

                        // nagiiba kulay maangas
                        $subscriptionColor = '';
                        if ($subscriptionType === 'premium') {
                        $subscriptionColor = 'green';
                        } elseif ($subscriptionType === 'regular') {
                        $subscriptionColor = 'blue';
                        } elseif ($subscriptionType === 'Expired') {
                        $subscriptionColor = 'red';
                        }
                ?>
                    <tr>
                    <td id="studentNumber-<?= htmlspecialchars($plate) ?>"><?php echo htmlspecialchars($studentNumber) ?></td>
                    <td id="firstname-<?= htmlspecialchars($plate) ?>"><?php echo htmlspecialchars($firstname) ?></td>
                    <td id="lastname-<?= htmlspecialchars($plate) ?>"><?php echo htmlspecialchars($lastname) ?></td>
                    <td id="plate-<?= htmlspecialchars($plate) ?>"><?php echo htmlspecialchars($plate) ?></td>
                    <td id="vehicleType-<?= htmlspecialchars($plate) ?>"><?php echo htmlspecialchars($vehicleType) ?></td>
                    <td id="customerType-<?= htmlspecialchars($plate) ?>"><?php echo htmlspecialchars($customerType) ?></td>
                    <td id="subscriptionType-<?= htmlspecialchars($plate) ?>" style="color: <?php echo $subscriptionColor; ?>;">
                        <?php echo htmlspecialchars($subscriptionType) ?>
                    </td>
                    <td id="lastLoginTime-<?= htmlspecialchars($plate) ?>"><?php echo $lastLoginTime ? htmlspecialchars($lastLoginTime) : 'No login history'; ?></td>
                    <td id="lastLogoutTime-<?= htmlspecialchars($plate) ?>">
                    <?php echo $lastLogoutTime ? htmlspecialchars($lastLogoutTime) : 'No Time out history'; ?>
                    </td>
                    </tr>    
                <?php
                    }
                    } else {
                ?>
                    <tr>
                    <td colspan="9" style="text-align: center;">No data available</td>
                    </tr>
                <?php
                    }
                ?>
                </tbody>
            </table>

            </div>
            
            <div class="admin" id="admin">
            <div id="search">
            <label for="searchInput" class="search-label">Search:</label>
            <input type="text" id="searchInput" class="search-input" placeholder="">
            <button class="customer" onclick="showcustomer()">ADMIN</button>
            <br>
            </div>
            
                <table>
                <thead>
                    <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Firstname</th>
                    <th scope="col">Lastname</th>
                    <th scope="col">email</th>
                    <th scope="col">Last Login Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // eto rin nagpreprepare ng data sa users database para sa history haha
                    $stmt = $conn->prepare("
                        SELECT 
                        users.id AS id,
                        users.firstname AS firstname,
                        users.lastname AS lastname,
                        users.email AS email,
                        user_history.login_time AS lastLoginTime
                        FROM 
                        users
                        LEFT JOIN 
                        user_history
                        ON 
                        users.id = user_history.id
                    ");
                    $stmt->execute();

                    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

                    if (count($result) > 0) {
                        foreach ($result as $row) { 
                        $id = $row['id'];
                        $firstname = $row['firstname'];
                        $lastname = $row['lastname']; 
                        $email = $row['email'];
                        $lastLoginTime = $row['lastLoginTime']; // eto malupet napupunta ito sa login history kailangan may lastLoginTime haha
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($id) ?></td>
                        <td><?php echo htmlspecialchars($firstname) ?></td>
                        <td><?php echo htmlspecialchars($lastname) ?></td>
                        <td><?php echo htmlspecialchars($email) ?></td>
                        <td><?php echo $lastLoginTime ? htmlspecialchars($lastLoginTime) : 'No login history'; ?></td>
                    </tr>    
                    <?php
                        }
                    } else {
                    ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">No data available</td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
                </table>

            </div>

            </div>
        </section>
</main>

    <script src="bahay/script.js"></script>
    <script>
        /*tinatago niya yung customer tapos admin angas e haha*/
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
         /*search bar eto kinuha ko sa google sakit sa ulo e haha*/
        document.getElementById("searchInput").addEventListener("keyup", function() {
    var filter = this.value.toUpperCase();
    var table = document.getElementById("customerTable");
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
 /*scroll function to para hindi humiwalay sa search haha*/
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    document.getElementById("search").style.top = "0";
  } else {
    document.getElementById("search").style.top = "-50px";
  }
}
});
    </script>
</body>
</html>