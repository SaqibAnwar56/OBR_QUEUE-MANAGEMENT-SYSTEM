<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}
include 'db.php';

// Original Logic: Fetch dishes
$dishes = $conn->query("SELECT * FROM dishes ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cuisine Management | Premium Admin</title>
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
            --border-s:  rgba(255,255,255,.05);
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
            max-width: 1200px;
            margin: 0 auto;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ─── HEADER AREA ─── */
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

        .back-link {
            color: var(--text-m);
            text-decoration: none;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            transition: var(--tr);
            display: block;
            margin-bottom: 0.5rem;
        }

        .back-link:hover { color: var(--gold); }

        /* ─── ADD BUTTON ─── */
        .add-btn {
            background: var(--gold);
            color: var(--bg);
            text-decoration: none;
            padding: 1rem 2rem;
            border-radius: 4px;
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: var(--tr);
        }

        .add-btn:hover {
            background: var(--gold-l);
            transform: translateY(-3px);
            box-shadow: 0 10px 30px var(--gold-glow);
        }

        /* ─── TABLE STYLING ─── */
        .table-container {
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: 4px;
            overflow: hidden;
            box-shadow: 0 30px 60px rgba(0,0,0,0.5);
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
            color: var(--gold);
            border-bottom: 1px solid var(--border);
        }

        td {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-s);
            font-size: 0.9rem;
            color: var(--text-s);
        }

        tr:hover td {
            background: rgba(200,169,110, 0.03);
            color: var(--text);
        }

        /* ─── ACTION LINKS ─── */
        .edit-link {
            color: var(--gold);
            text-decoration: none;
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: var(--tr);
        }

        .delete-link {
            color: var(--red);
            text-decoration: none;
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: var(--tr);
            margin-left: 15px;
            opacity: 0.8;
        }

        .edit-link:hover, .delete-link:hover {
            letter-spacing: 2px;
            opacity: 1;
        }

        .price-tag {
            color: var(--gold-l);
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.4rem;
            font-weight: 600;
        }

        .empty-state {
            padding: 5rem;
            text-align: center;
            color: var(--text-m);
            font-style: italic;
        }
    </style>
</head>
<body>

<div class="container">
    
    <div class="header-flex">
        <div>
            <a href="dashboard.php" class="back-link">← Console Dashboard</a>
            <h2>Manage <em>Dishes</em></h2>
        </div>
        <a href="add_dish.php" class="add-btn">+ Add New Cuisine</a>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Ref ID</th>
                    <th>Dish Designation</th>
                    <th>Gastronomic Description</th>
                    <th>Investment</th>
                    <th style="text-align: center;">Controls</th>
                </tr>
            </thead>
            <tbody>
                <?php if($dishes && $dishes->num_rows > 0): ?>
                    <?php while($row = $dishes->fetch_assoc()) { ?>
                    <tr>
                        <td style="color: var(--gold-d); font-family: serif;">#<?php echo str_pad($row['id'], 3, '0', STR_PAD_LEFT); ?></td>
                        <td style="font-weight: 500; color: var(--text);"><?php echo htmlspecialchars($row['name']); ?></td>
                        <td style="color: var(--text-m); max-width: 350px; font-size: 0.85rem;"><?php echo htmlspecialchars($row['description']); ?></td>
                        <td class="price-tag">Rs. <?php echo number_format($row['price'], 2); ?></td>
                        <td style="text-align: center;">
                            <a href="edit_dish.php?id=<?php echo $row['id']; ?>" class="edit-link">Edit</a>
                            <a href="delete_dish.php?id=<?php echo $row['id']; ?>" 
                               class="delete-link" 
                               onclick="return confirm('Secure action: Confirm permanent deletion?');">Remove</a>
                        </td>
                    </tr>
                    <?php } ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="empty-state">No culinary listings currently available in the database.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
