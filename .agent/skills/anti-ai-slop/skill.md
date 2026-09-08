# 🎨 SKILL.md — Anti-Slop UI Guidelines for IT Helpdesk System

Panduan standar kualitas UI/UX untuk memastikan antarmuka proyek **Helpdesk & Ticket Tracking System (Laravel + Tailwind CSS)** tampil modern, bersih, profesional, dan efisien.

---

## 🛑 Principles of Anti-Slop UI

1. **Information Hierarchy First:** Data penting (Kode Tiket, Status, Prioritas) harus lebih mencolok daripada elemen dekoratif.
2. **Scannability:** Pengguna (Admin/Teknisi) harus bisa memahami status tiket dalam waktu kurang dari 3 detik.
3. **No Unnecessary Clutter:** Hindari animasi berlebihan, efek _shadow_ yang kelewatan, warna neon, atau komponen yang tidak memiliki fungsi operasional.
4. **Consistent Feedback:** Setiap tindakan pengguna (Submit, Assign, Update Status) wajib memberikan _feedback_ visual yang jelas (Flash Message, Toast, Loading State).

---

## 🎨 Color Palette & Status Badging Rules

Gunakan sistem pewarnaan yang konsisten berbasis konteks status dan prioritas. Jangan gunakan warna secara acak.

### 1. Status Badges (`tickets.status`)

| Status          | Tailwind Classes                                           | Visual Style                      |
| :-------------- | :--------------------------------------------------------- | :-------------------------------- |
| **OPEN**        | `bg-blue-50 text-blue-700 border border-blue-200`          | Biru Soft (Perlu Perhatian)       |
| **IN_PROGRESS** | `bg-amber-50 text-amber-700 border border-amber-200`       | Kuning/Oranye (Sedang Dikerjakan) |
| **RESOLVED**    | `bg-emerald-50 text-emerald-700 border border-emerald-200` | Hijau Soft (Selesai/Sukses)       |
| **CLOSED**      | `bg-gray-100 text-gray-600 border border-gray-200`         | Abu-abu (Arsip/Tutup)             |

### 2. Priority Indicators (`tickets.priority`)

| Priority   | Tailwind Classes                                    | Visual Style     |
| :--------- | :-------------------------------------------------- | :--------------- |
| **LOW**    | `bg-slate-100 text-slate-600`                       | Netral           |
| **MEDIUM** | `bg-sky-100 text-sky-700`                           | Informatif       |
| **HIGH**   | `bg-orange-100 text-orange-700 font-semibold`       | Peringatan       |
| **URGENT** | `bg-rose-100 text-rose-700 font-bold animate-pulse` | Kritis (Darurat) |

---

## 📐 Layout & Micro-Interactions Standards

### A. Tables (Daftar Tiket)

- ❌ **Slop:** Tabel tanpa batasan teks (_text overflow_), kolom menumpuk di tampilan mobile, warna zebra-striping terlalu tajam.
- ✅ **Clean:**
    - Gunakan `truncate` dan atribut `title="..."` untuk teks deskripsi/judul yang panjang.
    - Terapkan `overflow-x-auto` pada pembungkus tabel agar _responsive_.
    - Gunakan warna latar `hover:bg-gray-50/50` yang sangat halus untuk pengalaman interaktif.

### B. Forms (Form Pengajuan Tiket)

- ❌ **Slop:** Form terlalu panjang tanpa grup, label input tidak jelas, tombol submit yang tidak memiliki state visual saat diproses.
- ✅ **Clean:**
    - Kelompokkan input secara logis (misal: Judul & Kategori berdampingan via `grid grid-cols-1 md:grid-cols-2`).
    - Tautkan pesan error validasi Blade (`@error`) langsung di bawah masing-masing input dengan warna `text-rose-500 text-xs mt-1`.
    - Tambahkan indikator visual untuk file upload (tampilkan nama file atau _thumbnail preview_ saat foto diunggah).

### C. Sidebar & Handling Card (Detail Tiket)

- ❌ **Slop:** Form penugasan teknisi dan status bercampur aduk tanpa pemisah.
- ✅ **Clean:**
    - Pisahkan area **Content & Timeline Logs** (sisi kiri, 2/3 lebar) dengan **Action Sidebar** (sisi kanan, 1/3 lebar).
    - Bungkus form _Assign Teknisi_ khusus Admin dalam kontainer pemisah yang jelas (`border-2 border-indigo-100 bg-indigo-50/30 p-4 rounded-lg`).

---

## 🛠️ Tailwind CSS Standard Snippets

### 1. Clean Action Card Container

```html
<div
    class="bg-white rounded-xl border border-gray-200/80 shadow-sm p-5 hover:shadow-md transition-shadow duration-200"
>
    <!-- Card Content -->
</div>
```
