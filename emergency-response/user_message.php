<?php
require_once 'db/config.php';
require_once 'includes/functions.inc.php';
session_start();

if (!isUser()) { // You need to implement this function to check user login
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];

// Find the admin's ID (assuming only one admin)
$admin = $conn->query("SELECT id FROM admins LIMIT 1")->fetch_assoc();
$admin_id = $admin['id'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Chat</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body style="background:#f0f0f0">
    <audio id="notificationSound" src="assets/sound-2.mp3" preload="auto"></audio>
    <div class="flex h-screen items-center justify-center">
        <div class="w-full max-w-2xl bg-white shadow rounded p-6 flex flex-col h-[80vh]">
            <h2 class="text-2xl font-bold mb-4">Chat with Admin</h2>
            <div id="chatBox" class="flex-1 overflow-y-auto bg-gray-200 p-4 border rounded mb-4 h-96 flex flex-col space-y-2"></div>
            <form id="chatForm" class="flex mt-auto gap-2">
                <input type="text" id="chatMessage" placeholder="Type a message..." class="flex-grow border p-2 rounded-l focus:outline-none">
                <button type="submit" class="bg-blue-500 text-white px-4 rounded-r hover:bg-blue-700">Send</button>
            </form>
        </div>
    </div>
    <script>
        const chatBox = document.getElementById('chatBox');
        const chatForm = document.getElementById('chatForm');
        const chatMessage = document.getElementById('chatMessage');
        let lastMessageId = null;

        function loadMessages() {
            fetch(`get_messages.php?contact_id=<?= $admin_id ?>`)
                .then(res => res.json())
                .then(data => {
                    chatBox.innerHTML = '';
                    let latestId = null;
                    data.forEach(msg => {
                        const isUser = msg.sender_id == <?= $user_id ?>;
                        const alignClass = isUser ? 'justify-end' : 'justify-start';
                        const bubbleClass = isUser ? 'bg-blue-500 text-white' : 'bg-gray-300 text-gray-900';
                        const messageHTML = `
                            <div class="flex ${alignClass} mb-2">
                                <div class="max-w-xs px-4 py-2 rounded-2xl ${bubbleClass} shadow">
                                    ${msg.message}
                                </div>
                            </div>
                        `;
                        chatBox.innerHTML += messageHTML;
                        latestId = msg.id;
                    });
                    if (latestId && lastMessageId && latestId > lastMessageId) {
                        const latestMsg = data.find(msg => msg.id == latestId);
                        if (latestMsg && latestMsg.sender_id != <?= $user_id ?>) {
                            playNotification();
                        }
                    }
                    lastMessageId = latestId;
                    chatBox.scrollTop = chatBox.scrollHeight;
                });
        }

        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if (!chatMessage.value.trim()) return;
            fetch('send_message.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `receiver_id=<?= $admin_id ?>&message=${encodeURIComponent(chatMessage.value)}`
            }).then(() => {
                chatMessage.value = '';
                loadMessages();
            });
        });

        function playNotification() {
            document.getElementById('notificationSound').play();
            Toastify({
                text: "New message received!",
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "#00ff80",
            }).showToast();
        }

        setInterval(loadMessages, 2000);
        loadMessages();
    </script>
</body>

</html>