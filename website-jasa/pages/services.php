<?php
require_once 'config/database.php';
$stmt = $pdo->query("SELECT * FROM services ORDER BY created_at DESC");
$services = $stmt->fetchAll();
?>

<title>Services | Jasa Pembuatan Website Profesional</title>
<section class="services-section py-5" style="background: #f8f9fa;">
    <div class="container">

        <!-- Heading -->
        <div class="text-center mb-5">
            <h3 class="fw-bold text-uppercase" style="color: #FF653F;">Services</h3>
            <h1 class="fw-bold mt-2">Layanan Profesional DBI TECH</h1>
            <p class="text-muted mx-auto" style="max-width: 700px;">
                Kami menyediakan berbagai layanan pembuatan website modern,
                responsif, cepat, dan SEO Friendly untuk membantu bisnis Anda
                berkembang lebih profesional di era digital.
            </p>
        </div>

        <!-- Services Card -->
        <div class="row g-4">
            <?php foreach($services as $service): ?>
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-lg h-100 rounded-4 service-card">
                    <div class="card-body text-center p-4">

                        <!-- Icon -->
                        <div class="service-icon mb-4">
                            <div class="display-4 text-danger">
                                <?php echo $service['icon']; ?>
                            </div>
                        </div>

                        <!-- Title -->
                        <h4 class="fw-bold mb-3">
                            <?php echo htmlspecialchars($service['title']); ?>
                        </h4>

                        <!-- Description -->
                        <p class="text-muted">
                            <?php echo htmlspecialchars($service['description']); ?>
                        </p>

                        <!-- Button -->
                        <a href="index.php?page=contact" 
                            class="btn rounded-pill px-4 mt-3 text-white" style="background-color: #FF653F;">
                            Konsultasi
                        </a>

                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<style>
.service-card {
    transition: 0.3s ease;
}

.service-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.08);
}

.service-icon {
    width: 80px;
    height: 80px;
    margin: auto;
    background: #fff5f5;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>