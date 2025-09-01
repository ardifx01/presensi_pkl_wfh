# Sistem Presensi PKL Work From Home
### SMKN 1 Surabaya

<p align="center">
  ![SMKN1 SURABAYA](public/images/smk-negeri-1sby.png)
</p>

<p align="center">
  <strong>Sistem Presensi Digital untuk Praktek Kerja Lapangan (PKL)</strong><br>
  Sekolah Menengah Kejuruan Negeri 1 Surabaya
</p>

---

## 📋 Tentang Sistem

Sistem Presensi PKL Work From Home adalah aplikasi web yang dikembangkan untuk memudahkan siswa SMKN 1 Surabaya dalam melakukan presensi PKL secara digital. Sistem ini menyediakan:

### ✨ Fitur Utama
- **🔐 Login dengan Google** - Autentikasi menggunakan akun Google siswa
- **📝 Form Presensi Digital** - Interface yang mudah digunakan seperti Google Form
- **📊 Dashboard Admin** - Panel kontrol untuk admin sekolah
- **📄 Export Excel** - Unduh data presensi dalam format Excel
- **📱 Responsive Design** - Dapat diakses dari desktop dan mobile
- **🔒 Data Privacy** - Setiap siswa hanya dapat melihat data presensinya sendiri

### 🎯 Konsentrasi Keahlian yang Didukung
1. Rekayasa Perangkat Lunak
2. Teknik Komputer dan Jaringan
3. Bisnis Digital
4. Manajemen Perkantoran
5. Manajemen Logistik
6. Akuntansi
7. Perhotelan
8. Desain Komunikasi Visual
9. Produksi dan Siaran Program Televisi

## 🛠️ Teknologi yang Digunakan

- **Framework**: Laravel 12
- **Database**: MySQL/SQLite
- **Frontend**: Bootstrap 5, Blade Templates
- **Authentication**: Laravel Socialite (Google OAuth)
- **Export**: Maatwebsite Excel
- **Storage**: Laravel File Storage

## 📦 Instalasi

### Persyaratan Sistem
- PHP 8.2 atau lebih tinggi
- Composer
- Node.js & NPM
- MySQL/SQLite

### Langkah Instalasi

1. **Clone Repository**
   ```bash
   git clone https://github.com/ReXooGen/presensi_pkl_wfh.git
   cd presensi_pkl_wfh
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Setup Database**
   ```bash
   # Edit .env file dengan konfigurasi database
   php artisan migrate
   php artisan db:seed
   ```

5. **Konfigurasi Google OAuth**
   ```bash
   # Tambahkan di .env file:
   GOOGLE_CLIENT_ID=your_google_client_id
   GOOGLE_CLIENT_SECRET=your_google_client_secret
   GOOGLE_REDIRECT_URL=http://localhost:8000/auth/google/callback
   ```

6. **Setup Storage**
   ```bash
   php artisan storage:link
   ```

7. **Jalankan Aplikasi**
   ```bash
   php artisan serve
   ```

## 🚀 Penggunaan

### Untuk Siswa
1. Akses aplikasi melalui browser
2. Login menggunakan akun Google
3. Isi form presensi sesuai jadwal (Pagi, Siang, Sore)
4. Upload foto sebagai bukti presensi
5. Lihat riwayat presensi pribadi

### Untuk Admin
1. Login dengan kredensial admin
2. Akses dashboard admin
3. Monitor data presensi seluruh siswa
4. Filter dan export data ke Excel
5. Kelola pengaturaan sistem

## 📱 Jadwal Presensi

- **Pagi**: 10.00 WIB (window: 09:30 - 10:30)
- **Siang**: 14.00 WIB (window: 13:30 - 14:30)
- **Sore**: 16.30 WIB (window: 16:00 - 17:00)

## 🔧 Konfigurasi Production

### Optimasi Memory untuk Export
- Simple Export: < 100 records
- Clean Export: 100-500 records  
- Lightweight Export: > 500 records

### Security Features
- CSRF Protection
- Input Validation
- File Upload Security
- User Data Isolation

## 📁 Struktur Project

```
├── app/
│   ├── Http/Controllers/     # Controller files
│   ├── Models/              # Eloquent models
│   ├── Exports/             # Excel export classes
│   └── Jobs/                # Background jobs
├── database/
│   ├── migrations/          # Database migrations
│   └── seeders/            # Data seeders
├── resources/
│   ├── views/              # Blade templates
│   │   ├── absensi/        # Attendance views
│   │   ├── admin/          # Admin dashboard
│   │   └── auth/           # Authentication views
│   └── css/                # Stylesheets
└── storage/
    ├── app/public/         # Public file storage
    └── exports/            # Temporary export files
```

## 🤝 Kontribusi

Kami menerima kontribusi dari komunitas. Silakan:

1. Fork repository ini
2. Buat branch untuk fitur baru (`git checkout -b feature/fitur-baru`)
3. Commit perubahan (`git commit -am 'Tambah fitur baru'`)
4. Push ke branch (`git push origin feature/fitur-baru`)
5. Buat Pull Request

## 📞 Kontak & Support

- **Sekolah**: SMKN 1 Surabaya
- **Developer**: ReXooGen
- **Repository**: https://github.com/ReXooGen/presensi_pkl_wfh

## 📄 Lisensi

Sistem ini dikembangkan untuk SMKN 1 Surabaya. Built with Laravel Framework yang berlisensi [MIT](https://opensource.org/licenses/MIT).
