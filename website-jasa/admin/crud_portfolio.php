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

// Tentukan path yang benar (menggunakan absolute path)
$base_path = dirname(__DIR__); // C:\laragon\www\website-jasa
$upload_dir = $base_path . '/assets/images/';
$upload_url = 'assets/images/';

// Buat folder jika belum ada
if(!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Handle CRUD operations
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['action'])) {
        try {
            switch($_POST['action']) {
                case 'create':
                    if(!$isAdmin) {
                        throw new Exception("Anda tidak memiliki izin untuk menambah data!");
                    }
                    
                    $title = $_POST['title'];
                    $description = $_POST['description'];
                    $client_name = $_POST['client_name'];
                    
                    // Handle file upload
                    $image_url = '';
                    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                        // Validasi file
                        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
                        $max_size = 20 * 1024 * 1024; // 20MB
                        
                        if(!in_array($_FILES['image']['type'], $allowed_types)) {
                            throw new Exception("Format file tidak didukung! Gunakan JPG, PNG, atau GIF.");
                        }
                        
                        if($_FILES['image']['size'] > $max_size) {
                            throw new Exception("Ukuran file terlalu besar! Maksimal 2MB.");
                        }
                        
                        // Generate unique filename
                        $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                        $filename = time() . '_' . uniqid() . '.' . $file_extension;
                        $target_file = $upload_dir . $filename;
                        
                        // Validasi gambar
                        $check = getimagesize($_FILES['image']['tmp_name']);
                        if($check !== false) {
                            if(move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                                $image_url = $filename;
                            } else {
                                throw new Exception("Gagal upload gambar! Periksa permission folder.");
                            }
                        } else {
                            throw new Exception("File bukan gambar yang valid!");
                        }
                    } else {
                        throw new Exception("Silakan pilih gambar untuk portfolio!");
                    }
                    
                    $sql = "INSERT INTO portfolio (title, description, image_url, client_name) VALUES (?, ?, ?, ?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$title, $description, $image_url, $client_name]);
                    $message = "Portfolio berhasil ditambahkan!";
                    $messageType = "success";
                    break;
                    
                case 'update':
                    if(!$isAdmin) {
                        throw new Exception("Anda tidak memiliki izin untuk mengupdate data!");
                    }
                    
                    $id = $_POST['id'];
                    $title = $_POST['title'];
                    $description = $_POST['description'];
                    $client_name = $_POST['client_name'];
                    
                    // Get current image
                    $stmt = $pdo->prepare("SELECT image_url FROM portfolio WHERE id = ?");
                    $stmt->execute([$id]);
                    $current = $stmt->fetch();
                    $image_url = $current['image_url'];
                    
                    // Handle new image upload
                    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
                        $max_size = 2 * 1024 * 1024;
                        
                        if(!in_array($_FILES['image']['type'], $allowed_types)) {
                            throw new Exception("Format file tidak didukung!");
                        }
                        
                        if($_FILES['image']['size'] > $max_size) {
                            throw new Exception("Ukuran file terlalu besar!");
                        }
                        
                        $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                        $filename = time() . '_' . uniqid() . '.' . $file_extension;
                        $target_file = $upload_dir . $filename;
                        
                        $check = getimagesize($_FILES['image']['tmp_name']);
                        if($check !== false) {
                            if(move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                                // Delete old image if exists
                                if($image_url && file_exists($upload_dir . $image_url)) {
                                    unlink($upload_dir . $image_url);
                                }
                                $image_url = $filename;
                            } else {
                                throw new Exception("Gagal upload gambar baru!");
                            }
                        } else {
                            throw new Exception("File bukan gambar yang valid!");
                        }
                    }
                    
                    $sql = "UPDATE portfolio SET title=?, description=?, image_url=?, client_name=? WHERE id=?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$title, $description, $image_url, $client_name, $id]);
                    $message = "Portfolio berhasil diupdate!";
                    $messageType = "success";
                    break;
                    
                case 'delete':
                    if(!$isAdmin) {
                        throw new Exception("Anda tidak memiliki izin untuk menghapus data!");
                    }
                    
                    $id = $_POST['id'];
                    
                    // Delete image file
                    $stmt = $pdo->prepare("SELECT image_url FROM portfolio WHERE id = ?");
                    $stmt->execute([$id]);
                    $portfolio = $stmt->fetch();
                    if($portfolio['image_url'] && file_exists($upload_dir . $portfolio['image_url'])) {
                        unlink($upload_dir . $portfolio['image_url']);
                    }
                    
                    $sql = "DELETE FROM portfolio WHERE id=?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$id]);
                    $message = "Portfolio berhasil dihapus!";
                    $messageType = "success";
                    break;
            }
            
            // Redirect to avoid form resubmission
            if($messageType == 'success') {
                header("Location: crud_portfolio.php?msg=" . urlencode($message) . "&type=" . $messageType);
                exit();
            }
            
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
$items_per_page = 6;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $items_per_page;

// Search
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Build query based on search
if(!empty($search)) {
    // Count total records with search
    $count_sql = "SELECT COUNT(*) FROM portfolio WHERE title LIKE ? OR client_name LIKE ?";
    $stmt = $pdo->prepare($count_sql);
    $stmt->execute(["%$search%", "%$search%"]);
    $total_records = $stmt->fetchColumn();
    
    // Get records with search and pagination
    $sql = "SELECT * FROM portfolio WHERE title LIKE ? OR client_name LIKE ? ORDER BY created_at DESC LIMIT $items_per_page OFFSET $offset";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["%$search%", "%$search%"]);
    $portfolios = $stmt->fetchAll();
} else {
    // Count total records
    $stmt = $pdo->query("SELECT COUNT(*) FROM portfolio");
    $total_records = $stmt->fetchColumn();
    
    // Get records with pagination
    $sql = "SELECT * FROM portfolio ORDER BY created_at DESC LIMIT $items_per_page OFFSET $offset";
    $stmt = $pdo->query($sql);
    $portfolios = $stmt->fetchAll();
}

$total_pages = ceil($total_records / $items_per_page);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../img/logo1dbi.png">
    <title>Portfolio - Jasa Pembuatan Website Profesional</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .role-badge {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        .preview-image {
            max-width: 200px;
            margin-top: 10px;
            border-radius: 5px;
        }
        .portfolio-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <span class="navbar-brand">
                <i class="fas fa-folder-open"></i> Manage Portfolio
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
                        placeholder="Cari portfolio..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    <?php if($search): ?>
                        <a href="crud_portfolio.php" class="btn btn-secondary ms-2">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    <?php endif; ?>
                </form>
            </div>
            <div class="col-md-6 text-end">
                <?php if($isAdmin): ?>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="fas fa-plus"></i> Tambah Portfolio
                    </button>
                <?php else: ?>
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle"></i> Mode Read Only - Anda hanya bisa melihat data
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Portfolio Grid -->
        <div class="row">
            <?php if(count($portfolios) > 0): ?>
                <?php foreach($portfolios as $portfolio): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <?php 
                        $image_path = '../assets/images/' . $portfolio['image_url'];
                        if($portfolio['image_url'] && file_exists($upload_dir . $portfolio['image_url'])): 
                        ?>
                            <img src="<?php echo $image_path; ?>" class="card-img-top" alt="<?php echo $portfolio['title']; ?>">
                        <?php else: ?>
                            <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center">
                                <i class="fas fa-image fa-4x text-white"></i>
                            </div>
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($portfolio['title']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars(substr($portfolio['description'], 0, 100)); ?>...</p>
                            <p class="text-muted">
                                <i class="fas fa-user"></i> Client: <?php echo htmlspecialchars($portfolio['client_name']); ?>
                            </p>
                            <small class="text-muted">
                                <i class="fas fa-calendar"></i> <?php echo date('d M Y', strtotime($portfolio['created_at'])); ?>
                            </small>
                        </div>
                        <div class="card-footer bg-transparent">
                            <?php if($isAdmin): ?>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $portfolio['id']; ?>">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $portfolio['id']; ?>">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            <?php else: ?>
                                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $portfolio['id']; ?>">
                                    <i class="fas fa-eye"></i> Lihat Detail
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Edit Modal (Admin Only) -->
                <?php if($isAdmin): ?>
                <div class="modal fade" id="editModal<?php echo $portfolio['id']; ?>" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form method="POST" enctype="multipart/form-data">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        <i class="fas fa-edit"></i> Edit Portfolio
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="id" value="<?php echo $portfolio['id']; ?>">
                                    
                                    <div class="mb-3">
                                        <label>Judul Portfolio *</label>
                                        <input type="text" name="title" class="form-control" 
                                            value="<?php echo htmlspecialchars($portfolio['title']); ?>" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label>Deskripsi *</label>
                                        <textarea name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($portfolio['description']); ?></textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label>Nama Client *</label>
                                        <input type="text" name="client_name" class="form-control" 
                                            value="<?php echo htmlspecialchars($portfolio['client_name']); ?>" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label>Gambar Portfolio</label>
                                        <?php if($portfolio['image_url'] && file_exists($upload_dir . $portfolio['image_url'])): ?>
                                            <div class="mb-2">
                                                <img src="../assets/images/<?php echo $portfolio['image_url']; ?>" class="preview-image" alt="Current image">
                                                <p class="text-muted mt-1">Gambar saat ini</p>
                                            </div>
                                        <?php endif; ?>
                                        <input type="file" name="image" class="form-control" accept="image/*">
                                        <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar. Format: JPG, PNG, GIF (Max 2MB)</small>
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
                <div class="modal fade" id="deleteModal<?php echo $portfolio['id']; ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        <i class="fas fa-trash"></i> Hapus Portfolio
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $portfolio['id']; ?>">
                                    <p>Apakah Anda yakin ingin menghapus portfolio <strong><?php echo htmlspecialchars($portfolio['title']); ?></strong>?</p>
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
                <?php else: ?>
                <!-- View Modal (User Only) -->
                <div class="modal fade" id="viewModal<?php echo $portfolio['id']; ?>" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-folder-open"></i> Detail Portfolio
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <?php if($portfolio['image_url'] && file_exists($upload_dir . $portfolio['image_url'])): ?>
                                    <img src="../assets/images/<?php echo $portfolio['image_url']; ?>" class="img-fluid mb-3" alt="<?php echo $portfolio['title']; ?>">
                                <?php endif; ?>
                                <h4><?php echo htmlspecialchars($portfolio['title']); ?></h4>
                                <p><?php echo nl2br(htmlspecialchars($portfolio['description'])); ?></p>
                                <hr>
                                <p><strong>Client:</strong> <?php echo htmlspecialchars($portfolio['client_name']); ?></p>
                                <p><strong>Dibuat:</strong> <?php echo date('d F Y H:i', strtotime($portfolio['created_at'])); ?></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle fa-2x mb-2"></i>
                        <h5>Belum ada data portfolio</h5>
                        <?php if($isAdmin): ?>
                            <p>Klik tombol "Tambah Portfolio" untuk menambahkan portfolio pertama Anda.</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
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
                
                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo $i == $current_page ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>
                
                <li class="page-item <?php echo $current_page == $total_pages ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $current_page+1; ?>&search=<?php echo urlencode($search); ?>">
                        Next <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
    </div>

    <!-- Add Modal (Admin Only) -->
    <?php if($isAdmin): ?>
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-plus"></i> Tambah Portfolio Baru
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="create">
                        
                        <div class="mb-3">
                            <label>Judul Portfolio *</label>
                            <input type="text" name="title" class="form-control" 
                                placeholder="Contoh: Website E-commerce Modern" required>
                        </div>
                        
                        <div class="mb-3">
                            <label>Deskripsi *</label>
                            <textarea name="description" class="form-control" rows="4" 
                                    placeholder="Deskripsikan project portfolio ini..." required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label>Nama Client *</label>
                            <input type="text" name="client_name" class="form-control" 
                                placeholder="Contoh: PT Maju Jaya" required>
                        </div>
                        
                        <div class="mb-3">
                            <label>Gambar Portfolio *</label>
                            <input type="file" name="image" class="form-control" accept="image/*" required>
                            <small class="text-muted">Format: JPG, PNG, GIF (Max 2MB)</small>
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