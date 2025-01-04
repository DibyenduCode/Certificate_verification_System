<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation - Certificate API</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">

<header class="bg-blue-600 py-4 shadow-md">
    <div class="max-w-7xl mx-auto text-center">
      
    <img src="adminpanel.png" class="w-32 h-12 mx-auto mt-2">
        <!-- Title -->
        <h1 class="text-white text-3xl font-semibold">API Documentation</h1>
        <!-- Logo -->
        
    </div>
</header>


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

</body>
</html>
