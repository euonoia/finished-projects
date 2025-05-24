<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html><?php
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
    <title>Dashboard</title>
    <style>
       form {
            display: flex;
            flex-direction: column;
            margin: 5px;
            padding: 5px;
            height: 150px;
            width: 100px;
        }
        form label {
            font-weight: bold;
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
                                <a class="nav-link dropdown-title">Manage</a>
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
            <h1 class="main-title">ADD PARKING SLOT</h1>

        </header>

        <section class="content">
            <div class="main-content">

                <form action="db/add-parking-slot.php" id="parkingslot" method="POST">
               
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" min="1" required>
                
                <button type="submit">Add Slot</button>
                </form>
                
            </div>
        </section>
</main>

    <script src="bahay/script.js"></script>
</body>
</html>