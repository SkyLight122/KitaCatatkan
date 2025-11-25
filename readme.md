## KitaCatatkan

KitaCatatkan merupakan Sebuah Website Reminder Khusus Sekolah yang bertujuan untuk mengingatkan para siswa untuk melihat dan mengingat tugas-tugas maupun sekolah atau rumah, serta list-list ulangan kedepannya
di website kalian bisa menjadi editor list tugas untuk membuat daftar tugas yang ada tetapi khusus kepada pengurus kelas.

## Instalasi

1.Klon repositori ini

Laragon - Terminal
git clone https://github.com/SkyLight122/KitaCatatkan.git

2.buka file repositori ini

  Laragon - Terminal
  CD KitaCatatkan

3.Buka Web dari file repositori

  Laragon - Terminal
  PHP artisan serve

  Click link website nya

Atau tinggal buka website dengan link : KitaCatatkan.ski.id

## Penggunaan

1.kalian perlu login atau daftar akun di web ini.

2.Navigasi
  di web ini terdapat fitur berupa:

  A.Beranda
   1.Make new Grup = di beranda ini kalian bisa membuat grup baru untuk kelas kalian masing-masing, dengan memasukkan nama, deskripsi dan julukan/kelas.
   2.Join Grup = kalian bergabung ke grup kelas yang sudah dibuat oleh pembuatnya.
   3.Daftar Tugas = Daftar ini berisi tampilan tugas yang ada, hanya menampilkan apakah tugas sudah selesai atau belum selesai.

  B.Halaman Daftar Tugas
   1.Kalender Note = kalender ini juga berisi daftar-daftar tugas yang sudah ditentukan oleh editor atau pengurus kelas, jika tekan notenya, akan muncul informasi tugas secara detail.
   2.Edit atau Tambahkan (khusus editor) = pengurus kelas mengisi tugas seperti mata pelajaran, tipe tugas dan deadlinenya.

Teknologi digunakan:
- Laravel
- Tailwind CSS
- Laravel Flux

## Kontribusi

kita mengadakan kontribusi dari kalian semua yang berminat bekerjasama untuk mengimprovisasi KitaCatatkan agar lebih menarik, berguna dan sederhana untuk para user
dengan memperbaiki bugs, menghadirkan fitur baru, atau membagi materi baru, kerjasamamu sangat berharga bagi kami.

### Cara berkontribusi
1.

### Panduan Kontribusi
-

## Link Arsitektur = https://www.figma.com/design/FGJO8CIzzWOAqPWuMIphBn/Project-Calendar?node-id=0-1&p=f&t=lPtLX7w0krabyBwW-0

kami berterimakasih yang sudah membaca atau mengikuti panduan ini, atas perhatian anda, saya ucapkan terimakasih.

## Anggota tim
- Hendry Sen
- Shane Marcellino Emmanuel
- Vincent Nicola











SAS


# 📘 KitaCatatkan  
Aplikasi manajemen tugas dan pengingat berbasis web yang dibuat khusus untuk lingkungan sekolah.  
KitaCatatkan membantu siswa melihat, mengingat, dan mengatur tugas sekolah, ulangan, serta catatan penting lainnya — baik secara pribadi maupun bersama dalam grup kelas.

Aplikasi ini digunakan oleh siswa serta pengurus kelas (editor) untuk mengelola daftar tugas secara efisien, modern, dan terstruktur.

---

## 🚀 Fitur Utama

### 🟦 1. Manajemen Grup Kelas
- Membuat grup kelas baru.
- Bergabung ke grup menggunakan kode unik.
- Setiap grup memiliki halaman tugas, anggota, dan kontrol editor.

### 🟩 2. Tugas Personal & Grup
- Tambah, edit, hapus tugas.
- Status tugas (Belum Dimulai, Sedang Dikerjakan, Selesai).
- Prioritas tugas (Rendah, Sedang, Tinggi).
- Tipe tugas (badge grayscale).
- Kategori tugas (custom per user).
- Notifikasi keterlambatan tugas (deadline sudah lewat).

### 🟨 3. Sistem Editor (Pengurus Kelas)
- Hanya editor yang bisa membuat atau mengelola tugas grup.
- Anggota hanya bisa melihat dan update progress.

### 🟧 4. Kalender Tugas
- Menampilkan tugas sesuai tanggal.
- Klik tanggal → muncul detail tugas.
- Cocok untuk list ulangan & deadline.

### 🟪 5. Fitur Pencarian, Filter & Sorting
- Filter kategori, prioritas, tipe tugas, status.
- Sortir berdasarkan deadline & status.
- Pencarian tugas real-time.

### ⚫ 6. Tampilan Modern
Menggunakan:
- Laravel
- Livewire + Laravel Flux
- Tailwind CSS
- Alpine.js  
Dengan desain minimalis & responsif.

---

## 🗂 Entitas Utama

### **Users**
- name  
- email  
- password  
- profile photo (opsional)

### **Groups**
- title  
- description  
- label/kelas  
- image (opsional)  
- invite key  

### **Assignments**
- title  
- description  
- due_date  
- assignment_type (personal / group)  
- category_id  
- priority_id  
- type_assignment_id (nullable)  
- status_id  

### **Categories**
- name  
- icon  
- user_id  

### **Type Assignments**
- category (nama tipe)  
- color (0–255 grayscale)  
- user_id  

### **Priorities**
- name  
- level  
- color  

---

## 🛠 Instalasi (Local Development)

### 1️⃣ Clone repository
```bash
git clone https://github.com/SkyLight122/KitaCatatkan.git
###2️⃣ Masuk ke folder project
cd KitaCatatkan
###3️⃣ Install dependencies
composer install
###4️⃣ Copy environment
cp .env.example .env
###5️⃣ Generate key aplikasi
php artisan key:generate
###6️⃣ Konfigurasi database
Buat database baru, lalu edit file .env:

DB_DATABASE=nama_database
DB_USERNAME=root
DB_PASSWORD=
###7️⃣ Migrasi database
php artisan migrate
###8️⃣ Buat storage link
php artisan storage:link
###9️⃣ Jalankan server
php artisan serve
###🔟 Jalankan Vite (melalui Composer)
KitaCatatkan menggunakan:
composer run dev


##🧪 Penggunaan
1. Buat akun atau login
Pengguna harus login untuk mengakses dashboard tugas.

2. Beranda
- Create Group → membuat grup/kelas baru.
- Join Group → masuk menggunakan kode grup.
- Daftar Tugas Pribadi → semua tugas personal user.

3. Daftar Tugas
- Lihat tugas personal & grup.
- Gunakan fitur sorting, filtering, dan pencarian.
- Klik tugas → tampil detail dalam modal.

4. Kalender Tugas

- Menampilkan tugas berdasarkan tanggal.
- Digunakan untuk list ulangan dan deadline penting.


##🧩 Kontribusi

Repository ini dikelola oleh satu maintainer utama.
Kontribusi anggota tim bersifat non-teknis, terutama:
- Pembuatan desain UI/UX
- Pembuatan blueprint arsitektur aplikasi
- Ide fitur
- Validasi interface
- Pengembangan tampilan Figma
Proses coding, struktur repository, hingga manajemen backend sepenuhnya dikelola oleh maintainer tunggal (Vincent Nicola).
Kontribusi tambahan berupa:
- Feedback
- Penemuan bug
- Pengajuan ide fitur
- sangat dihargai.


##🗺 Desain Arsitektur

Figma project:
https://www.figma.com/design/FGJO8CIzzWOAqPWuMIphBn/Project-Calendar?node-id=0-1&p=f&t=uOceWhSdTUAFIgmj-0


##👥 Anggota Tim
- Hendry Sen —> Front end
- Shane Marcellino Emmanuel —> Designer
- Vincent Nicola —> Backend


##❤️ Penutup
Terima kasih telah menggunakan aplikasi KitaCatatkan.
Semoga aplikasi ini membantu siswa dalam mengatur tugas secara lebih terstruktur, efisien, dan modern.
