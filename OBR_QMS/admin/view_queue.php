<?php
include("../includes/db.php");
session_start();
if(!isset($_SESSION['admin_id'])){ header("Location: login.php"); exit(); }

// ─── STATUS ACTIONS ───────────────────────────────────────────
if(isset($_GET['serve'])){
    $id = intval($_GET['serve']);
    $conn->query("UPDATE queue SET status='serving' WHERE id=$id AND status='waiting'");
    header("Location: view_queue.php"); exit;
}
if(isset($_GET['complete'])){
    $id = intval($_GET['complete']);
    $conn->query("UPDATE queue SET status='served' WHERE id=$id AND status='serving'");
    header("Location: view_queue.php"); exit;
}
if(isset($_GET['cancel'])){
    $id = intval($_GET['cancel']);
    $conn->query("UPDATE queue SET status='cancelled' WHERE id=$id AND status='waiting'");
    header("Location: view_queue.php"); exit;
}

// ─── QUEUE DATA ───────────────────────────────────────────────
$result = $conn->query("
    SELECT * FROM queue 
    WHERE status IN ('waiting','serving','served','cancelled')
    ORDER BY FIELD(status,'serving','waiting','served','cancelled'), token_number ASC
");

$stats_waiting  = (int)$conn->query("SELECT COUNT(*) as c FROM queue WHERE status='waiting'")->fetch_assoc()['c'];
$stats_serving  = (int)$conn->query("SELECT COUNT(*) as c FROM queue WHERE status='serving'")->fetch_assoc()['c'];
$stats_served   = (int)$conn->query("SELECT COUNT(*) as c FROM queue WHERE status='served' AND DATE(created_at)=CURDATE()")->fetch_assoc()['c'];
$stats_total    = (int)$conn->query("SELECT COUNT(*) as c FROM queue WHERE DATE(created_at)=CURDATE()")->fetch_assoc()['c'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Queue | Premium Admin</title>
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
            --red:       #e05050;
            --green:     #4db37e;
            --tr:        .3s ease;
        }

        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            padding: 3rem;
        }

        .container { max-width: 1400px; margin: auto; animation: fadeIn .6s ease; }
        @keyframes fadeIn { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:none} }

        /* ── HEADER ── */
        .page-header {
            display: flex; justify-content: space-between; align-items: flex-end;
            margin-bottom: 3rem; border-bottom: 1px solid var(--border); padding-bottom: 2rem;
        }
        .page-title h1 { font-family: 'Cormorant Garamond', serif; font-size: 3.5rem; color: var(--gold); }
        .page-sub { color: var(--text-m); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.2em; }

        .back-btn {
            text-decoration: none; color: var(--text-m); font-size: 0.75rem;
            text-transform: uppercase; letter-spacing: 2px; transition: var(--tr);
        }
        .back-btn:hover { color: var(--gold); }

        /* ── STATS ROW ── */
        .stats-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.2rem; margin-bottom: 3rem; }
        .stat-card {
            background: var(--bg2); border: 1px solid var(--border);
            padding: 2rem; border-radius: 4px; text-align: center; transition: var(--tr);
        }
        .stat-card:hover { border-color: var(--gold); transform: translateY(-5px); }
        .stat-num { font-family: 'Cormorant Garamond', serif; font-size: 2.8rem; color: var(--gold); line-height: 1; }
        .stat-lbl { font-size: 0.6rem; color: var(--text-m); text-transform: uppercase; letter-spacing: 2px; margin-top: 0.5rem; display: block; }

        /* ── LIVE HIGHLIGHT ── */
        .live-banner {
            display: flex; align-items: center; justify-content: center; gap: 3rem;
            background: var(--bg2); border: 1px solid var(--gold-d);
            padding: 2rem; margin-bottom: 3rem; border-radius: 4px;
        }
        .live-tok { font-family: 'Cormorant Garamond', serif; font-size: 4rem; color: var(--gold); line-height: 1; }
        .live-label { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.3em; color: var(--gold-d); }

        /* ── TABLE ── */
        .table-wrapper { background: var(--bg2); border: 1px solid var(--border); border-radius: 4px; overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        th {
            background: var(--bg3); padding: 1.2rem; text-align: left;
            font-size: 0.65rem; text-transform: uppercase; letter-spacing: 2px; color: var(--gold-d);
        }
        td { padding: 1.5rem 1.2rem; border-bottom: 1px solid var(--border); font-size: 0.85rem; color: var(--text-s); }
        tr:hover td { background: rgba(200,169,110,0.02); color: var(--text); }

        tr.row-serving td { border-left: 4px solid var(--gold); background: rgba(200,169,110,0.05); }

        .tok-num {
            font-family: 'Cormorant Garamond', serif; font-size: 1.5rem;
            font-weight: 600; color: var(--gold-l);
        }

        /* ── ACTIONS ── */
        .btn {
            text-decoration: none; padding: 0.5rem 1.2rem; border-radius: 2px;
            font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; transition: var(--tr);
        }
        .btn-serve { background: var(--gold); color: var(--bg); }
        .btn-serve:hover { background: var(--gold-l); box-shadow: 0 5px 15px var(--gold-glow); }
        .btn-done  { border: 1px solid var(--green); color: var(--green); }
        .btn-cancel { color: var(--red); font-size: 0.65rem; margin-left: 10px; }

        .pill { font-size: 0.6rem; padding: 4px 10px; border-radius: 2px; text-transform: uppercase; font-weight: 700; }
        .p-waiting { border: 1px solid var(--gold-d); color: var(--gold-d); }
        .p-serving { background: var(--gold); color: var(--bg); animation: pulse 2s infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.7; } }

    </style>
</head>
<body>

<div class="container">
    <div class="page-header">
        <div class="page-title">
            <a href="dashboard.php" class="back-btn">← Console Dashboard</a>
            <h1>Queue <em>Orchestration</em></h1>
        </div>
        <div class="page-sub">Live Terminal Status: Connected</div>
    </div>

    <div class="stats-row">
        <div class="stat-card">
            <span class="stat-num num-waiting"><?= $stats_waiting ?></span>
            <span class="stat-lbl">In Queue</span>
        </div>
        <div class="stat-card">
            <span class="stat-num num-serving"><?= $stats_serving ?></span>
            <span class="stat-lbl">At Counters</span>
        </div>
        <div class="stat-card">
            <span class="stat-num num-served"><?= $stats_served ?></span>
            <span class="stat-lbl">Today's Guests</span>
        </div>
        <div class="stat-card">
            <span class="stat-num"><?= $stats_total ?></span>
            <span class="stat-lbl">Daily Capacity</span>
        </div>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Token</th>
                    <th>Guest Identity</th>
                    <th>Contact</th>
                    <th>Status</th>
                    <th style="text-align: right;">Command</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr class="<?= ($row['status']=='serving')?'row-serving':'' ?>">
                    <td><span class="tok-num">#<?= str_pad($row['token_number'], 3, '0', STR_PAD_LEFT) ?></span></td>
                    <td style="color: var(--text); font-weight: 500;"><?= htmlspecialchars($row['customer_name']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                    <td>
                        <span class="pill p-<?= $row['status'] ?>">
                            <?= strtoupper($row['status']) ?>
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <?php if($row['status'] == 'waiting'): ?>
                            <a href="?serve=<?= $row['id'] ?>" class="btn btn-serve">Initialize</a>
                            <a href="?cancel=<?= $row['id'] ?>" class="btn btn-cancel" onclick="return confirm('Void this token?')">Void</a>
                        <?php elseif($row['status'] == 'serving'): ?>
                            <a href="?complete=<?= $row['id'] ?>" class="btn btn-done">Complete Service</a>
                        <?php else: ?>
                            <span style="color: var(--text-m); font-size: 0.7rem;">Archive Only</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
