# 🗳️ Sistem E-Voting Laravel

Aplikasi **Sistem E-Voting** berbasis Laravel + Tailwind CSS untuk kebutuhan organisasi / kampus.  
Fitur ini memudahkan proses pemilihan secara online, transparan, dan aman.

---

## ✨ Fitur Utama

### 🔑 Autentikasi
- Registrasi & login user (pemilih).
- Role user **Admin** & **User**.
- Logout aman dengan CSRF token.

### 👥 Role User
- **Admin**
  - Kelola *Election* (pemilihan).
  - Kelola *Position* (jabatan yang dipilih, misalnya Ketua, Wakil, dll).
  - Kelola *Candidate* (kandidat per posisi).
  - Lihat hasil voting realtime dalam bentuk grafik.
  - Export hasil ke PDF.
- **User**
  - Lihat daftar *Election* yang aktif.
  - Ikut memberikan **1 suara untuk 1 kandidat**.
  - Tidak bisa mengubah pilihan setelah vote.
  - Melihat ringkasan hasil (tanpa grafik detail).

### 📊 Voting
- Pemilih hanya bisa **memberikan 1 suara**.
- Kandidat ditampilkan dengan:
  - Foto profil (klik untuk zoom).
  - Nama & posisi.
  - Visi & Misi singkat.
- Form voting otomatis tertutup setelah user memilih.

### 📈 Hasil Pemilihan
- **Admin**
  - Lihat grafik realtime (Chart.js).
  - Filter hasil per posisi atau gabungan.
  - Download laporan hasil voting dalam bentuk **PDF**.
- **User**
  - Melihat ringkasan hasil per posisi (tanpa grafik).

### 🖼️ UI/UX
- Dibangun dengan **Tailwind CSS** → clean, responsive, modern.
- Komponen:
  - Card untuk daftar election.
  - Form login & register elegan.
  - Modal zoom foto kandidat.
  - Dashboard Admin dengan tabel rapi & konsisten.
- Footer konsisten


---

## 📂 Struktur Fitur

### Controllers
- `AuthController` → Login, register, logout.
- `DashboardController` → Dashboard umum & admin.
- `Admin\ElectionController` → CRUD Election.
- `Admin\PositionController` → CRUD Position.
- `Admin\CandidateController` → CRUD Candidate.
- `VoteController` → Voting logic user.
- `ResultController` → Hasil voting (admin & user), export PDF.

### Routes
- `/` → Home (daftar election aktif).
- `/login` → Login user.
- `/register` → Register user.
- `/admin` → Dashboard admin.
- `/admin/elections` → Kelola elections.
- `/admin/elections/{id}/results` → Hasil voting admin (grafik).
- `/admin/elections/{id}/results/pdf` → Export hasil ke PDF.
- `/e/{id}/vote` → Voting user.
- `/e/{id}/results` → Ringkasan hasil user.

### Views (Blade)
- `layouts/app.blade.php` → Layout utama user.
- `layouts/admin.blade.php` → Layout utama admin.
- `home.blade.php` → Daftar election aktif.
- `auth/login.blade.php` → Form login.
- `vote/index.blade.php` → Halaman voting.
- `results/index.blade.php` → Hasil admin (grafik).
- `results/user.blade.php` → Hasil user (ringkasan).
- `results/pdf.blade.php` → Template PDF hasil.

---

## 🛠️ Teknologi

- **Laravel 12.x**
- **PHP 8.4**
- **Tailwind CSS**
- **Chart.js** (grafik realtime)
- **Barryvdh DomPDF** (export PDF)
- **MySQL** (database)

---