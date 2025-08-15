<?php
require_once '../db/config.php';
require_once '../includes/functions.inc.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    redirect('users.php'); // Adjust as needed
}

$user_id = $_SESSION['admin_id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Chat with Users</title>
    <link rel="icon" href="/emergency-response/assets/err.jpg" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="" style="background:#4efc51">
    <audio id="notificationSound" src="../assets/sound-2.mp3" preload="auto"></audio>
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
                    <a href="recent_reports.php" class="flex items-center p-3 rounded-lg hover:bg-blue-600/50 transition-all duration-300">
                        <i class="fas fa-flag text-blue-500"></i>
                        <span class="ml-3">Recent Reports</span>
                    </a>
                    <a href="message.php" aria-current="page" class="flex items-center p-3 rounded-lg bg-green-600/80 tra
                    transition-all duration-300 ">
                        <i class="fas fa-comments text-green-200"></i>
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

        <!-- Chat Section -->

        <div class="flex-1 p-6 overflow-auto bg-gradient-to-br from-gray-100 to-gray-300">
            <div class="max-w-3xl mx-auto bg-white shadow-xl rounded-2xl p-0 h-full flex flex-col border border-gray-200">
                <!-- Chat Header -->
                <div class="sticky top-0 z-10 bg-white rounded-t-2xl px-6 py-4 flex items-center border-b">
                    <i class="fas fa-comments text-green-500 text-2xl mr-3"></i>
                    <h2 class="text-xl font-bold flex-1">Chat with Users</h2>
                    <!-- Optional: Add online status or admin avatar -->
                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-800">
                        Admin
                    </span>
                </div>

                <!-- User Select -->
                <div class="px-6 pt-4">
                    <select id="contactSelect" class="mb-4 p-3 border border-gray-300 rounded-lg w-full focus:ring-2 focus:ring-green-400 transition">
                        <option value="">-- Select a user --</option>
                        <?php
                        $users = $conn->query("SELECT id, username , contact_number FROM users");
                        while ($user = $users->fetch_assoc()):
                        ?>
                            <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['username']) ?> - <?= htmlspecialchars($user['contact_number']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Chat Box -->
                <div id="chatBox" class="flex-1 overflow-y-auto bg-gray-50 px-6 py-4 border-b border-t h-96 flex flex-col space-y-3 custom-scrollbar">
                    <!-- Example message bubbles (to be dynamically filled) -->
                    <!--
                    <div class="flex items-end gap-2">
                        <img src="https://ui-avatars.com/api/?name=User" class="w-8 h-8 rounded-full border" alt="User Avatar">
                        <div class="bg-gray-200 text-gray-800 px-4 py-2 rounded-2xl rounded-bl-none max-w-xs shadow">
                            Hello, how can I help you?
                        </div>
                    </div>
                    <div class="flex items-end gap-2 justify-end">
                        <div class="bg-green-500 text-white px-4 py-2 rounded-2xl rounded-br-none max-w-xs shadow">
                            I need assistance with my account.
                        </div>
                        <img src="https://ui-avatars.com/api/?name=Admin" class="w-8 h-8 rounded-full border" alt="Admin Avatar">
                    </div>
                    -->
                </div>

                <!-- Message Form -->
                <form id="chatForm" class="flex gap-2 px-6 py-4 bg-white rounded-b-2xl border-t">
                    <input type="text" id="chatMessage" placeholder="Type a message..." autocomplete="off"
                        class="flex-grow border border-gray-300 p-3 rounded-l-xl focus:outline-none focus:ring-2 focus:ring-green-400 transition text-gray-800 bg-gray-100">
                    <button type="submit"
                        class="bg-green-500 text-white px-6 py-2 rounded-r-xl font-semibold hover:bg-green-600 transition shadow">
                        <i class="fas fa-paper-plane"></i>
                        <span class="sr-only">Send</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Optional: Custom scrollbar styling -->
        <style>
            .custom-scrollbar::-webkit-scrollbar {
                width: 8px;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: #d1d5db;
                border-radius: 4px;
            }

            .custom-scrollbar {
                scrollbar-width: thin;
                scrollbar-color: #d1d5db #f9fafb;
            }
        </style>


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
                    window.location.href = "../includes/logout.inc.php";
                }
            });
        }

        const chatBox = document.getElementById('chatBox');
        const contactSelect = document.getElementById('contactSelect');
        const chatForm = document.getElementById('chatForm');
        const chatMessage = document.getElementById('chatMessage');

        let currentContact = null;

        contactSelect.addEventListener('change', function() {
            currentContact = this.value;
            loadMessages();
            setTimeout(() => {
                fetch(`get_messages.php?contact_id=${currentContact}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.length) {
                            lastMessageId = data[data.length - 1].id;
                        }
                    });
            }, 500);

        });

        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if (!currentContact || !chatMessage.value.trim()) return;

            fetch('sind_missig.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `receiver_id=${currentContact}&message=${encodeURIComponent(chatMessage.value)}`
            }).then(() => {
                chatMessage.value = '';
                loadMessages();
            });
        });

        function loadMessages() {
            if (!currentContact) return;
            fetch(`get_messages.php?contact_id=${currentContact}`)
                .then(res => res.json())
                .then(data => {
                    chatBox.innerHTML = '';
                    data.forEach(msg => {
                        const isAdmin = msg.sender_id == <?= $_SESSION['admin_id'] ?>;
                        const alignClass = isAdmin ? 'justify-end' : 'justify-start';
                        const bubbleClass = isAdmin ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-900';

                        const messageHTML = `
        <div class="flex ${alignClass} mb-2">
            <div class="max-w-xs px-4 py-2 rounded-2xl ${bubbleClass} shadow">
                ${msg.message}
            </div>
        </div>
    `;
                        chatBox.innerHTML += messageHTML;
                    });

                    chatBox.scrollTop = chatBox.scrollHeight;
                });
        }

        setInterval(loadMessages, 2000);

        let lastMessageId = null;

        function loadMessages() {
            if (!currentContact) return;
            fetch(`get_messages.php?contact_id=${currentContact}`)
                .then(res => res.json())
                .then(data => {
                    chatBox.innerHTML = '';
                    let latestId = null;

                    data.forEach(msg => {
                        const isAdmin = msg.sender_id == <?= $_SESSION['admin_id'] ?>;
                        const alignClass = isAdmin ? 'justify-end' : 'justify-start';
                        const bubbleClass = isAdmin ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-900';

                        const messageHTML = `
                    <div class="flex ${alignClass} mb-2">
                        <div class="max-w-xs px-4 py-2 rounded-2xl ${bubbleClass} shadow">
                            ${msg.message}
                        </div>
                    </div>
                `;
                        chatBox.innerHTML += messageHTML;

                        latestId = msg.id; // Track last ID
                    });

                    if (latestId && lastMessageId && latestId > lastMessageId) {
                        const latestMsg = data.find(msg => msg.id == latestId);
                        if (latestMsg && latestMsg.sender_id != <?= $_SESSION['admin_id'] ?>) {
                            playNotification();
                        }
                    }


                    lastMessageId = latestId;
                    chatBox.scrollTop = chatBox.scrollHeight;
                });
        }

        function playNotification() {
            document.getElementById('notificationSound').play();

            Toastify({
                text: "New message received!",
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "#00ff80", // green
            }).showToast();
        }
    </script>

</body>

</html>