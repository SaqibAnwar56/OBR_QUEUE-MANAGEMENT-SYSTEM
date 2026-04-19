<?php // includes/footer.php ?>
<footer>
  <div class="container">
    <div class="footer-grid">
      <div>
        <div class="ft-brand-name"><span>O-</span>Bailia</div>
        <div class="ft-brand-sub">Restaurant · Sanghar</div>
        <p class="ft-desc">Sanghar ka premium restaurant — authentic Pakistani cuisine, fresh daily, open 24/7. Karahi, Biryani, BBQ aur bahut kuch.</p>
      </div>
      <div>
        <p class="ft-h">Pages</p>
        <ul class="ft-links">
          <li><a href="index.php">Home</a></li>
          <li><a href="menu.php">Menu</a></li>
          <li><a href="join_queue.php">Take Token</a></li>
          <li><a href="queue_status.php">Queue Status</a></li>
          <li><a href="contact.php">Contact</a></li>
        </ul>
      </div>
      <div>
        <p class="ft-h">Account</p>
        <ul class="ft-links">
          <?php if (isset($_SESSION['customer_id'])): ?>
          <li><a href="dashboard.php">My Dashboard</a></li>
          <li><a href="logout.php">Logout</a></li>
          <?php else: ?>
          <li><a href="register.php">Register</a></li>
          <li><a href="login.php">Login</a></li>
          <?php endif; ?>
          <li><a href="feedback.php">Feedback</a></li>
        </ul>
      </div>
      <div>
        <p class="ft-h">Contact</p>
        <div style="font-size:.8rem;color:var(--muted);line-height:2.2;">
          <div><i class="fas fa-map-marker-alt" style="color:var(--gold);margin-right:8px;"></i>Sanghar, Sindh</div>
          <div><i class="fas fa-clock" style="color:var(--gold);margin-right:8px;"></i>Open 24/7</div>
          <div><i class="fas fa-utensils" style="color:var(--gold);margin-right:8px;"></i>Pakistani Cuisine</div>
        </div>
      </div>
    </div>
    <div class="ft-bottom">
      &copy; 2026 <span>O-Bailia Restaurant</span> &nbsp;·&nbsp; OBR-QMS Queue System &nbsp;·&nbsp; Sanghar
    </div>
  </div>
</footer>
