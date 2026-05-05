<?php
require_once '../config/database.php';
require_once '../includes/auth.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$isAdmin = ($_SESSION['role'] == 'admin');

// Ambil statistik - HAPUS query yang menggunakan is_read
$stats = [
    'services' => $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn(),
    'portfolio' => $pdo->query("SELECT COUNT(*) FROM portfolio")->fetchColumn(),
    'contacts' => $pdo->query("SELECT COUNT(*) FROM contacts")->fetchColumn(),
];

// Cek apakah kolom is_read ada, jika ada maka tampilkan
try {
    $check_column = $pdo->query("SHOW COLUMNS FROM contacts LIKE 'is_read'");
    $has_is_read = $check_column->rowCount() > 0;
    
    if($has_is_read) {
        $stats['contacts_unread'] = $pdo->query("SELECT COUNT(*) FROM contacts WHERE is_read = 0")->fetchColumn();
    } else {
        $stats['contacts_unread'] = 0;
    }
} catch(PDOException $e) {
    $stats['contacts_unread'] = 0;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../img/logo1dbi.png">
    <title>Dashboard - Jasa Pembuatan Website Profesional</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            background: #f5f7fa;
            font-family: 'Segoe UI', sans-serif;
        }

        .navbar {
            background: #111827;
            padding: 15px 0;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 22px;
        }

        .dashboard-title {
            font-weight: 700;
            margin-bottom: 30px;
        }

        .stat-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: 0.3s;
            cursor: pointer;
        }

        .stat-card:hover {
            transform: translateY(-8px);
        }

        .stat-icon {
            font-size: 40px;
            opacity: 0.9;
        }

        .menu-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: 0.3s;
        }

        .menu-card:hover {
            transform: translateY(-8px);
        }

        .menu-icon {
            font-size: 45px;
            color: #dc3545;
            margin-bottom: 15px;
        }

        .btn-custom {
            border-radius: 30px;
            padding: 10px 25px;
            font-weight: 600;
        }
        
        .badge-notif {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 12px;
            padding: 5px 8px;
        }
        
        .card {
            position: relative;
        }
        
        .unread-badge {
            animation: pulse 1.5s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-dark">
        <div class="container">
            <span class="navbar-brand">
                <i class="fas fa-laptop-code"></i> Admin Dashboard
            </span>

            <div>
                <span class="text-white me-3">
                    Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
                </span>

                <a href="../index.php" class="btn btn-outline-light btn-sm btn-custom">
                    <i class="fas fa-globe me-1"></i> View Site
                </a>

                <a href="../logout.php" class="btn btn-danger btn-sm btn-custom">
                    <i class="fas fa-sign-out-alt me-1"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-5">

        <h2 class="dashboard-title">
            <i class="fas fa-chart-line me-2 text-danger"></i> Dashboard Overview
        </h2>

        <!-- Statistik -->
        <div class="row g-4">

            <div class="col-md-4">
                <div class="card stat-card bg-primary text-white p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5>Total Services</h5>
                            <h1><?php echo $stats['services']; ?></h1>
                            <small>Layanan aktif</small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-cogs"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card stat-card bg-success text-white p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5>Total Portfolio</h5>
                            <h1><?php echo $stats['portfolio']; ?></h1>
                            <small>Project selesai</small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-images"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card stat-card bg-info text-white p-4" style="cursor: pointer;" onclick="location.href='view_contacts.php'">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5>Total Contacts</h5>
                            <h1><?php echo $stats['contacts']; ?></h1>
                            <small>
                                <?php if(isset($stats['contacts_unread']) && $stats['contacts_unread'] > 0): ?>
                                    <span class="badge bg-warning text-dark mt-1">
                                        <i class="fas fa-envelope"></i> <?php echo $stats['contacts_unread']; ?> belum dibaca
                                    </span>
                                <?php else: ?>
                                    Semua pesan masuk
                                <?php endif; ?>
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Menu CRUD -->
        <h3 class="mt-5 mb-4">
            <i class="fas fa-cog me-2 text-danger"></i> Management Menu
        </h3>
        
        <div class="row g-4">

            <div class="col-md-3">
                <div class="card menu-card h-100 p-4 text-center">
                    <div class="menu-icon">
                        <i class="fas fa-code"></i>
                    </div>
                    <h4>Services</h4>
                    <p class="text-muted small">
                        Kelola layanan website yang ditawarkan
                    </p>
                    <a href="crud_services.php" class="btn btn-primary btn-custom mt-2">
                        <i class="fas fa-edit me-1"></i> Manage
                    </a>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card menu-card h-100 p-4 text-center">
                    <div class="menu-icon">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <h4>Portfolio</h4>
                    <p class="text-muted small">
                        Kelola galeri project client
                    </p>
                    <a href="crud_portfolio.php" class="btn btn-success btn-custom mt-2">
                        <i class="fas fa-edit me-1"></i> Manage
                    </a>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card menu-card h-100 p-4 text-center">
                    <div class="menu-icon">
                        <i class="fas fa-tags"></i>
                    </div>
                    <h4>Pricing</h4>
                    <p class="text-muted small">
                        Kelola paket harga layanan
                    </p>
                    <a href="crud_pricing.php" class="btn btn-info btn-custom mt-2 text-white">
                        <i class="fas fa-edit me-1"></i> Manage
                    </a>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card menu-card h-100 p-4 text-center position-relative">
                    <div class="menu-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h4>Contacts</h4>
                    <p class="text-muted small">
                        Lihat pesan masuk dari client
                    </p>
                    <a href="view_contacts.php" class="btn btn-warning btn-custom mt-2">
                        <i class="fas fa-eye me-1"></i> View Messages
                    </a>
                </div>
            </div>

        </div>
        
        <!-- Informasi Tambahan -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card bg-light">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h5 class="mb-2">
                                    <i class="fas fa-info-circle text-danger me-2"></i> Informasi Login
                                </h5>
                                <p class="mb-1">
                                    <strong>Username Admin:</strong> admin<br>
                                    <strong>Password Admin:</strong> admin
                                </p>
                                <p class="mb-0 text-muted small">
                                    Admin memiliki akses penuh untuk CRUD semua data.
                                </p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <i class="fas fa-shield-alt fa-3x text-muted"></i>
                                <p class="text-muted small mt-2">
                                    Last login: <?php echo date('d F Y H:i:s'); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>