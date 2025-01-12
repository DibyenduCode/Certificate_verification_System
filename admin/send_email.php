<?php
// Include PHPMailer files
require '../phpmailer/PHPMailer.php';
require '../phpmailer/SMTP.php';
require '../phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendCertificateEmail($recipient_email, $student_name, $course, $issue_date, $mentor_name) {
    $mail = new PHPMailer(true); // Create PHPMailer instance

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
        $mail->SMTPAuth = true;
        $mail->Username = 'your-email@gmail.com'; // Your email address
        $mail->Password = 'your-email-password'; // Your email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587; // SMTP port for TLS

        //Recipients
        $mail->setFrom('your-email@gmail.com', 'Your Name or Company');
        $mail->addAddress($recipient_email, $student_name);

        //Content
        $mail->isHTML(true);
        $mail->Subject = 'Your Certificate for ' . $course;
        $mail->Body = "
            <p>Dear $student_name,</p>
            <p>Congratulations! You have successfully completed the $course course.</p>
            <p>Certificate Number: $certificate_number</p>
            <p>Issue Date: $issue_date</p>
            <p>Mentor: $mentor_name</p>
            <p>Thank you for your hard work!</p>
            <p>Best regards,<br>Your Company</p>
        ";

        // Send the email
        $mail->send();
        return true;
    } catch (Exception $e) {
        return 'Email could not be sent. Mailer Error: ' . $mail->ErrorInfo;
    }
}

?>
