<?php
session_start();

// Check if the user is logged in and has the admin role
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Include database connection
include '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO admins (username, password_hash, role) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $password_hash, $role);

    if ($stmt->execute()) {
        $_SESSION['success'] = "User added successfully.";
    } else {
        $_SESSION['error'] = "Failed to add user.";
    }

    $stmt->close();
}

$sql = "SELECT id, username, role FROM admins";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Management</title>
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
        <!-- Logo -->
        <div class="text-white text-2xl font-semibold">
        <a href="../admin/dashboard.php" class="text-white"><img src="adminpanel.png" class="w-40"></a>
        </div>
        
        <!-- Navigation Menu -->
        <div class="flex space-x-6">
        <a href="../admin/dashboard.php" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md">Add Certificate</a>
          <a href="../admin/manage_certificate.php" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md">Management Certificate</a>
          <a href="../admin/manage_user.php" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md">Staff Management</a>
          <a href="../admin/apidoc.php" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md">API Doc.</a>

          <!-- Profile Dropdown -->
          <div class="relative">
  <button id="profileButton" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md flex items-center space-x-2">
    <span>Profile</span>
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-5 w-5">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
    </svg>
  </button>

  <!-- Dropdown Menu -->
  <div id="dropdownMenu" class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg hidden">
    <div class="py-2">
      <a href="../admin/profile.php" class="block text-gray-800 hover:bg-gray-100 px-4 py-2">Edit Profile</a>
      <a href="../admin/logout.php" class="block text-gray-800 hover:bg-gray-100 px-4 py-2">Logout</a>
    </div>
  </div>
</div>

          </div>
        </div>
      </div>
    </div>
  </nav>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6 text-center primary-color">Staff Management</h1>

        <!-- Display success or error messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <p class="bg-green-100 text-green-800 p-3 rounded mb-4 text-center"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <p class="bg-red-100 text-red-800 p-3 rounded mb-4 text-center"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
        <?php endif; ?>

        <!-- User Creation Form -->
        <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4 primary-color text-center">Add New User</h2>
            <form action="" method="POST" class="space-y-4">
                <div>
                    <label for="username" class="block font-medium">Username:</label>
                    <input type="text" name="username" id="username" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 primary-border focus:ring-blue-500">
                </div>
                <div>
                    <label for="password" class="block font-medium">Password:</label>
                    <input type="password" name="password" id="password" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 primary-border focus:ring-blue-500">
                </div>
                <div>
                    <label for="role" class="block font-medium">Role:</label>
                    <select name="role" id="role" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 primary-border focus:ring-blue-500">
                        <option value="admin">Admin</option>
                        <option value="staff">Staff</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition primary-bg">Add User</button>
            </form>
        </div>

        <!-- Users Table -->
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4 primary-color text-center">All Users</h2>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-gray-300 text-center">
                    <thead>
                        <tr class="bg-gray-100">
                        
                            <th class="border border-gray-300 px-4 py-2 primary-color">Username</th>
                            <th class="border border-gray-300 px-4 py-2 primary-color">Role</th>
                            <th class="border border-gray-300 px-4 py-2 primary-color">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr class="hover:bg-gray-50">
                                    
                                    <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td class="border border-gray-300 px-4 py-2"><?php echo ucfirst($row['role']); ?></td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="inline-block bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition">Edit</a>
                                        <a href="delete_user.php?id=<?php echo $row['id']; ?>" class="inline-block bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="border border-gray-300 px-4 py-2 text-center">No users found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <footer class="bg-[#2563EB] text-white py-6 mt-10">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <div class="text-sm">
            &copy; <span id="currentYear"></span> Biswas Company. All Rights Reserved.
        </div>
    </div>
    <script>
        document.getElementById('currentYear').textContent = new Date().getFullYear();
    </script>
</footer>

<script>
        // Script to toggle the dropdown menu
        document.addEventListener('DOMContentLoaded', function () {
            const profileButton = document.getElementById('profileButton');
            const dropdownMenu = document.getElementById('dropdownMenu');

            profileButton.addEventListener('click', function () {
                dropdownMenu.classList.toggle('hidden');
            });

            // Close the dropdown when clicking outside
            document.addEventListener('click', function (event) {
                if (!profileButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                    dropdownMenu.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>
