-- =============================================
-- Data contoh transaksi 12 bulan terakhir
-- Untuk barang contoh BRG-001, BRG-002, BRG-003
-- Tanggal dihitung relatif dari hari ini (CURDATE),
-- jadi data selalu terlihat "setahun terakhir".
-- Aman diimpor berulang kali.
-- =============================================

USE `inventaris_barang`;

SET @b1 = (SELECT id FROM barangs WHERE kode_barang = 'BRG-001');
SET @b2 = (SELECT id FROM barangs WHERE kode_barang = 'BRG-002');
SET @b3 = (SELECT id FROM barangs WHERE kode_barang = 'BRG-003');

-- Kembalikan parameter EOQ barang contoh ke nilai awal
UPDATE barangs SET biaya_pemesanan = 150000, biaya_penyimpanan = 5000, lead_time = 3, safety_stock = 10 WHERE id = @b1;
UPDATE barangs SET biaya_pemesanan = 120000, biaya_penyimpanan = 8000, lead_time = 5, safety_stock = 5  WHERE id = @b2;
UPDATE barangs SET biaya_pemesanan = 100000, biaya_penyimpanan = 4000, lead_time = 2, safety_stock = 8  WHERE id = @b3;

-- Hapus transaksi lama milik 3 barang contoh saja
DELETE FROM transaksis WHERE barang_id IN (@b1, @b2, @b3);

-- ---------------------------------------------
-- BRG-001 Kertas HVS A4
-- ---------------------------------------------
INSERT INTO transaksis (barang_id, jenis, jumlah, tanggal, keterangan, created_at, updated_at) VALUES
(@b1, 'masuk',  400, DATE_SUB(CURDATE(), INTERVAL 350 DAY), 'Pembelian dari supplier', NOW(), NOW()),
(@b1, 'keluar',  95, DATE_SUB(CURDATE(), INTERVAL 345 DAY), 'Pemakaian bulanan', NOW(), NOW()),
(@b1, 'keluar', 100, DATE_SUB(CURDATE(), INTERVAL 315 DAY), 'Pemakaian bulanan', NOW(), NOW()),
(@b1, 'keluar', 110, DATE_SUB(CURDATE(), INTERVAL 285 DAY), 'Pemakaian bulanan', NOW(), NOW()),
(@b1, 'masuk',  300, DATE_SUB(CURDATE(), INTERVAL 258 DAY), 'Pembelian dari supplier', NOW(), NOW()),
(@b1, 'keluar', 105, DATE_SUB(CURDATE(), INTERVAL 255 DAY), 'Pemakaian bulanan', NOW(), NOW()),
(@b1, 'keluar', 120, DATE_SUB(CURDATE(), INTERVAL 225 DAY), 'Pemakaian bulanan', NOW(), NOW()),
(@b1, 'keluar', 115, DATE_SUB(CURDATE(), INTERVAL 195 DAY), 'Pemakaian bulanan', NOW(), NOW()),
(@b1, 'masuk',  300, DATE_SUB(CURDATE(), INTERVAL 168 DAY), 'Pembelian dari supplier', NOW(), NOW()),
(@b1, 'keluar', 100, DATE_SUB(CURDATE(), INTERVAL 165 DAY), 'Pemakaian bulanan', NOW(), NOW()),
(@b1, 'keluar', 125, DATE_SUB(CURDATE(), INTERVAL 135 DAY), 'Pemakaian bulanan', NOW(), NOW()),
(@b1, 'masuk',  300, DATE_SUB(CURDATE(), INTERVAL 108 DAY), 'Pembelian dari supplier', NOW(), NOW()),
(@b1, 'keluar', 130, DATE_SUB(CURDATE(), INTERVAL 105 DAY), 'Pemakaian bulanan', NOW(), NOW()),
(@b1, 'keluar', 110, DATE_SUB(CURDATE(), INTERVAL 75 DAY),  'Pemakaian bulanan', NOW(), NOW()),
(@b1, 'masuk',  300, DATE_SUB(CURDATE(), INTERVAL 48 DAY),  'Pembelian dari supplier', NOW(), NOW()),
(@b1, 'keluar', 120, DATE_SUB(CURDATE(), INTERVAL 45 DAY),  'Pemakaian bulanan', NOW(), NOW()),
(@b1, 'keluar', 140, DATE_SUB(CURDATE(), INTERVAL 15 DAY),  'Pemakaian bulanan', NOW(), NOW());

-- ---------------------------------------------
-- BRG-002 Tinta Printer Hitam
-- ---------------------------------------------
INSERT INTO transaksis (barang_id, jenis, jumlah, tanggal, keterangan, created_at, updated_at) VALUES
(@b2, 'masuk',  110, DATE_SUB(CURDATE(), INTERVAL 350 DAY), 'Pembelian dari supplier', NOW(), NOW()),
(@b2, 'keluar',  18, DATE_SUB(CURDATE(), INTERVAL 345 DAY), 'Pemakaian bulanan', NOW(), NOW()),
(@b2, 'keluar',  20, DATE_SUB(CURDATE(), INTERVAL 315 DAY), 'Pemakaian bulanan', NOW(), NOW()),
(@b2, 'keluar',  22, DATE_SUB(CURDATE(), INTERVAL 285 DAY), 'Pemakaian bulanan', NOW(), NOW()),
(@b2, 'keluar',  20, DATE_SUB(CURDATE(), INTERVAL 255 DAY), 'Pemakaian bulanan', NOW(), NOW()),
(@b2, 'keluar',  24, DATE_SUB(CURDATE(), INTERVAL 225 DAY), 'Pemakaian bulanan', NOW(), NOW()),
(@b2, 'masuk',   70, DATE_SUB(CURDATE(), INTERVAL 198 DAY), 'Pembelian dari supplier', NOW(), NOW()),
(@b2, 'keluar',  22, DATE_SUB(CURDATE(), INTERVAL 195 DAY), 'Pemakaian bulanan', NOW(), NOW()),
(@b2, 'keluar',  20, DATE_SUB(CURDATE(), INTERVAL 165 DAY), 'Pemakaian bulanan', NOW(), NOW()),
(@b2, 'keluar',  25, DATE_SUB(CURDATE(), INTERVAL 135 DAY), 'Pemakaian bulanan', NOW(), NOW()),
(@b2, 'masuk',  100, DATE_SUB(CURDATE(), INTERVAL 108 DAY), 'Pembelian dari supplier', NOW(), NOW()),
(@b2, 'keluar',  26, DATE_SUB(CURDATE(), INTERVAL 105 DAY), 'Pemakaian bulanan', NOW(), NOW()),
(@b2, 'keluar',  22, DATE_SUB(CURDATE(), INTERVAL 75 DAY),  'Pemakaian bulanan', NOW(), NOW()),
(@b2, 'keluar',  24, DATE_SUB(CURDATE(), INTERVAL 45 DAY),  'Pemakaian bulanan', NOW(), NOW()),
(@b2, 'keluar',  28, DATE_SUB(CURDATE(), INTERVAL 15 DAY),  'Pemakaian bulanan', NOW(), NOW());

-- ---------------------------------------------
-- BRG-003 Pulpen Standard
-- ---------------------------------------------
INSERT INTO transaksis (barang_id, jenis, jumlah, tanggal, keterangan, created_at, updated_at) VALUES
(@b3, 'masuk',   75, DATE_SUB(CURDATE(), INTERVAL 350 DAY), 'Pembelian dari supplier', NOW(), NOW()),
(@b3, 'keluar',  12, DATE_SUB(CURDATE(), INTERVAL 345 DAY), 'Pemakaian bulanan', NOW(), NOW()),
(@b3, 'keluar',  14, DATE_SUB(CURDATE(), INTERVAL 315 DAY), 'Pemakaian bulanan', NOW(), NOW()),
(@b3, 'keluar',  15, DATE_SUB(CURDATE(), INTERVAL 285 DAY), 'Pemakaian bulanan', NOW(), NOW()),
(@b3, 'keluar',  13, DATE_SUB(CURDATE(), INTERVAL 255 DAY), 'Pemakaian bulanan', NOW(), NOW()),
(@b3, 'keluar',  16, DATE_SUB(CURDATE(), INTERVAL 225 DAY), 'Pemakaian bulanan', NOW(), NOW()),
(@b3, 'masuk',   60, DATE_SUB(CURDATE(), INTERVAL 200 DAY), 'Pembelian dari supplier', NOW(), NOW()),
(@b3, 'keluar',  15, DATE_SUB(CURDATE(), INTERVAL 195 DAY), 'Pemakaian bulanan', NOW(), NOW()),
(@b3, 'keluar',  14, DATE_SUB(CURDATE(), INTERVAL 165 DAY), 'Pemakaian bulanan', NOW(), NOW()),
(@b3, 'keluar',  17, DATE_SUB(CURDATE(), INTERVAL 135 DAY), 'Pemakaian bulanan', NOW(), NOW()),
(@b3, 'masuk',   55, DATE_SUB(CURDATE(), INTERVAL 110 DAY), 'Pembelian dari supplier', NOW(), NOW()),
(@b3, 'keluar',  18, DATE_SUB(CURDATE(), INTERVAL 105 DAY), 'Pemakaian bulanan', NOW(), NOW()),
(@b3, 'keluar',  15, DATE_SUB(CURDATE(), INTERVAL 75 DAY),  'Pemakaian bulanan', NOW(), NOW()),
(@b3, 'keluar',  16, DATE_SUB(CURDATE(), INTERVAL 45 DAY),  'Pemakaian bulanan', NOW(), NOW()),
(@b3, 'keluar',  19, DATE_SUB(CURDATE(), INTERVAL 15 DAY),  'Pemakaian bulanan', NOW(), NOW());

-- ---------------------------------------------
-- Hitung ulang stok dari seluruh transaksi
-- (masuk dikurangi keluar)
-- ---------------------------------------------
UPDATE barangs b
SET b.stok = COALESCE((
    SELECT SUM(CASE t.jenis WHEN 'masuk' THEN t.jumlah ELSE -t.jumlah END)
    FROM transaksis t
    WHERE t.barang_id = b.id
), 0)
WHERE b.id IN (@b1, @b2, @b3);

-- Cek hasil
SELECT kode_barang, nama_barang, stok FROM barangs WHERE id IN (@b1, @b2, @b3);
