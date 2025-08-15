<?php
require_once '../includes/functions.inc.php';
require_once '../db/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $contactNumber = sanitizeInput($_POST['contact_number']);
    $password = hashPassword($_POST['password']);
    $bloodType = sanitizeInput($_POST['blood_type']);
    $medicalInfo = sanitizeInput($_POST['medical_info']);

    $stmt = $conn->prepare("INSERT INTO users (username, email, contact_number, password, blood_type, medical_info, active) VALUES (?, ?, ?, ?, ?, ?, 1)");
    $stmt->bind_param("ssssss", $username, $email, $contactNumber, $password, $bloodType, $medicalInfo);

    if ($stmt->execute()) {
        header("Location: login.php?register=success");
        exit();
    } else {
        $error = "Registration failed: " . $conn->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency System - Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center backdrop-blur-md">
        <div class="bg-white/80 backdrop-blur-xl p-6 rounded-xl shadow-lg w-full max-w-sm">
            <div class="text-center mb-4">
                <img src="../assets/err.jpg" alt="Emergency System Logo" class="h-16 mx-auto rounded-lg">
                <h1 class="text-2xl font-bold text-red-600 mt-2">
                    <i class="fas fa-ambulance mr-2"></i>Register
                </h1>
            </div>

            <?php if (isset($error)): ?>
                <div class="bg-red-100/80 text-red-700 p-2 rounded mb-4 text-sm">
                    <i class="fas fa-exclamation-triangle mr-1"></i><?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-3">
                <div class="flex flex-wrap -mx-2">
                    <div class="w-1/2 px-2">
                        <input type="text" name="username" placeholder="Username" required
                            class="w-full px-3 py-2 border rounded text-sm">
                    </div>
                    <div class="w-1/2 px-2">
                        <input type="email" name="email" placeholder="Email" required
                            class="w-full px-3 py-2 border rounded text-sm">
                    </div>
                </div>

                <div class="flex flex-wrap -mx-2">
                    <div class="w-1/2 px-2">
                        <input type="number" name="contact_number" placeholder="Emergency Contact" required
                            class="w-full px-3 py-2 border rounded text-sm">
                    </div>
                    <div class="w-1/2 px-2">
                        <input type="password" name="password" placeholder="Password" required
                            class="w-full px-3 py-2 border rounded text-sm">
                    </div>
                </div>

                <select name="blood_type" class="w-full px-3 py-2 border rounded text-sm">
                    <option value="" disabled selected>Select Blood Type</option>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                </select>

                <div class="group">
                    <label class="block text-sm text-gray-700" for="medical_info">
                        <i class="fas fa-notes-medical text-red-500 mr-2"></i>Medical Information
                    </label>
                    <textarea name="medical_info" rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-300"
                        placeholder="List any allergies, conditions, or current medications..."></textarea>
                </div>

                <button type="submit"
                    class="w-full bg-red-500 text-white py-3 px-6 rounded-lg hover:bg-red-600 transform hover:scale-[1.02] transition-all duration-300 font-bold shadow-lg hover:shadow-red-500/30">
                    <i class="fas fa-user-plus mr-2"></i>Register Now
                </button>

                <p class="text-center mt-6 text-gray-600">
                    Already registered?
                    <a href="login.php" class="text-red-500 hover:text-red-600 font-semibold hover:underline transition-all duration-300">
                        Sign in here
                    </a>
                </p>
            </form>
        </div>
    </div>
</body>

</html>