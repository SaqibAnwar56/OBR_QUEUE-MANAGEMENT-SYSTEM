<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

include 'db.php';

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("INSERT INTO dishes (name, description, price) VALUES (?, ?, ?)");
    $stmt->bind_param("ssd", $name, $description, $price);
    $stmt->execute();

    header("Location: manage_dishes.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Dish | Premium Admin</title>
    <!-- Importing your theme fonts -->
    <link href="https://googleapis.com" rel="stylesheet">
    
    <style>
        /* ─── YOUR PREMIUM VARIABLES ─── */
        :root {
            --gold:      #c8a96e;
            --gold-l:    #e2c88a;
            --gold-d:    #8a6930;
            --gold-dim:  rgba(200,169,110,.12);
            --gold-glow: rgba(200,169,110,.28);
            --bg:        #0a0908;
            --bg2:       #111009;
            --bg3:       #181510;
            --text:      #f0ebe1;
            --text-s:    #b8a888;
            --text-m:    #7a6e5a;
            --border:    rgba(200,169,110,.2);
            --border-s:  rgba(255,255,255,.07);
            --r:         10px;
            --tr:        .25s ease;
        }

        body {
            margin: 0;
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        /* ─── FORM CONTAINER ─── */
        .form-container {
            background: var(--bg2);
            border: 1px solid var(--border);
            padding: 4rem;
            border-radius: var(--r);
            width: 100%;
            max-width: 550px;
            box-shadow: 0 30px 70px rgba(0,0,0,0.8);
        }

        h2 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.8rem;
            color: var(--gold);
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .subtitle {
            display: block;
            text-align: center;
            color: var(--text-m);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            margin-bottom: 3rem;
        }

        /* ─── FORM ELEMENTS ─── */
        .form-group {
            margin-bottom: 1.8rem;
        }

        label {
            display: block;
            font-size: 0.68rem;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 10px;
        }

        input[type="text"], 
        input[type="number"], 
        textarea {
            width: 100%;
            background: var(--bg3);
            border: 1px solid var(--border);
            padding: 1.1rem;
            border-radius: 4px;
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            transition: var(--tr);
            outline: none;
        }

        textarea {
            height: 120px;
            resize: none;
        }

        input:focus, textarea:focus {
            border-color: var(--gold);
            background: var(--bg4);
            box-shadow: 0 0 15px var(--gold-dim);
        }

        /* ─── BUTTONS (Using your classes) ─── */
        .btn-gold {
            display: inline-flex; align-items: center; justify-content: center; gap: .5rem;
            background: var(--gold); color: var(--bg);
            padding: 1rem 2rem; border-radius: 4px; width: 100%;
            font-size: .8rem; font-weight: 600; letter-spacing: .15em; text-transform: uppercase;
            border: none; transition: var(--tr); cursor: pointer;
            margin-top: 1rem;
        }
        .btn-gold:hover { 
            background: var(--gold-l); 
            transform: translateY(-2px); 
            box-shadow: 0 10px 30px var(--gold-glow); 
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 2rem;
            color: var(--text-m);
            text-decoration: none;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            transition: var(--tr);
        }

        .back-link:hover {
            color: var(--gold);
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Add New <em>Dish</em></h2>
    <span class="subtitle">Culinary Excellence Starts Here</span>

    <form method="POST">
        <div class="form-group">
            <label>Dish Name</label>
            <input type="text" name="name" placeholder="e.g. Truffle Risotto" required autofocus>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" placeholder="Describe the ingredients and preparation..." required></textarea>
        </div>

        <div class="form-group">
            <label>Price (PKR)</label>
            <input type="number" step="0.01" name="price" placeholder="0.00" required>
        </div>

        <button type="submit" name="submit" class="btn-gold">Create Dish Listing</button>
    </form>

    <a href="manage_dishes.php" class="back-link">← Return to Management</a>
</div>

</body>
</html>
