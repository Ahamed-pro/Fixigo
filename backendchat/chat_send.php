<?php

session_start();
include "../config/db.php";
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) { echo json_encode(['success'=>false,'error'=>'Not logged in']); exit; }

$sender_id   = (int)$_SESSION['user_id'];
$sender_type = $_SESSION['account_type'] === 'workshop' ? 'workshop' : 'user';
$conv_id     = (int)($_POST['conversation_id'] ?? 0);
$message     = trim($_POST['message'] ?? '');

if (!$conv_id || !$message) { echo json_encode(['success'=>false,'error'=>'Missing data']); exit; }


$conv = $conn->prepare("SELECT * FROM chat_conversations WHERE id=?");
$conv->bind_param("i", $conv_id);
$conv->execute();
$c = $conv->get_result()->fetch_assoc();
if (!$c) { echo json_encode(['success'=>false,'error'=>'Conversation not found']); exit; }

if ($sender_type === 'user' && $c['user_id'] !== $sender_id) {
    echo json_encode(['success'=>false,'error'=>'Unauthorised']); exit;
}
if ($sender_type === 'workshop') {
    $ws = $conn->prepare("SELECT id FROM workshops WHERE user_id=? LIMIT 1");
    $ws->bind_param("i", $sender_id);
    $ws->execute();
    $wsrow = $ws->get_result()->fetch_assoc();
    if (!$wsrow || (int)$wsrow['id'] !== (int)$c['workshop_id']) {
        echo json_encode(['success'=>false,'error'=>'Unauthorised']); exit;
    }
}


$ins = $conn->prepare("INSERT INTO chat_messages (conversation_id, sender_id, sender_type, message) VALUES (?,?,?,?)");
$ins->bind_param("iiss", $conv_id, $sender_id, $sender_type, $message);
$ins->execute();
$msg_id = $conn->insert_id;


$unread_col = $sender_type === 'user' ? 'ws_unread' : 'user_unread';
$conn->query("UPDATE chat_conversations SET last_message='".addslashes($message)."', last_at=NOW(), $unread_col=$unread_col+1 WHERE id=$conv_id");

echo json_encode([
    'success'    => true,
    'message_id' => $msg_id,
    'time'       => date('H:i')
]);
