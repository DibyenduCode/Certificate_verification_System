<?php
header('Content-Type: application/json');

require_once 'db_connect.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['certificate_number'])) {
        $certificate_number = mysqli_real_escape_string($conn, $_GET['certificate_number']);

        // Fetch all the relevant fields for the given certificate_number
        $sql = "SELECT * FROM certificates WHERE certificate_number = '$certificate_number'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $response = array(
                'status' => 'success', 
                'certificate_number' => $row['certificate_number'], 
                'student_name' => $row['student_name'], 
                'course' => $row['course'],
                'email' => $row['email'],
                'phone_number' => $row['phone_number'],
                'issue_date' => $row['issue_date'],
                'mentor_name' => $row['mentor_name']
            );
        } else {
            $response = array('status' => 'error', 'message' => 'Invalid Certificate Number');
        }
    } else {
        $response = array('status' => 'error', 'message' => 'Certificate number is required');
    }

    echo json_encode($response);
} else {
    $response = array('status' => 'error', 'message' => 'Invalid Request Method');
    echo json_encode($response);
}

mysqli_close($conn); 
?>
