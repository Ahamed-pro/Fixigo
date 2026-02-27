<?php

include "../config/db.php";
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

$first_name = trim($_POST['first_name'] ?? '');
$last_name  = trim($_POST['last_name']  ?? '');
$email      = trim($_POST['email']      ?? '');
$phone      = trim($_POST['phone']      ?? '');
$topic      = trim($_POST['topic']      ?? '');
$message    = trim($_POST['message']    ?? '');


if (!$first_name || !$email || !$topic || !$message) {
    echo json_encode(['success' => false, 'error' => 'Please fill in all required fields.']);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'error' => 'Please enter a valid email address.']);
    exit;
}

$stmt = $conn->prepare("
    INSERT INTO contact_messages (first_name, last_name, email, phone, topic, message)
    VALUES (?, ?, ?, ?, ?, ?)
");
$stmt->bind_param("ssssss", $first_name, $last_name, $email, $phone, $topic, $message);

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'your_email@gmail.com';
    $mail->Password = 'your_app_password'; 
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

$fullname = $first_name." ".$last_name;

$time = date("Y-m-d H:i:s");


    $mail->setFrom('your_email@gmail.com', 'Fixigo');
    $mail->addAddress($email,$fullname);

   $mail->Subject = 'Thank you for Reaching us!';
   $mail->Body    = "Dear $first_name,\n\n".
                    " Thank you for reaching out to us through our website.\n".
                    "We have received your message and our team will review it shortly.\n". 
                    "We aim to respond within 24–48 hours, depending on the nature of your inquiry.\n\n".
                    "If your question is about registration or signing in, \n". 
                    "please visit the Help Center on our site for step-by-step guidance. \n". 
                    "Otherwise, rest assured that we will get back to you with the support you need.\n\n".
                    "Best regards,\n". 
                    "The Fixigo Team";


    $mail->send();
    echo "EMAIL SENT SUCCESSFULLY";
} catch (Exception $e) {
    echo "EMAIL ERROR: {$mail->ErrorInfo}";
}
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Could not save message. Please try again.']);
}
