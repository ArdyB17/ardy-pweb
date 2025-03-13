# SKKPD - Sistem Kredit Kegiatan Peserta Didik

## Deskripsi

SKKPD adalah sistem manajemen penilaian berbasis poin untuk kegiatan peserta didik di SMK TI Bali Global Denpasar. Sistem ini dirancang untuk memantau dan mengevaluasi keterlibatan siswa dalam berbagai kegiatan non-akademik dengan target 30 poin dalam masa studi 3 tahun.

## Fitur Utama

- 🎯 Manajemen Poin & Sertifikat Kegiatan
- 👥 Pengelolaan Data Siswa & Operator
- 📚 Manajemen Kategori & Sub-kategori Kegiatan
- 📊 Dashboard Monitoring Progress
- 🔐 Multi-level User Authentication (Operator & Siswa)

## Persyaratan Sistem

- PHP versi 7.4 ke atas
- MySQL versi 5.7 ke atas
- Web Server Apache/Nginx
- Browser modern (Chrome, Firefox, Safari, Edge)

## Panduan Instalasi

1. Clone repositori ini:

```bash
git clone https://github.com/yourusername/skkpd.git
```

2. Import database:

```bash
mysql -u username -p nama_database < database/skkpd.sql
```

3. Konfigurasi database:
   Edit file `koneksi.php`:

```php
$koneksi = mysqli_connect("localhost", "username", "password", "nama_database");
```

## Struktur Direktori

```
skkpd/
├── bootstrap/          # File CSS Bootstrap
├── css/               # File CSS kustom
├── gambar/            # Aset gambar & file upload
├── tambah/            # Form penambahan data
├── ubah/              # Form edit data
├── tampilan/          # Halaman utama
├── koneksi.php        # Konfigurasi database
├── login.php          # Halaman login
└── README.md          # Dokumentasi
```

## Modul Sistem

### 1. Manajemen Siswa

- Pendaftaran siswa baru
- Update data siswa
- Manajemen kelas & jurusan
- Monitoring poin kegiatan

### 2. Manajemen Kegiatan

- Kategori & sub-kategori kegiatan
- Alokasi poin per kegiatan
- Upload & validasi sertifikat
- Status validasi sertifikat

### 3. Manajemen User

- Multi-level user (Operator & Siswa)
- Manajemen akun operator
- Ganti password
- Riwayat login

## Penggunaan

### Login Operator

- Username: [username operator]
- Akses ke semua fitur manajemen
- Validasi sertifikat
- Pengelolaan master data

### Login Siswa

- Login menggunakan NIS
- Upload sertifikat kegiatan
- Monitoring poin
- Update profil & password

## Kontribusi

1. Fork repositori
2. Buat branch fitur (`git checkout -b fitur/FiturBaru`)
3. Commit perubahan (`git commit -m 'Menambahkan fitur baru'`)
4. Push ke branch (`git push origin fitur/FiturBaru`)
5. Buat Pull Request

## Lisensi

Proyek ini dilisensikan di bawah MIT License - lihat file LICENSE untuk detail.

## Credit

- Pak Arie (you're goated pak 🙏🏻)
- Ardy Berata
