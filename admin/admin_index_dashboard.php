<?php

session_start();


if (!isset($_SESSION["user_id"]) || ($_SESSION["account_type"] ?? '') !== "admin") {

}

include "../config/db.php";


$r1 = $conn->query("SELECT COUNT(*) as c FROM users WHERE account_type='user'");
$total_users = $r1 ? (int)$r1->fetch_assoc()['c'] : 0;
$r1->close();

$r2 = $conn->query("SELECT COUNT(*) as c FROM workshops");
$total_workshops = $r2 ? (int)$r2->fetch_assoc()['c'] : 0;
$r2->close();

$r3 = $conn->query("SELECT COUNT(*) as c FROM workshops WHERE payment_status='paid'");
$paid_workshops = $r3 ? (int)$r3->fetch_assoc()['c'] : 0;
$r3->close();

$r4 = $conn->query("SELECT COUNT(*) as c FROM workshops WHERE payment_status='pending'");
$pending_pay = $r4 ? (int)$r4->fetch_assoc()['c'] : 0;
$r4->close();


$ws_result = $conn->query("
    SELECT w.*, u.full_name, u.email, u.phone
    FROM workshops w
    JOIN users u ON w.user_id = u.id
    ORDER BY w.created_at DESC
    LIMIT 10
");


$users_result = $conn->query("
    SELECT * FROM users ORDER BY created_at DESC LIMIT 10
");


$payments_result = $conn->query("
    SELECT cp.*, u.full_name, u.email, w.workshop_name
    FROM card_payments cp
    JOIN users u ON cp.user_id = u.id
    LEFT JOIN workshops w ON w.user_id = cp.user_id
    ORDER BY cp.id DESC
");


$sos_result = $conn->query("SELECT * FROM emergency_requests ORDER BY created_at DESC");


$contact_result = $conn->query("
    SELECT * FROM contact_messages 
    ORDER BY created_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fixigo – Admin Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='20' fill='%23FF5C1A'/><text y='.9em' font-size='70' x='12'>🔧</text></svg>"/>
  <link rel="stylesheet" href="admin.css">

</head>
<body>
<canvas id="dots"></canvas>

<aside class="sidebar">
  <a href="../index.php" class="sb-logo"><div class="li">🔧</div><span>Fix<b>igo</b></span></a>
  <div class="sb-user">
    <div class="sb-avatar">⚡</div>
    <div class="sb-name">Administrator</div>
    <span class="sb-role">🔐 Super Admin</span>
  </div>
  <nav class="sb-nav">
    <div class="sb-label">Management</div>
    <a class="sb-item active" href="#" onclick="showTab('overview',this)"><span class="ic">📊</span>Overview</a>
    <a class="sb-item" href="#" onclick="showTab('workshops',this)"><span class="ic">🏪</span>Workshops</a>
    <a class="sb-item" href="#" onclick="showTab('users',this)"><span class="ic">👥</span>Users</a>
    <a class="sb-item" href="#" onclick="showTab('payments',this)"><span class="ic">💳</span>Payments</a>
    <a class="sb-item" href="#" onclick="showTab('sos',this)"><span class="ic">🚨</span>Emergency SOS</a>
    <a class="sb-item" href="#" onclick="showTab('contact',this)"><span class="ic">📩</span>Contact Messages</a>
    <div class="sb-label">System</div>
    <a class="sb-item" href="#"><span class="ic">🔔</span>Notifications</a>
    <a class="sb-item" href="#"><span class="ic">⚙️</span>Settings</a>
  </nav>
  <div class="sb-bottom">
    <a href="../backend/logout.php" class="sb-item"><span class="ic">🚪</span>Logout</a>
  </div>
</aside>


<div class="main">
  <div class="topbar">
    <h2>Admin Control Panel</h2>
    <div class="topbar-r">
      <a href="../index.php" class="btn btn-gh">← View Site</a>
      <a href="../backend/logout.php" class="btn btn-gh">Logout</a>
    </div>
  </div>

  <div class="content">

  
    <div class="admin-hero">
      <div>
        <div class="tag">🔐 Admin Panel</div>
        <h1>Fixigo <span>Control Center</span></h1>
        <p>Manage users, workshops, payments, and monitor platform activity.</p>
      </div>
    </div>

    <div class="stats">
      <div class="stat"><div class="stat-top"><div class="si si-bl">👥</div><span class="sbadge sbadge-or">Users</span></div><div class="stat-n"><?= $total_users ?></div><div class="stat-l">Vehicle Owners</div></div>
      <div class="stat"><div class="stat-top"><div class="si si-or">🏪</div><span class="sbadge sbadge-or">Total</span></div><div class="stat-n"><?= $total_workshops ?></div><div class="stat-l">Workshops Registered</div></div>
      <div class="stat"><div class="stat-top"><div class="si si-gr">✅</div><span class="sbadge sbadge-gr">Verified</span></div><div class="stat-n"><?= $paid_workshops ?></div><div class="stat-l">Paid & Active</div></div>
      <div class="stat"><div class="stat-top"><div class="si si-re">⏳</div><span class="sbadge sbadge-re">Action Needed</span></div><div class="stat-n"><?= $pending_pay ?></div><div class="stat-l">Pending Payment</div></div>
    </div>

  
    <div class="tabs">
      <button class="tab active" onclick="showTab('overview',this)">📊 Overview</button>
      <button class="tab" onclick="showTab('workshops',this)">🏪 Workshops</button>
      <button class="tab" onclick="showTab('users',this)">👥 Users</button>
      <button class="tab" onclick="showTab('payments',this)">💳 Payments</button>
      <button class="tab" onclick="showTab('sos',this)">🚨 SOS</button>
      <button class="tab" onclick="showTab('contact',this)">📩 Contact</button>
    </div>


    <div class="tab-panel active" id="tab-overview">
      <div class="cbox">
        <div class="cbox-head"><h4>Recent Workshops</h4><span><?= $total_workshops ?> total</span></div>
        <table>
          <thead><tr><th>Workshop</th><th>Owner</th><th>District</th><th>Specialisation</th><th>Payment</th><th>Actions</th></tr></thead>
          <tbody>
          <?php
          $recent_ws = $conn->query("SELECT w.*, u.full_name, u.email FROM workshops w JOIN users u ON w.user_id=u.id ORDER BY w.created_at DESC LIMIT 5");
          if($recent_ws && $recent_ws->num_rows > 0):
            while($row = $recent_ws->fetch_assoc()): ?>
          <tr id="ws-row-<?= $row['id'] ?>">
            <td><div class="cell-name"><div class="avatar-sm avatar-ws">🏪</div><div><div class="td-name"><?= htmlspecialchars($row['workshop_name']) ?></div><div class="td-email"><?= htmlspecialchars($row['email']) ?></div></div></div></td>
            <td><?= htmlspecialchars($row['full_name']) ?></td>
            <td><?= htmlspecialchars($row['district']) ?></td>
            <td><?= htmlspecialchars($row['specialisation']) ?></td>
            <td><span class="status-badge <?= $row['payment_status']==='paid'?'s-paid':'s-pending' ?>"><?= $row['payment_status']==='paid'?'✅ Paid':'⏳ Pending' ?></span></td>
            <td><div class="action-btns">
              <button class="action-btn ab-view" onclick="viewWorkshop('<?=addslashes($row['workshop_name'])?>','<?=addslashes($row['full_name'])?>','<?=addslashes($row['email'])?>','<?=addslashes($row['district'])?>','<?=addslashes($row['specialisation'])?>','<?=addslashes($row['address']??'')?>','<?=addslashes($row['business_reg']??'')?>','<?=$row['payment_status']?>')">View</button>
              <?php if($row['payment_status']==='pending'):?>
              <button class="action-btn ab-approve" onclick="approvePayment(<?=$row['id']?>)">Approve</button>
              <?php endif;?>
              <button class="action-btn ab-del" onclick="deleteWorkshop(<?=$row['id']?>,'<?=addslashes($row['workshop_name'])?>')">Delete</button>
            </div></td>
          </tr>
          <?php endwhile; else: ?>
          <tr><td colspan="6"><div class="empty"><div class="ei">🏪</div><p>No workshops registered yet.</p></div></td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>


    <div class="tab-panel" id="tab-workshops">
      <div class="cbox">
        <div class="cbox-head">
          <h4>All Workshops</h4>
          <div class="search-bar"><input type="text" placeholder="🔍 Search workshops…" oninput="filterTable('ws-tbody',this.value)"></div>
        </div>
        <table>
          <thead><tr><th>Workshop</th><th>Owner</th><th>Contact</th><th>District</th><th>Specialisation</th><th>Payment</th><th>Registered</th><th>Actions</th></tr></thead>
          <tbody id="ws-tbody">
          <?php if($ws_result && $ws_result->num_rows > 0): while($row = $ws_result->fetch_assoc()): ?>
          <tr id="ws-row-<?= $row['id'] ?>">
            <td><div class="cell-name"><div class="avatar-sm avatar-ws">🏪</div><div><div class="td-name"><?= htmlspecialchars($row['workshop_name']) ?></div><?php if($row['business_reg']):?><div class="td-email"><?= htmlspecialchars($row['business_reg']) ?></div><?php endif;?></div></div></td>
            <td><?= htmlspecialchars($row['full_name']) ?></td>
            <td><div class="td-email"><?= htmlspecialchars($row['email']) ?></div><div class="td-email"><?= htmlspecialchars($row['phone']) ?></div></td>
            <td><?= htmlspecialchars($row['district']) ?></td>
            <td><?= htmlspecialchars($row['specialisation']) ?></td>
            <td><span class="status-badge <?= $row['payment_status']==='paid'?'s-paid':'s-pending' ?>"><?= $row['payment_status']==='paid'?'✅ Paid':'⏳ Pending' ?></span></td>
            <td class="td-email"><?= date('d M Y', strtotime($row['created_at'])) ?></td>
            <td><div class="action-btns">
              <button class="action-btn ab-view" onclick="viewWorkshop('<?=addslashes($row['workshop_name'])?>','<?=addslashes($row['full_name'])?>','<?=addslashes($row['email'])?>','<?=addslashes($row['district'])?>','<?=addslashes($row['specialisation'])?>','<?=addslashes($row['address']??'')?>','<?=addslashes($row['business_reg']??'')?>','<?=$row['payment_status']?>')">👁 View</button>
              <?php if($row['payment_status']==='pending'):?>
              <button class="action-btn ab-approve" onclick="approvePayment(<?=$row['id']?>)">✅ Approve</button>
              <?php endif;?>
              <button class="action-btn ab-del" onclick="deleteWorkshop(<?=$row['id']?>,'<?=addslashes($row['workshop_name'])?>')">🗑 Delete</button>
            </div></td>
          </tr>
          <?php endwhile; else: ?>
          <tr><td colspan="8"><div class="empty"><div class="ei">🏪</div><p>No workshops registered yet.</p></div></td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>


    <div class="tab-panel" id="tab-users">
      <div class="cbox">
        <div class="cbox-head">
          <h4>All Users</h4>
          <div class="search-bar"><input type="text" placeholder="🔍 Search users…" oninput="filterTable('users-tbody',this.value)"></div>
        </div>
        <table>
          <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Account Type</th><th>Registered</th><th>Actions</th></tr></thead>
          <tbody id="users-tbody">
          <?php if($users_result && $users_result->num_rows > 0): while($row = $users_result->fetch_assoc()):
            $reg = date('d M Y', strtotime($row['created_at']));
          ?>
          <tr id="user-row-<?= $row['id'] ?>">
            <td><div class="cell-name"><div class="avatar-sm"><?= strtoupper(substr($row['full_name'],0,1)) ?></div><div class="td-name"><?= htmlspecialchars($row['full_name']) ?></div></div></td>
            <td class="td-email"><?= htmlspecialchars($row['email']) ?></td>
            <td class="td-email"><?= htmlspecialchars($row['phone']) ?></td>
            <td><span class="status-badge <?= $row['account_type']==='workshop'?'s-workshop':'s-user' ?>"><?= $row['account_type']==='workshop'?'🏪 Workshop':'🚗 Vehicle Owner' ?></span></td>
            <td class="td-email"><?= $reg ?></td>
            <td><div class="action-btns">
              <button class="action-btn ab-view" onclick="viewUser(<?=$row['id']?>,'<?=addslashes(htmlspecialchars($row['full_name']))?>','<?=addslashes($row['email'])?>','<?=addslashes($row['phone'])?>','<?=$row['account_type']?>','<?=$reg?>')">View</button>
              <button class="action-btn ab-del" onclick="deleteUser(<?=$row['id']?>,'<?=addslashes(htmlspecialchars($row['full_name']))?>')">🗑 Delete</button>
            </div></td>
          </tr>
          <?php endwhile; else: ?>
          <tr><td colspan="6"><div class="empty"><div class="ei">👥</div><p>No users registered yet.</p></div></td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  
    <div class="tab-panel" id="tab-payments">
      <div class="cbox">
        <div class="cbox-head">
          <h4>💳 Card Payments</h4>
          <div class="search-bar"><input type="text" placeholder="🔍 Search payments…" oninput="filterTable('pay-tbody',this.value)"></div>
        </div>
        <table>
          <thead><tr><th>Cardholder</th><th>Workshop</th><th>Card Number</th><th>Expiry</th><th>Amount</th><th>Email</th></tr></thead>
          <tbody id="pay-tbody">
          <?php if($payments_result && $payments_result->num_rows > 0): while($row = $payments_result->fetch_assoc()):
            $masked = str_repeat('*', max(0, strlen($row['card_number']) - 4)) . substr($row['card_number'], -4);
          ?>
          <tr>
            <td class="td-name"><?= htmlspecialchars($row['cardholder_name']) ?></td>
            <td><?= htmlspecialchars($row['workshop_name'] ?? '—') ?></td>
            <td style="font-family:monospace;letter-spacing:1px"><?= htmlspecialchars($masked) ?></td>
            <td><?= htmlspecialchars($row['expiry']) ?></td>
            <td><strong style="color:var(--green)">LKR <?= number_format((float)$row['amount'], 2) ?></strong></td>
            <td class="td-email"><?= htmlspecialchars($row['email']) ?></td>
          </tr>
          <?php endwhile; else: ?>
          <tr><td colspan="6"><div class="empty"><div class="ei">💳</div><p>No payment records found.</p></div></td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>


    <div class="tab-panel" id="tab-sos">
      <div class="cbox">
        <div class="cbox-head">
          <h4>🚨 Emergency SOS Alerts</h4>
          <div class="search-bar"><input type="text" placeholder="🔍 Search…" oninput="filterTable('sos-tbody',this.value)"></div>
        </div>
        <table>
          <thead><tr><th>Name</th><th>Phone</th><th>Type</th><th>Location</th><th>Landmark</th><th>Date & Time</th></tr></thead>
          <tbody id="sos-tbody">
          <?php if($sos_result && $sos_result->num_rows > 0): while($row = $sos_result->fetch_assoc()): ?>
          <tr>
            <td class="td-name"><?= htmlspecialchars($row['name']) ?></td>
            <td class="td-email"><?= htmlspecialchars($row['phone']) ?></td>
            <td><span class="sos-badge">🚨 <?= htmlspecialchars($row['emergency_type']) ?></span></td>
            <td style="font-size:12px;color:var(--text-muted);max-width:160px"><?= htmlspecialchars($row['location']) ?></td>
            <td class="td-email"><?= htmlspecialchars($row['landmark'] ?? '—') ?></td>
            <td class="td-email"><?= date('d M Y H:i', strtotime($row['created_at'])) ?></td>
          </tr>
          <?php endwhile; else: ?>
          <tr><td colspan="6"><div class="empty"><div class="ei">🚨</div><p>No emergency SOS alerts recorded.</p></div></td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
    

<div class="tab-panel" id="tab-contact">
  <div class="cbox">
    <div class="cbox-head">
      <h4>📩 Contact Messages</h4>
      <div class="search-bar">
        <input type="text" placeholder="🔍 Search messages…" oninput="filterTable('contact-tbody',this.value)">
      </div>
    </div>

    <table>
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Topic</th>
          <th>Message</th>
          <th>Date</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody id="contact-tbody">

      <?php if($contact_result && $contact_result->num_rows > 0): 
        while($row = $contact_result->fetch_assoc()): ?>

        <tr style="<?= $row['is_read'] ? '' : 'background:rgba(255,92,26,.05);' ?>">
          <td class="td-name">
            <?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?>
          </td>

          <td class="td-email"><?= htmlspecialchars($row['email']) ?></td>

          <td class="td-email">
            <?= htmlspecialchars($row['phone'] ?? '—') ?>
          </td>

          <td><?= htmlspecialchars($row['topic']) ?></td>

          <td style="max-width:250px;font-size:12px;color:var(--text-muted)">
            <?= htmlspecialchars(substr($row['message'],0,100)) ?>...
          </td>

          <td class="td-email">
            <?= date('d M Y H:i', strtotime($row['created_at'])) ?>
          </td>

          <td>
            <?php if($row['is_read']): ?>
              <span class="status-badge s-paid">✔ Read</span>
            <?php else: ?>
              <span class="status-badge s-pending">📬 Unread</span>
            <?php endif; ?>
          </td>
        </tr>

      <?php endwhile; else: ?>

        <tr>
          <td colspan="7">
            <div class="empty">
              <div class="ei">📩</div>
              <p>No contact messages found.</p>
            </div>
          </td>
        </tr>

      <?php endif; ?>

      </tbody>
    </table>
  </div>
</div>


  </div>
</div>

<div class="modal-overlay" id="modal-user" onclick="if(event.target===this)closeModal('modal-user')">
  <div class="modal-box">
    <button class="modal-close" onclick="closeModal('modal-user')">✕</button>
    <div class="modal-avatar" id="mu-avatar">U</div>
    <div class="modal-uname" id="mu-name">—</div>
    <div class="mrow"><span class="mlabel">Email</span><span class="mval" id="mu-email">—</span></div>
    <div class="mrow"><span class="mlabel">Phone</span><span class="mval" id="mu-phone">—</span></div>
    <div class="mrow"><span class="mlabel">Account</span><span class="mval" id="mu-type">—</span></div>
    <div class="mrow"><span class="mlabel">Registered</span><span class="mval" id="mu-date">—</span></div>
    <div class="modal-actions">
      <button class="btn btn-gh" style="flex:1" onclick="closeModal('modal-user')">Close</button>
      <button class="btn btn-red" style="flex:1" id="mu-del-btn">🗑 Delete User</button>
    </div>
  </div>
</div>


<div class="modal-overlay" id="modal-ws" onclick="if(event.target===this)closeModal('modal-ws')">
  <div class="modal-box">
    <button class="modal-close" onclick="closeModal('modal-ws')">✕</button>
    <div class="modal-avatar ws">🏪</div>
    <div class="modal-uname" id="mw-name">—</div>
    <div class="mrow"><span class="mlabel">Owner</span><span class="mval" id="mw-owner">—</span></div>
    <div class="mrow"><span class="mlabel">Email</span><span class="mval" id="mw-email">—</span></div>
    <div class="mrow"><span class="mlabel">District</span><span class="mval" id="mw-district">—</span></div>
    <div class="mrow"><span class="mlabel">Specialisation</span><span class="mval" id="mw-spec">—</span></div>
    <div class="mrow"><span class="mlabel">Address</span><span class="mval" id="mw-address">—</span></div>
    <div class="mrow"><span class="mlabel">Business Reg</span><span class="mval" id="mw-reg">—</span></div>
    <div class="mrow"><span class="mlabel">Payment</span><span class="mval" id="mw-pay">—</span></div>
    <div class="modal-actions">
      <button class="btn btn-gh" style="flex:1" onclick="closeModal('modal-ws')">Close</button>
    </div>
  </div>
</div>

<div class="toast" id="toast"><span id="t-icon">✅</span><span id="t-msg"></span></div>

<script src="admin.js"></script>
</body>
</html>