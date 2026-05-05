<title>Home | Jasa Pembuatan Website Profesional</title>
<section class="hero-section text-dark" style="background: url('img/back-hero2.png') center center / cover no-repeat;">
    <div class="container py-5">
        <div class="row min-vh-100 align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-6">Bikin Website Profesional Jadi Lebih Mudah Bareng DBI TECH</h1>
                <p class="lead mb-4">Kami siap bantu kamu membuat website yang modern, <br>responsif, cepat, dan SEO friendly dengan harga yang <br>terjangkau. Cocok untuk bisnis, usaha, personal branding, <br>sampai company profile.</p>
                <a href="index.php?page=contact" class="btn text-white px-3 rounded-pill" style="background-color: #FF653F;">Contact Me</a>
            </div>
        </div>
    </div>
</section>

    <?php
    require_once 'config/database.php';
    $stmt = $pdo->query("SELECT * FROM pricing ORDER BY price ASC");
    $pricings = $stmt->fetchAll();
    ?>

    <div class="container py-5">
        <h1 class="text-center mb-5">Harga Paket</h1>
        <div class="row g-4">
            <?php foreach($pricings as $package): ?>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm <?php echo $package['is_popular'] ? 'border-danger' : ''; ?>">
                    <?php if($package['is_popular']): ?>
                    <div class="card-header text-white text-center" style="background-color: #FF653F;">Paling Populer</div>
                    <?php endif; ?>
                    <div class="card-body text-center">
                        <h3><?php echo $package['package_name']; ?></h3>
                        <h2 class="text" style="color: #FF653F;">Rp <?php echo number_format($package['price'], 0, ',', '.'); ?></h2>
                        <ul class="list-unstyled mt-3">
                            <?php 
                            $features = explode(',', $package['features']);
                            foreach($features as $feature): ?>
                            <li class="mb-2">✓ <?php echo trim($feature); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <a href="index.php?page=contact" class="btn text-white" style="background-color: #FF653F;">Pilih Paket</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>