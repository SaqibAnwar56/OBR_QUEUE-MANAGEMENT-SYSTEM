<?php
session_start();
include 'db.php';
// getDishImg embedded directly
function getDishImg($dish, $idx = 0) {
    if (!empty($dish['image'])) { $local = '../assets/images/' . $dish['image']; if (file_exists($local)) return $local; }
    $name = strtolower($dish['name'] ?? ''); $cat = strtolower($dish['category'] ?? '');
    $byName = ['karahi'=>'https://images.unsplash.com/photo-1631452180519-c014fe946bc7?w=500&q=80','biryani'=>'https://images.unsplash.com/photo-1589302168068-964664d93dc0?w=500&q=80','tikka'=>'https://images.unsplash.com/photo-1567188040759-fb8a883dc6d8?w=500&q=80','kabab'=>'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=500&q=80','kebab'=>'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=500&q=80','seekh'=>'https://images.unsplash.com/photo-1544025162-d76694265947?w=500&q=80','chapli'=>'https://images.unsplash.com/photo-1529193591184-b1d58069ecdd?w=500&q=80','fish'=>'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=500&q=80','machi'=>'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=500&q=80','dal'=>'https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=500&q=80','naan'=>'https://images.unsplash.com/photo-1574894709920-11b28e7367e3?w=500&q=80','roti'=>'https://images.unsplash.com/photo-1574894709920-11b28e7367e3?w=500&q=80','paratha'=>'https://images.unsplash.com/photo-1574894709920-11b28e7367e3?w=500&q=80','lassi'=>'https://images.unsplash.com/photo-1605761366416-df0fbf7ab33a?w=500&q=80','gulab'=>'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=500&q=80','halwa'=>'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=500&q=80','kheer'=>'https://images.unsplash.com/photo-1559496417-e7f25cb247f3?w=500&q=80','platter'=>'https://images.unsplash.com/photo-1585937421612-70a008356fbe?w=500&q=80','chicken'=>'https://images.unsplash.com/photo-1527477396000-e27163b481c2?w=500&q=80','mutton'=>'https://images.unsplash.com/photo-1603360946369-dc9bb6258143?w=500&q=80','beef'=>'https://images.unsplash.com/photo-1544025162-d76694265947?w=500&q=80','rice'=>'https://images.unsplash.com/photo-1516714435131-44d6b64dc6a2?w=500&q=80','mango'=>'https://images.unsplash.com/photo-1605761366416-df0fbf7ab33a?w=500&q=80','juice'=>'https://images.unsplash.com/photo-1621506289937-a8e4df240d0b?w=500&q=80','soup'=>'https://images.unsplash.com/photo-1547592180-85f173990554?w=500&q=80','salad'=>'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=500&q=80','handi'=>'https://images.unsplash.com/photo-1631452180519-c014fe946bc7?w=500&q=80','nihari'=>'https://images.unsplash.com/photo-1603360946369-dc9bb6258143?w=500&q=80','haleem'=>'https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=500&q=80','pulao'=>'https://images.unsplash.com/photo-1516714435131-44d6b64dc6a2?w=500&q=80','korma'=>'https://images.unsplash.com/photo-1603360946369-dc9bb6258143?w=500&q=80','paneer'=>'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=500&q=80','fry'=>'https://images.unsplash.com/photo-1527477396000-e27163b481c2?w=500&q=80','boti'=>'https://images.unsplash.com/photo-1544025162-d76694265947?w=500&q=80'];
    foreach ($byName as $k => $v) { if (strpos($name,$k)!==false) return $v; }
    $byCat = ['biryani'=>'https://images.unsplash.com/photo-1589302168068-964664d93dc0?w=500&q=80','bbq'=>'https://images.unsplash.com/photo-1529193591184-b1d58069ecdd?w=500&q=80','grill'=>'https://images.unsplash.com/photo-1544025162-d76694265947?w=500&q=80','dessert'=>'https://images.unsplash.com/photo-1551024506-0bccd828d307?w=500&q=80','drink'=>'https://images.unsplash.com/photo-1544145945-f90425340c7e?w=500&q=80','beverage'=>'https://images.unsplash.com/photo-1544145945-f90425340c7e?w=500&q=80','bread'=>'https://images.unsplash.com/photo-1574894709920-11b28e7367e3?w=500&q=80','vegetarian'=>'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=500&q=80','seafood'=>'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=500&q=80','main'=>'https://images.unsplash.com/photo-1631452180519-c014fe946bc7?w=500&q=80','platter'=>'https://images.unsplash.com/photo-1585937421612-70a008356fbe?w=500&q=80'];
    foreach ($byCat as $k => $v) { if (strpos($cat,$k)!==false) return $v; }
    $pool = ['https://images.unsplash.com/photo-1631452180519-c014fe946bc7?w=500&q=80','https://images.unsplash.com/photo-1589302168068-964664d93dc0?w=500&q=80','https://images.unsplash.com/photo-1585937421612-70a008356fbe?w=500&q=80','https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=500&q=80','https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=500&q=80','https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=500&q=80','https://images.unsplash.com/photo-1574894709920-11b28e7367e3?w=500&q=80','https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=500&q=80'];
    return $pool[$idx % count($pool)];
}

// ── Live stats from queue table (admin's same data) ──────────
$waiting = (int)$conn->query("SELECT COUNT(*) as c FROM queue WHERE status='waiting'")->fetch_assoc()['c'];
$serving = $conn->query("SELECT * FROM queue WHERE status='serving' ORDER BY id DESC LIMIT 1")->fetch_assoc();
$served_today = (int)$conn->query("SELECT COUNT(*) as c FROM queue WHERE (status='served' OR status='Served') AND DATE(created_at)=CURDATE()")->fetch_assoc()['c'];

// ── Real dishes from admin dishes table ───────────────────────
$dishes = [];
$dr = $conn->query("SELECT * FROM dishes ORDER BY id ASC LIMIT 6");
if ($dr) while ($r=$dr->fetch_assoc()) $dishes[] = $r;

$total_dishes = (int)$conn->query("SELECT COUNT(*) as c FROM dishes")->fetch_assoc()['c'];

// ── Marquee from real dish names ─────────────────────────────
$marq = [];
$mr = $conn->query("SELECT name FROM dishes ORDER BY id");
if ($mr) while ($r=$mr->fetch_assoc()) $marq[] = $r['name'];
if (empty($marq)) $marq = ['Chicken Karahi','Mutton Biryani','Seekh Kabab','Grilled Fish','Dal Makhani','Mango Lassi','Gulab Jamun','Special Platter'];
$marq2 = array_merge($marq, $marq); // double for seamless scroll
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>OBR Restaurant Sanghar — Premium Dining &amp; Smart Queue</title>
<meta name="description" content="OBR Restaurant Sanghar — fine dining with smart digital queue. Join the queue, order online, track live.">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/customer.css">
</head>
<body>

<!-- ═══ NAVBAR ═══ -->
<nav class="nav" id="mainNav">
  <a href="index.php" class="nav-logo"><span>OBR</span> Restaurant</a>
  <ul class="nav-menu" id="navMenu">
    <li><a href="index.php" class="active">Home</a></li>
    <li><a href="menu.php">Menu</a></li>
    <li><a href="queue_status.php">Live Queue</a></li>
    <?php if(isset($_SESSION['cust_id'])): ?>
      <li><a href="dashboard.php">Dashboard</a></li>
      <li><a href="orders.php">My Orders</a></li>
      <li><a href="feedback.php">Feedback</a></li>
      <li><a href="logout.php" class="nav-out">Logout</a></li>
    <?php else: ?>
      <li><a href="login.php">Login</a></li>
      <li><a href="register.php" class="nav-cta">Register</a></li>
    <?php endif; ?>
  </ul>
  <button class="hamburger" id="hamburger"><span></span><span></span><span></span></button>
</nav>

<!-- ═══ HERO — using real restaurant images ═══ -->
<section class="hero">
  <div class="hero-slides">
    <div class="hero-slide active"   style="background-image:url('../assets/images/outside.webp')"></div>
    <div class="hero-slide"          style="background-image:url('../assets/images/view.webp')"></div>
    <div class="hero-slide"          style="background-image:url('../assets/images/night.jpg')"></div>
  </div>
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <p class="eyebrow">Est. 2026 &middot; Sanghar, Pakistan</p>
    <h1>Fine Dining,<br><em>Reimagined</em></h1>
    <p class="hero-desc">Reserve your spot digitally &amp; order food online.<br>Premium dining experience in Sanghar.</p>
    <div class="hero-btns">
      <a href="<?= isset($_SESSION['cust_id'])?'get_token.php':'register.php' ?>" class="btn-gold lg">🎫 Get Queue Token</a>
      <a href="menu.php" class="btn-outline">Explore Menu</a>
    </div>

  </div>
  <div class="scroll-hint" onclick="document.getElementById('about').scrollIntoView({behavior:'smooth'})">
    <div class="scroll-line"></div>
    <span>Scroll</span>
  </div>
</section>

<!-- ═══ MARQUEE (real dish names) ═══ -->
<div class="marquee-bar">
  <div class="marquee-track">
    <?php foreach($marq2 as $item): ?>
    <span class="marquee-item"><?= htmlspecialchars($item) ?></span>
    <?php endforeach; ?>
  </div>
</div>

<!-- ═══ ABOUT — real restaurant images ═══ -->
<section class="about-sec section" id="about">
  <div class="container about-grid">
    <div class="about-imgs reveal">
      <img src="../assets/images/outside.webp" class="img-main" alt="OBR Restaurant Outside View"
           onerror="this.src='https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=800&q=80'">
      <img src="../assets/images/dish.webp" class="img-float" alt="OBR Signature Dish"
           onerror="this.src='https://images.unsplash.com/photo-1567337710282-00832b415979?w=500&q=80'">
      <div class="about-badge"><span>6+</span>Years of Excellence</div>
    </div>
    <div class="about-text reveal">
      <p class="sec-tag">Our Story</p>
      <h2>A Culinary Journey<br>Unlike <em>Any Other</em></h2>
      <p>At OBR Restaurant, we blend tradition with innovation. Our chefs craft every dish with the finest local ingredients, creating moments that linger long after the last bite.</p>
      <p>Our smart queue management system means your experience begins seamlessly — no standing in line. Join digitally, track live, arrive when your table is ready.</p>
      <div class="about-nums">
        <div class="anum"><span class="n">5★</span><span>Google Rating</span></div>
        <div class="anum"><span class="n"><?= max($total_dishes,15) ?>+</span><span>Dishes</span></div>
        <div class="anum"><span class="n">10K+</span><span>Happy Guests</span></div>
      </div>
    </div>
  </div>
</section>

<!-- ═══ FEATURED DISHES (real from admin dishes table) ═══ -->
<section class="section" style="background:var(--bg)">
  <div class="container">
    <p class="sec-tag center reveal">From Our Kitchen</p>
    <h2 class="center reveal">Signature <em>Dishes</em></h2>
    <p class="center reveal" style="color:var(--text-s);font-weight:300;margin-bottom:0">Crafted fresh daily by our expert chefs using finest local ingredients</p>
    <div class="dishes-grid">
    <?php if(!empty($dishes)):
      foreach($dishes as $i=>$d): $img=getDishImg($d,$i); ?>
      <div class="dish-card reveal">
        <div class="dish-img-wrap">
          <img src="<?= $img ?>" alt="<?= htmlspecialchars($d['name']) ?>" loading="lazy">
          <span class="dish-cat"><?= htmlspecialchars($d['category']??'Special') ?></span>
        </div>
        <div class="dish-body">
          <h3><?= htmlspecialchars($d['name']) ?></h3>
          <p><?= htmlspecialchars(mb_substr($d['description']??'Crafted with finest fresh ingredients',0,70)) ?>…</p>
          <div class="dish-foot">
            <span class="dish-price">Rs. <?= number_format($d['price'],0) ?></span>
            <span class="dish-avail <?= ($d['available']??1)?'yes':'no' ?>"><?= ($d['available']??1)?'Available':'Sold Out' ?></span>
          </div>
        </div>
      </div>
      <?php endforeach;
    else: // fallback demo dishes
      $demo=[
        ['Chicken Karahi','Tender chicken in rich spiced tomato gravy',950,'Main Course','https://images.unsplash.com/photo-1631452180519-c014fe946bc7?w=500&q=80'],
        ['Mutton Biryani','Fragrant basmati with slow-cooked mutton',850,'Biryani','https://images.unsplash.com/photo-1589302168068-964664d93dc0?w=500&q=80'],
        ['Seekh Kabab','Charcoal-grilled minced beef kababs',650,'BBQ & Grill','https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=500&q=80'],
        ['Grilled Fish','Fresh fish marinated and grilled',800,'Seafood','https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=500&q=80'],
        ['Dal Makhani','Slow-cooked lentils in creamy sauce',450,'Vegetarian','https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=500&q=80'],
        ['Gulab Jamun','Rose-flavored milk dumplings in syrup',250,'Dessert','https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=500&q=80'],
      ];
      foreach($demo as $d): ?>
      <div class="dish-card reveal">
        <div class="dish-img-wrap"><img src="<?= $d[4] ?>" alt="<?= $d[0] ?>" loading="lazy"><span class="dish-cat"><?= $d[3] ?></span></div>
        <div class="dish-body"><h3><?= $d[0] ?></h3><p><?= $d[1] ?></p><div class="dish-foot"><span class="dish-price">Rs. <?= number_format($d[2],0) ?></span><span class="dish-avail yes">Available</span></div></div>
      </div>
      <?php endforeach; endif; ?>
    </div>
    <div class="center mt3"><a href="menu.php" class="btn-gold reveal">View Full Menu &amp; Order Online →</a></div>
  </div>
</section>

<!-- ═══ HOW IT WORKS ═══ -->
<section class="hiw section">
  <div class="container">
    <p class="sec-tag center reveal">Smart Queuing System</p>
    <h2 class="center reveal">How It <em>Works</em></h2>
    <div class="steps-row reveal">
      <div class="step-box"><div class="step-n">01</div><div class="step-ico">👤</div><h3>Register</h3><p>Create your free account with name and phone in 30 seconds.</p></div>
      <div class="step-box"><div class="step-n">02</div><div class="step-ico">🎫</div><h3>Get Token</h3><p>Join the digital queue and receive your token number instantly.</p></div>
      <div class="step-box"><div class="step-n">03</div><div class="step-ico">🍽️</div><h3>Order Ahead</h3><p>Browse our menu and pre-order so food is ready when you arrive.</p></div>
      <div class="step-box"><div class="step-n">04</div><div class="step-ico">📡</div><h3>Track Live</h3><p>Watch your queue position update in real-time on your phone.</p></div>
    </div>
  </div>
</section>

<!-- ═══ GALLERY — real restaurant images ═══ -->
<section class="gallery-sec">
  <div class="gallery-grid">
    <div class="gal-item tall reveal">
      <img src="../assets/images/outside.webp" alt="OBR Restaurant Outside" loading="lazy"
           onerror="this.src='https://images.unsplash.com/photo-1466978913421-dad2ebd01d17?w=900&q=80'">
      <div class="gal-lbl">Restaurant View</div>
    </div>
    <div class="gal-item reveal">
      <img src="../assets/images/dish.webp" alt="Signature Dish" loading="lazy"
           onerror="this.src='https://images.unsplash.com/photo-1631452180519-c014fe946bc7?w=700&q=80'">
      <div class="gal-lbl">Signature Dish</div>
    </div>
    <div class="gal-item reveal">
      <img src="../assets/images/dish2.webp" alt="Chef Special" loading="lazy"
           onerror="this.src='https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=700&q=80'">
      <div class="gal-lbl">Chef Special</div>
    </div>
    <div class="gal-item reveal">
      <img src="../assets/images/menu.webp" alt="Our Menu" loading="lazy"
           onerror="this.src='https://images.unsplash.com/photo-1585937421612-70a008356fbe?w=700&q=80'">
      <div class="gal-lbl">Our Menu</div>
    </div>
    <div class="gal-item reveal">
      <img src="../assets/images/night.jpg" alt="Night View" loading="lazy"
           onerror="this.src='https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=700&q=80'">
      <div class="gal-lbl">Night Ambience</div>
    </div>
  </div>
</section>

<!-- ═══ TESTIMONIALS ═══ -->
<section class="testimonials">
  <div class="container">
    <p class="sec-tag center reveal">What Guests Say</p>
    <h2 class="center reveal">Loved by <em>Thousands</em></h2>
    <div class="testi-grid">
      <div class="testi-card reveal">
        <div class="testi-stars">★★★★★</div>
        <p class="testi-text">"The queue system is genius! I joined digitally, ordered from the menu, and my food was ready the moment I sat down. OBR has truly modernized dining."</p>
        <p class="testi-author">— Usman A., Sanghar</p>
      </div>
      <div class="testi-card reveal">
        <div class="testi-stars">★★★★★</div>
        <p class="testi-text">"Karahi was absolutely divine — rich, spicy, perfectly cooked. The staff was incredibly warm. Will be returning every weekend without fail!"</p>
        <p class="testi-author">— Ayesha M., Nawabshah</p>
      </div>
      <div class="testi-card reveal">
        <div class="testi-stars">★★★★★</div>
        <p class="testi-text">"No more standing in the heat waiting. The live queue tracking is a game-changer. Best restaurant experience in Lahore by far."</p>
        <p class="testi-author">— Bilal K., Sanghar</p>
      </div>
    </div>
  </div>
</section>

<!-- ═══ CTA — real restaurant image ═══ -->
<section class="cta-sec">
  <div class="cta-bg" style="background-image:url('../assets/images/view.webp')"
       onerror="this.style.backgroundImage='url(https://images.unsplash.com/photo-1424847651672-bf20a4b0982b?w=1600&q=80)'"></div>
  <div class="cta-overlay"></div>
  <div class="cta-content reveal">
    <p class="eyebrow">Reserve Your Spot</p>
    <h2>Ready for an Unforgettable<br>Dining Experience?</h2>
    <p>Join the digital queue now — it's free and takes 30 seconds.</p>
    <a href="<?= isset($_SESSION['cust_id'])?'get_token.php':'register.php' ?>" class="btn-gold lg">
      🎫 <?= isset($_SESSION['cust_id'])?'Get My Token Now':'Reserve My Spot — Free' ?>
    </a>
  </div>
</section>

<!-- ═══ FOOTER ═══ -->
<footer class="footer">
  <div class="container">
    <div class="footer-grid">
      <div class="footer-col">
        <span class="f-brand">OBR Restaurant</span>
        <p>Smart dining. Exceptional taste.<br>Sanghar's premier queue-managed restaurant.</p>
        <div class="footer-socials"><a href="#">FB</a><a href="#">IG</a><a href="#">TW</a></div>
      </div>
      <div class="footer-col">
        <h4>Quick Links</h4>
        <a href="index.php">Home</a><a href="menu.php">Menu</a>
        <a href="queue_status.php">Live Queue</a><a href="register.php">Register</a><a href="login.php">Login</a>
      </div>
      <div class="footer-col">
        <h4>Account</h4>
        <a href="register.php">Create Account</a><a href="login.php">Login</a>
        <a href="get_token.php">Get Token</a><a href="dashboard.php">Dashboard</a><a href="feedback.php">Feedback</a>
      </div>
      <div class="footer-col">
        <h4>Contact Us</h4>
        <p>📍 3W7J+JFW, Nawabshah Rd<br>Sanghar, Pakistan</p>
        <p>📞 0325 7367364</p>
        <p>✉️ info@obrrestaurant.com</p>
        <p>🕐 Open 24 Hours · 7 Days a Week</p>
      </div>
    </div>
  </div>
  <div class="footer-bottom"><p>&copy; <?= date('Y') ?> OBR Restaurant. All rights reserved. | Powered by OBR QMS</p></div>
</footer>

<script src="../assets/js/customer.js"></script>
</body>
</html>