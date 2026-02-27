<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us — Fixigo</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='20' fill='%23FF5C1A'/><text y='.9em' font-size='70' x='12'>🔧</text></svg>"/>
  <link rel="stylesheet" href="pages.css">
  <style>
    .mission-box {
      background: linear-gradient(135deg, rgba(255,92,26,.1), rgba(255,92,26,.02));
      border: 1px solid rgba(255,92,26,.25);
      border-radius: 20px;
      padding: 48px;
      text-align: center;
      margin-bottom: 52px;
      position: relative;
      overflow: hidden;
    }
    .mission-box::after {
      content: '🔧';
      position: absolute;
      right: 32px; top: 50%;
      transform: translateY(-50%);
      font-size: 100px;
      opacity: .05;
      pointer-events: none;
    }
    .mission-box h2 {
      font-family: 'Syne', sans-serif;
      font-size: clamp(20px,4vw,30px);
      font-weight: 800;
      margin-bottom: 14px;
    }
    .mission-box h2 span { color: var(--orange); }
    .mission-box p {
      font-size: 16px;
      color: var(--text-muted);
      line-height: 1.7;
      max-width: 620px;
      margin: 0 auto;
    }
    .stat-row {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 16px;
      margin-bottom: 52px;
    }
    .stat-box {
      background: var(--dark3);
      border: 1px solid var(--border);
      border-radius: 16px;
      padding: 24px;
      text-align: center;
      transition: border-color .3s, transform .3s;
    }
    .stat-box:hover { border-color: rgba(255,92,26,.3); transform: translateY(-3px); }
    .stat-num {
      font-family: 'Syne', sans-serif;
      font-size: 32px;
      font-weight: 800;
      color: var(--orange);
      margin-bottom: 4px;
    }
    .stat-lbl { font-size: 13px; color: var(--text-muted); }
    .team-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 20px; }
    .team-card {
      background: var(--dark3);
      border: 1px solid var(--border);
      border-radius: 18px;
      padding: 28px 24px;
      text-align: center;
      transition: border-color .3s, transform .3s;
    }
    .team-card:hover { border-color: rgba(255,92,26,.25); transform: translateY(-3px); }
    .team-avatar {
      width: 64px;
      height: 64px;
      border-radius: 18px;
      background: linear-gradient(135deg, var(--orange), #ff9a5c);
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Syne', sans-serif;
      font-size: 24px;
      font-weight: 800;
      margin: 0 auto 14px;
    }
    .team-name { font-family: 'Syne', sans-serif; font-size: 15px; font-weight: 800; margin-bottom: 4px; }
    .team-role { font-size: 12px; color: var(--orange); margin-bottom: 10px; }
    .team-bio { font-size: 13px; color: var(--text-muted); line-height: 1.55; }
    .timeline { position: relative; padding-left: 28px; }
    .timeline::before {
      content: '';
      position: absolute;
      left: 7px; top: 6px; bottom: 6px;
      width: 2px;
      background: linear-gradient(to bottom, var(--orange), rgba(255,92,26,.1));
    }
    .tl-item { position: relative; margin-bottom: 32px; }
    .tl-dot {
      position: absolute;
      left: -24px;
      top: 4px;
      width: 14px;
      height: 14px;
      border-radius: 50%;
      background: var(--orange);
      box-shadow: 0 0 10px rgba(255,92,26,.4);
    }
    .tl-year {
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: var(--orange);
      margin-bottom: 4px;
    }
    .tl-title {
      font-family: 'Syne', sans-serif;
      font-size: 16px;
      font-weight: 800;
      margin-bottom: 6px;
    }
    .tl-desc { font-size: 13px; color: var(--text-muted); line-height: 1.6; }
    .values-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 16px; }
    .value-card {
      background: var(--dark4);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 22px;
      transition: border-color .3s;
    }
    .value-card:hover { border-color: rgba(255,92,26,.25); }
    .value-icon { font-size: 24px; margin-bottom: 10px; }
    .value-title { font-family: 'Syne', sans-serif; font-size: 14px; font-weight: 800; margin-bottom: 6px; }
    .value-desc { font-size: 12px; color: var(--text-muted); line-height: 1.55; }
    @media (max-width: 768px) {
      .stat-row { grid-template-columns: repeat(2,1fr); }
      .team-grid { grid-template-columns: 1fr; }
      .values-grid { grid-template-columns: 1fr; }
      .mission-box { padding: 28px 20px; }
      .mission-box::after { display: none; }
    }
    @media (max-width: 480px) {
      .stat-row { grid-template-columns: repeat(2,1fr); }
    }
  </style>
</head>
<body>
<canvas id="dots"></canvas>


<nav class="nav">
  <a href="../index.php" class="nav-logo"><div class="li">🔧</div><span>Fix<b>igo</b></span></a>
  <div class="nav-links">
    <a href="../index.php">Home</a>
    <a href="about.php" class="active">About</a>
    <a href="contact.php">Contact</a>
    <a href="help.php">Help Center</a>
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
    <div class="page-tag">ℹ️ Our Story</div>
    <h1>Built for <span>Sri Lankan</span> Roads</h1>
    <p>Fixigo was born from a simple frustration — finding a trustworthy mechanic shouldn't be stressful. We built the platform we wished existed.</p>
  </div>


  <div class="mission-box">
    <div class="page-tag" style="margin-bottom:14px">🎯 Our Mission</div>
    <h2>Fast, Transparent &amp; <span>Trustworthy</span> Vehicle Repair</h2>
    <p>We connect Sri Lankan vehicle owners with verified local workshops — making car repair faster, more transparent, and stress-free. Whether it's a routine service or a roadside emergency, Fixigo has you covered.</p>
  </div>


  <div class="stat-row">
    <div class="stat-box"><div class="stat-num">500+</div><div class="stat-lbl">Workshops Listed</div></div>
    <div class="stat-box"><div class="stat-num">12K+</div><div class="stat-lbl">Vehicle Owners</div></div>
    <div class="stat-box"><div class="stat-num">25</div><div class="stat-lbl">Districts Covered</div></div>
    <div class="stat-box"><div class="stat-num">24/7</div><div class="stat-lbl">Emergency SOS</div></div>
  </div>

  <hr class="divider">


  <div class="section-title">What <span>We Do</span></div>
  <div class="section-sub">Three things that make Fixigo different</div>
  <div class="grid-3" style="margin-bottom:52px">
    <div class="card">
      <div class="card-icon">🏪</div>
      <h3>Verified Workshops</h3>
      <p>Every workshop on Fixigo goes through a verification process before going live — so you only see trusted, legit mechanics.</p>
    </div>
    <div class="card">
      <div class="card-icon">📋</div>
      <h3>Real-Time Requests</h3>
      <p>Send a service request directly to a workshop and get a response in minutes. No phone tag, no guessing games.</p>
    </div>
    <div class="card">
      <div class="card-icon">🚨</div>
      <h3>Emergency SOS</h3>
      <p>Broken down on the road? One tap sends your GPS location to nearby workshops and our team via SMS — instantly.</p>
    </div>
  </div>

  <hr class="divider">


  <div class="section-title">Our <span>Values</span></div>
  <div class="section-sub">The principles that guide everything we build</div>
  <div class="values-grid" style="margin-bottom:52px">
    <div class="value-card"><div class="value-icon">🤝</div><div class="value-title">Trust</div><div class="value-desc">Every workshop is verified. Every review is real. We never compromise on honesty.</div></div>
    <div class="value-card"><div class="value-icon">⚡</div><div class="value-title">Speed</div><div class="value-desc">From request to response in minutes. We respect your time on and off the road.</div></div>
    <div class="value-card"><div class="value-icon">🌍</div><div class="value-title">Accessibility</div><div class="value-desc">Built for all 25 districts of Sri Lanka — urban or rural, we've got you covered.</div></div>
    <div class="value-card"><div class="value-icon">🔒</div><div class="value-title">Safety</div><div class="value-desc">Your data is secure. Your payments are protected. Your emergency alerts are prioritised.</div></div>
    <div class="value-card"><div class="value-icon">📊</div><div class="value-title">Transparency</div><div class="value-desc">Clear pricing, real reviews, verified listings. No hidden surprises — ever.</div></div>
    <div class="value-card"><div class="value-icon">🚀</div><div class="value-title">Innovation</div><div class="value-desc">We're always building — smarter tools, better features, and faster responses.</div></div>
  </div>

  <hr class="divider">

<div style="display:grid;grid-template-columns:1fr 1fr;gap:40px;align-items:start">
    <div>
      <div class="section-title">Our <span>Journey</span></div>
      <div class="section-sub">How Fixigo came to be</div>
      <div class="timeline">
        <div class="tl-item">
          <div class="tl-dot"></div>
          <div class="tl-year">2022 — The Idea</div>
          <div class="tl-title">Born from Frustration</div>
          <div class="tl-desc">Our founder's car broke down on the Colombo–Kandy highway. No app, no list of workshops, no help. That moment sparked Fixigo.</div>
        </div>
        <div class="tl-item">
          <div class="tl-dot"></div>
          <div class="tl-year">2023 — Beta Launch</div>
          <div class="tl-title">First 50 Workshops</div>
          <div class="tl-desc">We launched in Colombo with 50 verified workshops and 200 early users. The feedback was overwhelming — keep going.</div>
        </div>
        <div class="tl-item">
          <div class="tl-dot"></div>
          <div class="tl-year">2024 — SOS Feature</div>
          <div class="tl-title">Emergency Roadside Help</div>
          <div class="tl-desc">We launched the Emergency SOS feature — sending SMS alerts to nearby workshops and our support team in real time.</div>
        </div>
        <div class="tl-item">
          <div class="tl-dot"></div>
          <div class="tl-year">2025 — Nationwide</div>
          <div class="tl-title">All 25 Districts</div>
          <div class="tl-desc">Fixigo expanded to cover all 25 districts of Sri Lanka with 500+ verified workshops and 12,000+ registered users.</div>
        </div>
      </div>
    </div>


    <div>
      <div class="section-title">The <span>Team</span></div>
      <div class="section-sub">The people building Fixigo</div>
      <div style="display:flex;flex-direction:column;gap:14px">
        <div class="team-card" style="display:flex;align-items:center;gap:16px;text-align:left;padding:20px">
          <div class="team-avatar" style="flex-shrink:0;width:50px;height:50px;font-size:20px;margin:0">Z</div>
          <div>
            <div class="team-name">Zumair</div>
            <div class="team-role">Founder & CEO</div>
            <div class="team-bio">Passionate about making vehicle repair accessible for every Sri Lankan.</div>
          </div>
        </div>
        <div class="team-card" style="display:flex;align-items:center;gap:16px;text-align:left;padding:20px">
          <div class="team-avatar" style="flex-shrink:0;width:50px;height:50px;font-size:20px;margin:0;background:linear-gradient(135deg,#409CFF,#7BC8FF)">A</div>
          <div>
            <div class="team-name">Ahamed</div>
            <div class="team-role">CTO (Chief Technology Officer)</div>
            <div class="team-bio">Leads the technical team, manages infrastructure, and ensures the site runs smoothly and securely.</div>
          </div>
        </div>
        <div class="team-card" style="display:flex;align-items:center;gap:16px;text-align:left;padding:20px">
          <div class="team-avatar" style="flex-shrink:0;width:50px;height:50px;font-size:20px;margin:0;background:linear-gradient(135deg,#3DDB7A,#5eeea0)">A</div>
          <div>
            <div class="team-name">Anas</div>
            <div class="team-role">Head of Operations</div>
            <div class="team-bio">Manages workshop onboarding and ensures every listing meets Fixigo's quality standards.</div>
          </div>
        </div>
          <div class="team-card" style="display:flex;align-items:center;gap:16px;text-align:left;padding:20px">
          <div class="team-avatar" style="flex-shrink:0;width:50px;height:50px;font-size:20px;margin:0;background:linear-gradient(135deg,#409CFF,#7BC8FF)">N</div>
          <div>
            <div class="team-name">Nadha</div>
            <div class="team-role">Head of Technology</div>
            <div class="team-bio">Full-stack engineer building fast, reliable systems that work even in the toughest spots.</div>
          </div>
        </div>
          <div class="team-card" style="display:flex;align-items:center;gap:16px;text-align:left;padding:20px;margin-left: -510px;margin-right:500px">
          <div class="team-avatar" style="flex-shrink:0;width:50px;height:50px;font-size:20px;margin:0;background:linear-gradient(135deg,#3DDB7A,#5eeea0)">K</div>
          <div>
            <div class="team-name">Kiwiyashini</div>
            <div class="team-role">Product Manager</div>
            <div class="team-bio">Coordinates between design, development, and customer feedback to improve the site.</div>
          </div>
        </div>
          <div class="team-card" style="display:flex;align-items:center;gap:16px;text-align:left;padding:20px;margin-bottom:10px;margin-top:-141px">
          <div class="team-avatar" style="flex-shrink:0;width:50px;height:50px;font-size:20px;margin:0;background:linear-gradient(135deg,#409CFF,#7BC8FF)">A</div>
          <div>
            <div class="team-name">Bashini</div>
            <div class="team-role">Customer Support Lead</div>
            <div class="team-bio">Manages the help center, trains support staff, and ensures users get quick, helpful answers.</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <hr class="divider">


  <div style="text-align:center;padding:20px 0">
    <div class="page-tag" style="margin-bottom:14px">🚀 Join Us</div>
    <h2 style="font-family:'Syne',sans-serif;font-size:clamp(22px,4vw,34px);font-weight:800;margin-bottom:12px">Ready to <span style="color:var(--orange)">Get Started?</span></h2>
    <p style="color:var(--text-muted);font-size:15px;margin-bottom:28px">Join thousands of vehicle owners and workshops already on Fixigo.</p>
    <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap">
      <a href="../auth.php" class="btn btn-or">Create Free Account →</a>
      <a href="contact.php" class="btn btn-gh">Contact Us</a>
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
</script>
</body>
</html>
