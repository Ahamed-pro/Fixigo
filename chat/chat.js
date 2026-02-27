
const canvas = document.getElementById('dots'), ctx = canvas.getContext('2d');
let W, H, dots = [];
function rsz() { W = canvas.width = window.innerWidth; H = canvas.height = window.innerHeight; }
function mkDot() { return { x: Math.random()*W, y: Math.random()*H, vx: (Math.random()-.5)*.4, vy: (Math.random()-.5)*.4, r: Math.random()*1.5+.5, a: Math.random()*.3+.1 }; }
function initDots() { dots = []; for (let i = 0; i < Math.min(Math.floor(W*H/16000), 70); i++) dots.push(mkDot()); }
function drawDots() {
  ctx.clearRect(0,0,W,H);
  dots.forEach(d => { d.x+=d.vx; d.y+=d.vy; if(d.x<0||d.x>W)d.vx*=-1; if(d.y<0||d.y>H)d.vy*=-1; ctx.save(); ctx.globalAlpha=d.a; ctx.beginPath(); ctx.arc(d.x,d.y,d.r,0,Math.PI*2); ctx.fillStyle='#FF5C1A'; ctx.fill(); ctx.restore(); });
  requestAnimationFrame(drawDots);
}
rsz(); initDots(); drawDots();
window.addEventListener('resize', () => { rsz(); initDots(); });

// ── STATE ───────────────────────────────
let activeConvId  = null;   // currently open conversation id
let lastMsgId     = 0;      // last message id received (for polling)
let pollTimer     = null;   // setInterval handle
let allConvs      = [];     // raw conversations from server
let lastDates     = {};     // track date seps per conv

// ── LOAD CONVERSATIONS ───────────────────
function loadConvList() {
  fetch('../backendchat/chat_list.php')
    .then(r => r.json())
    .then(d => {
      if (!d.success) return;
      allConvs = d.conversations || [];
      renderConvList(allConvs);
    })
    .catch(() => {});
}

function renderConvList(convs) {
  const list = document.getElementById('conv-list');
  if (!convs.length) {
    list.innerHTML = '<div class="no-convs">No conversations yet.</div>';
    return;
  }
  list.innerHTML = convs.map(c => {
    const unread    = parseInt(c.unread) || 0;
    const isActive  = c.id == activeConvId ? 'active' : '';
    const isUnread  = unread > 0 ? 'unread' : '';
    const initial   = c.other_name ? c.other_name.charAt(0).toUpperCase() : '?';
    const avClass   = MY_TYPE === 'workshop' ? 'user-av' : '';
    const preview   = c.last_message ? escHtml(c.last_message.substring(0,40)) + (c.last_message.length > 40 ? '…' : '') : 'Start chatting…';
    const time      = c.last_at || '';
    const badge     = unread > 0 ? `<div class="conv-item-badge">${unread}</div>` : '';

    return `
      <div class="conv-item ${isActive} ${isUnread}" 
           data-id="${c.id}" 
           data-othername="${escHtml(c.other_name)}"
           data-name="${escHtml(c.other_name).toLowerCase()}" 
           onclick="openConvById(this)">
        <div class="conv-item-av ${avClass}">${initial}</div>
        <div class="conv-item-body">
          <div class="conv-item-name">${escHtml(c.other_name)}</div>
          <div class="conv-item-preview">${preview}</div>
        </div>
        <div class="conv-item-meta">
          <div class="conv-item-time">${time.split('·')[1]?.trim() || time}</div>
          ${badge}
        </div>
      </div>`;
  }).join('');
}

function filterConvs(q) {
  q = q.toLowerCase();
  const filtered = allConvs.filter(c => !q || c.other_name.toLowerCase().includes(q));
  renderConvList(filtered);
}


function openConvById(el) {
  const convId    = parseInt(el.dataset.id);
  const otherName = el.dataset.othername || '?';

  
  document.querySelectorAll('.conv-item').forEach(e => e.classList.remove('active'));
  el.classList.add('active');


  document.getElementById('conv-panel').classList.add('slide-out');


  el.style.opacity = '0.6';

  const data = new FormData();
  data.append('conversation_id', convId);
  fetch('../backendchat/chat_open.php', { method: 'POST', body: data })
    .then(r => r.json())
    .then(d => {
      el.style.opacity = '1';
      if (!d.success) { alert('Error: ' + (d.error || 'Could not open chat.')); return; }
      activateChat(d.conversation_id, d.other_name, d.messages, d.my_type, d.my_id);
    })
    .catch(err => { el.style.opacity = '1'; alert('Network error.'); console.error(err); });
}


function openConvByWorkshop(el) {
  const workshopId = parseInt(el.dataset.id);
  const wsName     = el.dataset.wsname || 'Workshop';

  hideNewChatModal();


  el.style.opacity = '0.5';

  const data = new FormData();
  data.append('workshop_id', workshopId);
  fetch('../backendchat/chat_open.php', { method: 'POST', body: data })
    .then(r => r.json())
    .then(d => {
      el.style.opacity = '1';
      if (!d.success) { alert('Error: ' + (d.error || 'Could not open chat.')); return; }
      // Add to local conv list if brand new
      if (!allConvs.find(c => c.id == d.conversation_id)) {
        allConvs.unshift({ id: d.conversation_id, other_name: d.other_name, unread: 0, last_message: '', last_at: '' });
        renderConvList(allConvs);
      }
      activateChat(d.conversation_id, d.other_name, d.messages, d.my_type, d.my_id);
      document.getElementById('conv-panel').classList.add('slide-out');
    })
    .catch(err => {
      el.style.opacity = '1';
      alert('Network error — could not open chat.');
      console.error(err);
    });
}


function activateChat(convId, otherName, messages, myType, myId) {
  activeConvId = convId;
  lastMsgId    = 0;


  document.getElementById('chat-empty').style.display   = 'none';
  document.getElementById('chat-window').style.display  = 'flex';
  document.getElementById('chat-window').style.flexDirection = 'column';


  document.getElementById('ch-name').textContent   = otherName;
  document.getElementById('ch-avatar').textContent = otherName.charAt(0).toUpperCase();

  
  const area = document.getElementById('messages-area');
  area.innerHTML = '';
  lastDates[convId] = null;
  messages.forEach(m => appendMsg(m, myType, myId));
  scrollToBottom();


  const el = document.querySelector(`.conv-item[data-id="${convId}"]`);
  if (el) {
    el.classList.remove('unread');
    const badge = el.querySelector('.conv-item-badge');
    if (badge) badge.remove();
  }

  // Start polling
  if (pollTimer) clearInterval(pollTimer);
  pollTimer = setInterval(() => pollMessages(), 2000);
}


function pollMessages() {
  if (!activeConvId) return;
  fetch(`../backendchat/chat_poll.php?conversation_id=${activeConvId}&last_id=${lastMsgId}`)
    .then(r => r.json())
    .then(d => {
      if (!d.success || !d.messages.length) return;
      d.messages.forEach(m => {
        if (m.id > lastMsgId) {
          appendMsg(m, d.my_type, MY_ID);
          lastMsgId = m.id;
          // Update conv preview
          const convEl = document.querySelector(`.conv-item[data-id="${activeConvId}"] .conv-item-preview`);
          if (convEl) convEl.textContent = m.message.substring(0, 40) + (m.message.length > 40 ? '…' : '');
        }
      });
      scrollToBottom();
    })
    .catch(() => {});
}


function appendMsg(m, myType, myId) {
  const area   = document.getElementById('messages-area');
  const isMine = (m.sender_type === myType);


  if (m.date_fmt && lastDates[activeConvId] !== m.date_fmt) {
    lastDates[activeConvId] = m.date_fmt;
    const sep = document.createElement('div');
    sep.className = 'date-sep';
    sep.textContent = m.date_fmt;
    area.appendChild(sep);
  }

  const initial  = isMine ? MY_NAME_INITIAL : (document.getElementById('ch-name').textContent.charAt(0).toUpperCase());
  const tick     = isMine ? `<span class="tick ${m.is_read ? 'read' : ''}">✓✓</span>` : '';
  const rowClass = isMine ? 'mine' : 'theirs';

  const row = document.createElement('div');
  row.className = `msg-row ${rowClass}`;
  row.dataset.id = m.id;
  row.innerHTML = `
    <div class="msg-av">${initial}</div>
    <div class="msg-content">
      <div class="msg-bubble">${escHtml(m.message)}</div>
      <div class="msg-meta">${tick}<span>${m.time_fmt}</span></div>
    </div>`;
  area.appendChild(row);

  if (m.id > lastMsgId) lastMsgId = m.id;
}

// ── SEND MESSAGE ──
function sendMsg() {
  const input = document.getElementById('msg-input');
  const msg   = input.value.trim();
  if (!msg || !activeConvId) return;

  const btn = document.getElementById('send-btn');
  btn.disabled = true;
  input.value  = '';

  const data = new FormData();
  data.append('conversation_id', activeConvId);
  data.append('message', msg);

  fetch('../backendchat/chat_send.php', { method: 'POST', body: data })
    .then(r => r.json())
    .then(d => {
      btn.disabled = false;
      if (d.success) {
        appendMsg({
          id: d.message_id, sender_type: MY_TYPE, sender_id: MY_ID,
          message: msg, is_read: 0,
          time_fmt: d.time, date_fmt: todayLabel()
        }, MY_TYPE, MY_ID);
        scrollToBottom();

        const conv = allConvs.find(c => c.id == activeConvId);
        if (conv) conv.last_message = msg;
        const prevEl = document.querySelector(`.conv-item[data-id="${activeConvId}"] .conv-item-preview`);
        if (prevEl) prevEl.textContent = msg.substring(0, 40) + (msg.length > 40 ? '…' : '');
      }
    })
    .catch(() => { btn.disabled = false; });
}


function backToList() {
  document.getElementById('conv-panel').classList.remove('slide-out');
  if (pollTimer) { clearInterval(pollTimer); pollTimer = null; }
  activeConvId = null;
}


function showNewChatModal() {
  document.getElementById('overlay').classList.add('show');
  document.getElementById('new-chat-modal').classList.add('show');
  document.getElementById('ws-search').value = '';
  filterWs('');
}
function hideNewChatModal() {
  document.getElementById('overlay').classList.remove('show');
  document.getElementById('new-chat-modal').classList.remove('show');
}
function filterWs(q) {
  q = q.toLowerCase();
  document.querySelectorAll('.ws-item').forEach(el => {
    el.style.display = el.dataset.name.includes(q) ? '' : 'none';
  });
}


function scrollToBottom() {
  const area = document.getElementById('messages-area');
  area.scrollTop = area.scrollHeight;
}
function escHtml(s) {
  return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
function escJs(s) { return String(s).replace(/'/g,"\\'"); }
function todayLabel() {
  const d = new Date();
  return d.toLocaleDateString('en-GB', { day:'2-digit', month:'short', year:'numeric' });
}


const MY_NAME_INITIAL = (typeof MY_TYPE !== 'undefined') ? '?' : '?';



document.addEventListener('DOMContentLoaded', () => {
  loadConvList();


  setInterval(loadConvList, 10000);
});
