## 1. Arsitektur Proyek (MVC - Model View Controller)

Proyek ini dibangun menggunakan **Laravel** dengan pola desain **MVC (Model-View-Controller)** berbasis _Server-Side Rendering (SSR)_:

```mermaid
graph TD
    A["🌐 Browser / User"] -->|1. HTTP Request| B["🛣️ Routes (routes/web.php)"]
    B -->|2. Cek Auth & Middleware Role| C["⚙️ Controller (app/Http/Controllers)"]
    C -->|3. Query / Manipulasi Data| D["🗄️ Model Eloquent (app/Models)"]
    D -->|4. Ambil Data DB| E["💾 Database SQLite/MySQL"]
    D -->|5. Return Data Objek| C
    C -->|6. Kirim Data ke View| F["🎨 Blade View (resources/views)"]
    F -->|7. Render HTML & CSS (Tailwind)| A
```

---

## 2. Struktur Komponen Utama Kode

### A. Routing & Access Control (`routes/`)

Seluruh jalur akses ditentukan pada [routes/web.php](file:///c:/laragon/www/project-kerja-praktik/routes/web.php). Pengamanan dilakukan menggunakan Middleware:

- **`auth`**: Mengharuskan pengguna sudah login.
- **`role:user`**: Akses khusus Karyawan (Pelapor).
- **`role:technician,admin`**: Akses untuk Teknisi & Administrator.

### B. Database Models (`app/Models/`)

1. **[User.php](file:///c:/laragon/www/project-kerja-praktik/app/Models/User.php)**: Menyimpan data pengguna aplikasi (`role`: `user`, `technician`, atau `admin`).
2. **[Category.php](file:///c:/laragon/www/project-kerja-praktik/app/Models/Category.php)**: Master data kategori kendala IT (misal: _Hardware_, _Software_, _Network_).
3. **[Ticket.php](file:///c:/laragon/www/project-kerja-praktik/app/Models/Ticket.php)**: Tabel utama tiket kendala. Memiliki relasi:
    - `belongsTo(User::class, 'user_id')` (Pelapor)
    - `belongsTo(User::class, 'technician_id')` (Teknisi PJ)
    - `belongsTo(Category::class)` (Kategori)
    - `hasMany(TicketLog::class)` (Riwayat & Diskusi)
4. **[TicketLog.php](file:///c:/laragon/www/project-kerja-praktik/app/Models/TicketLog.php)**: Catatan riwayat pengerjaan, perubahan status, dan balasan percakapan antara pelapor dan teknisi.

---

## 3. Penjelasan Alur CRUD Tiket (Create, Read, Update, Delete)

Pengelolaan tiket pengguna ditangani oleh **[TicketController.php](file:///c:/laragon/www/project-kerja-praktik/app/Http/Controllers/User/TicketController.php)**:

### 🟢 1. CREATE (Membuat Tiket Baru)

1. **Form Input**: User mengklik _"Buat Tiket Baru"_ (`GET /user/tickets/create`). Method `create()` mengambil seluruh kategori dan menampilkan [create.blade.php](file:///c:/laragon/www/project-kerja-praktik/resources/views/user/tickets/create.blade.php).
2. **Proses Simpan**: Saat form disubmit (`POST /user/tickets`), method `store()` menjalankan langkah:
    - **Validasi Input**: Memastikan judul, deskripsi, kategori, dan urgensi terisi, serta tipe lampiran gambar valid.
    - **Unggah Lampiran**: Jika ada foto, disimpan ke folder `storage/app/public/tickets/`.
    - **Generate Kode Tiket Unik**: Membuat kode otomatis seperti `TCK-20260913-A1B2`.
    - **Simpan DB**: Menyimpan ke tabel `tickets` dan membuat catatan log awal pengajuan di `ticket_logs`.

---

### 🔵 2. READ (Melihat & Memantau Tiket)

1. **Daftar Tiket (`index`)**: `GET /user/tickets`
    - Mengambil daftar tiket milik user yang sedang login (`where('user_id', auth()->id())`).
    - Mendukung pencarian (`q`) dan _pagination_ yang dirender pada [index.blade.php](file:///c:/laragon/www/project-kerja-praktik/resources/views/user/tickets/index.blade.php).
2. **Detail Tiket (`show`)**: `GET /user/tickets/{id}`
    - Mengambil detail 1 tiket beserta relasi `category`, `technician`, dan `logs.user`.
    - Dirender pada [show.blade.php](file:///c:/laragon/www/project-kerja-praktik/resources/views/user/tickets/show.blade.php) untuk menampilkan detail masalah dan riwayat balasan teknisi.

---

### 🟡 3. UPDATE (Mengubah Data Tiket)

1. **Pengeditan oleh Pelapor (Karyawan)**:
    - **Syarat Utama**: Hanya bisa dilakukan jika **`status === 'open'`** (belum dikerjakan teknisi).
    - **Form Edit**: `GET /user/tickets/{id}/edit` membuka [edit.blade.php](file:///c:/laragon/www/project-kerja-praktik/resources/views/user/tickets/edit.blade.php) dengan inputan yang terisi data lama.
    - **Proses Update**: `PUT /user/tickets/{id}` memvalidasi data baru, memperbarui record database, dan menambahkan catatan ke `ticket_logs` bahwa pelapor memperbarui data tiket.
2. **Perubahan Status oleh Teknisi**:
    - Ditangani oleh [TicketHandlingController.php](file:///c:/laragon/www/project-kerja-praktik/app/Http/Controllers/Tech/TicketHandlingController.php#L78-L110) melalui `PATCH /tech/tickets/{id}/status` untuk mengubah status dari `open` ➔ `in_progress` ➔ `resolved` / `closed`.

---

### 🔴 4. DELETE (Menghapus Tiket)

1. **Pemicu**: User mengklik tombol _"Hapus"_ pada daftar atau detail tiket (`DELETE /user/tickets/{id}`).
2. **Syarat Keamanan**: Method `destroy()` memastikan tiket milik user aktif dan **`status === 'open'`**.
3. **Proses Pembersihan**:
    - Menghapus file gambar lampiran dari disk penyimpanan jika ada (`Storage::disk('public')->delete(...)`).
    - Menghapus record tiket dari database (Catatan di `ticket_logs` otomatis ikut terhapus karena aturan database `onDelete('cascade')`).

---

## 4. Alur Autentikasi & Tampilan Layout

- **Layout Utama**: Seluruh halaman menggunakan [layouts/app.blade.php](file:///c:/laragon/www/project-kerja-praktik/resources/views/layouts/app.blade.php) yang berisi Sidebar Navigasi ([navigation.blade.php](file:///c:/laragon/www/project-kerja-praktik/resources/views/layouts/navigation.blade.php)), Top Bar, Breadcrumb otomatis, dan area konten `$slot`.
- **Interaktivitas UI**: Menggunakan Tailwind CSS untuk styling dan Alpine.js untuk fitur interaktif ringan (seperti toggle sidebar, dropdown profil, dan pratinjau unggahan gambar).
