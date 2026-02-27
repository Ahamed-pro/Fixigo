const canvas = document.getElementById("dot-canvas"),
  ctx = canvas.getContext("2d");
let W,
  H,
  dots = [];
function resize() {
  W = canvas.width = window.innerWidth;
  H = canvas.height = window.innerHeight;
}
class Dot {
  constructor() {
    this.reset();
  }
  reset() {
    this.x = Math.random() * W;
    this.y = Math.random() * H;
    this.vx = (Math.random() - 0.5) * 0.4;
    this.vy = (Math.random() - 0.5) * 0.4;
    this.r = Math.random() * 1.8 + 0.5;
    this.alpha = Math.random() * 0.4 + 0.1;
    this.life = Math.random() * 200 + 100;
    this.age = 0;
  }
  update() {
    this.x += this.vx;
    this.y += this.vy;
    this.age++;
    if (
      this.age > this.life ||
      this.x < 0 ||
      this.x > W ||
      this.y < 0 ||
      this.y > H
    )
      this.reset();
  }
  draw() {
    ctx.save();
    ctx.globalAlpha = this.alpha;
    ctx.beginPath();
    ctx.arc(this.x, this.y, this.r, 0, Math.PI * 2);
    ctx.fillStyle = "#FF5C1A";
    ctx.fill();
    ctx.restore();
  }
}
let mouse = { x: 0, y: 0 };
window.addEventListener("mousemove", (e) => {
  mouse.x = e.clientX;
  mouse.y = e.clientY;
});
function initDots() {
  dots = [];
  const c = Math.min(Math.floor((W * H) / 14000), 100);
  for (let i = 0; i < c; i++) dots.push(new Dot());
}
function drawConns() {
  const md = 110;
  for (let i = 0; i < dots.length; i++) {
    for (let j = i + 1; j < dots.length; j++) {
      const dx = dots[i].x - dots[j].x,
        dy = dots[i].y - dots[j].y,
        d = Math.sqrt(dx * dx + dy * dy);
      if (d < md) {
        ctx.save();
        ctx.globalAlpha = (1 - d / md) * 0.06;
        ctx.strokeStyle = "#FF5C1A";
        ctx.lineWidth = 0.5;
        ctx.beginPath();
        ctx.moveTo(dots[i].x, dots[i].y);
        ctx.lineTo(dots[j].x, dots[j].y);
        ctx.stroke();
        ctx.restore();
      }
    }
    const mdx = dots[i].x - mouse.x,
      mdy = dots[i].y - mouse.y,
      md2 = Math.sqrt(mdx * mdx + mdy * mdy);
    if (md2 < 140) {
      ctx.save();
      ctx.globalAlpha = (1 - md2 / 140) * 0.14;
      ctx.strokeStyle = "#FF7A42";
      ctx.lineWidth = 0.5;
      ctx.beginPath();
      ctx.moveTo(dots[i].x, dots[i].y);
      ctx.lineTo(mouse.x, mouse.y);
      ctx.stroke();
      ctx.restore();
    }
  }
}
function animate() {
  ctx.clearRect(0, 0, W, H);
  dots.forEach((d) => {
    d.update();
    d.draw();
  });
  drawConns();
  requestAnimationFrame(animate);
}
resize();
initDots();
animate();
window.addEventListener("resize", () => {
  resize();
  initDots();
});

function showTab(tab) {
  switchTab(tab);
}
function switchTab(tab) {
  const tabs = document.querySelectorAll(".auth-tab");
  const login = document.getElementById("panel-login");
  const reg = document.getElementById("panel-register");
  if (tab === "login") {
    tabs[0].classList.add("active");
    tabs[1].classList.remove("active");
    login.style.display = "";
    reg.style.display = "none";
  } else {
    tabs[1].classList.add("active");
    tabs[0].classList.remove("active");
    login.style.display = "none";
    reg.style.display = "";
  }
}

function onTypeChange(radio) {
  const wf = document.getElementById("workshop-fields");
  const btn = document.getElementById("btn-register-text");
  if (radio.value === "workshop") {
    wf.style.display = "block";
    btn.textContent = "Create Workshop Account & Pay";
  } else {
    wf.style.display = "none";
    btn.textContent = "Create Account";
  }
}

function togglePass(id, btn) {
  const el = document.getElementById(id);
  el.type = el.type === "password" ? "text" : "password";
  btn.textContent = el.type === "password" ? "👁" : "🙈";
}

function checkStrength(input) {
  const v = input.value;
  const segs = [
    document.getElementById("s1"),
    document.getElementById("s2"),
    document.getElementById("s3"),
    document.getElementById("s4"),
  ];
  const label = document.getElementById("strength-label");
  const colors = ["#FF4C4C", "#FF9020", "#FFD020", "#3DDB7A"];
  const labels = [
    "Weak — add numbers & symbols",
    "Fair — add uppercase letters",
    "Good — almost there!",
    "Strong password ✓",
  ];
  let score = 0;
  if (v.length >= 8) score++;
  if (/[A-Z]/.test(v)) score++;
  if (/[0-9]/.test(v)) score++;
  if (/[^A-Za-z0-9]/.test(v)) score++;
  segs.forEach((s, i) => {
    s.style.background = i < score ? colors[score - 1] : "var(--dark-5)";
  });
  label.textContent =
    v.length === 0 ? "Enter a password" : labels[Math.max(0, score - 1)];
  label.style.color =
    v.length === 0 ? "var(--text-dim)" : colors[Math.max(0, score - 1)];
}

function selectPayMethod(el, method) {
  document
    .querySelectorAll(".pay-method")
    .forEach((m) => m.classList.remove("selected"));
  el.classList.add("selected");
  document.getElementById("card-fields").style.display = "none";
  document.getElementById("bank-fields").style.display = "none";
  document.getElementById("cash-fields").style.display = "none";
  if (method === "card") {
    document.getElementById("card-fields").style.display = "block";
  } else if (method === "bank") {
    document.getElementById("bank-fields").style.display = "block";
  } else {
    document.getElementById("cash-fields").style.display = "block";
  }
}

function formatCard(el) {
  let v = el.value.replace(/\D/g, "").slice(0, 16);
  el.value = v.replace(/(\d{4})(?=\d)/g, "$1 ");
}
function formatExpiry(el) {
  let v = el.value.replace(/\D/g, "").slice(0, 4);
  if (v.length >= 3) v = v.slice(0, 2) + " / " + v.slice(2);
  el.value = v;
}

function getLocation(fieldId, statusId) {
  const field = document.getElementById(fieldId);
  const status = document.getElementById(statusId);
  const btn = document.getElementById(
    "loc-btn-" +
      fieldId.replace("w-address", "workshop").replace("req-location", "req"),
  );

  if (!("geolocation" in navigator)) {
    setLocStatus(
      status,
      "❌ Geolocation is not supported by your browser.",
      "error",
    );
    return;
  }

  setLocStatus(status, "📡 Requesting your location…", "loading");
  if (btn) btn.classList.add("loading");

  navigator.geolocation.getCurrentPosition(
    (pos) => {
      const lat = pos.coords.latitude.toFixed(6);
      const lng = pos.coords.longitude.toFixed(6);

      const latField = document.getElementById("req-lat");
      const lngField = document.getElementById("req-lng");
      if (latField) latField.value = lat;
      if (lngField) lngField.value = lng;

      fetch(
        `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`,
      )
        .then((r) => r.json())
        .then((data) => {
          const addr = data.display_name || `${lat}, ${lng}`;
          const short = data.address
            ? [
                data.address.road,
                data.address.suburb || data.address.city_district,
                data.address.city || data.address.town,
                data.address.state,
              ]
                .filter(Boolean)
                .join(", ")
            : addr;
          field.value = short;
          setLocStatus(
            status,
            `✅ Location detected: ${short.slice(0, 60)}…`,
            "success",
          );
          if (btn) {
            btn.classList.remove("loading");
            btn.textContent = "✅";
          }
        })
        .catch(() => {
          field.value = `${lat}, ${lng}`;
          setLocStatus(status, `✅ GPS coords: ${lat}, ${lng}`, "success");
          if (btn) {
            btn.classList.remove("loading");
            btn.textContent = "✅";
          }
        });
    },
    (err) => {
      let msg = "Unable to get location.";
      if (err.code === 1)
        msg =
          "❌ Location permission denied. Please allow access in your browser/device settings.";
      else if (err.code === 2)
        msg = "❌ Location unavailable. Check GPS is enabled.";
      else if (err.code === 3)
        msg = "❌ Location request timed out. Try again.";
      setLocStatus(status, msg, "error");
      if (btn) {
        btn.classList.remove("loading");
        btn.textContent = "📡";
      }
      showToast(
        "Location denied",
        "Please enable GPS in your device settings.",
        "⚠️",
        true,
      );
    },
    { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 },
  );
}

function setLocStatus(el, msg, type) {
  el.textContent = msg;
  el.className = "location-status " + type;
}

function showToast(title, msg, icon = "✅", isErr = false) {
  const t = document.getElementById("toast");
  document.getElementById("t-icon").textContent = icon;
  document.getElementById("t-title").textContent = title;
  document.getElementById("t-msg").textContent = msg;
  t.className = "toast" + (isErr ? " error" : "") + " show";
  setTimeout(() => t.classList.remove("show"), 4000);
}
function openRequestModal() {
  document.getElementById("modal-request").classList.add("active");
  document.body.style.overflow = "hidden";
}
function closeModal() {
  document.getElementById("modal-request").classList.remove("active");
  document.body.style.overflow = "";
}
document.getElementById("modal-request").addEventListener("click", (e) => {
  if (e.target === e.currentTarget) closeModal();
});
document.addEventListener("keydown", (e) => {
  if (e.key === "Escape") closeModal();
});

document.getElementById("form-login").addEventListener("submit", function (e) {
  var email = document.getElementById("login-email").value.trim();
  var pass = document.getElementById("login-pass").value;
  if (!email || !pass) {
    e.preventDefault();
    showToast(
      "Missing fields",
      "Please fill in all required fields.",
      "⚠️",
      true,
    );
    return;
  }

  var btn = e.target.querySelector(".btn-submit");
  btn.innerHTML = '<div class="spinner"></div> Signing in…';
  btn.classList.add("loading");
});

document
  .getElementById("form-register")
  .addEventListener("submit", function (e) {
    var fname = document.getElementById("r-fname").value.trim();
    var email = document.getElementById("r-email").value.trim();
    var phone = document.getElementById("r-phone").value.trim();
    var pass = document.getElementById("r-pass").value;
    var terms = document.getElementById("r-terms").checked;
    var type = document.querySelector(
      'input[name="account-type"]:checked',
    ).value;

    if (!fname || !email || !phone || !pass) {
      e.preventDefault();
      showToast(
        "Missing fields",
        "Please fill in all required fields.",
        "⚠️",
        true,
      );
      return;
    }
    if (!terms) {
      e.preventDefault();
      showToast(
        "Terms required",
        "Please accept our Terms of Service.",
        "⚠️",
        true,
      );
      return;
    }
    if (pass.length < 6) {
      e.preventDefault();
      showToast(
        "Weak password",
        "Password must be at least 6 characters.",
        "⚠️",
        true,
      );
      return;
    }
    if (type === "workshop") {
      var wname = document.getElementById("w-name").value.trim();
      var wdistrict = document.getElementById("w-district").value;
      var waddr = document.getElementById("w-address").value.trim();
      if (!wname || !wdistrict || !waddr) {
        e.preventDefault();
        showToast(
          "Workshop details missing",
          "Please fill in all workshop fields.",
          "⚠️",
          true,
        );
        return;
      }
    }

    var btn = document.getElementById("btn-register");
    btn.innerHTML = '<div class="spinner"></div> Processing…';
    btn.classList.add("loading");
  });

document.getElementById("form-request").addEventListener("submit", (e) => {
  e.preventDefault();
  const name = document.getElementById("req-name").value.trim();
  const phone = document.getElementById("req-phone").value.trim();
  const loc = document.getElementById("req-location").value.trim();
  const svc = document.getElementById("req-service").value;

  if (!name) {
    showToast("Name required", "Please enter your name.", "⚠️", true);
    return;
  }
  if (!phone) {
    showToast(
      "Phone required",
      "Please enter your contact number.",
      "⚠️",
      true,
    );
    return;
  }
  if (!loc) {
    showToast(
      "Location required",
      "Please tap 📡 to share your GPS location.",
      "⚠️",
      true,
    );
    return;
  }
  if (!svc) {
    showToast("Service required", "Please select a service type.", "⚠️", true);
    return;
  }

  const btn = e.target.querySelector(".btn-submit");
  btn.innerHTML = '<div class="spinner"></div> Sending…';
  btn.classList.add("loading");
  setTimeout(() => {
    closeModal();
    setTimeout(() => {
      btn.innerHTML = "Send Request 🔧";
      btn.classList.remove("loading");
    }, 500);
    showToast("Request sent!", "A workshop will contact you shortly.", "🔧");
  }, 1500);
});
