<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="img/logo1dbi.png">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        /* NAVBAR LEBIH KECIL & RAPI */
    #mainNavbar {
        background: #ffffff;
        padding: 10px 0;
        transition: all 0.4s ease;
        z-index: 999;
    }

    /* SAAT DI SCROLL */
    #mainNavbar.scrolled {
        background: rgba(255, 255, 255, 0.92); /* putih agak transparan */
        backdrop-filter: blur(8px); /* efek glass / pudar */
        -webkit-backdrop-filter: blur(8px);
        padding: 6px 0;
        transition: all 0.4s ease;
    }

    /* LOGO */
    #mainNavbar img {
        height: 45px;
        width: auto;
    }

    /* LINK */
    #mainNavbar .nav-link {
        color: #000 !important;
        font-weight: 500;
        font-size: 15px;
        padding: 8px 14px;
    }

    /* HOVER */
    #mainNavbar .nav-link:hover {
        color: #dc3545 !important;
    }

    /* BUTTON CONTACT */
    #mainNavbar .btn-danger {
        padding: 8px 18px;
        font-size: 14px;
        font-weight: 600;
        border-radius: 50px;
    }

    /* AGAR CONTENT TIDAK TERTUTUP NAVBAR */
    .main-content {
        padding-top: 90px;
    }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav id="mainNavbar" class="navbar navbar-expand-lg fixed-top">
    <div class="container">

        <img src="img/logo1dbi.png" alt="Logo DBI" style="height: 75px; width: auto;">

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">

                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=home">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=about">About Us</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=services">Services</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=portfolio">Portfolio</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=pricing">Pricing</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link btn text-white px-4 rounded-pill ms-2" style="background-color: #FF653F;"
                        href="index.php?page=contact">
                        Contact Me
                    </a>
                </li>

                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="admin/dashboard.php">Dashboard</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            Logout (<?php echo $_SESSION['username']; ?>)
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>

<!-- CONTENT -->
<div class="main-content">
    <!-- Isi halaman kamu di sini -->
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    window.addEventListener("scroll", function () {
        const navbar = document.getElementById("mainNavbar");

        if (window.scrollY > 50) {
            navbar.classList.add("scrolled");
        } else {
            navbar.classList.remove("scrolled");
        }
    });
</script>

</body>
</html>