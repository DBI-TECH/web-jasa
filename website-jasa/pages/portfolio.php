<?php
require_once 'config/database.php';
$stmt = $pdo->query("SELECT * FROM portfolio ORDER BY created_at DESC");
$portfolios = $stmt->fetchAll();
?>

<title>Portfolio | Jasa Pembuatan Website Profesional</title>
<section class="portfolio-section py-5" style="background: #f8f9fa;">
    <div class="container">

        <!-- Heading -->
        <div class="text-center mb-5">
            <h3 class="fw-bold text-uppercase" style="color: #FF653F;">Portfolio</h3>
            <h1 class="fw-bold mt-2">Project Terbaik DBI TECH</h1>
            <p class="text-muted mx-auto" style="max-width: 700px;">
                Kami telah membantu berbagai bisnis, UMKM, personal branding,
                hingga company profile tampil lebih profesional melalui website
                modern, responsif, dan berkualitas tinggi.
            </p>
        </div>

        <!-- Portfolio Cards -->
        <div class="row g-4">
            <?php foreach($portfolios as $portfolio): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden portfolio-card h-100">

                    <!-- Image -->
                    <div class="portfolio-img">
                        <img 
                            src="assets/images/<?php echo $portfolio['image_url']; ?>" 
                            class="card-img-top"
                            alt="<?php echo htmlspecialchars($portfolio['title']); ?>"
                        >
                    </div>

                    <!-- Content -->
                    <div class="card-body p-4">

                        <span class="badge mb-3" style="background-color: #FF653F;">
                            Client Project
                        </span>

                        <h4 class="fw-bold mb-3">
                            <?php echo htmlspecialchars($portfolio['title']); ?>
                        </h4>

                        <p class="text-muted">
                            <?php echo htmlspecialchars(substr($portfolio['description'], 0, 100)); ?>...
                        </p>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <small class="text-muted">
                                <strong>Client:</strong> 
                                <?php echo htmlspecialchars($portfolio['client_name']); ?>
                            </small>

                            <!-- Tombol Detail - Memanggil Modal -->
                            <button type="button" 
                                    class="btn btn-sm rounded-pill px-3 text-white" style="background-color: #FF653F;"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#portfolioModal<?php echo $portfolio['id']; ?>">
                                <i class="fas fa-eye me-1"></i> Detail
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Modal Detail Portfolio -->
            <div class="modal fade" id="portfolioModal<?php echo $portfolio['id']; ?>" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header text-white" style="background-color: #FF653F;">
                            <h5 class="modal-title">
                                <i class="fas fa-folder-open me-2"></i> 
                                Detail Portfolio: <?php echo htmlspecialchars($portfolio['title']); ?>
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <!-- Kolom Gambar -->
                                <div class="col-md-6">
                                    <div class="detail-image-container">
                                        <img src="assets/images/<?php echo $portfolio['image_url']; ?>" 
                                            class="img-fluid rounded shadow-sm detail-image"
                                            alt="<?php echo htmlspecialchars($portfolio['title']); ?>">
                                    </div>
                                </div>
                                
                                <!-- Kolom Informasi -->
                                <div class="col-md-6">
                                    <h3 class="mb-3" style="color: #FF653F;"><?php echo htmlspecialchars($portfolio['title']); ?></h3>
                                    
                                    <div class="info-section mb-3">
                                        <strong style="color: #FF653F;">
                                            <i class="fas fa-user me-2"></i> Client:
                                        </strong>
                                        <p class="mt-2 mb-3"><?php echo htmlspecialchars($portfolio['client_name']); ?></p>
                                    </div>
                                    
                                    <div class="info-section mb-3">
                                        <strong style="color: #FF653F;">
                                            <i class="fas fa-calendar-alt me-2"></i> Tanggal Pengerjaan:
                                        </strong>
                                        <p class="mt-2 mb-3"><?php echo date('d F Y', strtotime($portfolio['created_at'])); ?></p>
                                    </div>
                                    
                                    <div class="info-section mb-3">
                                        <strong style="color: #FF653F;">
                                            <i class="fas fa-align-left me-2"></i> Deskripsi Project:
                                        </strong>
                                        <p class="mt-2 mb-3"><?php echo nl2br(htmlspecialchars($portfolio['description'])); ?></p>
                                    </div>
                                    
                                    <div class="info-section">
                                        <strong style="color: #FF653F;">
                                            <i class="fas fa-tag me-2"></i> Kategori:
                                        </strong>
                                        <p class="mt-2">
                                            <span class="badge" style="background-color: #FF653F;">Website Development</span>
                                            <span class="badge bg-secondary">Portfolio</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                                <i class="fas fa-times me-2"></i> Tutup
                            </button>
                            <a href="index.php?page=contact" class="btn btn-danger rounded-pill px-4">
                                <i class="fas fa-envelope me-2"></i> Hubungi Kami
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<style>
.portfolio-card {
    transition: 0.3s ease;
    cursor: pointer;
}

.portfolio-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.08);
}

.portfolio-img {
    overflow: hidden;
}

.portfolio-img img {
    height: 250px;
    width: 100%;
    object-fit: cover;
    transition: 0.4s ease;
}

.portfolio-card:hover .portfolio-img img {
    transform: scale(1.05);
}

/* Style untuk tombol detail */
.btn-outline-danger:hover {
    background-color: #dc3545;
    color: white;
    transform: translateY(-2px);
    transition: all 0.3s ease;
}

/* Style untuk modal detail */
.detail-image-container {
    position: relative;
    overflow: hidden;
    border-radius: 10px;
}

.detail-image {
    width: 100%;
    height: auto;
    max-height: 400px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.detail-image:hover {
    transform: scale(1.02);
}

.info-section {
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
}

.info-section:last-child {
    border-bottom: none;
}

.modal-dialog-scrollable .modal-body {
    max-height: 70vh;
}

/* Responsive */
@media (max-width: 768px) {
    .detail-image {
        margin-bottom: 20px;
    }
    
    .modal-dialog {
        margin: 10px;
    }
}
</style>

<!-- Tambahkan Font Awesome jika belum ada -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">