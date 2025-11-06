<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Super Admin') {
    header("Location: dashboard.php");
    exit();
}

require 'koneksi.php';

$success = "";
$error = "";

// Hapus user
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $sql = "DELETE FROM users WHERE id = ? AND role != 'Super Admin'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $delete_id);
    if (mysqli_stmt_execute($stmt)) {
        $success = "Pengguna berhasil dihapus!";
    } else {
        $error = "Gagal menghapus pengguna!";
    }
    mysqli_stmt_close($stmt);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role'];
    $pm_id = ($role == 'Team Member' && !empty($_POST['project_manager_id'])) ? $_POST['project_manager_id'] : NULL;
    
    $check_sql = "SELECT id FROM users WHERE username = ?";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "s", $username);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);
    
    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        $error = "Username '$username' sudah digunakan! Silakan gunakan username lain.";
        $show_modal = true; 
        $form_data = [
            'username' => $username,
            'role' => $role,
            'project_manager_id' => $pm_id
        ];
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, password, role, project_manager_id) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssi", $username, $password_hash, $role, $pm_id);
        
        if (mysqli_stmt_execute($stmt)) {
            $success = "Pengguna '$username' berhasil ditambahkan!";
        } else {
            $error = "Gagal menambahkan pengguna! Silakan coba lagi.";
            $show_modal = true;
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_stmt_close($check_stmt);
}

$sql = "SELECT u.*, pm.username as pm_name FROM users u 
        LEFT JOIN users pm ON u.project_manager_id = pm.id 
        ORDER BY u.role, u.username";
$result = mysqli_query($conn, $sql);

// Ambil daftar Project Manager untuk dropdown
$sql_pm = "SELECT id, username FROM users WHERE role = 'Project Manager' ORDER BY username";
$result_pm = mysqli_query($conn, $sql_pm);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna - Manajemen Proyek</title>
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
        
        .btn-back {
            padding: 8px 20px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-back:hover {
            background: #2980b9;
        }
        
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .page-title {
            font-size: 32px;
            color: #2c3e50;
        }
        
        .btn-add {
            padding: 12px 24px;
            background: #2ecc71;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-add:hover {
            background: #27ae60;
            transform: translateY(-2px);
        }
        
        .alert {
            padding: 14px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 30px;
            margin-bottom: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th {
            background: #f8f9fa;
            padding: 14px;
            text-align: left;
            font-weight: 600;
            color: #2c3e50;
            font-size: 14px;
            border-bottom: 2px solid #e1e8ed;
        }
        
        td {
            padding: 14px;
            border-bottom: 1px solid #f1f3f5;
            font-size: 14px;
            color: #5a6c7d;
        }
        
        tr:hover {
            background: #f8f9fa;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .badge i {
            margin-right: 4px;
        }
        
        .badge-admin {
            background: #e8f4fd;
            color: #0066cc;
        }
        
        .badge-manager {
            background: #e7f3ff;
            color: #0052a3;
        }
        
        .badge-member {
            background: #e8f8f5;
            color: #00875a;
        }
        
        .btn-delete {
            padding: 6px 16px;
            background: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 13px;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .btn-delete:hover {
            background: #c0392b;
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
        }
        
        .modal.active {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 16px;
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .modal-header {
            font-size: 24px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-close {
            float: right;
            font-size: 24px;
            cursor: pointer;
            color: #7f8c8d;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            color: #2c3e50;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        input, select {
            width: 100%;
            padding: 10px 14px;
            border: 2px solid #e1e8ed;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        input:focus, select:focus {
            outline: none;
            border-color: #3498db;
        }
        
        .btn-submit {
            width: 100%;
            padding: 12px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-submit:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-brand">
            <i class="fas fa-rocket"></i>
            Manajemen Proyek
        </div>
        <a href="dashboard.php" class="btn-back">
            <i class="fas fa-arrow-left"></i>
            Kembali ke Dashboard
        </a>
    </nav>
    
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Kelola Pengguna</h1>
            <button class="btn-add" onclick="openModal()">
                <i class="fas fa-user-plus"></i>
                Tambah Pengguna
            </button>
        </div>
        
        <?php if($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span><?php echo $success; ?></span>
            </div>
        <?php endif; ?>
        
        <?php if($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo $error; ?></span>
            </div>
        <?php endif; ?>
        
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Project Manager</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    while($user = mysqli_fetch_assoc($result)): 
                    ?>
                    <tr>
                        <td><strong><?php echo $no++; ?></strong></td>
                        <td>
                            <i class="fas fa-user"></i>
                            <?php echo htmlspecialchars($user['username']); ?>
                        </td>
                        <td>
                            <?php 
                            $badge_class = 'badge-member';
                            $icon = 'fa-user';
                            if($user['role'] == 'Super Admin') {
                                $badge_class = 'badge-admin';
                                $icon = 'fa-user-shield';
                            } elseif($user['role'] == 'Project Manager') {
                                $badge_class = 'badge-manager';
                                $icon = 'fa-user-tie';
                            }
                            ?>
                            <span class="badge <?php echo $badge_class; ?>">
                                <i class="fas <?php echo $icon; ?>"></i>
                                <?php echo $user['role']; ?>
                            </span>
                        </td>
                        <td>
                            <?php echo $user['pm_name'] ? htmlspecialchars($user['pm_name']) : '-'; ?>
                        </td>
                        <td>
                            <?php if($user['role'] != 'Super Admin'): ?>
                                <a href="?delete=<?php echo $user['id']; ?>" 
                                class="btn-delete" 
                                onclick="return confirm('Yakin ingin menghapus pengguna ini?')">
                                    <i class="fas fa-trash"></i>
                                    Hapus
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Modal Tambah User -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="btn-close" onclick="closeModal()">&times;</span>
            <h2 class="modal-header">
                <i class="fas fa-user-plus"></i>
                Tambah Pengguna Baru
            </h2>
            
            <form method="POST">
                <div class="form-group">
                    <label>
                        <i class="fas fa-user"></i>
                        Username
                    </label>
                    <input type="text" name="username" required>
                </div>
                
                <div class="form-group">
                    <label>
                        <i class="fas fa-lock"></i>
                        Password
                    </label>
                    <input type="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label>
                        <i class="fas fa-user-tag"></i>
                        Role
                    </label>
                    <select name="role" id="roleSelect" onchange="togglePMField()" required>
                        <option value="">Pilih Role</option>
                        <option value="Project Manager">Project Manager</option>
                        <option value="Team Member">Team Member</option>
                    </select>
                </div>
                
                <div class="form-group" id="pmField" style="display:none;">
                    <label>
                        <i class="fas fa-user-tie"></i>
                        Project Manager
                    </label>
                    <select name="project_manager_id">
                        <option value="">Pilih Project Manager</option>
                        <?php 
                        mysqli_data_seek($result_pm, 0);
                        while($pm = mysqli_fetch_assoc($result_pm)): 
                        ?>
                            <option value="<?php echo $pm['id']; ?>">
                                <?php echo htmlspecialchars($pm['username']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <button type="submit" name="add_user" class="btn-submit">
                    <i class="fas fa-plus"></i>
                    Tambah Pengguna
                </button>
            </form>
        </div>
    </div>
    
    <script>
        function openModal() {
            document.getElementById('addModal').classList.add('active');
        }
        
        function closeModal() {
            document.getElementById('addModal').classList.remove('active');
        }
        
        function togglePMField() {
            const role = document.getElementById('roleSelect').value;
            const pmField = document.getElementById('pmField');
            if(role === 'Team Member') {
                pmField.style.display = 'block';
            } else {
                pmField.style.display = 'none';
            }
        }
        
        window.onclick = function(event) {
            const modal = document.getElementById('addModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>
<?php mysqli_close($conn); ?>