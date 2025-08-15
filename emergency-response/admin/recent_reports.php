<?php
require_once '../includes/functions.inc.php';
require_once '../db/config.php';

session_start();

if (!isAdmin()) {
    redirect('../pages/admin-login.php');
}

// Get recent emergency reports from the database
$reports = $conn->query("
    SELECT emergency_reports.*, users.username 
    FROM emergency_reports
    JOIN users ON emergency_reports.user_id = users.id
    ORDER BY emergency_reports.timestamp DESC
    LIMIT 10
");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/emergency-response/assets/err.jpg" type="image/x-icon">
    <title>Recent Reports</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Toastify CSS + JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="../assets/notifications.js"></script>


</head>

<body class="bg-blue-400">

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
                    <a href="recent_reports.php" aria-current="page" class="flex items-center p-3 rounded-lg bg-blue-600/80 transition-all duration-300 ">
                        <i class="fas fa-flag text-blue-200"></i>
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

        <?php if (isset($_SESSION['message'])): ?>
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                <?php
                echo $_SESSION['message'];
                unset($_SESSION['message']);
                ?>
            </div>
        <?php endif; ?>

        <!-- Main Content -->
        <div class="flex-1 p-8 overflow-y-auto">
            <div class="bg-blue-400 pb-4">
                <h2 class="text-2xl font-bold" style="color:#fff">Recent Emergency Reports</h2>
            </div>

            <!-- Recent Emergency Reports -->
            <div class="bg-white p-6 rounded-lg shadow mt-6">
                <?php if ($reports->num_rows > 0): ?>
                    <div class="space-y-4">
                        <?php while ($report = $reports->fetch_assoc()): ?>
                            <div class="border rounded-lg p-4">
                                <div class="flex justify-between items-center mb-4">
                                    <div>
                                        <h4 class="font-medium text-lg"><?php echo ucfirst($report['type']); ?> Emergency</h4>
                                        <p class="text-sm text-gray-500">Uploaded by: <span class="font-semibold"><?php echo htmlspecialchars($report['username']); ?></span></p>
                                    </div>
                                </div>


                                <p class="text-gray-600 mt-2"><?php echo htmlspecialchars($report['description'] ?? ''); ?></p>

                                <?php if (!empty($report['image']) && file_exists("../" . $report['image'])): ?>
                                    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm hidden" id="overlay-<?php echo (int)$report['id']; ?>">
                                        <div class="flex items-center justify-center h-full">
                                            <img src="../<?php echo htmlspecialchars($report['image']); ?>"
                                                alt="Emergency Image"
                                                class="max-h-[80vh] max-w-[90vw] object-contain"
                                                onclick="event.stopPropagation()">
                                        </div>
                                    </div>
                                    <img src="../<?php echo htmlspecialchars($report['image']); ?>"
                                        alt="Emergency Image"
                                        onclick="showFullImage(<?php echo (int)$report['id']; ?>)"
                                        class="mt-3 w-48 h-32 object-cover rounded-lg shadow-md cursor-pointer transition-transform duration-300 hover:opacity-90"
                                        onerror="this.src='../assets/default-image.jpg'">
                                    <script>
                                        function showFullImage(reportId) {
                                            try {
                                                const overlay = document.getElementById(`overlay-${reportId}`);
                                                if (overlay) {
                                                    overlay.classList.remove('hidden');
                                                    overlay.onclick = () => overlay.classList.add('hidden');
                                                }
                                            } catch (error) {
                                                console.error('Error showing full image:', error);
                                            }
                                        }
                                    </script>
                                <?php endif; ?>

                                <div class="flex justify-between mt-4 text-sm text-gray-500">
                                    <?php
                                    $location = htmlspecialchars($report['location']);
                                    $lat = $report['latitude'];
                                    $lng = $report['longitude'];

                                    if (!empty($lat) && !empty($lng)) {
                                        $mapLink = "https://www.google.com/maps?q={$lat},{$lng}";
                                        echo "<span>Location: <a href='$mapLink' target='_blank' class='text-blue-600 underline'>$location</a></span>";
                                    } else {
                                        echo "<span>Location: $location</span>";
                                    }
                                    ?>
                                    <span>Reported on: <?php echo date('M j, Y g:i A', strtotime($report['timestamp'])); ?></span>
                                </div>

                                <button
                                    onclick="confirmDelete(<?php echo $report['id']; ?>)"
                                    class="mt-2 bg-red-500 hover:bg-red-600 text-white text-sm px-3 py-1 rounded">
                                    <i class="fas fa-trash-alt mr-1"></i> Delete
                                </button>


                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500">No recent emergency reports.</p>
                <?php endif; ?>
            </div>

        </div>
    </div>
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
        let lastNotifiedMessageId = null;

        function checkForIncomingMessages() {
            fetch('../admin/check_new_messages.php')
                .then(res => res.json())
                .then(data => {
                    if (data && data.id && data.sender_id != <?= $_SESSION['admin_id'] ?>) {
                        if (lastNotifiedMessageId !== data.id) {
                            showToast("New message: " + data.message, "#00b894");
                            document.getElementById('notificationSound')?.play();
                            lastNotifiedMessageId = data.id;
                        }
                    }
                });
        }

        // Start polling every 5 seconds
        setInterval(checkForIncomingMessages, 5000);




        function confirmDelete(reportId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This report will be permanently deleted.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create a hidden form and submit it
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = 'delete_report.php';

                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'report_id';
                    input.value = reportId;

                    form.appendChild(input);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('deleted') === '1') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Report deleted successfully',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });

                // Clean up the URL
                const newUrl = window.location.origin + window.location.pathname;
                window.history.replaceState({}, document.title, newUrl);
            }
        });
    </script>

</body>
<script>
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