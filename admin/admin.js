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

function showTab(name, btn) {
  document
    .querySelectorAll(".tab-panel")
    .forEach((p) => p.classList.remove("active"));
  document
    .querySelectorAll(".tab")
    .forEach((t) => t.classList.remove("active"));
  document.getElementById("tab-" + name).classList.add("active");
  if (btn) btn.classList.add("active");
  document
    .querySelectorAll(".sb-item")
    .forEach((i) => i.classList.remove("active"));
}

function filterTable(tbodyId, query) {
  const q = query.toLowerCase();
  document.querySelectorAll("#" + tbodyId + " tr").forEach((row) => {
    row.style.display = row.textContent.toLowerCase().includes(q) ? "" : "none";
  });
}

function approvePayment(workshopId) {
  if (!confirm("Mark this workshop as PAID and activate listing?")) return;
  fetch("approve_workshop.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "workshop_id=" + workshopId,
  })
    .then((r) => r.json())
    .then((d) => {
      if (d.success) {
        showToast("Workshop approved!", "🏪");
        var row = document.getElementById("ws-row-" + workshopId);
        if (row) {
          var btn = row.querySelector(".ab-approve");
          if (btn) btn.remove();
          var badge = row.querySelector(".status-badge");
          if (badge) {
            badge.className = "status-badge s-paid";
            badge.textContent = "✅ Paid";
          }
        }
      } else {
        showToast("Error: " + d.error, "❌", true);
      }
    })
    .catch((err) => showToast("Error: " + err.message, "❌", true));
}

function deleteUser(userId, userName) {
  if (
    !confirm(
      'Delete "' +
        userName +
        '"?\n\nPermanently deletes their account and workshop. Cannot be undone.',
    )
  )
    return;
  fetch("delete_user.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "user_id=" + userId,
  })
    .then((r) => r.json())
    .then((d) => {
      if (d.success) {
        showToast('"' + userName + '" deleted.', "🗑");
        var row = document.getElementById("user-row-" + userId);
        if (row) {
          row.style.transition = "opacity .3s";
          row.style.opacity = "0";
          setTimeout(() => row.remove(), 320);
        }
      } else {
        showToast("Error: " + d.error, "❌", true);
      }
    })
    .catch((err) => showToast("Error: " + err.message, "❌", true));
}

function deleteWorkshop(workshopId, workshopName) {
  if (
    !confirm(
      'Delete workshop "' + workshopName + '"?\n\nThis cannot be undone.',
    )
  )
    return;
  fetch("delete_workshop.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "workshop_id=" + workshopId,
  })
    .then((r) => r.json())
    .then((d) => {
      if (d.success) {
        showToast('"' + workshopName + '" deleted.', "🗑");
        var row = document.getElementById("ws-row-" + workshopId);
        if (row) {
          row.style.transition = "opacity .3s";
          row.style.opacity = "0";
          setTimeout(() => row.remove(), 320);
        }
      } else {
        showToast("Error: " + d.error, "❌", true);
      }
    })
    .catch((err) => showToast("Error: " + err.message, "❌", true));
}

function viewUser(userId, name, email, phone, type, registered) {
  document.getElementById("mu-avatar").textContent = name
    .charAt(0)
    .toUpperCase();
  document.getElementById("mu-name").textContent = name;
  document.getElementById("mu-email").textContent = email;
  document.getElementById("mu-phone").textContent = phone;
  document.getElementById("mu-type").textContent =
    type === "workshop" ? "🏪 Workshop Owner" : "🚗 Vehicle Owner";
  document.getElementById("mu-date").textContent = registered;
  document.getElementById("mu-del-btn").onclick = function () {
    closeModal("modal-user");
    deleteUser(userId, name);
  };
  openModal("modal-user");
}

function viewWorkshop(
  name,
  owner,
  email,
  district,
  spec,
  address,
  reg,
  payStatus,
) {
  document.getElementById("mw-name").textContent = name;
  document.getElementById("mw-owner").textContent = owner;
  document.getElementById("mw-email").textContent = email;
  document.getElementById("mw-district").textContent = district;
  document.getElementById("mw-spec").textContent = spec;
  document.getElementById("mw-address").textContent = address || "—";
  document.getElementById("mw-reg").textContent = reg || "—";
  document.getElementById("mw-pay").textContent =
    payStatus === "paid" ? "✅ Paid & Active" : "⏳ Pending";
  openModal("modal-ws");
}

function openModal(id) {
  document.getElementById(id).classList.add("open");
}
function closeModal(id) {
  document.getElementById(id).classList.remove("open");
}

function showToast(msg, icon, err) {
  const t = document.getElementById("toast");
  document.getElementById("t-icon").textContent = icon || "✅";
  document.getElementById("t-msg").textContent = msg;
  t.className = "toast" + (err ? " err" : "") + " show";
  setTimeout(() => t.classList.remove("show"), 5000);
}
