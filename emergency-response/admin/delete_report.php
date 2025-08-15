<?php
require_once '../db/config.php';
require_once '../includes/functions.inc.php';
session_start();

if (!isAdmin()) {
    redirect('../pages/admin-login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['report_id'])) {
    $report_id = intval($_POST['report_id']);

    // Fetch report to delete image (if any)
    $stmt = $conn->prepare("SELECT image FROM emergency_reports WHERE id = ?");
    $stmt->bind_param("i", $report_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $report = $result->fetch_assoc();

    // Delete image file if it exists
    if (!empty($report['image']) && file_exists("../" . $report['image'])) {
        unlink("../" . $report['image']);
    }

    // Delete report from database
    $stmt = $conn->prepare("DELETE FROM emergency_reports WHERE id = ?");
    $stmt->bind_param("i", $report_id);
    $stmt->execute();

  
}

header("Location: recent_reports.php?deleted=1");
exit;

?>
