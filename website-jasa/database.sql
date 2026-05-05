-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 04, 2026 at 08:35 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jasa_website`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetPopularPackages` ()   BEGIN
    SELECT * FROM pricings WHERE is_popular = TRUE LIMIT 1;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `MarkContactAsRead` (IN `contact_id` INT)   BEGIN
    UPDATE contacts SET is_read = TRUE WHERE id = contact_id;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `phone`, `subject`, `message`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 'Budi Santoso', 'budi@example.com', '081234567890', 'Info Harga Website Company Profile', 'Saya tertarik untuk membuat website company profile untuk perusahaan saya. Mohon info detail harganya.', 0, '2026-05-02 01:20:56', '2026-05-02 01:20:56'),
(2, 'Siti Aisyah', 'siti@example.com', '081298765432', 'Request E-Commerce', 'Butuh toko online untuk produk fashion. Ada berapa pilihan paketnya? Terima kasih.', 1, '2026-05-02 01:20:56', '2026-05-02 01:20:56'),
(3, 'Ahmad Fauzi', 'ahmad@example.com', '085678901234', 'Perbaikan Website', 'Website saya error setelah update plugin. Mohon bantuannya.', 0, '2026-05-02 01:20:56', '2026-05-02 01:20:56');

-- --------------------------------------------------------

--
-- Stand-in structure for view `dashboard_stats`
-- (See below for the actual view)
--
CREATE TABLE `dashboard_stats` (
`total_portfolios` bigint
,`total_pricings` bigint
,`total_services` bigint
,`total_users` bigint
,`unread_messages` bigint
);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `portfolios`
--

CREATE TABLE `portfolios` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `completion_date` date DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `portfolios`
--

INSERT INTO `portfolios` (`id`, `title`, `description`, `category`, `image`, `client_name`, `completion_date`, `url`, `created_at`, `updated_at`) VALUES
(1, 'E-Commerce Store - Fashion Retail', 'Platform e-commerce lengkap untuk brand fashion lokal dengan fitur filter produk, wishlist, sistem review, dan integrasi pembayaran digital.', 'E-Commerce', 'uploads/portfolio/ecommerce1.jpg', 'FashionHub Indonesia', '2024-01-15', 'https://fashionhub.com', '2026-05-02 01:20:56', '2026-05-02 01:20:56'),
(2, 'Company Profile - Tech Startup', 'Website company profile modern untuk perusahaan teknologi startup dengan animasi interaktif dan timeline perkembangan perusahaan.', 'Company Profile', 'uploads/portfolio/company1.jpg', 'TechInnovate', '2024-02-20', 'https://techinnovate.com', '2026-05-02 01:20:56', '2026-05-02 01:20:56'),
(3, 'Learning Management System', 'Sistem manajemen pembelajaran online untuk universitas dengan fitur video course, quiz, sertifikat digital, dan dashboard murid.', 'Web Application', 'uploads/portfolio/lms.jpg', 'Universitas Maju', '2024-01-10', 'https://elearning.univ.ac.id', '2026-05-02 01:20:56', '2026-05-02 01:20:56'),
(4, 'Restaurant Website & Online Order', 'Website restoran dengan sistem pemesanan online, menu digital, reservasi meja, dan integrasi dengan sistem POS restaurant.', 'Restaurant', 'uploads/portfolio/resto.jpg', 'Warung Makan Enak', '2024-02-01', 'https://warungenak.com', '2026-05-02 01:20:56', '2026-05-02 01:20:56'),
(5, 'Property Listing Portal', 'Portal properti dengan fitur pencarian advanced, filter harga & lokasi, virtual tour, dan form kontak agen properti.', 'Real Estate', 'uploads/portfolio/property.jpg', 'PropertiKita', '2024-01-25', 'https://propertikita.com', '2026-05-02 01:20:56', '2026-05-02 01:20:56'),
(6, 'Healthcare Appointment System', 'Sistem booking appointment online untuk klinik kesehatan dengan fitur jadwal dokter, rekam medis online, dan reminder via WhatsApp.', 'Healthcare', 'uploads/portfolio/healthcare.jpg', 'Klinik Sehat', '2024-02-10', 'https://kliniksehat.com', '2026-05-02 01:20:56', '2026-05-02 01:20:56');

-- --------------------------------------------------------

--
-- Table structure for table `pricings`
--

CREATE TABLE `pricings` (
  `id` bigint UNSIGNED NOT NULL,
  `package_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `features` json NOT NULL,
  `is_popular` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pricings`
--

INSERT INTO `pricings` (`id`, `package_name`, `price`, `features`, `is_popular`, `created_at`, `updated_at`) VALUES
(1, 'Basic', '2500000.00', '[\"Website 5 Halaman\", \"Responsive Design\", \"Contact Form\", \"Basic SEO\", \"1 Month Support\"]', 0, '2026-05-02 01:20:56', '2026-05-02 01:20:56'),
(2, 'Business', '5000000.00', '[\"Website 10 Halaman\", \"Responsive Design\", \"CMS (Content Management System)\", \"Advanced SEO\", \"3 Month Support\", \"Free Domain & Hosting 1 Tahun\"]', 1, '2026-05-02 01:20:56', '2026-05-02 01:20:56'),
(3, 'Premium', '10000000.00', '[\"Unlimited Halaman\", \"Custom Web Application\", \"E-Commerce Integration\", \"Advanced SEO\", \"12 Month Support\", \"Free Domain & Hosting 1 Tahun\", \"Maintenance 1 Tahun\"]', 0, '2026-05-02 01:20:56', '2026-05-02 01:20:56'),
(4, 'Enterprise', '25000000.00', '[\"Custom Development\", \"API Integration\", \"Mobile App (Optional)\", \"Dedicated Team\", \"24/7 Priority Support\", \"Lifetime Updates\", \"SLA Guarantee\"]', 0, '2026-05-02 01:20:56', '2026-05-02 01:20:56');

-- --------------------------------------------------------

--
-- Stand-in structure for view `recent_portfolios`
-- (See below for the actual view)
--
CREATE TABLE `recent_portfolios` (
`category` varchar(100)
,`client_name` varchar(255)
,`completion_date` date
,`created_at` timestamp
,`description` text
,`id` bigint unsigned
,`image` varchar(255)
,`title` varchar(255)
,`updated_at` timestamp
,`url` varchar(255)
);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `title`, `description`, `icon`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Web Development', 'Kami mengembangkan website company profile, landing page, portal berita, hingga aplikasi web kompleks dengan teknologi terbaru seperti Laravel, React, dan Vue.js. Semua website yang kami buat responsif dan SEO friendly.', 'fa-code', 'uploads/services/webdev.jpg', '2026-05-02 01:20:56', '2026-05-02 01:20:56'),
(2, 'E-Commerce Solution', 'Solusi toko online lengkap dengan fitur manajemen produk, keranjang belanja, payment gateway integration (Midtrans, Xendit, Stripe), shipping management, dan sistem invoice otomatis.', 'fa-cart-shopping', 'uploads/services/ecommerce.jpg', '2026-05-02 01:20:56', '2026-05-02 01:20:56'),
(3, 'Mobile Responsive Design', 'Desain website yang optimal di semua perangkat (desktop, tablet, mobile) dengan pendekatan mobile-first. Memastikan pengalaman pengguna yang konsisten di berbagai ukuran layar.', 'fa-mobile-screen', 'uploads/services/mobile.jpg', '2026-05-02 01:20:56', '2026-05-02 01:20:56'),
(4, 'SEO Optimization', 'Optimasi website untuk mesin pencari Google. Kami melakukan riset keyword, on-page SEO, technical SEO, dan content strategy untuk meningkatkan peringkat website Anda.', 'fa-magnifying-glass-chart', 'uploads/services/seo.jpg', '2026-05-02 01:20:56', '2026-05-02 01:20:56'),
(5, 'Website Maintenance', 'Layanan maintenance dan support website secara berkala termasuk update keamanan, backup data, monitoring performa, dan technical support 24/7 untuk website Anda.', 'fa-gear', 'uploads/services/maintenance.jpg', '2026-05-02 01:20:56', '2026-05-02 01:20:56'),
(6, 'Custom Web Application', 'Pengembangan aplikasi web custom sesuai kebutuhan bisnis Anda, termasuk sistem inventory, HRIS, CRM, dashboard analytics, dan aplikasi internal lainnya.', 'fa-laptop-code', 'uploads/services/custom.jpg', '2026-05-02 01:20:56', '2026-05-02 01:20:56');

--
-- Triggers `services`
--
DELIMITER $$
CREATE TRIGGER `before_service_delete` BEFORE DELETE ON `services` FOR EACH ROW BEGIN
    INSERT INTO `service_logs` (service_id, title, action, deleted_at)
    VALUES (OLD.id, OLD.title, 'DELETED', NOW());
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` bigint UNSIGNED NOT NULL,
  `client_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_company` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` tinyint UNSIGNED NOT NULL DEFAULT '5',
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `client_name`, `client_position`, `client_company`, `client_image`, `content`, `rating`, `is_approved`, `created_at`, `updated_at`) VALUES
(1, 'Budi Santoso', 'CEO', 'TechInnovate', NULL, 'Tim WebPro sangat profesional dan responsif. Website yang dibuat melebihi ekspektasi kami. Prosesnya cepat dan hasilnya memuaskan!', 5, 1, '2026-05-02 01:20:56', '2026-05-02 01:20:56'),
(2, 'Dewi Lestari', 'Marketing Manager', 'FashionHub', NULL, 'E-commerce website kami berjalan lancar. Penjualan meningkat 200% setelah menggunakan platform dari WebPro. Highly recommended!', 5, 1, '2026-05-02 01:20:56', '2026-05-02 01:20:56'),
(3, 'Rudi Hartono', 'Owner', 'Warung Makan Enak', NULL, 'Sistem online order sangat membantu bisnis restoran kami. Pelanggan jadi lebih mudah memesan. Supportnya juga cepat tanggap.', 4, 1, '2026-05-02 01:20:56', '2026-05-02 01:20:56'),
(4, 'Sari Wijaya', 'Director', 'PropertiKita', NULL, 'Portal properti yang dibuat sangat modern dan mudah digunakan. Fitur pencariannya advanced dan membantu klien menemukan properti impian mereka.', 5, 0, '2026-05-02 01:20:56', '2026-05-02 01:20:56');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','user') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'admin@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NULL, '2026-05-02 01:20:56', '2026-05-02 01:20:56'),
(2, 'Regular User', 'user@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', NULL, '2026-05-02 01:20:56', '2026-05-02 01:20:56'),
(3, 'John Doe', 'john@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', NULL, '2026-05-02 01:20:56', '2026-05-02 01:20:56'),
(4, 'Jane Smith', 'jane@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', NULL, '2026-05-02 01:20:56', '2026-05-02 01:20:56');

-- --------------------------------------------------------

--
-- Structure for view `dashboard_stats`
--
DROP TABLE IF EXISTS `dashboard_stats`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `dashboard_stats`  AS SELECT (select count(0) from `services`) AS `total_services`, (select count(0) from `portfolios`) AS `total_portfolios`, (select count(0) from `pricings`) AS `total_pricings`, (select count(0) from `users`) AS `total_users`, (select count(0) from `contacts` where (`contacts`.`is_read` = 0)) AS `unread_messages``unread_messages`  ;

-- --------------------------------------------------------

--
-- Structure for view `recent_portfolios`
--
DROP TABLE IF EXISTS `recent_portfolios`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `recent_portfolios`  AS SELECT `portfolios`.`id` AS `id`, `portfolios`.`title` AS `title`, `portfolios`.`description` AS `description`, `portfolios`.`category` AS `category`, `portfolios`.`image` AS `image`, `portfolios`.`client_name` AS `client_name`, `portfolios`.`completion_date` AS `completion_date`, `portfolios`.`url` AS `url`, `portfolios`.`created_at` AS `created_at`, `portfolios`.`updated_at` AS `updated_at` FROM `portfolios` ORDER BY `portfolios`.`created_at` DESC LIMIT 0, 66  ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contacts_is_read_index` (`is_read`),
  ADD KEY `contacts_email_index` (`email`),
  ADD KEY `idx_contacts_created` (`created_at` DESC);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `portfolios`
--
ALTER TABLE `portfolios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `portfolios_category_index` (`category`),
  ADD KEY `idx_portfolios_created` (`created_at` DESC);

--
-- Indexes for table `pricings`
--
ALTER TABLE `pricings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pricings_is_popular_index` (`is_popular`),
  ADD KEY `idx_pricings_price` (`price`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_services_created` (`created_at`);
ALTER TABLE `services` ADD FULLTEXT KEY `services_search_index` (`title`,`description`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `testimonials_is_approved_index` (`is_approved`),
  ADD KEY `testimonials_rating_index` (`rating`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `users_email_index` (`email`),
  ADD KEY `idx_users_role_created` (`role`,`created_at`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `portfolios`
--
ALTER TABLE `portfolios`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pricings`
--
ALTER TABLE `pricings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
