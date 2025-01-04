
<?php
session_start();

include '../db_connect.php'; // Assuming you have this file for DB connection

// CSRF token validation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['_csrf_token']) || $_POST['_csrf_token'] !== $_SESSION['_csrf_token']) {
        die("<p>Invalid CSRF token. Please try again.</p>");
    }
}

// Retrieve form inputs
$username = $_POST['username'];
$password = $_POST['password'];

// Prepare and execute SQL query to fetch user data
$sql = "SELECT id, username, password_hash, role FROM admins WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    // Verify password
    if (password_verify($password, $row['password_hash'])) {
        // Store session variables
        $_SESSION['admin_id'] = $row['id']; 
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role']; // Store role for redirection logic

        // Redirect based on role
        if ($_SESSION['role'] === 'admin') {
            header("Location: dashboard.php"); // Redirect to admin dashboard
        } else {
            header("Location: ../user_dashboard/index.php"); // Redirect to staff dashboard
        }
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

// session_start();

// include '../db_connect.php'; 

// // CSRF token validation
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     if (!isset($_POST['_csrf_token']) || $_POST['_csrf_token'] !== $_SESSION['_csrf_token']) {
//         die("<p>Invalid CSRF token. Please try again.</p>");
//     }
// }

// $username = $_POST['username'];
// $password = $_POST['password'];

// $sql = "SELECT id, password_hash FROM admins WHERE username = ?";
// $stmt = $conn->prepare($sql);
// $stmt->bind_param("s", $username); 
// $stmt->execute();
// $result = $stmt->get_result();

// if ($result->num_rows > 0) {
//     $row = $result->fetch_assoc();
//     if (password_verify($password, $row['password_hash'])) {
//         $_SESSION['admin_id'] = $row['id']; 
//         header("Location: dashboard.php"); 
//         exit();
//     } else {
//         $_SESSION['error'] = "Incorrect password. Please try again."; // Friendly error message
//         header("Location: login.php");
//         exit();
//     }
// } else {
//     $_SESSION['error'] = "Username not found. Please try again."; // Friendly error message
//     header("Location: login.php");
//     exit();
// }

// $stmt->close();
// $conn->close();
// 
