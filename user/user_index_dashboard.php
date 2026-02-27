<?php

session_start();
if (!isset($_SESSION["user_id"])) {
  header("Location: ../auth.php?error=Please+login+first.");
  exit;
}
if ($_SESSION["account_type"] === "workshop") {
  header("Location: ../workshop/workshop_index_dashboard.php");
  exit;
}

include "../config/db.php";

$user_id    = (int) $_SESSION["user_id"];
$user_name  = $_SESSION["user_name"];
$first_name = explode(" ", $user_name)[0];
$successMsg = isset($_GET["success"]) ? htmlspecialchars($_GET["success"]) : "";


$workshops = [];
$wres = $conn->query("
    SELECT w.id, w.workshop_name, w.district, w.specialisation
    FROM workshops w JOIN users u ON w.user_id = u.id
    WHERE u.account_type='workshop' AND w.payment_status='paid'
    ORDER BY w.workshop_name ASC
");
if ($wres) while ($r = $wres->fetch_assoc()) $workshops[] = $r;
$workshop_count = count($workshops);


$requests = [];
$rq = $conn->prepare("
    SELECT id, workshop_name, service_type, location, status,
           DATE_FORMAT(created_at,'%d %b %Y') AS date_fmt
    FROM service_requests
    WHERE user_id = ?
    ORDER BY created_at DESC
");
$rq->bind_param("i", $user_id);
$rq->execute();
$rres = $rq->get_result();
while ($r = $rres->fetch_assoc()) $requests[] = $r;

$total_req    = count($requests);
$accepted_req = count(array_filter($requests, fn($r) => $r['status'] === 'accepted'));
$pending_req  = count(array_filter($requests, fn($r) => $r['status'] === 'pending'));
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Fixigo – My Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='20' fill='%23FF5C1A'/><text y='.9em' font-size='70' x='12'>🔧</text></svg>" />
  <link rel="stylesheet" href="user.css">
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
      <span class="sb-role">🚗 Vehicle Owner</span>
    </div>
    <nav class="sb-nav">
      <div class="sb-label">Main</div>
      <a class="sb-item active" href="#"><span class="ic">🏠</span>Dashboard</a>
      <a class="sb-item" href="../index.php#workshops"><span class="ic">🔍</span>Find Workshops</a>
      <a class="sb-item" href="#" onclick="event.preventDefault();openModal('modal-request')"><span class="ic">📋</span>New Request</a>
      <div class="sb-label">Account</div>
      <a class="sb-item" href="#" onclick="event.preventDefault();openModal('modal-vehicle')"><span class="ic">🚗</span>My Vehicles</a>
      <a class="sb-item" href="#"><span class="ic">⚙️</span>Settings</a>
    </nav>
    <div class="sb-bottom">
      <a href="../backend/logout.php" class="sb-item"><span class="ic">🚪</span>Logout</a>
    </div>
  </aside>

  <div class="main">
    <div class="topbar">
      <h2>My Dashboard</h2>
      <button class="btn btn-gh" onclick="toggleSidebar()">☰</button>
      <div class="sidebar-overlay" onclick="closeSidebar()"></div>
      <div class="topbar-r">
        <a href="../index.php" class="btn btn-gh">← Back to Site</a>
        <button class="btn" onclick="openModal('modal-sos')" style="background:rgba(255,76,76,.1);color:#FF4C4C;border:1px solid rgba(255,76,76,.25)">🚨 SOS</button>
        <a href="../backend/logout.php" class="btn btn-gh">Logout</a>
      </div>
    </div>

    <div class="content">

      <div class="welcome">
        <div class="tag">Vehicle Owner Dashboard</div>
        <h1>Hey, <span><?= htmlspecialchars($first_name) ?></span> 👋</h1>
        <p>Find workshops, track service requests, and manage your vehicles — all in one place.</p>
        <div class="welcome-btns">
          <a href="../index.php#workshops" class="btn btn-or">🔍 Find a Workshop</a>
          <button class="btn" onclick="openModal('modal-request')" style="background:rgba(255,92,26,.1);color:var(--orange);border:1px solid rgba(255,92,26,.3)">📋 Request Service</button>
          <button class="btn" onclick="openModal('modal-sos')" style="background:rgba(255,76,76,.08);color:#FF4C4C;border:1px solid rgba(255,76,76,.25)">🚨 Emergency SOS</button>
        </div>
      </div>


      <div class="stats">
        <div class="stat">
          <div class="stat-top">
            <div class="si si-or">📋</div><span class="sbadge sbadge-or">Total</span>
          </div>
          <div class="stat-n" id="stat-total"><?= $total_req ?></div>
          <div class="stat-l">Service Requests</div>
        </div>
        <div class="stat">
          <div class="stat-top">
            <div class="si si-gr">✅</div><span class="sbadge sbadge-gr">Done</span>
          </div>
          <div class="stat-n" id="stat-accepted"><?= $accepted_req ?></div>
          <div class="stat-l">Accepted</div>
        </div>
        <div class="stat">
          <div class="stat-top">
            <div class="si si-bl">⏳</div><span class="sbadge sbadge-or">Waiting</span>
          </div>
          <div class="stat-n" id="stat-pending"><?= $pending_req ?></div>
          <div class="stat-l">Pending</div>
        </div>
        <div class="stat">
          <div class="stat-top">
            <div class="si si-pu">🔧</div><span class="sbadge sbadge-gr">Active</span>
          </div>
          <div class="stat-n"><?= $workshop_count ?></div>
          <div class="stat-l">Workshops Available</div>
        </div>
      </div>


      <div class="g2">
        <div>
          <div class="sh">
            <h3>Quick Actions</h3>
          </div>
          <div class="ac-grid">
            <a class="ac" href="../index.php#workshops">
              <div class="ai">🔍</div>
              <div class="at">Find Workshop</div>
              <div class="ad">Search by district or service</div>
            </a>
            <a class="ac" href="#" onclick="event.preventDefault();openModal('modal-request')">
              <div class="ai">📋</div>
              <div class="at">Request Service</div>
              <div class="ad">Send to a workshop</div>
            </a>
            <a class="ac" href="#" onclick="event.preventDefault();openModal('modal-vehicle')">
              <div class="ai">🚗</div>
              <div class="at">Add Vehicle</div>
              <div class="ad">Save car details</div>
            </a>
            <a class="ac" href="#" onclick="event.preventDefault();openModal('modal-sos')">
              <div class="ai">🚨</div>
              <div class="at">Emergency SOS</div>
              <div class="ad">Get roadside help fast</div>
            </a>
          </div>
        </div>
        <div>
          <div class="sh">
            <h3>Available Workshops</h3><a href="../index.php#workshops">View all →</a>
          </div>
          <div class="cbox">
            <div class="ws-list">
              <?php if ($workshops): $icons = ['🏪', '⚙️', '🔧', '🛠️', '🚗']; ?>
                <?php foreach (array_slice($workshops, 0, 5) as $i => $row): ?>
                  <div class="ws-item">
                    <div class="wsa"><?= $icons[$i % 5] ?></div>
                    <div class="ws-info">
                      <div class="wn"><?= htmlspecialchars($row['workshop_name']) ?></div>
                      <div class="wm"><?= htmlspecialchars($row['district']) ?> · <?= htmlspecialchars($row['specialisation']) ?></div>
                    </div>
                    <span class="wst">Open</span>
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
                <div class="empty" style="padding:30px">
                  <div class="ei">🏪</div>
                  <p>No verified workshops yet.</p>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      <div class="sh" style="margin-top:4px">
        <h3>My Service Requests</h3>
        <button class="btn btn-or" onclick="openModal('modal-request')" style="font-size:12px;padding:6px 16px">+ New Request</button>
      </div>
      <div class="cbox" id="req-wrap">
        <?php if ($requests): ?>
          <table style="width:100%;border-collapse:collapse" id="req-table">
            <thead>
              <tr>
                <?php foreach (['Workshop', 'Service', 'Location', 'Status', 'Date'] as $h): ?>
                  <th style="padding:11px 16px;font-size:11px;text-transform:uppercase;letter-spacing:.8px;color:var(--text-dim);text-align:left;border-bottom:1px solid rgba(255,255,255,.07);font-weight:500"><?= $h ?></th>
                <?php endforeach; ?>
              </tr>
            </thead>
            <tbody id="req-tbody">
              <?php foreach ($requests as $r):
                [$bg, $fg, $lbl] = match ($r['status']) {
                  'accepted' => ['rgba(61,219,122,.12)', '#3DDB7A', '✅ Accepted'],
                  'ignored'  => ['rgba(255,76,76,.1)', '#FF4C4C', '✗ Ignored'],
                  default    => ['rgba(255,92,26,.1)', '#FF5C1A', '⏳ Pending'],
                };
              ?>
                <tr style="border-bottom:1px solid rgba(255,255,255,.03)">
                  <td style="padding:13px 16px;font-size:13px;font-weight:600"><?= htmlspecialchars($r['workshop_name']) ?></td>
                  <td style="padding:13px 16px;font-size:13px"><?= htmlspecialchars($r['service_type']) ?></td>
                  <td style="padding:13px 16px;font-size:12px;color:var(--text-muted)"><?= htmlspecialchars($r['location'] ?: '—') ?></td>
                  <td style="padding:13px 16px"><span style="font-size:11px;padding:3px 11px;border-radius:50px;background:<?= $bg ?>;color:<?= $fg ?>"><?= $lbl ?></span></td>
                  <td style="padding:13px 16px;font-size:12px;color:var(--text-muted)"><?= $r['date_fmt'] ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <div class="empty" id="req-empty">
            <div class="ei">📋</div>
            <p>No requests yet. Hit <strong>New Request</strong> to send your first one!</p>
          </div>
        <?php endif; ?>
      </div>


      <div class="sh" style="margin-top:28px">
        <h3>My Vehicles</h3>
        <a href="#" onclick="event.preventDefault();openModal('modal-vehicle')">+ Add Vehicle →</a>
      </div>
      <div class="cbox" id="vehicles-preview">
        <div class="empty">
          <div class="ei">🚗</div>
          <p>No vehicles saved yet. Add your car for faster bookings!</p>
        </div>
      </div>

    </div>
  </div>


  <div class="modal-overlay" id="modal-request" onclick="if(event.target===this)closeModal('modal-request')">
    <div class="modal">
      <button class="close-btn" onclick="closeModal('modal-request')">✕</button>
      <h2>Request Service 🔧</h2>
      <p>Fill in your details. The workshop will be notified immediately.</p>
      <form id="form-request" onsubmit="doSubmitRequest(event)">
        <label>Your Name</label>
        <input type="text" id="req-name" value="<?= htmlspecialchars($user_name) ?>" readonly style="opacity:.7">

        <label>Phone Number *</label>
        <input type="tel" id="req-phone" placeholder="+94 77 000 0000" required>

        <label>Your Location <small style="text-transform:none;letter-spacing:0;color:var(--orange)">(tap 📡 for GPS)</small></label>
        <div class="location-row">
          <input type="text" id="req-location" placeholder="Tap 📡 or type your address">
          <button type="button" class="gps-btn" onclick="getGPSLocation('req-location','req-gps-status')">📡</button>
        </div>
        <small id="req-gps-status" class="gps-status"></small>

        <label>Service Type *</label>
        <select id="req-service" required>
          <option value="">Select service…</option>
          <option>Engine Repair</option>
          <option>Tyre Change</option>
          <option>Oil Change</option>
          <option>AC Repair</option>
          <option>Brake Service</option>
          <option>Battery Replacement</option>
          <option>Electrical Issue</option>
          <option>Body Work</option>
          <option>Wheel Alignment</option>
          <option>Other</option>
        </select>

        <label>Select Workshop *</label>
        <select id="req-workshop-id" required>
          <option value="">Choose a workshop…</option>
          <?php foreach ($workshops as $row): ?>
            <option value="<?= (int)$row['id'] ?>">
              <?= htmlspecialchars($row['workshop_name']) ?> — <?= htmlspecialchars($row['district']) ?> (<?= htmlspecialchars($row['specialisation']) ?>)
            </option>
          <?php endforeach; ?>
          <?php if (!$workshops): ?><option value="" disabled>No verified workshops yet</option><?php endif; ?>
        </select>

        <label>Describe the Issue</label>
        <textarea id="req-desc" placeholder="What's happening with your vehicle?"></textarea>

        <div class="modal-btns">
          <button type="button" class="btn-modal-cancel" onclick="closeModal('modal-request')">Cancel</button>
          <button type="submit" class="btn-modal-submit" id="req-submit-btn">Send Request 🔧</button>
        </div>
      </form>
    </div>
  </div>


  <div class="modal-overlay" id="modal-sos" onclick="if(event.target===this)closeModal('modal-sos')">
    <div class="modal" style="border-color:rgba(255,32,32,.3)">
      <button class="close-btn" onclick="closeModal('modal-sos')">✕</button>
      <h2 style="color:#FF4C4C">🚨 Emergency SOS</h2>
      <p>We'll alert the nearest available mechanic immediately.</p>
      <form onsubmit="submitSOS(event)">
        <label>Your Name *</label><input type="text" id="sos-name" placeholder="Full name" required>
        <label>Phone Number *</label><input type="tel" id="sos-phone" placeholder="+94 77 000 0000" required>
        <label>Your Location * <small style="text-transform:none;letter-spacing:0;color:var(--orange)">(GPS strongly recommended)</small></label>
        <div class="location-row">
          <input type="text" id="sos-location" placeholder="Tap 📡 to share GPS location">
          <button type="button" class="gps-btn" onclick="getGPSLocation('sos-location','sos-gps-status')">📡</button>
        </div>
        <small id="sos-gps-status" class="gps-status"></small>
        <label>Emergency Type *</label>
        <select id="sos-type" required>
          <option value="">What happened?</option>
          <option>Flat Tyre</option>
          <option>Dead Battery / Won't Start</option>
          <option>Engine Overheating</option>
          <option>Accident / Breakdown</option>
          <option>Fuel Empty</option>
          <option>Electrical Failure</option>
          <option>Brake Failure</option>
          <option>Other</option>
        </select>
        <div class="modal-btns">
          <button type="button" class="btn-modal-cancel" onclick="closeModal('modal-sos')">Cancel</button>
          <button type="submit" class="btn-sos" id="sos-submit-btn">🚨 Send SOS Now</button>
        </div>
      </form>
    </div>
  </div>


  <div class="modal-overlay" id="modal-vehicle" onclick="if(event.target===this)closeModal('modal-vehicle')">
    <div class="modal">
      <button class="close-btn" onclick="closeModal('modal-vehicle')">✕</button>
      <h2>🚗 Add Vehicle</h2>
      <p>Save your vehicle details for faster service bookings.</p>
      <form onsubmit="addVehicle(event)">
        <div class="form-row-2">
          <div><label>Make (Brand) *</label><input type="text" id="v-make" placeholder="Toyota, Honda…" required></div>
          <div><label>Model *</label><input type="text" id="v-model" placeholder="Axio, Civic…" required></div>
        </div>
        <div class="form-row-2">
          <div><label>Year *</label><input type="number" id="v-year" placeholder="2020" min="1990" max="2025" required></div>
          <div><label>Colour</label><input type="text" id="v-color" placeholder="Silver, Black…"></div>
        </div>
        <label>Licence Plate *</label><input type="text" id="v-plate" placeholder="ABC-1234" required>
        <label>Fuel Type</label>
        <select id="v-fuel">
          <option value="">Select…</option>
          <option>Petrol</option>
          <option>Diesel</option>
          <option>Hybrid</option>
          <option>Electric</option>
        </select>
        <div class="modal-btns">
          <button type="button" class="btn-modal-cancel" onclick="closeModal('modal-vehicle')">Cancel</button>
          <button type="submit" class="btn-modal-submit">🚗 Save Vehicle</button>
        </div>
      </form>
    </div>
  </div>

  <div class="toast" id="toast"><span id="t-icon">✅</span><span id="t-msg"></span></div>

  <script src="user.js"></script>
  <script>
    function doSubmitRequest(event) {
      event.preventDefault();

      var phone = document.getElementById('req-phone').value.trim();
      var service = document.getElementById('req-service').value;
      var wsId = document.getElementById('req-workshop-id').value;
      var loc = document.getElementById('req-location').value.trim();
      var desc = document.getElementById('req-desc').value.trim();

      if (!phone) {
        showToast('Please enter your phone number.', '⚠️');
        return;
      }
      if (!service) {
        showToast('Please select a service type.', '⚠️');
        return;
      }
      if (!wsId) {
        showToast('Please select a workshop.', '⚠️');
        return;
      }

      var btn = document.getElementById('req-submit-btn');
      btn.textContent = '⏳ Sending…';
      btn.disabled = true;

      var fd = new FormData();
      fd.append('phone', phone);
      fd.append('service_type', service);
      fd.append('workshop_id', wsId);
      fd.append('location', loc);
      fd.append('description', desc);

      fetch('../backend/submit_request.php', {
          method: 'POST',
          body: fd
        })
        .then(function(r) {
          return r.json();
        })
        .then(function(data) {
          btn.textContent = 'Send Request 🔧';
          btn.disabled = false;

          if (!data.success) {
            showToast(data.error || 'Something went wrong.', '❌');
            return;
          }

          closeModal('modal-request');
          document.getElementById('form-request').reset();
          document.getElementById('req-gps-status').textContent = '';


          var empty = document.getElementById('req-empty');
          if (empty) empty.remove();

          var wrap = document.getElementById('req-wrap');
          var table = document.getElementById('req-table');
          if (!table) {
            wrap.innerHTML =
              '<table style="width:100%;border-collapse:collapse" id="req-table"><thead><tr>' + ['Workshop', 'Service', 'Location', 'Status', 'Date'].map(function(h) {
                return '<th style="padding:11px 16px;font-size:11px;text-transform:uppercase;letter-spacing:.8px;color:var(--text-dim);text-align:left;border-bottom:1px solid rgba(255,255,255,.07);font-weight:500">' + h + '</th>';
              }).join('') +
              '</tr></thead><tbody id="req-tbody"></tbody></table>';
          }
          var tbody = document.getElementById('req-tbody') || document.querySelector('#req-table tbody');
          var tr = document.createElement('tr');
          tr.style.borderBottom = '1px solid rgba(255,255,255,.03)';
          tr.innerHTML =
            '<td style="padding:13px 16px;font-size:13px;font-weight:600">' + esc(data.workshop_name) + '</td>' +
            '<td style="padding:13px 16px;font-size:13px">' + esc(data.service_type) + '</td>' +
            '<td style="padding:13px 16px;font-size:12px;color:var(--text-muted)">' + esc(data.location || '—') + '</td>' +
            '<td style="padding:13px 16px"><span style="font-size:11px;padding:3px 11px;border-radius:50px;background:rgba(255,92,26,.1);color:#FF5C1A">⏳ Pending</span></td>' +
            '<td style="padding:13px 16px;font-size:12px;color:var(--text-muted)">' + esc(data.created_at) + '</td>';
          tbody.insertBefore(tr, tbody.firstChild);


          var tEl = document.getElementById('stat-total');
          var pEl = document.getElementById('stat-pending');
          if (tEl) tEl.textContent = parseInt(tEl.textContent || 0) + 1;
          if (pEl) pEl.textContent = parseInt(pEl.textContent || 0) + 1;

          showToast('Request sent to ' + data.workshop_name + '! 🔧', '✅');
        })
        .catch(function() {
          btn.textContent = 'Send Request 🔧';
          btn.disabled = false;
          showToast('Network error. Please try again.', '❌');
        });
    }

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

    function esc(s) {
      var d = document.createElement('div');
      d.textContent = s || '';
      return d.innerHTML;
    }

    <?php if ($successMsg): ?>
      window.addEventListener('load', function() {
        showToast(<?= json_encode($successMsg) ?>, '👋');
      });
    <?php endif; ?>
  </script>
</body>

</html>