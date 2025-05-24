<?php
session_start();
include("connect.php");
include("config.php");
?>
<?php
$conditionsData = [];
try {
    $sql = "SELECT `condition`, COUNT(*) as count FROM patients GROUP BY `condition`";
    $query = $dbh->prepare($sql);
    $query->execute();
    $conditionsData = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Fetch total patients for the card
$totalPatients = 0;
try {
    $sql1 = "SELECT id FROM patients";
    $query1 = $dbh->prepare($sql1);
    $query1->execute();
    $totalPatients = $query1->rowCount();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$recentActivity = [];
try {
    $sql = "SELECT 
            id,
            CONCAT(firstName, ' ', lastName) as patient_name,
            `condition`,
            date as registered_date
        FROM patients
        ORDER BY date DESC
        LIMIT 5";
    
    $query = $dbh->prepare($sql);
    $query->execute();
    $recentActivity = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Silent fail (or log to error file)
}
$dashboardData = [
    'totalPatients' => 0,
    'conditionsData' => [],
    'recentActivity' => []
];

try {
    // Total Patients Count
    $sql = "SELECT COUNT(id) as total FROM patients";
    $query = $dbh->prepare($sql);
    $query->execute();
    $dashboardData['totalPatients'] = $query->fetchColumn();

    // Patient Conditions Data
    $sql = "SELECT `condition`, COUNT(*) as count FROM patients GROUP BY `condition`";
    $query = $dbh->prepare($sql);
    $query->execute();
    $dashboardData['conditionsData'] = $query->fetchAll(PDO::FETCH_ASSOC);

    // Recent Activity (last 3 registrations)
    $sql = "SELECT CONCAT(firstName, ' ', lastName) as name, `condition`, date 
            FROM patients ORDER BY date DESC LIMIT 3";
    $query = $dbh->prepare($sql);
    $query->execute();
    $dashboardData['recentActivity'] = $query->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Dashboard Error: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="bahay/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Dashboard</title>
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
            <!-- Cards and Chart Section -->
            <div class="cards-chart-container">
                <!-- Card -->
                <div class="dashboard-stat">
                    <span class="bg-icon"><i class="fa fa-users"></i></span>
                    <div class="nimbers">
                        <span class="number-counter"><?php echo htmlentities($totalPatients); ?></span>
                        <span class="name">Patients</span>
                    </div>
                </div>

                <!-- Chart -->
                <div class="chart-container">
                    <h2 class="chart-title">Patient Conditions</h2>
                    <canvas id="conditionsChart"></canvas>
                </div>
            </div>

            <div class="recent-activity">
    <h2><i class="fas fa-clock"></i> Recent Registrations</h2>
    
    <?php if(empty($recentActivity)): ?>
        <p class="no-activity">No new patients found</p>
    <?php else: ?>
        <ul>
            <?php foreach ($recentActivity as $activity): ?>
                <li>
                    <span class="patient-name"><?= htmlspecialchars($activity['patient_name']) ?></span>
                    <span class="condition-badge"><?= htmlspecialchars($activity['condition']) ?></span>
                    <span class="activity-date"><?= date('M d, Y', strtotime($activity['registered_date'])) ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
</div>
        </section>
    </main>
    <script src="bahay/script.js"></script>
    <script>
        // Prepare data for the chart
        const conditionsData = <?php echo json_encode($conditionsData); ?>;
        const labels = conditionsData.map(data => data.condition);
        const counts = conditionsData.map(data => data.count);

        // Render the pie chart
        const ctx = document.getElementById('conditionsChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Patient Conditions',
                    data: counts,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        enabled: true
                    }
                }
            }
        });
    </script>
</body>
</html>