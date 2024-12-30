<?php
// Start the session to manage login state

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation - Certificate API</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">

<nav class="bg-blue-600 shadow-md">
    <div class="max-w-7xl mx-auto px-6">
      <div class="flex justify-between items-center py-4">
        <!-- Logo -->
        <div class="text-white text-2xl font-semibold">
        <a href="../admin/dashboard.php" class="text-white"><img src="adminpanel.png" class="w-40"></a>
        </div>
        
        <!-- Navigation Menu -->
        <div class="flex space-x-6">
          <a href="../admin/dashboard.php" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md">Certificate Management</a>
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

<div class="container mx-auto my-8 px-6 py-6 bg-white rounded-lg shadow-lg">

    <h2 class="text-2xl font-semibold text-blue-600 mb-4">Endpoint</h2>
    <div class="bg-gray-100 border-l-4 border-blue-600 p-4 mb-6">
        <p class="text-blue-600">API URL: <code class="bg-gray-200 text-blue-600 p-2 rounded">https://biswascompany.com/CERT/api.php</code></p>
    </div>

    <h2 class="text-2xl font-semibold text-blue-600 mb-4">Parameters</h2>
    <ul class="list-disc pl-6 mb-6">
        <li><strong>certificate_number</strong>: <em>Required</em> The certificate number for retrieving information.</li>
    </ul>

    <h2 class="text-2xl font-semibold text-blue-600 mb-4">Response Formats</h2>

    <h3 class="text-xl font-semibold text-blue-600 mt-6">1. Missing Certificate Number</h3>
    <pre class="bg-gray-800 text-white p-4 rounded-lg mb-6">
{
    "status": "error",
    "message": "Certificate number is required"
}
    </pre>

    <h3 class="text-xl font-semibold text-blue-600 mt-6">2. Valid Certificate Number</h3>
    <p>Example: <code class="bg-gray-200 text-blue-600 p-2 rounded">https://biswascompany.com/CERT/api.php?certificate_number=CERT12345</code></p>
    <pre class="bg-gray-800 text-white p-4 rounded-lg mb-6 mt-4">
{
    "status": "success",
    "certificate_number": "CERT12345",
    "student_name": "John Doe",
    "course": "Web Development",
    "email": "john@example.com",
    "phone_number": "1234567890",
    "issue_date": "2023-12-01",
    "mentor_name": "Jane Smith"
}
    </pre>

    <h3 class="text-xl font-semibold text-blue-600 mt-6">3. Invalid Certificate Number</h3>
    <p>Example: <code class="bg-gray-200 text-blue-600 p-2 rounded">https://biswascompany.com/CERT/api.php?certificate_number=wrongCERT12345</code></p>
    <pre class="bg-gray-800 text-white p-4 rounded-lg mb-6 mt-4">
{
    "status": "error",
    "message": "Invalid Certificate Number"
}
    </pre>

    <h2 class="text-2xl font-semibold text-blue-600 mt-6">How to Use</h2>
    <p class="mb-6">To use the API, include the <strong>certificate_number</strong> as a query parameter in the URL:</p>

    <div class="bg-gray-100 p-4 rounded-lg mb-6">
        <pre class="text-blue-600 font-mono">
<code class="bg-gray-200 text-blue-600 p-2 rounded">https://biswascompany.com/CERT/api.php?certificate_number=CERT12345</code>
        </pre>
        <p class="mt-2">This will retrieve the certificate information for the certificate number <code class="bg-gray-200 text-blue-600 p-2 rounded">CERT12345</code>.</p>
    </div>

    <h2 class="text-2xl font-semibold text-blue-600 mt-6">Error Handling</h2>
    <p class="mb-6">The API handles errors gracefully. If no certificate number is provided, it will return an error stating that the certificate number is required. If an invalid certificate number is provided, it will return an error indicating that the certificate number is invalid.</p>

</div>

<footer class="bg-blue-600 text-white text-center py-4 mt-8">
    <p>&copy; 2024 Biswas Company. All rights reserved.</p>
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
