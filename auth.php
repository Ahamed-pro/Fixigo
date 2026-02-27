<?php
$openTab    = (isset($_GET["tab"])      && $_GET["tab"]      === "register") ? "register" : "login";
$errorMsg   = isset($_GET["error"])      ? htmlspecialchars($_GET["error"])  : "";
$registered = isset($_GET["registered"]) ? $_GET["registered"]               : "";
$regType    = isset($_GET["type"])       ? $_GET["type"]                     : "user";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Fixigo – Sign In / Register</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='20' fill='%23FF5C1A'/><text y='.9em' font-size='70' x='12'>🔧</text></svg>"/>
  <link rel="stylesheet" href="css/auth1.css">
</head>
<body>

<canvas id="dot-canvas"></canvas>


<nav class="topbar">
  <a href="index.php" class="nav-logo">
    <div class="logo-icon">🔧</div>
    <span class="logo-text">Fix<span>igo</span></span>
  </a>
  <a href="index.php" class="back-btn">← Back to Home</a>
</nav>

<main class="page">
  <div class="auth-wrap">


    <div class="auth-tabs">
      <button class="auth-tab <?= $openTab==='login' ? 'active' : '' ?>" onclick="switchTab('login')">Sign In</button>
      <button class="auth-tab <?= $openTab==='register' ? 'active' : '' ?>" onclick="switchTab('register')">Create Account</button>
    </div>


    <div id="panel-login" class="auth-card" <?= $openTab==='register' ? 'style="display:none"' : '' ?>>
      <div id="login-form-wrap">
        <h1 class="auth-heading">Welcome back 👋</h1>
        <p class="auth-sub">Don't have an account? <a href="#" onclick="switchTab('register')">Sign up free</a></p>

        <div class="social-row">
          <a href="#" class="social-btn"><span class="social-icon">🌐</span> Google</a>
          <a href="#" class="social-btn"><span class="social-icon">📘</span> Facebook</a>
        </div>
        <div class="divider"><span>or sign in with email</span></div>

        <form id="form-login" action="backend/login.php" method="POST">
          <div class="form-group">
            <label><span>📧</span> Email Address <span class="req">*</span></label>
            <div class="input-wrap">
              <span class="input-icon">@</span>
              <input type="email" id="login-email" name="email" placeholder="you@example.com" required/>
            </div>
          </div>
          <div class="form-group">
            <label><span>🔒</span> Password <span class="req">*</span></label>
            <div class="input-wrap">
              <span class="input-icon">🔑</span>
              <input type="password" id="login-pass" name="password" placeholder="••••••••" required/>
              <button type="button" class="pass-toggle" onclick="togglePass('login-pass',this)">👁</button>
            </div>
          </div>
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:22px;font-size:13px">
            <label class="checkbox-wrap" style="margin:0">
              <input type="checkbox"/> <span>Remember me</span>
            </label>
            <a href="password/forgot_password.php" style="color:var(--orange);text-decoration:none;font-size:13px">Forgot password?</a>
          </div>
          <button type="submit" class="btn-submit">Sign In →</button>
        </form>
      </div>

 
      <div id="login-success" class="success-screen">
        <span class="success-icon">✅</span>
        <h2 class="success-title">You're in!</h2>
        <p class="success-msg">Welcome back to Fixigo. Redirecting to your dashboard…</p>
        <a href="user/user_index_dashboard.php" class="btn-go">Go to Dashboard →</a>
      </div>
    </div>


    <div id="panel-register" class="auth-card" <?= $openTab==='register' ? '' : 'style="display:none"' ?>>
      <div id="register-form-wrap">
        <h1 class="auth-heading">Create your account</h1>
        <p class="auth-sub">Already registered? <a href="#" onclick="switchTab('login')">Sign in</a></p>

        <form id="form-register" action="backend/register.php" method="POST">

  
        <div style="margin-bottom:20px">
          <p style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.8px;color:var(--text-muted);margin-bottom:12px">I am a…</p>
          <div class="type-picker">
            <label class="type-option">
              <input type="radio" name="account-type" value="user" checked onchange="onTypeChange(this)"/>
              <div class="type-label">
                <span class="type-icon">🚗</span>
                <span class="type-name">Vehicle Owner</span>
                <span class="type-desc">Find workshops & request service</span>
              </div>
            </label>
            <label class="type-option">
              <input type="radio" name="account-type" value="workshop" onchange="onTypeChange(this)"/>
              <div class="type-label">
                <span class="type-icon">🔧</span>
                <span class="type-name">Workshop Owner</span>
                <span class="type-desc">List your workshop & get clients</span>
                <span class="type-badge">Registration fee applies</span>
              </div>
            </label>
          </div>
        </div>

        <div class="divider"><span>account details</span></div>


          <div class="form-row">
            <div class="form-group">
              <label>First Name <span class="req">*</span></label>
              <div class="input-wrap">
                <span class="input-icon">👤</span>
                <input type="text" id="r-fname" name="first_name" placeholder="Kamal" required/>
              </div>
            </div>
            <div class="form-group">
              <label>Last Name <span class="req">*</span></label>
              <div class="input-wrap no-icon">
                <input type="text" id="r-lname" name="last_name" placeholder="Perera" required/>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label>Email Address <span class="req">*</span></label>
            <div class="input-wrap">
              <span class="input-icon">@</span>
              <input type="email" id="r-email" name="email" placeholder="you@example.com" required/>
            </div>
          </div>

          <div class="form-group">
            <label>Phone Number <span class="req">*</span></label>
            <div class="input-wrap">
              <span class="input-icon">📞</span>
              <input type="tel" id="r-phone" name="phone" placeholder="+94 77 000 0000" required/>
            </div>
          </div>

    
          <div class="form-group">
            <label>Password <span class="req">*</span></label>
            <div class="input-wrap">
              <span class="input-icon">🔑</span>
              <input type="password" id="r-pass" name="password" placeholder="Create a strong password" required oninput="checkStrength(this)"/>
              <button type="button" class="pass-toggle" onclick="togglePass('r-pass',this)">👁</button>
            </div>
            <div class="strength-bar">
              <div class="strength-seg" id="s1"></div>
              <div class="strength-seg" id="s2"></div>
              <div class="strength-seg" id="s3"></div>
              <div class="strength-seg" id="s4"></div>
            </div>
            <div class="strength-label" id="strength-label">Enter a password</div>
          </div>


          <div id="workshop-fields" style="display:none;animation:fadeIn .4s ease">
            <div class="section-sep"></div>
            <p style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.8px;color:var(--text-muted);margin-bottom:16px">🏪 Workshop Details</p>

            <div class="form-group">
              <label>Workshop Name <span class="req">*</span></label>
              <div class="input-wrap">
                <span class="input-icon">🏪</span>
                <input type="text" id="w-name" name="workshop_name" placeholder="e.g. AutoTech Pro Workshop"/>
              </div>
            </div>

            <div class="form-group">
              <label>Business Registration No.</label>
              <div class="input-wrap">
                <span class="input-icon">📄</span>
                <input type="text" id="w-regno" name="business_reg" placeholder="e.g. BR/12345/PV"/>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label>District <span class="req">*</span></label>
                <div class="input-wrap no-icon">
                  <select id="w-district" name="district">
                    <option value="">Select district</option>
                    <option>Colombo</option><option>Gampaha</option><option>Kalutara</option>
                    <option>Kandy</option><option>Matale</option><option>Nuwara Eliya</option>
                    <option>Galle</option><option>Matara</option><option>Hambantota</option>
                    <option>Jaffna</option><option>Kilinochchi</option><option>Mannar</option>
                    <option>Mullaitivu</option><option>Vavuniya</option><option>Trincomalee</option>
                    <option>Batticaloa</option><option>Ampara</option><option>Kurunegala</option>
                    <option>Puttalam</option><option>Anuradhapura</option><option>Polonnaruwa</option>
                    <option>Badulla</option><option>Moneragala</option><option>Ratnapura</option><option>Kegalle</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label>Specialisation <span class="req">*</span></label>
                <div class="input-wrap no-icon">
                  <select id="w-spec" name="specialisation">
                    <option value="">Select type</option>
                    <option>General Repairs</option>
                    <option>Engine Specialist</option>
                    <option>Electrical / Diagnostics</option>
                    <option>Tyre & Wheel</option>
                    <option>Body & Paint</option>
                    <option>AC Repair</option>
                    <option>Transmission</option>
                    <option>Multi-service</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label>Workshop Address <span class="req">*</span></label>
              <div class="input-wrap">
                <span class="input-icon">📍</span>
                <div class="location-field" style="flex:1;position:relative">
                  <input type="text" id="w-address" name="address" placeholder="Street, City" style="padding-right:50px;border-radius:10px;padding-left:0"/>
                  <button type="button" class="location-btn" id="loc-btn-workshop" onclick="getLocation('w-address','loc-status-workshop')" title="Get my current location">📡</button>
                </div>
              </div>
              <div class="location-status" id="loc-status-workshop"></div>
            </div>

    
            <div class="section-sep"></div>
            <div class="payment-section visible" id="payment-section">
              <div class="payment-header">
                <div class="payment-title">💳 Registration Fee</div>
                <div class="payment-amount">LKR 4,999 <span>/ one-time</span></div>
              </div>
              <div class="payment-features">
                <div class="pay-feat">Listed on Fixigo Workshop Directory</div>
                <div class="pay-feat">Receive unlimited service requests</div>
                <div class="pay-feat">Verified Workshop badge</div>
                <div class="pay-feat">Emergency dispatch eligibility</div>
                <div class="pay-feat">Analytics dashboard access</div>
              </div>
              <p style="font-size:11px;text-transform:uppercase;letter-spacing:.8px;color:var(--text-muted);font-weight:600;margin-bottom:10px">Payment Method</p>
              <div class="payment-methods">
                <div class="pay-method selected" onclick="selectPayMethod(this,'card')">💳 Card</div>
              </div>


              <div class="card-fields visible" id="card-fields">
                <div class="form-group" style="margin-bottom:12px">
                  <label style="margin-bottom:6px">Card Number</label>
                  <div class="input-wrap">
                    <span class="input-icon">💳</span>
                    <input type="text" id="card-num" name="card_number" placeholder="1234 5678 9012 3456" maxlength="19" oninput="formatCard(this)"/>
                  </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                  <div class="form-group" style="margin-bottom:0">
                    <label style="margin-bottom:6px">Expiry</label>
                    <div class="input-wrap no-icon">
                      <input type="text" placeholder="MM / YY" name="expiry" maxlength="7" oninput="formatExpiry(this)"/>
                    </div>
                  </div>
                  <div class="form-group" style="margin-bottom:0">
                    <label style="margin-bottom:6px">CVV</label>
                    <div class="input-wrap no-icon">
                      <input type="password" placeholder="•••" maxlength="4"/>
                    </div>
                  </div>
                </div>
                  <div class="form-group" style="margin-top:10px;margin-bottom:0">
                  <label style="margin-bottom:6px">Amount paying :</label>
                  <div class="input-wrap no-icon">
                    <input type="number" name="payamount" placeholder="Amount paying :"/>
                  </div>
                </div>
                <div class="form-group" style="margin-top:10px;margin-bottom:0">
                  <label style="margin-bottom:6px">Cardholder Name</label>
                  <div class="input-wrap no-icon">
                    <input type="text" name="cardholder_name" placeholder="Name on card"/>
                  </div>
                </div>
              </div>

     
           

              <div style="display:flex;align-items:center;gap:8px;margin-top:14px;font-size:11px;color:var(--text-dim)">
                🔒 Payments are secured with 256-bit SSL encryption
              </div>
            </div>
          </div>

      
          <label class="checkbox-wrap">
            <input type="checkbox" id="r-terms" required/>
            <span>I agree to Fixigo's <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></span>
          </label>

          <label class="checkbox-wrap">
            <input type="checkbox" id="r-updates"/>
            <span>Send me service updates and offers via email</span>
          </label>

          <button type="submit" class="btn-submit" id="btn-register">
            <span id="btn-register-text">Create Account</span>
          </button>
        </form>
      </div>

     
      <div id="register-success" class="success-screen">
        <span class="success-icon" id="reg-success-icon">🎉</span>
        <h2 class="success-title" id="reg-success-title">Account Created!</h2>
        <p class="success-msg" id="reg-success-msg">Welcome to Fixigo. Your account is ready.</p>
        <a href="workshop/workshop_index_dashboard.php" class="btn-go" id="reg-success-btn">Go to Dashboard →</a>
      </div>
    </div>
 

  </div>
</main>

<div class="modal-overlay" id="modal-request">
  <div class="modal-box">
    <button class="modal-close" onclick="closeModal()">✕</button>
    <div class="modal-badge">🔧 Service</div>
    <h2 class="modal-title">Request Service</h2>
    <p class="modal-sub">Contact and location are required so the workshop can reach you.</p>

    <form id="form-request" novalidate>
      <div class="form-row">
        <div class="form-group">
          <label>Your Name <span class="req">*</span></label>
          <div class="input-wrap">
            <span class="input-icon">👤</span>
            <input type="text" id="req-name" placeholder="Full name" required/>
          </div>
        </div>
        <div class="form-group">
          <label>Phone <span class="req">*</span></label>
          <div class="input-wrap">
            <span class="input-icon">📞</span>
            <input type="tel" id="req-phone" placeholder="+94 77 …" required/>
          </div>
        </div>
      </div>

  
      <div class="form-group">
        <label>Your Location <span class="req">*</span></label>
        <div class="input-wrap location-field">
          <span class="input-icon">📍</span>
          <input type="text" id="req-location" placeholder="Tap 📡 to get your GPS location" required readonly style="cursor:default"/>
          <button type="button" class="location-btn" id="loc-btn-req" onclick="getLocation('req-location','loc-status-req')" title="Share my GPS location">📡</button>
        </div>
        <div class="location-status" id="loc-status-req"></div>
        <input type="hidden" id="req-lat"/>
        <input type="hidden" id="req-lng"/>
        <p class="field-hint">⚠️ Location is mandatory. Please enable GPS on your device.</p>
      </div>

      <div class="form-group">
        <label>Vehicle Make & Model</label>
        <div class="input-wrap">
          <span class="input-icon">🚗</span>
          <input type="text" id="req-vehicle" placeholder="e.g. Toyota Axio 2018"/>
        </div>
      </div>

      <div class="form-group">
        <label>Service Type <span class="req">*</span></label>
        <div class="input-wrap no-icon">
          <select id="req-service" required>
            <option value="">Select a service…</option>
            <option>Engine Repair</option><option>Tyre Change</option>
            <option>Oil Change</option><option>AC Repair</option>
            <option>Brake Service</option><option>Battery Replacement</option>
            <option>Electrical Issue</option><option>Body Work</option><option>Other</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label>Describe Your Issue</label>
        <div class="input-wrap no-icon">
          <textarea id="req-desc" placeholder="Tell the workshop what's happening…"></textarea>
        </div>
      </div>

      <button type="submit" class="btn-submit">Send Request 🔧</button>
    </form>
  </div>
</div>


<div class="toast" id="toast">
  <span class="toast-icon" id="t-icon">✅</span>
  <div class="toast-body">
    <div class="toast-title" id="t-title">Done!</div>
    <div class="toast-msg" id="t-msg"></div>
  </div>
</div>

<script src="js/auth1.js"></script>

<script>

window.addEventListener('load', function() {

    <?php if (!empty($errorMsg)): ?>
    showToast('Error', <?= json_encode($errorMsg) ?>, '❌', true);

    <?php elseif ($registered === 'yes' && $regType === 'workshop'): ?>
    showToast('Workshop Registered! 🏪', 'Your account is ready. Please sign in.', '✅');

    <?php elseif ($registered === 'yes'): ?>
    showToast('Account Created! 🎉', 'Welcome! You can now sign in.', '✅');
    <?php endif; ?>

});
</script>
</body>
</html>