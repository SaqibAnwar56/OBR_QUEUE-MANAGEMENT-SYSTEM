<?php
// Session check
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "obr_qms");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/* DELETE FEEDBACK Logic */
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM customer_feedback WHERE id=$id");
    header("Location: view_feedback.php");
    exit();
}

/* FETCH DATA Logic */
$result = $conn->query("SELECT * FROM customer_feedback ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Experience | Premium Admin</title>
    <!-- Premium Fonts -->
    <link href="https://googleapis.com" rel="stylesheet">
    
    <style>
        :root {
            --gold:      #c8a96e;
            --gold-l:    #e2c88a;
            --gold-d:    #8a6930;
            --gold-dim:  rgba(200,169,110,.12);
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

        body {
            margin: 0;
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            padding: 4rem;
        }

        .container {
            max-width: 1250px;
            margin: auto;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ─── HEADER AREA ─── */
        .header-area {
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

        /* ─── TABLE STYLING ─── */
        .table-card {
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: 4px;
            overflow: hidden;
            box-shadow: 0 30px 70px rgba(0, 0, 0, 0.6);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th {
            background: var(--bg3);
            padding: 1.5rem;
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--gold-d);
            border-bottom: 1px solid var(--border);
        }

        td {
            padding: 1.8rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            font-size: 0.9rem;
            vertical-align: top;
            color: var(--text-s);
        }

        tr:hover td { 
            background: rgba(200, 169, 110, 0.02); 
            color: var(--text);
        }

        /* ─── BADGES ─── */
        .badge {
            padding: 4px 12px;
            border-radius: 2px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            background: var(--gold-dim);
            color: var(--gold);
            border: 1px solid var(--border);
        }

        .message-cell {
            max-width: 350px;
            font-style: italic;
            line-height: 1.7;
            color: var(--text-m);
        }

        .delete-btn {
            color: var(--red);
            text-decoration: none;
            font-weight: 700;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: var(--tr);
            opacity: 0.7;
        }

        .delete-btn:hover {
            opacity: 1;
            letter-spacing: 2px;
        }

        .empty-msg {
            text-align: center;
            padding: 6rem;
            color: var(--text-m);
            font-family: 'Cormorant+Garamond', serif;
            font-size: 1.4rem;
        }

        .patron-name {
            color: var(--text);
            font-weight: 600;
            font-size: 1rem;
            display: block;
            margin-bottom: 4px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header-area">
        <div>
            <a href="dashboard.php" class="back-btn">← Console Dashboard</a>
            <h2>Patron <em>Feedback</em></h2>
        </div>
        <div style="text-align: right; color: var(--text-m); text-transform: uppercase; letter-spacing: 2px; font-size: 0.7rem;">
            Total Submissions<br>
            <strong style="color: var(--gold); font-size: 1.8rem; font-family: 'Cormorant Garamond', serif;">
                <?php echo $result->num_rows; ?>
            </strong>
        </div>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Ref</th>
                    <th>Patron Details</th>
                    <th>Category</th>
                    <th>Observation</th>
                    <th>Date</th>
                    <th style="text-align: center;">Controls</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td style="font-family: serif; color: var(--gold-d);">#<?php echo str_pad($row['id'], 3, '0', STR_PAD_LEFT); ?></td>
                            <td>
                                <span class="patron-name"><?php echo htmlspecialchars($row['name']); ?></span>
                                <small style="color: var(--text-m); letter-spacing: 0.5px;"><?php echo htmlspecialchars($row['email']); ?></small>
                            </td>
                            <td><span class="badge"><?php echo htmlspecialchars($row['feedback_type']); ?></span></td>
                            <td class="message-cell">"<?php echo htmlspecialchars($row['message']); ?>"</td>
                            <td style="font-size: 0.8rem; color: var(--text-m);">
                                <?php echo date('M d, Y', strtotime($row['created_at'])); ?>
                            </td>
                            <td style="text-align: center;">
                                <a href="view_feedback.php?delete=<?php echo $row['id']; ?>" 
                                   class="delete-btn"
                                   onclick="return confirm('Secure Action: Permanently archive this feedback?');">
                                   Discard
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="empty-msg">The feedback ledger is currently empty.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>

<?php $conn->close(); ?>
