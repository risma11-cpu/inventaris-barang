# Sistem Informasi Inventaris Barang (Metode EOQ)

Aplikasi web berbasis **Laravel 12** untuk mengelola stok barang dan menentukan **berapa banyak** serta **kapan** barang harus dipesan ulang dengan metode **Economic Order Quantity (EOQ)** dan **Reorder Point (ROP)**.

## Kelompok

| No | Nama | NPM |
|----|------|-----|
| 1 | Rismatur Rahmi | 24105111073 |
| 2 | Asmaul Husna | 24105111081 |
| 3 | Zafira Ismy Samahya | 24105111061 |

## Fitur

- **Data Barang**: tambah, edit, hapus, dan cari barang beserta parameter EOQ (biaya pemesanan, biaya penyimpanan, lead time, safety stock). Stok awal dicatat otomatis sebagai transaksi masuk, dan setelah itu stok hanya berubah lewat transaksi.
- **Transaksi**: pencatatan barang masuk dan keluar. Stok berubah otomatis, transaksi keluar ditolak jika stok tidak cukup, dan menghapus transaksi mengembalikan stok.
- **Analisis EOQ**: menghitung kebutuhan tahunan, EOQ, ROP, frekuensi pemesanan, total biaya persediaan, dan status stok (Aman, Pesan Ulang, Kritis) dari riwayat barang keluar.
- **Beranda**: ringkasan inventaris, daftar barang yang perlu dipesan ulang, transaksi terbaru, serta grafik barang masuk vs keluar dan stok vs ROP.
- **Laporan**: cetak atau simpan PDF dan ekspor CSV (dapat dibuka di Excel).
- **Tampilan**: tema neo-brutalism berbasis Bootstrap 5.

## Metode EOQ

| Komponen | Rumus |
|----------|-------|
| D (kebutuhan per tahun) | total barang keluar ÷ jumlah hari periode × 365 |
| EOQ | √(2 × D × S ÷ H), dibulatkan ke atas |
| ROP | (rata-rata pemakaian harian × lead time) + safety stock |
| Frekuensi pemesanan | D ÷ EOQ |
| Total biaya persediaan | (D ÷ EOQ × S) + (EOQ ÷ 2 × H) |

Keterangan: **S** = biaya pemesanan sekali pesan, **H** = biaya penyimpanan per unit per tahun.

Status stok: **Pesan Ulang** jika stok ≤ ROP, **Kritis** jika stok ≤ safety stock.

## Teknologi

- PHP 8.2+ dan Laravel 12
- MySQL (XAMPP)
- Bootstrap 5.3 dan Chart.js 4

## Cara Menjalankan

Prasyarat: PHP 8.2 atau lebih baru, Composer, MySQL (XAMPP), dan Git.

**1. Clone dan pasang dependensi**

```bash
git clone https://github.com/risma11-cpu/inventaris-barang.git
cd inventaris-barang
composer install
```

**2. Siapkan file `.env`**

```bash
copy .env.example .env
php artisan key:generate
```

Lalu ubah bagian berikut di `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventaris_barang
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

**3. Impor database**

Nyalakan MySQL di XAMPP, lalu impor kedua file SQL secara berurutan:

```bash
cmd /c "C:\xampp\mysql\bin\mysql.exe -u root < database\sql\inventaris_barang.sql"
cmd /c "C:\xampp\mysql\bin\mysql.exe -u root < database\sql\data_contoh_transaksi.sql"
```

File pertama membuat database, tabel, dan 3 barang contoh. File kedua (opsional) mengisi riwayat transaksi 12 bulan agar halaman Analisis EOQ langsung berisi data.

> Jangan jalankan `php artisan migrate` setelah mengimpor SQL, karena tabelnya sudah dibuat dan akan muncul error "table already exists".

**4. Jalankan aplikasi**

```bash
php artisan serve
```

Buka `http://127.0.0.1:8000` di browser.

## Struktur Utama

| Lokasi | Isi |
|--------|-----|
| `routes/web.php` | Daftar rute aplikasi |
| `app/Http/Controllers/` | Logika halaman (barang, transaksi, EOQ, beranda) |
| `app/Services/EoqService.php` | Perhitungan EOQ, ROP, biaya, dan status stok |
| `app/Models/` | Model `Barang` dan `Transaksi` |
| `resources/views/` | Tampilan Blade |
| `public/css/neo.css` | Tema neo-brutalism |
| `database/sql/` | File SQL untuk membuat database dan data contoh |
