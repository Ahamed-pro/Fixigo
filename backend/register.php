<?php


include "../config/db.php";
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

   
    $first_name   = trim($_POST["first_name"]   ?? "");
    $last_name    = trim($_POST["last_name"]    ?? "");
    $full_name    = $first_name . " " . $last_name;  
    $email        = trim($_POST["email"]        ?? "");
    $phone        = trim($_POST["phone"]        ?? "");
    $password     = trim($_POST["password"]     ?? "");
    $account_type = trim($_POST["account-type"] ?? "user");


    if (empty($first_name) || empty($last_name) || empty($email) || empty($phone) || empty($password)) {
        header("Location: ../auth.php?error=All+fields+are+required.");
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../auth.php?error=Please+enter+a+valid+email+address.");
        exit;
    }
    $phone_digits = preg_replace('/[^0-9]/', '', $phone);
    if (strlen($phone_digits) < 7 || strlen($phone_digits) > 15) {
        header("Location: ../auth.php?error=Please+enter+a+valid+phone+number.");
        exit;
    }
    if (strlen($password) < 6) {
        header("Location: ../auth.php?error=Password+must+be+at+least+6+characters.");
        exit;
    }


    $check = $conn->prepare("SELECT id FROM users WHERE email = ? OR phone = ?");
    $check->bind_param("ss", $email, $phone);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        header("Location: ../auth.php?error=Email+or+phone+number+is+already+registered.");
        exit;
    }

       if ($account_type === "workshop") {
    $amount_pay = trim($_POST["payamount"] ?? "");

    if ($amount_pay < 4999) {
        header("Location: ../auth.php?error=" . urlencode("User save failed: (Invalid Payment) " . $conn->error));
        exit;
    }
}
 
    if ($account_type === "workshop") {
        $workshop_name  = trim($_POST["workshop_name"]  ?? "");
        $business_reg   = trim($_POST["business_reg"]   ?? "");
        $district       = trim($_POST["district"]       ?? "");
        $specialisation = trim($_POST["specialisation"] ?? "");
        $address        = trim($_POST["address"]        ?? "");
        
        if (empty($workshop_name)) {
            header("Location: ../auth.php?error=Workshop+name+is+required."); exit;
        }
        if (empty($district)) {
            header("Location: ../auth.php?error=Please+select+your+district."); exit;
        }
        if (empty($specialisation)) {
            header("Location: ../auth.php?error=Please+select+your+specialisation."); exit;
        }
        if (empty($address)) {
            header("Location: ../auth.php?error=Workshop+address+is+required."); exit;
        }
    }


    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (full_name, account_type, email, phone, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $full_name, $account_type, $email, $phone, $hashed_password);

    if (!$stmt->execute()) {
        header("Location: ../auth.php?error=" . urlencode("User save failed: " . $conn->error));
        exit;
    }

    $new_user_id = $conn->insert_id;

   

    if ($account_type === "workshop") {


    $amount_pay = trim($_POST["payamount"] ?? "");
    $payment_status = ($amount_pay >= 4999) ? "paid" : "pending";
    if ($amount_pay < 4999) {
        header("Location: ../auth.php?error=" . urlencode("User save failed (Invalid Payment): " . $conn->error));
        exit;
    }
   
 
$card_number = trim($_POST['card_number'] ?? "");
$expiry = trim($_POST['expiry'] ?? "");
$amount = trim($_POST['payamount'] ?? "0");
$cardholder_name = trim($_POST['cardholder_name'] ?? "");


if (empty($card_number) || empty($expiry) || empty($cardholder_name)) {
    header("Location: ../auth.php?error=Payment+details+missing.");
    exit;
}


$pay = $conn->prepare("
    INSERT INTO card_payments 
    (user_id, card_number, expiry, cardholder_name, amount)
    VALUES (?, ?, ?, ?, ?)
");

if (!$pay) {
    header("Location: ../auth.php?error=Payment+database+error.");
    exit;
}

$pay->bind_param(
    "isssd",
    $new_user_id,   
    $card_number,
    $expiry,
    $cardholder_name,
    $amount
);



if (!$pay->execute()) {
    header("Location: ../auth.php?error=Payment+save+failed.");
    exit;
}


        $ws = $conn->prepare("
            INSERT INTO workshops (user_id, full_name,email,contact-number,workshop_name, business_reg, district, specialisation, address,payment_status)
            VALUES (?, ?,?,?, ?, ?, ?, ?, ?,?)
        ");

        if (!$ws) {
            header("Location: ../auth.php?error=" . urlencode("Workshop prepare failed: " . $conn->error));
            exit;
        }

        
        $ws->bind_param("isssssssss",
            $new_user_id,
            $full_name,
            $email,
            $phone,
            $workshop_name,
            $business_reg,
            $district,
            $specialisation,
            $address,
            $payment_status
        );

        if (!$ws->execute()) {
            header("Location: ../auth.php?error=" . urlencode("Workshop save failed: " . $ws->error));
            exit;
        }
    }


$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'sendersmail';
    $mail->Password   = 'snbpvpeyuknjgwmx';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
    $mail->Port       = 587;

    $mail->setFrom('sendersmail', 'Fixigo Registration');
    $mail->addAddress($email, $full_name); 
    $mail->Subject = 'Welcome to Fixigo!';
    $mail->Body    =
        "Hello $full_name,\n\n" .
        "Thank you for registering with Fixigo.\n" .
        "Your account type: $account_type\n" .
        "Phone: $phone\n\n" .
        "We're excited to have you onboard!\n\n" .
        "Best regards,\nFixigo Team";

    $mail->send();

} catch (Exception $e) {
    error_log("Email error: {$mail->ErrorInfo}");
}

    if ($account_type === "workshop") {
        header("Location: ../auth.php?registered=yes&type=workshop");
    } else {
        header("Location: ../auth.php?registered=yes&type=user");
    }
    exit;
}
?>