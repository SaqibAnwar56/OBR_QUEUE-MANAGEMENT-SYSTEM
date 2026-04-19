<?php
session_start();
if(!isset($_SESSION['admin_id'])){ header("Location: login.php"); exit(); }
include 'db.php';

// Confirm payment
if(isset($_GET['confirm']) && is_numeric($_GET['confirm'])){
    $id = (int)$_GET['confirm'];
    $conn->query("UPDATE orders SET payment_status='paid' WHERE id=$id");
    header("Location: view_payments.php?msg=confirmed"); exit;
}

// Reject / reset to unpaid
if(isset($_GET['reject']) && is_numeric($_GET['reject'])){
    $id = (int)$_GET['reject'];
    $conn->query("UPDATE orders SET payment_status='unpaid', transaction_id=NULL WHERE id=$id");
    header("Location: view_payments.php?msg=rejected"); exit;
}

// Filter logic
$filter = $_GET['filter'] ?? 'all';
$where  = '';
if($filter === 'pending')  $where = "WHERE payment_status='pending_verification'";
elseif($filter === 'paid') $where = "WHERE payment_status='paid'";
elseif($filter === 'unpaid') $where = "WHERE payment_status='unpaid'";

// Statistics
$total_pending  = (int)$conn->query("SELECT COUNT(*) as c FROM orders WHERE payment_status='pending_verification'")->fetch_assoc()['c'];
$total_paid     = (int)$conn->query("SELECT COUNT(*) as c FROM orders WHERE payment_status='paid'")->fetch_assoc()['c'];
$total_unpaid   = (int)$conn->query("SELECT COUNT(*) as c FROM orders WHERE payment_status='unpaid'")->fetch_assoc()['c'];
$revenue_paid   = (float)$conn->query("SELECT COALESCE(SUM(total_amount),0) as s FROM orders WHERE payment_status='paid'")->fetch_assoc()['s'];

// Orders Query
$orders_q = $conn->query("
    SELECT o.*, COUNT(oi.id) as item_count
    FROM orders o
    LEFT JOIN order_items oi ON oi.order_id = o.id
    $where
    GROUP BY o.id
    ORDER BY
        CASE payment_status WHEN 'pending_verification' THEN 1 WHEN 'unpaid' THEN 2 ELSE 3 END,
        o.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Payment Ledger | Premium Admin</title>
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
  --green:     #4db37e;
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
}

/* ─── MAIN CONTENT ─── */
.main { margin-left: 280px; padding: 4rem; width: 100%; animation: fadeIn 0.6s ease; }
@keyframes fadeIn { from{opacity:0; transform:translateY(10px)} to{opacity:1; transform:none} }

.page-header {
    display: flex; justify-content: space-between; align-items: flex-end;
    margin-bottom: 3rem; border-bottom: 1px solid var(--border); padding-bottom: 2rem;
}
.page-title h1 { font-family: 'Cormorant Garamond', serif; font-size: 3.5rem; color: var(--gold); }
.page-sub { color: var(--text-m); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.2em; }

/* ─── STATS ─── */
.stats-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.2rem; margin-bottom: 3rem; }
.stat-card {
    background: var(--bg2); border: 1px solid var(--border);
    padding: 2rem; border-radius: 4px; text-align: center; transition: var(--tr);
}
.stat-card:hover { border-color: var(--gold); transform: translateY(-5px); }
.s-num { font-family: 'Cormorant Garamond', serif; font-size: 2.4rem; color: var(--gold); line-height: 1; display: block; }
.s-lbl { font-size: 0.6rem; color: var(--text-m); text-transform: uppercase; letter-spacing: 2px; margin-top: 0.5rem; display: block; }

/* ─── FILTER TABS ─── */
.filter-tabs { display: flex; gap: 0.8rem; margin-bottom: 2rem; }
.ftab {
    text-decoration: none; padding: 0.6rem 1.4rem; border-radius: 2px;
    font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;
    border: 1px solid var(--border); color: var(--text-m); transition: var(--tr);
}
.ftab:hover, .ftab.active { color: var(--gold); border-color: var(--gold); }
.ftab.active { background: var(--gold-dim); }

/* ─── TABLE ─── */
.table-wrap { background: var(--bg2); border: 1px solid var(--border); border-radius: 4px; overflow: hidden; }
table { width: 100%; border-collapse: collapse; }
th {
    background: var(--bg3); padding: 1.2rem; text-align: left;
    font-size: 0.65rem; text-transform: uppercase; letter-spacing: 2px; color: var(--gold-d);
}
td { padding: 1.5rem 1.2rem; border-bottom: 1px solid var(--border); font-size: 0.85rem; color: var(--text-s); }
tr:hover td { background: rgba(200,169,110,0.02); color: var(--text); }

/* Status Badges */
.pbadge { font-size: 0.6rem; padding: 4px 10px; border-radius: 2px; text-transform: uppercase; font-weight: 700; }
.pb-pending { background: var(--gold); color: var(--bg); animation: glow 2s infinite; }
.pb-paid { color: var(--green); border: 1px solid var(--green); }
.pb-unpaid { color: var(--text-m); border: 1px solid var(--border); }
@keyframes glow { 0%, 100% { opacity: 1; } 50% { opacity: 0.7; } }

/* Actions */
.btn-confirm { background: var(--gold); color: var(--bg); text-decoration: none; padding: 0.5rem 1rem; border-radius: 2px; font-weight: 700; font-size: 0.7rem; }
.btn-reject { color: var(--red); text-decoration: none; font-size: 0.7rem; font-weight: 700; margin-left: 10px; }

</style>
</head>
<body>

<aside class="sidebar">
    <div class="brand">OBR <em>VIP</em></div>
    <div class="nav-menu">
        <a href="dashboard.php">🏠 Dashboard</a>
        <a href="manage_dishes.php">🍴 Manage Dishes</a>
        <a href="view_payments.php" class="active">💳 Payment Ledger</a>
        <a href="daily_report.php">📑 Daily Sales</a>
        <a href="logout.php" class="logout">🚪 Logout</a>
    </div>
</aside>

<main class="main">
    <div class="page-header">
        <div class="page-title">
            <span class="page-sub">Financial Audit</span>
            <h1>Payment <em>Ledger</em></h1>
        </div>
        <div style="text-align: right;">
            <span class="s-lbl">Vault Revenue</span>
            <span class="s-num" style="font-size: 2.8rem;">Rs. <?= number_format($revenue_paid, 2) ?></span>
        </div>
    </div>

    <div class="stats-row">
        <div class="stat-card">
            <span class="s-num"><?= $total_pending ?></span>
            <span class="s-lbl">Pending Verification</span>
        </div>
        <div class="stat-card">
            <span class="s-num"><?= $total_paid ?></span>
            <span class="s-lbl">Confirmed Payments</span>
        </div>
        <div class="stat-card">
            <span class="s-num"><?= $total_unpaid ?></span>
            <span class="s-lbl">Outstanding</span>
        </div>
        <div class="stat-card">
            <span class="s-num"><?= $orders_q->num_rows ?></span>
            <span class="s-lbl">Total Transactions</span>
        </div>
    </div>

    <div class="filter-tabs">
        <a href="?filter=all" class="ftab <?= $filter=='all'?'active':'' ?>">All</a>
        <a href="?filter=pending" class="ftab <?= $filter=='pending'?'active':'' ?>">Pending</a>
        <a href="?filter=paid" class="ftab <?= $filter=='paid'?'active':'' ?>">Paid</a>
        <a href="?filter=unpaid" class="ftab <?= $filter=='unpaid'?'active':'' ?>">Unpaid</a>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Ref ID</th>
                    <th>Patron</th>
                    <th>Investment</th>
                    <th>Status</th>
                    <th>Transaction ID</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($o = $orders_q->fetch_assoc()): ?>
                <tr>
                    <td style="font-family: serif; color: var(--gold-d);">#<?= str_pad($o['id'], 4, '0', STR_PAD_LEFT) ?></td>
                    <td style="color: var(--text);"><?= htmlspecialchars($o['customer_name']) ?></td>
                    <td style="font-family: 'Cormorant Garamond', serif; font-size: 1.1rem; color: var(--gold-l);">Rs. <?= number_format($o['total_amount'], 2) ?></td>
                    <td>
                        <?php if($o['payment_status'] == 'pending_verification'): ?>
                            <span class="pbadge pb-pending">Verification Required</span>
                        <?php elseif($o['payment_status'] == 'paid'): ?>
                            <span class="pbadge pb-paid">Confirmed</span>
                        <?php else: ?>
                            <span class="pbadge pb-unpaid">Unpaid</span>
                        <?php endif; ?>
                    </td>
                    <td style="font-family: monospace; color: var(--gold-d);"><?= $o['transaction_id'] ?? '---' ?></td>
                    <td style="text-align: right;">
                        <?php if($o['payment_status'] == 'pending_verification'): ?>
                            <a href="?confirm=<?= $o['id'] ?>" class="btn-confirm">Approve</a>
                            <a href="?reject=<?= $o['id'] ?>" class="btn-reject" onclick="return confirm('Decline this transaction?')">Decline</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>

</body>
</html>
