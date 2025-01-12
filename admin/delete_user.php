<?php
session_start();

// Check if the user is logged in and has the admin role
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Include database connection
include '../db_connect.php';

// Handle user deletion
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Check if the admin is trying to delete themselves
    if ($_SESSION['admin_id'] == $id) {
        $_SESSION['error'] = "You cannot delete your own account.";
        header("Location: manage_user.php");
        exit();
    }

    // Prepare and execute the delete query
    $sql = "DELETE FROM admins WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "User deleted successfully.";
    } else {
        $_SESSION['error'] = "Failed to delete user.";
    }

    $stmt->close();
} else {
    $_SESSION['error'] = "Invalid request.";
}

header("Location: manage_user.php");
exit();
?>
