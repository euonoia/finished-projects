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
         <link rel="icon" href="img/cropped.png" type="image/x-icon">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="bahay/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Dashboard</title>
    <style>
        ::-webkit-scrollbar {
            display: none;
        }
        * {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .chart-container {
            padding: 20px;
            background: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin: 20px auto;
            max-width: 1000px;
        }

        #conditionsChart {
            margin: 20px auto;
            max-width: 100%; /* Ensure it fits within the container */
            height: 300px; /* Adjust height for better visibility */
        }

        .chart-title {
            text-align: left;
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .cards-container {
        display: flex;
        justify-content: space-around;
        margin-bottom: 20px;
        
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
                            <a class="nav-link dropdown-title" style="color:rgb(255, 255, 255); background-color:rgb(3, 44, 87); padding: 5px 10px; border-radius: 5px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">Manage</a>
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
            <h1 class="main-title">Dashboard</h1>
            <div class="user-profile">
                <span class="user-avatar">
                    <i class="fa fa-user-circle"></i>
                </span>
                <span class="user-name">
                    <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : ''; ?>
                </span>
            </div>
        </header>
        <section class="content">
            <div class="dashboard-top-row">
                <div class="cards-chart-container">
                    <div class="dashboard-stat">
                        <i class="fa fa-user-md bg-icon"></i>
                        <div class="nimbers">
                            <div class="number-counter">
                                <?php
                                $sql = "SELECT COUNT(*) FROM doctors";
                                $query = $dbh->prepare($sql);
                                $query->execute();
                                echo $query->fetchColumn();
                                ?>
                            </div>
                            <div class="name">Doctors</div>
                        </div>
                    </div>
                    <div class="dashboard-stat">
                        <i class="fa fa-users bg-icon"></i>
                        <div class="nimbers">
                            <div class="number-counter"><?php echo htmlentities($totalPatients); ?></div>
                            <div class="name">Patients</div>
                        </div>
                    </div>
                    <div class="dashboard-stat">
                        <i class="fa fa-calendar bg-icon"></i>
                        <div class="nimbers">
                            <div class="number-counter">
                               <?php
                                $sql = "SELECT COUNT(*) FROM appointments";
                                $query = $dbh->prepare($sql);
                                $query->execute();
                                echo $query->fetchColumn();
                                ?>
                            </div>
                            <div class="name">Appointments</div>
                        </div>
                    </div>
                </div>
                <div class="chart-container">
                    <h2 class="chart-title">Patient Symptoms</h2>
                    <canvas id="conditionsChart"></canvas>
                    <?php if (empty($conditionsData)): ?>
                        <div class="no-chart-data" style="text-align:center; color:#888; margin-top:10px; font-size: medium;">
                            No patient condition data available.
                        </div>
                    <?php endif; ?>
                </div></div>

            </div>

                <h2 style="background: #f1f7fa; padding: 12px 18px; border-radius: 8px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-clock" style="background: #e3eaf2; border-radius: 50%; padding: 8px; font-size: 20px;"></i> Walk-ins
                </h2>
            <div class="recent-activity" style="max-height: 200px; overflow-y: auto; padding-right: 8px;margin: 0 0 20px 0;">
               
                <?php if(empty($recentActivity)): ?>
                    <p class="no-activity">No new patients found</p>
                <?php else: ?>
                    <ul style="margin:0; padding:0; list-style:none;">
                        <?php foreach ($recentActivity as $activity): ?>
                            <li style="margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center;">
                                <span class="patient-name"><?= htmlspecialchars($activity['patient_name']) ?></span>
                                <span style="display: flex; align-items: center; gap: 10px;">
                                    <span class="condition-badge" data-condition="<?= strtolower(trim($activity['condition'])) ?>">
                                        <?= htmlspecialchars($activity['condition']) ?>
                                    </span>
                                    <span class="activity-date"><?= date('M d, Y', strtotime($activity['registered_date'])) ?></span>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <script src="bahay/script.js"></script>
    <script>
function randomColorList(n) {
    const colors = [];
    for(let i=0; i<n; i++) {
        const hue = Math.floor(Math.random() * 360);
        colors.push(`hsl(${hue}, 70%, 70%)`);
    }
    return colors;
}

const conditionsData = <?php echo json_encode($conditionsData); ?>;
const labels = conditionsData.map(data => data.condition);
const counts = conditionsData.map(data => data.count);
const palette = [
    "#4dd0e1", "#ffd54f", "#81c784", "#ffb74d", "#ba68c8", "#e57373", "#a1887f", "#90a4ae", "#f06292", "#7986cb",
    "#64b5f6", "#aed581", "#ff8a65", "#9575cd", "#7986cb", "#dce775", "#ffd740", "#b2dfdb", "#ffb300", "#ce93d8", "#ff7043",
    "#90caf9", "#a5d6a7", "#fff176", "#ffcc80", "#b39ddb", "#e1bee7", "#c5e1a5", "#ffe082", "#bcaaa4", "#b0bec5"
];
const colors = [];
for (let i = 0; i < labels.length; i++) {
    colors.push(palette[i % palette.length]);
}

// Map each condition to its chart color (normalize keys)
const conditionColors = {};
labels.forEach((condition, idx) => {
    conditionColors[condition.trim().toLowerCase()] = colors[idx];
});

// --- Labels Row ---
if (labels.length > 0) {
    const chartContainer = document.querySelector('.chart-container');
    const labelsRow = document.createElement('div');
    labelsRow.style.display = 'flex';
    labelsRow.style.flexWrap = 'wrap';
    labelsRow.style.justifyContent = 'center';
    labelsRow.style.gap = '16px';
    labelsRow.style.margin = '18px 0 0 0';

    labels.forEach((label, idx) => {
        const item = document.createElement('span');
        item.style.display = 'flex';
        item.style.alignItems = 'center';
        item.style.gap = '6px';
        item.style.fontSize = '16px';
        item.style.fontFamily = "'Segoe UI', Arial, sans-serif";
        item.style.background = '#fff';
        item.style.borderRadius = '6px';
        item.style.padding = '4px 10px 4px 4px';
        item.style.boxShadow = '0 1px 4px rgba(0,0,0,0.06)';
        item.style.marginBottom = '4px';

        const colorDot = document.createElement('span');
        colorDot.style.display = 'inline-block';
        colorDot.style.width = '16px';
        colorDot.style.height = '16px';
        colorDot.style.borderRadius = '50%';
        colorDot.style.background = colors[idx];
        colorDot.style.border = '1px solid #ccc';

        item.appendChild(colorDot);
        item.appendChild(document.createTextNode(label));
        labelsRow.appendChild(item);
    });

    chartContainer.appendChild(labelsRow);
}

const ctx = document.getElementById('conditionsChart').getContext('2d');
new Chart(ctx, {
    type: 'pie',
    data: {
        labels: labels,
        datasets: [{
            label: 'Patient Conditions',
            data: counts,
            backgroundColor: colors,
            borderColor: '#fff',
            borderWidth: 2,
            hoverOffset: 8,
            borderRadius: 8,
            borderAlign: 'center'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false, // Hide default legend since we have a custom labels row
            },
            tooltip: {
                enabled: true,
                bodyFont: {
                    size: 16 // Tooltip font size
                },
                titleFont: {
                    size: 16
                }
            },
            datalabels: {
                display: true,
                color: '#222',
                font: {
                    size: 20, // Pie slice label font size
                    weight: 'bold'
                }
            }
        }
    },
    plugins: [
        {
            id: 'customDataLabels',
            afterDraw: chart => {
                const ctx = chart.ctx;
                chart.data.datasets.forEach((dataset, i) => {
                    const meta = chart.getDatasetMeta(i);
                    meta.data.forEach((element, index) => {
                        ctx.save();
                        ctx.font = 'bold 20px Segoe UI, Arial';
                        ctx.fillStyle = '#222';
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        const position = element.tooltipPosition();
                        const value = dataset.data[index];
                        if (value > 0) {
                            ctx.fillText(value, position.x, position.y);
                        }
                        ctx.restore();
                    });
                });
            }
        }
    ]
});

// Color the badges in recent registrations to match the chart
document.querySelectorAll('.condition-badge').forEach(function(badge) {
    const condition = badge.textContent.trim().toLowerCase();
    badge.style.background = conditionColors[condition] || '#eee';
});

// Color the badges in recent registrations to match the chart
document.querySelectorAll('.condition-badge').forEach(function(badge) {
    const condition = badge.textContent.trim().toLowerCase();
    badge.style.background = conditionColors[condition] || '#eee';
});

// Color the badges in recent registrations to match the chart
document.querySelectorAll('.condition-badge').forEach(function(badge) {
    const condition = badge.textContent.trim().toLowerCase();
    badge.style.background = conditionColors[condition] || '#eee';
});
</script>
    <script>
document.querySelectorAll('.dropdown-toggle-js').forEach(function(toggle) {
    toggle.addEventListener('click', function(e) {
        e.preventDefault(); // Prevent page reload
        // Your dropdown open/close logic here, if needed
        // Example: toggle a class to show/hide the dropdown
        const parent = toggle.closest('.dropdown-container');
        parent.classList.toggle('open');
    });
});
</script>
</body>
</html>