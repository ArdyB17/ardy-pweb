<?php
if (isset($_POST['submit'])) {

    $nis = htmlspecialchars($_POST['NIS']);
    $no_absen = htmlspecialchars($_POST['No_Absen']);
    $nama_siswa = htmlspecialchars($_POST['Nama_Siswa']);
    $no_telp = htmlspecialchars($_POST['No_Telp']);
    $email = htmlspecialchars($_POST['Email']);
    $id_jurusan = htmlspecialchars($_POST['Jurusan']);
    $kelas = htmlspecialchars($_POST['Kelas']);
    $angkatan = htmlspecialchars($_POST['Angkatan']);

    $pass = "siswa" . $nis;
    $enkrip = password_hash($pass, PASSWORD_DEFAULT);

    $hasil = mysqli_query($koneksi, "INSERT INTO siswa VALUES ('$nis', '$no_absen', '$nama_siswa', '$no_telp', '$email', '$id_jurusan', '$kelas', '$angkatan')");
    $hasil_pengguna = mysqli_query($koneksi, "INSERT INTO pengguna VALUES (NULL, NULL,'$nis', '$enkrip')");

    if ($hasil) {
        echo "<script>alert('Data berhasil ditambahkan!');window.location.href='dashboard.php?page=siswa'</script>";
    } else {
        echo "<script>alert('Data gagal ditambahkan!');window.location.href='dashboard.php?page=tambah_siswa'</script>";
    }
}


?>

<link rel="stylesheet" href="../css/style_form_siswa.css">

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-xl-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark bg-gradient text-white p-3">
                    <h5 class="mb-0">
                        <i class="bi bi-person-plus-fill me-2"></i>Tambah Siswa
                    </h5>
                </div>

                <div class="card-body p-4">
                    <form action="" method="post" class="needs-validation" novalidate>
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="nis" name="NIS" placeholder="NIS" required>
                            <label for="nis">NIS</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="noAbsen" name="No_Absen" placeholder="No Absen" required>
                            <label for="noAbsen">No Absen</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="namaSiswa" name="Nama_Siswa" placeholder="Nama Siswa" required>
                            <label for="namaSiswa">Nama Siswa</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="tel" class="form-control" id="noTelp" name="No_Telp" placeholder="No telp" required>
                            <label for="noTelp">No telp</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="email" name="Email" placeholder="Email" required>
                            <label for="email">Email</label>
                        </div>

                        <!-- Change this part in your form -->
                        <div class="form-floating mb-3">
                            <select class="form-select" id="jurusanInput" name="Jurusan" required>
                                <option value="" selected>Pilih Jurusan</option>
                                <?php
                                $list = mysqli_query($koneksi, "SELECT * FROM jurusan");
                                while ($data = mysqli_fetch_assoc($list)) {
                                ?>
                                    <option value="<?= $data['Id_Jurusan'] ?>">
                                        <?= $data['Jurusan']; ?>
                                    </option>
                                <?php
                                }
                                ?>
                            </select>
                            <label for="jurusanInput">Jurusan</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="kelas" name="Kelas" placeholder="Kelas" required>
                            <label for="kelas">Kelas</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="angkatan" name="Angkatan" placeholder="Angkatan" required>
                            <label for="angkatan">Angkatan</label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary" name="submit" value="kirim" id="">
                                <i class="bi bi-check-lg me-2"></i>Simpan
                            </button>
                            <a href="dashboard.php?page=siswa" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Kembali
                            </a>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        animation: fadeIn 0.5s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-floating {
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .btn {
        transition: all 0.2s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
        .card-body {
            padding: 1.5rem;
        }

        .form-floating {
            margin-bottom: 1rem;
        }

        .btn {
            padding: 0.5rem 1rem;
        }
    }

    @media (max-width: 576px) {
        .card-body {
            padding: 1rem;
        }

        .form-floating>label {
            font-size: 0.9rem;
        }

        .btn {
            font-size: 0.9rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Form validation
        const form = document.querySelector('form');
        const inputs = form.querySelectorAll('input');

        // Add animation to inputs
        inputs.forEach((input, index) => {
            input.style.opacity = '0';
            input.style.transform = 'translateY(10px)';
            setTimeout(() => {
                input.style.transition = 'all 0.3s ease';
                input.style.opacity = '1';
                input.style.transform = 'translateY(0)';
            }, index * 100);
        });

        // Custom validation
        form.addEventListener('submit', (e) => {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        });

        // Phone number formatter
        const phoneInput = document.getElementById('noTelp');
        phoneInput.addEventListener('input', (e) => {
            let x = e.target.value.replace(/\D/g, '').match(/(\d{0,4})(\d{0,4})(\d{0,4})/);
            e.target.value = !x[2] ? x[1] : `${x[1]}-${x[2]}${x[3] ? `-${x[3]}` : ''}`;
        });
    });
</script>