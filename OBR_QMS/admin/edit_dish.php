<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

include 'db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$result = $conn->query("SELECT * FROM dishes WHERE id=$id");
$dish = $result->fetch_assoc();

if(!$dish) { header("Location: manage_dishes.php"); exit(); }

if(isset($_POST['update'])){
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("UPDATE dishes SET name=?, description=?, price=? WHERE id=?");
    $stmt->bind_param("ssdi", $name, $description, $price, $id);
    $stmt->execute();

    header("Location: manage_dishes.php?status=updated");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Cuisine | Premium Admin</title>
    <!-- Premium Fonts -->
    <link href="https://googleapis.com" rel="stylesheet">
    
    <style>
        /* ─── PREMIUM THEME VARIABLES ─── */
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
            --r:         4px;
            --tr:        .3s ease;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .edit-container {
            background: var(--bg2);
            border: 1px solid var(--border);
            padding: 4rem;
            border-radius: var(--r);
            width: 100%;
            max-width: 500px;
            box-shadow: 0 40px 100px rgba(0,0,0,0.8);
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 3rem;
            margin-bottom: 0.5rem;
            text-align: center;
            color: var(--gold);
        }

        .subtitle {
            text-align: center;
            color: var(--text-m);
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.25em;
            margin-bottom: 3rem;
            display: block;
        }

        .form-group {
            margin-bottom: 1.8rem;
        }

        label {
            display: block;
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: var(--gold);
            margin-bottom: 10px;
        }

        input, textarea {
            width: 100%;
            background: var(--bg3);
            border: 1px solid var(--border);
            padding: 1.2rem;
            border-radius: var(--r);
            color: white;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.95rem;
            box-sizing: border-box;
            transition: var(--tr);
            outline: none;
        }

        input:focus, textarea:focus {
            border-color: var(--gold);
            box-shadow: 0 0 15px var(--gold-glow);
        }

        button {
            width: 100%;
            background: var(--gold);
            color: var(--bg);
            padding: 1.1rem;
            border: none;
            border-radius: var(--r);
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            cursor: pointer;
            margin-top: 1rem;
            transition: var(--tr);
        }

        button:hover {
            background: var(--gold-l);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px var(--gold-glow);
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

        .back-link:hover { color: var(--gold); }

    </style>
</head>
<body>

<div class="edit-container">
    <h2>Edit <em>Cuisine</em></h2>
    <span class="subtitle">Refine your culinary details</span>

    <form method="POST">
        <div class="form-group">
            <label>Dish Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($dish['name']); ?>" required>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="3" required><?php echo htmlspecialchars($dish['description']); ?></textarea>
        </div>

        <div class="form-group">
            <label>Price (PKR)</label>
            <input type="number" step="0.01" name="price" value="<?php echo $dish['price']; ?>" required>
        </div>

        <button type="submit" name="update">Apply Updates</button>
    </form>

    <a href="manage_dishes.php" class="back-link">← Cancel and Go Back</a>
</div>

</body>
</html>
