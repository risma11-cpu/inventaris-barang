-- =============================================
-- Database: inventaris_barang
-- Sistem Informasi Inventaris Barang (Laravel 12 + EOQ)
-- =============================================

CREATE DATABASE IF NOT EXISTS `inventaris_barang`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `inventaris_barang`;

SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------
-- Tabel: barangs
-- ---------------------------------------------
DROP TABLE IF EXISTS `transaksis`;
DROP TABLE IF EXISTS `barangs`;

CREATE TABLE `barangs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode_barang` VARCHAR(30) NOT NULL,
  `nama_barang` VARCHAR(255) NOT NULL,
  `satuan` VARCHAR(20) NOT NULL,
  `stok` INT UNSIGNED NOT NULL DEFAULT 0,
  `harga_satuan` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `biaya_pemesanan` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `biaya_penyimpanan` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `lead_time` SMALLINT UNSIGNED NOT NULL DEFAULT 1,
  `safety_stock` INT UNSIGNED NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `barangs_kode_barang_unique` (`kode_barang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------
-- Tabel: transaksis
-- ---------------------------------------------
CREATE TABLE `transaksis` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `barang_id` BIGINT UNSIGNED NOT NULL,
  `jenis` ENUM('masuk','keluar') NOT NULL,
  `jumlah` INT UNSIGNED NOT NULL,
  `tanggal` DATE NOT NULL,
  `keterangan` VARCHAR(255) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaksis_barang_id_foreign` (`barang_id`),
  CONSTRAINT `transaksis_barang_id_foreign`
    FOREIGN KEY (`barang_id`) REFERENCES `barangs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- ---------------------------------------------
-- Data contoh (boleh dihapus kalau tidak perlu)
-- ---------------------------------------------
INSERT INTO `barangs`
  (`kode_barang`, `nama_barang`, `satuan`, `stok`, `harga_satuan`, `biaya_pemesanan`, `biaya_penyimpanan`, `lead_time`, `safety_stock`, `created_at`, `updated_at`)
VALUES
  ('BRG-001', 'Kertas HVS A4', 'rim', 50, 55000.00, 150000.00, 5000.00, 3, 10, NOW(), NOW()),
  ('BRG-002', 'Tinta Printer Hitam', 'botol', 20, 85000.00, 120000.00, 8000.00, 5, 5, NOW(), NOW()),
  ('BRG-003', 'Pulpen Standard', 'box', 30, 40000.00, 100000.00, 4000.00, 2, 8, NOW(), NOW());
