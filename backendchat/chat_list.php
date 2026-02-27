<?php

session_start();
include "../config/db.php";
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) { echo json_encode(['success'=>false]); exit; }

$my_id   = (int)$_SESSION['user_id'];
$my_type = $_SESSION['account_type'] === 'workshop' ? 'workshop' : 'user';

if ($my_type === 'workshop') {
    $ws = $conn->prepare("SELECT id FROM workshops WHERE user_id=? LIMIT 1");
    $ws->bind_param("i", $my_id);
    $ws->execute();
    $wsrow = $ws->get_result()->fetch_assoc();
    if (!$wsrow) { echo json_encode(['success'=>true,'conversations',[]]); exit; }
    $ws_id = (int)$wsrow['id'];

    $stmt = $conn->prepare("
        SELECT cc.id, cc.ws_unread AS unread, cc.last_message,
               DATE_FORMAT(cc.last_at,'%d %b %Y · %H:%i') AS last_at,
               u.full_name AS other_name
        FROM chat_conversations cc
        JOIN users u ON u.id = cc.user_id
        WHERE cc.workshop_id=?
        ORDER BY cc.last_at DESC
    ");
    $stmt->bind_param("i", $ws_id);
} else {
    $stmt = $conn->prepare("
        SELECT cc.id, cc.user_unread AS unread, cc.last_message,
               DATE_FORMAT(cc.last_at,'%d %b %Y · %H:%i') AS last_at,
               w.workshop_name AS other_name
        FROM chat_conversations cc
        JOIN workshops w ON w.id = cc.workshop_id
        WHERE cc.user_id=?
        ORDER BY cc.last_at DESC
    ");
    $stmt->bind_param("i", $my_id);
}

$stmt->execute();
$convs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode(['success'=>true, 'conversations'=>$convs, 'my_type'=>$my_type]);
