<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}
include 'db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// If the user clicked "Confirm"
if(isset($_POST['confirm_delete'])){
    $conn->query("DELETE FROM dishes WHERE id=$id");
    header("Location: manage_dishes.php?msg=deleted");
    exit();
}

// Fetch dish name for the UI
$dish = $conn->query("SELECT name FROM dishes WHERE id=$id")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Deletion | Premium Admin</title>
    <link href="https://googleapis.com" rel="stylesheet">
    <style>
        :root {
            --gold: #c8a96e;
            --bg: #0a0908;
            --bg2: #111009;
            --text: #f0ebe1;
            --border: rgba(200,169,110,.2);
            --red: #e05050;
        }
        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .confirm-box {
            background: var(--bg2);
            border: 1px solid var(--border);
            padding: 3rem;
            text-align: center;
            max-width: 450px;
            border-radius: 4px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
        }
        h2 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.2rem;
            color: var(--gold);
            margin-bottom: 1rem;
        }
        p { color: #b8a888; margin-bottom: 2rem; font-size: 0.9rem; }
        .dish-name { color: #fff; font-weight: bold; text-decoration: underline; }
        
        .btn-group { display: flex; gap: 1rem; justify-content: center; }
        
        .btn-delete {
            background: var(--red);
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 4px;
            text-transform: uppercase;
            font-weight: bold;
            font-size: 0.7rem;
            cursor: pointer;
        }
        .btn-cancel {
            background: transparent;
            color: var(--text);
            border: 1px solid var(--border);
            padding: 0.8rem 1.5rem;
            border-radius: 4px;
            text-decoration: none;
            text-transform: uppercase;
            font-weight: bold;
            font-size: 0.7rem;
        }
    </style>
</head>
<body>

<div class="confirm-box">
    <h2>Secure <em>Removal</em></h2>
    <p>Are you sure you want to permanently delete <br><span class="dish-name"><?= htmlspecialchars($dish['name'] ?? 'this dish') ?></span> from the menu?</p>
    
    <form method="POST">
        <div class="btn-group">
            <a href="manage_dishes.php" class="btn-cancel">Cancel</a>
            <button type="submit" name="confirm_delete" class="btn-delete">Confirm Delete</button>
        </div>
    </form>
</div>

</body>
</html>
