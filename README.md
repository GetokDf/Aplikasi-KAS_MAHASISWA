# 💰 Sistem Informasi Keuangan (KAS) Mahasiswa

![Laravel](https://img.shields.io/badge/Laravel-11.x-red?style=flat&logo=laravel)
![Livewire](https://img.shields.io/badge/Livewire-3.x-purple?style=flat&logo=livewire)
![TailwindCSS](https://img.shields.io/badge/TailwindCSS-3.x-blue?style=flat&logo=tailwindcss)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=flat&logo=mysql)

Sistem Informasi Keuangan (KAS) Mahasiswa adalah aplikasi web modern untuk mengelola keuangan organisasi mahasiswa. Dibangun dengan Laravel 11, Livewire 3, dan TailwindCSS untuk pengalaman pengguna yang responsif dan interaktif.

## ✨ Fitur Utama

### 📊 Dashboard
- Statistik real-time keuangan organisasi
- Saldo kas terkini
- Total pemasukan dan pengeluaran
- Grafik transaksi bulan ini
- Riwayat transaksi terakhir

### 💼 Manajemen Jabatan
- CRUD (Create, Read, Update, Delete) jabatan
- Validasi jabatan yang masih digunakan
- Pencarian dan pagination

### 👥 Manajemen Mahasiswa
- CRUD data mahasiswa
- Assign jabatan ke mahasiswa
- Status aktif/tidak aktif
- Filter berdasarkan status
- Pencarian berdasarkan nama, NIM, dan email

### 💵 Uang Kas
- Input pembayaran kas mahasiswa
- Otomatis update saldo kas
- Filter berdasarkan bulan dan tahun
- Riwayat pembayaran lengkap
- Statistik pemasukan

### 💳 Pengeluaran
- Input pengeluaran organisasi
- Upload bukti pengeluaran (foto/gambar)
- Validasi saldo mencukupi
- Otomatis kurangi saldo kas
- Filter dan pencarian

### 📈 Laporan Keuangan
- Laporan pemasukan vs pengeluaran
- Grafik Chart.js interaktif
- Filter berdasarkan periode (bulan/tahun)
- Top 5 mahasiswa rajin bayar
- Top 5 pengeluaran terbesar
- Detail transaksi lengkap

## 🛠️ Teknologi yang Digunakan

- **Backend**: Laravel 11.x
- **Frontend**: Livewire 3.x + TailwindCSS 3.x
- **Database**: MySQL 8.0+
- **Authentication**: Laravel Breeze
- **Charts**: Chart.js
- **Icons**: Heroicons (SVG)
- **Server**: XAMPP (Apache + MySQL)

## 📋 Prasyarat

Pastikan sistem Anda sudah terinstall:

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL 8.0+ (XAMPP)
- Git

## 🚀 Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/username/kas-mahasiswa.git
cd kas-mahasiswa
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### 3. Konfigurasi Environment

```bash
# Copy file .env
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Konfigurasi Database

Edit file `.env` dan sesuaikan dengan konfigurasi database Anda:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kas_mahasiswa
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Buat Database

Buat database baru di phpMyAdmin dengan nama `kas_mahasiswa`

### 6. Jalankan Migration & Seeder

```bash
# Jalankan migration
php artisan migrate

# Jalankan seeder untuk data awal
php artisan db:seed
```

### 7. Build Assets

```bash
# Development mode
npm run dev

# Production mode
npm run build
```

### 8. Jalankan Aplikasi

```bash
# Jalankan server Laravel
php artisan serve
```

Buka browser dan akses: `http://127.0.0.1:8000`

## 👤 Login Default (CARA LOGIN)

Setelah seeder dijalankan, gunakan kredensial berikut untuk login:

```
Email: admin@admin.com
Password: password
```

## 📁 Struktur Folder

```
kas-mahasiswa/
├── app/
│   ├── Livewire/          # Livewire Components
│   │   ├── Dashboard.php
│   │   ├── Jabatan/
│   │   ├── Mahasiswa/
│   │   ├── UangKas/
│   │   ├── Pengeluaran/
│   │   └── Laporan/
│   └── Models/            # Eloquent Models
├── database/
│   ├── migrations/        # Database Migrations
│   └── seeders/          # Database Seeders
├── resources/
│   └── views/
│       ├── components/    # Blade Components
│       └── livewire/     # Livewire Views
├── routes/
│   └── web.php           # Web Routes
└── public/
    └── storage/          # File Storage (Bukti Pengeluaran)
```

## 🎨 Screenshot

### Dashboard
<img width="1917" height="1009" alt="Screenshot 2026-01-08 024746" src="https://github.com/user-attachments/assets/a67eda36-d02e-4d57-8c1b-74481adaea12" />


### Laporan Keuangan
<img width="1919" height="1007" alt="Screenshot 2026-01-08 024756" src="https://github.com/user-attachments/assets/33339c74-235b-47f6-ad15-556182cb5ff1" />


## 🔐 Keamanan

- Authentication menggunakan Laravel Breeze
- Password di-hash dengan bcrypt
- CSRF Protection
- SQL Injection Protection (Eloquent ORM)
- XSS Protection

## 📝 Fitur Tambahan

- [x] Real-time update dengan Livewire
- [x] Responsive design (Mobile-friendly)
- [x] Pagination
- [x] Search & Filter
- [x] Form validation
- [x] Alert notifications
- [x] Upload file bukti pengeluaran
- [x] Grafik interaktif

## 🐛 Troubleshooting

### Error: npm command not found
```bash
# Install Node.js dari https://nodejs.org/
# Restart terminal setelah instalasi
```

### Error: Class 'Livewire\Component' not found
```bash
composer require livewire/livewire
```

### Error: SQLSTATE Connection refused
```bash
# Pastikan MySQL/XAMPP sudah running
# Cek konfigurasi .env sudah benar
```

### Chart tidak muncul
```bash
# Clear cache
php artisan view:clear
php artisan config:clear
php artisan cache:clear

# Rebuild assets
npm run build
```

## 🤝 Kontribusi

Kontribusi, issues, dan feature requests sangat diterima!

1. Fork repository ini
2. Buat branch baru (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## 📄 Lisensi

Proyek ini menggunakan lisensi MIT. Lihat file `LICENSE` untuk detail.

## 👨‍💻 Pengembang

Dikembangkan dengan ❤️ untuk memudahkan pengelolaan keuangan organisasi mahasiswa.

## 📞 Kontak

- Email: triantodafit@gmail.com

## 🙏 Acknowledgments

- [Laravel](https://laravel.com)
- [Livewire](https://livewire.laravel.com)
- [TailwindCSS](https://tailwindcss.com)
- [Chart.js](https://www.chartjs.org)
- [Heroicons](https://heroicons.com)

---

⭐ Jika proyek ini membantu Anda, jangan lupa berikan star di GitHub!
