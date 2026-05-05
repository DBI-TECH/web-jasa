<?php
require_once '../config/database.php';
require_once '../includes/auth.php';

// Cek apakah sudah login
if(!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$isAdmin = ($_SESSION['role'] == 'admin');
$message = '';
$messageType = '';

// Handle CRUD operations
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['action'])) {
        try {
            switch($_POST['action']) {
                case 'create':
                    if(!$isAdmin) {
                        throw new Exception("Anda tidak memiliki izin untuk menambah data!");
                    }
                    
                    $title = trim($_POST['title']);
                    $description = trim($_POST['description']);
                    $icon = trim($_POST['icon']);
                    
                    if(empty($title) || empty($description) || empty($icon)) {
                        throw new Exception("Semua field harus diisi!");
                    }
                    
                    $sql = "INSERT INTO services (title, description, icon) VALUES (?, ?, ?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$title, $description, $icon]);
                    $message = "Service berhasil ditambahkan!";
                    $messageType = "success";
                    break;
                    
                case 'update':
                    if(!$isAdmin) {
                        throw new Exception("Anda tidak memiliki izin untuk mengupdate data!");
                    }
                    
                    $id = $_POST['id'];
                    $title = trim($_POST['title']);
                    $description = trim($_POST['description']);
                    $icon = trim($_POST['icon']);
                    
                    if(empty($title) || empty($description) || empty($icon)) {
                        throw new Exception("Semua field harus diisi!");
                    }
                    
                    $sql = "UPDATE services SET title=?, description=?, icon=? WHERE id=?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$title, $description, $icon, $id]);
                    $message = "Service berhasil diupdate!";
                    $messageType = "success";
                    break;
                    
                case 'delete':
                    if(!$isAdmin) {
                        throw new Exception("Anda tidak memiliki izin untuk menghapus data!");
                    }
                    
                    $id = $_POST['id'];
                    $sql = "DELETE FROM services WHERE id=?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$id]);
                    $message = "Service berhasil dihapus!";
                    $messageType = "success";
                    break;
            }
            
            // Redirect after success
            header("Location: crud_services.php?msg=" . urlencode($message) . "&type=" . $messageType);
            exit();
            
        } catch(Exception $e) {
            $message = $e->getMessage();
            $messageType = "danger";
        }
    }
}

// Get message from URL if exists
if(isset($_GET['msg'])) {
    $message = $_GET['msg'];
    $messageType = isset($_GET['type']) ? $_GET['type'] : 'info';
}

// Pagination
$items_per_page = 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $items_per_page;

// Search
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Build query based on search
if(!empty($search)) {
    // Count total records with search
    $count_sql = "SELECT COUNT(*) FROM services WHERE title LIKE ? OR description LIKE ?";
    $stmt = $pdo->prepare($count_sql);
    $stmt->execute(["%$search%", "%$search%"]);
    $total_records = $stmt->fetchColumn();
    
    // Get records with search and pagination
    $sql = "SELECT * FROM services WHERE title LIKE ? OR description LIKE ? ORDER BY created_at DESC LIMIT $items_per_page OFFSET $offset";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["%$search%", "%$search%"]);
    $services = $stmt->fetchAll();
} else {
    // Count total records
    $stmt = $pdo->query("SELECT COUNT(*) FROM services");
    $total_records = $stmt->fetchColumn();
    
    // Get records with pagination
    $sql = "SELECT * FROM services ORDER BY created_at DESC LIMIT $items_per_page OFFSET $offset";
    $stmt = $pdo->query($sql);
    $services = $stmt->fetchAll();
}

$total_pages = ceil($total_records / $items_per_page);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../img/logo1dbi.png">
    <title>Services - Jasa Pembuatan Website Profesional</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .role-badge {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
        .service-icon {
            font-size: 24px;
        }
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }
        .btn-action {
            margin: 0 2px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <span class="navbar-brand">
                <i class="fas fa-cogs"></i> Manage Services
            </span>
            <div>
                <a href="dashboard.php" class="btn btn-outline-light btn-sm me-2">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="../index.php" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-globe"></i> View Site
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Alert Message -->
        <?php if($message): ?>
            <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                <i class="fas <?php echo $messageType == 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'; ?>"></i>
                <?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Search Bar -->
        <div class="row mb-4">
            <div class="col-md-6">
                <form method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" 
                        placeholder="Cari services..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    <?php if($search): ?>
                        <a href="crud_services.php" class="btn btn-secondary ms-2">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    <?php endif; ?>
                </form>
            </div>
            <div class="col-md-6 text-end">
                <?php if($isAdmin): ?>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="fas fa-plus"></i> Tambah Service
                    </button>
                <?php else: ?>
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle"></i> Mode Read Only - Anda hanya bisa melihat data
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Services Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th width="50">ID</th>
                        <th width="100">Icon</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th width="150">Created At</th>
                        <?php if($isAdmin): ?>
                        <th width="150">Actions</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($services) > 0): ?>
                        <?php foreach($services as $service): ?>
                        <tr>
                            <td><?php echo $service['id']; ?></td>
                            <td class="text-center">
                                <span class="service-icon"><?php echo htmlspecialchars($service['icon']); ?></span>
                            </td>
                            <td><?php echo htmlspecialchars($service['title']); ?></td>
                            <td><?php echo htmlspecialchars($service['description']); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($service['created_at'])); ?></td>
                            <?php if($isAdmin): ?>
                            <td class="text-center">
                                <button class="btn btn-warning btn-sm btn-action" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editModal<?php echo $service['id']; ?>"
                                        title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm btn-action" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal<?php echo $service['id']; ?>"
                                        title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                            <?php endif; ?>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal<?php echo $service['id']; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST">
                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                <i class="fas fa-edit"></i> Edit Service
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="id" value="<?php echo $service['id']; ?>">
                                            
                                            <div class="mb-3">
                                                <label>Title</label>
                                                <input type="text" name="title" class="form-control" 
                                                    value="<?php echo htmlspecialchars($service['title']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Description</label>
                                                <textarea name="description" class="form-control" rows="3" required><?php echo htmlspecialchars($service['description']); ?></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label>Icon (Emoji)</label>
                                                <input type="text" name="icon" class="form-control" 
                                                    value="<?php echo htmlspecialchars($service['icon']); ?>" required>
                                                <small class="text-muted">Contoh: 💻, 🎨, 📈, 🛒</small>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="deleteModal<?php echo $service['id']; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST">
                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                <i class="fas fa-trash"></i> Hapus Service
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $service['id']; ?>">
                                            <p>Apakah Anda yakin ingin menghapus service:</p>
                                            <p class="fw-bold">"<?php echo htmlspecialchars($service['title']); ?>"</p>
                                            <p class="text-danger">Tindakan ini tidak dapat dibatalkan!</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="<?php echo $isAdmin ? '6' : '5'; ?>" class="text-center">
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle"></i> Belum ada data service
                                    <?php if($isAdmin): ?>
                                        <br>Klik tombol "Tambah Service" untuk menambahkan.
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if($total_pages > 1): ?>
        <nav aria-label="Page navigation" class="mt-4">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php echo $current_page == 1 ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $current_page-1; ?>&search=<?php echo urlencode($search); ?>">
                        <i class="fas fa-chevron-left"></i> Previous
                    </a>
                </li>
                
                <?php 
                $start_page = max(1, $current_page - 2);
                $end_page = min($total_pages, $current_page + 2);
                
                if($start_page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=1&search=<?php echo urlencode($search); ?>">1</a>
                    </li>
                    <?php if($start_page > 2): ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    <?php endif; ?>
                <?php endif; ?>
                
                <?php for($i = $start_page; $i <= $end_page; $i++): ?>
                    <li class="page-item <?php echo $i == $current_page ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>
                
                <?php if($end_page < $total_pages): ?>
                    <?php if($end_page < $total_pages - 1): ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    <?php endif; ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $total_pages; ?>&search=<?php echo urlencode($search); ?>">
                            <?php echo $total_pages; ?>
                        </a>
                    </li>
                <?php endif; ?>
                
                <li class="page-item <?php echo $current_page == $total_pages ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $current_page+1; ?>&search=<?php echo urlencode($search); ?>">
                        Next <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
        
        <!-- Info -->
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="alert alert-secondary">
                    <i class="fas fa-chart-bar"></i> 
                    Total Records: <strong><?php echo $total_records; ?></strong> | 
                    Page <?php echo $current_page; ?> of <?php echo $total_pages; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal (Admin Only) -->
    <?php if($isAdmin): ?>
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-plus"></i> Tambah Service Baru
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="create">
                        
                        <div class="mb-3">
                            <label>Title *</label>
                            <input type="text" name="title" class="form-control" 
                                    placeholder="Contoh: Web Development" required>
                        </div>
                        
                        <div class="mb-3">
                            <label>Description *</label>
                            <textarea name="description" class="form-control" rows="3" 
                                        placeholder="Deskripsikan layanan ini..." required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label>Icon (Emoji) *</label>
                            <input type="text" name="icon" class="form-control" 
                                    placeholder="Contoh: 💻, 🎨, 📈, 🛒" required>
                            <small class="text-muted">Gunakan emoji untuk mewakili layanan</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>