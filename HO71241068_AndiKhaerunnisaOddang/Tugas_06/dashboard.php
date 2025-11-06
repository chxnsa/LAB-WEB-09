<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'koneksi.php';

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Statistik untuk dashboard
$total_projects = 0;
$total_tasks = 0;
$completed_tasks = 0;
$total_users = 0;

if ($role == 'Super Admin') {
    $sql = "SELECT COUNT(*) as total FROM projects";
    $result = mysqli_query($conn, $sql);
    $total_projects = mysqli_fetch_assoc($result)['total'];
    
    $sql = "SELECT COUNT(*) as total FROM tasks";
    $result = mysqli_query($conn, $sql);
    $total_tasks = mysqli_fetch_assoc($result)['total'];
    
    $sql = "SELECT COUNT(*) as total FROM tasks WHERE status = 'selesai'";
    $result = mysqli_query($conn, $sql);
    $completed_tasks = mysqli_fetch_assoc($result)['total'];
    
    $sql = "SELECT COUNT(*) as total FROM users";
    $result = mysqli_query($conn, $sql);
    $total_users = mysqli_fetch_assoc($result)['total'];
    
} elseif ($role == 'Project Manager') {
    $sql = "SELECT COUNT(*) as total FROM projects WHERE manager_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $total_projects = mysqli_fetch_assoc($result)['total'];
    
    $sql = "SELECT COUNT(*) as total FROM tasks WHERE project_id IN (SELECT id FROM projects WHERE manager_id = ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $total_tasks = mysqli_fetch_assoc($result)['total'];
    
    $sql = "SELECT COUNT(*) as total FROM tasks WHERE status = 'selesai' AND project_id IN (SELECT id FROM projects WHERE manager_id = ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $completed_tasks = mysqli_fetch_assoc($result)['total'];
    
} elseif ($role == 'Team Member') {
    $sql = "SELECT COUNT(DISTINCT project_id) as total FROM tasks WHERE assigned_to = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $total_projects = mysqli_fetch_assoc($result)['total'];
    
    $sql = "SELECT COUNT(*) as total FROM tasks WHERE assigned_to = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $total_tasks = mysqli_fetch_assoc($result)['total'];
    
    $sql = "SELECT COUNT(*) as total FROM tasks WHERE status = 'selesai' AND assigned_to = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $completed_tasks = mysqli_fetch_assoc($result)['total'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Manajemen Proyek</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
        }
        
        .navbar {
            background: white;
            padding: 16px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .navbar-brand {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .navbar-brand i {
            color: #3498db;
        }
        
        .navbar-user {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .user-info {
            text-align: right;
        }
        
        .user-name {
            font-weight: 600;
            color: #2c3e50;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            justify-content: flex-end;
        }
        
        .user-role {
            font-size: 12px;
            color: #7f8c8d;
        }
        
        .btn-logout {
            padding: 8px 20px;
            background: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-logout:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }
        
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .page-title {
            font-size: 32px;
            color: #2c3e50;
            margin-bottom: 30px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .stat-icon {
            font-size: 36px;
            margin-bottom: 10px;
            color: #3498db;
        }
        
        .stat-title {
            font-size: 14px;
            color: #7f8c8d;
            margin-bottom: 8px;
        }
        
        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #2c3e50;
        }
        
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }
        
        .menu-card {
            background: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            text-decoration: none;
            color: inherit;
            transition: all 0.3s;
            border-left: 4px solid transparent;
        }
        
        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        
        .menu-card.blue {
            border-left-color: #3498db;
        }
        
        .menu-card.green {
            border-left-color: #2ecc71;
        }
        
        .menu-card.orange {
            border-left-color: #e67e22;
        }
        
        .menu-card.purple {
            border-left-color: #9b59b6;
        }
        
        .menu-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
        
        .menu-card.blue .menu-icon {
            color: #3498db;
        }
        
        .menu-card.green .menu-icon {
            color: #2ecc71;
        }
        
        .menu-card.orange .menu-icon {
            color: #e67e22;
        }
        
        .menu-card.purple .menu-icon {
            color: #9b59b6;
        }
        
        .menu-title {
            font-size: 20px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
        }
        
        .menu-desc {
            font-size: 14px;
            color: #7f8c8d;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-brand">
            <i class="fas fa-rocket"></i>
            Manajemen Proyek
        </div>
        <div class="navbar-user">
            <div class="user-info">
                <div class="user-name">
                    <i class="fas fa-user-circle"></i>
                    <?php echo htmlspecialchars($username); ?>
                </div>
                <div class="user-role"><?php echo htmlspecialchars($role); ?></div>
            </div>
            <a href="logout.php" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </a>
        </div>
    </nav>
    
    <div class="container">
        <h1 class="page-title">Dashboard</h1>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-folder-open"></i>
                </div>
                <div class="stat-title">Total Proyek</div>
                <div class="stat-value"><?php echo $total_projects; ?></div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="stat-title">Total Tugas</div>
                <div class="stat-value"><?php echo $total_tasks; ?></div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-title">Tugas Selesai</div>
                <div class="stat-value"><?php echo $completed_tasks; ?></div>
            </div>
            
            <?php if($role == 'Super Admin'): ?>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-title">Total Pengguna</div>
                <div class="stat-value"><?php echo $total_users; ?></div>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="menu-grid">
            <?php if($role == 'Super Admin'): ?>
                <a href="users.php" class="menu-card purple">
                    <div class="menu-icon">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <div class="menu-title">Kelola Pengguna</div>
                    <div class="menu-desc">Tambah, edit, dan hapus Project Manager serta Team Member</div>
                </a>
            <?php endif; ?>
            
            <a href="projects.php" class="menu-card blue">
                <div class="menu-icon">
                    <i class="fas fa-project-diagram"></i>
                </div>
                <div class="menu-title">Kelola Proyek</div>
                <div class="menu-desc">
                    <?php 
                    if($role == 'Super Admin') echo 'Lihat dan kelola semua proyek';
                    elseif($role == 'Project Manager') echo 'Buat dan kelola proyek Anda';
                    else echo 'Lihat proyek yang Anda kerjakan';
                    ?>
                </div>
            </a>
            
            <a href="tasks.php" class="menu-card green">
                <div class="menu-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="menu-title">Kelola Tugas</div>
                <div class="menu-desc">
                    <?php 
                    if($role == 'Team Member') echo 'Lihat dan update status tugas Anda';
                    else echo 'Kelola tugas dalam proyek';
                    ?>
                </div>
            </a>
        </div>
    </div>
</body>
</html>
<?php mysqli_close($conn); ?>