<?php

session_start();
include "../config/db.php";   
header('Content-Type: application/json');

$user_id = (int)($_POST['user_id'] ?? 0);
if ($user_id < 1) {
    echo json_encode(['success' => false, 'error' => 'Invalid user ID.']);
    exit;
}


$ws = $conn->prepare("DELETE FROM workshops WHERE user_id = ?");
$ws->bind_param("i", $user_id);
$ws->execute();

$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'DB error: ' . $conn->error]);
}