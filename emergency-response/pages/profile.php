<?php
require_once '../includes/functions.inc.php';
require_once '../db/config.php';

session_start();

if (!isLoggedIn()) {
    redirect('login.php');
}

$userId = $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM users WHERE id = $userId")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email']);
    $contactNumber = isset($_POST['contact_number']) ? sanitizeInput($_POST['contact_number']) : '';
    $bloodType = sanitizeInput($_POST['blood_type']);
    $medicalInfo = sanitizeInput($_POST['medical_info']);
    $language = sanitizeInput($_POST['language']);

    $stmt = $conn->prepare("UPDATE users SET email = ?, contact_number = ?, blood_type = ?, medical_info = ?, language_pref = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $email, $contactNumber, $bloodType, $medicalInfo, $language, $userId);

    if ($stmt->execute()) {
        $success = "Profile updated successfully";
        $user = $conn->query("SELECT * FROM users WHERE id = $userId")->fetch_assoc();
    } else {
        $error = "Failed to update profile: " . $conn->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="/emergency-response/assets/err.jpg" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency System - Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../includes/style.css">
</head>

<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-900 text-white p-4 transition-all duration-300 ease-in-out transform hover:shadow-2xl">
            <div class="flex justify-center mb-6">
                <img src="../assets/err.jpg" alt="Emergency System Logo" class="h-16 rounded-full shadow-lg hover:scale-110 transition-transform duration-300">
            </div>

            <h1 class="text-xl font-bold mb-8 text-center bg-gradient-to-r from-purple-500 to-pink-500 bg-clip-text text-transparent">Rescuelink Emergency</h1>

            <nav class="space-y-1">
                <div class="border-b border-gray-700 pb-2 mb-4"></div>
                <a href="dashboard.php" class="flex items-center p-3 rounded-lg hover:bg-purple-700/80 transform hover:translate-x-2 transition-all duration-300">
                    <i class="fas fa-home text-lg w-8"></i>
                    <span>Dashboard</span>
                </a>
                <a href="user_messages.php" class="flex items-center p-3 rounded-lg hover:bg-green-700/80 transform hover:translate-x-2 transition-all duration-300">
                    <i class="fas fa-comments text-lg w-8"></i>
                    <span>Talk With Us</span>
                </a>
                <a href="profile.php" class="flex items-center p-3 rounded-lg bg-violet-700 transform hover:translate-x-2 transition-all duration-300">
                    <i class="fas fa-user text-lg w-8"></i>
                    <span>Profile</span>
                </a>
                <a href="contacts.php" class="flex items-center p-3 rounded-lg hover:bg-lime-700/80 transform hover:translate-x-2 transition-all duration-300">
                    <i class="fas fa-address-book text-lg w-8"></i>
                    <span>Emergency Contacts</span>
                </a>

                <div class="border-t border-gray-700 pt-4 mt-4"></div>
                <a href="#" onclick="confirmLogout()" class="flex items-center p-3 rounded-lg hover:bg-red-700/80 transform hover:translate-x-2 transition-all duration-300">
                    <i class="fas fa-sign-out-alt text-lg w-8"></i>
                    <span>Logout</span>
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <h2 class="text-2xl font-bold mb-6" style="color:#fff">Your Profile</h2>

            <?php if (isset($success)): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="bg-white p-6 rounded-lg shadow">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 mb-2" for="username">Username</label>
                        <input type="text" value="<?php echo htmlspecialchars($user['username']); ?>" disabled
                            class="w-full px-3 py-2 border rounded-lg bg-gray-100">
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2" for="email">Email</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2" for="contactNumber">Contact Number</label>
                        <input type="number" name="contact_number" value="<?php echo htmlspecialchars($user['contact_number']); ?>" required
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2" for="blood_type">Blood Type</label>
                        <select name="blood_type" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="A+" <?php echo $user['blood_type'] === 'A+' ? 'selected' : ''; ?>>A+</option>
                            <option value="A-" <?php echo $user['blood_type'] === 'A-' ? 'selected' : ''; ?>>A-</option>
                            <option value="B+" <?php echo $user['blood_type'] === 'B+' ? 'selected' : ''; ?>>B+</option>
                            <option value="B-" <?php echo $user['blood_type'] === 'B-' ? 'selected' : ''; ?>>B-</option>
                            <option value="AB+" <?php echo $user['blood_type'] === 'AB+' ? 'selected' : ''; ?>>AB+</option>
                            <option value="AB-" <?php echo $user['blood_type'] === 'AB-' ? 'selected' : ''; ?>>AB-</option>
                            <option value="O+" <?php echo $user['blood_type'] === 'O+' ? 'selected' : ''; ?>>O+</option>
                            <option value="O-" <?php echo $user['blood_type'] === 'O-' ? 'selected' : ''; ?>>O-</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2" for="language">Language Preference</label>
                        <select name="language" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="en" <?php echo $user['language_pref'] === 'en' ? 'selected' : ''; ?>>English</option>
                            <option value="es" <?php echo $user['language_pref'] === 'es' ? 'selected' : ''; ?>>Spanish</option>
                            <option value="fr" <?php echo $user['language_pref'] === 'fr' ? 'selected' : ''; ?>>French</option>
                            <option value="de" <?php echo $user['language_pref'] === 'de' ? 'selected' : ''; ?>>German</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-gray-700 mb-2" for="medical_info">Medical Information</label>
                        <textarea name="medical_info" rows="4"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($user['medical_info']); ?></textarea>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition duration-200">
                        Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function confirmLogout() {
            Swal.fire({
                title: 'Ready to Leave?',
                text: 'Your session will be ended securely',
                icon: 'question',
                iconColor: '#3b82f6',
                showCancelButton: true,
                confirmButtonText: 'Sign Out',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#3b82f6',
                background: '#ffffff',
                borderRadius: '1rem',
                customClass: {
                    popup: 'border-4 border-gray-300 min-w-[300px] max-w-[400px]',
                    title: 'text-xl font-semibold text-gray-800',
                    content: 'text-gray-600',
                    confirmButton: 'px-4 py-2 rounded-lg transition-all duration-300 hover:shadow-lg',
                    cancelButton: 'px-4 py-2 rounded-lg transition-all duration-300 hover:shadow-lg'
                },
                showClass: {
                    popup: 'animate__animated animate__fadeInDown animate__faster'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp animate__faster'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Logging Out...',
                        text: 'Please wait while we secure your session',
                        timer: 1500,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    }).then(() => {
                        window.location.href = "../includes/logout.inc.php";
                    });
                }
            });
        }
    </script>

</body>

</html>