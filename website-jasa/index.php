<?php
require_once 'config/database.php';

// Auth sudah termasuk dalam database.php jika menggunakan solusi sebelumnya
// Tapi pastikan session dimulai
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$allowed_pages = ['home', 'about', 'services', 'portfolio', 'pricing', 'contact'];

if(!in_array($page, $allowed_pages)) {
    $page = 'home';
}

include 'includes/header.php';

// Load page content
$page_file = "pages/$page.php";
if(file_exists($page_file)) {
    include $page_file;
} else {
    include 'pages/home.php';
}

include 'includes/footer.php';
?>