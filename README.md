# KitaCatatkan

KitaCatatkan adalah sebuah website reminder khusus sekolah yang bertujuan membantu siswa mengingat tugas-tugas maupun ulangan mendatang.
Website ini juga memungkinkan **pengurus kelas** menjadi editor untuk membuat atau mengatur daftar tugas bagi anggota kelompok.

## Instalasi

1. Clone repositori

```bash
git clone https://github.com/SkyLight122/KitaCatatkan.git
```

2. Masuk ke folder project
   cd KitaCatatkan

3. Install dependency backend (Laravel)
   composer install

4. Install dependency frontend (Tailwind, Vite, dll.)
   npm install

5. Copy file environment dan buat key Laravel
   cp .env.example .env
   php artisan key:generate
   Catatan: Jangan lupa konfigurasi database di file .env sesuai pengaturan lokal kamu.

6. Jalankan migrasi database (opsional kalau sudah ada schema)
   php artisan migrate

7. Jalankan server Laravel
   php artisan serve

8. Jalankan frontend (Vite) untuk Tailwind CSS
   npm run dev

Atau jika sudah ada shortcut di composer.json:
composer run dev

# Penggunaan

1. Login atau daftar akun untuk mulai menggunakan aplikasi.
2. Navigasi utama:
    - Beranda
        - Make New Group. Membuat grup kelas baru dengan nama, deskripsi, dan julukan/kelas.
        - Join Group. Bergabung ke grup kelas yang sudah dibuat.
        - Daftar Tugas. Melihat daftar tugas dengan status selesai/belum selesai.
    - Halaman Daftar Tugas
        - Kalender Note → Menampilkan daftar tugas di kalender, klik untuk detail.
        - Edit atau Tambahkan Tugas (khusus editor/pengurus kelas).

# Teknologi yang digunakan:

-   Laravel
-   Tailwind CSS
-   Laravel Flux
-   Vite

# Kontribusi

Kami membuka kontribusi dari siapa pun yang ingin membantu improvisasi KitaCatatkan agar lebih menarik, berguna, dan sederhana bagi pengguna.

Kontribusi dapat berupa:

-   Memperbaiki bug
-   Menambahkan fitur baru
-   Membagikan ide atau materi pengembangan

Cara berkontribusi

1. Fork repositori ini
2. Buat branch baru:
   git checkout -b fitur-baru
3. Lakukan perubahan dan commit
4. Push branch ke repo forked
5. Ajukan pull request

# Link Arsitektur

https://www.figma.com/design/FGJO8CIzzWOAqPWuMIphBn/Project-Calendar?node-id=0-1&p=f&t=lPtLX7w0krabyBwW-0

# Anggota Tim

-   Hendry Sen
-   Shane Marcellino Emmanuel
-   Vincent Nicola
