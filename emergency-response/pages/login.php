<?php
require_once '../includes/functions.inc.php';
require_once '../db/config.php';

session_start();

if (isLoggedIn()) {
    redirect('dashboard.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password, active FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (verifyPassword($password, $user['password'])) {
            if ($user['active'] == 1) {
                $_SESSION['user_id'] = $user['id'];
                redirect('dashboard.php');
            } else {
                $error = "Your account is inactive. Please contact administrator.";
            }
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
    <title>Emergency System - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="styles.css">

    <style>
        .scroll-reveal {
            opacity: 0;
            transform: translateY(50px);
            transition: all 1s ease-out;
        }

        .scroll-reveal.active {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>

<body class="bg-gray-100">

    <nav class="bg-transparent backdrop-blur-lg fixed w-full top-0 z-50 shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="#home" class="flex items-center">
                        <img src="../assets/err.jpg" alt="Emergency Response Logo" class="h-8 w-8 rounded-full mr-2">
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="#home" class="text-white hover:text-red-600 px-3 py-2 rounded-md transition-all duration-300 ease-in-out" onclick="event.preventDefault(); document.getElementById('home').scrollIntoView({behavior: 'smooth'})">Home</a>
                    <a href="#contacts" class="text-white hover:text-red-600 px-3 py-2 rounded-md transition-all duration-300 ease-in-out" onclick="event.preventDefault(); document.getElementById('contacts').scrollIntoView({behavior: 'smooth'})">Contacts</a>
                    <a href="#about" class="text-white hover:text-red-600 px-3 py-2 rounded-md transition-all duration-300 ease-in-out" onclick="event.preventDefault(); document.getElementById('about').scrollIntoView({behavior: 'smooth'})">About Us</a>
                </div>
            </div>
        </div>
    </nav>

    <div id="home" class="min-h-screen flex items-center justify-center bg-[url('../assets/background.jpg')] bg-cover bg-center bg-no-repeat backdrop-blur-xl pt-16 scroll-smooth transition-all duration-500">
        <div class="flex w-full max-w-5xl mx-auto">
            <!-- Left side - Information -->
            <div class="w-1/2 p-8 flex flex-col items-center justify-center scroll-reveal">
                <h2 class="text-6xl font-bold text-white mb-6">Emergency Response System</h2>
            </div>

            <!-- Login Form -->
            <div class="w-1/3 mx-auto scroll-reveal">
                <div class="backdrop-blur-md bg-white/70 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-2xl">
                    <h1 class="text-2xl font-bold text-red-700 text-center mb-6">Login</h1>

                    <?php if (isset($error)): ?>
                        <div class="bg-red-100/80 text-red-700 p-3 rounded mb-4 border border-red-200 text-center">
                            <i class="fas fa-exclamation-circle mr-2"></i><?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="space-y-4">
                        <div class="relative">
                            <i class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text" name="username" placeholder="Username" required
                                class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:border-red-400 focus:outline-none bg-white/80 transition-all duration-300">
                        </div>

                        <div class="relative">
                            <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="password" name="password" placeholder="Password" required
                                class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:border-red-400 focus:outline-none bg-white/80 transition-all duration-300">
                        </div>

                        <button type="submit" class="w-full bg-red-600 text-white py-2 rounded-lg font-medium transition-all duration-300 hover:bg-red-700">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                        </button>

                        <div class="text-center">
                            <a href="register.php" class="text-red-600 font-medium inline-flex items-center justify-center transition-all duration-300 hover:text-red-700">
                                <i class="fas fa-heart mr-2"></i>Join Our Community
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="contacts" class="scroll-mt-16 transition-all duration-500">
        <div class="min-h-screen bg-gray-900 py-16">
            <div class="max-w-5xl mx-auto px-4">
                <h2 class="text-4xl font-bold text-white text-center mb-12 scroll-reveal">Contact Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white/10 p-6 rounded-lg text-white text-center transition-transform duration-300 hover:scale-105 scroll-reveal">
                        <i class="fas fa-phone-alt text-4xl text-red-500 mb-4"></i>
                        <h3 class="text-xl font-semibold mb-2">Emergency Hotline</h3>
                        <p>24/7 Emergency: 911</p>
                        <p>Helpdesk: (02) 8123-4567</p>
                    </div>
                    <div class="bg-white/10 p-6 rounded-lg text-white text-center transition-transform duration-300 hover:scale-105 scroll-reveal">
                        <i class="fas fa-map-marker-alt text-4xl text-red-500 mb-4"></i>
                        <h3 class="text-xl font-semibold mb-2">Location</h3>
                        <p>123 Emergency Street</p>
                        <p>Metro Manila, Philippines</p>
                    </div>
                    <div class="bg-white/10 p-6 rounded-lg text-white text-center transition-transform duration-300 hover:scale-105 scroll-reveal">
                        <i class="fas fa-envelope text-4xl text-red-500 mb-4"></i>
                        <h3 class="text-xl font-semibold mb-2">Email</h3>
                        <p>help@rescuelink.com</p>
                        <p>support@rescuelink.com</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="about" class="scroll-mt-16 transition-all duration-500">
        <div class="min-h-screen bg-gray-900 py-16">
            <div class="max-w-5xl mx-auto px-4">
                <h2 class="text-4xl font-bold text-white text-center mb-12 scroll-reveal">About Us</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-white/10 p-6 rounded-lg text-white transition-transform duration-300 hover:scale-105 scroll-reveal">
                        <h3 class="text-2xl font-semibold mb-4 text-red-500">Our Mission</h3>
                        <p class="text-gray-300 leading-relaxed">
                            We are dedicated to providing rapid and efficient emergency response services to our community. Our mission is to save lives, minimize injuries, and protect property through immediate and professional emergency assistance.
                        </p>
                    </div>
                    <div class="bg-white/10 p-6 rounded-lg text-white transition-transform duration-300 hover:scale-105 scroll-reveal">
                        <h3 class="text-2xl font-semibold mb-4 text-red-500">Our Vision</h3>
                        <p class="text-gray-300 leading-relaxed">
                            To be the leading emergency response system, leveraging technology and human expertise to create a safer community where help is always just a click away.
                        </p>
                    </div>
                </div>
                <div class="mt-8 bg-white/10 p-6 rounded-lg text-white transition-transform duration-300 hover:scale-105 scroll-reveal">
                    <h3 class="text-2xl font-semibold mb-4 text-red-500">Why Choose Us?</h3>
                    <ul class="grid grid-cols-1 md:grid-cols-3 gap-4 text-gray-300">
                        <li class="flex items-center"><i class="fas fa-check-circle text-red-500 mr-2"></i>24/7 Emergency Support</li>
                        <li class="flex items-center"><i class="fas fa-check-circle text-red-500 mr-2"></i>Rapid Response Time</li>
                        <li class="flex items-center"><i class="fas fa-check-circle text-red-500 mr-2"></i>Professional Team</li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
    <?php if (isset($_GET['register']) && $_GET['register'] === 'success'): ?>
        <script>
            Swal.fire({
                title: "Emergency Access Granted!",
                text: "You can now log in to the emergency system.",
                icon: "success",
                confirmButtonColor: "#ef4444",
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            });

            // Clean URL
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.pathname);
            }
        </script>
    <?php endif; ?>

    <script>
        // Scroll reveal animation
        function checkScroll() {
            const elements = document.querySelectorAll('.scroll-reveal');
            elements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                const windowHeight = window.innerHeight;

                if (elementTop < windowHeight * 0.8) {
                    element.classList.add('active');
                }
            });
        }

        // Initial check
        checkScroll();

        // Add scroll event listener
        window.addEventListener('scroll', checkScroll);
    </script>

</body>

</html>