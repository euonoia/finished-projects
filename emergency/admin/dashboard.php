<?php

require_once '../includes/functions.inc.php';
require_once '../db/config.php';

session_start();


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
<html>

<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="icon" href="images/hehe.png">

    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="/emergency/css/style.css">

</head>

<body>
    <div class="wrapper">
        <aside id="sidebar">
            <div class="d-flex">
                <button class="toggle-btn" type="button">
                    <i class="lni lni-grid-alt"></i>
                </button>
                <div class="sidebar-logo">
                    <a href="#"></a>
                </div>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a href="dashboard.php" class="sidebar-link">
                        <i class="lni lni-user"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                        data-bs-target="#multi" aria-expanded="false" aria-controls="multi">
                        <i class="lni lni-layout"></i>
                        <span>Manage</span>
                    </a>
                    <ul id="multi" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                            <ul class="child-nav">
                                <li><a href="manage-students.php"><i class="fa fa fa-server"></i> <span>Students</span></a></li>
                            </ul>
                            <ul class="child-nav">
                                <li><a href="manage-classes.php"><i class="fa fa-file-text"></i> <span>Classes</span></a></li>
                            </ul>
                            <ul class="child-nav">
                                <li><a href="manage-subjects.php"><i class="fa fa fa-server"></i> <span>Subjects</span></a></li>
                            </ul>
                            <ul>
                                <li><a href="manage-subjectcombination.php"><i class="fa fa-newspaper-o"></i> <span>Integrate Subject</span></a></li>
                        </li>
                    </ul>
                    <ul class="child-nav">
                        <li><a href="add-result.php"><i class="fa fa fa-server"></i> <span>Result</span></a></li>
                    </ul>
                </li>
            </ul>
            </li>
            <li class="sidebar-item">
                <a href="change-password.php" class="sidebar-link">
                    <i class="lni lni-cog"></i>
                    <span>Change Password</span>
                </a>
            </li>
            </ul>
            <div class="sidebar-footer">
                <a href="logout.php" class="sidebar-link">
                    <i class="lni lni-exit"></i>
                </a>
            </div>
        </aside>
        <div class="main">
            <nav class="navbar navbar-expand px-4 py-3">
                <form action="#" class="d-none d-sm-inline-block">

                </form>
                <div class="navbar-collapse collapse">
                    <ul class="navbar-nav ms-auto">
                        <img src="images/HOHO.png" alt="" style="width:150px">
                    </ul>
                </div>
            </nav>


            <main class="content px-3 py-4">
                <div class="container-fluid">
                    <div class="mb-3">
                        <h3 class="fw-bold fs-4 mb-3">Admin Dashboard</h3>

                        <div class="row ">
                            <div class="col-12 col-md-3 ">

                                <div class="flex h-screen w-full">
                                    <!-- Sidebar -->

                                    <!-- Main Content -->
                                    <div class="flex-1 p-8 overflow-y-auto w-full">
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

                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row text-body-secondary">
                        <div class="col-6 text-start ">
                            <a class="text-body-secondary" href=" #">

                            </a>
                        </div>

                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script src="/emergency/css/script.js"></script>
    <script>
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




        // Initial fetch
        fetchDashboardCounts();
        // Every 5 seconds
        setInterval(fetchDashboardCounts, 5000);
    </script>
</body>

</html>