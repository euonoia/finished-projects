<?php
require_once '../includes/functions.inc.php';
require_once '../db/config.php';

session_start();

if (!isLoggedIn()) {
    redirect('login.php');
}

// Get user information
$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, email, blood_type, medical_info FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Get emergency contacts
$contacts = $conn->query("SELECT contact_name, contact_number FROM emergency_contacts WHERE user_id = $userId");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="/emergency-response/assets/err.jpg" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency System </title>
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
                <a href="dashboard.php" class="flex items-center p-3 rounded-lg bg-purple-700 transform hover:translate-x-2 transition-all duration-300">
                    <i class="fas fa-home text-lg w-8"></i>
                    <span>Dashboard</span>
                </a>
                <a href="user_messages.php" class="flex items-center p-3 rounded-lg hover:bg-green-700/80 transform hover:translate-x-2 transition-all duration-300">
                    <i class="fas fa-comments text-lg w-8"></i>
                    <span>Talk With Us</span>
                </a>
                <a href="profile.php" class="flex items-center p-3 rounded-lg hover:bg-violet-700/80 transform hover:translate-x-2 transition-all duration-300">
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
            <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-4 rounded-xl shadow-lg mb-8">
                <h2 class="text-2xl font-bold text-white">Welcome back, <?php echo htmlspecialchars($user['username']); ?>! 👋</h2>
                <p class="text-purple-100">Your safety is our priority.</p>
            </div>
            <!-- Emergency SOS Button -->
            <div class="mb-8 text-center">
                <button onclick="triggerEmergency()"
                    class="bg-red-500 hover:bg-red-600 text-white text-xl font-bold py-4 px-8 rounded-full shadow-lg transform hover:scale-110 hover:rotate-1 active:scale-95 transition-all duration-300"
                    onmouseover="this.classList.add('animate-wiggle')"
                    onmouseout="this.classList.remove('animate-wiggle')"
                    style="animation: heartbeat 1.5s ease-in-out infinite">
                    <i class="fas fa-bell mr-2 animate-spin"></i>
                    <span class="relative inline-block">
                        EMERGENCY SOS
                        <span class="absolute -top-1 -right-1 h-3 w-3 bg-yellow-400 rounded-full animate-pulse"></span>
                    </span>
                </button>
                <p class="mt-2 text-gray-600" style="color:#fff">Press in case of emergency</p><br>
                <button onclick="openEmergencyModal()"
                    class="bg-blue-500 hover:bg-blue-600 text-white text-xl font-bold py-4 px-8 rounded-full shadow-lg transition duration-200">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Detailed Emergency Report
                </button>

            </div>


            <div class="max-w-4xl mx-auto">
                <div class="bg-white/80 backdrop-blur-sm p-6 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300">
                    <div class="space-y-4">
                        <div class="flex items-center space-x-4 border-b pb-4">
                            <i class="fas fa-user-circle text-2xl text-purple-600"></i>
                            <div>
                                <p class="text-sm text-gray-500">Email: <?php echo htmlspecialchars($user['email']); ?></p>
                                <p class="text-sm text-gray-500">Blood Type: <?php echo htmlspecialchars($user['blood_type']); ?></p>
                                <p class="text-sm text-gray-500">Medical: <?php echo htmlspecialchars($user['medical_info']); ?></p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <i class="fas fa-phone-alt text-2xl text-green-600"></i>
                            <div>
                                <?php if ($contacts->num_rows > 0): ?>
                                    <?php while ($contact = $contacts->fetch_assoc()): ?>
                                        <p class="text-sm font-medium"><?php echo htmlspecialchars($contact['contact_name']); ?></p>
                                        <p class="text-sm text-gray-500"><?php echo htmlspecialchars($contact['contact_number']); ?></p>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <p class="text-sm text-gray-500">No emergency contacts</p>
                                <?php endif; ?>
                                <a href="contacts.php" class="text-xs text-purple-600 hover:text-purple-800">+ Add Contact</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Emergency Report Modal -->
        <div id="emergencyModal" class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-xl shadow-2xl max-w-md w-full mx-4 transform transition-all">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-500 mr-3"></i>
                        Report Emergency
                    </h2>
                    <button onclick="closeEmergencyModal()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <form id="emergencyForm" class="space-y-4">
                    <div class="relative">
                        <select id="emergencyType" class="w-full p-3 border border-gray-300 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="earthquake">🏚️ Earthquake</option>
                            <option value="flood">🌊 Flood</option>
                            <option value="fire">🔥 Fire</option>
                            <option value="accident">🚗 Accident</option>
                            <option value="medical">🏥 Medical Emergency</option>
                            <option value="crime">🚨 Crime</option>
                            <option value="others">❗ Others</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <textarea id="emergencyDescription" placeholder="Describe the emergency situation..." class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 min-h-[100px]"></textarea>

                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                        <input type="file" id="emergencyImage" class="hidden" accept="image/*" onchange="updateImagePreview(this)">
                        <label for="emergencyImage" class="cursor-pointer text-gray-600 hover:text-purple-600">
                            <i class="fas fa-camera mr-2"></i>
                            Upload Photo
                        </label>
                        <div id="imagePreview" class="mt-2 hidden">
                            <img id="previewImg" src="" alt="Preview" class="max-w-full h-32 mx-auto">
                            <p id="fileName" class="text-sm text-gray-500 mt-1"></p>
                        </div>
                    </div>

                    <input type="hidden" id="location" name="location">
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeEmergencyModal()" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                            Cancel
                        </button>
                        <button type="button" onclick="submitEmergencyReport()" class="px-6 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors flex items-center">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function updateImagePreview(input) {
                const preview = document.getElementById('imagePreview');
                const previewImg = document.getElementById('previewImg');
                const fileName = document.getElementById('fileName');

                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        fileName.textContent = input.files[0].name;
                        preview.classList.remove('hidden');
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            function triggerEmergency() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        position => {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;

                            fetch('../api/handle_sos.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        latitude: lat,
                                        longitude: lng
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: '🚨 Emergency Alert Sent!',
                                            text: 'Help is on the way.',
                                            confirmButtonColor: '#d33'
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: '⚠️ Failed to Send Alert',
                                            text: 'Please try again.',
                                            confirmButtonColor: '#d33'
                                        });
                                    }
                                })
                                .catch(error => {
                                    Swal.fire({
                                        icon: 'error',
                                        title: '❌ Error Sending Alert',
                                        text: error.message,
                                        confirmButtonColor: '#d33'
                                    });
                                });
                        },
                        error => {
                            Swal.fire({
                                icon: 'warning',
                                title: '⚠️ Location Error',
                                text: error.message,
                                confirmButtonColor: '#d33'
                            });
                        }
                    );
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: '❌ Geolocation Not Supported',
                        text: 'Your browser does not support geolocation.',
                        confirmButtonColor: '#d33'
                    });
                }
            }

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


            function getLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        document.getElementById("location").value =
                            position.coords.latitude + ", " + position.coords.longitude;
                    }, function(error) {
                        alert("Error getting location: " + error.message);
                    });
                } else {
                    alert("Geolocation is not supported by this browser.");
                }
            }

            // Open the modal and fetch location
            function openEmergencyModal() {
                document.getElementById('emergencyModal').classList.remove('hidden');
                fetchLocation();
            }

            // Close the modal
            function closeEmergencyModal() {
                document.getElementById('emergencyModal').classList.add('hidden');
            }

            // Submit Emergency Report
            function submitEmergencyReport() {
                const emergencyType = document.getElementById('emergencyType').value;
                const description = document.getElementById('emergencyDescription').value;
                const image = document.getElementById('emergencyImage').files[0];

                // Show loading screen
                Swal.fire({
                    title: 'Submitting Report...',
                    html: 'Please wait while we process your emergency report',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                if (!navigator.geolocation) {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Location Error',
                        text: 'Geolocation is not supported by your browser.',
                        confirmButtonColor: '#d33'
                    });
                    return;
                }

                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const formData = new FormData();
                        formData.append('type', emergencyType);
                        formData.append('description', description);
                        formData.append('latitude', position.coords.latitude);
                        formData.append('longitude', position.coords.longitude);

                        if (image) {
                            formData.append('image', image);
                            // Update loading message for image upload
                            Swal.update({
                                title: 'Uploading Image...',
                                html: 'Please wait while we upload your emergency photo'
                            });
                        }

                        fetch('../api/report_emergency.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                Swal.close(); // Close loading screen
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Emergency Reported!',
                                        text: `Location: ${data.address || 'Coordinates recorded'}`,
                                        confirmButtonColor: '#d33'
                                    });
                                    closeEmergencyModal();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Failed to Report',
                                        text: data.message,
                                        confirmButtonColor: '#d33'
                                    });
                                }
                            })
                            .catch(error => {
                                Swal.close(); // Close loading screen
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Something went wrong!',
                                    confirmButtonColor: '#d33'
                                });
                            });
                    },
                    function(error) {
                        Swal.close(); // Close loading screen
                        Swal.fire({
                            icon: 'error',
                            title: 'Location Error',
                            text: 'Could not get your location: ' + error.message,
                            confirmButtonColor: '#d33'
                        });
                    }
                );
            }
        </script>



</body>


</html>