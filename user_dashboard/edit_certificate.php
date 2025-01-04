<?php
include '../db_connect.php'; 
include '../auth.php'; 

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin/login.php"); // Redirect to login if not logged in
    exit();
}

$certificate_id = $_GET['id'];

// Retrieve certificate data
$sql = "SELECT * FROM certificates WHERE id = $certificate_id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $certificate_number = mysqli_real_escape_string($conn, $_POST['certificate_number']);
        $student_name = mysqli_real_escape_string($conn, $_POST['student_name']);
        $course = mysqli_real_escape_string($conn, $_POST['course']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
        $mentor_name = mysqli_real_escape_string($conn, $_POST['mentor_name']); // New field
        $issue_date = mysqli_real_escape_string($conn, $_POST['issue_date']); // New field

        // Update certificate data
        $sql = "UPDATE certificates SET 
                certificate_number = '$certificate_number', 
                student_name = '$student_name', 
                course = '$course', 
                email = '$email', 
                phone_number = '$phone_number', 
                mentor_name = '$mentor_name', -- Update mentor name
                issue_date = '$issue_date' -- Update issue date
                WHERE id = $certificate_id";

        if (mysqli_query($conn, $sql)) {
            header("Location: ../user_dashboard/manage_certificate.php"); // Redirect to dashboard after successful update
            exit();
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    }

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Certificate</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-gray-100 flex flex-col min-h-screen">

    <!-- Navbar -->
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

    <!-- Edit Certificate Form -->
    <div class="container mx-auto p-4 sm:p-8 mt-10 flex-grow">
        <h1 class="text-3xl font-semibold text-center text-gray-800 mb-6 sm:mb-8">Edit Certificate</h1>
        <form method="post" action="" class="bg-white p-6 sm:p-8 rounded-lg shadow-lg space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="certificate_number" class="block text-sm font-medium text-gray-700">Certificate Number</label>
                    <input type="text" name="certificate_number" id="certificate_number" class="mt-1 p-3 w-full border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" value="<?php echo $row['certificate_number']; ?>">
                </div>
                <div>
                    <label for="student_name" class="block text-sm font-medium text-gray-700">Student Name</label>
                    <input type="text" name="student_name" id="student_name" class="mt-1 p-3 w-full border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" value="<?php echo $row['student_name']; ?>">
                </div>
                <div>
                    <label for="course" class="block text-sm font-medium text-gray-600">Course</label>
                    <select name="course" id="course" class="mt-1 p-3 w-full border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500">
                        <option value="">Select a course</option>
                        <option value="Web Development(WordPress)" <?php echo ($row['course'] == 'Web Development(WordPress)') ? 'selected' : ''; ?>>Web Development(WordPress)</option>
                        <option value="Blogging" <?php echo ($row['course'] == 'Blogging') ? 'selected' : ''; ?>>Blogging</option>
                        <option value="Graphic Design" <?php echo ($row['course'] == 'Graphic Design') ? 'selected' : ''; ?>>Graphic Design</option>
                        <option value="Digital Marketing" <?php echo ($row['course'] == 'Digital Marketing') ? 'selected' : ''; ?>>Digital Marketing</option>
                        <option value="Web Development(Coding)" <?php echo ($row['course'] == 'Web Development(Coding)') ? 'selected' : ''; ?>>Web Development(Coding)</option>
                        <option value="Android App Development" <?php echo ($row['course'] == 'Android App Development') ? 'selected' : ''; ?>>Android App Development</option>
                        <option value="Google Ads" <?php echo ($row['course'] == 'Google Ads') ? 'selected' : ''; ?>>Google Ads</option>
                        <option value="Video Editing" <?php echo ($row['course'] == 'Video Editing') ? 'selected' : ''; ?>>Video Editing</option>
                        <option value="Youtube Challange" <?php echo ($row['course'] == 'Youtube Challange') ? 'selected' : ''; ?>>Youtube Challange</option>
                        <option value="Copy Writing" <?php echo ($row['course'] == 'Copy Writing') ? 'selected' : ''; ?>>Copy Writing</option>
                    </select>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" class="mt-1 p-3 w-full border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" value="<?php echo $row['email']; ?>">
                </div>
                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <input type="tel" name="phone_number" id="phone_number" class="mt-1 p-3 w-full border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" value="<?php echo $row['phone_number']; ?>">
                </div>
                <div>
                    <label for="mentor_name" class="block text-sm font-medium text-gray-600">Mentor Name</label>
                    <select name="mentor_name" id="mentor_name" class="mt-1 p-3 w-full border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500">
                        <option value="">Select a mentor</option>
                        <option value="Anirban Biswas" <?php echo ($row['mentor_name'] == 'Anirban Biswas') ? 'selected' : ''; ?>>Anirban Biswas</option>
                        <option value="Soubhik Mridha" <?php echo ($row['mentor_name'] == 'Soubhik Mridha') ? 'selected' : ''; ?>>Soubhik Mridha</option>
                        <option value="Chanchal Halder" <?php echo ($row['mentor_name'] == 'Chanchal Halder') ? 'selected' : ''; ?>>Chanchal Halder</option>
                        <option value="Soubhik Mridha & Chanchal Halder" <?php echo ($row['mentor_name'] == 'Soubhik Mridha & Chanchal Halder') ? 'selected' : ''; ?>>Soubhik Mridha & Chanchal Halder</option>
                        <option value="SK Asif Ali" <?php echo ($row['mentor_name'] == 'SK Asif Ali') ? 'selected' : ''; ?>>SK Asif Ali</option>
                        <option value="Kuntal Ghosh" <?php echo ($row['mentor_name'] == 'Kuntal Ghosh') ? 'selected' : ''; ?>>Kuntal Ghosh</option>
                        <option value="Chayan Roy" <?php echo ($row['mentor_name'] == 'Chayan Roy') ? 'selected' : ''; ?>>Chayan Roy</option>
                        <option value="Oindril Goaldar" <?php echo ($row['mentor_name'] == 'Oindril Goaldar') ? 'selected' : ''; ?>>Oindril Goaldar</option>
                        <option value="Manik Paik" <?php echo ($row['mentor_name'] == 'Manik Paik') ? 'selected' : ''; ?>>Manik Paik</option>
                        <option value="Bivas Das" <?php echo ($row['mentor_name'] == 'Bivas Das') ? 'selected' : ''; ?>>Bivas Das</option>
                        <option value="Anjan Roy" <?php echo ($row['mentor_name'] == 'Anjan Roy') ? 'selected' : ''; ?>>Anjan Roy</option>
                    </select>
                </div>
                <div>
                    <label for="issue_date" class="block text-sm font-medium text-gray-700">Issue Date</label>
                    <input type="date" name="issue_date" id="issue_date" class="mt-1 p-3 w-full border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" value="<?php echo $row['issue_date']; ?>">
                </div>
            </div>
            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 rounded-md focus:ring-2 focus:ring-blue-500">
                Update Certificate
            </button>
        </form>
    </div>

    <!-- Footer -->
    <footer class="bg-[#2563EB] text-white py-6 mt-auto">
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
    <?php
} else {
    echo "Certificate not found.";
}

mysqli_close($conn);
?>
