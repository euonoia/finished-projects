<?php
require_once '../includes/functions.inc.php';
require_once '../db/config.php';

session_start();

if (isAdmin()) {
    redirect('../admin/dashboard.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        if (verifyPassword($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            redirect('../admin/dashboard.php');
        } else {
            $error = "Invalid username or password";
        }
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/emergency-response/assets/err.jpg" type="image/x-icon">
    <title>Emergency System - Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center backdrop-blur-md">
        <div class="bg-white/80 backdrop-blur-xl p-8 rounded-2xl shadow-2xl w-full max-w-sm">
            <div class="flex justify-center mb-6 relative">
                <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg blur opacity-25"></div>
                <img src="../assets/err.jpg" alt="Logo" class="h-20 rounded-xl relative shadow-lg">
            </div>
            <h1 class="text-3xl font-bold text-center mb-8 bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent animate-gradient">Admin Login</h1>

            <?php if (isset($error)): ?>
                <div class="bg-red-100/80 text-red-600 p-4 rounded-lg mb-6 text-sm border border-red-200 backdrop-blur-sm">
                    <i class="fas fa-exclamation-circle mr-2"></i><?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div>
                    <div class="relative">
                        <i class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="username" placeholder="Username" required
                            class="w-full pl-10 pr-3 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 ring-blue-300/50 focus:outline-none bg-white/80 backdrop-blur-sm text-gray-700">
                    </div>
                </div>

                <div>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="password" name="password" placeholder="Password" required
                            class="w-full pl-10 pr-12 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 ring-blue-300/50 focus:outline-none bg-white/80 backdrop-blur-sm text-gray-700">
                        <i class="fas fa-eye absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 cursor-pointer"
                            onclick="togglePassword(this)"></i>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 rounded-xl font-semibold text-lg relative overflow-hidden backdrop-blur-sm">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login
                </button>
            </form>

            <script>
                function togglePassword(element) {
                    const passwordInput = element.previousElementSibling;
                    if (passwordInput.type === "password") {
                        passwordInput.type = "text";
                        element.classList.remove("fa-eye");
                        element.classList.add("fa-eye-slash");
                    } else {
                        passwordInput.type = "password";
                        element.classList.remove("fa-eye-slash");
                        element.classList.add("fa-eye");
                    }
                }
            </script>
        </div>
    </div>
</body>

</html>