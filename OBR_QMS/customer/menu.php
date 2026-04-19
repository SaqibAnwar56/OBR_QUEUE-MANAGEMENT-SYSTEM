<?php
session_start();
include 'db.php';
// getDishImg — embedded directly (no helpers.php needed)
function getDishImg($dish, $idx = 0) {
    if (!empty($dish['image'])) {
        $local = '../assets/images/' . $dish['image'];
        if (file_exists($local)) return $local;
    }
    $name = strtolower($dish['name'] ?? '');
    $cat  = strtolower($dish['category'] ?? '');
    $byName = [
        'karahi'=>'https://images.unsplash.com/photo-1631452180519-c014fe946bc7?w=500&q=80',
        'biryani'=>'https://images.unsplash.com/photo-1589302168068-964664d93dc0?w=500&q=80',
        'tikka'=>'https://images.unsplash.com/photo-1567188040759-fb8a883dc6d8?w=500&q=80',
        'kabab'=>'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=500&q=80',
        'kebab'=>'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=500&q=80',
        'seekh'=>'https://images.unsplash.com/photo-1544025162-d76694265947?w=500&q=80',
        'chapli'=>'https://images.unsplash.com/photo-1529193591184-b1d58069ecdd?w=500&q=80',
        'fish'=>'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=500&q=80',
        'machi'=>'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=500&q=80',
        'dal'=>'https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=500&q=80',
        'daal'=>'https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=500&q=80',
        'naan'=>'https://images.unsplash.com/photo-1574894709920-11b28e7367e3?w=500&q=80',
        'roti'=>'https://images.unsplash.com/photo-1574894709920-11b28e7367e3?w=500&q=80',
        'paratha'=>'https://images.unsplash.com/photo-1574894709920-11b28e7367e3?w=500&q=80',
        'lassi'=>'https://images.unsplash.com/photo-1605761366416-df0fbf7ab33a?w=500&q=80',
        'gulab'=>'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=500&q=80',
        'halwa'=>'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=500&q=80',
        'kheer'=>'https://images.unsplash.com/photo-1559496417-e7f25cb247f3?w=500&q=80',
        'platter'=>'https://images.unsplash.com/photo-1585937421612-70a008356fbe?w=500&q=80',
        'chicken'=>'https://images.unsplash.com/photo-1527477396000-e27163b481c2?w=500&q=80',
        'mutton'=>'https://images.unsplash.com/photo-1603360946369-dc9bb6258143?w=500&q=80',
        'beef'=>'https://images.unsplash.com/photo-1544025162-d76694265947?w=500&q=80',
        'rice'=>'https://images.unsplash.com/photo-1516714435131-44d6b64dc6a2?w=500&q=80',
        'chawal'=>'https://images.unsplash.com/photo-1516714435131-44d6b64dc6a2?w=500&q=80',
        'mango'=>'https://images.unsplash.com/photo-1605761366416-df0fbf7ab33a?w=500&q=80',
        'juice'=>'https://images.unsplash.com/photo-1621506289937-a8e4df240d0b?w=500&q=80',
        'soup'=>'https://images.unsplash.com/photo-1547592180-85f173990554?w=500&q=80',
        'salad'=>'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=500&q=80',
    ];
    foreach ($byName as $k => $v) { if (strpos($name,$k)!==false) return $v; }
    $byCat = [
        'biryani'=>'https://images.unsplash.com/photo-1589302168068-964664d93dc0?w=500&q=80',
        'bbq'=>'https://images.unsplash.com/photo-1529193591184-b1d58069ecdd?w=500&q=80',
        'grill'=>'https://images.unsplash.com/photo-1544025162-d76694265947?w=500&q=80',
        'dessert'=>'https://images.unsplash.com/photo-1551024506-0bccd828d307?w=500&q=80',
        'drink'=>'https://images.unsplash.com/photo-1544145945-f90425340c7e?w=500&q=80',
        'beverage'=>'https://images.unsplash.com/photo-1544145945-f90425340c7e?w=500&q=80',
        'bread'=>'https://images.unsplash.com/photo-1574894709920-11b28e7367e3?w=500&q=80',
        'vegetarian'=>'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=500&q=80',
        'seafood'=>'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=500&q=80',
        'main'=>'https://images.unsplash.com/photo-1631452180519-c014fe946bc7?w=500&q=80',
        'platter'=>'https://images.unsplash.com/photo-1585937421612-70a008356fbe?w=500&q=80',
    ];
    foreach ($byCat as $k => $v) { if (strpos($cat,$k)!==false) return $v; }
    $pool = [
        'https://images.unsplash.com/photo-1631452180519-c014fe946bc7?w=500&q=80',
        'https://images.unsplash.com/photo-1589302168068-964664d93dc0?w=500&q=80',
        'https://images.unsplash.com/photo-1585937421612-70a008356fbe?w=500&q=80',
        'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=500&q=80',
        'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=500&q=80',
        'https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=500&q=80',
        'https://images.unsplash.com/photo-1574894709920-11b28e7367e3?w=500&q=80',
        'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=500&q=80',
    ];
    return $pool[$idx % count($pool)];
}



// TOKEN CHECK — has token = can order
$has_token       = false;
$my_token_number = null;
if (isset($_SESSION['cust_id'])) {
    $p2 = $conn->real_escape_string($_SESSION['cust_phone']);
    $n2 = $conn->real_escape_string($_SESSION['cust_name']);
    $tq = $conn->query("SELECT token_number FROM queue WHERE (phone='$p2' OR name='$n2') AND status IN ('waiting','serving') ORDER BY id DESC LIMIT 1");
    $tr = $tq ? $tq->fetch_assoc() : null;
    if ($tr) {
        $has_token       = true;
        $my_token_number = $tr['token_number'];
    }
}

// Dishes grouped by category
$result = $conn->query("SELECT * FROM dishes ORDER BY category, name ASC");
$by_cat = []; $all_cats = [];
if ($result) while ($r = $result->fetch_assoc()) {
    $cat = $r['category'] ?? 'Special';
    $by_cat[$cat][] = $r;
    if (!in_array($cat, $all_cats)) $all_cats[] = $cat;
}
$total_items = array_sum(array_map('count', $by_cat));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Menu — OBR Restaurant</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/customer.css">
<style>


</style>
</head>
<body class="inner-page">
<?php include 'nav.php'; ?>

<!-- Page Hero -->
<div class="page-hero">
  <div class="ph-bg" style="background-image:url('../assets/images/menu.webp')" onerror="this.style.backgroundImage='url(https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=1400&q=80)'"></div>
  <div class="ph-over"></div>
  <div class="ph-txt">
    <p class="eyebrow">Explore</p>
    <h1>Our <em>Menu</em></h1>
    <p><?= $total_items ?: '20+' ?> items &middot; Crafted fresh daily</p>
  </div>
</div>



<!-- Filter Bar -->
<?php if (!empty($all_cats)): ?>
<div class="filter-bar">
  <button class="filt active" data-f="all">All Items</button>
  <?php foreach ($all_cats as $c): ?>
  <button class="filt" data-f="<?= htmlspecialchars($c) ?>"><?= htmlspecialchars($c) ?></button>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Menu Sections -->
<div class="menu-wrap">
<?php if (!empty($by_cat)):
  foreach ($by_cat as $cat => $items): ?>
  <div class="menu-sec" data-sec="<?= htmlspecialchars($cat) ?>">
    <div class="msec-hdr">
      <h2><?= htmlspecialchars($cat) ?></h2>
      <div class="msec-line"></div>
      <span class="msec-count"><?= count($items) ?> item<?= count($items)>1?'s':'' ?></span>
    </div>
    <div class="menu-grid">
      <?php foreach ($items as $i => $d):
        $img   = getDishImg($d, $i);
        $avail = ($d['available'] ?? 1) == 1;
      ?>
      <div class="menu-card reveal <?= !$avail ? 'soldout' : '' ?>">
        <div class="mc-img">
          <img src="<?= $img ?>" alt="<?= htmlspecialchars($d['name']) ?>" loading="lazy">
          <?php if (!$avail): ?><div class="mc-sold-badge">Sold Out</div><?php endif; ?>
        </div>
        <div class="mc-body">
          <h3><?= htmlspecialchars($d['name']) ?></h3>
          <p><?= htmlspecialchars($d['description'] ?? 'Crafted with finest fresh ingredients') ?></p>
          <div class="mc-foot">
            <span class="mc-price">Rs. <?= number_format($d['price'], 0) ?></span>
            <?php if ($avail): ?>
            <button class="mc-add"
              onclick="addToCart(<?= $d['id'] ?>,'<?= addslashes($d['name']) ?>',<?= $d['price'] ?>,'<?= $img ?>')"
              title="Add to cart">+</button>
            <?php else: ?>
            <button class="mc-add" disabled>✕</button>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endforeach;
else:
  $demo=[['Main Course',['Chicken Karahi',950,'https://images.unsplash.com/photo-1631452180519-c014fe946bc7?w=400&q=70'],['Mutton Biryani',850,'https://images.unsplash.com/photo-1589302168068-964664d93dc0?w=400&q=70']],['BBQ & Grill',['Seekh Kabab',650,'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=400&q=70']],['Desserts',['Gulab Jamun',250,'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=400&q=70']]];
  foreach($demo as $grp): $cat=$grp[0]; ?>
  <div class="menu-sec" data-sec="<?= $cat ?>">
    <div class="msec-hdr"><h2><?= $cat ?></h2><div class="msec-line"></div></div>
    <div class="menu-grid">
    <?php for($i=1;$i<count($grp);$i++): $d=$grp[$i]; ?>
      <div class="menu-card reveal">
        <div class="mc-img"><img src="<?= $d[2] ?>" alt="<?= $d[0] ?>" loading="lazy"></div>
        <div class="mc-body"><h3><?= $d[0] ?></h3>
          <div class="mc-foot"><span class="mc-price">Rs. <?= number_format($d[1],0) ?></span>
          <button class="mc-add" onclick="addToCart('<?= $i ?>','<?= $d[0] ?>',<?= $d[1] ?>,'<?= $d[2] ?>')">+</button></div>
        </div>
      </div>
    <?php endfor; ?>
    </div>
  </div>
  <?php endforeach;
endif; ?>
  <div class="center" style="padding:4rem 0 2rem;border-top:1px solid var(--border-s);margin-top:1rem">
    <a href="<?= isset($_SESSION['cust_id'])?'get_token.php':'register.php' ?>" class="btn-gold">🎫 Get Queue Token</a>
  </div>
</div>

<!-- Cart FAB -->
<button class="cart-fab hidden" id="cartFab" onclick="openCart()">
  🛒 <span>View Cart</span> <span class="cart-badge" id="cartBadge">0</span>
</button>

<!-- Cart Modal -->
<div class="cart-modal" id="cartModal">
  <div class="cart-backdrop" onclick="closeCart()"></div>
  <div class="cart-panel">
    <div class="cart-hdr">
      <h3>🛒 Your Cart</h3>
      <button class="cart-close" onclick="closeCart()">✕</button>
    </div>
    <div class="cart-items" id="cartItems">
      <div class="cart-empty"><div class="cart-empty-ico">🛒</div><p>Your cart is empty</p><button onclick="closeCart()" class="btn-outline sm">Browse Menu</button></div>
    </div>
    <div class="cart-footer" id="cartFooter" style="display:none">
      <div class="cart-total-row">
        <span>Total Amount</span>
        <span class="cart-total-amt" id="cartTotalAmt">Rs. 0</span>
      </div>
      <div class="fg" style="margin-bottom:1rem">
        <label>Order Notes <span class="opt">(optional)</span></label>
        <textarea id="cartNotes" placeholder="Special instructions..." rows="2"></textarea>
      </div>

      <?php if (!isset($_SESSION['cust_id'])): ?>
        <a href="login.php" class="btn-gold full" style="justify-content:center;display:flex">Login to Place Order</a>

      <?php elseif (!$has_token): ?>
        <div style="text-align:center;padding:1rem;background:rgba(239,68,68,.06);border:1px solid rgba(239,68,68,.2);border-radius:var(--rs)">
          <div style="font-size:1.5rem;margin-bottom:.4rem">🎫</div>
          <p style="font-size:.85rem;font-weight:600;margin-bottom:.25rem">Token Required</p>
          <p style="font-size:.78rem;color:var(--text-m);margin-bottom:.8rem">Get a queue token first before placing a food order.</p>
          <a href="get_token.php" class="btn-gold full" style="justify-content:center;display:flex">Get Queue Token</a>
        </div>

      <?php else: ?>
        <div style="display:flex;align-items:center;gap:.6rem;padding:.6rem .9rem;background:rgba(200,169,110,.06);border:1px solid rgba(200,169,110,.2);border-radius:var(--rs);margin-bottom:.8rem;font-size:.82rem">
          <span>🎫</span>
          <span>Token #<?= str_pad($my_token_number,3,'0',STR_PAD_LEFT) ?></span>
          <span style="opacity:.4">·</span>
          <span>💵</span>
          <strong>Pay by Cash</strong>
        </div>
        <button class="btn-gold full" id="placeOrderBtn" onclick="placeOrder()">✅ Place Order</button>
      <?php endif; ?>

    </div>
  </div>
</div>

<footer class="footer"><div class="footer-bottom"><p>&copy; <?= date('Y') ?> OBR Restaurant Sanghar.</p></div></footer>
<script src="../assets/js/customer.js"></script>
<script>
document.querySelectorAll('.filt').forEach(function(btn){
  btn.addEventListener('click',function(){
    document.querySelectorAll('.filt').forEach(b=>b.classList.remove('active'));
    this.classList.add('active');
    var f=this.dataset.f;
    document.querySelectorAll('.menu-sec').forEach(function(s){
      s.style.display=(f==='all'||s.dataset.sec===f)?'':'none';
    });
  });
});
</script>


</body>
</html>