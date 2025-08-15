<?php
require_once '../db/config.php';

$response = [];

// Incident counts
$result = $conn->query("SELECT 
    SUM(status = 'active') AS active,
    SUM(status = 'resolved') AS resolved 
    FROM incidents");
$row = $result->fetch_assoc();
$response['incidents_active'] = $row['active'] ?? 0;
$response['incidents_resolved'] = $row['resolved'] ?? 0;

// Community alerts
$result = $conn->query("SELECT COUNT(*) AS count FROM community_alerts");
$response['alerts'] = $result->fetch_assoc()['count'] ?? 0;

// Recent reports (customize table name if needed)
$result = $conn->query("SELECT COUNT(*) AS count FROM emergency_reports");
$response['reports'] = $result->fetch_assoc()['count'] ?? 0;

// Registered users
$result = $conn->query("SELECT COUNT(*) AS count FROM users");
$response['users'] = $result->fetch_assoc()['count'] ?? 0;

header('Content-Type: application/json');
echo json_encode($response);

