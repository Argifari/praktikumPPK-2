# JARA - Advanced Todo List Application (Backend API)

Repositori ini berisi backend RESTful API untuk aplikasi **JARA**, sebuah platform kolaborasi manajemen tugas (Todo List). Sistem ini dibangun menggunakan **Laravel** dan menggunakan pendekatan token-based authentication (Sanctum).

Dokumen ini disusun sebagai panduan *sprint* awal untuk memahami struktur database, otorisasi *Policy*, alur kolaborasi (*Invite/Remove*), dan titik temu (*endpoint*) setiap modul.

---

## 🛠️ Tech Stack & Requirements

*   **Framework:** Laravel 10 / 11
*   **Database:** MySQL 8.0+
*   **Authentication:** Laravel Sanctum
*   **PHP Version:** ^8.1

---

## 🗄️ Arsitektur Database (Database Schema)

Aplikasi JARA menggunakan 4 tabel utama dengan skema relasional berikut:

1.  **`users`**
    *   `id` (Primary Key)
    *   `name`, `email` (Unique), `password_hash`
    *   `role` (Enum: `admin`, `user`) - *Default: `user`*
2.  **`projects`**
    *   `id` (Primary Key)
    *   `title`, `description`
    *   `owner_id` (Foreign Key -> `users.id` ON DELETE CASCADE)
3.  **`project_user` (Pivot Table Kolaborasi)**
    *   `id` (Primary Key)
    *   `project_id` (Foreign Key -> `projects.id` ON DELETE CASCADE)
    *   `user_id` (Foreign Key -> `users.id` ON DELETE CASCADE)
    *   `joined_at` (Timestamp)
4.  **`tasks`**
    *   `id` (Primary Key)
    *   `project_id` (Foreign Key -> `projects.id` ON DELETE CASCADE)
    *   `title`, `description`
    *   `priority` (Enum: `low`, `medium`, `high`) - *Default: `medium`*
    *   `status` (Enum: `pending`, `completed`) - *Default: `pending`*
    *   `due_date` (Date, Nullable)
    *   `created_by` (Foreign Key -> `users.id` ON DELETE CASCADE)

---

## 🚀 Panduan Instalasi Lokal

1.  **Clone repositori & masuk ke folder direktori:**
    ```bash
    git clone [https://github.com/your-org/jara-backend.git](https://github.com/your-org/jara-backend.git)
    cd jara-backend
    ```

2.  **Install Dependensi Composer:**
    ```bash
    composer install
    ```

3.  **Setup Environment Variables:**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    *Ubah kredensial koneksi database MySQL pada file `.env` (misal: `DB_DATABASE=jara_db`, sesuaikan `DB_USERNAME` dan `DB_PASSWORD`).*

4.  **Jalankan Migration & Database Seeder:**
    ```bash
    php artisan migrate --seed
    ```

5.  **Jalankan Server:**
    ```bash
    php artisan serve
