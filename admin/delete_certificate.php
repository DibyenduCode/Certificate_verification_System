<?php
include '../db_connect.php'; 
include '../auth.php'; 

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    // Redirect to the user dashboard if the user is not an admin
    header("Location: ../user_dashboard/index.php");
    exit();
}

// Validate and sanitize the certificate ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ../admin/dashboard.php");
    exit();
}

$certificate_id = intval($_GET['id']);

// Check if the record exists
$stmt = $conn->prepare("SELECT id FROM certificates WHERE id = ?");
$stmt->bind_param("i", $certificate_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    $stmt->close();
    header("Location: ../admin/dashboard.php?message=" . urlencode("Certificate not found."));
    exit();
}

$stmt->close();

// Proceed with deletion
$stmt = $conn->prepare("DELETE FROM certificates WHERE id = ?");
$stmt->bind_param("i", $certificate_id);

if ($stmt->execute()) {
    session_start();
    $_SESSION['message'] = "Certificate deleted successfully!";
    header("Location: ../admin/dashboard.php");
    exit();
} else {
    error_log("Error deleting record: " . $stmt->error, 3, "../logs/errors.log");
    echo "An error occurred. Please try again later.";
}

$stmt->close();
mysqli_close($conn);
?>
