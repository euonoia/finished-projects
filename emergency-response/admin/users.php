<?php
require_once '../includes/functions.inc.php';
require_once '../db/config.php';

session_start();

if (!isAdmin()) {
    redirect('../pages/admin-login.php');
}

// Handle user deactivation
if (isset($_GET['deactivate']) && is_numeric($_GET['deactivate'])) {
    $userId = sanitizeInput($_GET['deactivate']);
    $stmt = $conn->prepare("UPDATE users SET active = 0 WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $_SESSION['success'] = "User deactivated successfully";
}

// Handle user activation
if (isset($_GET['activate']) && is_numeric($_GET['activate'])) {
    $userId = sanitizeInput($_GET['activate']);
    $stmt = $conn->prepare("UPDATE users SET active = 1 WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $_SESSION['success'] = "User activated successfully";
}

// Handle user deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $userId = sanitizeInput($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $_SESSION['success'] = "User deleted successfully";
    header("Location: users.php");
    exit();
}


// Get all users
$users = $conn->query("SELECT id, username, email, contact_number , created_at, active FROM users ORDER BY created_at DESC");
// Get active incidents
$incidents = $conn->query("
    SELECT i.id, u.username, i.latitude, i.longitude, i.timestamp 
    FROM incidents i
    JOIN users u ON i.user_id = u.id
    WHERE i.status = 'active'
    ORDER BY i.timestamp DESC
");

// Get recent community alerts
$alerts = $conn->query("
    SELECT * FROM community_alerts
    ORDER BY timestamp DESC
    LIMIT 5
");
// Fetch unread SOS count
$result = $conn->query("SELECT COUNT(*) AS count FROM incidents WHERE status = 'active' AND is_read = 0");
$unreadSOS = $result->fetch_assoc()['count'] ?? 0;

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/emergency-response/assets/err.jpg" type="image/x-icon">
    <title>User Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="../assets/notifications.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places"></script>
    <audio id="notificationSound" src="../assets/sound-2.mp3" preload="auto"></audio>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="bg-violet-400">
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
                    <a href="alerts.php" class="flex items-center p-3 rounded-lg hover:bg-yellow-600/50 transition-all duration-300">
                        <i class="fas fa-bullhorn text-yellow-500"></i>
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
                    <a href="users.php" aria-current="page" class="flex items-center p-3 rounded-lg bg-violet-600/80 transition-all duration-300">
                        <i class="fas fa-users text-violet-200"></i>
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
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-white">User Management</h2>
            </div>

            <!-- Success Message -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?php echo $_SESSION['success'];
                    unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <!-- Users Table -->
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="overflow-x-auto max-w-full">
                    <table class="w-full table-auto divide-y divide-gray-200">
                        <!-- Table Header -->
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Username</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Registered</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <!-- Table Body -->
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($user = $users->fetch_assoc()): ?>
                                <tr>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($user['username']); ?></td>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($user['contact_number']); ?></td>
                                    <td class="px-4 py-2"><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                                    <td class="px-4 py-2">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $user['active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                            <?php echo $user['active'] ? 'Active' : 'Inactive'; ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 flex gap-2">
                                        <a href="view-user.php?id=<?php echo $user['id']; ?>" class="text-blue-500 hover:text-blue-700">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if ($user['active']): ?>
                                            <a href="?deactivate=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure you want to deactivate this user?');" class="text-red-500 hover:text-red-700">
                                                <i class="fas fa-user-slash"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="?activate=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure you want to activate this user?');" class="text-green-500 hover:text-green-700">
                                                <i class="fas fa-user-check"></i>
                                            </a>
                                        <?php endif; ?>
                                        <a href="#" class="text-red-700 hover:text-red-900" onclick="confirmDelete(<?php echo $user['id']; ?>)">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
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

    function confirmDelete(userId) {
        Swal.fire({
            title: "Are you sure?",
            text: "This will permanently delete the user!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "?delete=" + userId;
            }
        });
    }

    function fetchDashboardCounts() {
        fetch('../api/get_dashboard_counts.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('incidents-count').textContent = data.incidents;
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