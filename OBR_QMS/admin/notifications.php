<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}
include 'db.php';

// Mark all as read
if(isset($_GET['mark_read'])){
    $conn->query("UPDATE notifications SET is_read=1 WHERE is_read=0");
    header("Location: notifications.php");
    exit();
}

// Delete all
if(isset($_GET['delete_all'])){
    $conn->query("TRUNCATE TABLE notifications");
    header("Location: notifications.php");
    exit();
}

// Fetch all notifications
$notifications = $conn->query("SELECT * FROM notifications ORDER BY created_at DESC");
$unread_count  = (int)$conn->query("SELECT COUNT(*) as c FROM notifications WHERE is_read=0")->fetch_assoc()['c'];
$total_count   = (int)$conn->query("SELECT COUNT(*) as c FROM notifications")->fetch_assoc()['c'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Notifications | Premium Admin</title>
<link href="https://googleapis.com" rel="stylesheet">
<style>
/* ─── VARIABLES ─── */
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

* { margin:0; padding:0; box-sizing:border-box; }
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
.nav-menu a:hover, .nav-menu a.active {
  background: var(--bg3);
  color: var(--gold);
  padding-left: 1.5rem;
}
.nav-menu a.logout { color: var(--red); opacity: 0.8; margin-top: auto; }

/* ─── MAIN CONTENT ─── */
.main { margin-left: 280px; padding: 4rem; width: 100%; animation: fadeIn 0.6s ease; }
@keyframes fadeIn { from{opacity:0; transform:translateY(10px)} to{opacity:1; transform:none} }

.page-header {
    display: flex; justify-content: space-between; align-items: flex-end;
    margin-bottom: 3rem; border-bottom: 1px solid var(--border); padding-bottom: 2rem;
}
.page-title h1 { font-family: 'Cormorant Garamond', serif; font-size: 3.5rem; color: var(--gold); }
.page-sub { color: var(--text-m); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.2em; }

.btn-row { display: flex; gap: 1rem; }
.btn {
    text-decoration: none; padding: 0.8rem 1.5rem; border-radius: 4px;
    font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;
    transition: var(--tr); display: inline-flex; align-items: center; border: 1px solid var(--border);
}
.btn-back { color: var(--text-s); }
.btn-back:hover { color: var(--gold); border-color: var(--gold); }
.btn-read { background: var(--gold); color: var(--bg); border: none; }
.btn-read:hover { background: var(--gold-l); transform: translateY(-2px); box-shadow: 0 10px 20px var(--gold-glow); }
.btn-del { color: var(--red); border-color: rgba(224,80,80,0.3); }
.btn-del:hover { background: rgba(224,80,80,0.1); }

/* ─── SUMMARY ─── */
.sum-row { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; margin-bottom: 3rem; }
.sum-card {
    background: var(--bg2); border: 1px solid var(--border);
    padding: 2rem; border-radius: 4px; display: flex; align-items: center; gap: 1.5rem;
}
.sum-num { font-family: 'Cormorant Garamond', serif; font-size: 3rem; color: var(--gold); line-height: 1; }
.sum-lbl { font-size: 0.65rem; color: var(--text-m); text-transform: uppercase; letter-spacing: 2px; }

/* ─── NOTIFICATION LIST ─── */
.notif-list { display: flex; flex-direction: column; gap: 1rem; }
.notif-card {
    background: var(--bg2); border: 1px solid var(--border);
    padding: 1.5rem 2rem; border-radius: 4px;
    display: flex; align-items: center; gap: 2rem; transition: var(--tr);
}
.notif-card:hover { border-color: var(--gold); background: var(--bg3); }
.notif-card.unread { border-left: 4px solid var(--gold); background: rgba(200,169,110,0.03); }

.notif-body { flex: 1; }
.notif-msg { font-size: 0.95rem; color: var(--text-s); line-height: 1.6; }
.notif-card.unread .notif-msg { color: var(--text); font-weight: 500; }
.notif-time { font-size: 0.75rem; color: var(--text-m); margin-top: 0.5rem; display: block; }

.badge {
    font-size: 0.6rem; padding: 4px 10px; border-radius: 2px;
    text-transform: uppercase; letter-spacing: 1px; font-weight: 700;
}
.badge-new { background: var(--gold); color: var(--bg); }
.badge-read { border: 1px solid var(--border); color: var(--text-m); }

.nav-badge { background: var(--gold); color: var(--bg); font-size: 10px; padding: 2px 6px; border-radius: 2px; margin-left: auto; }

.empty { text-align: center; padding: 5rem; border: 1px dashed var(--border); color: var(--text-m); font-family: 'Cormorant Garamond', serif; font-size: 1.5rem; }

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
        <a href="notifications.php" class="active">🔔 Notifications
            <?php if($unread_count > 0): ?>
            <span class="nav-badge"><?= $unread_count ?></span>
            <?php endif; ?>
        </a>
        <a href="logout.php" class="logout">🚪 Logout</a>
    </div>
</aside>

<main class="main">

    <div class="page-header">
        <div class="page-title">
            <span class="page-sub">Alert System</span>
            <h1><em>Notifications</em> Center</h1>
        </div>
        <div class="btn-row">
            <?php if($unread_count > 0): ?>
            <a href="notifications.php?mark_read=1" class="btn btn-read">Mark All Read</a>
            <?php endif; ?>
            <?php if($total_count > 0): ?>
            <a href="notifications.php?delete_all=1" class="btn btn-del" onclick="return confirm('Clear all alert history?')">Clear Archive</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="sum-row">
        <div class="sum-card">
            <div class="sum-num"><?= $unread_count ?></div>
            <div class="sum-lbl">Pending Review</div>
        </div>
        <div class="sum-card">
            <div class="sum-num"><?= $total_count ?></div>
            <div class="sum-lbl">Total Archive</div>
        </div>
    </div>

    <div class="notif-list">
        <?php if($total_count == 0): ?>
            <div class="empty">The archive is currently empty.</div>
        <?php else: ?>
            <?php while($n = $notifications->fetch_assoc()): ?>
            <div class="notif-card <?= $n['is_read'] ? 'read' : 'unread' ?>">
                <div class="notif-body">
                    <div class="notif-msg"><?= htmlspecialchars($n['message']) ?></div>
                    <span class="notif-time"><?= date('M d, Y • h:i A', strtotime($n['created_at'])) ?></span>
                </div>
                <span class="badge <?= $n['is_read'] ? 'badge-read' : 'badge-new' ?>">
                    <?= $n['is_read'] ? 'Archived' : 'New Alert' ?>
                </span>
            </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>

</main>

</body>
</html>
