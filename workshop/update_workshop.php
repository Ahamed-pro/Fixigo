<?php

session_start();
include "../config/db.php";
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in.']); exit;
}

$user_id        = (int)$_SESSION['user_id'];
$workshop_name  = trim($_POST['workshop_name']   ?? '');
$address        = trim($_POST['address']         ?? '');
$district       = trim($_POST['district']        ?? '');
$specialisation = trim($_POST['specialisation']  ?? '');
$business_reg   = trim($_POST['business_reg']    ?? '');

if (empty($workshop_name) || empty($address) || empty($district) || empty($specialisation)) {
    echo json_encode(['success' => false, 'error' => 'All fields except Business Reg are required.']); exit;
}

$stmt = $conn->prepare("
    UPDATE workshops
    SET workshop_name=?, address=?, district=?, specialisation=?, business_reg=?
    WHERE user_id=?
");
$stmt->bind_param("sssssi", $workshop_name, $address, $district, $specialisation, $business_reg, $user_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'DB error: ' . $conn->error]);
}
