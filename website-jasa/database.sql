CREATE DATABASE IF NOT EXISTS web_jasa;
USE web_jasa;

-- Table: contacts
CREATE TABLE contacts (
  id int NOT NULL,
  name varchar(100) NOT NULL,
  email varchar(100) NOT NULL,
  phone varchar(20) DEFAULT NULL,
  message text,
  created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO contacts (id, name, email, phone, message, created_at) VALUES
(1, 'daffa hafisd', 'daffahafisd11@gmail.com', '111', '111', '2026-05-04 00:06:31'),
(2, 'daffa hafisd', 'daffahafisd11@gmail.com', '089504005464', 'web error', '2026-05-04 00:09:04'),
(3, 'daffa hafisd', 'daffahafisd11@gmail.com', '111', 'jklojn', '2026-05-04 00:29:16'),
(4, 'daffa hafisd', 'daffahafisd11@gmail.com', '111', 'error', '2026-05-05 06:57:24');

ALTER TABLE contacts ADD PRIMARY KEY (id);
ALTER TABLE contacts MODIFY id int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

-- Table: portfolio
CREATE TABLE portfolio (
  id int NOT NULL,
  title varchar(100) NOT NULL,
  description text,
  image_url varchar(255) DEFAULT NULL,
  client_name varchar(100) DEFAULT NULL,
  created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO portfolio (id, title, description, image_url, client_name, created_at) VALUES
(1, 'Nasgorin-Aja', 'Landing Page Nasgorin-Aja', '1777852993_69f7e2415440e.png', 'Nasgorin', '2026-05-02 02:11:26'),
(5, 'Waterfall Tirto wening', 'Website untuk air terjun Tirto Wening yang berada di Kab. Semarang', '1777852968_69f7e228207d5.png', 'Tirto Wening', '2026-05-03 23:56:24'),
(6, 'vtfytf', 'gbtg', '1777964511_69f995df0a7f9.jpg', 'tbtt', '2026-05-05 07:01:51');

ALTER TABLE portfolio ADD PRIMARY KEY (id);
ALTER TABLE portfolio MODIFY id int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

-- Table: pricing
CREATE TABLE pricing (
  id int NOT NULL,
  package_name varchar(50) NOT NULL,
  price decimal(10,2) NOT NULL,
  features text,
  is_popular tinyint(1) DEFAULT '0',
  created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO pricing (id, package_name, price, features, is_popular, created_at) VALUES
(1, 'Basic', '2500000.00', 'Website 5 halaman, Responsive design, Contact form, 1 tahun domain', 0, '2026-05-02 02:11:26'),
(2, 'Business', '5000000.00', 'Website 10 halaman, Responsive design, CMS sederhana, SEO friendly', 1, '2026-05-02 02:11:26'),
(3, 'Premium', '10000000.00', 'Website unlimited, E-commerce, Database, Support 24/7', 0, '2026-05-02 02:11:26');

ALTER TABLE pricing ADD PRIMARY KEY (id);
ALTER TABLE pricing MODIFY id int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

-- Table: services
CREATE TABLE services (
  id int NOT NULL,
  title varchar(100) NOT NULL,
  description text,
  icon varchar(50) DEFAULT NULL,
  created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO services (id, title, description, icon, created_at) VALUES
(1, 'Web ', 'Membuat website profesional dan responsif', '💻', '2026-05-02 02:11:26'),
(2, 'UI/UX Design', 'Desain antarmuka yang menarik dan user-friendly', '🎨', '2026-05-02 02:11:26'),
(3, 'SEO Optimization', 'Optimasi website untuk mesin pencari', '📈', '2026-05-02 02:11:26'),
(4, 'E-commerce Solution', 'Toko online dengan fitur lengkap', '🛒', '2026-05-02 02:11:26');

ALTER TABLE services ADD PRIMARY KEY (id);
ALTER TABLE services MODIFY id int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

-- Table: users
CREATE TABLE users (
  id int NOT NULL,
  username varchar(50) NOT NULL,
  password varchar(255) NOT NULL,
  role enum('admin','user') DEFAULT 'user',
  created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO users (id, username, password, role, created_at) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin', '2026-05-02 02:11:26'),
(2, 'user', 'ee11cbb19052e40b07aac0ca060c23ee', 'user', '2026-05-02 02:11:26');

ALTER TABLE users ADD PRIMARY KEY (id);
ALTER TABLE users ADD UNIQUE KEY username (username);
ALTER TABLE users MODIFY id int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
