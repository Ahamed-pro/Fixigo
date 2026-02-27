

const canvas = document.getElementById('dots');
const ctx    = canvas.getContext('2d');
let W, H, dots = [];

function resizeCanvas() {
  W = canvas.width  = window.innerWidth;
  H = canvas.height = window.innerHeight;
}

function createDot() {
  return {
    x:     Math.random() * W,
    y:     Math.random() * H,
    vx:    (Math.random() - 0.5) * 0.4,
    vy:    (Math.random() - 0.5) * 0.4,
    size:  Math.random() * 1.7 + 0.5,
    alpha: Math.random() * 0.4 + 0.1,
  };
}

let mouse = { x: 0, y: 0 };
window.addEventListener('mousemove', function(e) {
  mouse.x = e.clientX;
  mouse.y = e.clientY;
});

function setupDots() {
  dots = [];
  let count = Math.min(Math.floor((W * H) / 14000), 100);
  for (let i = 0; i < count; i++) dots.push(createDot());
}

function drawLines() {
  for (let i = 0; i < dots.length; i++) {
    for (let j = i + 1; j < dots.length; j++) {
      let dx = dots[i].x - dots[j].x;
      let dy = dots[i].y - dots[j].y;
      let d  = Math.sqrt(dx * dx + dy * dy);
      if (d < 115) {
        ctx.save();
        ctx.globalAlpha = (1 - d / 115) * 0.06;
        ctx.strokeStyle = '#FF5C1A';
        ctx.lineWidth   = 0.5;
        ctx.beginPath();
        ctx.moveTo(dots[i].x, dots[i].y);
        ctx.lineTo(dots[j].x, dots[j].y);
        ctx.stroke();
        ctx.restore();
      }
    }
    let mdx = dots[i].x - mouse.x;
    let mdy = dots[i].y - mouse.y;
    let md  = Math.sqrt(mdx * mdx + mdy * mdy);
    if (md < 130) {
      ctx.save();
      ctx.globalAlpha = (1 - md / 130) * 0.13;
      ctx.strokeStyle = '#FF7A42';
      ctx.lineWidth   = 0.5;
      ctx.beginPath();
      ctx.moveTo(dots[i].x, dots[i].y);
      ctx.lineTo(mouse.x, mouse.y);
      ctx.stroke();
      ctx.restore();
    }
  }
}

function animateDots() {
  ctx.clearRect(0, 0, W, H);
  dots.forEach(function(dot) {
    dot.x += dot.vx;
    dot.y += dot.vy;
    if (dot.x < 0 || dot.x > W) dot.vx *= -1;
    if (dot.y < 0 || dot.y > H) dot.vy *= -1;
    ctx.save();
    ctx.globalAlpha = dot.alpha;
    ctx.beginPath();
    ctx.arc(dot.x, dot.y, dot.size, 0, Math.PI * 2);
    ctx.fillStyle = '#FF5C1A';
    ctx.fill();
    ctx.restore();
  });
  drawLines();
  requestAnimationFrame(animateDots);
}

resizeCanvas();
setupDots();
animateDots();
window.addEventListener('resize', function() { resizeCanvas(); setupDots(); });



function showTab(which) {
  let loginPanel    = document.getElementById('panel-login');
  let registerPanel = document.getElementById('panel-register');
  let tabs          = document.querySelectorAll('.tab');

  if (which === 'login') {
    loginPanel.style.display    = '';
    registerPanel.style.display = 'none';
    tabs[0].classList.add('active');
    tabs[1].classList.remove('active');
  } else {
    loginPanel.style.display    = 'none';
    registerPanel.style.display = '';
    tabs[0].classList.remove('active');
    tabs[1].classList.add('active');
  }
}



function toggleWorkshopFields(radio) {
  let workshopFields = document.getElementById('workshop-fields');
  let submitBtn      = document.getElementById('submit-btn');

  if (radio.value === 'workshop') {
    workshopFields.style.display = '';
    submitBtn.textContent = 'Create Workshop Account & Pay';
  } else {
    workshopFields.style.display = 'none';
    submitBtn.textContent = 'Create Account';
  }
}



function selectPayment(clickedTab, sectionId) {
  document.querySelectorAll('.pay-tab').forEach(function(tab) {
    tab.classList.remove('active');
  });
  clickedTab.classList.add('active');

  document.getElementById('card-section').style.display = 'none';
  document.getElementById('bank-section').style.display = 'none';
  document.getElementById('cash-section').style.display = 'none';

  document.getElementById(sectionId).style.display = '';
}


function togglePassword(inputId, button) {
  let input = document.getElementById(inputId);
  if (input.type === 'password') {
    input.type    = 'text';
    button.textContent = '🙈';
  } else {
    input.type    = 'password';
    button.textContent = '👁';
  }
}



function checkStrength(password) {
  let score = 0;

  if (password.length >= 8)              score++; 
  if (/[A-Z]/.test(password))           score++; 
  if (/[0-9]/.test(password))           score++;
  if (/[^A-Za-z0-9]/.test(password))   score++; 


  let colors = ['#FF4C4C', '#FF9020', '#FFD020', '#3DDB7A'];
  let labels = ['Weak — add numbers & symbols', 'Fair — add uppercase', 'Good — almost there!', 'Strong password ✓'];

  let segs  = [document.getElementById('s1'), document.getElementById('s2'), document.getElementById('s3'), document.getElementById('s4')];
  let label = document.getElementById('strength-text');

  segs.forEach(function(seg, i) {
    seg.style.background = i < score ? colors[score - 1] : 'var(--dark4)';
  });

  if (password.length === 0) {
    label.textContent = 'Enter a password';
    label.style.color = 'var(--text-dim)';
  } else {
    label.textContent = labels[Math.max(0, score - 1)];
    label.style.color = colors[Math.max(0, score - 1)];
  }
}




function formatCardNumber(input) {
  let digits = input.value.replace(/\D/g, '').slice(0, 16);
  input.value = digits.replace(/(\d{4})(?=\d)/g, '$1 ');
}


function formatExpiry(input) {
  let digits = input.value.replace(/\D/g, '').slice(0, 4);
  if (digits.length >= 3) {
    input.value = digits.slice(0, 2) + ' / ' + digits.slice(2);
  } else {
    input.value = digits;
  }
}




function getGPS(inputId, statusId) {
  let input  = document.getElementById(inputId);
  let status = document.getElementById(statusId);

  if (!navigator.geolocation) {
    showStatus(status, '❌ Your browser does not support GPS.', 'err');
    return;
  }

  showStatus(status, '📡 Getting your location…', 'loading');

  navigator.geolocation.getCurrentPosition(
    function(position) {
      let lat = position.coords.latitude.toFixed(6);
      let lng = position.coords.longitude.toFixed(6);

      fetch('https://nominatim.openstreetmap.org/reverse?lat=' + lat + '&lon=' + lng + '&format=json')
        .then(function(res) { return res.json(); })
        .then(function(data) {
          let addr  = data.address;
          let parts = [addr.road, addr.suburb || addr.city_district, addr.city || addr.town];
          let text  = parts.filter(Boolean).join(', ') || (lat + ', ' + lng);
          input.value = text;
          showStatus(status, '✅ Location: ' + text, 'ok');
        })
        .catch(function() {
          input.value = lat + ', ' + lng;
          showStatus(status, '✅ GPS: ' + lat + ', ' + lng, 'ok');
        });
    },
   
    function(error) {
      let msg = '❌ Could not get location.';
      if (error.code === 1) msg = '❌ Location denied. Allow access in browser settings.';
      if (error.code === 2) msg = '❌ GPS unavailable. Enable location on your device.';
      if (error.code === 3) msg = '❌ Timed out. Please try again.';
      showStatus(status, msg, 'err');
    },
    { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
  );
}

function showStatus(element, message, type) {
  element.textContent = message;
  element.className   = 'gps-status ' + type;
}



function showToast(message, icon) {
  icon = icon || '✅';
  let toast = document.getElementById('toast');
  document.getElementById('toast-icon').textContent = icon;
  document.getElementById('toast-msg').textContent  = message;
  toast.classList.add('show');
  setTimeout(function() { toast.classList.remove('show'); }, 3500);
}



function submitLogin(event) {
  event.preventDefault();

  let email = document.getElementById('login-email').value.trim();
  let pass  = document.getElementById('login-pass').value;

  if (!email || !pass) {
    showToast('Please fill in all fields.', '⚠️');
    return;
  }

  let btn = event.target.querySelector('.btn-orange');
  btn.textContent = 'Signing in…';
  btn.style.opacity = '0.7';

  setTimeout(function() {
    document.getElementById('login-form').style.display    = 'none';
    document.getElementById('login-success').style.display = '';
    showToast('Welcome back!', '👋');
  }, 1500);
}



function submitRegister(event) {
  event.preventDefault();

  let type  = document.querySelector('input[name="account-type"]:checked').value;
  let fname = document.getElementById('r-fname').value.trim();
  let email = document.getElementById('r-email').value.trim();
  let phone = document.getElementById('r-phone').value.trim();
  let pass  = document.getElementById('r-pass').value;
  let terms = document.getElementById('r-terms').checked;

  if (!fname || !email || !phone || !pass) {
    showToast('Please fill in all required fields.', '⚠️');
    return;
  }
  if (!terms) {
    showToast('Please accept the Terms of Service.', '⚠️');
    return;
  }
  if (pass.length < 8) {
    showToast('Password must be at least 8 characters.', '⚠️');
    return;
  }

  if (type === 'workshop') {
    let wname    = document.getElementById('w-name').value.trim();
    let district = document.getElementById('w-district').value;
    let address  = document.getElementById('w-address').value.trim();

    if (!wname || !district || !address) {
      showToast('Please fill in all workshop details.', '⚠️');
      return;
    }
  }

  let btn = document.getElementById('submit-btn');
  btn.textContent  = 'Processing…';
  btn.style.opacity = '0.7';

  setTimeout(function() {
    document.getElementById('register-form').style.display   = 'none';
    document.getElementById('register-success').style.display = '';

    if (type === 'workshop') {
      document.getElementById('success-icon').textContent  = '🏪';
      document.getElementById('success-title').textContent = 'Workshop Registered!';
      document.getElementById('success-msg').textContent   = 'Payment confirmed. Your workshop will be listed within 24 hours after verification.';
      document.getElementById('success-link').textContent  = 'View Dashboard →';
    } else {
      document.getElementById('success-icon').textContent  = '🎉';
      document.getElementById('success-title').textContent = 'Welcome, ' + fname + '!';
      document.getElementById('success-msg').textContent   = 'Your Fixigo account is ready. Start finding workshops near you!';
      document.getElementById('success-link').textContent  = 'Find Workshops →';
    }

    showToast(type === 'workshop' ? 'Workshop registered!' : 'Account created!',
              type === 'workshop' ? '🏪' : '🎉');
  }, 2000);
}
