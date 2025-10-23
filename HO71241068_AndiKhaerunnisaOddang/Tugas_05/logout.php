<?php
session_start();
require_once 'data.php';

// Perlindungan halaman - cek apakah user sudah login
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$currentUser = $_SESSION['user'];
$isAdmin = ($currentUser['username'] === 'adminxxx');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Login Sederhana</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f6fa;
            min-height: 100vh;
            padding: 20px;
        }
        
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .navbar h1 {
            font-size: 24px;
        }
        
        .btn-logout {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 10px 20px;
            border: 2px solid white;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-logout:hover {
            background: white;
            color: #667eea;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .welcome-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .welcome-card h2 {
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
        }
        
        .welcome-card p {
            color: #666;
            font-size: 16px;
        }
        
        .data-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .data-card h3 {
            color: #333;
            margin-bottom: 20px;
            font-size: 22px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        
        .user-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }
        
        .info-item {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
            border-left: 4px solid #667eea;
        }
        
        .info-item label {
            display: block;
            font-weight: 600;
            color: #555;
            margin-bottom: 5px;
            font-size: 14px;
        }
        
        .info-item span {
            color: #333;
            font-size: 16px;
        }
        
        .table-container {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        table th,
        table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        table th {
            font-weight: 600;
            font-size: 14px;
        }
        
        table tbody tr:hover {
            background: #f8f9fa;
        }
        
        table td {
            color: #333;
            font-size: 14px;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .badge-admin {
            background: #e74c3c;
            color: white;
        }
        
        .badge-user {
            background: #3498db;
            color: white;
        }
        
        .badge-male {
            background: #3498db;
            color: white;
        }
        
        .badge-female {
            background: #e91e63;
            color: white;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>📊 Dashboard</h1>
        <a href="logout.php" class="btn-logout">Logout</a>
    </div>
    
    <div class="container">
        <div class="welcome-card">
            <?php if ($isAdmin): ?>
                <h2>👋 Selamat Datang, Admin!</h2>
                <p>Anda memiliki akses penuh untuk melihat semua data pengguna.</p>
            <?php else: ?>
                <h2>👋 Selamat Datang, <?php echo htmlspecialchars($currentUser['name']); ?>!</h2>
                <p>Berikut adalah informasi akun Anda.</p>
            <?php endif; ?>
        </div>
        
        <div class="data-card">
            <?php if ($isAdmin): ?>
                <!-- Tampilan untuk Admin - Menampilkan semua user -->
                <h3>📋 Data Semua Pengguna</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Username</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Gender</th>
                                <th>Fakultas</th>
                                <th>Angkatan</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            foreach ($users as $user): 
                                $role = ($user['username'] === 'adminxxx') ? 'Admin' : 'User';
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td>
                                        <?php if (isset($user['gender'])): ?>
                                            <span class="badge badge-<?php echo strtolower($user['gender']); ?>">
                                                <?php echo htmlspecialchars($user['gender']); ?>
                                            </span>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo isset($user['faculty']) ? htmlspecialchars($user['faculty']) : '-'; ?></td>
                                    <td><?php echo isset($user['batch']) ? htmlspecialchars($user['batch']) : '-'; ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo strtolower($role); ?>">
                                            <?php echo $role; ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <!-- Tampilan untuk User Biasa - Menampilkan data sendiri -->
                <h3>👤 Informasi Profil Anda</h3>
                <div class="user-info">
                    <div class="info-item">
                        <label>Username</label>
                        <span><?php echo htmlspecialchars($currentUser['username']); ?></span>
                    </div>
                    <div class="info-item">
                        <label>Nama Lengkap</label>
                        <span><?php echo htmlspecialchars($currentUser['name']); ?></span>
                    </div>
                    <div class="info-item">
                        <label>Email</label>
                        <span><?php echo htmlspecialchars($currentUser['email']); ?></span>
                    </div>
                    <?php if (isset($currentUser['gender'])): ?>
                    <div class="info-item">
                        <label>Jenis Kelamin</label>
                        <span><?php echo htmlspecialchars($currentUser['gender']); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if (isset($currentUser['faculty'])): ?>
                    <div class="info-item">
                        <label>Fakultas</label>
                        <span><?php echo htmlspecialchars($currentUser['faculty']); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if (isset($currentUser['batch'])): ?>
                    <div class="info-item">
                        <label>Angkatan</label>
                        <span><?php echo htmlspecialchars($currentUser['batch']); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>