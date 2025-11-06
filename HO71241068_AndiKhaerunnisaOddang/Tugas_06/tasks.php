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
$project_filter = isset($_GET['project_id']) ? $_GET['project_id'] : '';

// Hapus task
if (isset($_GET['delete']) && $role == 'Project Manager') {
    $delete_id = $_GET['delete'];
    $sql = "DELETE FROM tasks WHERE id = ? AND project_id IN (SELECT id FROM projects WHERE manager_id = ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $delete_id, $user_id);
    if (mysqli_stmt_execute($stmt)) $success = "Tugas berhasil dihapus!";
    mysqli_stmt_close($stmt);
}

// Tambah task
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_task']) && $role == 'Project Manager') {
    $sql = "INSERT INTO tasks (nama_tugas, deskripsi, project_id, assigned_to) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssii", $_POST['nama_tugas'], $_POST['deskripsi'], $_POST['project_id'], $_POST['assigned_to']);
    if (mysqli_stmt_execute($stmt)) $success = "Tugas berhasil ditambahkan!";
    mysqli_stmt_close($stmt);
}

// Edit task
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_task']) && $role == 'Project Manager') {
    $sql = "UPDATE tasks SET nama_tugas = ?, deskripsi = ?, assigned_to = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssii", $_POST['nama_tugas'], $_POST['deskripsi'], $_POST['assigned_to'], $_POST['task_id']);
    if (mysqli_stmt_execute($stmt)) $success = "Tugas berhasil diupdate!";
    mysqli_stmt_close($stmt);
}

// Update status
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status']) && $role == 'Team Member') {
    $sql = "UPDATE tasks SET status = ? WHERE id = ? AND assigned_to = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sii", $_POST['status'], $_POST['task_id'], $user_id);
    if (mysqli_stmt_execute($stmt)) $success = "Status berhasil diupdate!";
    mysqli_stmt_close($stmt);
}

// Get projects and members
if ($role == 'Project Manager') {
    $sql_projects = "SELECT id, nama_proyek FROM projects WHERE manager_id = ? ORDER BY nama_proyek";
    $stmt_projects = mysqli_prepare($conn, $sql_projects);
    mysqli_stmt_bind_param($stmt_projects, "i", $user_id);
    mysqli_stmt_execute($stmt_projects);
    $result_projects = mysqli_stmt_get_result($stmt_projects);
    mysqli_stmt_close($stmt_projects);
    
    $sql_members = "SELECT id, username FROM users WHERE role = ? ORDER BY username";
    $stmt_members = mysqli_prepare($conn, $sql_members);
    $role_member = 'Team Member';
    mysqli_stmt_bind_param($stmt_members, "s", $role_member);
    mysqli_stmt_execute($stmt_members);
    $result_members = mysqli_stmt_get_result($stmt_members);
    mysqli_stmt_close($stmt_members);
}

// Get tasks based on role
if ($role == 'Super Admin') {
    if ($project_filter) {
        $sql = "SELECT t.*, p.nama_proyek, u.username as assigned_name FROM tasks t 
                LEFT JOIN projects p ON t.project_id = p.id LEFT JOIN users u ON t.assigned_to = u.id 
                WHERE t.project_id = ? ORDER BY t.id DESC";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $project_filter);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    } else {
        $sql = "SELECT t.*, p.nama_proyek, u.username as assigned_name FROM tasks t 
                LEFT JOIN projects p ON t.project_id = p.id LEFT JOIN users u ON t.assigned_to = u.id ORDER BY t.id DESC";
        $result = mysqli_query($conn, $sql);
    }
} elseif ($role == 'Project Manager') {
    if ($project_filter) {
        $sql = "SELECT t.*, p.nama_proyek, u.username as assigned_name FROM tasks t 
                LEFT JOIN projects p ON t.project_id = p.id LEFT JOIN users u ON t.assigned_to = u.id 
                WHERE t.project_id = ? AND p.manager_id = ? ORDER BY t.id DESC";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $project_filter, $user_id);
    } else {
        $sql = "SELECT t.*, p.nama_proyek, u.username as assigned_name FROM tasks t 
                LEFT JOIN projects p ON t.project_id = p.id LEFT JOIN users u ON t.assigned_to = u.id 
                WHERE p.manager_id = ? ORDER BY t.id DESC";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    if ($project_filter) {
        $sql = "SELECT t.*, p.nama_proyek, u.username as assigned_name FROM tasks t 
                LEFT JOIN projects p ON t.project_id = p.id LEFT JOIN users u ON t.assigned_to = u.id 
                WHERE t.assigned_to = ? AND t.project_id = ? ORDER BY t.id DESC";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $user_id, $project_filter);
    } else {
        $sql = "SELECT t.*, p.nama_proyek, u.username as assigned_name FROM tasks t 
                LEFT JOIN projects p ON t.project_id = p.id LEFT JOIN users u ON t.assigned_to = u.id 
                WHERE t.assigned_to = ? ORDER BY t.id DESC";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Tugas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f5f7fa; }
        .navbar { background: white; padding: 16px 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center; }
        .navbar-brand { font-size: 24px; font-weight: 700; color: #2c3e50; display: flex; align-items: center; gap: 12px; }
        .navbar-brand i { color: #3498db; }
        .btn-back { padding: 8px 20px; background: #3498db; color: white; text-decoration: none; border-radius: 8px; font-size: 14px; transition: all 0.3s; display: flex; align-items: center; gap: 8px; }
        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .page-title { font-size: 32px; color: #2c3e50; }
        .btn-add { padding: 12px 24px; background: #2ecc71; color: white; border: none; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.3s; display: flex; align-items: center; gap: 8px; }
        .alert { padding: 14px 20px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; display: flex; align-items: center; gap: 10px; }
        .alert-success { background: #d4edda; color: #155724; border-left: 4px solid #28a745; }
        .card { background: white; border-radius: 16px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); padding: 30px; }
        .task-card { background: white; border-radius: 12px; padding: 20px; border-left: 4px solid #3498db; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-bottom: 15px; transition: all 0.3s; }
        .task-card:hover { transform: translateX(5px); box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .task-card.status-belum { border-left-color: #95a5a6; }
        .task-card.status-proses { border-left-color: #f39c12; }
        .task-card.status-selesai { border-left-color: #2ecc71; }
        .task-header { display: flex; justify-content: space-between; align-items: start; margin-bottom: 12px; }
        .task-title { font-size: 18px; font-weight: 600; color: #2c3e50; flex: 1; display: flex; align-items: center; gap: 8px; }
        .task-title i { color: #3498db; font-size: 16px; }
        .task-status { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; margin-left: 10px; }
        .status-belum { background: #ecf0f1; color: #7f8c8d; }
        .status-proses { background: #fef5e7; color: #e67e22; }
        .status-selesai { background: #d5f4e6; color: #27ae60; }
        .task-desc { color: #7f8c8d; font-size: 14px; margin-bottom: 12px; line-height: 1.6; }
        .task-meta { display: flex; gap: 20px; font-size: 13px; color: #5a6c7d; margin-bottom: 12px; }
        .task-meta div { display: flex; align-items: center; gap: 6px; }
        .task-meta i { color: #7f8c8d; }
        .task-actions { display: flex; gap: 10px; padding-top: 12px; border-top: 1px solid #f1f3f5; }
        .btn-edit, .btn-delete { padding: 6px 16px; border-radius: 6px; font-size: 13px; text-decoration: none; transition: all 0.3s; cursor: pointer; border: none; color: white; display: flex; align-items: center; gap: 6px; }
        .btn-edit { background: #3498db; }
        .btn-delete { background: #e74c3c; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; }
        .modal.active { display: flex; justify-content: center; align-items: center; }
        .modal-content { background: white; padding: 30px; border-radius: 16px; width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto; }
        .modal-header { font-size: 24px; font-weight: 600; color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .btn-close { float: right; font-size: 24px; cursor: pointer; color: #7f8c8d; }
        .form-group { margin-bottom: 20px; }
        label { display: block; color: #2c3e50; font-weight: 500; margin-bottom: 8px; font-size: 14px; display: flex; align-items: center; gap: 6px; }
        input, textarea, select { width: 100%; padding: 10px 14px; border: 2px solid #e1e8ed; border-radius: 8px; font-size: 14px; transition: all 0.3s; font-family: inherit; }
        textarea { resize: vertical; min-height: 100px; }
        input:focus, textarea:focus, select:focus { outline: none; border-color: #3498db; }
        .btn-submit { width: 100%; padding: 12px; background: #3498db; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; }
        .status-form { display: flex; gap: 10px; align-items: center; }
        .status-form select { flex: 1; }
        .status-form button { padding: 8px 16px; background: #3498db; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; display: flex; align-items: center; gap: 6px; }
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
            Dashboard
        </a>
    </nav>
    
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Kelola Tugas</h1>
            <?php if($role == 'Project Manager'): ?>
                <button class="btn-add" onclick="openAddModal()">
                    <i class="fas fa-plus"></i>
                    Tambah Tugas
                </button>
            <?php endif; ?>
        </div>
        
        <?php if($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span><?php echo $success; ?></span>
            </div>
        <?php endif; ?>
        
        <div class="card">
            <?php 
            $has_tasks = false;
            while($task = mysqli_fetch_assoc($result)): 
                $has_tasks = true;
                $status_class = 'status-' . $task['status'];
            ?>
            <div class="task-card <?php echo $status_class; ?>">
                <div class="task-header">
                    <div class="task-title">
                        <i class="fas fa-clipboard-check"></i>
                        <?php echo htmlspecialchars($task['nama_tugas']); ?>
                    </div>
                    <span class="task-status <?php echo $status_class; ?>"><?php echo ucfirst($task['status']); ?></span>
                </div>
                <div class="task-desc"><?php echo htmlspecialchars($task['deskripsi']); ?></div>
                <div class="task-meta">
                    <div>
                        <i class="fas fa-folder"></i>
                        <?php echo htmlspecialchars($task['nama_proyek']); ?>
                    </div>
                    <div>
                        <i class="fas fa-user"></i>
                        <?php echo htmlspecialchars($task['assigned_name']); ?>
                    </div>
                </div>
                
                <?php if($role == 'Team Member'): ?>
                <form method="POST" class="status-form">
                    <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                    <select name="status">
                        <option value="belum" <?php echo $task['status']=='belum'?'selected':''; ?>>Belum</option>
                        <option value="proses" <?php echo $task['status']=='proses'?'selected':''; ?>>Proses</option>
                        <option value="selesai" <?php echo $task['status']=='selesai'?'selected':''; ?>>Selesai</option>
                    </select>
                    <button type="submit" name="update_status">
                        <i class="fas fa-sync-alt"></i>
                        Update
                    </button>
                </form>
                <?php endif; ?>
                
                <?php if($role == 'Project Manager'): ?>
                <div class="task-actions">
                    <button onclick='editTask(<?php echo json_encode($task); ?>)' class="btn-edit">
                        <i class="fas fa-edit"></i>
                        Edit
                    </button>
                    <a href="?delete=<?php echo $task['id']; ?>" class="btn-delete" onclick="return confirm('Hapus tugas ini?')">
                        <i class="fas fa-trash"></i>
                        Hapus
                    </a>
                </div>
                <?php endif; ?>
            </div>
            <?php endwhile; ?>
            
            <?php if(!$has_tasks): ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <h3>Belum Ada Tugas</h3>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <?php if($role == 'Project Manager'): ?>
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="btn-close" onclick="closeAddModal()">&times;</span>
            <h2 class="modal-header">
                <i class="fas fa-plus-circle"></i>
                Tambah Tugas
            </h2>
            <form method="POST">
                <div class="form-group">
                    <label>
                        <i class="fas fa-clipboard"></i>
                        Nama Tugas
                    </label>
                    <input type="text" name="nama_tugas" required>
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
                        <i class="fas fa-folder"></i>
                        Proyek
                    </label>
                    <select name="project_id" required>
                        <option value="">Pilih Proyek</option>
                        <?php mysqli_data_seek($result_projects, 0); while($p = mysqli_fetch_assoc($result_projects)): ?>
                            <option value="<?php echo $p['id']; ?>"><?php echo htmlspecialchars($p['nama_proyek']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>
                        <i class="fas fa-user-check"></i>
                        Assign ke Team Member
                    </label>
                    <select name="assigned_to" required>
                        <option value="">Pilih Member</option>
                        <?php mysqli_data_seek($result_members, 0); while($m = mysqli_fetch_assoc($result_members)): ?>
                            <option value="<?php echo $m['id']; ?>"><?php echo htmlspecialchars($m['username']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit" name="add_task" class="btn-submit">
                    <i class="fas fa-plus"></i>
                    Tambah Tugas
                </button>
            </form>
        </div>
    </div>
    
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="btn-close" onclick="closeEditModal()">&times;</span>
            <h2 class="modal-header">
                <i class="fas fa-edit"></i>
                Edit Tugas
            </h2>
            <form method="POST">
                <input type="hidden" name="task_id" id="edit_task_id">
                <div class="form-group">
                    <label>
                        <i class="fas fa-clipboard"></i>
                        Nama Tugas
                    </label>
                    <input type="text" name="nama_tugas" id="edit_nama_tugas" required>
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
                        <i class="fas fa-user-check"></i>
                        Assign ke Team Member
                    </label>
                    <select name="assigned_to" id="edit_assigned_to" required>
                        <?php mysqli_data_seek($result_members, 0); while($m = mysqli_fetch_assoc($result_members)): ?>
                            <option value="<?php echo $m['id']; ?>"><?php echo htmlspecialchars($m['username']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit" name="edit_task" class="btn-submit">
                    <i class="fas fa-save"></i>
                    Update Tugas
                </button>
            </form>
        </div>
    </div>
    
    <script>
        function openAddModal() { document.getElementById('addModal').classList.add('active'); }
        function closeAddModal() { document.getElementById('addModal').classList.remove('active'); }
        function closeEditModal() { document.getElementById('editModal').classList.remove('active'); }
        function editTask(task) {
            document.getElementById('edit_task_id').value = task.id;
            document.getElementById('edit_nama_tugas').value = task.nama_tugas;
            document.getElementById('edit_deskripsi').value = task.deskripsi;
            document.getElementById('edit_assigned_to').value = task.assigned_to;
            document.getElementById('editModal').classList.add('active');
        }
        window.onclick = function(e) {
            if (e.target.classList.contains('modal')) e.target.classList.remove('active');
        }
    </script>
    <?php endif; ?>
</body>
</html>
<?php mysqli_close($conn); ?>