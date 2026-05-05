<?php
require_once 'config/database.php';

$success = '';
$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'] ?? '';
        $message = $_POST['message'];
        
        // Sesuaikan dengan struktur tabel yang ada (tanpa subject)
        $sql = "INSERT INTO contacts (name, email, phone, message, created_at) 
                VALUES (?, ?, ?, ?, NOW())";
        $stmt = $pdo->prepare($sql);
        
        if($stmt->execute([$name, $email, $phone, $message])) {
            $success = "Pesan terkirim! Kami akan menghubungi Anda segera.";
        }
    } catch(Exception $e) {
        $error = "Gagal mengirim pesan. Silakan coba lagi.";
    }
}
?>

<title>Contact | Jasa Pembuatan Website Profesional</title>
<div class="container py-5">
    <h1 class="text-center mb-5">Hubungi Kami</h1>
    
    <?php if($success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> <?php echo $success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h4 class="mb-4"><i class="fas fa-paper-plane me-2"  style="color: #FF653F;"></i> Kirim Pesan</h4>
                    <form method="POST" id="contactForm">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">No. Telepon</label>
                            <input type="tel" name="phone" class="form-control">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Pesan <span class="text-danger">*</span></label>
                            <textarea name="message" class="form-control" rows="5" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn rounded-pill px-4" style="color: #FF653F;">
                            <i class="fas fa-paper-plane me-2"></i> Kirim Pesan
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card bg-light shadow-sm">
                <div class="card-body p-4">
                    <h4 class="mb-4"><i class="fas fa-address-card me-2" style="color: #FF653F;"></i> Informasi Kontak</h4>
                    
                    <div class="mb-4">
                        <p><i class="fas fa-map-marker-alt me-3" style="color: #FF653F;"></i> Semarang, Indonesia</p>
                        <p><i class="fas fa-envelope  me-3" style="color: #FF653F;"></i> dbitechnologic@gmail.com</p>
                        <p><i class="fab fa-whatsapp  me-3" style="color: #FF653F;"></i> +62 1234567890</p>
                        <hr>
                    <h5><i class="fas fa-share-alt me-2" style="color: #FF653F;"></i> Sosial Media</h5>
                    <div class="d-flex gap-3 mt-3">
                        <a href="https://www.instagram.com/dbi_tech?igsh=MTJjYmc5eDdobHFxNw==" target="_blank" class="text-decoration-none">
                            <i class="fab fa-instagram fa-lg" style="color: #FF653F;"></i>
                        </a>

                        <a href="https://x.com/DBI_Technology" target="_blank" class="text-decoration-none">
                            <i class="fab fa-x fa-lg" style="color: #FF653F;"></i>
                        </a>

                        <a href="https://github.com/DBI-TECH" target="_blank" class="text-decoration-none">
                            <i class="fab fa-github fa-lg" style="color: #FF653F;"></i>
                        </a>

                        <a href="https://www.tiktok.com/@dbi.tech" target="_blank" class="text-decoration-none">
                            <i class="fab fa-tiktok fa-lg" style="color: #FF653F;"></i>
                        </a>

                        <a href="https://www.facebook.com/profile.php?id=61589417064369" target="_blank" class="text-decoration-none">
                            <i class="fab fa-facebook fa-lg" style="color: #FF653F;"></i>
                        </a>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-control:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}
.btn-danger:hover {
    transform: translateY(-2px);
    transition: all 0.3s ease;
}
</style>