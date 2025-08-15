<?php
require_once '../db/config.php';
require_once '../includes/functions.inc.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    redirect('login.php'); // Adjust as needed
}

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Support Center</title>
    <link rel="icon" href="/emergency-response/assets/err.jpg" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../includes/style.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Toastify CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <!-- Notification Sound -->
    <audio id="notificationSound" src="../assets/sound-5.mp3" preload="auto"></audio>

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
                <a href="user_messages.php" class="flex items-center p-3 rounded-lg bg-green-700 transform hover:translate-x-2 transition-all duration-300">
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

        <!-- Main Chat Area -->
        <div class="flex-1 p-6">
            <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-2xl h-full flex flex-col transition-all duration-300 hover:shadow-xl">
                <h2 class="text-xl font-medium p-5 border-b text-gray-800 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span id="adminStatus" class="w-2 h-2 rounded-full"></span>
                        Professional Support Center
                    </div>
                    <span id="statusText" class="text-sm font-normal"></span>
                </h2>

                <!-- Chat Box -->
                <div id="chatBox"
                    class="flex-1 overflow-y-auto bg-gray-50 p-4 flex flex-col space-y-2 transition-all scroll-smooth"
                    style="scrollbar-width: thin; scrollbar-color: #E5E7EB transparent;">
                </div>

                <!-- Message Form -->
                <form id="chatForm" class="p-4 border-t flex items-center gap-3">
                    <input type="text"
                        id="chatMessage"
                        placeholder="Type your message..."
                        class="flex-1 px-5 py-3 rounded-full bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-400/50 transition-all duration-300 placeholder-gray-400"
                        autocomplete="off">
                    <button type="submit"
                        class="bg-green-500 text-white px-8 py-3 rounded-full hover:bg-green-600 active:scale-95 transition-all duration-300 flex items-center gap-2 shadow-md hover:shadow-lg"
                        id="sendButton">
                        <span>Send</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </form>
            </div>
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


        const chatBox = document.getElementById('chatBox');
        const chatForm = document.getElementById('chatForm');
        const chatMessage = document.getElementById('chatMessage');
        const ADMIN_ID = 1; // Assumed admin ID

        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if (!chatMessage.value.trim()) return;

            fetch('../admin/send_message.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `receiver_id=${ADMIN_ID}&message=${encodeURIComponent(chatMessage.value)}`
            }).then(() => {
                chatMessage.value = '';
                loadMessages();
            });
        });

        let lastMessageId = null;

        function loadMessages() {
            fetch(`../admin/git_missig.php?contact_id=${ADMIN_ID}`)
                .then(res => res.json())
                .then(data => {
                    chatBox.innerHTML = '';
                    let latestId = null;

                    data.forEach(msg => {
                        const isUser = msg.sender_id == <?= $user_id ?>;
                        const alignClass = isUser ? 'items-end' : 'items-start';
                        const bubbleClass = isUser ?
                            'bg-green-600 text-white rounded-br-none' :
                            'bg-gray-300 text-gray-900 rounded-bl-none';
                        const senderLabel = isUser ? 'You' : 'Admin';

                        const messageHTML = `
                    <div class="flex flex-col ${alignClass} w-full mb-2">
                        <span class="text-xs text-gray-500 mb-1 px-2">${senderLabel}</span>
                        <div class="max-w-sm px-4 py-2 rounded-2xl shadow ${bubbleClass}">
                            ${msg.message}
                        </div>
                    </div>
                `;
                        chatBox.innerHTML += messageHTML;

                        latestId = msg.id;
                    });

                    // Trigger notification if a new message from admin is detected
                    if (latestId && lastMessageId && latestId > lastMessageId) {
                        const latestMsg = data.find(msg => msg.id == latestId);
                        if (latestMsg && latestMsg.sender_id == ADMIN_ID) {
                            playNotification();
                        }
                    }

                    lastMessageId = latestId;
                    chatBox.scrollTop = chatBox.scrollHeight;
                });
        }


        setInterval(loadMessages, 2000);

        function playNotification() {
            document.getElementById('notificationSound').play();

            Toastify({
                text: "New message from Admin!",
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "#00b09b"
            }).showToast();
        }
    </script>
</body>

</html>