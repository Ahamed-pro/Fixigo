<?php

session_start();
include "../config/db.php";


$avg_result = $conn->query("SELECT AVG(rating) as avg, COUNT(*) as total FROM reviews WHERE is_approved=1");
$stats      = $avg_result->fetch_assoc();
$avg_rating = round((float)($stats['avg'] ?? 0), 1);
$total_reviews = (int)($stats['total'] ?? 0);


$dist = [];
for ($i = 5; $i >= 1; $i--) {
    $r = $conn->query("SELECT COUNT(*) as c FROM reviews WHERE is_approved=1 AND rating=$i");
    $dist[$i] = (int)$r->fetch_assoc()['c'];
}


$filter_cat   = $_GET['cat']    ?? 'all';
$filter_stars = (int)($_GET['stars'] ?? 0);
$sort         = $_GET['sort']   ?? 'newest';

$where = "WHERE is_approved=1";
if ($filter_cat !== 'all')  $where .= " AND category='" . $conn->real_escape_string($filter_cat) . "'";
if ($filter_stars > 0)      $where .= " AND rating=$filter_stars";
$order = $sort === 'highest' ? "rating DESC, created_at DESC"
       : ($sort === 'lowest' ? "rating ASC, created_at DESC" : "created_at DESC");

$reviews_result = $conn->query("SELECT * FROM reviews $where ORDER BY $order");
$reviews = [];
while ($r = $reviews_result->fetch_assoc()) $reviews[] = $r;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reviews & Ratings — Fixigo</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='20' fill='%23FF5C1A'/><text y='.9em' font-size='70' x='12'>🔧</text></svg>"/>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="review.css">
</head>
<body>
<canvas id="dots"></canvas>


<nav class="nav">
  <a href="../index.php" class="nav-logo"><div class="li">🔧</div><span>Fix<b>igo</b></span></a>
  <div class="nav-links">
    <a href="../index.php">Home</a>
    <a href="../footer/about.php">About</a>
    <a href="../footer/contact.php">Contact</a>
    <a href="../footer/help.php">Help</a>
    <a href="reviews.php" class="active">Reviews</a>
    <a href="../auth.php" class="nav-cta">Get Started</a>
  </div>
  <button class="nav-menu-btn" onclick="toggleMenu()">☰</button>
</nav>
<div class="mobile-menu" id="mobile-menu">
  <a href="../index.php">🏠 Home</a>
  <a href="../footer/about.php">ℹ️ About</a>
  <a href="../footer/contact.php">📞 Contact</a>
  <a href="../footer/help.php">❓ Help</a>
  <a href="reviews.php">⭐ Reviews</a>
  <a href="../auth.php" style="color:var(--orange);font-weight:700">→ Get Started</a>
</div>

<div class="page-wrap">


  <div class="page-hero">
    <div class="page-tag">⭐ Customer Reviews</div>
    <h1>What Our <span>Customers Say</span></h1>
    <p>Real reviews from real vehicle owners and workshop partners across Sri Lanka.</p>
  </div>

 
  <?php if ($total_reviews > 0): ?>
  <div class="rating-hero">
    <div class="big-rating">
      <div class="big-num"><?= $avg_rating ?></div>
      <div class="big-stars"><?= str_repeat('⭐', round($avg_rating)) ?><?= str_repeat('☆', 5 - round($avg_rating)) ?></div>
      <div class="big-count"><?= $total_reviews ?> review<?= $total_reviews !== 1 ? 's' : '' ?></div>
    </div>
    <div class="star-bars">
      <?php for ($i = 5; $i >= 1; $i--):
        $pct = $total_reviews > 0 ? round(($dist[$i] / $total_reviews) * 100) : 0; ?>
      <div class="sbar-row">
        <span class="sbar-label"><?= $i ?>★</span>
        <div class="sbar-track"><div class="sbar-fill" style="width:<?= $pct ?>%"></div></div>
        <span class="sbar-count"><?= $dist[$i] ?></span>
      </div>
      <?php endfor; ?>
    </div>
  </div>
  <?php endif; ?>


  <div class="form-section" id="write-review">
    <div class="form-title">Write a <span>Review</span></div>
    <div class="form-sub">Share your experience with Fixigo and help others make better decisions.</div>

    <div class="alert alert-ok" id="rv-ok">✅ Thank you! Your review has been posted.</div>
    <div class="alert alert-err" id="rv-err"></div>

    
    <div style="margin-bottom:4px;font-size:11px;text-transform:uppercase;letter-spacing:.7px;color:var(--text-dim)">Your Rating</div>
    <div class="star-picker" id="star-picker">
      <span data-val="1">⭐</span>
      <span data-val="2">⭐</span>
      <span data-val="3">⭐</span>
      <span data-val="4">⭐</span>
      <span data-val="5">⭐</span>
    </div>
    <div class="star-hint" id="star-hint">Click to rate</div>
    <input type="hidden" id="rv-rating" value="0">

    <div class="form-grid">
      <div class="fgroup">
        <label>Your Name *</label>
        <input type="text" id="rv-name" placeholder="e.g. Kavindu Perera" value="<?= isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : '' ?>">
      </div>
      <div class="fgroup">
        <label>Email Address *</label>
        <input type="email" id="rv-email" placeholder="your@email.com">
      </div>
    </div>

    <div class="fgroup" style="margin-bottom:16px">
      <label>Category</label>
      <select id="rv-category">
        <option value="General">General Experience</option>
        <option value="Workshop Quality">Workshop Quality</option>
        <option value="Customer Support">Customer Support</option>
        <option value="Emergency SOS">Emergency SOS</option>
        <option value="App & Platform">App & Platform</option>
        <option value="Payment">Payment</option>
      </select>
    </div>

    <div class="fgroup" style="margin-bottom:24px">
      <label>Your Review *</label>
      <textarea id="rv-text" placeholder="Tell us about your experience with Fixigo…"></textarea>
    </div>

    <button class="btn btn-or" onclick="submitReview()" id="rv-btn">Post Review ⭐</button>
  </div>

  
  <div>
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px">
      <div>
        <div class="section-title">Customer <span>Reviews</span></div>
        <div class="section-sub" style="margin-bottom:0"><?= count($reviews) ?> review<?= count($reviews) !== 1 ? 's' : '' ?><?= $filter_cat !== 'all' ? ' in ' . htmlspecialchars($filter_cat) : '' ?></div>
      </div>
    </div>

   
    <div class="filters-row">
      <div class="filter-pills">
        <a href="reviews.php" class="pill <?= $filter_cat==='all' && !$filter_stars ? 'active':'' ?>">All</a>
        <a href="?stars=5" class="pill <?= $filter_stars===5 ? 'active':'' ?>">⭐⭐⭐⭐⭐</a>
        <a href="?stars=4" class="pill <?= $filter_stars===4 ? 'active':'' ?>">⭐⭐⭐⭐</a>
        <a href="?stars=3" class="pill <?= $filter_stars===3 ? 'active':'' ?>">⭐⭐⭐</a>
        <a href="?cat=General"          class="pill <?= $filter_cat==='General'          ? 'active':'' ?>">General</a>
        <a href="?cat=Workshop Quality" class="pill <?= $filter_cat==='Workshop Quality' ? 'active':'' ?>">Workshop</a>
        <a href="?cat=Emergency SOS"    class="pill <?= $filter_cat==='Emergency SOS'    ? 'active':'' ?>">SOS</a>
        <a href="?cat=Customer Support" class="pill <?= $filter_cat==='Customer Support' ? 'active':'' ?>">Support</a>
      </div>
      <select class="sort-select" onchange="window.location='reviews.php?sort='+this.value+'<?= $filter_cat!=='all'?'&cat='.urlencode($filter_cat):'' ?><?= $filter_stars?'&stars='.$filter_stars:'' ?>'">
        <option value="newest"  <?= $sort==='newest'  ? 'selected':'' ?>>Newest First</option>
        <option value="highest" <?= $sort==='highest' ? 'selected':'' ?>>Highest Rated</option>
        <option value="lowest"  <?= $sort==='lowest'  ? 'selected':'' ?>>Lowest Rated</option>
      </select>
    </div>


    <div class="reviews-grid" id="reviews-grid">
      <?php if (empty($reviews)): ?>
      <div class="empty" style="grid-column:1/-1">
        <div class="ei">⭐</div>
        <p>No reviews yet<?= $filter_cat !== 'all' ? ' in this category' : '' ?>. Be the first to review!</p>
      </div>
      <?php else: foreach ($reviews as $i => $rv):
        $stars_full  = str_repeat('⭐', $rv['rating']);
        $stars_empty = str_repeat('☆', 5 - $rv['rating']);
        $initials    = strtoupper(substr($rv['name'], 0, 1));
        $colors      = ['linear-gradient(135deg,#FF5C1A,#ff9a5c)','linear-gradient(135deg,#409CFF,#7BC8FF)','linear-gradient(135deg,#3DDB7A,#5eeea0)','linear-gradient(135deg,#A78BFA,#c4b5fd)'];
        $color       = $colors[$i % 4];
      ?>
      <div class="review-card" style="animation-delay:<?= $i * 0.05 ?>s">
        <div class="rc-top">
          <div class="rc-user">
            <div class="rc-avatar" style="background:<?= $color ?>"><?= $initials ?></div>
            <div>
              <div class="rc-name"><?= htmlspecialchars($rv['name']) ?></div>
              <div class="rc-date"><?= date('d M Y', strtotime($rv['created_at'])) ?></div>
            </div>
          </div>
          <div class="rc-stars"><?= $stars_full . $stars_empty ?></div>
        </div>
        <div><span class="rc-cat"><?= htmlspecialchars($rv['category']) ?></span></div>
        <div class="rc-text"><?= nl2br(htmlspecialchars($rv['review_text'])) ?></div>
      </div>
      <?php endforeach; endif; ?>
    </div>
  </div>


  <div style="text-align:center;padding:10px 0 20px">
    <div style="font-size:13px;color:var(--text-muted);margin-bottom:12px">Had a great experience? Tell the world! 🌍</div>
    <a href="#write-review" class="btn btn-or" onclick="document.getElementById('rv-name').focus()">Write a Review ⭐</a>
  </div>

</div>
<footer class="footer">
  <a href="../index.php" class="footer-logo">🔧 Fix<b>igo</b></a>
  <p>© <?= date('Y') ?> Fixigo. All rights reserved.</p>
  <div class="footer-links">
    <a href="../footer/about.php">About</a>
    <a href="../footer/contact.php">Contact</a>
    <a href="../footer/help.php">Help</a>
    <a href="reviews.php">Reviews</a>
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


const hints = ['','😞 Poor','😕 Fair','😐 Okay','😊 Good','🤩 Excellent!'];
let selectedRating = 0;

document.querySelectorAll('.star-picker span').forEach(function(star) {
  star.addEventListener('mouseenter', function() {
    highlightStars(parseInt(this.dataset.val));
    document.getElementById('star-hint').textContent = hints[parseInt(this.dataset.val)];
  });
  star.addEventListener('mouseleave', function() {
    highlightStars(selectedRating);
    document.getElementById('star-hint').textContent = selectedRating ? hints[selectedRating] : 'Click to rate';
  });
  star.addEventListener('click', function() {
    selectedRating = parseInt(this.dataset.val);
    document.getElementById('rv-rating').value = selectedRating;
    highlightStars(selectedRating);
    document.getElementById('star-hint').textContent = hints[selectedRating];
  });
});

function highlightStars(val) {
  document.querySelectorAll('.star-picker span').forEach(function(s) {
    const isLit = parseInt(s.dataset.val) <= val;
    s.classList.toggle('lit', isLit);
  });
}


function submitReview() {
  const name   = document.getElementById('rv-name').value.trim();
  const email  = document.getElementById('rv-email').value.trim();
  const rating = document.getElementById('rv-rating').value;
  const cat    = document.getElementById('rv-category').value;
  const text   = document.getElementById('rv-text').value.trim();
  const btn    = document.getElementById('rv-btn');

  if (!name || !email || !text) { showAlert('err','Please fill in your name, email and review.'); return; }
  if (rating < 1) { showAlert('err','Please select a star rating.'); return; }
  if (!/\S+@\S+\.\S+/.test(email)) { showAlert('err','Please enter a valid email address.'); return; }

  btn.disabled = true;
  btn.textContent = 'Posting…';

  const data = new URLSearchParams();
  data.append('name', name);
  data.append('email', email);
  data.append('rating', rating);
  data.append('category', cat);
  data.append('review_text', text);

  fetch('submit_review.php', {
    method: 'POST',
    headers: {'Content-Type':'application/x-www-form-urlencoded'},
    body: data.toString()
  })
  .then(r => r.json())
  .then(d => {
    btn.disabled = false;
    btn.textContent = 'Post Review ⭐';
    if (d.success) {
      showAlert('ok','✅ Thank you! Your review has been posted.');
   
      document.getElementById('rv-name').value  = '';
      document.getElementById('rv-email').value = '';
      document.getElementById('rv-text').value  = '';
      document.getElementById('rv-rating').value = '0';
      selectedRating = 0;
      highlightStars(0);
      document.getElementById('star-hint').textContent = 'Click to rate';
    
      prependReview(d.review);
    } else {
      showAlert('err', d.error || 'Something went wrong.');
    }
  })
  .catch(() => {
    btn.disabled = false;
    btn.textContent = 'Post Review ⭐';
    showAlert('err','Network error. Please try again.');
  });
}

function showAlert(type, msg) {
  document.getElementById('rv-ok').style.display  = type === 'ok'  ? 'block' : 'none';
  document.getElementById('rv-err').style.display = type === 'err' ? 'block' : 'none';
  if (type === 'err') document.getElementById('rv-err').textContent = '⚠️ ' + msg;
  window.scrollTo({top: document.getElementById('write-review').offsetTop - 80, behavior:'smooth'});
}

function prependReview(rv) {
  const colors = ['linear-gradient(135deg,#FF5C1A,#ff9a5c)','linear-gradient(135deg,#409CFF,#7BC8FF)','linear-gradient(135deg,#3DDB7A,#5eeea0)','linear-gradient(135deg,#A78BFA,#c4b5fd)'];
  const color  = colors[Math.floor(Math.random() * colors.length)];
  const stars  = '⭐'.repeat(rv.rating) + '☆'.repeat(5 - rv.rating);
  const initial = rv.name.charAt(0).toUpperCase();

  const card = document.createElement('div');
  card.className = 'review-card';
  card.style.animationDelay = '0s';
  card.innerHTML = `
    <div class="rc-top">
      <div class="rc-user">
        <div class="rc-avatar" style="background:${color}">${initial}</div>
        <div>
          <div class="rc-name">${rv.name}</div>
          <div class="rc-date">${rv.created_at}</div>
        </div>
      </div>
      <div class="rc-stars">${stars}</div>
    </div>
    <div><span class="rc-cat">${rv.category}</span></div>
    <div class="rc-text">${rv.review_text.replace(/\n/g,'<br>')}</div>`;

  const grid = document.getElementById('reviews-grid');
 
  const empty = grid.querySelector('.empty');
  if (empty) empty.closest('[style*="grid-column"]') ? empty.parentElement.remove() : empty.remove();
  grid.prepend(card);
}
</script>
</body>
</html>