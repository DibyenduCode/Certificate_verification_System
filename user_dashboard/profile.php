<?php
include '../db_connect.php'; 
include '../auth.php'; 

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin/login.php"); // Redirect to login if not logged in
    exit();
}

$admin_id = $_SESSION['admin_id'];

// Fetch current username
$current_username = '';
$result = mysqli_query($conn, "SELECT username FROM admins WHERE id = $admin_id");
if ($row = mysqli_fetch_assoc($result)) {
    $current_username = $row['username'];
}

$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = trim($_POST['new_username']); 
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validation
    $errors = [];
    if (!empty($new_username) && strlen($new_username) < 3) {
        $errors[] = "Username must be at least 3 characters.";
    }
    if (!empty($new_password) && strlen($new_password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }
    if ($new_password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // Process updates if no errors
    if (empty($errors)) {
        $updates = [];
        if (!empty($new_username)) {
            // Check if username already exists
            $stmt = $conn->prepare("SELECT id FROM admins WHERE username = ? AND id != ?");
            $stmt->bind_param("si", $new_username, $admin_id);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $errors[] = "Username already exists.";
            } else {
                $updates[] = "username = '$new_username'";
            }
        }
        if (!empty($new_password)) {
            $updates[] = "password_hash = '" . password_hash($new_password, PASSWORD_DEFAULT) . "'";
        }

        if (!empty($updates)) {
            $update_sql = "UPDATE admins SET " . implode(', ', $updates) . " WHERE id = $admin_id";
            if (mysqli_query($conn, $update_sql)) {
                $success_message = "Profile updated successfully!";
                // Update session data
                if (!empty($new_username)) {
                    $_SESSION['username'] = $new_username;
                }
            } else {
                $errors[] = "Error updating profile: " . mysqli_error($conn);
            }
        } else {
            $errors[] = "No changes made.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    <!-- Navigation Bar -->
    <nav class="bg-blue-600 shadow-md">
    <div class="max-w-7xl mx-auto px-6">
      <div class="flex justify-between items-center py-4">
        <!-- Logo -->
        <div class="text-white text-2xl font-semibold">
        <a href="../admin/dashboard.php" class="text-white"><img src="adminpanel.png" class="w-40"></a>
        </div>
        
        <!-- Navigation Menu -->
        <div class="flex space-x-6">
        <a href="../user_dashboard/index.php" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md">Add Certificate</a>
          <a href="../user_dashboard/manage_certificate.php" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md">Management Certificate</a>
     
        
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
      <a href="../user_dashboard/profile.php" class="block text-gray-800 hover:bg-gray-100 px-4 py-2">Edit Profile</a>
      <a href="../admin/logout.php" class="block text-gray-800 hover:bg-gray-100 px-4 py-2">Logout</a>
    </div>
  </div>
</div>

          </div>
        </div>
      </div>
    </div>
  </nav>

    <!-- Success Message Section -->
    <?php if (!empty($success_message)): ?>
    <div class="fixed inset-x-0 top-16 mx-auto max-w-4xl">
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mt-6 rounded shadow-md">
            <p class="font-semibold text-center"><?php echo htmlspecialchars($success_message); ?></p>
        </div>
    </div>
    <?php endif; ?>

    <!-- Profile Update Section -->
    <div class="container mx-auto px-6 sm:px-8 py-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4 border-b pb-4">Update Profile</h1>

        <!-- Current Profile Section -->
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-700">Current Profile Information</h2>
            <div class="mt-2 text-sm text-gray-600">
                <p><strong>Username:</strong> <?php echo htmlspecialchars($current_username); ?></p>
                <p class="mt-1 text-gray-500">You can update your username or password below.</p>
            </div>
        </div>

        <!-- Error and Success Messages -->
        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc pl-5">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Update Form -->
        <form action="" method="post">
    <div class="mb-6 space-y-6">
        <div>
            <label for="new_username" class="block text-sm font-medium text-gray-700">New Username</label>
            <input type="text" id="new_username" name="new_username" value="<?php echo htmlspecialchars($current_username); ?>" class="mt-1 block w-full p-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600 cursor-not-allowed" readonly>
        </div>

        <div>
            <label for="new_password" class="block text-sm font-medium text-gray-700">New Password</label>
            <input type="password" id="new_password" name="new_password" class="mt-1 block w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-gray-900">
        </div>

        <div>
            <label for="confirm_password" class="block text-sm font-medium text-gray-700 mt-4">Confirm New Password</label>
            <input type="password" id="confirm_password" name="confirm_password" class="mt-1 block w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-gray-900">
        </div>
    </div>

    <div class="flex justify-end mt-6">
        <button type="submit" class="bg-blue-600 text-white font-semibold py-2 px-6 rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
            Save Changes
        </button>
    </div>
</form>


    </div>

    <!-- Footer -->
    <footer class="bg-[#2563EB] text-white py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <div class="text-sm">
                &copy; <span id="currentYear"></span> Biswas Company. All Rights Reserved.
            </div>
        </div>
    </footer>

    <script>
        document.getElementById('currentYear').textContent = new Date().getFullYear();
    </script>

</body>
</html>
