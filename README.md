# Portfolio Generator

Sistem generator portfolio yang memungkinkan pengguna untuk membuat portfolio profesional dengan berbagai template yang menarik.

## Fitur Utama

### 🎨 4 Template Portfolio
- **Modern Clean**: Template modern dengan desain bersih dan minimalis
- **Creative Portfolio**: Template kreatif dengan desain yang menarik dan colorful
- **Professional Business**: Template profesional untuk bisnis dengan desain formal
- **Minimalist**: Template minimalis dengan fokus pada konten

### 📝 Form Input Portfolio
- Nama proyek
- Judul proyek
- Deskripsi proyek
- Upload gambar utama
- Upload gambar tambahan (multiple)
- Pilihan template

### 🖼️ Upload Gambar
- Upload gambar utama proyek
- Upload multiple gambar tambahan
- Preview gambar sebelum upload
- Validasi format dan ukuran file

### 📤 Export & Download
- Download portfolio sebagai file HTML
- Preview portfolio dengan template yang dipilih
- Export siap pakai untuk hosting

### 🎯 Interface User-Friendly
- Multi-step form untuk kemudahan input
- Preview real-time
- Responsive design
- Modern UI dengan Bootstrap 5

## Instalasi

1. Clone repository ini
2. Install dependencies:
   ```bash
   composer install
   npm install
   ```

3. Setup environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Setup database:
   ```bash
   php artisan migrate
   php artisan db:seed --class=PortfolioTemplateSeeder
   ```

5. Buat storage link:
   ```bash
   php artisan storage:link
   ```

6. Jalankan server:
   ```bash
   php artisan serve
   ```

## Cara Penggunaan

### 1. Membuat Portfolio Baru
1. Klik tombol "Buat Portfolio Baru"
2. Pilih template yang diinginkan
3. Isi informasi proyek (nama, judul, deskripsi)
4. Upload gambar utama dan gambar tambahan
5. Klik "Simpan Portfolio"

### 2. Mengelola Portfolio
- Lihat daftar portfolio di halaman utama
- Edit portfolio yang sudah ada
- Hapus portfolio yang tidak diperlukan
- Download portfolio sebagai HTML

### 3. Preview & Export
- Preview portfolio dengan template yang dipilih
- Download portfolio sebagai file HTML siap pakai
- File HTML dapat dihosting di server manapun

## Struktur Database

### Tabel `portfolios`
- `id`: Primary key
- `project_name`: Nama proyek
- `project_title`: Judul proyek
- `description`: Deskripsi proyek
- `project_image`: Path gambar utama
- `additional_images`: JSON array gambar tambahan
- `template_id`: ID template yang dipilih
- `custom_data`: JSON data tambahan
- `status`: Status portfolio (draft/published)
- `created_at`, `updated_at`: Timestamps

### Tabel `portfolio_templates`
- `id`: Primary key
- `name`: Nama template
- `slug`: Slug template
- `description`: Deskripsi template
- `preview_image`: Gambar preview
- `template_html`: HTML template
- `template_css`: CSS template
- `template_config`: Konfigurasi template (JSON)
- `is_active`: Status aktif template
- `created_at`, `updated_at`: Timestamps

## Template System

Setiap template memiliki:
- HTML structure dengan placeholder
- CSS styling yang unik
- Konfigurasi template (max images, color scheme, dll)
- Placeholder yang akan diganti dengan data portfolio:
  - `{{project_name}}`
  - `{{project_title}}`
  - `{{description}}`
  - `{{project_image}}`
  - `{{additional_images}}`

## Teknologi yang Digunakan

- **Backend**: Laravel 10
- **Frontend**: Bootstrap 5, Font Awesome
- **Database**: SQLite (default) / MySQL / PostgreSQL
- **File Storage**: Laravel Storage
- **Image Processing**: Native PHP

## Kontribusi

1. Fork repository ini
2. Buat branch fitur baru (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## Lisensi

Distributed under the MIT License. See `LICENSE` for more information.

## Kontak

Jika ada pertanyaan atau saran, silakan buat issue di repository ini.