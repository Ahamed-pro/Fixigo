const c = document.getElementById("dots"),
  x = c.getContext("2d");
let W,
  H,
  ds = [];
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
    a: Math.random() * 0.4 + 0.1,
  };
}
function init() {
  ds = [];
  for (let i = 0; i < Math.min(Math.floor((W * H) / 14000), 80); i++)
    ds.push(mk());
}
function drw() {
  x.clearRect(0, 0, W, H);
  ds.forEach((d) => {
    d.x += d.vx;
    d.y += d.vy;
    if (d.x < 0 || d.x > W) d.vx *= -1;
    if (d.y < 0 || d.y > H) d.vy *= -1;
    x.save();
    x.globalAlpha = d.a;
    x.beginPath();
    x.arc(d.x, d.y, d.r, 0, Math.PI * 2);
    x.fillStyle = "#FF5C1A";
    x.fill();
    x.restore();
  });
  requestAnimationFrame(drw);
}
rsz();
init();
drw();
window.addEventListener("resize", () => {
  rsz();
  init();
});

function showToast(msg, icon, err) {
  const t = document.getElementById("toast");
  document.getElementById("t-icon").textContent = icon || "✅";
  document.getElementById("t-msg").textContent = msg;
  t.className = "toast" + (err ? " err" : "") + " show";
  setTimeout(() => t.classList.remove("show"), 4000);
}

function openModal(id) {
  document.getElementById(id).classList.add("open");
  document.body.style.overflow = "hidden";
}
function closeModal(id) {
  document.getElementById(id).classList.remove("open");
  document.body.style.overflow = "";
}

document.addEventListener("keydown", function (e) {
  if (e.key === "Escape") {
    document.querySelectorAll(".modal-overlay.open").forEach(function (m) {
      m.classList.remove("open");
    });
    document.body.style.overflow = "";
  }
});

function getGPSLocation(inputId, statusId) {
  var input = document.getElementById(inputId);
  var status = document.getElementById(statusId);

  if (!navigator.geolocation) {
    setGpsStatus(status, "❌ GPS not supported by your browser.", "err");
    return;
  }
  setGpsStatus(status, "📡 Getting your location…", "loading");

  navigator.geolocation.getCurrentPosition(
    function (pos) {
      var lat = pos.coords.latitude.toFixed(6);
      var lng = pos.coords.longitude.toFixed(6);
      fetch(
        "https://nominatim.openstreetmap.org/reverse?lat=" +
          lat +
          "&lon=" +
          lng +
          "&format=json",
      )
        .then(function (r) {
          return r.json();
        })
        .then(function (data) {
          var addr = data.address || {};
          var parts = [
            addr.road,
            addr.suburb || addr.city_district,
            addr.city || addr.town,
            addr.state,
          ];
          var short = parts.filter(Boolean).join(", ") || lat + ", " + lng;
          input.value = short;
          setGpsStatus(status, "✅ " + short.slice(0, 55), "ok");
        })
        .catch(function () {
          input.value = lat + ", " + lng;
          setGpsStatus(status, "✅ GPS: " + lat + ", " + lng, "ok");
        });
    },
    function (err) {
      var msgs = {
        1: "❌ Permission denied. Allow location access.",
        2: "❌ GPS unavailable.",
        3: "❌ Timed out. Try again.",
      };
      setGpsStatus(
        status,
        msgs[err.code] || "❌ Could not get location.",
        "err",
      );
    },
    { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 },
  );
}
function setGpsStatus(el, msg, type) {
  el.textContent = msg;
  el.className = "gps-status " + type;
}

var serviceRequests = [];

function submitRequest(event) {
  event.preventDefault();
  var name = document.getElementById("req-name").value.trim();
  var phone = document.getElementById("req-phone").value.trim();
  var loc = document.getElementById("req-location").value.trim();
  var service = document.getElementById("req-service").value;
  var desc = document.getElementById("req-desc").value.trim();

  if (!name) {
    showToast("Please enter your name.", "⚠️");
    return;
  }
  if (!phone) {
    showToast("Please enter your phone number.", "⚠️");
    return;
  }
  if (!service) {
    showToast("Please select a service type.", "⚠️");
    return;
  }

  var btn = document.getElementById("req-submit-btn");
  btn.textContent = "⏳ Sending…";
  btn.disabled = true;

  setTimeout(function () {
    serviceRequests.unshift({
      service: service,
      location: loc || "Not specified",
      desc: desc,
      status: "Pending",
      date: new Date().toLocaleDateString("en-GB", {
        day: "2-digit",
        month: "short",
        year: "numeric",
      }),
    });

    closeModal("modal-request");
    event.target.reset();
    document.getElementById("req-gps-status").textContent = "";
    btn.textContent = "Send Request 🔧";
    btn.disabled = false;

    refreshRequestsPreview();
    refreshStats();
    showToast("Request sent! A workshop will contact you shortly.", "🔧");
  }, 1400);
}

function refreshRequestsPreview() {
  var box = document.getElementById("requests-preview");
  if (serviceRequests.length === 0) {
    box.innerHTML =
      '<div class="empty"><div class="ei">📋</div><p>No requests yet. Click <strong>Request Service</strong> to get started!</p></div>';
    return;
  }
  var html = serviceRequests
    .slice(0, 3)
    .map(function (r) {
      var badgeClass =
        r.status === "Pending"
          ? "req-pending"
          : r.status === "Completed"
            ? "req-done"
            : "req-cancel";
      return (
        '<div style="display:flex;align-items:center;justify-content:space-between;padding:13px 18px;border-bottom:1px solid rgba(255,255,255,.03)">' +
        '<div><strong style="font-size:14px">' +
        r.service +
        "</strong>" +
        '<div style="font-size:12px;color:var(--text-muted);margin-top:2px">' +
        r.location +
        " · " +
        r.date +
        "</div></div>" +
        '<span class="req-badge ' +
        badgeClass +
        '">' +
        r.status +
        "</span>" +
        "</div>"
      );
    })
    .join("");
  box.innerHTML = html;
}

function submitSOS(event) {
  event.preventDefault();
  var name = document.getElementById("sos-name").value.trim();
  var phone = document.getElementById("sos-phone").value.trim();
  var loc = document.getElementById("sos-location").value.trim();
  var type = document.getElementById("sos-type").value;

  if (!name) {
    showToast("Please enter your name.", "⚠️");
    return;
  }
  if (!phone) {
    showToast("Please enter your phone number.", "⚠️");
    return;
  }
  if (!loc) {
    showToast("Please share your location first.", "⚠️");
    return;
  }
  if (!type) {
    showToast("Please select emergency type.", "⚠️");
    return;
  }

  var btn = document.getElementById("sos-submit-btn");
  btn.textContent = "⏳ Sending SOS…";
  btn.disabled = true;

  setTimeout(function () {
    closeModal("modal-sos");
    event.target.reset();
    document.getElementById("sos-gps-status").textContent = "";
    btn.textContent = "🚨 Send SOS Now";
    btn.disabled = false;
    showToast("SOS sent! Help is on the way! 🚨", "🚨");
  }, 1400);
}

var vehicles = JSON.parse(localStorage.getItem("fixigo_vehicles") || "[]");

function addVehicle(event) {
  event.preventDefault();
  var make = document.getElementById("v-make").value.trim();
  var model = document.getElementById("v-model").value.trim();
  var year = document.getElementById("v-year").value.trim();
  var color = document.getElementById("v-color").value.trim();
  var plate = document.getElementById("v-plate").value.trim().toUpperCase();
  var fuel = document.getElementById("v-fuel").value;

  if (!make || !model || !year || !plate) {
    showToast("Please fill in all required fields.", "⚠️");
    return;
  }
  vehicles.push({
    make: make,
    model: model,
    year: year,
    color: color,
    plate: plate,
    fuel: fuel,
  });
  localStorage.setItem("fixigo_vehicles", JSON.stringify(vehicles));
  closeModal("modal-vehicle");
  event.target.reset();
  refreshVehiclesPreview();
  refreshStats();
  showToast(make + " " + model + " saved! 🚗", "✅");
}

function removeVehicle(index) {
  if (!confirm("Remove this vehicle?")) return;
  vehicles.splice(index, 1);
  localStorage.setItem("fixigo_vehicles", JSON.stringify(vehicles));
  refreshVehiclesPreview();
  refreshStats();
  showToast("Vehicle removed.", "🗑");
}

function refreshVehiclesPreview() {
  var box = document.getElementById("vehicles-preview");
  if (vehicles.length === 0) {
    box.innerHTML =
      '<div class="empty"><div class="ei">🚗</div><p>No vehicles saved yet. Add your car for faster bookings!</p></div>';
    return;
  }
  var html =
    '<div class="vehicle-grid">' +
    vehicles
      .map(function (v, i) {
        return (
          '<div class="vehicle-card">' +
          '<div class="vc-icon">🚗</div>' +
          '<div class="vc-name">' +
          v.make +
          " " +
          v.model +
          "</div>" +
          '<div class="vc-plate">' +
          v.plate +
          "</div>" +
          '<div class="vc-meta">' +
          v.year +
          (v.color ? " · " + v.color : "") +
          (v.fuel ? " · " + v.fuel : "") +
          "</div>" +
          '<button class="vc-del" onclick="removeVehicle(' +
          i +
          ')">🗑 Remove</button>' +
          "</div>"
        );
      })
      .join("") +
    "</div>";
  box.innerHTML = html;
}

function refreshStats() {
  document.getElementById("stat-req").textContent = serviceRequests.length;
  document.getElementById("stat-done").textContent = serviceRequests.filter(
    function (r) {
      return r.status === "Completed";
    },
  ).length;
  document.getElementById("stat-veh").textContent = vehicles.length;
}

refreshVehiclesPreview();
refreshRequestsPreview();
refreshStats();
