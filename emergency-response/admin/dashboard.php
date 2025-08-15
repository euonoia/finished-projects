<?php
require_once '../includes/functions.inc.php';
require_once '../db/config.php';

session_start();

if (!isAdmin()) {
    redirect('../pages/admin-login.php');
}
$_SESSION['welcome_message'] = 'Welcome back, Admin!';

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
    <title>Admin Dashboard</title>
    <link rel="icon" href="/emergency-response/assets/err.jpg" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="../assets/notifications.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places"></script>
    <audio id="notificationSound" src="../assets/sound-2.mp3" preload="auto"></audio>
    <script src="./tailwind3.js"></script>
</head>

<body class="bg-white-300">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-900 text-white p-6 transition-all duration-300 ease-in-out transform hover:shadow-2xl">
            <div class="flex justify-center mb-6">
                <img src="../assets/err.jpg" alt="Emergency System Logo" class="h-16 rounded-full shadow-lg hover:scale-110 transition-transform duration-300">
            </div>
            <h1 class="text-xl font-bold mb-8 text-center bg-gradient-to-r from-cyan-500 to-blue-500 bg-clip-text text-transparent">Admin Dashboard</h1>
            <nav class="space-y-4">
                <div class="border-b border-gray-700 pb-4">
                    <a href="dashboard.php" class="flex items-center p-3 rounded-lg bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 transition-all duration-300">
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
            <h2 class="text-2xl font-bold mb-6">Admin Dashboard</h2>

            <!-- Status -->
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6 text-center">
                <div class="bg-white p-4 rounded shadow">
                    <h3 class="text-sm font-medium text-gray-600">Active Incidents</h3>
                    <p id="incidents-active" class="text-2xl font-bold text-red-600">0</p>
                </div>
                <div class="bg-white p-4 rounded shadow">
                    <h3 class="text-sm font-medium text-gray-600">Resolved Incidents</h3>
                    <p id="incidents-resolved" class="text-2xl font-bold text-green-600">0</p>
                </div>
                <div class="bg-white p-4 rounded shadow">
                    <h3 class="text-sm font-medium text-gray-600">Community Alerts</h3>
                    <p id="alerts-count" class="text-2xl font-bold text-yellow-600">0</p>
                </div>
                <div class="bg-white p-4 rounded shadow">
                    <h3 class="text-sm font-medium text-gray-600">Recent Reports</h3>
                    <p id="reports-count" class="text-2xl font-bold text-blue-600">0</p>
                </div>
                <div class="bg-white p-4 rounded shadow">
                    <h3 class="text-sm font-medium text-gray-600">Total Users</h3>
                    <p id="users-count" class="text-2xl font-bold text-purple-600">0</p>
                </div>
            </div>

            <!-- Active Incidents -->
            <div class="bg-white p-6 rounded-lg shadow mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Active Emergency Incidents</h3>
                    <a href="incidents.php" class="text-blue-500 hover:underline">View All</a>
                </div>

                <?php if ($incidents->num_rows > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
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
                                            <form method="POST" action="resolve_incident.php" class="inline">
                                                <input type="hidden" name="incident_id" value="<?php echo $incident['id']; ?>">
                                                <button type="submit" class="text-green-500 hover:text-green-700">
                                                    <i class="fas fa-check"></i> Resolve
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500">No active incidents</p>
                <?php endif; ?>
            </div>

            <!-- Map Container -->


            <!-- Recent Community Alerts -->
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Recent Community Alerts</h3>
                    <a href="alerts.php" class="text-blue-500 hover:underline">View All</a>
                </div>

                <?php if ($alerts->num_rows > 0): ?>
                    <div class="space-y-4">
                        <?php while ($alert = $alerts->fetch_assoc()): ?>
                            <div class="border-b pb-4">
                                <h4 class="font-medium"><?php echo htmlspecialchars($alert['title']); ?></h4>
                                <p class="text-gray-600 text-sm"><?php echo htmlspecialchars($alert['description']); ?></p>
                                <div class="flex justify-between mt-2">
                                    <span class="text-xs text-gray-500"><?php echo htmlspecialchars($alert['location']); ?></span>
                                    <span class="text-xs text-gray-500"><?php echo date('M j, Y g:i A', strtotime($alert['timestamp'])); ?></span>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500">No community alerts</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
<audio id="alertSound" src="../assets/sound-5.mp3" preload="auto"></audio>

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
    // here
    function fetchSOSCount() {
        fetch('get_sos_count.php')
            .then(response => response.text())
            .then(data => {
                let sosCount = document.getElementById('sos-count');
                if (parseInt(data) > 0) {
                    sosCount.textContent = data;
                    sosCount.style.display = "inline-block";
                } else {
                    sosCount.style.display = "none";
                }
            })
            .catch(error => console.error("Error fetching SOS count:", error));
    }

    // Refresh SOS notification count every 5 seconds
    setInterval(fetchSOSCount, 5000);

    function viewOnMap(lat, lng) {
        let map = new google.maps.Map(document.getElementById("map"), {
            center: {
                lat: lat,
                lng: lng
            },
            zoom: 15
        });

        let marker = new google.maps.Marker({
            position: {
                lat: lat,
                lng: lng
            },
            map: map
        });

        let infoWindow = new google.maps.InfoWindow({
            content: `<strong>Latitude:</strong> ${lat} <br> <strong>Longitude:</strong> ${lng}`
        });

        infoWindow.open(map, marker);
    }
    let previousActiveIncidents = 0;
    let previousCommunityAlerts = 0;
    let previousReports = 0;
    let previousUsers = 0;
    let lastNotifiedMessageId = null;
    let initialLoadDone = false;

    function showToast(text, color = "#00b894") {
        Toastify({
            text: text,
            duration: 4000,
            close: true,
            gravity: "top",
            position: "right",
            backgroundColor: color,
        }).showToast();
    }

    function checkForIncomingMessages() {
        fetch("check_new_messages.php")
            .then(response => response.json())
            .then(data => {
                if (data && data.id && data.sender_id != <?= $_SESSION['admin_id'] ?>) {
                    if (lastNotifiedMessageId !== data.id) {
                        lastNotifiedMessageId = data.id;
                        document.getElementById("notificationSound").play();
                        showToast("New message received: " + data.message);
                    }
                }
            })
            .catch(err => console.error("Error checking messages:", err));
    }

    setInterval(checkForIncomingMessages, 5000);

    function fetchDashboardCounts() {
        fetch('../api/get_dashboard_counts.php')
            .then(response => response.json())
            .then(data => {
                // Update dashboard UI
                document.getElementById('incidents-active').textContent = data.incidents_active;
                document.getElementById('incidents-resolved').textContent = data.incidents_resolved;
                document.getElementById('alerts-count').textContent = data.alerts;
                document.getElementById('reports-count').textContent = data.reports;
                document.getElementById('users-count').textContent = data.users;

                // Only trigger alerts after initial load
                if (initialLoadDone) {
                    if (data.incidents_active > previousActiveIncidents) {
                        triggerAlert("⚠️ New active incident detected!", "#dc2626");
                    }

                    if (data.alerts > previousCommunityAlerts) {
                        triggerAlert("📢 New Community alert!", "#facc15");
                    }

                    if (data.reports > previousReports) {
                        triggerAlert("📝 New Report submitted!", "#3b82f6");
                    }

                    if (data.users > previousUsers) {
                        triggerAlert("🎉 New User joined!", "#8b5cf6");
                    }
                }

                // Update previous counts
                previousActiveIncidents = data.incidents_active;
                previousCommunityAlerts = data.alerts;
                previousReports = data.reports;
                previousUsers = data.users;

                // Mark initial load complete
                if (!initialLoadDone) initialLoadDone = true;
            })
            .catch(error => console.error("Dashboard count fetch error:", error));
    }


    function triggerAlert(message, bgColor) {
        const audio = document.getElementById("alertSound");
        if (audio) audio.play();

        // Set body flash color
        let flashClass = "";

        if (message.includes("incident")) {
            flashClass = "bg-red-100";
        } else if (message.includes("alert")) {
            flashClass = "bg-yellow-100";
        } else if (message.includes("Report")) {
            flashClass = "bg-blue-100";
        } else if (message.includes("User")) {
            flashClass = "bg-purple-100";
        }

        // Flash background if applicable
        if (flashClass) {
            document.body.classList.add(flashClass);
            setTimeout(() => {
                document.body.classList.remove(flashClass);
            }, 500);
        }

        // Show toast
        Toastify({
            text: message,
            duration: 4000,
            gravity: "top",
            position: "right",
            backgroundColor: bgColor || "#333",
            stopOnFocus: true
        }).showToast();
    }
    //end dito



    // Initial fetch
    fetchDashboardCounts();
    // Every 5 seconds
    setInterval(fetchDashboardCounts, 5000);
</script>



</html>