const canvas = document.getElementById("dots");
const ctx = canvas.getContext("2d");
let W,
  H,
  dots = [];

function resizeCanvas() {
  W = canvas.width = window.innerWidth;
  H = canvas.height = window.innerHeight;
}

function createDot() {
  return {
    x: Math.random() * W,
    y: Math.random() * H,
    vx: (Math.random() - 0.5) * 0.4,
    vy: (Math.random() - 0.5) * 0.4,
    size: Math.random() * 1.7 + 0.5,
    alpha: Math.random() * 0.4 + 0.1,
  };
}

let mouse = { x: 0, y: 0 };
window.addEventListener("mousemove", function (e) {
  mouse.x = e.clientX;
  mouse.y = e.clientY;
});

function setupDots() {
  dots = [];
  let count = Math.min(Math.floor((W * H) / 14000), 110);
  for (let i = 0; i < count; i++) {
    dots.push(createDot());
  }
}

function drawLines() {
  let maxDist = 115;

  for (let i = 0; i < dots.length; i++) {
    for (let j = i + 1; j < dots.length; j++) {
      let dx = dots[i].x - dots[j].x;
      let dy = dots[i].y - dots[j].y;
      let dist = Math.sqrt(dx * dx + dy * dy);

      if (dist < maxDist) {
        ctx.save();
        ctx.globalAlpha = (1 - dist / maxDist) * 0.06;
        ctx.strokeStyle = "#FF5C1A";
        ctx.lineWidth = 0.5;
        ctx.beginPath();
        ctx.moveTo(dots[i].x, dots[i].y);
        ctx.lineTo(dots[j].x, dots[j].y);
        ctx.stroke();
        ctx.restore();
      }
    }

    let mdx = dots[i].x - mouse.x;
    let mdy = dots[i].y - mouse.y;
    let mDist = Math.sqrt(mdx * mdx + mdy * mdy);

    if (mDist < 140) {
      ctx.save();
      ctx.globalAlpha = (1 - mDist / 140) * 0.14;
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

function animateDots() {
  ctx.clearRect(0, 0, W, H);

  dots.forEach(function (dot) {
    dot.x += dot.vx;
    dot.y += dot.vy;

    if (dot.x < 0 || dot.x > W) dot.vx *= -1;
    if (dot.y < 0 || dot.y > H) dot.vy *= -1;

    ctx.save();
    ctx.globalAlpha = dot.alpha;
    ctx.beginPath();
    ctx.arc(dot.x, dot.y, dot.size, 0, Math.PI * 2);
    ctx.fillStyle = "#FF5C1A";
    ctx.fill();
    ctx.restore();
  });

  drawLines();
  requestAnimationFrame(animateDots);
}

resizeCanvas();
setupDots();
animateDots();
window.addEventListener("resize", function () {
  resizeCanvas();
  setupDots();
});

window.addEventListener("scroll", function () {
  let navbar = document.getElementById("navbar");
  if (window.scrollY > 50) {
    navbar.classList.add("scrolled");
  } else {
    navbar.classList.remove("scrolled");
  }
});

let revealElements = document.querySelectorAll(".reveal");

let revealObserver = new IntersectionObserver(
  function (entries) {
    entries.forEach(function (entry, index) {
      if (entry.isIntersecting) {
        setTimeout(function () {
          entry.target.classList.add("visible");
        }, index * 80);
      }
    });
  },
  { threshold: 0.1 },
);

revealElements.forEach(function (el) {
  revealObserver.observe(el);
});

function countUp(element) {
  let target = parseInt(element.getAttribute("data-count"));
  let duration = 1400;
  let start = performance.now();

  function update(now) {
    let progress = Math.min((now - start) / duration, 1);
    let eased = 1 - Math.pow(1 - progress, 3);
    element.textContent = Math.floor(eased * target).toLocaleString();

    if (progress < 1) {
      requestAnimationFrame(update);
    } else {
      element.textContent = target.toLocaleString();
    }
  }
  requestAnimationFrame(update);
}

let counterObserver = new IntersectionObserver(
  function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        countUp(entry.target);
        counterObserver.unobserve(entry.target);
      }
    });
  },
  { threshold: 0.5 },
);

document.querySelectorAll("[data-count]").forEach(function (el) {
  counterObserver.observe(el);
});

function showModal(id) {
  document.getElementById(id).classList.add("open");
  document.body.style.overflow = "hidden";
}

function hideModal(id) {
  document.getElementById(id).classList.remove("open");
  document.body.style.overflow = "";
}

function closeModalOutside(event) {
  if (event.target === event.currentTarget) {
    event.currentTarget.classList.remove("open");
    document.body.style.overflow = "";
  }
}

document.addEventListener("keydown", function (e) {
  if (e.key === "Escape") {
    document.querySelectorAll(".modal-overlay.open").forEach(function (m) {
      m.classList.remove("open");
    });
    document.body.style.overflow = "";
  }
});

function getGPS(inputId, statusId) {
  let input = document.getElementById(inputId);
  let status = document.getElementById(statusId);

  if (!navigator.geolocation) {
    showStatus(status, "❌ Your browser does not support GPS location.", "err");
    return;
  }

  showStatus(status, "📡 Getting your location…", "loading");

  navigator.geolocation.getCurrentPosition(
    function (position) {
      let lat = position.coords.latitude.toFixed(6);
      let lng = position.coords.longitude.toFixed(6);

      fetch(
        "https://nominatim.openstreetmap.org/reverse?lat=" +
          lat +
          "&lon=" +
          lng +
          "&format=json",
      )
        .then(function (response) {
          return response.json();
        })
        .then(function (data) {
          let addr = data.address;
          let parts = [
            addr.road,
            addr.suburb || addr.city_district,
            addr.city || addr.town,
            addr.state,
          ];
          let shortAddress = parts.filter(Boolean).join(", ");

          input.value = shortAddress || lat + ", " + lng;
          showStatus(status, "✅ Location found: " + input.value, "ok");
        })
        .catch(function () {
          input.value = lat + ", " + lng;
          showStatus(status, "✅ GPS coordinates: " + lat + ", " + lng, "ok");
        });
    },

    function (error) {
      let message = "❌ Could not get location.";
      if (error.code === 1)
        message =
          "❌ Permission denied. Please allow location access in your browser settings.";
      if (error.code === 2)
        message =
          "❌ GPS signal unavailable. Make sure location is enabled on your device.";
      if (error.code === 3)
        message = "❌ Location request timed out. Please try again.";
      showStatus(status, message, "err");
    },

    { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 },
  );
}

function showStatus(element, message, type) {
  element.textContent = message;
  element.className = "gps-status " + type;
}

function filterWorkshops(query) {
  let q = query.toLowerCase();
  let cards = document.querySelectorAll(".workshop-card");

  cards.forEach(function (card) {
    let name = card.getAttribute("data-name") || "";
    let tags = card.getAttribute("data-tags") || "";

    if (name.includes(q) || tags.includes(q) || q === "") {
      card.style.display = "";
    } else {
      card.style.display = "none";
    }
  });
}

function showToast(message, icon) {
  icon = icon || "✅";
  let toast = document.getElementById("toast");
  document.getElementById("toast-icon").textContent = icon;
  document.getElementById("toast-msg").textContent = message;
  toast.classList.add("show");
  setTimeout(function () {
    toast.classList.remove("show");
  }, 3500);
}

function submitRequest(event) {
  event.preventDefault();

  let name = document.getElementById("req-name").value.trim();
  let phone = document.getElementById("req-phone").value.trim();
  let location = document.getElementById("req-location").value.trim();
  let service = document.getElementById("req-service").value;

  if (!name) {
    showToast("Please enter your name.", "⚠️");
    return;
  }
  if (!phone) {
    showToast("Please enter your phone number.", "⚠️");
    return;
  }
  if (!location) {
    showToast("Please share your GPS location first.", "⚠️");
    return;
  }
  if (!service) {
    showToast("Please select a service type.", "⚠️");
    return;
  }

  hideModal("modal-request");
  showToast("Request sent! A workshop will contact you shortly.", "🔧");

  event.target.reset();
}

function submitEmergency(e) {
  e.preventDefault();

  const formData = new FormData();

  formData.append("name", document.getElementById("em-name").value);
  formData.append("phone", document.getElementById("em-phone").value);
  formData.append("landmark", document.getElementById("em-landmark").value);
  formData.append("location", document.getElementById("em-location").value);
  formData.append("type", document.getElementById("em-type").value);

  fetch("backend/save_emergency.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.text())
    .then((data) => {
      console.log(data);
      alert(data);
    })
    .catch((error) => console.error(error));
}
