-- Create tables for Diabetes Prediction System
-- Using the same structure as the migration files

-- Table: petugas
CREATE TABLE IF NOT EXISTS `petugas` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','petugas') DEFAULT 'petugas',
  `status_aktif` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: pasien
CREATE TABLE IF NOT EXISTS `pasien` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `umur` int(11) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `berat` float NOT NULL,
  `tinggi` float NOT NULL,
  `alamat` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: prediksi
CREATE TABLE IF NOT EXISTS `prediksi` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_pasien` int(11) unsigned NOT NULL,
  `pregnancies` int(11) NOT NULL,
  `glucose` float NOT NULL,
  `blood_pressure` float NOT NULL,
  `skin_thickness` float NOT NULL,
  `insulin` float NOT NULL,
  `bmi` float NOT NULL,
  `dpf` float NOT NULL,
  `age` int(11) NOT NULL,
  `hasil` tinyint(1) NOT NULL COMMENT '0=Tidak Diabetes, 1=Diabetes',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_pasien` (`id_pasien`),
  CONSTRAINT `prediksi_ibfk_1` FOREIGN KEY (`id_pasien`) REFERENCES `pasien` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user (password: admin123)
INSERT INTO `petugas` (`nama`, `username`, `password`, `role`, `status_aktif`, `created_at`) 
VALUES ('Administrator', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1, NOW())
ON DUPLICATE KEY UPDATE `updated_at` = NOW();

-- Insert default petugas user (password: petugas123)
INSERT INTO `petugas` (`nama`, `username`, `password`, `role`, `status_aktif`, `created_at`) 
VALUES ('Petugas Demo', 'petugas', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'petugas', 1, NOW())
ON DUPLICATE KEY UPDATE `updated_at` = NOW();
