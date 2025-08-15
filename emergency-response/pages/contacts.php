<?php
require_once '../includes/functions.inc.php';
require_once '../db/config.php';

session_start();

if (!isLoggedIn()) {
    redirect('login.php');
}

$userId = $_SESSION['user_id'];



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update']) && !empty($_POST['contact_id'])) {
        // Updating an existing contact
        $contact_id = sanitizeInput($_POST['contact_id']);
        $contact_name = sanitizeInput($_POST['contact_name']);
        $contact_number = sanitizeInput($_POST['contact_number']);

        $stmt = $conn->prepare("UPDATE emergency_contacts SET contact_name = ?, contact_number = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ssii", $contact_name, $contact_number, $contact_id, $userId);

        if ($stmt->execute()) {
            echo "<script>alert('Contact updated successfully!'); window.location.href='contacts.php';</script>";
        } else {
            echo "<script>alert('Failed to update contact.');</script>";
        }
    } elseif (isset($_POST['delete'])) {
        // Deleting a contact
        $contactId = sanitizeInput($_POST['contact_id']);
        $stmt = $conn->prepare("DELETE FROM emergency_contacts WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $contactId, $userId);
        $stmt->execute();
    } else {
        // Adding a new contact
        $name = sanitizeInput($_POST['contact_name']);
        $number = sanitizeInput($_POST['contact_number']);

        $stmt = $conn->prepare("INSERT INTO emergency_contacts (user_id, contact_name, contact_number) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $userId, $name, $number);
        $stmt->execute();
    }
}


// Get current contacts
$contacts = $conn->query("SELECT id, contact_name, contact_number FROM emergency_contacts WHERE user_id = $userId");


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="/emergency-response/assets/err.jpg" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency Contacts</title>
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
                <a href="profile.php" class="flex items-center p-3 rounded-lg hover:bg-violet-700/80 transform hover:translate-x-2 transition-all duration-300">
                    <i class="fas fa-user text-lg w-8"></i>
                    <span>Profile</span>
                </a>
                <a href="contacts.php" class="flex items-center p-3 rounded-lg bg-lime-700 transform hover:translate-x-2 transition-all duration-300">
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
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold" style="color:#fff">Emergency Contacts</h2>
            </div>

            <!-- Add Contact Form -->
            <div class="bg-white p-6 rounded-lg shadow mb-6">
                <h3 class="text-lg font-semibold mb-4">Add New Contact</h3>
                <form method="POST">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 mb-2" for="contact_name">Name</label>
                            <input type="text" name="contact_name" required
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-2" for="contact_number">Phone Number</label>
                            <input type="tel" name="contact_number" required
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition duration-200">
                        Add Contact
                    </button>
                </form>
            </div>
            <?php
            // Handle form submissions
            if (isset($_POST['update'])) {
                $contact_id = $_POST['contact_id'];  // Get the contact ID
                $contact_name = $_POST['contact_name'];
                $contact_number = $_POST['contact_number'];

                $stmt = $conn->prepare("UPDATE emergency_contacts SET contact_name = ?, contact_number = ? WHERE id = ?");
                $stmt->bind_param("ssi", $contact_name, $contact_number, $contact_id);

                if ($stmt->execute()) {
                    echo "<script>alert('Contact updated successfully!'); window.location.href='contacts.php';</script>";
                } else {
                    echo "<script>alert('Failed to update contact.');</script>";
                }
            }

            ?>
            <!-- Contacts List -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-4">Your Emergency Contacts</h3>
                <?php if ($contacts->num_rows > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone Number</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php while ($contact = $contacts->fetch_assoc()): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($contact['contact_name']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($contact['contact_number']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <!-- Edit Button -->
                                            <button onclick="editContact('<?php echo $contact['id']; ?>', '<?php echo htmlspecialchars($contact['contact_name']); ?>', '<?php echo htmlspecialchars($contact['contact_number']); ?>')"
                                                class="text-blue-500 hover:text-blue-700">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <!-- Edit Contact Modal -->
                                            <div id="editModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
                                                <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                                                    <h3 class="text-lg font-semibold mb-4">Edit Emergency Contact</h3>
                                                    <form id="editContactForm" method="POST">
                                                        <form id="editContactForm" method="POST">
                                                            <!-- Hidden input to store the contact ID -->
                                                            <input type="hidden" name="contact_id" id="editContactId">

                                                            <label class="block text-sm font-medium text-gray-700">Name</label>
                                                            <input type="text" name="contact_name" id="editContactName" required class="w-full p-2 border rounded mb-4">

                                                            <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                                                            <input type="text" name="contact_number" id="editContactNumber" required class="w-full p-2 border rounded mb-4">

                                                            <div class="flex justify-end">
                                                                <button type="button" onclick="closeEditModal()" class="bg-gray-300 px-4 py-2 rounded mr-2">Cancel</button>
                                                                <button type="submit" name="update" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>

                                                            </div>
                                                        </form>

                                                </div>
                                            </div>

                                            <!-- Delete Form -->
                                            <form method="POST" onsubmit="return confirm('Are you sure you want to delete this contact?');" class="inline">
                                                <input type="hidden" name="contact_id" value="<?php echo $contact['id']; ?>">
                                                <button type="submit" name="delete" class="text-red-500 hover:text-red-700">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>

                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500">You haven't added any emergency contacts yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

</body>

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


    function editContact(id, name, number) {
        document.getElementById('editContactId').value = id; // Make sure ID is set
        document.getElementById('editContactName').value = name;
        document.getElementById('editContactNumber').value = number;
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>

</html>