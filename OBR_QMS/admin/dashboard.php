<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}
include 'db.php';

// Logic remains the same
$notif_count     = (int)$conn->query("SELECT COUNT(*) as c FROM notifications WHERE is_read=0")->fetch_assoc()['c'];
$total_dishes    = (int)$conn->query("SELECT COUNT(*) as c FROM dishes")->fetch_assoc()['c'];
$total_queue     = (int)$conn->query("SELECT COUNT(*) as c FROM queue WHERE status='waiting'")->fetch_assoc()['c'];
$serving_now     = (int)$conn->query("SELECT COUNT(*) as c FROM queue WHERE status='serving'")->fetch_assoc()['c'];
$served_today    = (int)$conn->query("SELECT COUNT(*) as c FROM queue WHERE (status='served' OR status='Served') AND DATE(created_at)=CURDATE()")->fetch_assoc()['c'];
$today_orders    = (int)$conn->query("SELECT COUNT(*) as c FROM orders WHERE DATE(created_at)=CURDATE()")->fetch_assoc()['c'];
$today_sales     = (float)$conn->query("SELECT COALESCE(SUM(total_amount),0) as s FROM orders WHERE DATE(created_at)=CURDATE()")->fetch_assoc()['s'];
$total_customers = (int)$conn->query("SELECT COUNT(*) as c FROM customers")->fetch_assoc()['c'];
$recent_notifs   = $conn->query("SELECT * FROM notifications ORDER BY created_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Admin Dashboard | Premium OBR</title>
<link href="https://googleapis.com" rel="stylesheet">
<style>
/* ─── PREMIUM VARIABLES ─── */
:root {
  --gold:      #c8a96e;
  --gold-l:    #e2c88a;
  --gold-d:    #8a6930;
  --gold-glow: rgba(200,169,110,.25);
  --bg:        #0a0908;
  --bg2:       #111009;
  --bg3:       #181510;
  --text:      #f0ebe1;
  --text-s:    #b8a888;
  --text-m:    #7a6e5a;
  --border:    rgba(200,169,110,.15);
  --red:       #e05050;
  --tr:        .3s ease;
}

* { margin: 0; padding: 0; box-sizing: border-box; }
body {
  font-family: 'DM Sans', sans-serif;
  background: var(--bg);
  color: var(--text);
  display: flex;
  min-height: 100vh;
}

/* ─── SIDEBAR ─── */
.sidebar {
  width: 280px;
  background: var(--bg2);
  border-right: 1px solid var(--border);
  padding: 3rem 1.5rem;
  position: fixed;
  height: 100vh;
  display: flex;
  flex-direction: column;
}
.brand {
  font-family: 'Cormorant Garamond', serif;
  font-size: 2rem;
  font-weight: 600;
  color: var(--gold);
  text-align: center;
  margin-bottom: 4rem;
  letter-spacing: 2px;
}
.nav-menu a {
  color: var(--text-s);
  text-decoration: none;
  padding: 1rem 1.2rem;
  border-radius: 4px;
  margin-bottom: 0.5rem;
  display: flex;
  align-items: center;
  gap: 12px;
  font-size: 0.85rem;
  text-transform: uppercase;
  letter-spacing: 1px;
  transition: var(--tr);
}
.nav-menu a:hover {
  background: var(--bg3);
  color: var(--gold);
  padding-left: 1.5rem;
}
.nav-menu a.logout { color: var(--red); opacity: 0.8; margin-top: auto; }

/* ─── MAIN CONTENT ─── */
.main { margin-left: 280px; padding: 4rem; width: 100%; }
.page-title { 
  font-family: 'Cormorant Garamond', serif; 
  font-size: 3.5rem; 
  color: var(--gold); 
  margin-bottom: 3rem;
}

/* ─── STAT CARDS ─── */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1.5rem;
  margin-bottom: 3rem;
}
.stat-card {
  background: var(--bg2);
  border: 1px solid var(--border);
  padding: 2.5rem 2rem;
  border-radius: 4px;
  transition: var(--tr);
}
.stat-card:hover {
  border-color: var(--gold);
  transform: translateY(-5px);
  box-shadow: 0 15px 40px rgba(0,0,0,0.4);
}
.stat-card p {
  font-size: 0.65rem;
  text-transform: uppercase;
  letter-spacing: 2px;
  color: var(--text-m);
  margin-bottom: 0.5rem;
}
.stat-card h3 {
  font-family: 'Cormorant Garamond', serif;
  font-size: 2.8rem;
  color: var(--gold);
}

/* ─── OVERVIEW PANEL ─── */
.overview-panel {
  background: var(--bg2);
  border: 1px solid var(--border);
  padding: 2.5rem;
  display: flex;
  justify-content: space-around;
  margin-bottom: 3rem;
}
.ov-num {
  font-family: 'Cormorant Garamond', serif;
  font-size: 3rem;
  color: var(--gold-l);
  display: block;
}
.ov-lbl {
  font-size: 0.7rem;
  text-transform: uppercase;
  letter-spacing: 2px;
  color: var(--text-m);
}

/* ─── TWO COLUMN LAYOUT ─── */
.two-col { display: grid; grid-template-columns: 1.5fr 1fr; gap: 2rem; }
.sec-card { background: var(--bg2); border: 1px solid var(--border); border-radius: 4px; }
.sec-hdr {
  padding: 1.5rem;
  border-bottom: 1px solid var(--border);
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.sec-hdr h3 { font-family: 'Cormorant Garamond', serif; font-size: 1.5rem; color: var(--gold); }

.notif-item {
  padding: 1.2rem 1.5rem;
  border-bottom: 1px solid var(--border);
  font-size: 0.85rem;
  transition: var(--tr);
}
.notif-item:hover { background: var(--bg3); }
.ni-time { color: var(--text-m); font-size: 0.75rem; margin-top: 4px; display: block; }

.nav-badge {
    background: var(--gold);
    color: var(--bg);
    font-size: 10px;
    padding: 2px 6px;
    border-radius: 2px;
    margin-left: auto;
}

</style>
</head>
<body>

<aside class="sidebar">
    <div class="brand">OBR <em>VIP</em></div>
    <div class="nav-menu">
        <a href="manage_dishes.php">🍴 Manage Dishes</a>
        <a href="view_queue.php">👥 View Queue</a>
        <a href="reports.php">📊 Analytics</a>
        <a href="daily_report.php">📑 Daily Sales</a>
        <a href="view_feedback.php">💬 Feedback</a>
        <a href="notifications.php">
            🔔 Notifications
            <?php if($notif_count > 0): ?>
            <span class="nav-badge"><?= $notif_count ?></span>
            <?php endif; ?>
        </a>
        <a href="logout.php" class="logout">🚪 Logout</a>
    </div>
</aside>

<main class="main">
    <p style="color: var(--gold); font-size: 0.8rem; letter-spacing: 3px; text-transform: uppercase;">Management Console</p>
    <h1 class="page-title">Admin <em>Dashboard</em></h1>

    <div class="stats-grid">
        <div class="stat-card">
            <p>Dishes Active</p>
            <h3><?= $total_dishes ?></h3>
        </div>
        <div class="stat-card">
            <p>Currently Serving</p>
            <h3><?= $serving_now ?></h3>
        </div>
        <div class="stat-card">
            <p>Today's Orders</p>
            <h3><?= $today_orders ?></h3>
        </div>
        <div class="stat-card">
            <p>Today's Sales</p>
            <h3>Rs. <?= number_format($today_sales) ?></h3>
        </div>
    </div>

    <div class="overview-panel">
        <div style="text-align:center">
            <span class="ov-num"><?= $total_queue ?></span>
            <span class="ov-lbl">Waiting in Queue</span>
        </div>
        <div style="text-align:center">
            <span class="ov-num"><?= $served_today ?></span>
            <span class="ov-lbl">Served Today</span>
        </div>
        <div style="text-align:center">
            <span class="ov-num"><?= $total_customers ?></span>
            <span class="ov-lbl">Total Members</span>
        </div>
    </div>

    <div class="two-col">
        <div class="sec-card">
            <div class="sec-hdr">
                <h3>Recent Notifications</h3>
                <a href="notifications.php" style="color: var(--gold); text-decoration: none; font-size: 0.7rem; text-transform: uppercase;">View All</a>
            </div>
            <?php while($n = $recent_notifs->fetch_assoc()): ?>
            <div class="notif-item">
                <div class="ni-msg" style="color: var(--text-s)"><?= htmlspecialchars($n['message']) ?></div>
                <span class="ni-time"><?= date('h:i A', strtotime($n['created_at'])) ?></span>
            </div>
            <?php endwhile; ?>
        </div>

        <div class="sec-card" style="padding: 2rem;">
            <h3 style="font-family: 'Cormorant Garamond', serif; color: var(--gold); margin-bottom: 1.5rem;">Quick Actions</h3>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <a href="add_dish.php" style="background: var(--gold); color: var(--bg); text-decoration: none; padding: 1rem; text-align: center; font-weight: 700; border-radius: 4px; font-size: 0.8rem; text-transform: uppercase;">+ New Dish Listing</a>
                <a href="reports.php" style="border: 1px solid var(--gold); color: var(--gold); text-decoration: none; padding: 1rem; text-align: center; font-weight: 700; border-radius: 4px; font-size: 0.8rem; text-transform: uppercase;">Generate Full Report</a>
            </div>
        </div>
    </div>
</main>

</body>
</html>
