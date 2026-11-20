# 🔧 Chrisma Motor – Sistem Inventori Bengkel

Aplikasi web untuk mengelola inventori dan operasional **Bengkel Chrisma Motor** (Subah). Dibuat dengan Laravel supaya pencatatan stok dan data bengkel lebih rapi dan mudah dipantau.

## ✨ Fitur

- 🔐 Login admin
- 📦 Manajemen inventori / stok barang
- _(tambahkan fitur lain, misalnya: data sparepart, transaksi, laporan)_

## 🛠️ Teknologi

- [Laravel](https://laravel.com) (PHP) dengan Blade templating
- [Vite](https://vitejs.dev) untuk aset frontend
- MySQL / MariaDB _(sesuaikan dengan database yang kamu pakai)_
- PHPUnit untuk testing

## 📋 Kebutuhan Sistem

- PHP >= 8.2
- Composer
- Node.js & npm
- MySQL / MariaDB

## 🚀 Instalasi

1. Clone repository

   ```bash
   git clone https://github.com/sibaikun/BengkalChrismaMotor.git
   cd BengkalChrismaMotor
   ```

2. Install dependensi

   ```bash
   composer install
   npm install
   ```

3. Salin file environment dan generate key

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Atur koneksi database di file `.env`

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. Jalankan migrasi (dan seeder jika ada)

   ```bash
   php artisan migrate --seed
   ```

6. Jalankan aplikasi

   ```bash
   npm run dev
   php artisan serve
   ```

   Buka `http://localhost:8000` di browser.

## 🗂️ Struktur Folder

| Folder       | Keterangan                              |
| ------------ | --------------------------------------- |
| `app/`       | Controller, model, dan logika aplikasi  |
| `database/`  | Migrasi dan seeder                      |
| `resources/` | View Blade, CSS, dan JS                 |
| `routes/`    | Definisi route                          |
| `public/`    | Aset publik (gambar, dll.)              |
| `tests/`     | Pengujian otomatis                      |

## 🧪 Testing

```bash
php artisan test
```

## 👤 Pembuat

**Sibaikun** – Mahasiswa Universitas Semarang (USM)
GitHub: [@sibaikun](https://github.com/sibaikun)

## 📄 Lisensi

Proyek ini menggunakan lisensi [MIT](https://opensource.org/licenses/MIT).