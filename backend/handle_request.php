<?php


session_start();
include "../config/db.php";
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['account_type'] !== 'workshop') {
    echo json_encode(['success'=>false,'error'=>'Not authorised.']);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success'=>false,'error'=>'Invalid method.']);
    exit;
}

$action     = trim($_POST['action']     ?? ''); 
$request_id = (int)($_POST['request_id'] ?? 0);
$user_id    = (int)$_SESSION['user_id'];

if (!in_array($action, ['accepted','ignored'])) {
    echo json_encode(['success'=>false,'error'=>'Invalid action.']);
    exit;
}
if ($request_id < 1) {
    echo json_encode(['success'=>false,'error'=>'Invalid request ID.']);
    exit;
}


$check = $conn->prepare("
    SELECT sr.id
    FROM service_requests sr
    JOIN workshops w ON sr.workshop_id = w.id
    WHERE sr.id = ?
      AND w.user_id = ?
      AND sr.status = 'pending'
    LIMIT 1
");
$check->bind_param("ii", $request_id, $user_id);
$check->execute();
if (!$check->get_result()->fetch_assoc()) {
    echo json_encode(['success'=>false,'error'=>'Request not found or already handled.']);
    exit;
}


$upd = $conn->prepare("UPDATE service_requests SET status = ? WHERE id = ?");
$upd->bind_param("si", $action, $request_id);

if ($upd->execute()) {
    echo json_encode(['success'=>true, 'new_status'=>$action]);
} else {
    echo json_encode(['success'=>false,'error'=>'Update failed: '.$conn->error]);
}
