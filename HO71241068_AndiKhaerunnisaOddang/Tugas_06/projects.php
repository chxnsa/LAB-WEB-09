<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'koneksi.php';

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$success = "";
$error = "";

// Hapus proyek
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    
    if ($role == 'Super Admin') {
        $sql = "DELETE FROM projects WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $delete_id);
    } else {
        $sql = "DELETE FROM projects WHERE id = ? AND manager_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $delete_id, $user_id);
    }
    
    if (mysqli_stmt_execute($stmt)) {
        $success = "Proyek berhasil dihapus!";
    } else {
        $error = "Gagal menghapus proyek!";
    }
    mysqli_stmt_close($stmt);
}

// Tambah proyek
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_project']) && $role == 'Project Manager') {
    $nama_proyek = $_POST['nama_proyek'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];
    
    $sql = "INSERT INTO projects (nama_proyek, deskripsi, tanggal_mulai, tanggal_selesai, manager_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssi", $nama_proyek, $deskripsi, $tanggal_mulai, $tanggal_selesai, $user_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $success = "Proyek berhasil ditambahkan!";
    } else {
        $error = "Gagal menambahkan proyek!";
    }
    mysqli_stmt_close($stmt);
}

// Edit proyek
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_project']) && $role == 'Project Manager') {
    $project_id = $_POST['project_id'];
    $nama_proyek = $_POST['nama_proyek'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];
    
    $sql = "UPDATE projects SET nama_proyek = ?, deskripsi = ?, tanggal_mulai = ?, tanggal_selesai = ? WHERE id = ? AND manager_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssii", $nama_proyek, $deskripsi, $tanggal_mulai, $tanggal_selesai, $project_id, $user_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $success = "Proyek berhasil diupdate!";
    } else {
        $error = "Gagal mengupdate proyek!";
    }
    mysqli_stmt_close($stmt);
}

// Ambil daftar proyek berdasarkan role
if ($role == 'Super Admin') {
    $sql = "SELECT p.*, u.username as manager_name FROM projects p 
            LEFT JOIN users u ON p.manager_id = u.id 
            ORDER BY p.id DESC";
    $result = mysqli_query($conn, $sql);
} elseif ($role == 'Project Manager') {
    $sql = "SELECT p.*, u.username as manager_name FROM projects p 
            LEFT JOIN users u ON p.manager_id = u.id 
            WHERE p.manager_id = ? 
            ORDER BY p.id DESC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    $sql = "SELECT DISTINCT p.*, u.username as manager_name FROM projects p 
            LEFT JOIN users u ON p.manager_id = u.id 
            INNER JOIN tasks t ON p.id = t.project_id 
            WHERE t.assigned_to = ? 
            ORDER BY p.id DESC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Proyek - Manajemen Proyek</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f5f7fa; }
        .navbar { background: white; padding: 16px 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center; }
        .navbar-brand { font-size: 24px; font-weight: 700; color: #2c3e50; display: flex; align-items: center; gap: 12px; }
        .navbar-brand i { color: #3498db; }
        .btn-back { padding: 8px 20px; background: #3498db; color: white; text-decoration: none; border-radius: 8px; font-size: 14px; transition: all 0.3s; display: flex; align-items: center; gap: 8px; }
        .btn-back:hover { background: #2980b9; }
        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .page-title { font-size: 32px; color: #2c3e50; }
        .btn-add { padding: 12px 24px; background: #2ecc71; color: white; border: none; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.3s; display: flex; align-items: center; gap: 8px; }
        .btn-add:hover { background: #27ae60; transform: translateY(-2px); }
        .alert { padding: 14px 20px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; display: flex; align-items: center; gap: 10px; }
        .alert-success { background: #d4edda; color: #155724; border-left: 4px solid #28a745; }
        .alert-error { background: #f8d7da; color: #721c24; border-left: 4px solid #dc3545; }
        .projects-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px; }
        .project-card { background: white; border-radius: 16px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); padding: 25px; transition: all 0.3s; border-top: 4px solid #3498db; }
        .project-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
        .project-title { font-size: 20px; font-weight: 600; color: #2c3e50; margin-bottom: 10px; display: flex; align-items: center; gap: 8px; }
        .project-title i { color: #3498db; font-size: 18px; }
        .project-desc { color: #7f8c8d; font-size: 14px; margin-bottom: 15px; line-height: 1.6; }
        .project-meta { display: flex; flex-direction: column; gap: 8px; margin-bottom: 15px; font-size: 13px; color: #5a6c7d; }
        .project-meta div { display: flex; align-items: center; gap: 8px; }
        .project-meta i { color: #7f8c8d; width: 16px; }
        .project-actions { display: flex; gap: 10px; margin-top: 15px; padding-top: 15px; border-top: 1px solid #f1f3f5; }
        .btn-edit, .btn-delete, .btn-view { padding: 8px 16px; border-radius: 6px; font-size: 13px; text-decoration: none; transition: all 0.3s; cursor: pointer; display: flex; align-items: center; gap: 6px; }
        .btn-edit { background: #3498db; color: white; }
        .btn-edit:hover { background: #2980b9; }
        .btn-delete { background: #e74c3c; color: white; }
        .btn-delete:hover { background: #c0392b; }
        .btn-view { background: #95a5a6; color: white; }
        .btn-view:hover { background: #7f8c8d; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; }
        .modal.active { display: flex; justify-content: center; align-items: center; }
        .modal-content { background: white; padding: 30px; border-radius: 16px; width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto; }
        .modal-header { font-size: 24px; font-weight: 600; color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .btn-close { float: right; font-size: 24px; cursor: pointer; color: #7f8c8d; }
        .form-group { margin-bottom: 20px; }
        label { display: block; color: #2c3e50; font-weight: 500; margin-bottom: 8px; font-size: 14px; display: flex; align-items: center; gap: 6px; }
        input, textarea { width: 100%; padding: 10px 14px; border: 2px solid #e1e8ed; border-radius: 8px; font-size: 14px; transition: all 0.3s; font-family: inherit; }
        textarea { resize: vertical; min-height: 100px; }
        input:focus, textarea:focus { outline: none; border-color: #3498db; }
        .btn-submit { width: 100%; padding: 12px; background: #3498db; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.3s; display: flex; align-items: center; justify-content: center; gap: 8px; }
        .btn-submit:hover { background: #2980b9; }
        .empty-state { text-align: center; padding: 60px 20px; color: #7f8c8d; }
        .empty-state-icon { font-size: 64px; margin-bottom: 20px; color: #bdc3c7; }
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
            <h1 class="page-title">Kelola Proyek</h1>
            <?php if($role == 'Project Manager'): ?>
                <button class="btn-add" onclick="openAddModal()">
                    <i class="fas fa-plus"></i>
                    Tambah Proyek
                </button>
            <?php endif; ?>
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
        
        <div class="projects-grid">
            <?php 
            $has_projects = false;
            while($project = mysqli_fetch_assoc($result)): 
                $has_projects = true;
            ?>
            <div class="project-card">
                <div class="project-title">
                    <i class="fas fa-folder-open"></i>
                    <?php echo htmlspecialchars($project['nama_proyek']); ?>
                </div>
                <div class="project-desc"><?php echo htmlspecialchars($project['deskripsi']); ?></div>
                <div class="project-meta">
                    <div>
                        <i class="fas fa-calendar-alt"></i>
                        Mulai: <?php echo date('d/m/Y', strtotime($project['tanggal_mulai'])); ?>
                    </div>
                    <div>
                        <i class="fas fa-flag-checkered"></i>
                        Selesai: <?php echo date('d/m/Y', strtotime($project['tanggal_selesai'])); ?>
                    </div>
                    <div>
                        <i class="fas fa-user-tie"></i>
                        Manager: <?php echo htmlspecialchars($project['manager_name']); ?>
                    </div>
                </div>
                <div class="project-actions">
                    <a href="tasks.php?project_id=<?php echo $project['id']; ?>" class="btn-view">
                        <i class="fas fa-tasks"></i>
                        Lihat Tugas
                    </a>
                    <?php if($role == 'Project Manager'): ?>
                        <a href="#" onclick="openEditModal(<?php echo $project['id']; ?>, '<?php echo addslashes($project['nama_proyek']); ?>', '<?php echo addslashes($project['deskripsi']); ?>', '<?php echo $project['tanggal_mulai']; ?>', '<?php echo $project['tanggal_selesai']; ?>')" class="btn-edit">
                            <i class="fas fa-edit"></i>
                            Edit
                        </a>
                    <?php endif; ?>
                    <?php if($role == 'Super Admin' || $role == 'Project Manager'): ?>
                        <a href="?delete=<?php echo $project['id']; ?>" class="btn-delete" onclick="return confirm('Yakin ingin menghapus proyek ini?')">
                            <i class="fas fa-trash"></i>
                            Hapus
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        
        <?php if(!$has_projects): ?>
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-folder-open"></i>
            </div>
            <h3>Belum Ada Proyek</h3>
            <p>
                <?php 
                if($role == 'Project Manager') echo 'Mulai dengan menambahkan proyek baru';
                else echo 'Belum ada proyek yang tersedia';
                ?>
            </p>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Modal Tambah Proyek -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="btn-close" onclick="closeAddModal()">&times;</span>
            <h2 class="modal-header">
                <i class="fas fa-plus-circle"></i>
                Tambah Proyek Baru
            </h2>
            
            <form method="POST">
                <div class="form-group">
                    <label>
                        <i class="fas fa-folder"></i>
                        Nama Proyek
                    </label>
                    <input type="text" name="nama_proyek" required>
                </div>
                
                <div class="form-group">
                    <label>
                        <i class="fas fa-align-left"></i>
                        Deskripsi
                    </label>
                    <textarea name="deskripsi" required></textarea>
                </div>
                
                <div class="form-group">
                    <label>
                        <i class="fas fa-calendar-alt"></i>
                        Tanggal Mulai
                    </label>
                    <input type="date" name="tanggal_mulai" required>
                </div>
                
                <div class="form-group">
                    <label>
                        <i class="fas fa-flag-checkered"></i>
                        Tanggal Selesai
                    </label>
                    <input type="date" name="tanggal_selesai" required>
                </div>
                
                <button type="submit" name="add_project" class="btn-submit">
                    <i class="fas fa-plus"></i>
                    Tambah Proyek
                </button>
            </form>
        </div>
    </div>
    
    <!-- Modal Edit Proyek -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="btn-close" onclick="closeEditModal()">&times;</span>
            <h2 class="modal-header">
                <i class="fas fa-edit"></i>
                Edit Proyek
            </h2>
            
            <form method="POST">
                <input type="hidden" name="project_id" id="edit_project_id">
                
                <div class="form-group">
                    <label>
                        <i class="fas fa-folder"></i>
                        Nama Proyek
                    </label>
                    <input type="text" name="nama_proyek" id="edit_nama_proyek" required>
                </div>
                
                <div class="form-group">
                    <label>
                        <i class="fas fa-align-left"></i>
                        Deskripsi
                    </label>
                    <textarea name="deskripsi" id="edit_deskripsi" required></textarea>
                </div>
                
                <div class="form-group">
                    <label>
                        <i class="fas fa-calendar-alt"></i>
                        Tanggal Mulai
                    </label>
                    <input type="date" name="tanggal_mulai" id="edit_tanggal_mulai" required>
                </div>
                
                <div class="form-group">
                    <label>
                        <i class="fas fa-flag-checkered"></i>
                        Tanggal Selesai
                    </label>
                    <input type="date" name="tanggal_selesai" id="edit_tanggal_selesai" required>
                </div>
                
                <button type="submit" name="edit_project" class="btn-submit">
                    <i class="fas fa-save"></i>
                    Update Proyek
                </button>
            </form>
        </div>
    </div>
    
    <script>
        function openAddModal() { document.getElementById('addModal').classList.add('active'); }
        function closeAddModal() { document.getElementById('addModal').classList.remove('active'); }
        function openEditModal(id, nama, deskripsi, mulai, selesai) {
            document.getElementById('edit_project_id').value = id;
            document.getElementById('edit_nama_proyek').value = nama;
            document.getElementById('edit_deskripsi').value = deskripsi;
            document.getElementById('edit_tanggal_mulai').value = mulai;
            document.getElementById('edit_tanggal_selesai').value = selesai;
            document.getElementById('editModal').classList.add('active');
        }
        function closeEditModal() { document.getElementById('editModal').classList.remove('active'); }
        window.onclick = function(event) {
            const addModal = document.getElementById('addModal');
            const editModal = document.getElementById('editModal');
            if (event.target == addModal) closeAddModal();
            if (event.target == editModal) closeEditModal();
        }
    </script>
</body>
</html>
<?php mysqli_close($conn); ?>