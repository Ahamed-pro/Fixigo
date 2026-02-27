
const c = document.getElementById('dots');

if (c) {
  const x = c.getContext('2d');
  let W, H, ds = [];

  function rsz() {
    W = c.width = window.innerWidth;
    H = c.height = window.innerHeight;
  }

  function mk() {
    return {
      x: Math.random() * W,
      y: Math.random() * H,
      vx: (Math.random() - 0.5) * 0.4,
      vy: (Math.random() - 0.5) * 0.4,
      r: Math.random() * 1.7 + 0.5,
      a: Math.random() * 0.4 + 0.1
    };
  }

  function init() {
    ds = [];
    for (let i = 0; i < Math.min(Math.floor(W * H / 14000), 80); i++) {
      ds.push(mk());
    }
  }

  function drw() {
    x.clearRect(0, 0, W, H);

    ds.forEach(d => {
      d.x += d.vx;
      d.y += d.vy;

      if (d.x < 0 || d.x > W) d.vx *= -1;
      if (d.y < 0 || d.y > H) d.vy *= -1;

      x.save();
      x.globalAlpha = d.a;
      x.beginPath();
      x.arc(d.x, d.y, d.r, 0, Math.PI * 2);
      x.fillStyle = '#FF5C1A';
      x.fill();
      x.restore();
    });

    requestAnimationFrame(drw);
  }

  rsz();
  init();
  drw();

  window.addEventListener('resize', () => {
    rsz();
    init();
  });
}


function showToast(msg, icon, err) {
  var t = document.getElementById('toast');
  if (!t) return;

  document.getElementById('t-icon').textContent = icon || '✅';
  document.getElementById('t-msg').textContent = msg;

  t.className = 'toast' + (err ? ' err' : '') + ' show';

  setTimeout(() => t.classList.remove('show'), 4000);
}


function scrollToRequests() {
  var el = document.getElementById('section-requests');
  if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
}


function filterCards(status, btn) {
  document.querySelectorAll('.ftab').forEach(t => t.classList.remove('active'));
  if (btn) btn.classList.add('active');

  document.querySelectorAll('.req-card').forEach(card => {
    var s = card.getAttribute('data-status');
    card.style.display = (status === 'all' || s === status) ? '' : 'none';
  });
}


function handleRequest(requestId, action) {
  var card = document.getElementById('card-' + requestId);
  var badge = document.getElementById('badge-' + requestId);
  var actions = document.getElementById('actions-' + requestId);

  if (actions) {
    actions.querySelectorAll('button').forEach(b => b.disabled = true);
  }

  var fd = new FormData();
  fd.append('request_id', requestId);
  fd.append('action', action);

  fetch('../backend/handle_request.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(data => {

      if (!data.success) {
        showToast(data.error || 'Something went wrong.', '❌', true);
        if (actions) actions.querySelectorAll('button').forEach(b => b.disabled = false);
        return;
      }

      var newStatus = data.new_status;

      if (card) card.setAttribute('data-status', newStatus);

      if (badge) {
        if (newStatus === 'accepted') {
          badge.className = 'rc-badge rb-accepted';
          badge.textContent = '✅ Accepted';
          card.classList.add('is-accepted');
        } else {
          badge.className = 'rc-badge rb-ignored';
          badge.textContent = '✗ Ignored';
          card.classList.add('is-ignored');
        }
      }

      if (actions) {
        actions.className = 'rc-handled';
        actions.innerHTML =
          newStatus === 'accepted'
            ? '✅ You accepted this request'
            : '✗ You ignored this request';
      }

      var pendEl = document.getElementById('ws-pending');
      var acceptEl = document.getElementById('ws-accepted');
      var ignoreEl = document.getElementById('ws-ignored');

      if (pendEl)
        pendEl.textContent = Math.max(0, parseInt(pendEl.textContent) - 1);

      if (newStatus === 'accepted' && acceptEl)
        acceptEl.textContent = parseInt(acceptEl.textContent) + 1;

      if (newStatus === 'ignored' && ignoreEl)
        ignoreEl.textContent = parseInt(ignoreEl.textContent) + 1;

      showToast(
        newStatus === 'accepted'
          ? 'Request accepted! The customer will be notified.'
          : 'Request ignored.',
        newStatus === 'accepted' ? '✅' : '✗',
        newStatus === 'ignored'
      );
    })
    .catch(() => {
      showToast('Network error. Please try again.', '❌', true);
      if (actions) actions.querySelectorAll('button').forEach(b => b.disabled = false);
    });
}

function openModal(id) {
  var m = document.getElementById(id);
  if (m) m.style.display = 'flex';
}

function closeModal(id) {
  var m = document.getElementById(id);
  if (m) m.style.display = 'none';
}

['modal-edit', 'modal-payment'].forEach(function (id) {
  var m = document.getElementById(id);
  if (m) {
    m.addEventListener('click', function (e) {
      if (e.target === m) closeModal(id);
    });
  }
});


function saveProfile() {
  var data = new URLSearchParams();
  data.append('workshop_name', document.getElementById('ef-name').value.trim());
  data.append('address', document.getElementById('ef-address').value.trim());
  data.append('district', document.getElementById('ef-district').value.trim());
  data.append('specialisation', document.getElementById('ef-spec').value.trim());
  data.append('business_reg', document.getElementById('ef-reg').value.trim());

  fetch('update_workshop.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: data.toString()
  })
    .then(r => r.json())
    .then(d => {
      if (d.success) {
        closeModal('modal-edit');
        showToast('Profile updated successfully!', '⚙️');

        var heroName = document.querySelector('.ws-hero h1 span');
        if (heroName)
          heroName.textContent = document.getElementById('ef-name').value.trim();
      } else {
        showToast('Error: ' + (d.error || 'Unknown error'), '❌', true);
      }
    })
    .catch(() => {
      showToast('Network error. Try again.', '❌', true);
    });
}