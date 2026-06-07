# Al-Fazza Bakery - Web Ordering Platform & Admin Management 🥐 🍰

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white) ![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white) ![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white) ![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white) ![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black) ![Midtrans](https://img.shields.io/badge/Midtrans_Payment_Gateway-001A36?style=for-the-badge)

Repositori ini berisi *source code* untuk proyek Ujian Akhir Semester (UAS) mata kuliah Pemrograman Web (Semester 4), Program Studi Rekayasa Perangkat Lunak (Software Engineering), Universitas Pendidikan Indonesia (UPI) Kampus Cibiru.

Proyek ini adalah platform pemesanan *end-to-end* untuk **Al-Fazza Bakery**, sebuah toko roti dan kue modern. Terdiri dari halaman *Customer-facing* yang sangat interaktif dan responsif, serta *Content Management System* (Dashboard Admin) yang komprehensif untuk mengelola operasional bisnis mulai dari penjualan hingga pesanan kustom.

---

## 🚀 Fitur Utama

### Customer Facing Web:
- **Responsive UI/UX & Animations:** Desain antarmuka premium, responsif, dan interaktif menggunakan **Tailwind CSS** dipadukan dengan Native CSS Animations untuk pengalaman pengguna yang maksimal.
- **Sistem Templating Blade:** Komponen UI yang modular dan *reusable* (Hero, Produk Terlaris, Kategori, dll).
- **Integrasi Payment Gateway (Midtrans):** Fitur *checkout* untuk pembelian produk secara aman dan *seamless*. Terintegrasi langsung dengan API Midtrans (Mode Sandbox) yang mendukung berbagai metode pembayaran instan seperti QRIS, E-Wallet (GoPay, ShopeePay), dan Virtual Account Bank.
- **Dynamic Shopping Cart:** Sistem keranjang belanja dinamis berbasis Session yang menghitung total secara otomatis tanpa perlu *reload* halaman.
- **Custom Cake Ordering:** Fitur unggulan bagi pelanggan untuk merancang dan memesan kue ulang tahun/spesial sesuai spesifikasi dan keinginan (Bentuk, Rasa, Isian, Tema, dll).

### Admin Dashboard (CMS):
- **Manajemen Pesanan (*Order Management*):** 
  - Pesanan Online (via Midtrans)
  - Pesanan Kasir / POS (Point of Sales)
  - Pesanan Custom (Pesanan Kue Kustom)
- **Manajemen Produk:** Fitur CRUD terintegrasi untuk menambah, mengedit, menghapus, dan mengatur stok roti, kue, pastry, dan cookies.
- **Laporan Penjualan Terintegrasi:** Dasbor analitik untuk melihat ringkasan pendapatan, performa penjualan, dan mengekspor laporan dalam jangka waktu tertentu.
- **Manajemen Pengguna:** Pengelolaan data pelanggan dan akses Admin.

---

## 💻 Panduan Instalasi (Local Development)

Ikuti langkah-langkah di bawah ini untuk menjalankan aplikasi Al-Fazza Bakery di komputer/laptop Anda (Localhost):

### Prasyarat (Requirements)
Pastikan Anda sudah menginstal aplikasi berikut di komputer Anda:
- [XAMPP](https://www.apachefriends.org/) (termasuk PHP >= 8.1 dan MySQL)
- [Composer](https://getcomposer.org/)
- [Node.js & NPM](https://nodejs.org/) (untuk Vite & Tailwind CSS)
- [Git](https://git-scm.com/)

### Langkah-langkah Instalasi

1. **Clone Repositori**
   Buka terminal/CMD dan jalankan perintah:
   ```bash
   git clone https://github.com/Zsxam/al-fazza-uas.git
   cd al-fazza-uas
   ```

2. **Install Dependensi PHP & NPM**
   ```bash
   composer install
   npm install
   ```

3. **Kompilasi Asset (Tailwind CSS & Vite)**
   ```bash
   npm run build
   ```

4. **Konfigurasi Environment File (.env)**
   Gandakan (copy) file `.env.example` dan ubah namanya menjadi `.env`:
   ```bash
   cp .env.example .env
   ```
   Buka file `.env` dan atur koneksi *database* ke MySQL lokal Anda:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=alfazza_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   *(Pastikan Anda telah membuat database kosong bernama `alfazza_db` di phpMyAdmin)*

5. **Konfigurasi API Keys & Email Server (Penting!)**
   Di dalam file `.env`, pastikan Anda memasukkan *Client Key* dan *Server Key* Midtrans Sandbox Anda, beserta konfigurasi SMTP Email (contoh menggunakan Gmail) untuk pengiriman tagihan:
   ```env
   # MIDTRANS CONFIG
   MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxxxxx
   MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxxxxxx
   MIDTRANS_IS_PRODUCTION=false

   # MAIL CONFIG (Untuk kirim tagihan ke pelanggan)
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=465
   MAIL_USERNAME=email_anda@gmail.com
   MAIL_PASSWORD=password_app_gmail_anda
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=email_anda@gmail.com
   MAIL_FROM_NAME="Al-Fazza Bakery"
   ```

6. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

7. **Jalankan Migrasi & Seeder Database**
   Perintah ini akan membuat semua tabel dan mengisi data awal (seperti produk, admin, dan transaksi *dummy*):
   ```bash
   php artisan migrate:fresh --seed
   ```

8. **Jalankan Server Lokal**
   Buka 2 terminal secara bersamaan.
   Di terminal pertama, jalankan server PHP:
   ```bash
   php artisan serve
   ```
   *(Opsional)* Di terminal kedua, jalankan server Vite untuk auto-refresh CSS saat development:
   ```bash
   npm run dev
   ```
   
Aplikasi sekarang dapat diakses melalui browser di alamat: `http://127.0.0.1:8000`

---

## 👤 Kredensial Akses

Untuk masuk ke **Dashboard Admin**, Anda bisa cari mandiri di *Seeder*

---
*Dibuat dengan ❤️ untuk Ujian Akhir Semester Pemrograman Web UPI.*
