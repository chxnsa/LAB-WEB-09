<?php

session_start();

if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit;
}

$error_message = '';
if (isset($_SESSION['login_error'])) {
    $error_message = $_SESSION['login_error'];
    unset($_SESSION['login_error']); 
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login System</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #f0f2f5; 
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            color: #1c1e21;
        }

        .login-container {
            background-color: #ffffff; 
            padding: 40px;
            border-radius: 12px; 
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid #e0e0e0;
            width: 100%;
            max-width: 380px;
            text-align: center;
        }

        h2 {
            font-weight: 700;
            color: #1c1e21;
            margin-bottom: 30px;
            font-size: 28px;
        }

        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
            color: #606770;
            font-weight: 500;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            background-color: #f9f9f9; 
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px;
            color: #333;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        input[type="text"]:focus,
        input[type="p assword"]:focus {
            background-color: #ffffff;
            border-color: #007aff; 
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.2);
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #007aff; /* Apple Blue */
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s, transform 0.1s;
            margin-top: 10px;
        }

        button:hover {
            background-color: #006ae6;
        }
        
        button:active {
            transform: scale(0.99);
        }

        .error {
            color: #fa383e;
            background-color: #ffebe9;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 25px;
            border: 1px solid #fa383e;
            font-size: 15px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Silakan Login</h2>
        <?php if ($error_message): ?>
            <p class="error"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>
        <form action="proses_login.php" method="POST">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autocomplete="username">
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required autocomplete="current-password">
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>