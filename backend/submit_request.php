<?php


session_start();
include "../config/db.php";
header('Content-Type: application/json');


if (!isset($_SESSION['user_id']) || $_SESSION['account_type'] !== 'user') {
    echo json_encode(['success' => false, 'error' => 'Not authorised.']);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid method.']);
    exit;
}

$user_id      = (int)    $_SESSION['user_id'];
$user_name    =          $_SESSION['user_name'];
$user_phone   = trim($_POST['phone']        ?? '');
$service_type = trim($_POST['service_type'] ?? '');
$workshop_id  = (int)   ($_POST['workshop_id']  ?? 0);
$location     = trim($_POST['location']     ?? '');
$description  = trim($_POST['description']  ?? '');


if (!$user_phone)   { echo json_encode(['success'=>false,'error'=>'Phone is required.']);        exit; }
if (!$service_type) { echo json_encode(['success'=>false,'error'=>'Service type is required.']); exit; }
if ($workshop_id<1) { echo json_encode(['success'=>false,'error'=>'Please select a workshop.']); exit; }


$ws = $conn->prepare("
    SELECT w.id, w.workshop_name
    FROM workshops w JOIN users u ON w.user_id = u.id
    WHERE w.id = ? AND w.payment_status = 'paid' AND u.account_type = 'workshop'
    LIMIT 1
");
$ws->bind_param("i", $workshop_id);
$ws->execute();
$wrow = $ws->get_result()->fetch_assoc();
if (!$wrow) {
    echo json_encode(['success'=>false,'error'=>'Workshop not found or inactive.']);
    exit;
}
$workshop_name = $wrow['workshop_name'];


$stmt = $conn->prepare("
    INSERT INTO service_requests
        (user_id, user_name, user_phone, workshop_id, workshop_name, service_type, location, description)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");
$stmt->bind_param("isisssss",
    $user_id, $user_name, $user_phone,
    $workshop_id, $workshop_name,
    $service_type, $location, $description
);

if ($stmt->execute()) {
    echo json_encode([
        'success'       => true,
        'request_id'    => $conn->insert_id,
        'workshop_name' => $workshop_name,
        'service_type'  => $service_type,
        'location'      => $location,
        'created_at'    => date('d M Y'),
    ]);
} else {
    echo json_encode(['success'=>false,'error'=>'DB error: '.$conn->error]);
}
