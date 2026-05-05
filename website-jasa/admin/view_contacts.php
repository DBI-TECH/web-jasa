<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
redirectIfNotLoggedIn();

$isAdmin = ($_SESSION['role'] == 'admin');

// Hapus pesan
if(isset($_GET['delete']) && is_numeric($_GET['delete']) && $isAdmin) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM contacts WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: view_contacts.php?msg=deleted");
    exit();
}

// Ambil semua pesan
$stmt = $pdo->query("SELECT * FROM contacts ORDER BY created_at DESC");
$contacts = $stmt->fetchAll();

// Total pesan
$total_count = count($contacts);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../img/logo1dbi.png">
    <title>View Contact - Jasa Pembuatan Website Profesional</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }
        .message-preview {
            max-width: 250px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .card-contact {
            transition: 0.3s ease;
        }
        .card-contact:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <span class="navbar-brand">
                <i class="fas fa-envelope"></i> Contact Messages
                <?php if($total_count > 0): ?>
                    <span class="badge bg-danger"><?php echo $total_count; ?> Total</span>
                <?php endif; ?>
            </span>
            <div>
                <a href="dashboard.php" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="../logout.php" class="btn btn-danger btn-sm ms-2">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> Pesan berhasil dihapus!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-envelope text-primary me-2"></i> Daftar Pesan Masuk
                    </h4>
                    <div>
                        <span class="badge bg-secondary">Total: <?php echo $total_count; ?></span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php if(count($contacts) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Telepon</th>
                                    <th>Pesan</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($contacts as $contact): ?>
                                <tr>
                                    <td><?php echo $contact['id']; ?></td>
                                    <td><?php echo htmlspecialchars($contact['name'] ?? ''); ?></td>
                                    <td>
                                        <a href="mailto:<?php echo htmlspecialchars($contact['email'] ?? ''); ?>">
                                            <?php echo htmlspecialchars($contact['email'] ?? ''); ?>
                                        </a>
                                    </td>
                                    <td><?php echo htmlspecialchars($contact['phone'] ?? '-'); ?></td>
                                    <td class="message-preview">
                                        <?php echo htmlspecialchars(substr($contact['message'] ?? '', 0, 50)); ?>...
                                    </td>
                                    <td>
                                        <small><?php echo date('d/m/Y H:i', strtotime($contact['created_at'] ?? 'now')); ?></small>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $contact['id']; ?>">
                                            <i class="fas fa-eye"></i> Detail
                                        </button>
                                        <?php if($isAdmin): ?>
                                        <a href="?delete=<?php echo $contact['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus pesan ini?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>

                                <!-- Modal Detail -->
                                <div class="modal fade" id="viewModal<?php echo $contact['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-envelope me-2"></i> Detail Pesan
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="fw-bold text-primary">
                                                                <i class="fas fa-user me-1"></i> Nama:
                                                            </label>
                                                            <p class="mt-1"><?php echo htmlspecialchars($contact['name'] ?? '-'); ?></p>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label class="fw-bold text-primary">
                                                                <i class="fas fa-envelope me-1"></i> Email:
                                                            </label>
                                                            <p class="mt-1">
                                                                <a href="mailto:<?php echo htmlspecialchars($contact['email'] ?? ''); ?>">
                                                                    <?php echo htmlspecialchars($contact['email'] ?? '-'); ?>
                                                                </a>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="fw-bold text-primary">
                                                                <i class="fas fa-phone me-1"></i> Telepon:
                                                            </label>
                                                            <p class="mt-1"><?php echo htmlspecialchars($contact['phone'] ?? '-'); ?></p>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label class="fw-bold text-primary">
                                                                <i class="fas fa-calendar me-1"></i> Tanggal:
                                                            </label>
                                                            <p class="mt-1"><?php echo date('d F Y H:i:s', strtotime($contact['created_at'] ?? 'now')); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <hr>
                                                
                                                <div class="mb-3">
                                                    <label class="fw-bold text-primary">
                                                        <i class="fas fa-comment me-1"></i> Pesan:
                                                    </label>
                                                    <div class="alert alert-light mt-2">
                                                        <?php echo nl2br(htmlspecialchars($contact['message'] ?? '')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <a href="mailto:<?php echo htmlspecialchars($contact['email'] ?? ''); ?>?subject=Re: Pesan dari <?php echo urlencode($contact['name'] ?? ''); ?>" class="btn btn-primary">
                                                    <i class="fas fa-reply"></i> Balas Email
                                                </a>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="fas fa-times"></i> Tutup
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info text-center py-5">
                        <i class="fas fa-inbox fa-4x mb-3"></i>
                        <h5>Belum ada pesan masuk</h5>
                        <p>Semua pesan dari customer akan muncul di sini.</p>
                        <a href="../index.php?page=contact" class="btn btn-primary mt-2">
                            <i class="fas fa-arrow-left me-1"></i> Kembali ke Halaman Contact
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>