<?php
// includes/nav.php — shared nav for all customer pages
$current = $current ?? '';
?>
<nav class="nav" id="nav">
  <div class="nav-inner">
    <!-- Logo -->
    <a href="index.php" class="nav-logo">
      <img src="assets/images/outside.webp" alt="O-Bailia" class="nav-logo-img"
           onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
      <div class="nav-logo-img-fallback" style="display:none;">O</div>
      <div>
        <div class="brand-name"><span>O-</span>Bailia</div>
        <div class="brand-sub">Restaurant · Sanghar</div>
      </div>
    </a>

    <!-- Desktop Links -->
    <div class="nav-links">
      <a href="index.php"        class="nl <?= $current==='home'   ?'active':'' ?>">Home</a>
      <a href="menu.php"         class="nl <?= $current==='menu'   ?'active':'' ?>">Menu</a>
      <a href="join_queue.php"   class="nl <?= $current==='token'  ?'active':'' ?>">Take Token</a>
      <a href="queue_status.php" class="nl <?= $current==='status' ?'active':'' ?>">Queue Status</a>
      <a href="contact.php"      class="nl <?= $current==='contact'?'active':'' ?>">Contact</a>
      <?php if (isset($_SESSION['customer_id'])): ?>
        <a href="dashboard.php" class="btn-nav btn-nav-outline ms-1">
          <i class="fas fa-user" style="margin-right:6px;"></i>
          <?= htmlspecialchars(explode(' ', $_SESSION['customer_name'])[0]) ?>
        </a>
        <a href="logout.php" class="btn-nav btn-nav-solid" style="margin-left:6px;">Logout</a>
      <?php else: ?>
        <a href="login.php"    class="btn-nav btn-nav-outline" style="margin-left:8px;">Login</a>
        <a href="register.php" class="btn-nav btn-nav-solid"  style="margin-left:6px;">Register</a>
      <?php endif; ?>
    </div>

    <!-- Hamburger -->
    <button class="hamburger" aria-label="Menu">
      <span></span><span></span><span></span>
    </button>
  </div>
</nav>

<!-- Mobile Menu -->
<div class="mobile-menu">
  <button class="close-btn">&#x2715;</button>
  <a href="index.php"        class="nl">Home</a>
  <a href="menu.php"         class="nl">Menu</a>
  <a href="join_queue.php"   class="nl">Take Token</a>
  <a href="queue_status.php" class="nl">Queue Status</a>
  <a href="contact.php"      class="nl">Contact</a>
  <?php if (isset($_SESSION['customer_id'])): ?>
    <a href="dashboard.php" class="btn btn-outline btn-sm">My Account</a>
    <a href="logout.php"    class="btn btn-gold btn-sm">Logout</a>
  <?php else: ?>
    <a href="login.php"    class="btn btn-outline btn-sm">Login</a>
    <a href="register.php" class="btn btn-gold btn-sm">Register</a>
  <?php endif; ?>
</div>
