<?php

session_start();
include "../config/db.php";
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) { echo json_encode(['success'=>false,'error'=>'Not logged in']); exit; }

$my_id   = (int)$_SESSION['user_id'];
$my_type = $_SESSION['account_type'] === 'workshop' ? 'workshop' : 'user';


if ($my_type === 'user') {

    $workshop_id = (int)($_POST['workshop_id']     ?? 0);
    $conv_id_req = (int)($_POST['conversation_id'] ?? 0);

 
    if ($workshop_id) {
        $ws = $conn->prepare("SELECT id, workshop_name FROM workshops WHERE id=? LIMIT 1");
        $ws->bind_param("i", $workshop_id);
        $ws->execute();
        $workshop = $ws->get_result()->fetch_assoc();
        if (!$workshop) { echo json_encode(['success'=>false,'error'=>'Workshop not found']); exit; }

        $find = $conn->prepare("SELECT id FROM chat_conversations WHERE user_id=? AND workshop_id=? LIMIT 1");
        $find->bind_param("ii", $my_id, $workshop_id);
        $find->execute();
        $existing = $find->get_result()->fetch_assoc();
        if ($existing) {
            $conv_id = (int)$existing['id'];
        } else {
            $ins = $conn->prepare("INSERT INTO chat_conversations (user_id, workshop_id) VALUES (?,?)");
            $ins->bind_param("ii", $my_id, $workshop_id);
            $ins->execute();
            $conv_id = $conn->insert_id;
        }
        $other_name = $workshop['workshop_name'];

 
    } elseif ($conv_id_req) {
        $find = $conn->prepare("
            SELECT cc.id, w.workshop_name
            FROM chat_conversations cc
            JOIN workshops w ON w.id = cc.workshop_id
            WHERE cc.id=? AND cc.user_id=? LIMIT 1
        ");
        $find->bind_param("ii", $conv_id_req, $my_id);
        $find->execute();
        $row = $find->get_result()->fetch_assoc();
        if (!$row) { echo json_encode(['success'=>false,'error'=>'Conversation not found']); exit; }
        $conv_id    = (int)$row['id'];
        $other_name = $row['workshop_name'];

    } else {
        echo json_encode(['success'=>false,'error'=>'Missing workshop_id or conversation_id']); exit;
    }


} else {
    $conv_id = (int)($_POST['conversation_id'] ?? 0);
    if (!$conv_id) { echo json_encode(['success'=>false,'error'=>'Missing conversation_id']); exit; }


    $cu = $conn->prepare("SELECT cc.id, u.full_name FROM chat_conversations cc JOIN users u ON u.id=cc.user_id WHERE cc.id=? LIMIT 1");
    $cu->bind_param("i", $conv_id);
    $cu->execute();
    $crow = $cu->get_result()->fetch_assoc();
    if (!$crow) { echo json_encode(['success'=>false,'error'=>'Conversation not found']); exit; }
    $other_name = $crow['full_name'];
}


$other_type = $my_type === 'user' ? 'workshop' : 'user';
$conn->query("UPDATE chat_messages SET is_read=1 WHERE conversation_id=$conv_id AND sender_type='$other_type'");
$unread_col = $my_type === 'user' ? 'user_unread' : 'ws_unread';
$conn->query("UPDATE chat_conversations SET $unread_col=0 WHERE id=$conv_id");


$msgs = $conn->prepare("
    SELECT id, sender_id, sender_type, message, is_read,
           DATE_FORMAT(created_at,'%H:%i') AS time_fmt,
           DATE_FORMAT(created_at,'%d %b %Y') AS date_fmt
    FROM chat_messages WHERE conversation_id=? ORDER BY created_at ASC LIMIT 60
");
$msgs->bind_param("i", $conv_id);
$msgs->execute();
$messages = $msgs->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode([
    'success'         => true,
    'conversation_id' => $conv_id,
    'other_name'      => $other_name,
    'messages'        => $messages,
    'my_type'         => $my_type,
    'my_id'           => $my_id
]);
