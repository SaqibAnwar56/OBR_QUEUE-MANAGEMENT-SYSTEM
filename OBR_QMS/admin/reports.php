<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

include 'db.php';

/* ===============================
   TOTAL ORDERS
================================ */
$total_orders = 0;
$res1 = $conn->query("SELECT COUNT(*) as total FROM orders");
if($res1){
    $row1 = $res1->fetch_assoc();
    $total_orders = $row1['total'] ?? 0;
}

/* ===============================
   TOTAL REVENUE
================================ */
$total_revenue = 0;
$res2 = $conn->query("SELECT SUM(total_amount) as total FROM orders");
if($res2){
    $row2 = $res2->fetch_assoc();
    $total_revenue = $row2['total'] ?? 0;
}

/* ===============================
   TOP 5 POPULAR DISHES
================================ */
$popular = $conn->query("
    SELECT d.name, SUM(oi.quantity) as total_qty
    FROM order_items oi
    JOIN dishes d ON oi.dish_id = d.id
    GROUP BY oi.dish_id
    ORDER BY total_qty DESC
    LIMIT 5
");

/* ===============================
   RECENT 5 ORDERS
================================ */
$recent = $conn->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Analytics Intelligence | Premium Admin</title>
    <link href="https://googleapis.com" rel="stylesheet">
    <style>
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
            --tr:        .3s ease;
        }

        body {
            margin: 0;
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            padding: 4rem;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ─── HEADER ─── */
        .header-flex {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 4rem;
            border-bottom: 1px solid var(--border);
            padding-bottom: 2rem;
        }

        h2 { 
            font-family: 'Cormorant Garamond', serif;
            font-size: 3.5rem; 
            color: var(--gold);
            margin: 0; 
        }

        .back-btn {
            text-decoration: none;
            color: var(--text-m);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            transition: var(--tr);
            display: block;
            margin-bottom: 0.5rem;
        }

        .back-btn:hover { color: var(--gold); }

        /* ─── STATS GRID ─── */
        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: var(--bg2);
            border: 1px solid var(--border);
            padding: 3rem;
            border-radius: 4px;
            transition: var(--tr);
        }

        .stat-card:hover { 
            border-color: var(--gold); 
            transform: translateY(-5px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
        }

        .stat-card h3 { 
            font-size: 0.65rem; 
            text-transform: uppercase; 
            letter-spacing: 3px; 
            color: var(--text-m); 
            margin: 0; 
        }

        .stat-card .value { 
            font-family: 'Cormorant Garamond', serif;
            font-size: 4rem; 
            color: var(--gold);
            margin-top: 10px; 
            display: block; 
            line-height: 1;
        }

        /* ─── TABLES ─── */
        .report-section {
            background: var(--bg2);
            border: 1px solid var(--border);
            padding: 2.5rem;
            margin-bottom: 2.5rem;
            border-radius: 4px;
        }

        .report-section h3 { 
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.8rem; 
            color: var(--gold);
            margin-bottom: 2rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th {
            background: var(--bg3);
            padding: 1.2rem;
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--gold-d);
            border-bottom: 1px solid var(--border);
        }

        td {
            padding: 1.5rem 1.2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            font-size: 0.9rem;
            color: var(--text-s);
        }

        tr:hover td { 
            background: rgba(200, 169, 110, 0.03); 
            color: var(--text);
        }

        .currency { color: var(--gold-l); font-weight: 600; font-family: 'Cormorant Garamond', serif; font-size: 1.2rem; }
        .qty-badge { color: var(--gold); font-weight: 700; font-size: 0.85rem; }

    </style>
</head>
<body>

<div class="container">
    <div class="header-flex">
        <div>
            <a href="dashboard.php" class="back-btn">← Console Dashboard</a>
            <h2>Analytics <em>Intelligence</em></h2>
        </div>
        <div style="text-align: right; color: var(--text-m); font-size: 0.7rem; text-transform: uppercase; letter-spacing: 2px;">
            Vault Synchronization: <br><strong style="color: var(--gold);"><?php echo date('H:i'); ?> Today</strong>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <h3>Cumulative Orders</h3>
            <span class="value"><?php echo number_format($total_orders); ?></span>
        </div>
        <div class="stat-card">
            <h3>Total Revenue</h3>
            <span class="value"><small style="font-size: 1.5rem;">Rs.</small> <?php echo number_format($total_revenue, 2); ?></span>
        </div>
    </div>

    <div class="report-section">
        <h3>🔥 Gastronomic Popularity (Top 5)</h3>
        <table>
            <thead>
                <tr>
                    <th>Designation</th>
                    <th>Volume Sold</th>
                </tr>
            </thead>
            <tbody>
                <?php if($popular && $popular->num_rows > 0){ ?>
                    <?php while($row = $popular->fetch_assoc()){ ?>
                        <tr>
                            <td style="font-weight: 500; color: var(--text);"><?php echo $row['name']; ?></td>
                            <td><span class="qty-badge"><?php echo $row['total_qty']; ?> Units</span></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr><td colspan="2" style="text-align: center; color: var(--text-m);">No analytical data available</td></tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="report-section">
        <h3>🕒 Recent Transactions</h3>
        <table>
            <thead>
                <tr>
                    <th>Ref ID</th>
                    <th>Patron Name</th>
                    <th>Investment</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <?php if($recent && $recent->num_rows > 0){ ?>
                    <?php while($row = $recent->fetch_assoc()){ ?>
                        <tr>
                            <td style="color: var(--gold-d); font-family: serif;">#<?php echo str_pad($row['id'], 4, '0', STR_PAD_LEFT); ?></td>
                            <td style="color: var(--text);"><?php echo htmlspecialchars($row['customer_name']); ?></td>
                            <td class="currency">Rs. <?php echo number_format($row['total_amount'], 2); ?></td>
                            <td style="color: var(--text-m); font-size: 0.8rem;"><?php echo date('M d, H:i', strtotime($row['created_at'])); ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr><td colspan="4" style="text-align: center; color: var(--text-m);">No order history found</td></tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
