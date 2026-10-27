# Sistem Asisten Analisis Alert SOC Berbasis AI (SOCIA)

## Deskripsi

**Sistem Asisten Analisis Alert SOC Berbasis AI (SOCIA)** merupakan aplikasi berbasis web yang dikembangkan untuk membantu Security Operations Center (SOC) Engineer memahami dan menangani alert keamanan dari Magnus secara lebih cepat dan terstruktur.

Aplikasi menerima pesan alert melalui input teks, mengekstraksi informasi penting, menyimpan data tiket, dan memanfaatkan model AI lokal untuk membantu menghasilkan ringkasan alert, poin yang perlu diperhatikan, serta rekomendasi langkah penanganan yang relevan.

Hasil analisis AI digunakan sebagai panduan bagi SOC Engineer, bukan untuk menggantikan penilaian dan keputusan engineer. Engineer tetap bertanggung jawab untuk memeriksa hasil analisis, menjalankan langkah investigasi yang sesuai, dan menentukan hasil akhir penanganan alert.

## Teknologi

| Komponen | Teknologi |
|---|---|
| Backend | Laravel 12, PHP 8.2 |
| Frontend | Laravel Blade, Bootstrap |
| Ikon antarmuka | Bootstrap Icons |
| Database | MySQL/MariaDB |
| AI lokal | Gemma 3:4b melalui Ollama |
| Authentication | Laravel Breeze |
| Authorization | Spatie Laravel Permission |
| Ekspor Excel | Maatwebsite Laravel Excel |
| Version Control | Git dan GitHub |
| Development Environment | XAMPP, Composer, Node.js dan npm |

## Role Pengguna

SOCIA dirancang dengan dua role utama.

### 1. Administrator

Administrator memiliki akses terhadap fitur operasional dan administrasi, meliputi:

- Mengakses dashboard dan fitur operasional SOCIA.
- Melihat dan mengelola tiket SOC sesuai hak akses.
- Mengelola akun SOC Engineer.
- Mengelola Knowledge Base dan SOP setelah fitur tersebut tersedia.

### 2. SOC Engineer

SOC Engineer memiliki akses terhadap fitur operasional, meliputi:

- Memasukkan dan meninjau tiket SOC.
- Melakukan analisis alert menggunakan AI.
- Meninjau ringkasan alert dan poin yang perlu diperhatikan.
- Meninjau rekomendasi dan action checklist.
- Melakukan verifikasi dan mencatat hasil investigasi.
- Melihat dan mencetak laporan investigasi setelah fitur laporan tersedia.
- Melihat daftar tiket dan mengekspor data tiket ke Excel.

SOC Engineer tidak memiliki akses ke fitur Kelola User dan Kelola Knowledge Base.

## Fitur Aplikasi

### Fitur yang Telah Dikembangkan

- **Login dan autentikasi pengguna** menggunakan Laravel Breeze.
- **Role dan hak akses** untuk Administrator dan SOC Engineer.
- **Dashboard** untuk mengakses informasi dan fitur operasional.
- **Pengelolaan tiket SOC**, termasuk melihat detail tiket.
- **Halaman All Tickets** untuk menampilkan daftar tiket yang tersimpan.
- **Pencarian tiket otomatis** berdasarkan kata kunci dan filter tanggal kejadian.
- **Penghapusan tiket** khusus Administrator.
- **Analisis alert berbantuan AI** untuk membantu memahami informasi alert.
- **Ringkasan alert dan poin yang perlu diperhatikan** sebagai bagian dari hasil analisis.
- **Rekomendasi langkah penanganan dan action checklist** untuk membantu proses investigasi.
- **Pengelolaan akun pengguna** melalui halaman Kelola User.
- **Ekspor tiket ke Excel** dengan pilihan tahun dan bulan melalui modal popup tanpa perlu berpindah halaman.

### Fitur yang Direncanakan atau Masih Perlu Diselesaikan

- Penyempurnaan dan pengujian integrasi AI lokal melalui Ollama.
- Penyempurnaan rekomendasi penanganan berdasarkan Knowledge Base dan SOP.
- Penyempurnaan engineer review dan pencatatan hasil akhir investigasi.
- Pengembangan pengelolaan Knowledge Base dan SOP melalui antarmuka aplikasi.
- Pengembangan dan penyempurnaan cetak laporan investigasi.
- Pengujian menyeluruh seluruh fitur dan pembatasan hak akses.
- Pengujian instalasi dari awal pada lingkungan baru.
- Penyelesaian dokumentasi teknis proyek.

## Progres Pengembangan

### Fondasi Aplikasi

- [x] Inisialisasi proyek Laravel.
- [x] Inisialisasi Git dan repository GitHub.
- [x] Konfigurasi lingkungan pengembangan.
- [x] Perancangan struktur database.
- [x] Pembuatan migration database.
- [x] Pembuatan model dan relasi dasar.
- [x] Pembuatan seeder role dan permission.
- [x] Konfigurasi autentikasi menggunakan Laravel Breeze.
- [x] Konfigurasi role Administrator dan SOC Engineer.
- [x] Konfigurasi middleware pembatasan akses berdasarkan role.
- [x] Pengembangan awal halaman Kelola User.

### Pengembangan Fitur

- [x] Pengembangan halaman All Tickets.
- [x] Pencarian otomatis dan filter tanggal kejadian.
- [x] Fitur penghapusan tiket dengan pembatasan role.
- [x] Fitur ekspor tiket ke Excel.
- [x] Filter ekspor berdasarkan tahun dan bulan.
- [x] Modal popup untuk pemilihan periode ekspor.
- [x] Pemformatan laporan Excel agar lebih mudah dibaca.
- [ ] Penyempurnaan layout utama dan konsistensi antarmuka.
- [ ] Penyempurnaan Dashboard.
- [ ] Penyempurnaan integrasi dan pengujian AI lokal.
- [ ] Penyempurnaan action checklist dan hasil investigasi.
- [ ] Pengembangan antarmuka Knowledge Base dan pengelolaan SOP.
- [ ] Penyelesaian cetak laporan investigasi.
- [ ] Pengujian seluruh fitur dan hak akses.
- [ ] Pengujian instalasi dari awal.
- [ ] Penyelesaian dokumentasi proyek.

> **Catatan progres:** Checklist di atas merupakan ringkasan pengembangan. Status akhir setiap fitur perlu disesuaikan dengan implementasi dan hasil pengujian terbaru.

## Persyaratan Sistem

Pastikan perangkat telah memiliki:

- PHP 8.2 atau versi yang sesuai dengan dependensi proyek.
- Composer.
- Node.js dan npm.
- MySQL atau MariaDB, misalnya melalui XAMPP.
- Git.

Untuk menjalankan fitur analisis AI lokal, siapkan juga:

- Ollama.
- Model Gemma 3:4b yang telah diunduh melalui Ollama.

## Instalasi dan Menjalankan Project

### 1. Clone Repository

```bash
git clone https://github.com/Nadilaarsyifa/PROJECT_SOCIA2.git
cd PROJECT_SOCIA2
```

### 2. Instal Dependensi PHP

```bash
composer install
```

### 3. Konfigurasi Environment

Salin file `.env.example` menjadi `.env`.

Pada Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Sesuaikan konfigurasi database di dalam file `.env`, misalnya:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=soc_ticket_ai
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan nama database, username, dan password dengan konfigurasi lokal masing-masing.

### 4. Buat Application Key

```bash
php artisan key:generate
```

### 5. Buat Database

Buat database sesuai nilai `DB_DATABASE` pada file `.env` melalui phpMyAdmin atau MySQL/MariaDB.

### 6. Instal Dependensi Frontend

```bash
npm install
```

### 7. Jalankan Migration

```bash
php artisan migrate
```

### 8. Isi Data Awal Role dan Permission

Jika seeder tersedia dan telah dikonfigurasi untuk membuat role serta permission:

```bash
php artisan db:seed --class=RoleSeeder
```

Jika proyek memiliki seeder pengguna awal, jalankan seeder tersebut sesuai konfigurasi proyek.

**Catatan:** Periksa isi seeder sebelum menjalankannya kembali pada database yang sudah berisi data.

### 9. Siapkan Model AI Lokal

Jika fitur AI telah dikonfigurasi untuk menggunakan Ollama, jalankan Ollama dan pastikan model Gemma 3:4b tersedia.

Untuk mengunduh model jika belum tersedia:

```bash
ollama pull gemma3:4b
```

Nama model dan alamat layanan Ollama harus sesuai dengan konfigurasi yang digunakan aplikasi.

### 10. Jalankan Aplikasi

Jalankan server Laravel:

```bash
php artisan serve
```

Jika diperlukan, jalankan kompilasi aset frontend pada terminal terpisah:

```bash
npm run dev
```

Buka aplikasi melalui alamat berikut:

http://127.0.0.1:8000

## Struktur Direktori Utama

```text
PROJECT_SOCIA2/
├── app/
│   ├── Exports/
│   ├── Http/
│   │   └── Controllers/
│   └── Models/
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
│       └── tickets/
├── routes/
│   └── web.php
├── .env.example
├── composer.json
├── package.json
└── README.md
```

Struktur di atas merupakan gambaran direktori utama yang relevan dengan fitur aplikasi. Direktori dan file lain tetap mengikuti struktur proyek Laravel.

## Keamanan

- Jangan mengunggah file `.env` ke repository.
- Jangan menyimpan password atau kredensial pribadi dalam kode sumber.
- Pastikan route dan halaman dibatasi berdasarkan role pengguna.
- Verifikasi rekomendasi AI sebelum melakukan tindakan terhadap alert keamanan.
- Gunakan data uji yang aman ketika menguji fitur analisis dan investigasi.

## Status Proyek

SOCIA masih dalam tahap pengembangan bertahap. Fondasi Laravel, autentikasi, pembatasan hak akses, pengelolaan tiket, analisis berbantuan AI, dan fitur ekspor Excel telah menjadi bagian dari pengembangan aplikasi. Penyempurnaan fitur, pengujian integrasi, dan dokumentasi teknis tetap dilakukan sesuai perkembangan implementasi.

