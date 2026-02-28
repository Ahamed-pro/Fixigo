<?php

session_start();
include "../config/db.php";
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) { echo json_encode(['success'=>false]); exit; }

$my_id       = (int)$_SESSION['user_id'];
$my_type     = $_SESSION['account_type'] === 'workshop' ? 'workshop' : 'user';
$conv_id     = (int)($_GET['conversation_id'] ?? 0);
$last_id     = (int)($_GET['last_id'] ?? 0);

if (!$conv_id) { echo json_encode(['success'=>false]); exit; }


$other_type = $my_type === 'user' ? 'workshop' : 'user';
$conn->query("UPDATE chat_messages SET is_read=1 WHERE conversation_id=$conv_id AND sender_type='$other_type' AND is_read=0");
$unread_col = $my_type === 'user' ? 'user_unread' : 'ws_unread';
$conn->query("UPDATE chat_conversations SET $unread_col=0 WHERE id=$conv_id");


$stmt = $conn->prepare("
    SELECT id, sender_id, sender_type, message, is_read,
           DATE_FORMAT(created_at,'%H:%i') AS time_fmt,
           DATE_FORMAT(created_at,'%d %b %Y') AS date_fmt
    FROM chat_messages
    WHERE conversation_id=? AND id>?
    ORDER BY created_at ASC
");
$stmt->bind_param("ii", $conv_id, $last_id);
$stmt->execute();
$messages = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode(['success'=>true, 'messages'=>$messages, 'my_type'=>$my_type]);
