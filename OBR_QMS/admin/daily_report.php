<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}
include 'db.php'; // Ensure db.php is in the same folder

// ── DATE FILTER (default = today) ────────────────────────────
$selected_date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$date_esc      = $conn->real_escape_string($selected_date);

// ── SUMMARY: Total Orders + Revenue + Avg ────────────────────
$sum = $conn->query("
    SELECT 
        COUNT(*)             AS total_orders,
        COALESCE(SUM(total_amount), 0) AS total_revenue,
        COALESCE(AVG(total_amount), 0) AS avg_order
    FROM orders
    WHERE DATE(created_at) = '$date_esc'
")->fetch_assoc();

$today_orders  = $sum['total_orders'];
$today_sales   = $sum['total_revenue'];
$today_avg     = $sum['avg_order'];

// ── ALL ORDERS FOR SELECTED DATE ─────────────────────────────
$orders_q = $conn->query("
    SELECT o.id, o.customer_name, o.total_amount, o.created_at,
           COUNT(oi.id) AS item_count
    FROM orders o
    LEFT JOIN order_items oi ON oi.order_id = o.id
    WHERE DATE(o.created_at) = '$date_esc'
    GROUP BY o.id
    ORDER BY o.created_at DESC
");
$orders = [];
if($orders_q) while($r = $orders_q->fetch_assoc()) $orders[] = $r;

// ── TOP 5 DISHES FOR SELECTED DATE ───────────────────────────
$top_q = $conn->query("
    SELECT d.name AS dish_name, d.price,
           SUM(oi.quantity)              AS total_qty,
           SUM(oi.quantity * d.price)   AS dish_revenue
    FROM order_items oi
    JOIN orders o ON o.id = oi.order_id
    JOIN dishes d  ON d.id = oi.dish_id
    WHERE DATE(o.created_at) = '$date_esc'
    GROUP BY oi.dish_id
    ORDER BY total_qty DESC
    LIMIT 5
");
$top_dishes = [];
if($top_q) while($r = $top_q->fetch_assoc()) $top_dishes[] = $r;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Daily Sales Report | Premium Admin</title>
    <link href="https://googleapis.com" rel="stylesheet">
    <style>
        :root {
            --gold:      #c8a96e;
            --gold-l:    #e2c88a;
            --gold-d:    #8a6930;
            --bg:        #0a0908;
            --bg2:       #111009;
            --bg3:       #181510;
            --text:      #f0ebe1;
            --text-s:    #b8a888;
            --text-m:    #7a6e5a;
            --border:    rgba(200,169,110,.2);
            --border-s:  rgba(255,255,255,.07);
            --tr:        .25s ease;
        }

        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            padding: 3rem;
        }

        .container { max-width: 1300px; margin: auto; animation: fadeIn .6s ease; }
        @keyframes fadeIn { from{opacity:0; transform:translateY(10px)} to{opacity:1; transform:none} }

        .page-header {
            display: flex; justify-content: space-between; align-items: flex-end;
            margin-bottom: 3rem; border-bottom: 1px solid var(--border); padding-bottom: 2rem;
        }
        .page-title h1 { font-family: 'Cormorant Garamond', serif; font-size: 3rem; color: var(--gold); }
        .page-title p { color: var(--text-s); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.2em; }

        .date-form {
            display: flex; align-items: center; gap: 1rem;
            background: var(--bg2); border: 1px solid var(--border);
            padding: 1rem 1.5rem; border-radius: 4px;
        }
        .date-form input[type="date"] {
            background: var(--bg3); border: 1px solid var(--border);
            color: var(--text); padding: .5rem; border-radius: 4px; outline: none;
        }
        .btn-filter {
            background: var(--gold); color: var(--bg); border: none;
            padding: .6rem 1.2rem; border-radius: 4px; font-weight: 600; cursor: pointer;
        }

        .summary-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 3rem; }
        .metric-card { background: var(--bg2); border: 1px solid var(--border); padding: 2.2rem; border-radius: 4px; }
        .metric-card h3 { font-size: .65rem; text-transform: uppercase; letter-spacing: .2em; color: var(--text-s); margin-bottom: 1rem; }
        .metric-value { font-family: 'Cormorant Garamond', serif; font-size: 3.2rem; color: var(--gold); }

        .report-section { background: var(--bg2); border: 1px solid var(--border); border-radius: 4px; padding: 2rem; margin-bottom: 2rem; }
        .section-title { font-family: 'Cormorant Garamond', serif; font-size: 1.8rem; color: var(--gold); margin-bottom: 1.5rem; }
        
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; font-size: 0.7rem; text-transform: uppercase; color: var(--gold); padding: 1rem; border-bottom: 1px solid var(--border); }
        td { padding: 1.2rem 1rem; font-size: 0.9rem; border-bottom: 1px solid var(--border-s); color: var(--text-s); }

        .back-btn {
            display: inline-block; color: var(--text-m); text-decoration: none;
            font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em;
            margin-bottom: 1.5rem; transition: var(--tr);
        }
        .back-btn:hover { color: var(--gold); }
    </style>
</head>
<body>

<div class="container">
    <!-- FIX: Link changed from admin_dashboard.php to dashboard.php -->
    <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>

    <div class="page-header">
        <div class="page-title">
            <p>Financial Overview</p>
            <h1>Daily <em>Report</em></h1>
        </div>
        
        <form class="date-form" method="GET">
            <input type="date" name="date" value="<?php echo $selected_date; ?>">
            <button type="submit" class="btn-filter">Update</button>
        </form>
    </div>

    <div class="summary-row">
        <div class="metric-card">
            <h3>Total Orders</h3>
            <span class="metric-value"><?php echo $today_orders; ?></span>
        </div>
        <div class="metric-card">
            <h3>Total Revenue</h3>
            <span class="metric-value">Rs. <?= number_format($today_sales, 2); ?></span>
        </div>
        <div class="metric-card">
            <h3>Avg. Ticket</h3>
            <span class="metric-value">Rs. <?= number_format($today_avg, 2); ?></span>
        </div>
    </div>

    <div class="report-section">
        <h2 class="section-title">Order Log for <?= date('M d, Y', strtotime($selected_date)); ?></h2>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($orders)): ?>
                    <tr><td colspan="4" style="text-align:center;">No records found.</td></tr>
                <?php else: foreach($orders as $o): ?>
                    <tr>
                        <td>#<?= $o['id']; ?></td>
                        <td><?= htmlspecialchars($o['customer_name']); ?></td>
                        <td><?= $o['item_count']; ?> Items</td>
                        <td style="color:var(--gold)">Rs. <?= number_format($o['total_amount'], 2); ?></td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
