<?php

session_start();
if (!isset($_SESSION["user_id"])) {
  header("Location: ../auth.php?error=Please+login+first.");
  exit;
}
if ($_SESSION["account_type"] === "user") {
  header("Location: ../user/user_index_dashboard.php");
  exit;
}

include "../config/db.php";

$user_id    = (int) $_SESSION["user_id"];
$user_name  = $_SESSION["user_name"];
$first_name = explode(" ", $user_name)[0];
$successMsg = isset($_GET["success"]) ? htmlspecialchars($_GET["success"]) : "";


$ws = $conn->prepare("SELECT * FROM workshops WHERE user_id = ? LIMIT 1");
$ws->bind_param("i", $user_id);
$ws->execute();
$workshop = $ws->get_result()->fetch_assoc();
$workshop_id = $workshop ? (int)$workshop['id'] : 0;


$all_requests = [];
if ($workshop_id) {
  $rq = $conn->prepare("
        SELECT sr.id, sr.user_name, sr.user_phone, sr.service_type,
               sr.location, sr.description, sr.status,
               DATE_FORMAT(sr.created_at, '%d %b %Y · %H:%i') AS date_fmt
        FROM service_requests sr
        WHERE sr.workshop_id = ?
        ORDER BY sr.created_at DESC
    ");
  $rq->bind_param("i", $workshop_id);
  $rq->execute();
  $rr = $rq->get_result();
  while ($r = $rr->fetch_assoc()) $all_requests[] = $r;
}

$total_req    = count($all_requests);
$pending_req  = count(array_filter($all_requests, fn($r) => $r['status'] === 'pending'));
$accepted_req = count(array_filter($all_requests, fn($r) => $r['status'] === 'accepted'));
$ignored_req  = count(array_filter($all_requests, fn($r) => $r['status'] === 'ignored'));
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Fixigo – Workshop Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='20' fill='%23FF5C1A'/><text y='.9em' font-size='70' x='12'>🔧</text></svg>" />
  <link rel="stylesheet" href="workshop.css">
</head>

<body>
  <canvas id="dots"></canvas>


  <aside class="sidebar">
    <a href="../index.php" class="sb-logo">
      <div class="li">🔧</div><span>Fix<b>igo</b></span>
    </a>
    <div class="sb-user">
      <div class="sb-avatar"><?= strtoupper(substr($first_name, 0, 1)) ?></div>
      <div class="sb-name"><?= htmlspecialchars($user_name) ?></div>
      <span class="sb-role">🏪 Workshop Owner</span>
    </div>
    <nav class="sb-nav">
      <div class="sb-label">Workshop</div>
      <a class="sb-item active" href="#"><span class="ic">🏠</span>Dashboard</a>
      <a class="sb-item" href="#" onclick="scrollToRequests()"><span class="ic">📋</span>Service Requests
        <?php if ($pending_req > 0): ?>
          <span style="margin-left:auto;background:var(--orange);color:#fff;font-size:10px;padding:1px 7px;border-radius:50px"><?= $pending_req ?></span>
        <?php endif; ?>
      </a>
      <div class="sb-label">Account</div>
      <a class="sb-item" href="#"><span class="ic">⚙️</span>Workshop Profile</a>
      <a class="sb-item" href="#"><span class="ic">💳</span>Payment Status</a>
    </nav>
    <div class="sb-bottom">
      <a href="../backend/logout.php" class="sb-item"><span class="ic">🚪</span>Logout</a>
    </div>
  </aside>


  <div class="main">
    <div class="topbar">
      <h2>Workshop Dashboard</h2>
      <div class="topbar-r">
        <button class="btn btn-gh" onclick="toggleSidebar()">☰</button>
        <div class="sidebar-overlay" onclick="closeSidebar()"></div>
        <?php if ($pending_req > 0): ?>
          <span style="background:rgba(255,92,26,.12);color:var(--orange);border:1px solid rgba(255,92,26,.3);padding:6px 14px;border-radius:50px;font-size:13px;font-weight:600">
            🔔 <?= $pending_req ?> new request<?= $pending_req > 1 ? 's' : '' ?>
          </span>
        <?php endif; ?>
        <a href="../index.php" class="btn btn-gh">← Back to Site</a>
        <a href="../backend/logout.php" class="btn btn-gh">Logout</a>
      </div>
    </div>

    <div class="content">


      <?php if ($workshop): ?>
        <div class="ws-hero">
          <div class="tag">Workshop Owner Dashboard</div>
          <h1>🏪 <span><?= htmlspecialchars($workshop['workshop_name']) ?></span></h1>
          <div class="ws-meta-row">
            <div class="ws-meta-item">📍 <strong><?= htmlspecialchars($workshop['district']) ?></strong></div>
            <div class="ws-meta-item">🔧 <strong><?= htmlspecialchars($workshop['specialisation']) ?></strong></div>
            <div class="ws-meta-item">📌 <strong><?= htmlspecialchars($workshop['address']) ?></strong></div>
            <span class="pay-badge <?= $workshop['payment_status'] === 'paid' ? 'pay-paid' : 'pay-pending' ?>">
              <?= $workshop['payment_status'] === 'paid' ? '✅ Verified & Listed' : '⏳ Payment Pending' ?>
            </span>
          </div>
        </div>
      <?php else: ?>
        <div class="ws-hero">
          <div class="tag">Workshop Dashboard</div>
          <h1>Hey, <span><?= htmlspecialchars($first_name) ?></span> 👋</h1>
          <p style="color:var(--text-muted);margin-top:8px">⚠️ Workshop details not found.</p>
        </div>
      <?php endif; ?>


      <div class="stats">
        <div class="stat">
          <div class="stat-top">
            <div class="si si-or">📋</div><span class="sbadge sbadge-or">Total</span>
          </div>
          <div class="stat-n"><?= $total_req ?></div>
          <div class="stat-l">Total Requests</div>
        </div>
        <div class="stat">
          <div class="stat-top">
            <div class="si si-bl">⏳</div><span class="sbadge sbadge-or">Waiting</span>
          </div>
          <div class="stat-n" id="ws-pending"><?= $pending_req ?></div>
          <div class="stat-l">Pending</div>
        </div>
        <div class="stat">
          <div class="stat-top">
            <div class="si si-gr">✅</div><span class="sbadge sbadge-gr">Done</span>
          </div>
          <div class="stat-n" id="ws-accepted"><?= $accepted_req ?></div>
          <div class="stat-l">Accepted</div>
        </div>
        <div class="stat">
          <div class="stat-top">
            <div class="si si-pu">✗</div><span class="sbadge sbadge-or">Skipped</span>
          </div>
          <div class="stat-n" id="ws-ignored"><?= $ignored_req ?></div>
          <div class="stat-l">Ignored</div>
        </div>
      </div>


      <div class="g2">
        <div>
          <div class="sh">
            <h3>Quick Actions</h3>
          </div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
            <?php
            $actions = [
              ['📋', 'Service Requests', 'View & respond to customers', 'javascript:scrollToRequests()'],
              ['⚙️', 'Edit Profile', 'Update your workshop info', 'javascript:openModal(\'modal-edit\')'],
              ['📊', 'Analytics', 'Views, requests & trends', '#'],
              ['💳', 'Payment', 'Check your payment status', 'javascript:openModal(\'modal-payment\')'],
            ];
            foreach ($actions as $a): ?>
              <a href="<?= $a[3] ?>" style="background:var(--dark4);border:1px solid var(--border);border-radius:14px;padding:18px;text-decoration:none;display:block;transition:.25s" onmouseover="this.style.borderColor='rgba(255,92,26,.3)'" onmouseout="this.style.borderColor='var(--border)'">
                <div style="font-size:26px;margin-bottom:8px"><?= $a[0] ?></div>
                <div style="font-weight:600;font-size:14px;margin-bottom:3px;color:var(--text)"><?= $a[1] ?></div>
                <div style="font-size:12px;color:var(--text-muted)"><?= $a[2] ?></div>
              </a>
            <?php endforeach; ?>
          </div>
        </div>

        <?php if ($workshop): ?>
          <div>
            <div class="sh">
              <h3>Workshop Info</h3>
            </div>
            <div class="cbox" style="margin-bottom:0">
              <div style="padding:4px 18px">
                <?php
                $fields = [
                  ['📛', 'Workshop Name', $workshop['workshop_name']],
                  ['📍', 'District',      $workshop['district']],
                  ['🔧', 'Specialisation', $workshop['specialisation']],
                  ['📌', 'Address',       $workshop['address']],
                  ['📄', 'Business Reg',  $workshop['business_reg'] ?: '—'],
                ];
                foreach ($fields as $f): ?>
                  <div class="info-row">
                    <span class="info-icon"><?= $f[0] ?></span>
                    <div>
                      <div class="info-label"><?= $f[1] ?></div>
                      <div class="info-val"><?= htmlspecialchars($f[2]) ?></div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        <?php endif; ?>
      </div>


      <div class="sh" id="section-requests">
        <h3>Service Requests <?php if ($total_req): ?><span style="color:var(--text-dim);font-size:13px;font-family:'DM Sans',sans-serif;font-weight:400">(<?= $total_req ?>)</span><?php endif; ?></h3>
        <?php if ($pending_req > 0): ?>
          <span style="color:var(--orange)"><?= $pending_req ?> pending</span>
        <?php endif; ?>
      </div>

      <?php if (!$all_requests): ?>
        <div class="cbox">
          <div class="empty">
            <div class="ei">📭</div>
            <p>No service requests yet.<br>Your workshop will receive requests once customers find you.</p>
          </div>
        </div>

      <?php else: ?>


        <div style="background:var(--dark3);border:1px solid var(--border);border-radius:16px 16px 0 0;border-bottom:none">
          <div class="filter-tabs">
            <button class="ftab active" onclick="filterCards('all',this)">All (<?= $total_req ?>)</button>
            <button class="ftab" onclick="filterCards('pending',this)">⏳ Pending (<?= $pending_req ?>)</button>
            <button class="ftab" onclick="filterCards('accepted',this)">✅ Accepted (<?= $accepted_req ?>)</button>
            <button class="ftab" onclick="filterCards('ignored',this)">✗ Ignored (<?= $ignored_req ?>)</button>
          </div>
        </div>

        <div style="background:var(--dark3);border:1px solid var(--border);border-top:none;border-radius:0 0 16px 16px;margin-bottom:28px">
          <div class="req-list" id="req-card-list">
            <?php foreach ($all_requests as $r):
              $initials = strtoupper(substr($r['user_name'], 0, 1));
              $statusClass = 'rb-' . $r['status'];
              $cardClass   = $r['status'] !== 'pending' ? 'is-' . $r['status'] : '';
              $badgeLabel  = match ($r['status']) {
                'accepted' => '✅ Accepted',
                'ignored'  => '✗ Ignored',
                default    => '⏳ Pending',
              };
            ?>
              <div class="req-card <?= $cardClass ?>" id="card-<?= $r['id'] ?>" data-status="<?= $r['status'] ?>">


                <div class="rc-top">
                  <div class="rc-user">
                    <div class="rc-avatar"><?= htmlspecialchars($initials) ?></div>
                    <div>
                      <div class="rc-name"><?= htmlspecialchars($r['user_name']) ?></div>
                      <div class="rc-phone">📞 <?= htmlspecialchars($r['user_phone']) ?></div>
                    </div>
                  </div>
                  <span class="rc-badge <?= $statusClass ?>" id="badge-<?= $r['id'] ?>"><?= $badgeLabel ?></span>
                </div>


                <div class="rc-details">
                  <div class="rc-detail">
                    <div class="rc-detail-label">Service Requested</div>
                    <div class="rc-detail-val">🔧 <?= htmlspecialchars($r['service_type']) ?></div>
                  </div>
                  <div class="rc-detail">
                    <div class="rc-detail-label">Location</div>
                    <div class="rc-detail-val">📍 <?= htmlspecialchars($r['location'] ?: 'Not specified') ?></div>
                  </div>
                </div>


                <?php if ($r['description']): ?>
                  <div class="rc-desc">
                    <div class="rc-detail-label" style="margin-bottom:5px">Customer Note</div>
                    <p>"<?= htmlspecialchars($r['description']) ?>"</p>
                  </div>
                <?php endif; ?>

                <div class="rc-date">📅 <?= $r['date_fmt'] ?></div>


                <?php if ($r['status'] === 'pending'): ?>
                  <div class="rc-actions" id="actions-<?= $r['id'] ?>">
                    <button class="btn-accept" onclick="handleRequest(<?= $r['id'] ?>,'accepted')">✅ Accept Request</button>
                    <button class="btn-ignore" onclick="handleRequest(<?= $r['id'] ?>,'ignored')">✗ Ignore</button>
                  </div>
                <?php else: ?>
                  <div class="rc-handled" id="actions-<?= $r['id'] ?>">
                    <?= $r['status'] === 'accepted' ? '✅ You accepted this request' : '✗ You ignored this request' ?>
                  </div>
                <?php endif; ?>

              </div>
            <?php endforeach; ?>
          </div>
        </div>

      <?php endif; ?>

    </div>
  </div>

  <div class="toast" id="toast"><span id="t-icon">✅</span><span id="t-msg"></span></div>


  <div id="modal-edit" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.65);backdrop-filter:blur(6px);z-index:200;align-items:center;justify-content:center;padding:20px">
    <div style="background:var(--dark3);border:1px solid var(--border);border-radius:20px;width:100%;max-width:500px;padding:32px;position:relative;animation:popIn .22s cubic-bezier(.34,1.56,.64,1);max-height:90vh;overflow-y:auto">
      <button onclick="closeModal('modal-edit')" style="position:absolute;top:16px;right:16px;background:rgba(255,255,255,.06);border:none;color:var(--text-muted);font-size:15px;width:30px;height:30px;border-radius:8px;cursor:pointer">✕</button>
      <div style="font-size:22px;margin-bottom:6px">⚙️</div>
      <div style="font-family:'Syne',sans-serif;font-size:18px;font-weight:800;margin-bottom:20px">Edit Workshop Profile</div>
      <?php if ($workshop): ?>
        <div style="display:flex;flex-direction:column;gap:14px">
          <div>
            <label style="font-size:11px;text-transform:uppercase;letter-spacing:.7px;color:var(--text-dim);display:block;margin-bottom:6px">Workshop Name</label>
            <input id="ef-name" value="<?= htmlspecialchars($workshop['workshop_name']) ?>" style="width:100%;background:var(--dark4);border:1px solid var(--border);border-radius:10px;padding:10px 14px;color:var(--text);font-family:'DM Sans',sans-serif;font-size:14px;outline:none">
          </div>
          <div>
            <label style="font-size:11px;text-transform:uppercase;letter-spacing:.7px;color:var(--text-dim);display:block;margin-bottom:6px">Address</label>
            <input id="ef-address" value="<?= htmlspecialchars($workshop['address']) ?>" style="width:100%;background:var(--dark4);border:1px solid var(--border);border-radius:10px;padding:10px 14px;color:var(--text);font-family:'DM Sans',sans-serif;font-size:14px;outline:none">
          </div>
          <div>
            <label style="font-size:11px;text-transform:uppercase;letter-spacing:.7px;color:var(--text-dim);display:block;margin-bottom:6px">District</label>
            <input id="ef-district" value="<?= htmlspecialchars($workshop['district']) ?>" style="width:100%;background:var(--dark4);border:1px solid var(--border);border-radius:10px;padding:10px 14px;color:var(--text);font-family:'DM Sans',sans-serif;font-size:14px;outline:none">
          </div>
          <div>
            <label style="font-size:11px;text-transform:uppercase;letter-spacing:.7px;color:var(--text-dim);display:block;margin-bottom:6px">Specialisation</label>
            <input id="ef-spec" value="<?= htmlspecialchars($workshop['specialisation']) ?>" style="width:100%;background:var(--dark4);border:1px solid var(--border);border-radius:10px;padding:10px 14px;color:var(--text);font-family:'DM Sans',sans-serif;font-size:14px;outline:none">
          </div>
          <div>
            <label style="font-size:11px;text-transform:uppercase;letter-spacing:.7px;color:var(--text-dim);display:block;margin-bottom:6px">Business Reg (optional)</label>
            <input id="ef-reg" value="<?= htmlspecialchars($workshop['business_reg'] ?? '') ?>" style="width:100%;background:var(--dark4);border:1px solid var(--border);border-radius:10px;padding:10px 14px;color:var(--text);font-family:'DM Sans',sans-serif;font-size:14px;outline:none">
          </div>
        </div>
        <div style="display:flex;gap:10px;margin-top:22px">
          <button type="button" onclick="closeModal('modal-edit')" style="flex:1;padding:10px;border-radius:50px;background:var(--dark4);border:1px solid var(--border);color:var(--text-muted);cursor:pointer;font-family:'DM Sans',sans-serif;font-size:14px">Cancel</button>
          <button type="button" onclick="saveProfile()" style="flex:1;padding:10px;border-radius:50px;background:var(--orange);border:none;color:#fff;cursor:pointer;font-family:'Syne',sans-serif;font-weight:700;font-size:14px">Save Changes</button>
        </div>
      <?php endif; ?>
    </div>
  </div>


  <div id="modal-payment" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.65);backdrop-filter:blur(6px);z-index:200;align-items:center;justify-content:center;padding:20px">
    <div style="background:var(--dark3);border:1px solid var(--border);border-radius:20px;width:100%;max-width:420px;padding:32px;position:relative;animation:popIn .22s cubic-bezier(.34,1.56,.64,1)">
      <button onclick="closeModal('modal-payment')" style="position:absolute;top:16px;right:16px;background:rgba(255,255,255,.06);border:none;color:var(--text-muted);font-size:15px;width:30px;height:30px;border-radius:8px;cursor:pointer">✕</button>
      <div style="font-size:22px;margin-bottom:6px">💳</div>
      <div style="font-family:'Syne',sans-serif;font-size:18px;font-weight:800;margin-bottom:20px">Payment Status</div>
      <?php if ($workshop): ?>
        <div style="display:flex;flex-direction:column;gap:12px">
          <div style="background:var(--dark4);border-radius:14px;padding:20px;text-align:center">
            <?php if ($workshop['payment_status'] === 'paid'): ?>
              <div style="font-size:40px;margin-bottom:10px">✅</div>
              <div style="font-family:'Syne',sans-serif;font-size:18px;font-weight:800;color:var(--green);margin-bottom:6px">Payment Verified</div>
              <div style="font-size:13px;color:var(--text-muted)">Your workshop is active and visible to customers.</div>
            <?php else: ?>
              <div style="font-size:40px;margin-bottom:10px">⏳</div>
              <div style="font-family:'Syne',sans-serif;font-size:18px;font-weight:800;color:#FFD020;margin-bottom:6px">Payment Pending</div>
              <div style="font-size:13px;color:var(--text-muted)">Your listing is not yet visible. Please complete your payment to get activated.</div>
            <?php endif; ?>
          </div>
          <div style="background:var(--dark4);border-radius:12px;padding:14px 16px">
            <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--border)">
              <span style="font-size:12px;color:var(--text-dim);text-transform:uppercase;letter-spacing:.6px">Workshop</span>
              <span style="font-size:13px;font-weight:600"><?= htmlspecialchars($workshop['workshop_name']) ?></span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--border)">
              <span style="font-size:12px;color:var(--text-dim);text-transform:uppercase;letter-spacing:.6px">Status</span>
              <span style="font-size:13px;font-weight:600;color:<?= $workshop['payment_status'] === 'paid' ? 'var(--green)' : '#FFD020' ?>">
                <?= $workshop['payment_status'] === 'paid' ? 'Paid & Active' : 'Pending' ?>
              </span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:6px 0">
              <span style="font-size:12px;color:var(--text-dim);text-transform:uppercase;letter-spacing:.6px">Registered</span>
              <span style="font-size:13px;font-weight:600"><?= date('d M Y', strtotime($workshop['created_at'])) ?></span>
            </div>
          </div>
        </div>
      <?php endif; ?>
      <button onclick="closeModal('modal-payment')" style="width:100%;margin-top:18px;padding:10px;border-radius:50px;background:var(--dark4);border:1px solid var(--border);color:var(--text-muted);cursor:pointer;font-family:'DM Sans',sans-serif;font-size:14px">Close</button>
    </div>
  </div>

  <style>
    @keyframes popIn {
      from {
        opacity: 0;
        transform: scale(.93)
      }

      to {
        opacity: 1;
        transform: scale(1)
      }
    }
  </style>

  <script src="workshop.js"></script>

  <?php if ($successMsg): ?>
    <script>
      window.addEventListener('load', function() {
        showToast(<?= json_encode($successMsg) ?>, '👋');
      });

      function toggleSidebar() {
        document.querySelector('.sidebar').classList.toggle('active');
        document.querySelector('.sidebar-overlay').classList.toggle('active');
      }

      function closeSidebar() {
        document.querySelector('.sidebar').classList.remove('active');
        document.querySelector('.sidebar-overlay').classList.remove('active');
      }


      document.querySelectorAll('.sb-item').forEach(function(item) {
        item.addEventListener('click', function() {
          if (window.innerWidth <= 768) {
            closeSidebar();
          }
        });
      });
    </script>
  <?php endif; ?>
</body>

</html>