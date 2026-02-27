<?php

session_start();
include "../config/db.php";   
header('Content-Type: application/json');

$workshop_id = (int)($_POST['workshop_id'] ?? 0);
if ($workshop_id < 1) {
    echo json_encode(['success' => false, 'error' => 'Invalid workshop ID.']);
    exit;
}

$stmt = $conn->prepare("DELETE FROM workshops WHERE id = ?");
$stmt->bind_param("i", $workshop_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'DB error: ' . $conn->error]);
}