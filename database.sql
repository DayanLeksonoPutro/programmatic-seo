-- =====================================================
-- Programmatic SEO WordPress Plugin - Database Schema
-- =====================================================

-- -----------------------------------------------------
-- Table: wp_cities
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `wp_cities` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `province` varchar(100) NOT NULL DEFAULT 'Jawa Timur',
  `city_name` varchar(100) NOT NULL,
  `city_slug` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `city_slug` (`city_slug`),
  KEY `province` (`province`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table: wp_businesses
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `wp_businesses` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `city_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text,
  `phone` varchar(50) DEFAULT NULL,
  `whatsapp` varchar(50) DEFAULT NULL,
  `rating` decimal(2,1) DEFAULT 0.0,
  `lat` decimal(10,8) DEFAULT NULL,
  `lng` decimal(11,8) DEFAULT NULL,
  `description` text,
  `website` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `city_id` (`city_id`),
  KEY `is_active` (`is_active`),
  CONSTRAINT `fk_business_city` FOREIGN KEY (`city_id`) REFERENCES `wp_cities` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Insert Data: Kota/Kabupaten Jawa Timur (38 cities)
-- -----------------------------------------------------
INSERT INTO `wp_cities` (`province`, `city_name`, `city_slug`) VALUES
('Jawa Timur', 'Surabaya', 'surabaya'),
('Jawa Timur', 'Malang', 'malang'),
('Jawa Timur', 'Sidoarjo', 'sidoarjo'),
('Jawa Timur', 'Gresik', 'gresik'),
('Jawa Timur', 'Mojokerto', 'mojokerto'),
('Jawa Timur', 'Pasuruan', 'pasuruan'),
('Jawa Timur', 'Batu', 'batu'),
('Jawa Timur', 'Blitar', 'blitar'),
('Jawa Timur', 'Kediri', 'kediri'),
('Jawa Timur', 'Madiun', 'madiun'),
('Jawa Timur', 'Probolinggo', 'probolinggo'),
('Jawa Timur', 'Bondowoso', 'bondowoso'),
('Jawa Timur', 'Jember', 'jember'),
('Jawa Timur', 'Banyuwangi', 'banyuwangi'),
('Jawa Timur', 'Situbondo', 'situbondo'),
('Jawa Timur', 'Lumajang', 'lumajang'),
('Jawa Timur', 'Bangkalan', 'bangkalan'),
('Jawa Timur', 'Sampang', 'sampang'),
('Jawa Timur', 'Pamekasan', 'pamekasan'),
('Jawa Timur', 'Sumenep', 'sumenep'),
('Jawa Timur', 'Tuban', 'tuban'),
('Jawa Timur', 'Lamongan', 'lamongan'),
('Jawa Timur', 'Bojonegoro', 'bojonegoro'),
('Jawa Timur', 'Ngawi', 'ngawi'),
('Jawa Timur', 'Magetan', 'magetan'),
('Jawa Timur', 'Ponorogo', 'ponorogo'),
('Jawa Timur', 'Pacitan', 'pacitan'),
('Jawa Timur', 'Trenggalek', 'trenggalek'),
('Jawa Timur', 'Tulungagung', 'tulungagung'),
('Jawa Timur', 'Nganjuk', 'nganjuk'),
('Jawa Timur', 'Jombang', 'jombang'),
('Jawa Timur', 'Mojokerto', 'mojokerto-kab'),
('Jawa Timur', 'Pasuruan', 'pasuruan-kab'),
('Jawa Timur', 'Probolinggo', 'probolinggo-kab'),
('Jawa Timur', 'Malang', 'malang-kab'),
('Jawa Timur', 'Kediri', 'kediri-kab'),
('Jawa Timur', 'Blitar', 'blitar-kab'),
('Jawa Timur', 'Madiun', 'madiun-kab');

-- -----------------------------------------------------
-- Sample Data: Businesses (Jasa AC examples)
-- -----------------------------------------------------
INSERT INTO `wp_businesses` (`city_id`, `name`, `address`, `phone`, `whatsapp`, `rating`, `lat`, `lng`, `description`) VALUES
-- Bondowoso
((SELECT id FROM wp_cities WHERE city_slug = 'bondowoso'), 'AC Bondowoso Sejahtera', 'Jl. A Yani No. 45, Bondowoso', '0332-123456', '6281234567890', 4.5, -7.913459, 113.821059, 'Service AC profesional di Bondowoso dengan teknisi berpengalaman. Melayani perbaikan, cuci AC, dan instalasi.'),
((SELECT id FROM wp_cities WHERE city_slug = 'bondowoso'), 'Mitra AC Bondowoso', 'Jl. Sudirman No. 78, Bondowoso', '0332-234567', '6281234567891', 4.3, -7.915000, 113.823000, 'Ahli AC rumah dan kantor di Bondowoso. Garansi service 30 hari.'),

-- Jember
((SELECT id FROM wp_cities WHERE city_slug = 'jember'), 'Service AC Jember Utama', 'Jl. Gajah Mada No. 123, Jember', '0331-345678', '6281234567892', 4.7, -8.172119, 113.699323, 'Service AC terpercaya di Jember. Cepat, murah, dan bergaransi. Melayani semua merk AC.'),
((SELECT id FROM wp_cities WHERE city_slug = 'jember'), 'Teknisi AC Jember', 'Jl. Diponegoro No. 56, Jember', '0331-456789', '6281234567893', 4.4, -8.175000, 113.702000, 'Spesialis perbaikan AC di Jember. Ready 24 jam untuk emergency service.'),

-- Banyuwangi
((SELECT id FROM wp_cities WHERE city_slug = 'banyuwangi'), 'AC Banyuwangi Expert', 'Jl. A Yani No. 89, Banyuwangi', '0333-567890', '6281234567894', 4.6, -8.219233, 114.369227, 'Jasa service AC terbaik di Banyuwangi. Teknisi certified, harga transparan.'),
((SELECT id FROM wp_cities WHERE city_slug = 'banyuwangi'), 'Mitra AC Banyuwangi', 'Jl. Sudirman No. 34, Banyuwangi', '0333-678901', '6281234567895', 4.2, -8.222000, 114.372000, 'Service AC rumah tangga dan komersial di Banyuwangi. Free survey.'),

-- Surabaya
((SELECT id FROM wp_cities WHERE city_slug = 'surabaya'), 'AC Surabaya Center', 'Jl. Raya Darmo No. 123, Surabaya', '031-789012', '6281234567896', 4.8, -7.257472, 112.752088, 'Service AC terlengkap di Surabaya. Melayani perbaikan, cuci AC, freon, dan instalasi baru.'),
((SELECT id FROM wp_cities WHERE city_slug = 'surabaya'), 'Teknisi AC Surabaya', 'Jl. Mayjen Sungkono No. 45, Surabaya', '031-890123', '6281234567897', 4.5, -7.290000, 112.710000, 'Professional AC service di Surabaya. Garansi 60 hari untuk setiap perbaikan.'),

-- Malang
((SELECT id FROM wp_cities WHERE city_slug = 'malang'), 'AC Malang Service', 'Jl. Kawi No. 67, Malang', '0341-901234', '6281234567898', 4.6, -7.977838, 112.634056, 'Jasa service AC berkualitas di Malang. Teknisi ramah dan berpengalaman.'),
((SELECT id FROM wp_cities WHERE city_slug = 'malang'), 'Mitra AC Malang', 'Jl. Sudirman No. 12, Malang', '0341-012345', '6281234567899', 4.4, -7.980000, 112.637000, 'Service AC 24 jam di Malang. Siap datang ke lokasi dalam 1 jam.');
