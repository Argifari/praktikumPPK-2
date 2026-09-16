# JARA - Advanced Todo List Application (Backend API)

Repositori ini berisi backend RESTful API untuk aplikasi **JARA**, sebuah platform manajemen tugas (Todo List) tingkat lanjut yang mendukung pengelolaan tugas pribadi maupun kolaborasi tim. 

Sistem ini dibangun menggunakan **Laravel** dengan pendekatan *token-based authentication* (Sanctum) dan membagi struktur kerja ke dalam 3 modul utama.

---

## 📑 Software Requirement Specification (SRS)

Daftar spesifikasi kebutuhan fungsional (FR) berikut menjadi acuan utama pengembangan sistem:

### Modul 1: User & Authentication Management
*   **FR-AUTH-01**: Sistem harus menyediakan antarmuka bagi Admin untuk menambahkan akun pengguna baru (Email, Nama, Password default).
*   **FR-AUTH-02**: Admin dapat menghapus akun pengguna dari sistem.
*   **FR-AUTH-03**: Pengguna (Admin & User) dapat melakukan autentikasi (Login) untuk mendapatkan akses sistem dan penutupan sesi (Logout).

### Modul 2: Project / List Management
*   **FR-LIST-01**: Pengguna dapat membuat daftar tugas baru (Personal atau Team Project).
*   **FR-LIST-02**: Owner (Pemilik) dapat mengubah nama, deskripsi, atau menghapus daftar tugas miliknya.
*   **FR-LIST-03**: Owner dapat mengundang (*invite*) pengguna lain ke dalam daftar tugasnya berdasarkan email/username sebagai *collaborator*.
*   **FR-LIST-04**: Owner dapat mengeluarkan anggota (*remove collaborator*) dari daftar tugas.
*   **FR-LIST-05**: Sistem harus menampilkan indikator progres penyelesaian tugas (persentase `%` tugas selesai dibandingkan dengan total tugas) pada tiap daftar project.

### Modul 3: Task Management & Organization
*   **FR-TASK-01**: Anggota (*Owner* & *Collaborator*) dalam list dapat membuat tugas baru di dalam daftar tersebut.
*   **FR-TASK-02**: Pengguna dapat menetapkan atribut tugas meliputi: Judul, Deskripsi, Tenggat Waktu (*Due Date*), dan Tingkat Prioritas (*Low, Medium, High*).
*   **FR-TASK-03**: Pengguna dapat mengubah seluruh rincian/atribut tugas yang telah dibuat.
*   **FR-TASK-04**: Anggota dalam list dapat mengubah status tugas secara cepat menjadi Selesai (*Completed*) atau Belum Selesai (*Pending*).
*   **FR-TASK-05**: Pengguna dapat melakukan filter dan pengurutan (*sorting*) tugas berdasarkan prioritas, tenggat waktu, atau status penyelesaian.

---



