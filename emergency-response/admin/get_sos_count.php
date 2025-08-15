<?php
require_once '../db/config.php';

// Fetch unread SOS count
$result = $conn->query("SELECT COUNT(*) AS count FROM incidents WHERE status = 'active' AND is_read = 0");
echo $result->fetch_assoc()['count'] ?? 0;
?>
