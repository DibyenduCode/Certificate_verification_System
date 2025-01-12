<?php
session_start();

// Check if the user is logged in and has the admin role
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Include database connection
include '../db_connect.php';

// Fetch user details for editing
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT username, role FROM admins WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
    } else {
        $_SESSION['error'] = "User not found.";
        header("Location: manage_user.php");
        exit();
    }
    $stmt->close();
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: manage_user.php");
    exit();
}

// Handle form submission for updating user details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $role = $_POST['role'];
    $password = $_POST['password_hash'];

    if (!empty($password)) {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $sql = "UPDATE admins SET username = ?, role = ?, password = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $username, $role, $hashedPassword, $id);
    } else {
        $sql = "UPDATE admins SET username = ?, role = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $username, $role, $id);
    }

    if ($stmt->execute()) {
        $_SESSION['success'] = "User updated successfully.";
        header("Location: manage_user.php");
        exit();
    } else {
        $_SESSION['error'] = "Failed to update user.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .primary-color {
            color: #1F75FE;
        }
        .primary-bg {
            background-color: #1F75FE;
        }
        .primary-border {
            border-color: #1F75FE;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
<nav class="bg-blue-600 shadow-md">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between items-center py-4">
            <div class="text-white text-2xl font-semibold">
                <a href="../admin/dashboard.php" class="text-white">
                    <img src="adminpanel.png" class="w-40" alt="Admin Panel Logo">
                </a>
            </div>
            <div class="flex space-x-6">
                <a href="../admin/dashboard.php" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md">Dashboard</a>
                <a href="../admin/manage_certificate.php" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md">Manage Certificates</a>
                <a href="../admin/manage_user.php" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md">Manage Users</a>
                <a href="../admin/apidoc.php" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md">API Documentation</a>
            </div>
        </div>
    </div>
</nav>

<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-6 text-center primary-color">Edit User</h1>

    <?php if (isset($_SESSION['success'])): ?>
        <p class="bg-green-100 text-green-800 p-3 rounded mb-4 text-center"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <p class="bg-red-100 text-red-800 p-3 rounded mb-4 text-center"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>

    <div class="bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4 primary-color text-center">Update User Information</h2>
        <form action="" method="POST" class="space-y-4">
            <div>
                <label for="username" class="block font-medium">Username:</label>
                <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 primary-border focus:ring-blue-500">
            </div>
            <div>
                <label for="role" class="block font-medium">Role:</label>
                <select name="role" id="role" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 primary-border focus:ring-blue-500">
                    <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                    <option value="staff" <?php echo $user['role'] === 'staff' ? 'selected' : ''; ?>>Staff</option>
                </select>
            </div>
            <div>
                <label for="password" class="block font-medium">New Password (optional):</label>
                <input type="password" name="password" id="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 primary-border focus:ring-blue-500">
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition primary-bg">Update User</button>
        </form>
    </div>
</div>

<footer class="bg-blue-600 text-white py-6 mt-10">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <div class="text-sm">
            &copy; <span id="currentYear"></span> Biswas Company. All Rights Reserved.
        </div>
    </div>
    <script>
        document.getElementById('currentYear').textContent = new Date().getFullYear();
    </script>
</footer>
</body>
</html>
