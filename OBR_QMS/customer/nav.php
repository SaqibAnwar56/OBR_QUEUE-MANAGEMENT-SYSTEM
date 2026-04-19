<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<nav class="nav nav-solid" id="mainNav">
  <a href="index.php" class="nav-logo"><span>OBR</span> Restaurant</a>
  <ul class="nav-menu" id="navMenu">
    <li><a href="index.php">Home</a></li>
    <li><a href="menu.php">Menu</a></li>
    <li><a href="queue_status.php">Live Queue</a></li>
    <?php if (isset($_SESSION['cust_id'])): ?>
      <li><a href="dashboard.php">Dashboard</a></li>
      <li><a href="orders.php">My Orders</a></li>
      <li><a href="feedback.php">Feedback</a></li>
      <li><a href="logout.php" class="nav-out">Logout</a></li>
    <?php else: ?>
      <li><a href="login.php">Login</a></li>
      <li><a href="register.php" class="nav-cta">Register</a></li>
    <?php endif; ?>
  </ul>
  <button class="hamburger" id="hamburger" aria-label="Toggle menu">
    <span></span><span></span><span></span>
  </button>
</nav>
