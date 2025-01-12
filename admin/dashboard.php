<?php
include '../db_connect.php';
include '../auth.php'; // Include authentication check

// Check if the user is logged in and has the admin role
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    // Redirect to the user dashboard if the user is not an admin
    header("Location: ../user_dashboard/index.php");
    exit();
}

// Get the username of the logged-in user for the "User Stamp"
$user_stamp = $_SESSION['username']; // Assuming the username is stored in the session

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

// Handle form submission for adding a new certificate
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Generate the certificate number (only when form is submitted)
    $current_year = date("Y");

    // Fetch the last certificate number for the current year
    $last_certificate_query = "SELECT certificate_number FROM certificates 
                               WHERE certificate_number LIKE 'CERT{$current_year}%' 
                               ORDER BY certificate_number DESC LIMIT 1";
    $last_certificate_result = mysqli_query($conn, $last_certificate_query);
    $last_certificate_row = mysqli_fetch_assoc($last_certificate_result);

    if ($last_certificate_row) {
        // Extract the serial number and increment it
        $last_serial = (int)substr($last_certificate_row['certificate_number'], 8); // "CERTYYYY" is 8 characters
        $next_serial = $last_serial + 1;
    } else {
        // Start the serial number at 1 if no certificate exists for the year
        $next_serial = 1;
    }

    // Format the certificate number
    $next_certificate_number = "CERT{$current_year}" . str_pad($next_serial, 4, "0", STR_PAD_LEFT); // Pads serial to 4 digits
} else {
    // Generate the certificate number even if the form is not submitted yet
    $current_year = date("Y");

    // Fetch the last certificate number for the current year
    $last_certificate_query = "SELECT certificate_number FROM certificates 
                               WHERE certificate_number LIKE 'CERT{$current_year}%' 
                               ORDER BY certificate_number DESC LIMIT 1";
    $last_certificate_result = mysqli_query($conn, $last_certificate_query);
    $last_certificate_row = mysqli_fetch_assoc($last_certificate_result);

    if ($last_certificate_row) {
        // Extract the serial number and increment it
        $last_serial = (int)substr($last_certificate_row['certificate_number'], 8); // "CERTYYYY" is 8 characters
        $next_serial = $last_serial + 1;
    } else {
        // Start the serial number at 1 if no certificate exists for the year
        $next_serial = 1;
    }

    // Format the certificate number
    $next_certificate_number = "CERT{$current_year}" . str_pad($next_serial, 4, "0", STR_PAD_LEFT); // Pads serial to 4 digits
}

// Get the other form fields
$certificate_number = $next_certificate_number; // Auto-generated certificate number
$student_name = $course = $email = $phone_number = $issue_date = $mentor_name = ''; // Initialize these fields

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the other form fields
    $student_name = trim($_POST['student_name']);
    $course = trim($_POST['course']);
    $email = trim($_POST['email']);
    $phone_number = trim($_POST['phone_number']);
    $issue_date = trim($_POST['issue_date']);
    $mentor_name = trim($_POST['mentor_name']);
    $user_stamp = $_SESSION['username']; // Add the logged-in user's name

    // Input validation
    $errors = [];
    if (empty($certificate_number)) $errors[] = "Certificate number is required.";
    if (empty($student_name)) $errors[] = "Student name is required.";
    if (empty($course)) $errors[] = "Course is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email address.";
    if (!preg_match('/^\d{10}$/', $phone_number)) $errors[] = "Phone number must be 10 digits.";
    if (empty($issue_date)) $errors[] = "Issue date is required.";
    if (empty($mentor_name)) $errors[] = "Mentor name is required.";

    if (empty($errors)) {
        $certificate_number = mysqli_real_escape_string($conn,$certificate_number);
        $student_name = mysqli_real_escape_string($conn, $student_name);
        $course = mysqli_real_escape_string($conn, $course);
        $email = mysqli_real_escape_string($conn, $email);
        $phone_number = mysqli_real_escape_string($conn, $phone_number);
        $issue_date = mysqli_real_escape_string($conn, $issue_date);
        $mentor_name = mysqli_real_escape_string($conn, $mentor_name);
        $user_stamp = mysqli_real_escape_string($conn, $user_stamp);

        // Insert new certificate data
        $sql = "INSERT INTO certificates (certificate_number, student_name, course, email, phone_number, issue_date, mentor_name, user_stamp) 
                VALUES ('$certificate_number', '$student_name', '$course', '$email', '$phone_number', '$issue_date', '$mentor_name', '$user_stamp')";

        if (mysqli_query($conn, $sql)) {
          header("Location: ../admin/dashboard.php");
          exit();
            // Send email
            // include 'send_email.php';
            // $email_result = sendCertificateEmail($email, $student_name, $course, $issue_date, $mentor_name);

            // if ($email_result === true) {
            //     header("Location: ../admin/dashboard.php");
            //     exit();
            // } else {
            //     $errors[] = "Certificate added, but email failed: " . $email_result;
            // }
        } else {
            $errors[] = "Error adding certificate: " . mysqli_error($conn);
        }
    }
}
?>




<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    function searchTable() {
      // Declare variables
      var input, filter, table, tr, td, i, txtValue;
      input = document.getElementById("myInput");
      filter = input.value.toLowerCase();
      table = document.getElementById("myTable");
      tr = table.getElementsByTagName("tr");

      // Loop through all table rows, and hide those who don't match the search query
      for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td");
        if (td) {
          txtValue = "";
          for (j = 0; j < td.length; j++) {
            txtValue += td[j].textContent || td[j].innerText;
          }
          if (txtValue.toLowerCase().indexOf(filter) > -1) {
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

  <!-- Main Content -->
  <div class="container mx-auto p-8 bg-white shadow-md rounded-lg">
<div class="container mx-auto p-8 bg-white shadow-md rounded-lg">
  <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Certificate Dashboard</h1>

  <?php if (!empty($errors)): ?>
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
      <?php foreach ($errors as $error): ?>
        <p><?php echo $error; ?></p>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <form action="" method="post" class="mb-8 bg-gray-100 p-6 rounded-lg shadow">
    <h2 class="text-2xl font-semibold text-gray-700 mb-4">Add Certificate</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label for="certificate_number" class="block text-sm font-medium text-gray-600">Certificate Number</label>
        <input type="text" name="certificate_number" value="<?php echo $next_certificate_number; ?>" readonly id="certificate_number" class="mt-1 p-3 w-full bg-gray-50 text-gray-600 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 cursor-not-allowed">
      </div>
      <div>
        <label for="student_name" class="block text-sm font-medium text-gray-600">Student Name</label>
        <input type="text" name="student_name" id="student_name" class="mt-1 p-3 w-full border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500">
      </div>
      <div>
        <label for="course" class="block text-sm font-medium text-gray-600">Course</label>
        <select name="course" id="course" class="mt-1 p-3 w-full border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500">
          <option value="">Select a course</option>
          <option value="Web Development(WordPress)">Web Development(WordPress)</option>
          <option value="Bolging">Bolging</option>
          <option value="Graphic Design">Graphic Design</option>
          <option value="Digital Marketing">Digital Marketing</option>
          <option value="Web Development(Coding)">Web Development(Coding)</option>
          <option value="Android App Development">Android App Development</option>
          <option value="Google Ads">Google Ads</option>
          <option value="Video Editing">Video Editing</option>
          <option value="Youtube Challange">Youtube Challange</option>
          <option value="Copy Writing">Copy Writing</option>
        </select>
      </div>
      <div>
        <label for="email" class="block text-sm font-medium text-gray-600">Email</label>
        <input type="email" name="email" id="email" class="mt-1 p-3 w-full border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500">
      </div>
      <div>
        <label for="phone_number" class="block text-sm font-medium text-gray-600">Phone Number</label>
        <input type="tel" name="phone_number" id="phone_number" class="mt-1 p-3 w-full border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500">
      </div>
      <div>
        <label for="issue_date" class="block text-sm font-medium text-gray-600">Issue Date</label>
        <input type="date" name="issue_date" id="issue_date" class="mt-1 p-3 w-full border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500">
      </div>
      <div>
        <label for="mentor_name" class="block text-sm font-medium text-gray-600">Mentor Name</label>
        <select name="mentor_name" id="mentor_name" class="mt-1 p-3 w-full border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500">
          <option value="">Select a mentor</option>
          <option value="Anirban Biswas">Anirban Biswas</option>
          <option value="Soubhik Mridha">Soubhik Mridha</option>
          <option value="Chanchal Halder">Chanchal Halder</option>
          <option value="Soubhik Mridha & Chanchal Halder">Soubhik Mridha & Chanchal Halder</option>
          <option value="SK Asif Ali">SK Asif Ali</option>
          <option value="Kuntal Ghosh">Kuntal Ghosh</option>
          <option value="Chayan Roy">Chayan Roy</option>
          <option value="Oindril Goaldar">Oindril Goaldar</option>
          <option value="Manik Paik">Manik Paik</option>
          <option value="Bivas Das">Bivas Das</option>
          <option value="Anjan Roy">Anjan Roy</option>
        </select>
      </div>
     <div>
    <label for="user_stamp" class="block text-sm font-medium text-gray-600">User Stamp</label>
    <input 
        type="text" 
        name="user_stamp" 
        id="user_stamp" 
        value="<?php echo htmlspecialchars($user_stamp); ?>" 
        class="mt-1 p-3 w-full bg-gray-50 text-gray-600 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 cursor-not-allowed" 
        readonly 
    >
</div>

      <div class="md:col-span-2 text-center">
        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-6 rounded-md">Add Certificate</button>
      </div>
    </div>
  </form>
  <h2 class="text-2xl font-semibold text-gray-700 mb-4 ml-4">View Certificate</h2>
  <div class="mb-4">
    <input type="text" id="myInput" onkeyup="searchTable()" placeholder="Search..." class="border border-gray-300 rounded-md p-2 w-full md:w-1/3 mx-auto md:mx-0">
  </div>

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
        <!-- <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase">Actions</th> -->
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

            
            <!-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"> -->
    <!-- Edit Button -->
     
    <!-- <a href="../admin/edit_certificate.php?id=<?php echo $row['id']; ?>" 
       class="inline-block bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
        Edit
    </a> -->

    <!-- Delete Button -->
    <!-- <a href="../admin/delete_certificate.php?id=<?php echo $row['id']; ?>" 
       class="inline-block bg-red-600 text-white font-semibold py-2 px-4 rounded-lg shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1 ml-4" 
       onclick="return confirm('Are you sure you want to delete this record?');">
        Delete
    </a> -->
<!-- </td> -->


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


  <div class="mt-6 flex justify-center space-x-2">
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
      <a href="?page=<?php echo $i; ?>" class="bg-blue-100 text-blue-500 px-4 py-2 rounded hover:bg-blue-200">Page <?php echo $i; ?></a>
    <?php endfor; ?>
  </div>
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
