# Al-Fazza App

Proyek ini adalah aplikasi web yang dibangun menggunakan **Laravel 12** sebagai pemenuhan tugas Ujian Akhir Semester (UAS) mata kuliah Pemrograman Web. Aplikasi ini dilengkapi dengan integrasi payment gateway Midtrans dan fitur pembuatan laporan/dokumen PDF menggunakan DOMPDF.

## 1. Fitur Utama

- **Katalog & Pemesanan Online**: Pengunjung dapat melihat daftar produk, melakukan pesanan biasa (checkout), atau membuat pesanan kustom (custom order).
- **Pembayaran Otomatis**: Integrasi payment gateway (Midtrans) untuk memproses pembayaran secara online.
- **Sistem Kasir (POS)**: Modul khusus untuk kasir memproses transaksi langsung di tempat, termasuk fitur penyelesaian pesanan dan cetak struk.
- **Manajemen Toko (Admin)**: Panel admin untuk mengelola katalog produk, melacak pergerakan stok barang, serta memantau dan mengubah status pesanan pelanggan.
- **Laporan Transaksi**: Fitur rekapitulasi data dan ekspor laporan penjualan ke dalam format PDF dan Excel.
- **Ulasan Produk**: Fitur bagi pelanggan untuk memberikan ulasan (review) pada produk setelah menyelesaikan transaksi.

## 2. Persyaratan Sistem

Sebelum menjalankan proyek ini, pastikan sistem Anda memiliki spesifikasi berikut:
- PHP >= 8.2
- Composer
- Node.js & npm (untuk proses build aset dengan Vite)
- Database (MySQL/SQLite/PostgreSQL)

## 3. Struktur Direktori

Berikut adalah struktur utama direktori dalam proyek ini:

```text
al-fazza-app/
├── app/               # Berisi core code dari aplikasi (Models, Controllers, Middleware)
├── bootstrap/         # File untuk proses bootstrapping aplikasi Laravel
├── config/            # Semua file konfigurasi proyek
├── database/          # File migrasi, seeder, dan factory database
├── public/            # Entry point aplikasi (index.php) dan aset publik (gambar, css/js hasil build)
├── resources/         # View (Blade templates) dan raw assets (CSS/JS sebelum di-build)
├── routes/            # Definisi rute aplikasi (web.php, api.php, dll)
├── storage/           # Penyimpanan file yang di-generate, log, dan cache
├── tests/             # File untuk automated testing (PHPUnit/Pest)
└── vendor/            # Dependensi library PHP (hasil dari composer install)
```

## 4. Lisensi

Aplikasi ini dibangun di atas kerangka kerja Laravel yang dilisensikan di bawah [MIT License](https://opensource.org/licenses/MIT).
