<?php

$site_name = "Fixigo";
?>
<?php
include "config/db.php";


$sql = "
    SELECT w.*, u.full_name 
    FROM workshops w
    JOIN users u ON w.user_id = u.id
    WHERE u.account_type = 'workshop'
";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fixigo – Find Nearby Workshops</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='20' fill='%23FF5C1A'/><text y='.9em' font-size='70' x='12'>🔧</text></svg>"/>

  <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="helpcenter/stylechat.css">
</head>
<body>


  <canvas id="dots"></canvas>

 
  <nav id="navbar">
    <a href="index.php" class="logo">
      <div class="logo-icon">🔧</div>
      <span>Fix<b>igo</b></span>
    </a>

    <ul class="nav-links">
      <li><a href="#services">Services</a></li>
      <li><a href="#how">How It Works</a></li>
      <li><a href="#workshops">Workshops</a></li>
      <li><a href="#emergency">Emergency</a></li>
      <li><a href="footer/contact.php">Contact Us</a></li>
    </ul>

 <div class="nav-btns">
  <a href="auth.php" class="btn-outline">Sign In</a>
  <a href="auth.php?tab=register" class="btn-orange">Get Started</a>
</div>
  </nav>


  <section class="hero">
    <div class="hero-text">
      <div class="badge"><span class="dot"></span> Live Service Network</div>

      <h1>Your Car Trouble,<br><span class="orange">Fixed Fast.</span><br><span class="ghost">Guaranteed.</span></h1>

      <p>Fixigo connects you with trusted, certified workshops nearby. From routine service to emergency roadside help — we've got you covered.</p>

      <div class="hero-btns">
        <a href="#workshops" class="btn-orange btn-big">🔍 Find Workshops</a>
        <button class="btn-emergency btn-big" onclick="showModal('modal-emergency')">
          <span class="flash">🚨</span> Emergency SOS
        </button>
      </div>

   
      <div class="stats">
        <div class="stat">
          <strong data-count="2400">0</strong>+
          <span>Workshops</span>
        </div>
        <div class="stat">
          <strong data-count="48000">0</strong>+
          <span>Cars Serviced</span>
        </div>
        <div class="stat">
          <strong data-count="98">0</strong>%
          <span>Satisfaction</span>
        </div>
      </div>
    </div>


    <div class="hero-card">
      <div class="map-box">
        <div class="map-grid"></div>
        <div class="pin" style="top:40%;left:45%"><div class="ring"></div><div class="dot-pin orange-pin"></div></div>
        <div class="pin" style="top:25%;left:65%"><div class="ring"></div><div class="dot-pin green-pin"></div></div>
        <div class="pin" style="top:60%;left:30%"><div class="ring"></div><div class="dot-pin yellow-pin"></div></div>
      </div>
      <div class="mini-card">
        <div class="mini-icon">🔧</div>
        <div>
          <strong>AutoTech Pro</strong>
          <small>📍 2.1 km · Colombo 03</small>
        </div>
        <span class="rating">★ 4.9</span>
      </div>
      <div class="mini-card">
        <div class="mini-icon">🏎</div>
        <div>
          <strong>SpeedFix Garage</strong>
          <small>📍 3.4 km · Nugegoda</small>
        </div>
        <span class="rating">★ 4.7</span>
      </div>
    </div>
      
    
<button id="help-btn" onclick="toggleChat()">
  <span id="help-btn-icon">🤖</span>
  <span id="help-btn-text">Need Help?</span>
</button>


<div id="chat-container">


  <div id="chat-header">
    <div style="display:flex;align-items:center;gap:10px">
      <div id="bot-avatar">🤖</div>
      <div>
        <div style="font-size:14px;font-weight:800">Fixigo Assistant</div>
        <div id="bot-status">● Online</div>
      </div>
    </div>
    <button onclick="toggleChat()" title="Close">✕</button>
  </div>


  <div id="quick-suggestions">
    <div id="suggestions-label">Quick questions:</div>
    <div id="suggestions-row">
      <button class="suggestion-btn" onclick="sendSuggestion(this)">How to register?</button>
      <button class="suggestion-btn" onclick="sendSuggestion(this)">Book a service</button>
      <button class="suggestion-btn" onclick="sendSuggestion(this)">Emergency SOS</button>
      <button class="suggestion-btn" onclick="sendSuggestion(this)">Payment help</button>
    </div>
  </div>

 
  <div id="chat-messages"></div>


  <div id="chat-footer">
    <input type="text" id="chat-input" placeholder="Ask me anything…" onkeydown="if(event.key==='Enter') sendMessage()" autocomplete="off">
    <button id="send-btn" onclick="sendMessage()">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
    </button>
  </div>

</div>






  </section>


  <section class="section dark-bg" id="how">
    <div class="container">
      <p class="label">Process</p>
      <h2>How Fixigo Works</h2>
      <p class="desc">Get your vehicle sorted in four simple steps.</p>

      <div class="steps">
        <div class="step reveal">
          <div class="step-num">01</div>
          <div class="step-icon">📍</div>
          <h3>Share Location</h3>
          <p>Allow location access to find certified workshops near you.</p>
        </div>
        <div class="step reveal">
          <div class="step-num">02</div>
          <div class="step-icon">🔧</div>
          <h3>Choose Workshop</h3>
          <p>Browse ratings and availability. Pick the best workshop for you.</p>
        </div>
        <div class="step reveal">
          <div class="step-num">03</div>
          <div class="step-icon">📋</div>
          <h3>Send Request</h3>
          <p>Describe your issue and submit your request in seconds.</p>
        </div>
        <div class="step reveal">
          <div class="step-num">04</div>
          <div class="step-icon">✅</div>
          <h3>Get Fixed</h3>
          <p>The workshop confirms and your car gets expertly fixed.</p>
        </div>
      </div>
    </div>
  </section>

 
  <section class="section" id="services">
    <div class="container">
      <p class="label">What We Offer</p>
      <h2>All the Services You Need</h2>
      <p class="desc">From routine maintenance to 24/7 emergency rescue.</p>

      <div class="services">
        <div class="service-card reveal">
          <span class="svc-icon">🔍</span>
          <h3>Find Workshops</h3>
          <p>Search certified workshops near you with live availability and ratings.</p>
        </div>
        <div class="service-card featured reveal">
          <span class="svc-icon">🚨</span>
          <h3>Emergency Help</h3>
          <p>Stranded? Get instant emergency assistance dispatched 24/7.</p>
        </div>
        <div class="service-card reveal">
          <span class="svc-icon">📋</span>
          <h3>Service Requests</h3>
          <p>Submit a request and let workshops come to you with quotes.</p>
        </div>
        <div class="service-card reveal">
          <span class="svc-icon">⭐</span>
          <h3>Verified Reviews</h3>
          <p>Read honest reviews from real customers before choosing.</p>
        </div>
        <div class="service-card reveal">
          <span class="svc-icon">🗓</span>
          <h3>Easy Scheduling</h3>
          <p>Book appointments at your preferred time with reminders.</p>
        </div>
        <div class="service-card reveal">
          <span class="svc-icon">🔔</span>
          <h3>Live Updates</h3>
          <p>Get real-time status updates on your vehicle service.</p>
        </div>
      </div>
    </div>
  </section>


  <section class="section" id="workshops">
    <div class="container center">
      <p class="label">Directory</p>
      <h2>Find Nearby Workshops</h2>
      <p class="desc">Browse verified, certified workshops near you.</p>

      <div class="search-box">
        <span>🔍</span>
        <input type="text" id="search" placeholder="Search workshops or service type…" oninput="filterWorkshops(this.value)">
        <button class="btn-orange">Search</button>
      </div>






<?php
       function pickRandomEmoji() { 
$emojis = ["🚗", "⚙", "🔧", "⛓", "🧰", "💼"]; return $emojis[array_rand($emojis)];
 }
?>

      <div class="workshops" id="workshops-list">
              <?php if ($result && $result->num_rows > 0): ?>
       
    <?php while($row = $result->fetch_assoc()): ?>

        <div class="workshop-card reveal"
             data-name="<?php echo strtolower(htmlspecialchars($row['workshop_name'])); ?>"
             data-tags="<?php echo strtolower(htmlspecialchars($row['specialisation'])); ?>">

            <div class="wk-img">
           <?php echo pickRandomEmoji(); ?><span class="open-tag">Open Now</span>
            </div>

            <div class="wk-body">

                <div class="tags">
                    <span><?php echo htmlspecialchars($row['specialisation']); ?></span>
                </div>

                <h3><?php echo htmlspecialchars($row['workshop_name']); ?></h3>

                <p class="location">
                    📍 <?php echo htmlspecialchars($row['district']); ?>
                </p>

                <div class="wk-footer">
                    <span class="stars">★ 4.8 <small>(New)</small></span>

                    <a href="auth.php"><button class="btn-req"
                        onclick="">
                        Request →
                    </button>
                    </a>
                </div>

            </div>
        </div>

    <?php endwhile; ?>

<?php else: ?>

    <p style="color:white; text-align:center;">No workshops found.</p>

<?php endif; ?>
        </div>

      </div>
    </div>
  </section>


  <section class="section dark-bg" id="emergency">
    <div class="container">
      <div class="emergency-box reveal">
        <div class="em-badge"><span class="live-dot"></span> 24 / 7 Available</div>
        <h2>Stranded?<br>We're on our way.</h2>
        <p>Flat tyre, dead battery, breakdown — whatever the emergency, we dispatch the nearest mechanic to your location within minutes.</p>
        <button class="btn-sos" onclick="showModal('modal-emergency')">🚨 Activate SOS Now</button>
      </div>
    </div>
  </section>


  <section class="section dark-bg">
    <div class="container">
      <p class="label">Reviews</p>
      <h2>Loved by Drivers</h2>

      <div class="reviews">
        <div class="review-card reveal">
          <div class="quote">"</div>
          <p>Found a great workshop in minutes! The request system is brilliant. They fixed my car same day.</p>
          <div class="reviewer"><span>😊</span><div><strong>Kamal Perera</strong><small>Toyota Axio Owner</small></div></div>
        </div>
        <div class="review-card reveal">
          <div class="quote">"</div>
          <p>Emergency service saved me when I got a flat tyre at midnight. Fast, reliable, and affordable.</p>
          <div class="reviewer"><span>😄</span><div><strong>Nisha Fernando</strong><small>Honda City Owner</small></div></div>
        </div>
        <div class="review-card reveal">
          <div class="quote">"</div>
          <p>We use Fixigo for our entire fleet. The booking system and real-time tracking is a game changer.</p>
          <div class="reviewer"><span>🙂</span><div><strong>Rajiv Mendis</strong><small>Fleet Manager</small></div></div>
        </div>
      </div>
    </div>
  </section>


  <footer>
    <div class="container">
      <div class="footer-grid">
        <div>
          <a href="index.php" class="logo"><div class="logo-icon">🔧</div><span>Fix<b>igo</b></span></a>
          <p>Connecting drivers with trusted workshops. Fast, transparent, always on your side.</p>
          <div class="socials">
            <a href="#">𝕏</a><a href="#">in</a><a href="#">f</a><a href="#">▶</a>
          </div>
        </div>
        <div>
          <h4>Services</h4>
          <ul>
            <li><a href="#">Find Workshops</a></li>
            <li><a href="#">Emergency SOS</a></li>
            <li><a href="#">Request Service</a></li>
            <li><a href="#">Schedule Booking</a></li>
          </ul>
        </div>
        <div>
          <h4>Company</h4>
          <ul>
            <li><a href="../FixigoF/footer/about.php">About Fixigo</a></li>
            <li><a href="#">For Workshops</a></li>
            <li><a href="#">Careers</a></li>
            <li><a href="#">Blog</a></li>
          </ul>
        </div>
        <div>
          <h4>Support</h4>
          <ul>
            <li><a href="footer/help.php">Help Center</a></li>
            <li><a href="footer/contact.php">Contact Us</a></li>
            <li><a href="review/reviews.php">Reviews</a></li>
            <li><a href="#">Terms</a></li>
          </ul>
        </div>
      </div>
      <div class="footer-bottom">
        <span>© <?= date('Y') ?> Fixigo. All rights reserved.</span>
        <span>Sri Lanka's #1 Workshop Network</span>
      </div>
    </div>
  </footer>


  <div class="modal-overlay" id="modal-request" onclick="closeModalOutside(event)">
    <div class="modal">
      <button class="close-btn" onclick="hideModal('modal-request')">✕</button>
      <h2>Request Service 🔧</h2>
      <p>Contact and location are required.</p>

      <form onsubmit="submitRequest(event)">
        <label>Your Name *</label>
        <input type="text" id="req-name" placeholder="Full name" required>

        <label>Phone Number *</label>
        <input type="tel" id="req-phone" placeholder="+94 77 000 0000" required>

        <label>Your Location * <small style="color:#FF5C1A">(GPS required)</small></label>
        <div class="location-row">
          <input type="text" id="req-location" placeholder="Tap 📡 to get GPS location" readonly required>
          <button type="button" class="gps-btn" onclick="getGPS('req-location', 'req-status')">📡</button>
        </div>
        <small id="req-status" class="gps-status"></small>

        <label>Service Type *</label>
        <select id="req-service" required>
          <option value="">Select service…</option>
          <option>Engine Repair</option>
          <option>Tyre Change</option>
          <option>Oil Change</option>
          <option>AC Repair</option>
          <option>Brake Service</option>
          <option>Battery Replacement</option>
          <option>Electrical Issue</option>
          <option>Other</option>
        </select>

        <label>Describe the Issue</label>
        <textarea id="req-desc" placeholder="What's happening with your vehicle?"></textarea>

        <div class="modal-btns">
          <button type="button" onclick="hideModal('modal-request')">Cancel</button>
          <button type="submit" class="btn-orange">Send Request</button>
        </div>
      </form>
    </div>
  </div>

 
  <div class="modal-overlay" id="modal-emergency" onclick="closeModalOutside(event)">
    <div class="modal">
      <button class="close-btn" onclick="hideModal('modal-emergency')">✕</button>
      <h2>🚨 Emergency SOS</h2>
      <p>We'll dispatch the nearest mechanic immediately.</p>

      <form onsubmit="submitEmergency(event)">
        <label>Your Name *</label>
        <input type="text" id="em-name" placeholder="Full name" required>

        <label>Phone Number *</label>
        <input type="tel" id="em-phone" placeholder="+94 77 000 0000" required>
        
        <label>land Mark *</label>
        <input type="text" id="em-landmark" placeholder="eg: next to the lake" required>

        <label>Your Location * <small style="color:#FF5C1A">(GPS required)</small></label>
        <div class="location-row">
          <input type="text" id="em-location" placeholder="Tap 📡 to share GPS location" readonly required>
          <button type="button" class="gps-btn" onclick="getGPS('em-location', 'em-status')">📡</button>
        </div>
        <small id="em-status" class="gps-status"></small>

        <label>Emergency Type *</label>
    <select id="em-type" required>
  <option value="">What happened?</option>
  <option value="accident">Accident</option>
  <option value="medical">Medical Emergency (1990)</option>
  <option value="police">Inform Police (119)</option>
  <option value="other">Other</option>
</select>

        <div class="modal-btns">
          <button type="button" onclick="hideModal('modal-emergency')">Cancel</button>
          <button type="submit" class="btn-sos-sm">🚨 Send SOS</button>
        </div>
      </form>
    </div>
  </div>


  <div id="toast" class="toast">
    <span id="toast-icon">✅</span>
    <span id="toast-msg">Done!</span>
  </div>

  <script src="js/main.js"></script>
   <script src="helpcenter/scriptchat.js"></script>
</body>
</html>



