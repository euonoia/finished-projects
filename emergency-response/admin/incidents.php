<?php
require_once '../includes/functions.inc.php';
require_once '../db/config.php';

session_start();

if (!isAdmin()) {
    redirect('../pages/admin-login.php');
}

// Mark all unread SOS incidents as "read"
$conn->query("UPDATE incidents SET is_read = 1 WHERE is_read = 0");

// Handle incident resolution
if (isset($_GET['resolve']) && is_numeric($_GET['resolve'])) {
    $incidentId = sanitizeInput($_GET['resolve']);
    $stmt = $conn->prepare("UPDATE incidents SET status = 'resolved' WHERE id = ?");
    $stmt->bind_param("i", $incidentId);
    $stmt->execute();
    $_SESSION['success'] = "Incident marked as resolved";
}

// Get all incidents
$incidents = $conn->query("
    SELECT i.id, u.username, i.latitude, i.longitude, i.timestamp, i.status
    FROM incidents i
    JOIN users u ON i.user_id = u.id
    ORDER BY i.timestamp DESC
");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/emergency-response/assets/err.jpg" type="image/x-icon">
    <title>Incident Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places"></script>
    <script>
        function viewOnMap(lat, lng) {
            const map = new google.maps.Map(document.getElementById("map-modal"), {
                center: {
                    lat: lat,
                    lng: lng
                },
                zoom: 15
            });
            new google.maps.Marker({
                position: {
                    lat: lat,
                    lng: lng
                },
                map: map
            });
            document.getElementById('map-modal').classList.remove('hidden');
        }
    </script>
</head>

<body class="bg-red-400">
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
                    <a href="incidents.php" class="flex items-center p-3 rounded-lg bg-red-600/50 transition-all duration-300">
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
                <h2 class="text-2xl font-bold">Incident Management</h2>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?php echo $_SESSION['success'];
                    unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <div class="bg-white p-6 rounded-lg shadow">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($incident = $incidents->fetch_assoc()): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($incident['username']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-gray-700" id="address-<?php echo $incident['id']; ?>">Loading...</span><br>
                                        <a href="https://www.google.com/maps?q=<?php echo $incident['latitude']; ?>,<?php echo $incident['longitude']; ?>"
                                            target="_blank" class="text-blue-500 text-sm hover:underline">
                                            View on Google Maps
                                        </a>
                                        <script>
                                            fetch(`https://maps.googleapis.com/maps/api/geocode/json?latlng=<?php echo $incident['latitude']; ?>,<?php echo $incident['longitude']; ?>&key=YOUR_API_KEY`)
                                                .then(response => response.json())
                                                .then(data => {
                                                    const address = data.results[0]?.formatted_address || "Address Located";
                                                    document.getElementById("address-<?php echo $incident['id']; ?>").textContent = address;
                                                })
                                                .catch(() => {
                                                    document.getElementById("address-<?php echo $incident['id']; ?>").textContent = "Error loading address";
                                                });
                                        </script>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo date('M j, Y g:i A', strtotime($incident['timestamp'])); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        <?php echo $incident['status'] === 'active' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'; ?>">
                                            <?php echo ucfirst($incident['status']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if ($incident['status'] === 'active'): ?>
                                            <!-- Show "Resolve" button only if the incident is active -->
                                            <a href="?resolve=<?php echo $incident['id']; ?>" class="text-green-500 hover:text-green-700 mr-3">
                                                <i class="fas fa-check"></i> Resolve
                                            </a>
                                        <?php else: ?>
                                            <!-- Show "Resolved" text if already resolved -->
                                            <span class="text-gray-500 font-semibold">
                                                <i class="fas fa-check-circle"></i> Resolved
                                            </span>
                                        <?php endif; ?>

                                        <!-- Keep the "View on Map" link -->
                                        <!-- <a href="#" onclick="viewOnMap(<?php echo $incident['latitude']; ?>, <?php echo $incident['longitude']; ?>); return false;" 
       class="text-blue-500 hover:text-blue-700 ml-3">
        <i class="fas fa-map-marker-alt"></i> View on Map
    </a> -->
                                    </td>

                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Modal -->
    <div id="map-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-4 rounded-lg w-4/5 h-4/5">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Incident Location</h3>
                <button onclick="document.getElementById('map-modal').classList.add('hidden')"
                    class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="map" style="height: calc(100% - 50px); width: 100%;" class="border rounded-lg"></div>
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