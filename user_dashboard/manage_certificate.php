<?php
include '../db_connect.php';
include '../auth.php'; // Include authentication check


if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin/login.php"); // Redirect to login if not logged in
    exit();
}

// Handle record deletion
if (isset($_GET['delete'])) {
    $certificate_id = $_GET['delete'];
    $delete_sql = "DELETE FROM certificates WHERE id = $certificate_id";
    if (mysqli_query($conn, $delete_sql)) {
        header("Location: certificates.php");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}

// Pagination setup
$limit = 20; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch certificates with pagination
$sql = "SELECT * FROM certificates LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);

// Count total records for pagination
$total_sql = "SELECT COUNT(*) AS total FROM certificates";
$total_result = mysqli_query($conn, $total_sql);
$total_row = mysqli_fetch_assoc($total_result);
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function searchTable() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("myInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("myTable");
            tr = table.getElementsByTagName("tr");
            for (i = 1; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td");
                if (td) {
                    var match = false;
                    for (var j = 0; j < td.length; j++) {
                        if (td[j].textContent.toUpperCase().indexOf(filter) > -1) {
                            match = true;
                        }
                    }
                    if (match) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }       
            }
        }
    </script>
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
        <a href="../user_dashboard/index.php.php" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md">Add Certificate</a>
          <a href="../user_dashboard//manage_certificate.php" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md">Management Certificate</a>
     
        
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
      <a href="../user_dashboard//profile.php" class="block text-gray-800 hover:bg-gray-100 px-4 py-2">Edit Profile</a>
      <a href="../admin/logout.php" class="block text-gray-800 hover:bg-gray-100 px-4 py-2">Logout</a>
    </div>
  </div>
</div>

          </div>
        </div>
      </div>
    </div>
  </nav>

<!-- Main Content -->
<div class="flex-1 p-6 md:p-8 bg-white shadow-md rounded-lg">

    <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Certificate Dashboard</h1>

    <h2 class="text-2xl font-semibold text-gray-700 mb-4">Manage Certificate</h2>

    <!-- Search Bar -->
    <div class="mb-4">
    <input type="text" id="myInput" onkeyup="searchTable()" placeholder="Search..." class="border border-gray-300 rounded-md p-2 w-full md:w-1/3 mx-auto md:mx-0">
</div>

<!-- Table -->
<div class="overflow-x-auto">
    <table class="min-w-full table-auto bg-gray-100 shadow rounded-lg overflow-hidden" id="myTable">
        <thead class="bg-blue-500">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase">Certificate Number</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase">Student Name</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase">Course</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase">Email</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase">Phone Number</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase">Issue Date</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase">Mentor Name</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <tr class="hover:bg-gray-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo $row['certificate_number']; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo $row['student_name']; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo $row['course']; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo $row['email']; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo $row['phone_number']; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo $row['issue_date']; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo $row['mentor_name']; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <!-- Edit Button -->
                            <a href="../user_dashboard/edit_certificate.php?id=<?php echo $row['id']; ?>" 
                               class="inline-block bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                                Edit
                            </a>
                            <!-- Delete Button -->
                            <a href="/user_dashboard/delete_certificate.php?id=<?php echo $row['id']; ?>" 
                               class="inline-block bg-red-600 text-white font-semibold py-2 px-4 rounded-lg shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1 ml-4" 
                               onclick="return confirm('Are you sure you want to delete this record?');">
                                Delete
                            </a>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo '<tr><td colspan="8" class="text-center px-6 py-4 text-gray-600">No records found.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-6 flex justify-center space-x-2">
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?page=<?php echo $i; ?>" class="bg-blue-100 text-blue-500 px-4 py-2 rounded hover:bg-blue-200">
            Page <?php echo $i; ?>
        </a>
    <?php endfor; ?>
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
