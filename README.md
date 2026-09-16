# JARA — Advanced Todo List Web Application
> **Dokumentasi Spesifikasi Perangkat Lunak (SRS) & Panduan Pengembang**

JARA adalah aplikasi web manajemen tugas (*todo list*) modern yang mendukung pengelolaan pekerjaan personal maupun kolaborasi tim. Aplikasi ini dilengkapi dengan mekanisme autentikasi terproteksi, manajemen peran (*Admin, List Owner, Member*), transaksi basis data atomik untuk integritas data, serta pemantauan progres penyelesaian tugas secara *real-time*.

---

## 📋 Daftar Isi
1. [Peran Pengguna & Matriks Hak Akses](#-peran-pengguna--matriks-hak-akses)
2. [Spesifikasi Kebutuhan Fungsional (SRS)](#-spesifikasi-kebutuhan-fungsional-srs)
3. [Arsitektur Transaksi & Keamanan (NFR)](#-arsitektur-transaksi--keamanan-nfr)
4. [Pembagian Tugas Tim & User Stories](#-pembagian-tugas-tim--user-stories)
5. [Struktur Folder & Isolasi Berkas (Anti-Conflict)](#-struktur-folder--isolasi-berkas-anti-conflict)
6. [Panduan Instalasi & Pengoperasian](#-panduan-instalasi--pengoperasian)

---

## 👥 Peran Pengguna & Matriks Hak Akses

| Peran Pengguna | Deskripsi & Hak Akses Sistem |
|---|---|
| **System Admin** | Mengelola siklus hidup akun pengguna dalam sistem (Tambah & Hapus akun). Tidak memiliki akses ke isi *list/task* privat pengguna lain. |
| **List Owner** | Pembuat daftar tugas (*project/list*). Berhak penuh mengedit/menghapus *list*, mengundang (*invite*) dan mengeluarkan (*remove*) anggota, serta mengelola seluruh *task*. |
| **Collaborator / Member** | Anggota yang diundang ke dalam daftar tugas tertentu. Berhak membuat, membaca, mengedit, dan mengupdate status *task* di dalam *list* tersebut. |

---

## ⚙️ Spesifikasi Kebutuhan Fungsional (SRS)

### Modul 1: Auth & User Management (Admin)
* **SRS-FR-01**: Sistem wajib menyediakan fungsi autentikasi (Login & Logout) menggunakan kredensial email dan password.
* **SRS-FR-02**: Admin dapat menambahkan akun pengguna baru dengan menginput nama, email, dan password. Input email wajib diuji keunikannya.
* **SRS-FR-03**: Admin dapat menghapus akun pengguna dari sistem. Penghapusan akun wajib menangani referensi keanggotaannya secara aman (*clean reference*).

### Modul 2: Project / List Management & Kolaborasi
* **SRS-FR-04**: Pengguna terautentikasi dapat membuat daftar tugas (*project/list*) baru. Sistem wajib secara otomatis menetapkan pembuat sebagai **Owner**.
* **SRS-FR-05**: Pemilik daftar (*List Owner*) berhak menghapus daftar tugas miliknya secara **atomik (*database transaction*)** melalui 3 tahapan:
  1. Menghapus seluruh tugas (*tasks*) di dalam daftar.
  2. Menghapus seluruh keanggotaan kolaborator (*project members*).
  3. Menghapus data utama daftar (*project*).
  * *Rule Atomisitas*: Jika salah satu tahapan gagal, sistem wajib melakukan **Rollback** otomatis seluruh transaksi.
* **SRS-FR-06**: Owner dapat mengundang (*invite*) dan mengeluarkan (*remove*) pengguna lain ke/dari daftar tugas berdasarkan email/username.

### Modul 3: Task Engine & Progress Tracking
* **SRS-FR-07**: Sistem wajib menghitung dan menampilkan persentase progres penyelesaian tugas secara rasional pada setiap daftar:
  $$\text{Progres (\%)} = \left( \frac{\text{Jumlah Task Selesai}}{\text{Total Task}} \right) \times 100\%$$

---

## 🛡️ Arsitektur Transaksi & Keamanan (NFR)

1. **Atomicity & ACID Compliance (`DB::transaction`)**:
   Setiap operasi penghapusan project (*SRS-FR-05*) terbungkus penuh dalam transaksi basis data untuk menghindari adanya *orphan data* jika terjadi kegagalan sistem di tengah proses.
2. **Strict Policy Authorization (403 Forbidden)**:
   Akses ke Controller dilindungi oleh **Laravel Policy**. Permintaan dari pengguna yang tidak berwenang (misal: *Collaborator* yang mencoba menghapus *project*) akan ditolak dengan respons status **`HTTP 403 Forbidden`**.
3. **Parameterized Queries / Prepared Statements**:
   Seluruh interaksi kueri basis data menggunakan ORM Eloquent / Query Builder terparameterisasi untuk memastikan kekebalan dari ancaman **SQL Injection**.
4. **Server-Side Validation**:
   Setiap *request* yang masuk divalidasi pada **Form Request Class** terpisah sebelum masuk ke logika Controller.

---

## 🧑‍💻 Pembagian Tugas Tim & User Stories

Pembagian tugas dibagi berdasarkan fitur (*Feature-Based / Vertical Slicing*) agar setiap pengembang memegang alur *end-to-end* (Database, Logic Backend, dan Frontend UI).

### 🔷 Programmer 1 — Auth & Admin Management
* **Programmer 1 - SRS-FR-01**: Sebagai pengguna (Admin/User), saya ingin dapat melakukan login dan logout menggunakan email dan password, agar saya dapat masuk ke dalam sistem dengan aman.
* **Programmer 1 - SRS-FR-02**: Sebagai Admin, saya ingin dapat menambahkan akun pengguna baru dengan nama, email unik, dan password, agar saya bisa memberikan akses masuk kepada pengguna baru.
* **Programmer 1 - SRS-FR-03**: Sebagai Admin, saya ingin dapat menghapus akun pengguna dari sistem dan menangani keterikatan datanya secara aman, agar akun tidak aktif dapat dibersihkan tanpa merusak database.

### 🔶 Programmer 2 — Project, Collaboration & Atomic Transaction
* **Programmer 2 - SRS-FR-04**: Sebagai pengguna terautentikasi, saya ingin dapat membuat daftar tugas (*project/list*) baru dan otomatis menjadi *Owner*, agar saya dapat mengelola ruang kerja tim/pribadi.
* **Programmer 2 - SRS-FR-05**: Sebagai *List Owner*, saya ingin dapat menghapus daftar tugas milik saya beserta seluruh isi *tasks* dan keanggotaan secara atomik (*transaction & rollback*), agar data terhapus bersih tanpa menyisakan data menggantung.
* **Programmer 2 - SRS-FR-06**: Sebagai *List Owner*, saya ingin dapat mengundang (*invite*) dan mengeluarkan (*remove*) anggota lain berdasarkan email/username, agar dapat berkolaborasi dalam satu ruang kerja.

### 🟢 Programmer 3 — Task Engine, Filtering & Progress Tracking
* **Programmer 3 - SRS-FR-07**: Sebagai pengguna, saya ingin melihat persentase progres penyelesaian tugas pada setiap daftar secara *real-time*, agar saya dapat memantau tingkat penyelesaian pekerjaan.
* **Programmer 3 - Task Engine (Pendukung SRS-FR-07)**: Sebagai anggota daftar (*Owner/Member*), saya ingin dapat membuat, mengedit, memfilter, dan mengubah status tugas (*pending/completed*), agar tugas-tugas terorganisir dan dapat diolah ke dalam indikator progres.

---

## 📁 Struktur Folder & Isolasi Berkas (Anti-Conflict)

Untuk meminimalisir bentrok Git (*merge conflict*), setiap programmer bekerja pada folder modul dan file rute terpisah:

```text
jara-app/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/                  <-- [Programmer 1] UserController.php, AuthController.php
│   │   │   ├── Project/                <-- [Programmer 2] ProjectController.php, ProjectMemberController.php
│   │   │   └── Task/                   <-- [Programmer 3] TaskController.php, TaskStatusController.php
│   │   ├── Middleware/
│   │   │   └── EnsureIsAdmin.php       <-- [Programmer 1]
│   │   └── Requests/
│   │       ├── Admin/                  <-- [Programmer 1] StoreUserRequest.php
│   │       ├── Project/                <-- [Programmer 2] StoreProjectRequest.php, InviteMemberRequest.php
│   │       └── Task/                   <-- [Programmer 3] StoreTaskRequest.php, UpdateTaskRequest.php
│   ├── Models/
│   │   ├── User.php                    <-- [Programmer 1]
│   │   ├── Project.php                 <-- [Programmer 2]
│   │   └── Task.php                    <-- [Programmer 3]
│   ├── Policies/
│   │   ├── ProjectPolicy.php           <-- [Programmer 2]
│   │   └── TaskPolicy.php              <-- [Programmer 3]
│   └── Services/
│       └── ProjectService.php          <-- [Programmer 2] (DB::transaction logic)
├── database/
│   └── migrations/
│       ├── 2026_01_01_000001_create_users_table.php          <-- [Programmer 1]
│       ├── 2026_01_01_000002_create_projects_table.php       <-- [Programmer 2]
│       ├── 2026_01_01_000003_create_project_user_table.php   <-- [Programmer 2]
│       └── 2026_01_01_000004_create_tasks_table.php          <-- [Programmer 3]
├── resources/views/
│   ├── admin/                          <-- [Programmer 1]
│   ├── auth/                           <-- [Programmer 1]
│   ├── projects/                       <-- [Programmer 2]
│   └── tasks/                          <-- [Programmer 3]
└── routes/
    ├── admin.php                       <-- [Programmer 1]
    ├── auth.php                        <-- [Programmer 1]
    ├── project.php                     <-- [Programmer 2]
    └── task.php                        <-- [Programmer 3]