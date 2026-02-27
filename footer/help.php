<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Help Center — Fixigo</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='20' fill='%23FF5C1A'/><text y='.9em' font-size='70' x='12'>🔧</text></svg>"/>
  <link rel="stylesheet" href="pages.css">
  <style>
  
    .help-search-wrap {
      max-width: 540px;
      margin: 0 auto 52px;
      position: relative;
    }
    .help-search {
      width: 100%;
      background: var(--dark3);
      border: 1px solid var(--border);
      border-radius: 50px;
      padding: 14px 52px 14px 22px;
      font-size: 15px;
      font-family: 'DM Sans', sans-serif;
      color: var(--text);
      outline: none;
      transition: border-color .2s;
    }
    .help-search:focus { border-color: rgba(255,92,26,.5); }
    .help-search::placeholder { color: var(--text-dim); }
    .search-icon {
      position: absolute;
      right: 18px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 18px;
      pointer-events: none;
    }

   
    .cat-tabs {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
      margin-bottom: 32px;
    }
    .cat-tab {
      padding: 7px 16px;
      border-radius: 50px;
      border: 1px solid var(--border);
      background: transparent;
      color: var(--text-muted);
      font-family: 'DM Sans', sans-serif;
      font-size: 13px;
      font-weight: 500;
      cursor: pointer;
      transition: .2s;
    }
    .cat-tab:hover { border-color: rgba(255,92,26,.3); color: var(--orange); }
    .cat-tab.active { background: rgba(255,92,26,.12); color: var(--orange); border-color: rgba(255,92,26,.35); }

    .faq-section { margin-bottom: 40px; display: none; }
    .faq-section.active { display: block; }
    .faq-section-title {
      font-family: 'Syne', sans-serif;
      font-size: 18px;
      font-weight: 800;
      margin-bottom: 14px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .faq-item {
      background: var(--dark3);
      border: 1px solid var(--border);
      border-radius: 14px;
      margin-bottom: 10px;
      overflow: hidden;
      transition: border-color .2s;
    }
    .faq-item:hover { border-color: rgba(255,92,26,.2); }
    .faq-question {
      width: 100%;
      background: none;
      border: none;
      padding: 18px 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      cursor: pointer;
      text-align: left;
      font-family: 'DM Sans', sans-serif;
      font-size: 14px;
      font-weight: 600;
      color: var(--text);
      gap: 12px;
    }
    .faq-question:hover { color: var(--orange); }
    .faq-chevron {
      font-size: 12px;
      color: var(--text-dim);
      flex-shrink: 0;
      transition: transform .25s;
    }
    .faq-item.open .faq-chevron { transform: rotate(180deg); color: var(--orange); }
    .faq-answer {
      max-height: 0;
      overflow: hidden;
      transition: max-height .3s ease, padding .2s;
      font-size: 13px;
      color: var(--text-muted);
      line-height: 1.65;
    }
    .faq-item.open .faq-answer { max-height: 400px; }
    .faq-answer-inner { padding: 0 20px 18px; }
    .faq-answer a { color: var(--orange); }

   
    .quick-links { display: grid; grid-template-columns: repeat(3,1fr); gap: 14px; margin-bottom: 52px; }
    .ql-card {
      background: var(--dark4);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 20px;
      text-decoration: none;
      display: block;
      transition: .25s;
      text-align: center;
    }
    .ql-card:hover { border-color: rgba(255,92,26,.3); transform: translateY(-3px); background: rgba(255,92,26,.04); }
    .ql-icon { font-size: 28px; margin-bottom: 10px; }
    .ql-title { font-family: 'Syne', sans-serif; font-size: 14px; font-weight: 800; color: var(--text); margin-bottom: 4px; }
    .ql-desc { font-size: 12px; color: var(--text-muted); }


    #no-results { display:none; text-align:center; padding:40px; color:var(--text-muted); }

    @media (max-width: 768px) {
      .quick-links { grid-template-columns: repeat(2,1fr); }
    }
    @media (max-width: 480px) {
      .quick-links { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>
<canvas id="dots"></canvas>


<nav class="nav">
  <a href="index.php" class="nav-logo"><div class="li">🔧</div><span>Fix<b>igo</b></span></a>
  <div class="nav-links">
    <a href="../index.php">Home</a>
    <a href="about.php">About</a>
    <a href="contact.php">Contact</a>
    <a href="help.php" class="active">Help Center</a>
    <a href="../auth.php" class="nav-cta">Get Started</a>
  </div>
  <button class="nav-menu-btn" onclick="toggleMenu()">☰</button>
</nav>
<div class="mobile-menu" id="mobile-menu">
  <a href="../index.php">🏠 Home</a>
  <a href="about.php">ℹ️ About</a>
  <a href="contact.php">📞 Contact</a>
  <a href="help.php">❓ Help Center</a>
  <a href="../auth.php" style="color:var(--orange);font-weight:700">→ Get Started</a>
</div>

<div class="page-wrap">


  <div class="page-hero">
    <div class="page-tag">❓ Help Center</div>
    <h1>How Can We <span>Help You?</span></h1>
    <p>Find answers to common questions about using Fixigo — from registration to emergency SOS.</p>
  </div>

 
  <div class="help-search-wrap">
    <input class="help-search" type="text" id="faq-search" placeholder="Search for help… e.g. 'how to book'" oninput="searchFAQ(this.value)">
    <span class="search-icon">🔍</span>
  </div>


  <div class="quick-links">
    <a href="#" class="ql-card" onclick="switchCat('account',this)">
      <div class="ql-icon">👤</div>
      <div class="ql-title">Account & Login</div>
      <div class="ql-desc">Registration, login, password reset</div>
    </a>
    <a href="#" class="ql-card" onclick="switchCat('booking',this)">
      <div class="ql-icon">📋</div>
      <div class="ql-title">Booking a Service</div>
      <div class="ql-desc">How to find workshops and request repairs</div>
    </a>
    <a href="#" class="ql-card" onclick="switchCat('emergency',this)">
      <div class="ql-icon">🚨</div>
      <div class="ql-title">Emergency SOS</div>
      <div class="ql-desc">Roadside emergency help</div>
    </a>
    <a href="#" class="ql-card" onclick="switchCat('payment',this)">
      <div class="ql-icon">💳</div>
      <div class="ql-title">Payments</div>
      <div class="ql-desc">Fees, payment status and billing</div>
    </a>
    <a href="#" class="ql-card" onclick="switchCat('workshop',this)">
      <div class="ql-icon">🏪</div>
      <div class="ql-title">Workshop Owners</div>
      <div class="ql-desc">Register and manage your workshop</div>
    </a>
    <a href="contact.php" class="ql-card">
      <div class="ql-icon">💬</div>
      <div class="ql-title">Contact Support</div>
      <div class="ql-desc">Still stuck? Talk to our team</div>
    </a>
  </div>

  
  <div class="cat-tabs">
    <button class="cat-tab active" onclick="switchCat('all',this)">All Topics</button>
    <button class="cat-tab" onclick="switchCat('account',this)">👤 Account</button>
    <button class="cat-tab" onclick="switchCat('booking',this)">📋 Booking</button>
    <button class="cat-tab" onclick="switchCat('emergency',this)">🚨 Emergency</button>
    <button class="cat-tab" onclick="switchCat('payment',this)">💳 Payment</button>
    <button class="cat-tab" onclick="switchCat('workshop',this)">🏪 Workshop</button>
  </div>

  
  <div id="no-results">
    <div style="font-size:40px;margin-bottom:12px">🤔</div>
    <div style="font-family:'Syne',sans-serif;font-size:18px;font-weight:800;margin-bottom:8px">No results found</div>
    <p>Try different keywords or <a href="contact.php" style="color:var(--orange)">contact our support team</a>.</p>
  </div>


  <div class="faq-section active" id="cat-account" data-cat="account">
    <div class="faq-section-title">👤 Account &amp; Login</div>

    <div class="faq-item">
      <button class="faq-question" onclick="toggleFAQ(this)">
        How do I create a Fixigo account?
        <span class="faq-chevron">▼</span>
      </button>
      <div class="faq-answer"><div class="faq-answer-inner">
        Go to <a href="auth.php">auth.php</a> and click <strong>Create Account</strong>. Choose your account type — <em>Vehicle Owner</em> (to find and book workshops) or <em>Workshop Owner</em> (to list your business). Fill in your name, email, phone and password, then submit. You'll receive a confirmation email and be redirected to your dashboard.
      </div></div>
    </div>

    <div class="faq-item">
      <button class="faq-question" onclick="toggleFAQ(this)">
        How do I log in?
        <span class="faq-chevron">▼</span>
      </button>
      <div class="faq-answer"><div class="faq-answer-inner">
        Visit <a href="auth.php">auth.php</a> and click <strong>Sign In</strong>. Enter your registered email and password, then click Login. You'll be automatically redirected to your dashboard based on your account type.
      </div></div>
    </div>

    <div class="faq-item">
      <button class="faq-question" onclick="toggleFAQ(this)">
        I forgot my password — what do I do?
        <span class="faq-chevron">▼</span>
      </button>
      <div class="faq-answer"><div class="faq-answer-inner">
        Contact our support team at <a href="mailto:support@fixigo.lk">support@fixigo.lk</a> with your registered email address and we'll help you reset your password. An automated reset feature is coming soon.
      </div></div>
    </div>

    <div class="faq-item">
      <button class="faq-question" onclick="toggleFAQ(this)">
        Can I change my account type after registering?
        <span class="faq-chevron">▼</span>
      </button>
      <div class="faq-answer"><div class="faq-answer-inner">
        Account types cannot be changed once set. If you need a different account type, please <a href="contact.php">contact support</a> and we'll help you set up a new account.
      </div></div>
    </div>

    <div class="faq-item">
      <button class="faq-question" onclick="toggleFAQ(this)">
        How do I update my profile information?
        <span class="faq-chevron">▼</span>
      </button>
      <div class="faq-answer"><div class="faq-answer-inner">
        Log in and go to your dashboard. Workshop owners can click <strong>Edit Profile</strong> from the Quick Actions panel or the sidebar to update workshop details. Vehicle owners can update their profile from account settings.
      </div></div>
    </div>
  </div>

  
  <div class="faq-section active" id="cat-booking" data-cat="booking">
    <div class="faq-section-title">📋 Booking a Service</div>

    <div class="faq-item">
      <button class="faq-question" onclick="toggleFAQ(this)">
        How do I find a workshop near me?
        <span class="faq-chevron">▼</span>
      </button>
      <div class="faq-answer"><div class="faq-answer-inner">
        Log in as a Vehicle Owner and go to your dashboard. Use the <strong>Find a Workshop</strong> feature to browse verified workshops by district and specialisation. You can filter by location, service type, and more.
      </div></div>
    </div>

    <div class="faq-item">
      <button class="faq-question" onclick="toggleFAQ(this)">
        How do I send a service request?
        <span class="faq-chevron">▼</span>
      </button>
      <div class="faq-answer"><div class="faq-answer-inner">
        From your dashboard, select a workshop and click <strong>Request Service</strong>. Fill in your vehicle details, describe the issue, and submit. The workshop will receive your request and respond — typically within a few minutes.
      </div></div>
    </div>

    <div class="faq-item">
      <button class="faq-question" onclick="toggleFAQ(this)">
        How do I track my service request?
        <span class="faq-chevron">▼</span>
      </button>
      <div class="faq-answer"><div class="faq-answer-inner">
        From your <strong>Vehicle Owner Dashboard</strong>, go to <em>My Requests</em>. Each request shows its current status:<br><br>
        • ⏳ <strong>Pending</strong> — waiting for the workshop to respond<br>
        • ✅ <strong>Accepted</strong> — the workshop confirmed your request<br>
        • ✗ <strong>Ignored</strong> — the workshop didn't respond; try another
      </div></div>
    </div>

    <div class="faq-item">
      <button class="faq-question" onclick="toggleFAQ(this)">
        What if a workshop ignores my request?
        <span class="faq-chevron">▼</span>
      </button>
      <div class="faq-answer"><div class="faq-answer-inner">
        If a workshop ignores your request, you can simply send a request to another workshop from your dashboard. You can also report unresponsive workshops to us via <a href="contact.php">contact support</a>.
      </div></div>
    </div>
  </div>

  <div class="faq-section active" id="cat-emergency" data-cat="emergency">
    <div class="faq-section-title">🚨 Emergency SOS</div>

    <div class="faq-item">
      <button class="faq-question" onclick="toggleFAQ(this)">
        How does the Emergency SOS feature work?
        <span class="faq-chevron">▼</span>
      </button>
      <div class="faq-answer"><div class="faq-answer-inner">
        The SOS feature instantly sends your GPS location, landmark, and emergency type to nearby workshops and our support team via SMS. Log in, go to your dashboard, and tap the red <strong>🚨 Emergency SOS</strong> button. Fill in the quick form and submit — help is alerted immediately.
      </div></div>
    </div>

    <div class="faq-item">
      <button class="faq-question" onclick="toggleFAQ(this)">
        Do I need to be logged in to use SOS?
        <span class="faq-chevron">▼</span>
      </button>
      <div class="faq-answer"><div class="faq-answer-inner">
        Yes, you need a Fixigo account to use the SOS feature so we can identify you and contact you back. Registration is free and takes under 2 minutes. For life-threatening emergencies, always call <strong>119</strong> first.
      </div></div>
    </div>

    <div class="faq-item">
      <button class="faq-question" onclick="toggleFAQ(this)">
        Is the SOS feature available 24/7?
        <span class="faq-chevron">▼</span>
      </button>
      <div class="faq-answer"><div class="faq-answer-inner">
        Yes. The SOS alert system runs 24/7. Nearby workshops and our team will receive your alert at any time of day or night. Response times may vary outside business hours.
      </div></div>
    </div>

    <div class="faq-item">
      <button class="faq-question" onclick="toggleFAQ(this)">
        What information does SOS send?
        <span class="faq-chevron">▼</span>
      </button>
      <div class="faq-answer"><div class="faq-answer-inner">
        Your SOS alert sends: your name, phone number, GPS coordinates, nearest landmark, and the type of emergency (breakdown, flat tyre, accident, etc.) to our support team and nearby registered workshops via SMS.
      </div></div>
    </div>
  </div>


  <div class="faq-section active" id="cat-payment" data-cat="payment">
    <div class="faq-section-title">💳 Payments</div>

    <div class="faq-item">
      <button class="faq-question" onclick="toggleFAQ(this)">
        Is Fixigo free to use for vehicle owners?
        <span class="faq-chevron">▼</span>
      </button>
      <div class="faq-answer"><div class="faq-answer-inner">
        Yes! Creating an account and finding workshops is completely free for vehicle owners. You only pay for the actual repair work directly with the workshop — Fixigo does not charge vehicle owners any service fees.
      </div></div>
    </div>

    <div class="faq-item">
      <button class="faq-question" onclick="toggleFAQ(this)">
        How much does it cost to list my workshop?
        <span class="faq-chevron">▼</span>
      </button>
      <div class="faq-answer"><div class="faq-answer-inner">
        Workshop owners pay a one-time listing fee to activate their profile on Fixigo. Payment is done securely via card from your dashboard. Contact us at <a href="mailto:support@fixigo.lk">support@fixigo.lk</a> for current pricing details.
      </div></div>
    </div>

    <div class="faq-item">
      <button class="faq-question" onclick="toggleFAQ(this)">
        My payment status shows Pending — what does that mean?
        <span class="faq-chevron">▼</span>
      </button>
      <div class="faq-answer"><div class="faq-answer-inner">
        A <strong>Pending</strong> status means your workshop listing fee payment has been submitted but hasn't been approved by our admin team yet. Once approved, your status changes to <strong>Paid & Active</strong> and your workshop becomes visible to customers. This usually takes under 24 hours.
      </div></div>
    </div>

    <div class="faq-item">
      <button class="faq-question" onclick="toggleFAQ(this)">
        Is my payment information secure?
        <span class="faq-chevron">▼</span>
      </button>
      <div class="faq-answer"><div class="faq-answer-inner">
        Yes. All payment information is encrypted and processed securely. We do not store your full card details on our servers. Your card number is masked in all records.
      </div></div>
    </div>
  </div>


  <div class="faq-section active" id="cat-workshop" data-cat="workshop">
    <div class="faq-section-title">🏪 Workshop Owners</div>

    <div class="faq-item">
      <button class="faq-question" onclick="toggleFAQ(this)">
        How do I register my workshop on Fixigo?
        <span class="faq-chevron">▼</span>
      </button>
      <div class="faq-answer"><div class="faq-answer-inner">
        Sign up at <a href="auth.php">auth.php</a> and select <strong>Workshop Owner</strong>. Fill in your workshop name, district, specialisation, address and business registration number. Complete the listing payment to submit for approval. Once our admin approves your listing, you'll be live!
      </div></div>
    </div>

    <div class="faq-item">
      <button class="faq-question" onclick="toggleFAQ(this)">
        How do I accept a service request?
        <span class="faq-chevron">▼</span>
      </button>
      <div class="faq-answer"><div class="faq-answer-inner">
        Log in to your Workshop Dashboard. New requests appear in the <strong>Service Requests</strong> section. Click <strong>✓ Accept</strong> to confirm the request or <strong>✗ Ignore</strong> to skip it. The customer will be notified of your response automatically.
      </div></div>
    </div>

    <div class="faq-item">
      <button class="faq-question" onclick="toggleFAQ(this)">
        How do I update my workshop details?
        <span class="faq-chevron">▼</span>
      </button>
      <div class="faq-answer"><div class="faq-answer-inner">
        Go to your Workshop Dashboard and click <strong>Quick Actions → Edit Profile</strong>, or use the <strong>⚙️ Workshop Profile</strong> sidebar link. Update your name, address, district, specialisation or business reg, then click <strong>Save Changes</strong>. Changes go live instantly.
      </div></div>
    </div>

    <div class="faq-item">
      <button class="faq-question" onclick="toggleFAQ(this)">
        Why is my workshop not showing to customers?
        <span class="faq-chevron">▼</span>
      </button>
      <div class="faq-answer"><div class="faq-answer-inner">
        Your workshop is only visible once your <strong>payment status is Paid &amp; Active</strong>. Check your Payment Status from the dashboard. If your status shows Pending, your listing fee may not have been processed yet. <a href="contact.php">Contact support</a> if it's been more than 24 hours.
      </div></div>
    </div>
  </div>

  <hr class="divider">


  <div style="text-align:center;padding:10px 0 20px">
    <div style="font-size:36px;margin-bottom:12px">💬</div>
    <div class="section-title" style="margin-bottom:8px">Still Need <span>Help?</span></div>
    <p style="color:var(--text-muted);font-size:14px;margin-bottom:24px">Our support team is available Mon–Sat, 8am–8pm</p>
    <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap">
      <a href="contact.php" class="btn btn-or">📧 Contact Support</a>
      <a href="mailto:support@fixigo.lk" class="btn btn-gh">support@fixigo.lk</a>
    </div>
  </div>

</div>


<footer class="footer">
  <a href="../index.php" class="footer-logo">🔧 Fix<b>igo</b></a>
  <p>© <?= date('Y') ?> Fixigo. All rights reserved.</p>
  <div class="footer-links">
    <a href="about.php">About</a>
    <a href="contact.php">Contact</a>
    <a href="help.php">Help</a>
    <a href="../auth.php">Login</a>
  </div>
</footer>

<script>

const canvas=document.getElementById('dots'),ctx=canvas.getContext('2d');let W,H,dots=[];
function rsz(){W=canvas.width=window.innerWidth;H=canvas.height=window.innerHeight}
function mkDot(){return{x:Math.random()*W,y:Math.random()*H,vx:(Math.random()-.5)*.4,vy:(Math.random()-.5)*.4,r:Math.random()*1.5+.5,a:Math.random()*.3+.1}}
function init(){dots=[];for(let i=0;i<Math.min(Math.floor(W*H/16000),70);i++)dots.push(mkDot())}
function draw(){ctx.clearRect(0,0,W,H);dots.forEach(d=>{d.x+=d.vx;d.y+=d.vy;if(d.x<0||d.x>W)d.vx*=-1;if(d.y<0||d.y>H)d.vy*=-1;ctx.save();ctx.globalAlpha=d.a;ctx.beginPath();ctx.arc(d.x,d.y,d.r,0,Math.PI*2);ctx.fillStyle='#FF5C1A';ctx.fill();ctx.restore()});requestAnimationFrame(draw)}
rsz();init();draw();window.addEventListener('resize',()=>{rsz();init()});

function toggleMenu(){document.getElementById('mobile-menu').classList.toggle('open')}


function toggleFAQ(btn) {
  const item = btn.closest('.faq-item');
  const isOpen = item.classList.contains('open');

  document.querySelectorAll('.faq-item.open').forEach(i => i.classList.remove('open'));
  if (!isOpen) item.classList.add('open');
}


function switchCat(cat, el) {

  document.querySelectorAll('.cat-tab').forEach(t => t.classList.remove('active'));
  if(el && el.classList.contains('cat-tab')) el.classList.add('active');


  document.querySelectorAll('.faq-section').forEach(s => {
    if (cat === 'all' || s.dataset.cat === cat) {
      s.classList.add('active');
    } else {
      s.classList.remove('active');
    }
  });

  document.getElementById('faq-search').value = '';
  document.getElementById('no-results').style.display = 'none';
  document.querySelectorAll('.faq-item').forEach(i => i.style.display = '');


  document.querySelector('.cat-tabs').scrollIntoView({behavior:'smooth', block:'start'});
  return false;
}

function searchFAQ(query) {
  const q = query.toLowerCase().trim();

  if (!q) {
    document.querySelectorAll('.faq-section').forEach(s => s.classList.add('active'));
    document.querySelectorAll('.faq-item').forEach(i => i.style.display = '');
    document.getElementById('no-results').style.display = 'none';
    return;
  }

  document.querySelectorAll('.faq-section').forEach(s => s.classList.add('active'));

  let found = 0;
  document.querySelectorAll('.faq-item').forEach(item => {
    const text = item.textContent.toLowerCase();
    if (text.includes(q)) {
      item.style.display = '';
      found++;
    } else {
      item.style.display = 'none';
    }
  });

  document.querySelectorAll('.faq-section').forEach(s => {
    const visible = [...s.querySelectorAll('.faq-item')].some(i => i.style.display !== 'none');
    s.classList.toggle('active', visible);
  });

  document.getElementById('no-results').style.display = found === 0 ? 'block' : 'none';
}
</script>
</body>
</html>
