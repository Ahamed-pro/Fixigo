<?php

require_once "../config/db.php";
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

date_default_timezone_set("Asia/Colombo");
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = $_POST["email"];

    $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $token = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", strtotime("+30 minutes"));
        $created = date("Y-m-d H:i:s");
        $token = bin2hex(random_bytes(32));

        $insert = $conn->prepare("
    INSERT INTO password_resets (email, token, expires_at, created_at) 
    VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 30 MINUTE), NOW())
");

        if (!$insert) {
            die("Prepare failed: " . $conn->error);
        }

        $insert->bind_param("ss", $email, $token);

        if (!$insert->execute()) {
            die("Execute failed: " . $insert->error);
        }

        $reset_link = "http://localhost/Fixigof/password/reset_password.php?token=" . $token;


        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'sendersmail';
            $mail->Password = '';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $time = date("Y-m-d H:i:s");


            $mail->setFrom('sendersmail', 'Reset Password');
            $mail->addAddress($email);

            $mail->Subject = 'Reset password! ';
            $mail->Body    = "Dear user,\n\n" .
                "please click the bellow link to reset your password!.\n\n" .
                "Reset Link:\n" .
                "Reset link:$reset_link</a>\n\n" .

                "⚠️ Please do not share it.\n" .
                "Best regards,\n" .
                "The Fixigo Team";



            $mail->send();
            echo "EMAIL SENT SUCCESSFULLY";
        } catch (Exception $e) {
            echo "EMAIL ERROR: {$mail->ErrorInfo}";
        }
    } else {
        echo "If email exists, reset link sent.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password — Fixigo</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='20' fill='%23FF5C1A'/><text y='.9em' font-size='70' x='12'>🔧</text></svg>"/>
  <link rel="stylesheet" href="forgot.css">
</head>
<body>

<canvas id="dots"></canvas>


<a href="../index.php" class="logo">
  <div class="logo-icon">🔧</div>
  <span>Fix<b>igo</b></span>
</a>
<div class="container">
  <div class="card">

    <div class="card-icon">🔑</div>
    <h2>Forgot <span>Password?</span></h2>
    <p>Enter your registered email and we'll send you a reset link.</p>

  
    <div class="alert alert-ok"  id="alert-ok">✅ Reset link sent! Check your inbox.</div>
    <div class="alert alert-err" id="alert-err"></div>

    <form method="POST">
      <label class="field-label">Email Address</label>
      <input type="email" name="email" placeholder="your@email.com" required>
      <button type="submit">Send Reset Link</button>
    </form>

    <a href="../auth.php" class="back-link">← Back to Login</a>

  </div>
</div>

<script src="password.js"></script>
</body>
</html>