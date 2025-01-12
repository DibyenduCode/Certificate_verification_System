<?php
require('fpdf.php'); // Ensure you have FPDF installed

if (isset($_GET['id'])) {
    // Fetch certificate details from the database based on the ID
    $certificate_id = $_GET['id'];

    // Database connection
    include '../db_connect.php';
    $sql = "SELECT * FROM certificates WHERE id = $certificate_id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        die("Certificate not found.");
    }

    // Certificate details
    $certificate_number = $row['certificate_number'];
    $student_name = $row['student_name'];
    $course = $row['course'];
    $issue_date = date('F d, Y', strtotime($row['issue_date']));
    $mentor_name = $row['mentor_name'];

    // Create the PDF
    $pdf = new FPDF('L', 'mm', array(333, 235)); // Landscape, custom size matching 3334x2357 px scaled to mm
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Add the certificate image template
$pdf->Image('Web Devlopment Appreciation Certificate-1.png', 0, 0, 333, 235); // Adjust to fit the page

// Add text over the image
$pdf->SetTextColor(12, 81, 193); // Set text color to #0C51C1
$pdf->SetFont('Times', 'B', 24);

// Add the student name
$pdf->SetXY(5, 102); // Adjust the position to align with the blank area for the name
$pdf->Cell(0, 10, $student_name, 0, 1, 'C');

// Output the PDF
$pdf->Output('I', 'Certificate.pdf'); // Inline display in browser
} else {
    echo "Invalid request.";
}
?>
