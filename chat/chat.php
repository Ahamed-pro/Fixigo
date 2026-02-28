<?php

session_start();
if (!isset($_SESSION['user_id'])) { header("Location: ../auth.php?error=Please+login+first."); exit; }
include "../config/db.php";

$my_id    = (int)$_SESSION['user_id'];
$my_name  = $_SESSION['user_name'];
$my_type  = $_SESSION['account_type'] === 'workshop' ? 'workshop' : 'user';
$initials = strtoupper(substr($my_name, 0, 1));


$all_workshops = [];
if ($my_type === 'user') {
    $wq = $conn->query("SELECT id, workshop_name, district, specialisation FROM workshops WHERE payment_status='paid' ORDER BY workshop_name ASC");
    while ($w = $wq->fetch_assoc()) $all_workshops[] = $w;
}


$unread_total = 0;
if ($my_type === 'workshop') {
    $ws = $conn->prepare("SELECT id FROM workshops WHERE user_id=? LIMIT 1");
    $ws->bind_param("i", $my_id);
    $ws->execute();
    $wsrow = $ws->get_result()->fetch_assoc();
    if ($wsrow) {
        $wsid = (int)$wsrow['id'];
        $uq = $conn->query("SELECT COALESCE(SUM(ws_unread),0) AS t FROM chat_conversations WHERE workshop_id=$wsid");
        $unread_total = (int)$uq->fetch_assoc()['t'];
    }
} else {
    $uq = $conn->query("SELECT COALESCE(SUM(user_unread),0) AS t FROM chat_conversations WHERE user_id=$my_id");
    $unread_total = (int)$uq->fetch_assoc()['t'];
}

$back_link = $my_type === 'user' ? '../user/user_index_dashboard.php' : '../workshop/workshop_index_dashboard.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Messages — Fixigo</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='20' fill='%23FF5C1A'/><text y='.9em' font-size='70' x='12'>🔧</text></svg>"/>
  <link rel="stylesheet" href="chat.css">
</head>
<body>
<canvas id="dots"></canvas>


<aside class="sidebar">
  <a href="../index.php" class="sb-logo"><div class="li">🔧</div><span>Fix<b>igo</b></span></a>
  <div class="sb-user">
    <div class="sb-avatar"><?= $initials ?></div>
    <div class="sb-name"><?= htmlspecialchars($my_name) ?></div>
    <span class="sb-role"><?= $my_type === 'workshop' ? '🏪 Workshop Owner' : '🚗 Vehicle Owner' ?></span>
  </div>
  <nav class="sb-nav">
    <div class="sb-label">Navigation</div>
    <a class="sb-item" href="<?= $back_link ?>"><span class="ic">🏠</span>Dashboard</a>
    <?php if ($my_type === 'user'): ?>
    <a class="sb-item" href="../index.php#workshops"><span class="ic">🔍</span>Find Workshops</a>
    <?php endif; ?>
    <a class="sb-item active" href="chat.php">
      <span class="ic">💬</span>Messages
      <?php if ($unread_total > 0): ?>
        <span class="sb-badge"><?= $unread_total ?></span>
      <?php endif; ?>
    </a>
    <div class="sb-label">Account</div>
    <a class="sb-item" href="../backend/logout.php"><span class="ic">🚪</span>Logout</a>
  </nav>
</aside>


<div class="main-wrap">


  <div class="conv-panel" id="conv-panel">
    <div class="conv-header">
      <div>
        <h2>💬 Messages</h2>
        <?php if ($unread_total > 0): ?>
          <div class="unread-pill"><?= $unread_total ?> unread</div>
        <?php endif; ?>
      </div>
      <?php if ($my_type === 'user'): ?>
        <button class="btn-new" onclick="showNewChatModal()">＋ New</button>
      <?php endif; ?>
    </div>
    <div class="conv-search-wrap">
      <input id="conv-search" type="text" placeholder="🔍 Search…" oninput="filterConvs(this.value)">
    </div>
    <div class="conv-list" id="conv-list">
      <div class="loading-state">Loading conversations…</div>
    </div>
  </div>


  <div class="chat-panel" id="chat-panel">


    <div class="chat-empty" id="chat-empty">
      <div class="chat-empty-icon">💬</div>
      <div class="chat-empty-title">Your Messages</div>
      <p><?= $my_type === 'user' ? 'Select a conversation or start a new chat with a workshop.' : 'Select a conversation from the list to reply.' ?></p>
      <?php if ($my_type === 'user'): ?>
        <button class="btn-or" onclick="showNewChatModal()">＋ Start New Chat</button>
      <?php endif; ?>
    </div>

  
    <div class="chat-window" id="chat-window" style="display:none">

      <div class="chat-header">
        <button class="back-btn" onclick="backToList()">←</button>
        <div class="chat-avatar" id="ch-avatar">?</div>
        <div class="chat-header-info">
          <div class="chat-name" id="ch-name">—</div>
          <div class="chat-status">● Active</div>
        </div>
      </div>


      <div class="messages-area" id="messages-area"></div>


      <div class="typing-dots" id="typing-dots" style="display:none">
        <span></span><span></span><span></span>
      </div>


      <div class="input-bar">
        <input type="text" id="msg-input" placeholder="Type a message…"
               onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();sendMsg()}">
        <button class="send-btn" id="send-btn" onclick="sendMsg()">
          <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/>
          </svg>
        </button>
      </div>
    </div>
  </div>
</div>


<?php if ($my_type === 'user'): ?>
<div class="overlay" id="overlay" onclick="hideNewChatModal()"></div>
<div class="new-chat-modal" id="new-chat-modal">
  <div class="ncm-header">
    <div>
      <div class="ncm-title">🏪 Start a New Chat</div>
      <div class="ncm-sub">Select a workshop to message</div>
    </div>
    <button class="ncm-close" onclick="hideNewChatModal()">✕</button>
  </div>
  <input class="ncm-search" id="ws-search" type="text" placeholder="🔍 Search workshops…" oninput="filterWs(this.value)">
  <div class="ws-list" id="ws-list">
    <?php foreach ($all_workshops as $w): ?>
    <div class="ws-item"
         data-id="<?= (int)$w['id'] ?>"
         data-wsname="<?= htmlspecialchars($w['workshop_name'], ENT_QUOTES) ?>"
         data-name="<?= strtolower(htmlspecialchars($w['workshop_name'])) ?>"
         onclick="openConvByWorkshop(this)">
      <div class="ws-av"><?= strtoupper(substr($w['workshop_name'],0,1)) ?></div>
      <div>
        <div class="ws-name"><?= htmlspecialchars($w['workshop_name']) ?></div>
        <div class="ws-sub"><?= htmlspecialchars($w['district']) ?> · <?= htmlspecialchars($w['specialisation']) ?></div>
      </div>
    </div>
    <?php endforeach; ?>
    <?php if (empty($all_workshops)): ?>
      <div class="ws-empty">No active workshops found.</div>
    <?php endif; ?>
  </div>
</div>
<?php endif; ?>

<script>
const MY_TYPE = '<?= $my_type ?>';
const MY_ID   = <?= $my_id ?>;
</script>
<script src="chat.js"></script>
</body>
</html>
