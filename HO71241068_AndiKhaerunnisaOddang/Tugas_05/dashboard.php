<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

require_once 'data.php'; 

$currentUser = $_SESSION['user'];
$isAdmin = ($currentUser['username'] === 'adminxxx');
$welcomeMessage = $isAdmin ? 'Selamat Datang, Admin!' : 'Selamat Datang, ' . htmlspecialchars($currentUser['name']) . '!'; 

function sanitizeUserData($userArray) {
    return array_map(function($user) {
        unset($user['password']); 
        return $user;
    }, $userArray);
}

$displayData = $isAdmin ? sanitizeUserData($users) : [sanitizeUserData([$currentUser])[0]];

if (!$isAdmin) {
    $userSpecificData = [
        'Nama' => $currentUser['name'] ?? '-',
        'Username' => $currentUser['username'] ?? '-',
        'Email' => $currentUser['email'] ?? '-',
        'Gender' => $currentUser['gender'] ?? '-',
        'Fakultas' => $currentUser['faculty'] ?? '-',
        'Angkatan' => $currentUser['batch'] ?? '-',
    ];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #f0f2f5; /* Flat Light grey background */
            color: #1c1e21;
            padding: 40px;
        }
        
        .container {
            max-width: 1100px;
            margin: auto;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }

        h1 {
            font-weight: 700;
            color: #1c1e21;
            font-size: 36px;
        }
        
        .logout-link {
            font-size: 16px;
            color: #fa383e;
            text-decoration: none;
            font-weight: 500;
            padding: 8px 15px;
            border: 1px solid #fa383e;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .logout-link:hover {
            background-color: #fa383e;
            color: white;
        }
        
        h2 {
            font-weight: 600;
            font-size: 24px;
            margin-top: 30px;
            margin-bottom: 20px;
            color: #444;
        }

        .card {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); 
            border: 1px solid #e0e0e0;
            overflow: hidden;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            text-align: left;
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            font-size: 15px;
        }

        th {
            background-color: #f7f7fa; 
            color: #606770;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        tr:last-child td {
            border-bottom: none;
        }

        .user-detail-container {
            max-width: 600px;
            padding: 20px 0;
        }

        .detail-row {
            display: flex;
            padding: 12px 20px;
            border-bottom: 1px solid #eee; 
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            flex: 1;
            font-weight: 500;
            color: #606770;
            font-size: 16px;
        }

        .detail-value {
            flex: 2;
            font-weight: 400;
            color: #1c1e21;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1><?= htmlspecialchars($welcomeMessage) ?></h1>
            <a href="logout.php" class="logout-link">Logout</a>
        </header>
        
        <?php if ($isAdmin): ?>
            <h2>Data Semua Pengguna</h2>
            <div class="card">
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Gender</th>
                            <th>Fakultas</th>
                            <th>Angkatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($displayData as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['name'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($user['username'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($user['email'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($user['gender'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($user['faculty'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($user['batch'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <h2>Data Profil Anda</h2>
            <div class="card user-detail-container">
                <?php foreach ($userSpecificData as $label => $value): ?>
                    <div class="detail-row">
                        <span class="detail-label"><?= htmlspecialchars($label) ?></span>
                        <span class="detail-value"><?= htmlspecialchars($value) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>