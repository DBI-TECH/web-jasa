<?php
require_once 'config/database.php';
$stmt = $pdo->query("SELECT * FROM pricing ORDER BY price ASC");
$pricings = $stmt->fetchAll();
?>

<title>Pricing | Jasa Pembuatan Website Profesional</title>
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
                    <h2 class="text-danger">Rp <?php echo number_format($package['price'], 0, ',', '.'); ?></h2>
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