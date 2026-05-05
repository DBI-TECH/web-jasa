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
                    
                    $package_name = $_POST['package_name'];
                    $price = str_replace(['Rp', '.', ' '], '', $_POST['price']);
                    $features = $_POST['features'];
                    $is_popular = isset($_POST['is_popular']) ? 1 : 0;
                    
                    $sql = "INSERT INTO pricing (package_name, price, features, is_popular) VALUES (?, ?, ?, ?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$package_name, $price, $features, $is_popular]);
                    $message = "Paket harga berhasil ditambahkan!";
                    $messageType = "success";
                    break;
                    
                case 'update':
                    if(!$isAdmin) {
                        throw new Exception("Anda tidak memiliki izin untuk mengupdate data!");
                    }
                    
                    $id = $_POST['id'];
                    $package_name = $_POST['package_name'];
                    $price = str_replace(['Rp', '.', ' '], '', $_POST['price']);
                    $features = $_POST['features'];
                    $is_popular = isset($_POST['is_popular']) ? 1 : 0;
                    
                    $sql = "UPDATE pricing SET package_name=?, price=?, features=?, is_popular=? WHERE id=?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$package_name, $price, $features, $is_popular, $id]);
                    $message = "Paket harga berhasil diupdate!";
                    $messageType = "success";
                    break;
                    
                case 'delete':
                    if(!$isAdmin) {
                        throw new Exception("Anda tidak memiliki izin untuk menghapus data!");
                    }
                    
                    $id = $_POST['id'];
                    $sql = "DELETE FROM pricing WHERE id=?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$id]);
                    $message = "Paket harga berhasil dihapus!";
                    $messageType = "success";
                    break;
            }
        } catch(Exception $e) {
            $message = $e->getMessage();
            $messageType = "danger";
        }
    }
}

// Get all pricing data
$stmt = $pdo->query("SELECT * FROM pricing ORDER BY price ASC");
$pricings = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../img/logo1dbi.png">
    <title>Pricing - Jasa Pembuatan Website Profesional</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .role-badge {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
        .price-card {
            transition: transform 0.3s ease;
            margin-bottom: 20px;
        }
        .price-card:hover {
            transform: translateY(-5px);
        }
        .popular-badge {
            position: absolute;
            top: -10px;
            right: 20px;
        }
        .feature-list {
            list-style: none;
            padding-left: 0;
        }
        .feature-list li {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .feature-list li:last-child {
            border-bottom: none;
        }
        .feature-list li i {
            margin-right: 10px;
        }
    </style>
</head>
<body>
        <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <span class="navbar-brand">
                <i class="fas fa-tags"></i> Manage Pricing Packages
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
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h2><i class="fas fa-tags"></i> Daftar Paket Harga</h2>
                <p class="text-muted">Kelola paket layanan dan harga website</p>
            </div>
            <div class="col-md-6 text-end">
                <?php if($isAdmin): ?>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="fas fa-plus"></i> Tambah Paket Baru
                    </button>
                <?php else: ?>
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle"></i> Mode Read Only - Anda hanya bisa melihat data
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Pricing Cards -->
        <div class="row">
            <?php if(count($pricings) > 0): ?>
                <?php foreach($pricings as $package): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card price-card h-100 shadow-sm <?php echo $package['is_popular'] ? 'border-primary' : ''; ?> position-relative">
                        <?php if($package['is_popular']): ?>
                            <div class="popular-badge">
                                <span class="badge bg-primary">
                                    <i class="fas fa-star"></i> Most Popular
                                </span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="card-header text-center <?php echo $package['is_popular'] ? 'bg-primary text-white' : 'bg-light'; ?>">
                            <h3 class="mb-0"><?php echo htmlspecialchars($package['package_name']); ?></h3>
                        </div>
                        
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <h2 class="text-primary">
                                    Rp <?php echo number_format($package['price'], 0, ',', '.'); ?>
                                </h2>
                                <small class="text-muted">/project</small>
                            </div>
                            
                            <ul class="feature-list">
                                <?php 
                                $features = explode(',', $package['features']);
                                foreach($features as $feature): ?>
                                <li>
                                    <i class="fas fa-check-circle text-success"></i>
                                    <?php echo htmlspecialchars(trim($feature)); ?>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        
                        <div class="card-footer bg-transparent text-center">
                            <?php if($isAdmin): ?>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $package['id']; ?>">
                                    <i class="fas fa-edit"></i> Edit Paket
                                </button>
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $package['id']; ?>">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            <?php else: ?>
                                <a href="../index.php?page=contact" class="btn btn-primary">
                                    <i class="fas fa-envelope"></i> Hubungi Kami
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Edit Modal (Admin Only) -->
                <?php if($isAdmin): ?>
                <div class="modal fade" id="editModal<?php echo $package['id']; ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        <i class="fas fa-edit"></i> Edit Paket Harga
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="id" value="<?php echo $package['id']; ?>">
                                    
                                    <div class="mb-3">
                                        <label>Nama Paket *</label>
                                        <input type="text" name="package_name" class="form-control" value="<?php echo htmlspecialchars($package['package_name']); ?>" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label>Harga (Rp) *</label>
                                        <input type="number" name="price" class="form-control" value="<?php echo $package['price']; ?>" required>
                                        <small class="text-muted">Contoh: 5000000</small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label>Fitur - Fitur *</label>
                                        <textarea name="features" class="form-control" rows="5" required><?php echo htmlspecialchars($package['features']); ?></textarea>
                                        <small class="text-muted">Pisahkan setiap fitur dengan koma (,)</small>
                                    </div>
                                    
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" name="is_popular" class="form-check-input" id="popular<?php echo $package['id']; ?>" <?php echo $package['is_popular'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="popular<?php echo $package['id']; ?>">
                                            Tandai sebagai paket populer
                                        </label>
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
                <div class="modal fade" id="deleteModal<?php echo $package['id']; ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        <i class="fas fa-trash"></i> Hapus Paket
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $package['id']; ?>">
                                    <p>Apakah Anda yakin ingin menghapus paket <strong><?php echo htmlspecialchars($package['package_name']); ?></strong>?</p>
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
                <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle fa-2x mb-2"></i>
                        <h5>Belum ada paket harga</h5>
                        <?php if($isAdmin): ?>
                            <p>Klik tombol "Tambah Paket Baru" untuk menambahkan paket harga pertama.</p>
                        <?php else: ?>
                            <p>Silahkan hubungi admin untuk informasi harga.</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
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
                            <i class="fas fa-plus"></i> Tambah Paket Baru
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="create">
                        
                        <div class="mb-3">
                            <label>Nama Paket *</label>
                            <input type="text" name="package_name" class="form-control" placeholder="Contoh: Basic, Premium, dll" required>
                        </div>
                        
                        <div class="mb-3">
                            <label>Harga (Rp) *</label>
                            <input type="number" name="price" class="form-control" placeholder="Contoh: 2500000" required>
                        </div>
                        
                        <div class="mb-3">
                            <label>Fitur - Fitur *</label>
                            <textarea name="features" class="form-control" rows="5" placeholder="Tulis fitur-fitur, pisahkan dengan koma" required></textarea>
                            <small class="text-muted">Contoh: Website 5 halaman, Responsive design, Contact form</small>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="is_popular" class="form-check-input" id="popular">
                            <label class="form-check-label" for="popular">
                                Tandai sebagai paket populer
                            </label>
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