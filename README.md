# Sistem Asisten Analisis Alert SOC Berbasis AI

## Deskripsi

Sistem Asisten Analisis Alert SOC Berbasis AI (SOCIA) merupakan aplikasi berbasis web yang dikembangkan untuk membantu SOC Engineer memahami dan menangani alert keamanan dari Magnus secara lebih cepat dan terstruktur.

Aplikasi menerima pesan alert melalui input teks, menyimpan informasi tiket, dan dirancang untuk memanfaatkan model AI lokal guna menghasilkan ringkasan alert, poin perhatian, serta rekomendasi langkah penanganan berdasarkan alert dan SOP yang relevan.

Hasil analisis AI digunakan sebagai panduan bagi SOC Engineer, bukan untuk menggantikan penilaian dan keputusan engineer. Engineer tetap melakukan pemeriksaan dan menentukan hasil investigasi.

## Teknologi

- **Backend:** Laravel 12, PHP 8.2
- **Frontend:** Laravel Blade, Bootstrap (integrasi tampilan masih dalam pengembangan)
- **Database:** MySQL/MariaDB
- **AI:** Gemma 3:4b melalui Ollama
- **Authentication:** Laravel Breeze
- **Authorization:** Spatie Laravel Permission
- **Version Control:** Git dan GitHub
- **Development Environment:** XAMPP, Composer, Node.js dan npm

## Role Pengguna

Aplikasi dirancang dengan dua role utama:

### 1. Administrator

Administrator memiliki akses ke fitur operasional serta fitur administrasi, meliputi:
- Mengelola akun SOC Engineer.
- Mengelola Knowledge Base dan SOP.
- Mengakses fitur operasional SOCIA.

### 2. SOC Engineer

SOC Engineer memiliki akses ke fitur operasional, meliputi:
- Memasukkan dan mengelola tiket SOC.
- Melakukan analisis alert menggunakan AI.
- Meninjau rekomendasi dan action checklist.
- Melakukan verifikasi serta mencatat hasil investigasi.
- Melihat dan mencetak laporan investigasi.

SOC Engineer tidak memiliki akses ke fitur Kelola User dan Kelola Knowledge Base.

## Fitur Aplikasi

Fitur yang dikembangkan dan direncanakan meliputi:

1. Login dan autentikasi pengguna.
2. Pengelolaan role dan hak akses.
3. Pengelolaan tiket SOC.
4. Analisis alert menggunakan AI.
5. Ringkasan alert dan poin perhatian.
6. Rekomendasi langkah penanganan berdasarkan alert dan SOP.
7. Action checklist untuk memantau langkah penanganan.
8. Engineer review dan hasil investigasi.
9. Knowledge Base berbasis SOP.
10. Dashboard dan tabel seluruh tiket.
11. Cetak laporan investigasi.
12. Ekspor seluruh tiket ke Excel.

## Progres Pengembangan

### Fondasi Aplikasi

- [x] Inisialisasi project Laravel.
- [x] Inisialisasi Git dan repository GitHub.
- [x] Konfigurasi lingkungan pengembangan.
- [x] Perancangan struktur database.
- [x] Pembuatan migration database.
- [x] Pembuatan model dan relasi dasar.
- [x] Pembuatan seeder role dan permission.
- [x] Konfigurasi autentikasi menggunakan Laravel Breeze.
- [x] Konfigurasi role Administrator dan SOC Engineer.
- [x] Konfigurasi middleware pembatasan akses berdasarkan role.
- [x] Pengujian akses halaman khusus Administrator.
- [x] Pembuatan controller dan halaman awal Kelola User.

### Pengembangan Fitur

- [ ] Penyelesaian layout utama aplikasi.
- [ ] Pengembangan Dashboard.
- [ ] Pengembangan fitur input dan pengelolaan tiket.
- [ ] Integrasi model AI lokal melalui Ollama.
- [ ] Pengembangan analisis dan rekomendasi penanganan alert.
- [ ] Pengembangan action checklist.
- [ ] Pengembangan engineer review dan hasil investigasi.
- [ ] Pengembangan Knowledge Base dan pengelolaan SOP.
- [ ] Pengembangan cetak laporan dan ekspor Excel.
- [ ] Pengujian seluruh fitur dan hak akses.
- [ ] Pengujian instalasi dari awal.
- [ ] Penyelesaian dokumentasi proyek.

## Menjalankan Project

### Persyaratan

Pastikan perangkat telah memiliki:
- PHP 8.2 atau versi yang kompatibel dengan dependensi project.
- Composer.
- Node.js dan npm.
- MySQL/MariaDB, misalnya melalui XAMPP.
- Git.

Untuk menjalankan fitur AI nantinya, Ollama dan model Gemma 3:4b juga perlu disiapkan.

### Instalasi

1. Clone repository:

   ```bash
   git clone https://github.com/Nadilaarsyifa/PROJECT_SOCIA2.git
   cd PROJECT_SOCIA2
   ```

2. Instal dependensi PHP:

   ```bash
   composer install
   ```

3. Salin `.env.example` menjadi `.env`.

   ```bash
   copy .env.example .env
   ```

4. Sesuaikan konfigurasi database pada `.env`, termasuk nama database, username, dan password.

5. Buat database sesuai konfigurasi tersebut melalui MySQL atau phpMyAdmin.

6. Buat application key:

   ```bash
   php artisan key:generate
   ```

7. Instal dependensi frontend:

   ```bash
   npm install
   ```

8. Jalankan migration:

   ```bash
   php artisan migrate
   ```

9. Isi data awal role dan permission:

   ```bash
   php artisan db:seed --class=RoleSeeder
   ```

   Jika seeder pengguna sudah tersedia dan dikonfigurasi melalui `DatabaseSeeder`, jalankan juga seeder pengguna sesuai konfigurasi project.

10. Jalankan aplikasi:

    ```bash
    php artisan serve
    ```

11. Jika diperlukan, jalankan kompilasi aset frontend:

    ```bash
    npm run dev
    ```

Aplikasi dapat diakses melalui:

`http://127.0.0.1:8000`

## Catatan

Project masih dalam tahap pengembangan. Autentikasi, konfigurasi role dan permission, pembatasan akses halaman, serta halaman awal Kelola User telah dikerjakan. Integrasi AI dan fitur operasional lainnya akan diselesaikan secara bertahap.

Untuk keamanan, jangan memasukkan file `.env`, password, atau kredensial pribadi ke repository GitHub.
