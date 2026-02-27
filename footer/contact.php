<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us — Fixigo</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='20' fill='%23FF5C1A'/><text y='.9em' font-size='70' x='12'>🔧</text></svg>" />
  <link rel="stylesheet" href="pages.css">
  <style>
    .contact-grid {
      display: grid;
      grid-template-columns: 1fr 1.4fr;
      gap: 32px;
      align-items: start;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 6px;
      margin-bottom: 16px;
    }

    .form-group label {
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: .7px;
      color: var(--text-dim);
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
      background: var(--dark4);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 12px 16px;
      color: var(--text);
      font-family: 'DM Sans', sans-serif;
      font-size: 14px;
      outline: none;
      transition: border-color .2s;
      width: 100%;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
      border-color: rgba(255, 92, 26, .5);
    }

    .form-group textarea {
      resize: vertical;
      min-height: 120px;
    }

    .form-group select option {
      background: var(--dark3);
    }

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 14px;
    }

    .contact-info-item {
      display: flex;
      align-items: flex-start;
      gap: 14px;
      padding: 18px;
      background: var(--dark4);
      border: 1px solid var(--border);
      border-radius: 14px;
      margin-bottom: 14px;
      transition: border-color .2s;
    }

    .contact-info-item:hover {
      border-color: rgba(255, 92, 26, .25);
    }

    .ci-icon {
      font-size: 22px;
      flex-shrink: 0;
      margin-top: 2px;
    }

    .ci-label {
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: .7px;
      color: var(--text-dim);
      margin-bottom: 4px;
    }

    .ci-val {
      font-size: 14px;
      font-weight: 600;
    }

    .ci-sub {
      font-size: 12px;
      color: var(--text-muted);
      margin-top: 2px;
    }

    .map-box {
      background: var(--dark4);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 20px;
      margin-top: 6px;
      text-align: center;
    }

    .toast-msg {
      display: none;
      background: rgba(61, 219, 122, .1);
      border: 1px solid rgba(61, 219, 122, .3);
      border-radius: 12px;
      padding: 14px 18px;
      color: var(--green);
      font-size: 14px;
      margin-bottom: 18px;
      text-align: center;
    }

    @media (max-width: 768px) {
      .contact-grid {
        grid-template-columns: 1fr;
      }

      .form-row {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>

<body>
  <canvas id="dots"></canvas>


  <nav class="nav">
    <a href="index.php" class="nav-logo">
      <div class="li">🔧</div><span>Fix<b>igo</b></span>
    </a>
    <div class="nav-links">
      <a href="../index.php">Home</a>
      <a href="about.php">About</a>
      <a href="contact.php" class="active">Contact</a>
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
      <div class="page-tag">📞 Get In Touch</div>
      <h1>We're Here to <span>Help You</span></h1>
      <p>Have a question, issue, or just want to say hello? Our team is ready to assist you every step of the way.</p>
    </div>


    <div class="contact-grid">


      <div>
        <div class="section-title" style="margin-bottom:20px">Contact <span>Details</span></div>

        <div class="contact-info-item">
          <div class="ci-icon">📧</div>
          <div>
            <div class="ci-label">Email</div>
            <div class="ci-val">fixigo123@gmail.com</div>
            <div class="ci-sub">We reply within 24 hours</div>
          </div>
        </div>

        <div class="contact-info-item">
          <div class="ci-icon">📱</div>
          <div>
            <div class="ci-label">Phone</div>
            <div class="ci-val">+94 77 123 4567</div>
            <div class="ci-sub">Mon–Sat, 8am – 8pm</div>
          </div>
        </div>

        <div class="contact-info-item">
          <div class="ci-icon">📍</div>
          <div>
            <div class="ci-label">Office</div>
            <div class="ci-val">42 Galle Road, Colombo 03</div>
            <div class="ci-sub">Sri Lanka 🇱🇰</div>
          </div>
        </div>

        <div class="contact-info-item">
          <div class="ci-icon">🚨</div>
          <div>
            <div class="ci-label">Emergency Roadside SOS</div>
            <div class="ci-val">Available 24/7</div>
            <div class="ci-sub">Log in and use the SOS button on your dashboard</div>
          </div>
        </div>

        <div class="map-box">
          <div style="font-size:36px;margin-bottom:10px">🗺️</div>
          <div style="font-family:'Syne',sans-serif;font-weight:800;font-size:15px;margin-bottom:4px">Find Us on the Map</div>
          <div style="font-size:12px;color:var(--text-muted);margin-bottom:14px">Colombo 03, Western Province, Sri Lanka</div>
          <a href="https://maps.google.com/?q=Colombo+03+Sri+Lanka" target="_blank" class="btn btn-gh" style="font-size:13px;padding:8px 18px">📍 Open in Google Maps</a>
        </div>
      </div>


      <div>
        <div class="card" style="padding:32px">
          <div style="font-family:'Syne',sans-serif;font-size:18px;font-weight:800;margin-bottom:6px">Send Us a <span style="color:var(--orange)">Message</span></div>
          <div style="font-size:13px;color:var(--text-muted);margin-bottom:24px">Fill in the form and we'll get back to you shortly.</div>

          <div class="toast-msg" id="success-toast">✅ Message sent! We'll get back to you within 24 hours.</div>
          <div class="toast-msg" id="error-toast" style="background:rgba(255,76,76,.1);border-color:rgba(255,76,76,.3);color:var(--red)"></div>

          <div class="form-row">
            <div class="form-group">
              <label>First Name</label>
              <input type="text" id="fname" placeholder="John">
            </div>
            <div class="form-group">
              <label>Last Name</label>
              <input type="text" id="lname" placeholder="Perera">
            </div>
          </div>

          <div class="form-group">
            <label>Email Address</label>
            <input type="email" id="email" placeholder="john@example.com">
          </div>

          <div class="form-group">
            <label>Phone (optional)</label>
            <input type="tel" id="phone" placeholder="+94 77 000 0000">
          </div>

          <div class="form-group">
            <label>Topic</label>
            <select id="topic">
              <option value="">Select a topic…</option>
              <option>Account / Registration</option>
              <option>Booking a Service</option>
              <option>Emergency SOS</option>
              <option>Payment Issue</option>
              <option>Workshop Listing</option>
              <option>Technical Problem</option>
              <option>Other</option>
            </select>
          </div>

          <div class="form-group">
            <label>Message</label>
            <textarea id="message" placeholder="Describe your issue or question…"></textarea>
          </div>

          <button class="btn btn-or" style="width:100%;justify-content:center" onclick="submitForm()">
            Send Message 📨
          </button>
        </div>
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

  <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
  <script>
    const canvas = document.getElementById('dots'),
      ctx = canvas.getContext('2d');
    let W, H, dots = [];

    function rsz() {
      W = canvas.width = window.innerWidth;
      H = canvas.height = window.innerHeight
    }

    function mkDot() {
      return {
        x: Math.random() * W,
        y: Math.random() * H,
        vx: (Math.random() - .5) * .4,
        vy: (Math.random() - .5) * .4,
        r: Math.random() * 1.5 + .5,
        a: Math.random() * .3 + .1
      }
    }

    function init() {
      dots = [];
      for (let i = 0; i < Math.min(Math.floor(W * H / 16000), 70); i++) dots.push(mkDot())
    }

    function draw() {
      ctx.clearRect(0, 0, W, H);
      dots.forEach(d => {
        d.x += d.vx;
        d.y += d.vy;
        if (d.x < 0 || d.x > W) d.vx *= -1;
        if (d.y < 0 || d.y > H) d.vy *= -1;
        ctx.save();
        ctx.globalAlpha = d.a;
        ctx.beginPath();
        ctx.arc(d.x, d.y, d.r, 0, Math.PI * 2);
        ctx.fillStyle = '#FF5C1A';
        ctx.fill();
        ctx.restore()
      });
      requestAnimationFrame(draw)
    }
    rsz();
    init();
    draw();
    window.addEventListener('resize', () => {
      rsz();
      init()
    });

    function toggleMenu() {
      document.getElementById('mobile-menu').classList.toggle('open')
    }


    function showError(msg) {
      const el = document.getElementById('error-toast');
      el.textContent = '⚠️ ' + msg;
      el.style.display = 'block';
      document.getElementById('success-toast').style.display = 'none';
    }

    function submitForm() {
      const fname = document.getElementById('fname').value.trim();
      const lname = document.getElementById('lname').value.trim();
      const email = document.getElementById('email').value.trim();
      const phone = document.getElementById('phone').value.trim();
      const topic = document.getElementById('topic').value;
      const message = document.getElementById('message').value.trim();
      const btn = document.querySelector('.btn-or');

      if (!fname || !email || !topic || !message) {
        showError('Please fill in First Name, Email, Topic and Message.');
        return;
      }
      if (!/\S+@\S+\.\S+/.test(email)) {
        showError('Please enter a valid email address.');
        return;
      }

      btn.disabled = true;
      btn.textContent = 'Sending…';

      const data = new URLSearchParams();
      data.append('first_name', fname);
      data.append('last_name', lname);
      data.append('email', email);
      data.append('phone', phone);
      data.append('topic', topic);
      data.append('message', message);

      fetch('submit_contact.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: data.toString()
        })
        .then(r => r.json())
        .then(d => {
          btn.disabled = false;
          btn.textContent = 'Send Message 📨';
          if (d.success) {
            document.getElementById('success-toast').style.display = 'block';
            document.getElementById('error-toast').style.display = 'none';
            ['fname', 'lname', 'email', 'phone', 'message'].forEach(id => document.getElementById(id).value = '');
            document.getElementById('topic').value = '';
            window.scrollTo({
              top: 0,
              behavior: 'smooth'
            });
          } else {
            showError(d.error || 'Something went wrong.');
          }
        })
        .catch(() => {
          btn.disabled = false;
          btn.textContent = 'Send Message 📨';
        });
    }
  </script>
</body>

</html>