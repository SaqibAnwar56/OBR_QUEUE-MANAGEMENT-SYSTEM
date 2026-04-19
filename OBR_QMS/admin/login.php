<?php
session_start();

// 1. DATABASE CONNECTION
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "obr_qms";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. LOGOUT LOGIC
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// 3. LOGIN LOGIC
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $stmt = $conn->prepare("SELECT id, password FROM admins WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['admin_id'] = $row['id'];
                $_SESSION['admin_username'] = $username;
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Access Denied: Invalid Password.";
            }
        } else {
            $error = "System Error: Admin Not Found.";
        }
        $stmt->close();
    } else {
        $error = "Please fill in all security fields.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Access | Premium Admin</title>
    <!-- Premium Fonts -->
    <link href="https://googleapis.com" rel="stylesheet">
    
    <style>
        :root {
            --gold:      #c8a96e;
            --gold-l:    #e2c88a;
            --gold-glow: rgba(200,169,110,.25);
            --bg:        #0a0908;
            --bg2:       #111009;
            --bg3:       #181510;
            --text:      #f0ebe1;
            --text-m:    #7a6e5a;
            --border:    rgba(200,169,110,.15);
            --red:       #e05050;
            --tr:        .3s ease;
        }

        body {
            margin: 0;
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text);
            overflow: hidden;
        }

        /* Ambient subtle gold glow */
        body::before {
            content: "";
            position: absolute;
            width: 600px; height: 600px;
            background: radial-gradient(circle, var(--gold-glow) 0%, transparent 70%);
            z-index: -1;
            opacity: 0.4;
        }

        .login-container {
            background: var(--bg2);
            border: 1px solid var(--border);
            padding: 4rem 3.5rem;
            border-radius: 4px; /* Premium Sharp corners */
            width: 100%;
            max-width: 420px;
            box-shadow: 0 40px 100px rgba(0, 0, 0, 0.8);
            text-align: center;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 { 
            font-family: 'Cormorant Garamond', serif;
            font-size: 3rem; 
            margin-bottom: 0.5rem; 
            color: var(--gold);
            letter-spacing: 1px;
        }

        .subtitle { 
            color: var(--text-m); 
            font-size: 0.7rem; 
            text-transform: uppercase; 
            letter-spacing: 0.3em; 
            margin-bottom: 3rem; 
            display: block;
        }

        .error-box {
            background: rgba(224, 80, 80, 0.08);
            border: 1px solid var(--red);
            color: #ffaaaa;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 2rem;
            font-size: 0.8rem;
        }

        .input-group { margin-bottom: 1.8rem; text-align: left; }
        
        label { 
            font-size: 0.65rem; 
            text-transform: uppercase; 
            color: var(--gold); 
            letter-spacing: 0.2em;
            margin-bottom: 10px;
            display: block;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            background: var(--bg3);
            border: 1px solid var(--border);
            padding: 1.1rem;
            border-radius: 2px;
            color: white;
            box-sizing: border-box;
            transition: var(--tr);
            font-size: 0.95rem;
            outline: none;
        }

        input:focus {
            border-color: var(--gold);
            box-shadow: 0 0 15px var(--gold-glow);
        }

        .btn-login {
            width: 100%;
            background: var(--gold);
            color: var(--bg);
            padding: 1.1rem;
            border: none;
            border-radius: 2px;
            font-weight: 700;
            font-size: 0.8rem;
            cursor: pointer;
            transition: var(--tr);
            margin-top: 1rem;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .btn-login:hover {
            background: var(--gold-l);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px var(--gold-glow);
        }

        .navbar {
            position: absolute;
            top: 0; width: 100%;
            padding: 2.5rem 4rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo { 
            font-family: 'Cormorant Garamond', serif; 
            letter-spacing: 4px; 
            font-size: 1.4rem; 
            color: var(--gold); 
        }

        .copyright { 
            margin-top: 3rem; 
            font-size: 0.6rem; 
            color: var(--text-m); 
            letter-spacing: 2px; 
            text-transform: uppercase;
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="logo">OBR <em>VIP</em></div>
    </nav>

    <div class="login-container">
        <h2>Admin <em>Login</em></h2>
        <span class="subtitle">Secure Command Access</span>

        <?php if(isset($error)): ?>
            <div class="error-box"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="input-group">
                <label>Access Identity</label>
                <input type="text" name="username" placeholder="Username" required autofocus>
            </div>

            <div class="input-group">
                <label>Security Key</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn-login">Initialize Session</button>
        </form>
        
        <div class="copyright">© <?php echo date('Y'); ?> OBR Management Systems</div>
    </div>

</body>
</html>
