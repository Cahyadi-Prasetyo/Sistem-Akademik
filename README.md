# SiPaKu - Sistem Informasi Perkuliahan Akademik

**SiPaKu** adalah platform Sistem Informasi Akademik yang dibangun menggunakan **Laravel 12**, dirancang untuk menangani proses akademik perguruan tinggi secara digital, mulai dari pengelolaan data master, perencanaan studi (KRS), perkuliahan, hingga evaluasi dosen (EPBM) dan presensi.

## 🚀 Fitur Utama

### 1. Multi-Role Authentication
Sistem ini mendukung 5 peran pengguna dengan hak akses yang berbeda:
- **Admin**: Mengelola data master (Fakultas, Jurusan, Prodi, Matkul, Ruangan, User).
- **Kaprodi**: Mengelola periode EPBM dan memonitor aktivitas program studi.
- **Pimpinan (Rektorat)**: Melihat ringkasan data akademik dan hasil evaluasi (Executive Dashboard).
- **Dosen**: Melihat jadwal mengajar, menginput nilai, dan pencatatan absensi mahasiswa.
- **Mahasiswa**: Pengisian KRS, melihat jadwal, melihat nilai, mengisi EPBM, dan memonitor kehadiran.

### 2. Manajemen Akademik (Core)
- **Struktur Organisasi**: Manajemen Fakultas, Jurusan, dan Program Studi.
- **Perkuliahan**: Manajemen Mata Kuliah, Kurikulum, dan Jadwal Kuliah (Team Teaching support).
- **KRS (Kartu Rencana Studi)**: Mahasiswa dapat mengambil mata kuliah sesuai jatah SKS.

### 3. EPBM (Evaluasi Proses Belajar Mengajar)
Fitur untuk menjamin mutu perkuliahan melalui umpan balik mahasiswa:
- **Manajemen Periode**: Kaprodi dapat membuka/tutup periode evaluasi.
- **Kuesioner Dinamis**: Pertanyaan evaluasi (Rating & Esai) dapat disesuaikan per periode.
- **Reporting**: Laporan hasil evaluasi per dosen dan per mata kuliah untuk bahan monitoring.

### 4. Sistem Absensi (Kehadiran)
- **Input Dosen**: Dosen mencatat kehadiran di setiap pertemuan jadwal.
- **Status Kehadiran**: Mendukung status Hadir, Sakit, Izin, dan Alpha.
- **Rekapitulasi**: Mahasiswa dapat melihat persentase kehadiran mereka secara realtime.

---

## 🛠️ Teknologi yang Digunakan

- **Backend Framework**: Laravel 12 (PHP 8.2+)
- **Database**: MySQL / MariaDB
- **Frontend**: Blade Templates + TailwindCSS + Alpine.js
- **Architecture**: Repository Pattern untuk maintainability dan scalability.

---

## 💻 Cara Instalasi

Ikuti langkah berikut untuk menjalankan proyek di komputer lokal Anda:

### Prasyarat
- PHP >= 8.2
- Composer
- MySQL

### Langkah-langkah

1. **Clone Repository**
   ```bash
   git clone https://github.com/Cahyadi-Prasetyo/Sistem-Akademik.git
   cd Sistem-Akademik
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**
   Duplikasi file `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   ```
   Sesuaikan konfigurasi database di file `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sipaku
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate Key & Migrasi Database**
   ```bash
   php artisan key:generate
   php artisan migrate:fresh --seed
   ```

5. **Jalankan Aplikasi**
   ```bash
   php artisan serve
   ```
   Akses aplikasi di: `http://localhost:8000`

---

## 📝 Lisensi

Sistem ini adalah perangkat lunak open-source yang dilisensikan di bawah [MIT license](https://opensource.org/licenses/MIT).

Silakan gunakan, modifikasi, dan distribusikan sesuai dengan ketentuan lisensi.
