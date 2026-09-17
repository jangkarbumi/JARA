# Aplikasi JARA - Sistem Manajemen Tugas

Aplikasi web untuk mengelola tugas pribadi maupun tim. Pengguna dapat membuat, mengelompokkan, dan mengatur tugas ke dalam beberapa daftar (list/project), menetapkan prioritas dan tenggat waktu, serta menandai tugas sebagai selesai. Sistem juga memungkinkan kolaborasi antar pengguna dan pemantauan progres penyelesaian tugas.

## User Story

Sebagai pengguna, saya ingin dapat membuat dan mengelola daftar tugas, serta mengundang rekan kerja saya ke dalam daftar tersebut, sehingga kami dapat memantau dan menyelesaikan tugas bersama-sama secara efisien.

Sebagai admin, saya ingin mengelola akun pengguna, sehingga saya dapat menambah, mengubah, atau menghapus pengguna yang memiliki akses ke dalam sistem.

[Demo](#)

## Daftar SRS

| Kode | Deskripsi | Acceptance Criteria |
| :--- | :--- | :--- |
| SRS-001 | Otentikasi & Manajemen Pengguna (Admin) | - Pengguna dan Admin dapat login.<br>- Admin dapat membuat, melihat, mengubah, dan menghapus akun pengguna.<br>- Pengguna biasa tidak dapat mengakses halaman manajemen pengguna (Restricted). |
| SRS-002 | Manajemen Daftar Tugas (Project/List) | - Pengguna dapat membuat daftar tugas baru dan otomatis menjadi pemiliknya.<br>- Pengguna dapat mengubah nama daftar miliknya.<br>- Pengguna dapat menghapus daftar yang dimilikinya beserta seluruh tugas dan keanggotaan di dalamnya.<br>- Proses pembuatan dan penghapusan daftar harus berjalan secara atomik; jika salah satu langkah gagal, seluruh perubahan dibatalkan. |
| SRS-003 | Manajemen Tugas (Tasks) dalam Daftar | - Pengguna dapat menambahkan tugas ke dalam suatu daftar.<br>- Pengguna dapat mengubah dan menghapus tugas.<br>- Pengguna dapat mengatur prioritas dan tenggat waktu (deadline) tugas. |
| SRS-004 | Status Penyelesaian Tugas | - Pengguna dapat menandai tugas sebagai selesai atau membatalkannya.<br>- Perubahan status memicu pembaruan antarmuka secara langsung (real-time/responsif). |
| SRS-005 | Kolaborasi Pengguna | - Pemilik daftar dapat menambahkan pengguna lain (dari sistem) ke daftarnya sebagai kolaborator.<br>- Kolaborator dapat melihat, mengedit, dan menyelesaikan tugas dalam daftar tersebut.<br>- Pengguna tidak berhak (unauthorized user) tidak dapat mengakses daftar orang lain. |
| SRS-006 | Pemantauan Progres | - Kalkulasi persentase penyelesaian tugas (tugas selesai vs total tugas) dalam sebuah daftar.<br>- Menampilkan visualisasi progres berupa *progress bar* atau teks presentase di dashboard. |
| SRS-007 | Keamanan & Integritas Data | - Permintaan dari pengguna yang tidak berwenang harus ditolak (HTTP 403).<br>- Seluruh input pengguna wajib divalidasi sebelum diproses.<br>- Seluruh query database wajib menggunakan query terparameterisasi (*prepared statement*) untuk mencegah SQL Injection. |

## Pembagian Tugas

Berdasarkan fitur di atas, berikut adalah pembagian untuk 2 orang developer:

### 👨‍💻 Developer 1 (Fokus: Backend Foundation, Auth, & Lists)
- Setup awal proyek, environment (Laravel), dan database.
- Desain Schema Database (tabel `users`, `lists`, `tasks`, dan pivot tabel `list_user`).
- **SRS-001**: Mengembangkan sistem otentikasi (Login/Register).
- **SRS-001**: Membuat halaman dan logika CRUD manajemen pengguna untuk Admin.
- Setup middleware Role & Permissions untuk keamanan akses sistem.
- **SRS-002**: Mengembangkan logika CRUD untuk Daftar (Lists), termasuk menjadikan pembuat sebagai pemilik secara otomatis.
- **SRS-002**: Memastikan proses pembuatan dan penghapusan daftar berjalan secara atomik menggunakan database transaction.
- **SRS-007**: Memastikan seluruh permintaan dari pengguna tidak berwenang ditolak (HTTP 403) melalui middleware.
- **SRS-007**: Memastikan seluruh input divalidasi dan query menggunakan prepared statement (parameterized query).

### 👨‍💻 Developer 2 (Fokus: Tasks, Kolaborasi, Progres & Frontend UI/UX)
- **SRS-003**: Mengembangkan fitur CRUD untuk Tugas (Tasks) dan input prioritas serta tenggat waktu.
- **SRS-004**: Mengembangkan fungsionalitas pengubahan status tugas (checklist).
- **SRS-005**: Mengembangkan logika backend untuk fitur Kolaborasi tim (menambahkan pengguna lain ke dalam list).
- Membangun pengamanan backend untuk memastikan hanya pemilik dan kolaborator yang bisa mengakses list.
- **SRS-006**: Mengerjakan logika backend agregasi persentase progres tugas.
- **SRS-006**: Mengintegrasikan antarmuka visualisasi pemantauan progres (progress bar).
- Merancang dan memastikan seluruh Antarmuka Pengguna (UI/UX) responsif, rapi, dan interaktif.
