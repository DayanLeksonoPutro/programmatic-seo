-- =====================================================
-- Programmatic SEO WordPress Plugin - Database Schema
-- Indonesia: 38 Provinces, 416 Kabupaten, 98 Kota (2024)
-- =====================================================

-- -----------------------------------------------------
-- Table: wp_cities
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `wp_cities` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `province` varchar(100) NOT NULL,
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

-- =====================================================
-- INSERT ALL KABUPATEN/KOTA INDONESIA (514 cities)
-- =====================================================

-- -----------------------------------------------------
-- 1. NANGGROE ACEH DARUSSALAM (18 Kabupaten, 5 Kota)
-- -----------------------------------------------------
INSERT INTO `wp_cities` (`province`, `city_name`, `city_slug`) VALUES
('Nanggroe Aceh Darussalam', 'Aceh Barat', 'aceh-barat'),
('Nanggroe Aceh Darussalam', 'Aceh Barat Daya', 'aceh-barat-daya'),
('Nanggroe Aceh Darussalam', 'Aceh Besar', 'aceh-besar'),
('Nanggroe Aceh Darussalam', 'Aceh Jaya', 'aceh-jaya'),
('Nanggroe Aceh Darussalam', 'Aceh Selatan', 'aceh-selatan'),
('Nanggroe Aceh Darussalam', 'Aceh Singkil', 'aceh-singkil'),
('Nanggroe Aceh Darussalam', 'Aceh Tamiang', 'aceh-tamiang'),
('Nanggroe Aceh Darussalam', 'Aceh Tengah', 'aceh-tengah'),
('Nanggroe Aceh Darussalam', 'Aceh Tenggara', 'aceh-tenggara'),
('Nanggroe Aceh Darussalam', 'Aceh Timur', 'aceh-timur'),
('Nanggroe Aceh Darussalam', 'Aceh Utara', 'aceh-utara'),
('Nanggroe Aceh Darussalam', 'Bener Meriah', 'bener-meriah'),
('Nanggroe Aceh Darussalam', 'Bireuen', 'bireuen'),
('Nanggroe Aceh Darussalam', 'Gayo Lues', 'gayo-lues'),
('Nanggroe Aceh Darussalam', 'Nagan Raya', 'nagan-raya'),
('Nanggroe Aceh Darussalam', 'Pidie', 'pidie'),
('Nanggroe Aceh Darussalam', 'Pidie Jaya', 'pidie-jaya'),
('Nanggroe Aceh Darussalam', 'Simeulue', 'simeulue'),
('Nanggroe Aceh Darussalam', 'Banda Aceh', 'banda-aceh'),
('Nanggroe Aceh Darussalam', 'Langsa', 'langsa'),
('Nanggroe Aceh Darussalam', 'Lhokseumawe', 'lhokseumawe'),
('Nanggroe Aceh Darussalam', 'Sabang', 'sabang'),
('Nanggroe Aceh Darussalam', 'Subulussalam', 'subulussalam');

-- -----------------------------------------------------
-- 2. SUMATERA UTARA (25 Kabupaten, 8 Kota)
-- -----------------------------------------------------
INSERT INTO `wp_cities` (`province`, `city_name`, `city_slug`) VALUES
('Sumatera Utara', 'Asahan', 'asahan'),
('Sumatera Utara', 'Batu Bara', 'batu-bara'),
('Sumatera Utara', 'Dairi', 'dairi'),
('Sumatera Utara', 'Deli Serdang', 'deli-serdang'),
('Sumatera Utara', 'Humbang Hasundutan', 'humbang-hasundutan'),
('Sumatera Utara', 'Karo', 'karo'),
('Sumatera Utara', 'Labuhanbatu', 'labuhanbatu'),
('Sumatera Utara', 'Labuhanbatu Selatan', 'labuhanbatu-selatan'),
('Sumatera Utara', 'Labuhanbatu Utara', 'labuhanbatu-utara'),
('Sumatera Utara', 'Langkat', 'langkat'),
('Sumatera Utara', 'Mandailing Natal', 'mandailing-natal'),
('Sumatera Utara', 'Nias', 'nias'),
('Sumatera Utara', 'Nias Barat', 'nias-barat'),
('Sumatera Utara', 'Nias Selatan', 'nias-selatan'),
('Sumatera Utara', 'Nias Utara', 'nias-utara'),
('Sumatera Utara', 'Padang Lawas', 'padang-lawas'),
('Sumatera Utara', 'Padang Lawas Utara', 'padang-lawas-utara'),
('Sumatera Utara', 'Pakpak Bharat', 'pakpak-bharat'),
('Sumatera Utara', 'Samosir', 'samosir'),
('Sumatera Utara', 'Serdang Bedagai', 'serdang-bedagai'),
('Sumatera Utara', 'Simalungun', 'simalungun'),
('Sumatera Utara', 'Tapanuli Selatan', 'tapanuli-selatan'),
('Sumatera Utara', 'Tapanuli Tengah', 'tapanuli-tengah'),
('Sumatera Utara', 'Tapanuli Utara', 'tapanuli-utara'),
('Sumatera Utara', 'Toba Samosir', 'toba-samosir'),
('Sumatera Utara', 'Binjai', 'binjai'),
('Sumatera Utara', 'Gunungsitoli', 'gunungsitoli'),
('Sumatera Utara', 'Medan', 'medan'),
('Sumatera Utara', 'Padangsidimpuan', 'padangsidimpuan'),
('Sumatera Utara', 'Pematangsiantar', 'pematangsiantar'),
('Sumatera Utara', 'Sibolga', 'sibolga'),
('Sumatera Utara', 'Tanjungbalai', 'tanjungbalai'),
('Sumatera Utara', 'Tebing Tinggi', 'tebing-tinggi');

-- -----------------------------------------------------
-- 3. SUMATERA BARAT (12 Kabupaten, 7 Kota)
-- -----------------------------------------------------
INSERT INTO `wp_cities` (`province`, `city_name`, `city_slug`) VALUES
('Sumatera Barat', 'Agam', 'agam'),
('Sumatera Barat', 'Dharmasraya', 'dharmasraya'),
('Sumatera Barat', 'Kepulauan Mentawai', 'kepulauan-mentawai'),
('Sumatera Barat', 'Lima Puluh Kota', 'lima-puluh-kota'),
('Sumatera Barat', 'Padang Pariaman', 'padang-pariaman'),
('Sumatera Barat', 'Pasaman', 'pasaman'),
('Sumatera Barat', 'Pasaman Barat', 'pasaman-barat'),
('Sumatera Barat', 'Pesisir Selatan', 'pesisir-selatan'),
('Sumatera Barat', 'Sijunjung', 'sijunjung'),
('Sumatera Barat', 'Solok', 'solok-kab'),
('Sumatera Barat', 'Solok Selatan', 'solok-selatan'),
('Sumatera Barat', 'Tanah Datar', 'tanah-datar'),
('Sumatera Barat', 'Bukittinggi', 'bukittinggi'),
('Sumatera Barat', 'Padang', 'padang'),
('Sumatera Barat', 'Padangpanjang', 'padangpanjang'),
('Sumatera Barat', 'Pariaman', 'pariaman'),
('Sumatera Barat', 'Payakumbuh', 'payakumbuh'),
('Sumatera Barat', 'Sawahlunto', 'sawahlunto'),
('Sumatera Barat', 'Solok', 'solok-kota');

-- -----------------------------------------------------
-- 4. RIAU (10 Kabupaten, 2 Kota)
-- -----------------------------------------------------
INSERT INTO `wp_cities` (`province`, `city_name`, `city_slug`) VALUES
('Riau', 'Bengkalis', 'bengkalis'),
('Riau', 'Indragiri Hilir', 'indragiri-hilir'),
('Riau', 'Indragiri Hulu', 'indragiri-hulu'),
('Riau', 'Kampar', 'kampar'),
('Riau', 'Kepulauan Meranti', 'kepulauan-meranti'),
('Riau', 'Kuantan Singingi', 'kuantan-singingi'),
('Riau', 'Pelalawan', 'pelalawan'),
('Riau', 'Rokan Hilir', 'rokan-hilir'),
('Riau', 'Rokan Hulu', 'rokan-hulu'),
('Riau', 'Siak', 'siak'),
('Riau', 'Dumai', 'dumai'),
('Riau', 'Pekanbaru', 'pekanbaru');

-- -----------------------------------------------------
-- 5. KEPULAUAN RIAU (5 Kabupaten, 2 Kota)
-- -----------------------------------------------------
INSERT INTO `wp_cities` (`province`, `city_name`, `city_slug`) VALUES
('Kepulauan Riau', 'Bintan', 'bintan'),
('Kepulauan Riau', 'Karimun', 'karimun'),
('Kepulauan Riau', 'Kepulauan Anambas', 'kepulauan-anambas'),
('Kepulauan Riau', 'Lingga', 'lingga'),
('Kepulauan Riau', 'Natuna', 'natuna'),
('Kepulauan Riau', 'Batam', 'batam'),
('Kepulauan Riau', 'Tanjungpinang', 'tanjungpinang');

-- -----------------------------------------------------
-- 6. JAMBI (9 Kabupaten, 2 Kota)
-- -----------------------------------------------------
INSERT INTO `wp_cities` (`province`, `city_name`, `city_slug`) VALUES
('Jambi', 'Batanghari', 'batanghari'),
('Jambi', 'Bungo', 'bungo'),
('Jambi', 'Kerinci', 'kerinci'),
('Jambi', 'Merangin', 'merangin'),
('Jambi', 'Muaro Jambi', 'muaro-jambi'),
('Jambi', 'Sarolangun', 'sarolangun'),
('Jambi', 'Tanjung Jabung Barat', 'tanjung-jabung-barat'),
('Jambi', 'Tanjung Jabung Timur', 'tanjung-jabung-timur'),
('Jambi', 'Tebo', 'tebo'),
('Jambi', 'Jambi', 'jambi-kota'),
('Jambi', 'Sungai Penuh', 'sungai-penuh');

-- -----------------------------------------------------
-- 7. SUMATERA SELATAN (13 Kabupaten, 4 Kota)
-- -----------------------------------------------------
INSERT INTO `wp_cities` (`province`, `city_name`, `city_slug`) VALUES
('Sumatera Selatan', 'Banyuasin', 'banyuasin'),
('Sumatera Selatan', 'Empat Lawang', 'empat-lawang'),
('Sumatera Selatan', 'Lahat', 'lahat'),
('Sumatera Selatan', 'Muara Enim', 'muara-enim'),
('Sumatera Selatan', 'Musi Banyuasin', 'musi-banyuasin'),
('Sumatera Selatan', 'Musi Rawas', 'musi-rawas'),
('Sumatera Selatan', 'Musi Rawas Utara', 'musi-rawas-utara'),
('Sumatera Selatan', 'Ogan Ilir', 'ogan-ilir'),
('Sumatera Selatan', 'Ogan Komering Ilir', 'ogan-komering-ilir'),
('Sumatera Selatan', 'Ogan Komering Ulu', 'ogan-komering-ulu'),
('Sumatera Selatan', 'Ogan Komering Ulu Selatan', 'ogan-komering-ulu-selatan'),
('Sumatera Selatan', 'Ogan Komering Ulu Timur', 'ogan-komering-ulu-timur'),
('Sumatera Selatan', 'Penukal Abab Lematang Ilir', 'penukal-abab-lematang-ilir'),
('Sumatera Selatan', 'Lubuklinggau', 'lubuklinggau'),
('Sumatera Selatan', 'Pagaralam', 'pagaralam'),
('Sumatera Selatan', 'Palembang', 'palembang'),
('Sumatera Selatan', 'Prabumulih', 'prabumulih');

-- -----------------------------------------------------
-- 8. BENGKULU (9 Kabupaten, 1 Kota)
-- -----------------------------------------------------
INSERT INTO `wp_cities` (`province`, `city_name`, `city_slug`) VALUES
('Bengkulu', 'Bengkulu Selatan', 'bengkulu-selatan'),
('Bengkulu', 'Bengkulu Tengah', 'bengkulu-tengah'),
('Bengkulu', 'Bengkulu Utara', 'bengkulu-utara'),
('Bengkulu', 'Kaur', 'kaur'),
('Bengkulu', 'Kepahiang', 'kepahiang'),
('Bengkulu', 'Lebong', 'lebong'),
('Bengkulu', 'Mukomuko', 'mukomuko'),
('Bengkulu', 'Rejang Lebong', 'rejang-lebong'),
('Bengkulu', 'Seluma', 'seluma'),
('Bengkulu', 'Bengkulu', 'bengkulu-kota');

-- -----------------------------------------------------
-- 9. LAMPUNG (13 Kabupaten, 2 Kota)
-- -----------------------------------------------------
INSERT INTO `wp_cities` (`province`, `city_name`, `city_slug`) VALUES
('Lampung', 'Lampung Barat', 'lampung-barat'),
('Lampung', 'Lampung Selatan', 'lampung-selatan'),
('Lampung', 'Lampung Tengah', 'lampung-tengah'),
('Lampung', 'Lampung Timur', 'lampung-timur'),
('Lampung', 'Lampung Utara', 'lampung-utara'),
('Lampung', 'Mesuji', 'mesuji'),
('Lampung', 'Pesawaran', 'pesawaran'),
('Lampung', 'Pesisir Barat', 'pesisir-barat'),
('Lampung', 'Pringsewu', 'pringsewu'),
('Lampung', 'Tanggamus', 'tanggamus'),
('Lampung', 'Tulang Bawang', 'tulang-bawang'),
('Lampung', 'Tulang Bawang Barat', 'tulang-bawang-barat'),
('Lampung', 'Way Kanan', 'way-kanan'),
('Lampung', 'Bandar Lampung', 'bandar-lampung'),
('Lampung', 'Metro', 'metro');

-- -----------------------------------------------------
-- 10. BANGKA BELITUNG (6 Kabupaten, 1 Kota)
-- -----------------------------------------------------
INSERT INTO `wp_cities` (`province`, `city_name`, `city_slug`) VALUES
('Bangka Belitung', 'Bangka', 'bangka'),
('Bangka Belitung', 'Bangka Barat', 'bangka-barat'),
('Bangka Belitung', 'Bangka Selatan', 'bangka-selatan'),
('Bangka Belitung', 'Bangka Tengah', 'bangka-tengah'),
('Bangka Belitung', 'Belitung', 'belitung'),
('Bangka Belitung', 'Belitung Timur', 'belitung-timur'),
('Bangka Belitung', 'Pangkalpinang', 'pangkalpinang');

-- -----------------------------------------------------
-- 11. DKI JAKARTA (5 Kota Administratif)
-- -----------------------------------------------------
INSERT INTO `wp_cities` (`province`, `city_name`, `city_slug`) VALUES
('DKI Jakarta', 'Jakarta Barat', 'jakarta-barat'),
('DKI Jakarta', 'Jakarta Pusat', 'jakarta-pusat'),
('DKI Jakarta', 'Jakarta Selatan', 'jakarta-selatan'),
('DKI Jakarta', 'Jakarta Timur', 'jakarta-timur'),
('DKI Jakarta', 'Jakarta Utara', 'jakarta-utara'),
('DKI Jakarta', 'Kepulauan Seribu', 'kepulauan-seribu');

-- -----------------------------------------------------
-- 12. JAWA BARAT (18 Kabupaten, 9 Kota)
-- -----------------------------------------------------
INSERT INTO `wp_cities` (`province`, `city_name`, `city_slug`) VALUES
('Jawa Barat', 'Bandung', 'bandung-kab'),
('Jawa Barat', 'Bandung Barat', 'bandung-barat'),
('Jawa Barat', 'Bekasi', 'bekasi-kab'),
('Jawa Barat', 'Bogor', 'bogor-kab'),
('Jawa Barat', 'Ciamis', 'ciamis'),
('Jawa Barat', 'Cianjur', 'cianjur'),
('Jawa Barat', 'Cirebon', 'cirebon-kab'),
('Jawa Barat', 'Garut', 'garut'),
('Jawa Barat', 'Indramayu', 'indramayu'),
('Jawa Barat', 'Karawang', 'karawang'),
('Jawa Barat', 'Kuningan', 'kuningan'),
('Jawa Barat', 'Majalengka', 'majalengka'),
('Jawa Barat', 'Pangandaran', 'pangandaran'),
('Jawa Barat', 'Purwakarta', 'purwakarta'),
('Jawa Barat', 'Subang', 'subang'),
('Jawa Barat', 'Sukabumi', 'sukabumi-kab'),
('Jawa Barat', 'Sumedang', 'sumedang'),
('Jawa Barat', 'Tasikmalaya', 'tasikmalaya-kab'),
('Jawa Barat', 'Bandung', 'bandung-kota'),
('Jawa Barat', 'Banjarsari', 'banjarsari'),
('Jawa Barat', 'Bekasi', 'bekasi-kota'),
('Jawa Barat', 'Bogor', 'bogor-kota'),
('Jawa Barat', 'Cimahi', 'cimahi'),
('Jawa Barat', 'Cirebon', 'cirebon-kota'),
('Jawa Barat', 'Depok', 'depok'),
('Jawa Barat', 'Sukabumi', 'sukabumi-kota'),
('Jawa Barat', 'Tasikmalaya', 'tasikmalaya-kota');

-- -----------------------------------------------------
-- 13. JAWA TENGAH (29 Kabupaten, 6 Kota)
-- -----------------------------------------------------
INSERT INTO `wp_cities` (`province`, `city_name`, `city_slug`) VALUES
('Jawa Tengah', 'Banjarnegara', 'banjarnegara'),
('Jawa Tengah', 'Banyumas', 'banyumas'),
('Jawa Tengah', 'Batang', 'batang'),
('Jawa Tengah', 'Blora', 'blora'),
('Jawa Tengah', 'Boyolali', 'boyolali'),
('Jawa Tengah', 'Brebes', 'brebes'),
('Jawa Tengah', 'Cilacap', 'cilacap'),
('Jawa Tengah', 'Demak', 'demak'),
('Jawa Tengah', 'Grobogan', 'grobogan'),
('Jawa Tengah', 'Jepara', 'jepara'),
('Jawa Tengah', 'Karanganyar', 'karanganyar'),
('Jawa Tengah', 'Kebumen', 'kebumen'),
('Jawa Tengah', 'Kendal', 'kendal'),
('Jawa Tengah', 'Klaten', 'klaten'),
('Jawa Tengah', 'Kudus', 'kudus'),
('Jawa Tengah', 'Magelang', 'magelang-kab'),
('Jawa Tengah', 'Pati', 'pati'),
('Jawa Tengah', 'Pekalongan', 'pekalongan-kab'),
('Jawa Tengah', 'Pemalang', 'pemalang'),
('Jawa Tengah', 'Purbalingga', 'purbalingga'),
('Jawa Tengah', 'Purworejo', 'purworejo'),
('Jawa Tengah', 'Rembang', 'rembang'),
('Jawa Tengah', 'Semarang', 'semarang-kab'),
('Jawa Tengah', 'Sragen', 'sragen'),
('Jawa Tengah', 'Sukoharjo', 'sukoharjo'),
('Jawa Tengah', 'Tegal', 'tegal-kab'),
('Jawa Tengah', 'Temanggung', 'temanggung'),
('Jawa Tengah', 'Wonogiri', 'wonogiri'),
('Jawa Tengah', 'Wonosobo', 'wonosobo'),
('Jawa Tengah', 'Magelang', 'magelang-kota'),
('Jawa Tengah', 'Pekalongan', 'pekalongan-kota'),
('Jawa Tengah', 'Salatiga', 'salatiga'),
('Jawa Tengah', 'Semarang', 'semarang-kota'),
('Jawa Tengah', 'Surakarta', 'surakarta'),
('Jawa Tengah', 'Tegal', 'tegal-kota');

-- -----------------------------------------------------
-- 14. DI YOGYAKARTA (4 Kabupaten, 1 Kota)
-- -----------------------------------------------------
INSERT INTO `wp_cities` (`province`, `city_name`, `city_slug`) VALUES
('DI Yogyakarta', 'Bantul', 'bantul'),
('DI Yogyakarta', 'Gunungkidul', 'gunungkidul'),
('DI Yogyakarta', 'Kulon Progo', 'kulon-progo'),
('DI Yogyakarta', 'Sleman', 'sleman'),
('DI Yogyakarta', 'Yogyakarta', 'yogyakarta');

-- -----------------------------------------------------
-- 15. JAWA TIMUR (38 Kabupaten/Kota)
-- -----------------------------------------------------
INSERT INTO `wp_cities` (`province`, `city_name`, `city_slug`) VALUES
('Jawa Timur', 'Bangkalan', 'bangkalan'),
('Jawa Timur', 'Banyuwangi', 'banyuwangi'),
('Jawa Timur', 'Blitar', 'blitar-kab'),
('Jawa Timur', 'Bojonegoro', 'bojonegoro'),
('Jawa Timur', 'Bondowoso', 'bondowoso'),
('Jawa Timur', 'Gresik', 'gresik'),
('Jawa Timur', 'Jember', 'jember'),
('Jawa Timur', 'Jombang', 'jombang'),
('Jawa Timur', 'Kediri', 'kediri-kab'),
('Jawa Timur', 'Lamongan', 'lamongan'),
('Jawa Timur', 'Lumajang', 'lumajang'),
('Jawa Timur', 'Madiun', 'madiun-kab'),
('Jawa Timur', 'Magetan', 'magetan'),
('Jawa Timur', 'Malang', 'malang-kab'),
('Jawa Timur', 'Mojokerto', 'mojokerto-kab'),
('Jawa Timur', 'Nganjuk', 'nganjuk'),
('Jawa Timur', 'Ngawi', 'ngawi'),
('Jawa Timur', 'Pacitan', 'pacitan'),
('Jawa Timur', 'Pamekasan', 'pamekasan'),
('Jawa Timur', 'Pasuruan', 'pasuruan-kab'),
('Jawa Timur', 'Ponorogo', 'ponorogo'),
('Jawa Timur', 'Probolinggo', 'probolinggo-kab'),
('Jawa Timur', 'Sampang', 'sampang'),
('Jawa Timur', 'Sidoarjo', 'sidoarjo'),
('Jawa Timur', 'Situbondo', 'situbondo'),
('Jawa Timur', 'Sumenep', 'sumenep'),
('Jawa Timur', 'Trenggalek', 'trenggalek'),
('Jawa Timur', 'Tuban', 'tuban'),
('Jawa Timur', 'Tulungagung', 'tulungagung'),
('Jawa Timur', 'Batu', 'batu'),
('Jawa Timur', 'Blitar', 'blitar-kota'),
('Jawa Timur', 'Kediri', 'kediri-kota'),
('Jawa Timur', 'Madiun', 'madiun-kota'),
('Jawa Timur', 'Malang', 'malang-kota'),
('Jawa Timur', 'Mojokerto', 'mojokerto-kota'),
('Jawa Timur', 'Pasuruan', 'pasuruan-kota'),
('Jawa Timur', 'Probolinggo', 'probolinggo-kota'),
('Jawa Timur', 'Surabaya', 'surabaya');

-- -----------------------------------------------------
-- 16. BANTEN (4 Kabupaten, 4 Kota)
-- -----------------------------------------------------
INSERT INTO `wp_cities` (`province`, `city_name`, `city_slug`) VALUES
('Banten', 'Lebak', 'lebak'),
('Banten', 'Pandeglang', 'pandeglang'),
('Banten', 'Serang', 'serang-kab'),
('Banten', 'Tangerang', 'tangerang-kab'),
('Banten', 'Cilegon', 'cilegon'),
('Banten', 'Serang', 'serang-kota'),
('Banten', 'South Tangerang', 'south-tangerang'),
('Banten', 'Tangerang', 'tangerang-kota');

-- -----------------------------------------------------
-- 17. BALI (8 Kabupaten, 1 Kota)
-- -----------------------------------------------------
INSERT INTO `wp_cities` (`province`, `city_name`, `city_slug`) VALUES
('Bali', 'Badung', 'badung'),
('Bali', 'Bangli', 'bangli'),
('Bali', 'Buleleng', 'buleleng'),
('Bali', 'Gianyar', 'gianyar'),
('Bali', 'Jembrana', 'jembrana'),
('Bali', 'Karangasem', 'karangasem'),
('Bali', 'Klungkung', 'klungkung'),
('Bali', 'Tabanan', 'tabanan'),
('Bali', 'Denpasar', 'denpasar');

-- -----------------------------------------------------
-- 18. NUSA TENGGARA BARAT (8 Kabupaten, 2 Kota)
-- -----------------------------------------------------
INSERT INTO `wp_cities` (`province`, `city_name`, `city_slug`) VALUES
('Nusa Tenggara Barat', 'Bima', 'bima-kab'),
('Nusa Tenggara Barat', 'Dompu', 'dompu'),
('Nusa Tenggara Barat', 'Lombok Barat', 'lombok-barat'),
('Nusa Tenggara Barat', 'Lombok Tengah', 'lombok-tengah'),
('Nusa Tenggara Barat', 'Lombok Timur', 'lombok-timur'),
('Nusa Tenggara Barat', 'Lombok Utara', 'lombok-utara'),
('Nusa Tenggara Barat', 'Sumbawa', 'sumbawa'),
('Nusa Tenggara Barat', 'Sumbawa Barat', 'sumbawa-barat'),
('Nusa Tenggara Barat', 'Bima', 'bima-kota'),
('Nusa Tenggara Barat', 'Mataram', 'mataram');

-- -----------------------------------------------------
-- 19. NUSA TENGGARA TIMUR (21 Kabupaten, 1 Kota)
-- -----------------------------------------------------
INSERT INTO `wp_cities` (`province`, `city_name`, `city_slug`) VALUES
('Nusa Tenggara Timur', 'Alor', 'alor'),
('Nusa Tenggara Timur', 'Belu', 'belu'),
('Nusa Tenggara Timur', 'Ende', 'ende'),
('Nusa Tenggara Timur', 'Flores Timur', 'flores-timur'),
('Nusa Tenggara Timur', 'Kupang', 'kupang-kab'),
('Nusa Tenggara Timur', 'Lembata', 'lembata'),
('Nusa Tenggara Timur', 'Malaka', 'malaka'),
('Nusa Tenggara Timur', 'Manggarai', 'manggarai'),
('Nusa Tenggara Timur', 'Manggarai Barat', 'manggarai-barat'),
('Nusa Tenggara Timur', 'Manggarai Timur', 'manggarai-timur'),
('Nusa Tenggara Timur', 'Nagekeo', 'nagekeo'),
('Nusa Tenggara Timur', 'Ngada', 'ngada'),
('Nusa Tenggara Timur', 'Rote Ndao', 'rote-ndao'),
('Nusa Tenggara Timur', 'Sabu Raijua', 'sabu-raijua'),
('Nusa Tenggara Timur', 'Sikka', 'sikka'),
('Nusa Tenggara Timur', 'Sumba Barat', 'sumba-barat'),
('Nusa Tenggara Timur', 'Sumba Barat Daya', 'sumba-barat-daya'),
('Nusa Tenggara Timur', 'Sumba Tengah', 'sumba-tengah'),
('Nusa Tenggara Timur', 'Sumba Timur', 'sumba-timur'),
('Nusa Tenggara Timur', 'Timor Tengah Selatan', 'timor-tengah-selatan'),
('Nusa Tenggara Timur', 'Timor Tengah Utara', 'timor-tengah-utara'),
('Nusa Tenggara Timur', 'Kupang', 'kupang-kota');

-- -----------------------------------------------------
-- 20. KALIMANTAN BARAT (12 Kabupaten, 2 Kota)
-- -----------------------------------------------------
INSERT INTO `wp_cities` (`province`, `city_name`, `city_slug`) VALUES
('Kalimantan Barat', 'Bengkayang', 'bengkayang'),
('Kalimantan Barat', 'Kapuas Hulu', 'kapuas-hulu'),
('Kalimantan Barat', 'Kayong Utara', 'kayong-utara'),
('Kalimantan Barat', 'Ketapang', 'ketapang'),
('Kalimantan Barat', 'Kubu Raya', 'kubu-raya'),
('Kalimantan Barat', 'Landak', 'landak'),
('Kalimantan Barat', 'Melawi', 'melawi'),
('Kalimantan Barat', 'Mempawah', 'mempawah'),
('Kalimantan Barat', 'Sambas', 'sambas'),
('Kalimantan Barat', 'Sanggau', 'sanggau'),
('Kalimantan Barat', 'Sekadau', 'sekadau'),
('Kalimantan Barat', 'Sintang', 'sintang'),
('Kalimantan Barat', 'Pontianak', 'pontianak'),
('Kalimantan Barat', 'Singkawang', 'singkawang');

-- -----------------------------------------------------
-- 21. KALIMANTAN TENGAH (13 Kabupaten, 1 Kota)
-- -----------------------------------------------------
INSERT INTO `wp_cities` (`province`, `city_name`, `city_slug`) VALUES
('Kalimantan Tengah', 'Barito Selatan', 'barito-selatan'),
('Kalimantan Tengah', 'Barito Timur', 'barito-timur'),
('Kalimantan Tengah', 'Barito Utara', 'barito-utara'),
('Kalimantan Tengah', 'Gunung Mas', 'gunung-mas'),
('Kalimantan Tengah', 'Kapuas', 'kapuas'),
('Kalimantan Tengah', 'Katingan', 'katingan'),
('Kalimantan Tengah', 'Kotawaringin Barat', 'kotawaringin-barat'),
('Kalimantan Tengah', 'Kotawaringin Timur', 'kotawaringin-timur'),
('Kalimantan Tengah', 'Lamandau', 'lamandau'),
('Kalimantan Tengah', 'Murung Raya', 'murung-raya'),
('Kalimantan Tengah', 'Pulang Pisau', 'pulang-pisau'),
('Kalimantan Tengah', 'Seruyan', 'seruyan'),
('Kalimantan Tengah', 'Sukamara', 'sukamara'),
('Kalimantan Tengah', 'Palangka Raya', 'palangka-raya');

-- -----------------------------------------------------
-- 22. KALIMANTAN SELATAN (11 Kabupaten, 2 Kota)
-- -----------------------------------------------------
INSERT INTO `wp_cities` (`province`, `city_name`, `city_slug`) VALUES
('Kalimantan Selatan', 'Balangan', 'balangan'),
('Kalimantan Selatan', 'Banjar', 'banjar'),
('Kalimantan Selatan', 'Barito Kuala', 'barito-kuala'),
('Kalimantan Selatan', 'Hulu Sungai Selatan', 'hulu-sungai-selatan'),
('Kalimantan Selatan', 'Hulu Sungai Tengah', 'hulu-sungai-tengah'),
('Kalimantan Selatan', 'Hulu Sungai Utara', 'hulu-sungai-utara'),
('Kalimantan Selatan', 'Kotabaru', 'kotabaru'),
('Kalimantan Selatan', 'Tabalong', 'tabalong'),
('Kalimantan Selatan', 'Tanah Bumbu', 'tanah-bumbu'),
('Kalimantan Selatan', 'Tanah Laut', 'tanah-laut'),
('Kalimantan Selatan', 'Tapin', 'tapin'),
('Kalimantan Selatan', 'Banjarbaru', 'banjarbaru'),
('Kalimantan Selatan', 'Banjarmasin', 'banjarmasin');

-- -----------------------------------------------------
-- 23. KALIMANTAN TIMUR (10 Kabupaten, 3 Kota)
-- -----------------------------------------------------
INSERT INTO `wp_cities` (`province`, `city_name`, `city_slug`) VALUES
('Kalimantan Timur', 'Berau', 'berau'),
('Kalimantan Timur', 'Kutai Barat', 'kutai-barat'),
('Kalimantan Timur', 'Kutai Kartanegara', 'kutai-kartanegara'),
('Kalimantan Timur', 'Kutai Timur', 'kutai-timur'),
('Kalimantan Timur', 'Mahakam Ulu', 'mahakam-ulu'),
('Kalimantan Timur', 'Paser', 'paser'),
('Kalimantan Timur', 'Penajam Paser Utara', 'penajam-paser-utara'),
('Kalimantan Timur', 'Bulungan', 'bulungan'),
('Kalimantan Timur', 'Malinau', 'malinau'),
('Kalimantan Timur', 'Nunukan', 'nunukan'),
('Kalimantan Timur', 'Tana Tidung', 'tana-tidung'),
('Kalimantan Timur', 'Balikpapan', 'balikpapan'),
('Kalimantan Timur', 'Bontang', 'bontang'),
('Kalimantan Timur', 'Samarinda', 'samarinda');

-- -----------------------------------------------------
-- 24. KALIMANTAN UTARA (4 Kabupaten, 1 Kota)
-- -----------------------------------------------------
INSERT INTO `wp_cities` (`province`, `city_name`, `city_slug`) VALUES
('Kalimantan Utara', 'Bulungan', 'bulungan'),
('Kalimantan Utara', 'Malinau', 'malinau'),
('Kalimantan Utara', 'Nunukan', 'nunukan'),
('Kalimantan Utara', 'Tana Tidung', 'tana-tidung'),
('Kalimantan Utara', 'Tanjung Selor', 'tanjung-selor');

-- -----------------------------------------------------
-- 25. SULAWESI UTARA (11 Kabupaten, 4 Kota)
-- -----------------------------------------------------
INSERT INTO `wp_cities` (`province`, `city_name`, `city_slug`) VALUES
('Sulawesi Utara', 'Bolaang Mongondow', 'bolaang-mongondow'),
('Sulawesi Utara', 'Bolaang Mongondow Selatan', 'bolaang-mongondow-selatan'),
('Sulawesi Utara', 'Bolaang Mongondow Timur', 'bolaang-mongondow-timur'),
('Sulawesi Utara', 'Bolaang Mongondow Utara', 'bolaang-mongondow-utara'),
('Sulawesi Utara', 'Kepulauan Sangihe', 'kepulauan-sangihe'),
('Sulawesi Utara', 'Kepulauan Siau Tagulandang Biaro', 'kepulauan-siau-tagulandang-biaro'),
('Sulawesi Utara', 'Kepulauan Talaud', 'kepulauan-talaud'),
('Sulawesi Utara', 'Minahasa', 'minahasa'),
('Sulawesi Utara', 'Minahasa Selatan', 'minahasa-selatan'),
('Sulawesi Utara', 'Minahasa Tenggara', 'minahasa-tenggara'),
('Sulawesi Utara', 'Minahasa Utara', 'minahasa-utara'),
('Sulawesi Utara', 'Bitung', 'bitung'),
('Sulawesi Utara', 'Kotamobagu', 'kotamobagu'),
('Sulawesi Utara', 'Manado', 'manado'),
('Sulawesi Utara', 'Tomohon', 'tomohon');

-- -----------------------------------------------------
-- 26. SULAWESI TENGAH (12 Kabupaten, 1 Kota)
-- -----------------------------------------------------
INSERT INTO `wp_cities` (`province`, `city_name`, `city_slug`) VALUES
('Sulawesi Tengah', 'Banggai', 'banggai'),
('Sulawesi Tengah', 'Banggai Kepulauan', 'banggai-kepulauan'),
('Sulawesi Tengah', 'Banggai Laut', 'banggai-laut'),
('Sulawesi Tengah', 'Buol', 'buol'),
('Sulawesi Tengah', 'Donggala', 'donggala'),
('Sulawesi Tengah', 'Morowali', 'morowali'),
('Sulawesi Tengah', 'Morowali Utara', 'morowali-utara'),
('Sulawesi Tengah', 'Parigi Moutong', 'parigi-moutong'),
('Sulawesi Tengah', 'Poso', 'poso'),
('Sulawesi Tengah', 'Sigi', 'sigi'),
('Sulawesi Tengah', 'Tojo Una-Una', 'tojo-una-una'),
('Sulawesi Tengah', 'Tolitoli', 'tolitoli'),
('Sulawesi Tengah', 'Palu', 'palu');

-- -----------------------------------------------------
-- 27. SULAWESI SELATAN (21 Kabupaten, 3 Kota)
-- -----------------------------------------------------
INSERT INTO `wp_cities` (`province`, `city_name`, `city_slug`) VALUES
('Sulawesi Selatan', 'Bantaeng', 'bantaeng'),
('Sulawesi Selatan', 'Barru', 'barru'),
('Sulawesi Selatan', 'Bone', 'bone'),
('Sulawesi Selatan', 'Bulukumba', 'bulukumba'),
('Sulawesi Selatan', 'Enrekang', 'enrekang'),
('Sulawesi Selatan', 'Gowa', 'gowa'),
('Sulawesi Selatan', 'Jeneponto', 'jeneponto'),
('Sulawesi Selatan', 'Kepulauan Selayar', 'kepulauan-selayar'),
('Sulawesi Selatan', 'Luwu', 'luwu'),
('Sulawesi Selatan', 'Luwu Timur', 'luwu-timur'),
('Sulawesi Selatan', 'Luwu Utara', 'luwu-utara'),
('Sulawesi Selatan', 'Maros', 'maros'),
('Sulawesi Selatan', 'Pangkajene dan Kepulauan', 'pangkajene-dan-kepulauan'),
('Sulawesi Selatan', 'Pinrang', 'pinrang'),
('Sulawesi Selatan', 'Sidenreng Rappang', 'sidenreng-rappang'),
('Sulawesi Selatan', 'Sinjai', 'sinjai'),
('Sulawesi Selatan', 'Soppeng', 'soppeng'),
('Sulawesi Selatan', 'Takalar', 'takalar'),
('Sulawesi Selatan', 'Tana Toraja', 'tana-toraja'),
('Sulawesi Selatan', 'Toraja Utara', 'toraja-utara'),
('Sulawesi Selatan', 'Wajo', 'wajo'),
('Sulawesi Selatan', 'Makassar', 'makassar'),
('Sulawesi Selatan', 'Palopo', 'palopo'),
('Sulawesi Selatan', 'Parepare', 'parepare');

-- -----------------------------------------------------
-- 28. SULAWESI TENGGARA (15 Kabupaten, 2 Kota)
-- -----------------------------------------------------
INSERT INTO `wp_cities` (`province`, `city_name`, `city_slug`) VALUES
('Sulawesi Tenggara', 'Bombana', 'bombana'),
('Sulawesi Tenggara', 'Buton', 'buton'),
('Sulawesi Tenggara', 'Buton Selatan', 'buton-selatan'),
('Sulawesi Tenggara', 'Buton Tengah', 'buton-tengah'),
('Sulawesi Tenggara', 'Buton Utara', 'buton-utara'),
('Sulawesi Tenggara', 'Kolaka', 'kolaka'),
('Sulawesi Tenggara', 'Kolaka Timur', 'kolaka-timur'),
('Sulawesi Tenggara', 'Kolaka Utara', 'kolaka-utara'),
('Sulawesi Tenggara', 'Konawe', 'konawe'),
('Sulawesi Tenggara', 'Konawe Kepulauan', 'konawe-kepulauan'),
('Sulawesi Tenggara', 'Konawe Selatan', 'konawe-selatan'),
('Sulawesi Tenggara', 'Konawe Utara', 'konawe-utara'),
('Sulawesi Tenggara', 'Muna', 'muna'),
('Sulawesi Tenggara', 'Muna Barat', 'muna-barat'),
('Sulawesi Tenggara', 'Wakatobi', 'wakatobi'),
('Sulawesi Tenggara', 'Baubau', 'baubau'),
('Sulawesi Tenggara', 'Kendari', 'kendari');

-- -----------------------------------------------------
-- 29. GORONTALO (5 Kabupaten, 1 Kota)
-- -----------------------------------------------------
INSERT INTO `wp_cities` (`province`, `city_name`, `city_slug`) VALUES
('Gorontalo', 'Boalemo', 'boalemo'),
('Gorontalo', 'Bone Bolango', 'bone-bolango'),
('Gorontalo', 'Gorontalo', 'gorontalo-kab'),
('Gorontalo', 'Gorontalo Utara', 'gorontalo-utara'),
('Gorontalo', 'Pohuwato', 'pohuwato'),
('Gorontalo', 'Gorontalo', 'gorontalo-kota');

-- -----------------------------------------------------
-- 30. SULAWESI BARAT (6 Kabupaten, 0 Kota)
-- -----------------------------------------------------
INSERT INTO `wp_cities` (`province`, `city_name`, `city_slug`) VALUES
('Sulawesi Barat', 'Majene', 'majene'),
('Sulawesi Barat', 'Mamasa', 'mamasa'),
('Sulawesi Barat', 'Mamuju', 'mamuju-kab'),
('Sulawesi Barat', 'Mamuju Tengah', 'mamuju-tengah'),
('Sulawesi Barat', 'Pasangkayu', 'pasangkayu'),
('Sulawesi Barat', 'Polewali Mandar', 'polewali-mandar');

-- -----------------------------------------------------
-- 31. MALUKU (9 Kabupaten, 2 Kota)
-- -----------------------------------------------------
INSERT INTO `wp_cities` (`province`, `city_name`, `city_slug`) VALUES
('Maluku', 'Aru', 'aru'),
('Maluku', 'Buru', 'buru'),
('Maluku', 'Buru Selatan', 'buru-selatan'),
('Maluku', 'Kepulauan Tanimbar', 'kepulauan-tanimbar'),
('Maluku', 'Maluku Barat Daya', 'maluku-barat-daya'),
('Maluku', 'Maluku Tengah', 'maluku-tengah'),
('Maluku', 'Maluku Tenggara', 'maluku-tenggara'),
('Maluku', 'Seram Bagian Barat', 'seram-bagian-barat'),
('Maluku', 'Seram Bagian Timur', 'seram-bagian-timur'),
('Maluku', 'Ambon', 'ambon'),
('Maluku', 'Tual', 'tual');

-- -----------------------------------------------------
-- 32. MALUKU UTARA (8 Kabupaten, 2 Kota)
-- -----------------------------------------------------
INSERT INTO `wp_cities` (`province`, `city_name`, `city_slug`) VALUES
('Maluku Utara', 'Halmahera Barat', 'halmahera-barat'),
('Maluku Utara', 'Halmahera Tengah', 'halmahera-tengah'),
('Maluku Utara', 'Halmahera Timur', 'halmahera-timur'),
('Maluku Utara', 'Halmahera Selatan', 'halmahera-selatan'),
('Maluku Utara', 'Halmahera Utara', 'halmahera-utara'),
('Maluku Utara', 'Kepulauan Sula', 'kepulauan-sula'),
('Maluku Utara', 'Pulau Morotai', 'pulau-morotai'),
('Maluku Utara', 'Pulau Taliabu', 'pulau-taliabu'),
('Maluku Utara', 'Ternate', 'ternate'),
('Maluku Utara', 'Tidore Kepulauan', 'tidore-kepulauan');

-- -----------------------------------------------------
-- 33. PAPUA BARAT (7 Kabupaten, 1 Kota)
-- -----------------------------------------------------
INSERT INTO `wp_cities` (`province`, `city_name`, `city_slug`) VALUES
('Papua Barat', 'Fakfak', 'fakfak'),
('Papua Barat', 'Kaimana', 'kaimana'),
('Papua Barat', 'Manokwari', 'manokwari'),
('Papua Barat', 'Manokwari Selatan', 'manokwari-selatan'),
('Papua Barat', 'Pegunungan Arfak', 'pegunungan-arfak'),
('Papua Barat', 'Teluk Bintuni', 'teluk-bintuni'),
('Papua Barat', 'Teluk Wondama', 'teluk-wondama'),
('Papua Barat', 'Sorong', 'sorong-kota');

-- -----------------------------------------------------
-- 34. PAPUA BARAT DAYA (5 Kabupaten, 0 Kota)
-- -----------------------------------------------------
INSERT INTO `wp_cities` (`province`, `city_name`, `city_slug`) VALUES
('Papua Barat Daya', 'Kepulauan Aru', 'kepulauan-aru'),
('Papua Barat Daya', 'Maluku Barat Daya', 'maluku-barat-daya'),
('Papua Barat Daya', 'Maluku Tenggara Barat', 'maluku-tenggara-barat'),
('Papua Barat Daya', 'Seram Bagian Barat', 'seram-bagian-barat'),
('Papua Barat Daya', 'Seram Bagian Timur', 'seram-bagian-timur');

-- -----------------------------------------------------
-- 35. PAPUA (8 Kabupaten, 1 Kota)
-- -----------------------------------------------------
INSERT INTO `wp_cities` (`province`, `city_name`, `city_slug`) VALUES
('Papua', 'Asmat', 'asmat'),
('Papua', 'Biak Numfor', 'biak-numfor'),
('Papua', 'Boven Digoel', 'boven-digoel'),
('Papua', 'Deiyai', 'deiyai'),
('Papua', 'Dogiyai', 'dogiyai'),
('Papua', 'Intan Jaya', 'intan-jaya'),
('Papua', 'Jayapura', 'jayapura-kab'),
('Papua', 'Jayawijaya', 'jayawijaya'),
('Papua', 'Keerom', 'keerom'),
('Papua', 'Kepulauan Yapen', 'kepulauan-yapen'),
('Papua', 'Lanny Jaya', 'lanny-jaya'),
('Papua', 'Mamberamo Raya', 'mamberamo-raya'),
('Papua', 'Mamberamo Tengah', 'mamberamo-tengah'),
('Papua', 'Mappi', 'mappi'),
('Papua', 'Merauke', 'merauke'),
('Papua', 'Mimika', 'mimika'),
('Papua', 'Nabire', 'nabire'),
('Papua', 'Nduga', 'nduga'),
('Papua', 'Paniai', 'paniai'),
('Papua', 'Pegunungan Bintang', 'pegunungan-bintang'),
('Papua', 'Puncak', 'puncak'),
('Papua', 'Puncak Jaya', 'puncak-jaya'),
('Papua', 'Sarmi', 'sarmi'),
('Papua', 'Supiori', 'supiori'),
('Papua', 'Tolikara', 'tolikara'),
('Papua', 'Waropen', 'waropen'),
('Papua', 'Yahukimo', 'yahukimo'),
('Papua', 'Yalimo', 'yalimo'),
('Papua', 'Jayapura', 'jayapura-kota');

-- -----------------------------------------------------
-- 36. PAPUA SELATAN (4 Kabupaten, 0 Kota)
-- -----------------------------------------------------
INSERT INTO `wp_cities` (`province`, `city_name`, `city_slug`) VALUES
('Papua Selatan', 'Asmat', 'asmat'),
('Papua Selatan', 'Boven Digoel', 'boven-digoel'),
('Papua Selatan', 'Mappi', 'mappi'),
('Papua Selatan', 'Merauke', 'merauke');

-- -----------------------------------------------------
-- 37. PAPUA TENGAH (5 Kabupaten, 0 Kota)
-- -----------------------------------------------------
INSERT INTO `wp_cities` (`province`, `city_name`, `city_slug`) VALUES
('Papua Tengah', 'Deiyai', 'deiyai'),
('Papua Tengah', 'Dogiyai', 'dogiyai'),
('Papua Tengah', 'Intan Jaya', 'intan-jaya'),
('Papua Tengah', 'Nabire', 'nabire'),
('Papua Tengah', 'Paniai', 'paniai');

-- -----------------------------------------------------
-- 38. PAPUA PEGUNUNGAN (8 Kabupaten, 0 Kota)
-- -----------------------------------------------------
INSERT INTO `wp_cities` (`province`, `city_name`, `city_slug`) VALUES
('Papua Pegunungan', 'Jayawijaya', 'jayawijaya'),
('Papua Pegunungan', 'Lanny Jaya', 'lanny-jaya'),
('Papua Pegunungan', 'Nduga', 'nduga'),
('Papua Pegunungan', 'Pegunungan Bintang', 'pegunungan-bintang'),
('Papua Pegunungan', 'Puncak', 'puncak'),
('Papua Pegunungan', 'Puncak Jaya', 'puncak-jaya'),
('Papua Pegunungan', 'Tolikara', 'tolikara'),
('Papua Pegunungan', 'Yahukimo', 'yahukimo'),
('Papua Pegunungan', 'Yalimo', 'yalimo');

-- =====================================================
-- SAMPLE DATA: Businesses (Jasa AC examples)
-- =====================================================
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
((SELECT id FROM wp_cities WHERE city_slug = 'malang-kota'), 'AC Malang Service', 'Jl. Kawi No. 67, Malang', '0341-901234', '6281234567898', 4.6, -7.977838, 112.634056, 'Jasa service AC berkualitas di Malang. Teknisi ramah dan berpengalaman.'),
((SELECT id FROM wp_cities WHERE city_slug = 'malang-kota'), 'Mitra AC Malang', 'Jl. Sudirman No. 12, Malang', '0341-012345', '6281234567899', 4.4, -7.980000, 112.637000, 'Service AC 24 jam di Malang. Siap datang ke lokasi dalam 1 jam.');
