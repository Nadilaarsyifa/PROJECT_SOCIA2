# Sistem Asisten Analisis Alert SOC Berbasis AI

## Deskripsi

Sistem Asisten Analisis Alert SOC Berbasis AI merupakan aplikasi yang dikembangkan untuk membantu SOC Engineer memahami alert keamanan dari Magnus secara lebih cepat dan terstruktur.

Aplikasi menerima pesan alert melalui input teks, menyimpan informasi tiket, dan menggunakan AI untuk menghasilkan ringkasan alert, poin perhatian, serta rekomendasi langkah penanganan. Rekomendasi digunakan sebagai panduan bagi engineer, bukan untuk menggantikan keputusan engineer.

## Teknologi

* **Backend:** Laravel 12, PHP
* **Database:** MySQL/MariaDB
* **Frontend:** Blade, Bootstrap
* **AI:** Gemma 3:4b melalui Ollama
* **Version Control:** Git dan GitHub

## Fitur yang Direncanakan

* Login dan pengelolaan role pengguna
* Input dan pengelolaan ticket SOC
* Analisis alert menggunakan AI
* Rekomendasi langkah penanganan
* Action checklist
* Engineer review dan hasil investigasi
* Knowledge Base berbasis SOP
* Dashboard dan cetak laporan

## Progres Pengembangan

* [x] Inisialisasi project Laravel
* [x] Inisialisasi Git dan repository GitHub
* [x] Perancangan database dan migration
* [x] Pembuatan model dan konfigurasi relationship dasar
* [ ] Seeder data awal
* [ ] Authentication dan authorization
* [ ] Pengembangan fitur ticket dan analisis AI
* [ ] Pengembangan checklist dan laporan
* [ ] Testing dan dokumentasi final

## Menjalankan Project

1. Clone repository.
2. Jalankan `composer install`.
3. Salin `.env.example` menjadi `.env`.
4. Sesuaikan konfigurasi database di `.env`.
5. Jalankan `php artisan key:generate`.
6. Jalankan `php artisan migrate`.
7. Jalankan `php artisan serve`.

**Catatan:** Project masih dalam tahap pengembangan. Fitur yang belum selesai akan ditambahkan secara bertahap.
