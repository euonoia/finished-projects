<?php
require_once '../includes/functions.inc.php';
require_once '../db/config.php';

session_start();

if (!isAdmin()) {
    redirect('../pages/admin-login.php');
}

// Check if the user ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "Invalid user ID.";
    redirect('users.php');
}

$userId = sanitizeInput($_GET['id']);

// Fetch user details
$stmt = $conn->prepare("SELECT id, username, email, created_at, active FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error'] = "User not found.";
    redirect('users.php');
}

$user = $result->fetch_assoc();

// Fetch emergency contacts
$contactsStmt = $conn->prepare("SELECT contact_name, contact_number FROM emergency_contacts WHERE user_id = ?");
$contactsStmt->bind_param("i", $userId);
$contactsStmt->execute();
$contactsResult = $contactsStmt->get_result();
$emergencyContacts = $contactsResult->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/emergency-response/assets/err.jpg" type="image/x-icon">
    <title>View User - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="bg-gray-100">
    <!-- Main Container -->
    <div class="flex h-screen bg-gray-50">
        <!-- Modern Sidebar -->
        <aside class="w-72 bg-gradient-to-b from-gray-900 to-gray-800 text-white shadow-xl">
            <div class="p-6">
                <h1 class="text-2xl font-bold tracking-tight">Admin Panel</h1>
                <nav class="mt-8">
                    <ul class="space-y-4">
                        <li>
                            <a href="dashboard.php" class="flex items-center px-4 py-3 rounded-lg transition-all hover:bg-gray-700/50 hover:translate-x-1">
                                <i class="fas fa-tachometer-alt text-blue-400"></i>
                                <span class="ml-3 font-medium">Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="users.php" class="flex items-center px-4 py-3 rounded-lg transition-all hover:bg-gray-700/50 hover:translate-x-1">
                                <i class="fas fa-users text-green-400"></i>
                                <span class="ml-3 font-medium">Users</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Enhanced Content Area -->
        <main class="flex-1 p-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold mb-4">User Details</h2>
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="p-3 bg-gray-50 rounded">
                        <p class="text-sm text-gray-500">Username</p>
                        <p class="font-bold"><?php echo htmlspecialchars($user['username']); ?></p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded">
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-bold"><?php echo htmlspecialchars($user['email']); ?></p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded">
                        <p class="text-sm text-gray-500">Registered On</p>
                        <p class="font-bold"><?php echo date('M j, Y', strtotime($user['created_at'])); ?></p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded">
                        <p class="text-sm text-gray-500">Status</p>
                        <span class="<?php echo $user['active'] ? 'text-green-600' : 'text-red-600'; ?>"><?php echo $user['active'] ? 'Active' : 'Inactive'; ?></span>
                    </div>
                </div>
                <div class="border-t pt-4">
                    <h3 class="text-xl font-bold mb-3">Emergency Contacts</h3>
                    <?php if (empty($emergencyContacts)): ?>
                        <p class="text-gray-500">No emergency contacts found.</p>
                    <?php else: ?>
                        <div class="space-y-2 max-h-40 overflow-y-auto">
                            <?php foreach ($emergencyContacts as $contact): ?>
                                <div class="p-3 bg-gray-50 rounded">
                                    <p class="font-bold"><?php echo htmlspecialchars($contact['contact_name']); ?></p>
                                    <p><?php echo htmlspecialchars($contact['contact_number']); ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </main>
    </div>
</body>

</html>