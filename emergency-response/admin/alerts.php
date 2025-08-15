<?php
require_once '../includes/functions.inc.php';
require_once '../db/config.php';

session_start();

if (!isAdmin()) {
    redirect('../pages/admin-login.php');
}

// Handle alert creation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitizeInput($_POST['title']);
    $description = sanitizeInput($_POST['description']);
    $location = sanitizeInput($_POST['location']);
    $alertType = sanitizeInput($_POST['alert_type']);

    $stmt = $conn->prepare("INSERT INTO community_alerts (title, description, location, alert_type) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $description, $location, $alertType);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Alert created successfully";
    } else {
        $_SESSION['error'] = "Failed to create alert: " . $conn->error;
    }
}

// Handle alert deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $alertId = sanitizeInput($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM community_alerts WHERE id = ?");
    $stmt->bind_param("i", $alertId);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Alert deleted successfully";
    } else {
        $_SESSION['error'] = "Failed to delete alert";
    }
}

// Get all alerts
$alerts = $conn->query("SELECT * FROM community_alerts ORDER BY timestamp DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/emergency-response/assets/err.jpg" type="image/x-icon">
    <title>Community Alerts</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="bg-yellow-300">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-900 text-white p-6 transition-all duration-300 ease-in-out transform hover:shadow-2xl">
            <div class="flex justify-center mb-6">
                <img src="../assets/err.jpg" alt="Emergency System Logo" class="h-16 rounded-full shadow-lg hover:scale-110 transition-transform duration-300">
            </div>
            <h1 class="text-xl font-bold mb-8 text-center bg-gradient-to-r from-cyan-500 to-blue-500 bg-clip-text text-transparent">Admin Dashboard</h1>
            <nav class="space-y-4">
                <div class="border-b border-gray-700 pb-4">
                    <a href="dashboard.php" class="flex items-center p-3 rounded-lg hover:bg-gradient-to-r hover:from-cyan-500 hover:to-blue-500 transition-all duration-300">
                        <i class="fas fa-home text-lg"></i>
                        <span class="ml-3">Dashboard</span>
                    </a>
                </div>
                <div class="space-y-2">
                    <a href="incidents.php" class="flex items-center p-3 rounded-lg hover:bg-red-600/50 transition-all duration-300">
                        <i class="fas fa-exclamation-triangle text-red-500"></i>
                        <span class="ml-3">Incidents</span>
                    </a>
                    <a href="alerts.php" aria-current="page" class="flex items-center p-3 rounded-lg bg-yellow-600/80 transition-all duration-300 ">
                        <i class="fas fa-bullhorn text-yellow-200"></i>
                        <span class="ml-3">Community Alerts</span>
                    </a>
                    <a href="recent_reports.php" class="flex items-center p-3 rounded-lg hover:bg-blue-600/50 transition-all duration-300">
                        <i class="fas fa-flag text-blue-500"></i>
                        <span class="ml-3">Recent Reports</span>
                    </a>
                    <a href="message.php" class="flex items-center p-3 rounded-lg hover:bg-green-600/50 transition-all duration-300">
                        <i class="fas fa-comments text-green-500"></i>
                        <span class="ml-3">Messages</span>
                    </a>
                    <a href="users.php" class="flex items-center p-3 rounded-lg hover:bg-violet-600/50 transition-all duration-300">
                        <i class="fas fa-users text-violet-500"></i>
                        <span class="ml-3">Users</span>
                    </a>
                </div>
                <div class="border-t border-gray-700 pt-4 mt-6">
                    <a href="#" onclick="confirmLogout()" class="flex items-center p-3 rounded-lg hover:bg-red-600/50 transition-all duration-300">
                        <i class="fas fa-sign-out-alt text-red-500"></i>
                        <span class="ml-3">Logout</span>
                    </a>
                </div>
            </nav>
        </div>


        <!-- Main Content -->
        <div class="flex-1 p-8 overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Community Alerts</h2>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?php echo $_SESSION['success'];
                    unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo $_SESSION['error'];
                    unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <!-- Create Alert Form -->
            <div class="bg-white p-6 rounded-lg shadow mb-6">
                <h3 class="text-lg font-semibold mb-4">Create New Alert</h3>
                <form method="POST">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 mb-2" for="title">Title</label>
                            <input type="text" name="title" required
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-2" for="alert_type">Alert Type</label>
                            <select name="alert_type" required
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="weather">Weather</option>
                                <option value="safety">Safety</option>
                                <option value="health">Health</option>
                                <option value="crime">Crime</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2" for="location">Location</label>
                        <div class="flex">
                            <input type="text" id="location" name="location" required
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="You can type the exact address or Click Your Location.">
                            <button type="button" onclick="getLocation()"
                                class="ml-2 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-200">
                                Get Your Location
                            </button>
                        </div>
                    </div>

                    <script>
                        function getLocation() {
                            if (navigator.geolocation) {
                                navigator.geolocation.getCurrentPosition(function(position) {
                                    document.getElementById("location").value =
                                        position.coords.latitude + ", " + position.coords.longitude;
                                }, function(error) {
                                    alert("Error getting location: " + error.message);
                                });
                            } else {
                                alert("Geolocation is not supported by this browser.");
                            }
                        }
                    </script>
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2" for="description">Description</label>
                        <textarea name="description" rows="4" required
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition duration-200">
                        Create Alert
                    </button>
                </form>
            </div>

            <!-- Alerts List -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-4">Active Alerts</h3>
                <?php if ($alerts->num_rows > 0): ?>
                    <div class="space-y-4">
                        <?php while ($alert = $alerts->fetch_assoc()): ?>
                            <div class="border rounded-lg p-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-medium text-lg"><?php echo htmlspecialchars($alert['title']); ?></h4>
                                        <span class="inline-block px-2 py-1 text-xs rounded-full 
                                        <?php
                                        switch ($alert['alert_type']) {
                                            case 'weather':
                                                echo 'bg-blue-100 text-blue-800';
                                                break;
                                            case 'safety':
                                                echo 'bg-yellow-100 text-yellow-800';
                                                break;
                                            case 'health':
                                                echo 'bg-red-100 text-red-800';
                                                break;
                                            case 'crime':
                                                echo 'bg-purple-100 text-purple-800';
                                                break;
                                            default:
                                                echo 'bg-gray-100 text-gray-800';
                                        }
                                        ?>">
                                            <?php echo ucfirst($alert['alert_type']); ?>
                                        </span>
                                    </div>
                                    <a href="?delete=<?php echo $alert['id']; ?>"
                                        onclick="return confirm('Are you sure you want to delete this alert?');"
                                        class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                                <p class="text-gray-600 mt-2"><?php echo htmlspecialchars($alert['description']); ?></p>
                                <div class="flex justify-between mt-4 text-sm text-gray-500">
                                    <span><?php echo htmlspecialchars($alert['location']); ?></span>
                                    <span><?php echo date('M j, Y g:i A', strtotime($alert['timestamp'])); ?></span>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500">No community alerts found</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
<script>
    function confirmLogout() {
        Swal.fire({
            title: "Are you sure?",
            text: "You will be logged out!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, Logout"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "../includes/logout.inc.php"; // Redirect to logout
            }
        });
    }

    function fetchDashboardCounts() {
        fetch('../api/get_dashboard_counts.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('incidents-active').textContent = data.incidents_active;
                document.getElementById('alerts-count').textContent = data.alerts;
                document.getElementById('reports-count').textContent = data.reports;
                document.getElementById('users-count').textContent = data.users;
            })
            .catch(error => {
                console.error('Error fetching dashboard counts:', error);
            });
    }

    // Refresh counts every 5 seconds
    setInterval(fetchDashboardCounts, 5000);
    fetchDashboardCounts(); // Initial load
</script>

</html>