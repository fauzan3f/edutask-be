# EduTask - Project Management System (Backend)

EduTask adalah sistem manajemen proyek berbasis web yang dirancang khusus untuk lingkungan akademik. Aplikasi ini memungkinkan dosen, mahasiswa, dan tim riset untuk berkolaborasi dalam proyek, mengelola tugas, dan melacak progres pekerjaan.

## Fitur Utama

- **Autentikasi JWT**: Login, registrasi, dan otorisasi user berbasis token JWT dengan refresh token
- **Multi-role User**: Admin, Manajer Proyek, dan Anggota Tim dengan permission yang berbeda
- **Manajemen Proyek**: CRUD operasi untuk proyek dan tugas
- **Status Tugas**: Pelacakan status tugas (To Do, In Progress, Review, Done)
- **Komentar**: Fitur komentar untuk kolaborasi tim pada tugas
- **Dashboard**: Statistik dan visualisasi progres menggunakan Chart.js
- **Upload File**: Sistem pengunggahan file untuk tugas dan proyek
- **Jadwal Presentasi**: Fitur penjadwalan presentasi dengan notifikasi

## Teknologi

- Laravel (Backend API)
- JWT Authentication
- Spatie Permission untuk manajemen role dan permission
- Database MySQL/SQLite

## Entity Relationship Diagram (ERD)

```
+---------------+       +---------------+       +---------------+
|     Users     |       |    Projects   |       |     Tasks     |
+---------------+       +---------------+       +---------------+
| id            |       | id            |       | id            |
| name          |       | name          |       | title         |
| email         |       | description   |       | description   |
| password      |       | created_by    |------>| project_id    |----+
| position      |<------| manager_id    |       | assigned_to   |<---+
| department    |       | start_date    |       | created_by    |<---+
| avatar        |       | end_date      |       | status        |
| bio           |       | status        |       | priority      |
| phone         |       | priority      |       | due_date      |
| roles         |       | code          |       | start_date    |
+-------+-------+       | is_archived   |       | estimated_hrs |
        |               +-------+-------+       | actual_hours  |
        |                       |               | completed_at  |
        |               +-------v-------+       | task_code     |
        |               | project_user  |       +-------+-------+
        |               +---------------+               |
        +-------------->| project_id    |               |
                        | user_id       |               |
                        | role          |               |
                        +---------------+               |
                                                        |
+---------------+       +---------------+       +-------v-------+
|   Comments    |       | Task Statuses |       | Task Reminders|
+---------------+       +---------------+       +---------------+
| id            |       | id            |       | id            |
| content       |       | task_id       |------>| task_id       |
| task_id       |------>| changed_by    |<---+  | user_id       |<---+
| user_id       |<---+  | from_status   |    |  | reminder_time |    |
| parent_id     |----+  | to_status     |    |  | is_sent       |    |
| is_edited     |    |  | comment       |    |  | sent_at       |    |
| edited_at     |    |  +---------------+    |  | reminder_type |    |
+---------------+    |                        |  | message      |    |
                     |                        |  | is_recurring |    |
                     |                        |  +---------------+    |
                     |                        |                       |
                     |                        |                       |
                     |                        |                       |
                     |                        |                       |
                     +------------------------+-----------------------+
```

## Alur Sistem

1. **User Authentication**
   - User melakukan registrasi atau login
   - Sistem menghasilkan JWT token untuk autentikasi
   - Token disimpan di client untuk request selanjutnya

2. **Manajemen Proyek**
   - Admin/Project Manager membuat proyek baru
   - Proyek ditetapkan dengan manager dan anggota tim
   - Anggota dapat melihat proyek dimana mereka terlibat

3. **Manajemen Tugas**
   - Project Manager membuat dan menetapkan tugas
   - Tugas dikategorikan berdasarkan status (To Do, In Progress, Review, Done)
   - Anggota tim dapat memperbarui status tugas yang ditetapkan pada mereka

4. **Kolaborasi**
   - Anggota tim dapat menambahkan komentar pada tugas
   - File dapat diunggah dan dibagikan dalam konteks tugas atau proyek
   - Notifikasi dan pengingat otomatis untuk tenggat waktu tugas

5. **Monitoring dan Pelaporan**
   - Dashboard menampilkan statistik dan progres proyek
   - Visualisasi data menggunakan Chart.js
   - Laporan dapat dilihat berdasarkan proyek, anggota, atau periode waktu

6. **Penjadwalan Presentasi**
   - Jadwal presentasi dapat dibuat untuk proyek
   - Anggota tim diundang dan dapat mengonfirmasi kehadiran
   - Pengingat otomatis dikirim mendekati waktu presentasi

## Permission Management

| Permission | Admin | Project Manager | Team Member |
|------------|-------|----------------|-------------|
| manage users | ✓ | ✗ | ✗ |
| create project | ✓ | ✓ | ✗ |
| update project | ✓ | ✓ | ✗ |
| delete project | ✓ | ✗ | ✗ |
| view project | ✓ | ✓ | ✓ |
| create task | ✓ | ✓ | ✗ |
| update task | ✓ | ✓ | ✗ |
| delete task | ✓ | ✓ | ✗ |
| view task | ✓ | ✓ | ✓ |
| assign tasks | ✓ | ✓ | ✗ |
| update tasks | ✓ | ✓ | ✓ |
| comment tasks | ✓ | ✓ | ✓ |
| view dashboard | ✓ | ✓ | ✓ |

## Instalasi

1. Clone repository
   ```
   git clone https://github.com/fauzan3f/edutask-be.git
   cd edutask-be
   ```

2. Install dependencies
   ```
   composer install
   ```

3. Setup environment
   ```
   cp .env.example .env
   php artisan key:generate
   php artisan jwt:secret
   ```

4. Configure database in .env file

5. Run migrations and seeders
   ```
   php artisan migrate --seed
   ```

6. Start development server
   ```
   php artisan serve
   ```

## Default Users

- **Admin**
  - Email: admin@edutask.com
  - Password: password

- **Project Manager**
  - Email: manager@edutask.com
  - Password: password

- **Team Member**
  - Email: member@edutask.com
  - Password: password

## API Endpoints

Dokumentasi API lengkap tersedia setelah instalasi di:

```
http://localhost:8000/api/documentation
```

## Pengembang

- Muhammad Fauzan (Pengembang Aplikasi)
- Rizki Yudistira (Membantu Front End)
- Irsa Nurrohim (Membantu Back End)
