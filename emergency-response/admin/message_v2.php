<?php
// message_v2.php - New Messaging System
require_once '../db/config.php';
require_once '../includes/functions.inc.php';
session_start();

if (!isAdmin()) {
    redirect('admin-login.php');
}

$adminId = $_SESSION['admin_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Chat v2</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100">
<audio id="notificationSound" src="../assets/sound-2.mp3" preload="auto"></audio>
<div class="flex h-screen">
  <!-- Sidebar -->
  <div class="w-64 bg-gray-800 text-white p-4">
    <h1 class="text-xl font-bold mb-4">Admin Panel</h1>
    <ul>
      <li><a href="dashboard.php" class="block py-2 hover:text-green-400">Dashboard</a></li>
      <li><a href="message_v2.php" class="block py-2 text-green-400 font-semibold">Messages v2</a></li>
      <li><a href="#" onclick="confirmLogout()" class="block py-2 text-red-400">Logout</a></li>
    </ul>
  </div>

  <!-- Chat Area -->
  <div class="flex-1 p-6">
    <div class="max-w-4xl mx-auto bg-white shadow rounded p-4 h-full flex flex-col">
      <h2 class="text-2xl font-bold mb-4">Admin Chat (v2)</h2>
      <select id="userSelect" class="mb-4 p-2 border rounded">
        <option value="">Select a user</option>
        <?php
        $users = $conn->query("SELECT id, username FROM users");
        while ($user = $users->fetch_assoc()) {
            echo '<option value="' . $user['id'] . '">' . htmlspecialchars($user['username']) . '</option>';
        }
        ?>
      </select>
      <div id="chatBox" class="flex-1 overflow-y-auto p-4 bg-gray-200 rounded h-96 mb-4"></div>
      <form id="chatForm" class="flex gap-2">
        <input type="text" id="chatInput" placeholder="Type a message" class="flex-1 border p-2 rounded">
        <button class="bg-green-500 px-4 text-white rounded">Send</button>
      </form>
    </div>
  </div>
</div>

<script>
let currentUser = null;
let lastMsgId = null;

const userSelect = document.getElementById('userSelect');
const chatBox = document.getElementById('chatBox');
const chatInput = document.getElementById('chatInput');
const chatForm = document.getElementById('chatForm');

userSelect.addEventListener('change', () => {
  currentUser = userSelect.value;
  chatBox.innerHTML = '';
  if (currentUser) loadMessages();
});

chatForm.addEventListener('submit', e => {
  e.preventDefault();
  const msg = chatInput.value.trim();
  if (!msg || !currentUser) return;

  fetch('send_message.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `receiver_id=${currentUser}&message=${encodeURIComponent(msg)}`
  }).then(res => res.text()).then(() => {
    chatInput.value = '';
    loadMessages();
  });
});

function loadMessages() {
  fetch(`get_messages.php?contact_id=${currentUser}`)
    .then(res => res.json())
    .then(data => {
      chatBox.innerHTML = '';
      let latestId = null;
      data.forEach(msg => {
        const isAdmin = msg.sender_id == <?= $adminId ?>;
        const bubble = `<div class="flex ${isAdmin ? 'justify-end' : 'justify-start'} mb-2">
          <div class="px-4 py-2 rounded-xl shadow ${isAdmin ? 'bg-blue-600 text-white' : 'bg-white text-black'}` + `">
            ${msg.message}
          </div>
        </div>`;
        chatBox.innerHTML += bubble;
        latestId = msg.id;
      });
      if (lastMsgId && latestId && latestId > lastMsgId) {
        playNotification();
      }
      lastMsgId = latestId;
      chatBox.scrollTop = chatBox.scrollHeight;
    });
}

setInterval(() => {
  if (currentUser) loadMessages();
}, 3000);

function playNotification() {
  document.getElementById('notificationSound').play();
  Toastify({
    text: 'New message!',
    duration: 3000,
    backgroundColor: '#22c55e',
    gravity: 'top',
    position: 'right',
    close: true
  }).showToast();
}

function confirmLogout() {
  Swal.fire({
    title: 'Logout?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes',
    cancelButtonText: 'No'
  }).then(result => {
    if (result.isConfirmed) {
      location.href = '../includes/logout.inc.php';
    }
  });
}
</script>
</body>
</html>
