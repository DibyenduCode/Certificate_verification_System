<?php
session_start();

include '../db_connect.php'; 

// CSRF token validation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['_csrf_token']) || $_POST['_csrf_token'] !== $_SESSION['_csrf_token']) {
        die("<p>Invalid CSRF token. Please try again.</p>");
    }
}

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT id, password_hash FROM admins WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username); 
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password_hash'])) {
        $_SESSION['admin_id'] = $row['id']; 
        header("Location: dashboard.php"); 
        exit();
    } else {
        $_SESSION['error'] = "Incorrect password. Please try again."; // Friendly error message
        header("Location: login.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Username not found. Please try again."; // Friendly error message
    header("Location: login.php");
    exit();
}

$stmt->close();
$conn->close();
?>
