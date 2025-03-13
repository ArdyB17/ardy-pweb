<?php
// Get NIS from URL parameter
if (isset($_GET['NIS'])) {
    $nis = $_GET['NIS'];

    // Get existing student data
    $query = mysqli_query($koneksi, "SELECT * FROM siswa WHERE NIS = '$nis'");
    $data_update = mysqli_fetch_assoc($query);
}

// Handle form submission
if (isset($_POST['tombol_update'])) {
    $nis = htmlspecialchars($_POST['NIS']);
    $no_absen = htmlspecialchars($_POST['No_Absen']);
    $nama_siswa = htmlspecialchars($_POST['Nama_Siswa']);
    $no_telp = htmlspecialchars($_POST['No_Telp']);
    $email = htmlspecialchars($_POST['Email']);
    $id_jurusan = htmlspecialchars($_POST['id_jurusan']);
    $kelas = htmlspecialchars($_POST['Kelas']);
    $angkatan = htmlspecialchars($_POST['Angkatan']);
    $password = htmlspecialchars($_POST['password']);
    $konfirmasi_password = htmlspecialchars($_POST['konfirmasi_password']);

    // Check if the password is empty
    if ($password == NULL) {
        if ($password !== $konfirmasi_password) {
            echo "<script>alert('Password dengan konfirmasi tidak sama!');window.location.href='dashboard.php?page=ubah_siswa&NIS=$nis';
            </script>";
        } else {
            $update = mysqli_query(
                $koneksi,
                "UPDATE siswa SET 
                    No_Absen = '$no_absen',
                    Nama_Siswa = '$nama_siswa',
                    No_Telp = '$no_telp',
                    Email = '$email',
                    Id_Jurusan = '$id_jurusan',
                    Kelas = '$kelas',
                    Angkatan = '$angkatan'
                WHERE NIS = '$nis'"
            );

            if ($update) {
                echo "<script>alert('Data siswa berhasil diupdate!');window.location.href='dashboard.php?page=siswa';</script>";
            } else {
                echo "<script>alert('Data siswa gagal diupdate!');window.location.href='dashboard.php?page=ubah_siswa&NIS=$nis';</script>";
            }
        }
    } else {
        if ($password !== $konfirmasi_password) {
            echo "<script>alert('Password dengan konfirmasi tidak sama!');window.location.href='dashboard.php?page=ubah_siswa&NIS=$nis';
            </script>";
        } else {
            $update = mysqli_query(
                $koneksi,
                "UPDATE siswa SET 
                    No_Absen = '$no_absen',
                    Nama_Siswa = '$nama_siswa',
                    No_Telp = '$no_telp',
                    Email = '$email',
                    Id_Jurusan = '$id_jurusan',
                    Kelas = '$kelas',
                    Angkatan = '$angkatan'
                WHERE NIS = '$nis'"
            );



            $enkrip = password_hash($password, PASSWORD_DEFAULT);
            $hasil_pengguna = mysqli_query($koneksi, "UPDATE pengguna SET Password = '$enkrip' WHERE NIS = '$nis'");

            if ($update) {
                echo "<script>alert('Data siswa berhasil diupdate!');window.location.href='dashboard.php?page=siswa';</script>";
            } else {
                echo "<script>alert('Data siswa gagal diupdate!');window.location.href='dashboard.php?page=ubah_siswa&NIS=$nis';</script>";
            }
        }
    }
}
?>

<!-- main container system -->
<div class="container py-4 mb-5">
    <div class="row justify-content-center">

        <div class="col-xl-8">

            <!-- Form Card -->
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-dark bg-gradient text-white p-3">
                    <h5 class="mb-0">
                        <i class="bi bi-pencil-square me-2"></i>Ubah Data Siswa
                    </h5>
                </div>

                <div class="card-body p-4">
                    <form action="" method="post" class="needs-validation" novalidate>
                        <div class="row g-3">

                            <!-- Left Column -->
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="nis"
                                        name="NIS" value="<?php echo $data_update['NIS']; ?>" readonly>
                                    <label for="nis">NIS</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control" id="noAbsen"
                                        name="No_Absen" value="<?php echo $data_update['No_Absen']; ?>" required placeholder="No Absen">
                                    <label for="noAbsen">No Absen</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="namaSiswa"
                                        name="Nama_Siswa" value="<?php echo $data_update['Nama_Siswa']; ?>" required>
                                    <label for="namaSiswa">Nama Siswa</label>
                                </div>

                                <!-- Update the password fields in the form -->
                                <div class="form-floating mb-3 position-relative">
                                    <input type="password" class="form-control" id="password"
                                        name="password" placeholder="Ganti Password">
                                    <label for="password">Ganti Password</label>
                                    <button type="button" class="btn btn-outline-secondary position-absolute end-0 top-50 translate-middle-y me-2 border-0"
                                        onclick="togglePassword('password')" style="z-index: 5;">
                                        <i class="bi bi-eye" id="password-icon"></i>
                                    </button>
                                </div>


                                <div class="form-floating mb-3 position-relative">
                                    <input type="password" class="form-control" id="konfirmasi_password"
                                        name="konfirmasi_password" placeholder="Konfirmasi password">
                                    <label for="konfirmasi_password">Konfirmasi Password</label>
                                    <button type="button" class="btn btn-outline-secondary position-absolute end-0 top-50 translate-middle-y me-2 border-0"
                                        onclick="togglePassword('konfirmasi_password')" style="z-index: 5;">
                                        <i class="bi bi-eye" id="konfirmasi_password-icon"></i>
                                    </button>
                                </div>

                                <script>
                                    function togglePassword(fieldId) {
                                        const field = document.getElementById(fieldId);
                                        const icon = document.getElementById(fieldId + '-icon');

                                        if (field.type === 'password') {
                                            field.type = 'text';
                                            icon.classList.replace('bi-eye', 'bi-eye-slash');
                                        } else {
                                            field.type = 'password';
                                            icon.classList.replace('bi-eye-slash', 'bi-eye');
                                        }
                                    }
                                </script>

                            </div>

                            <!-- Right Column -->
                            <div class="col-md-6">

                                <div class="form-floating mb-3">
                                    <input type="tel" class="form-control" id="noTelp"
                                        name="No_Telp" value="<?php echo $data_update['No_Telp']; ?>" required>
                                    <label for="noTelp">No Telepon</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="email"
                                        name="Email" value="<?php echo $data_update['Email']; ?>" required>
                                    <label for="email">Email</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <select class="form-select" id="jurusanSelect" name="id_jurusan" required>
                                        <?php
                                        $query_jurusan = mysqli_query($koneksi, "SELECT * FROM jurusan");
                                        while ($jurusan = mysqli_fetch_assoc($query_jurusan)) {
                                            $selected = ($jurusan['Id_Jurusan'] == $data_update['Id_Jurusan']) ? 'selected' : '';
                                        ?>
                                            <option value="<?php echo $jurusan['Id_Jurusan']; ?>" <?php echo $selected; ?>>
                                                <?php echo $jurusan['Jurusan']; ?>
                                            </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    <label for="jurusanSelect">Jurusan</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="kelas"
                                        name="Kelas" value="<?php echo $data_update['Kelas']; ?>" required>
                                    <label for="kelas">Kelas</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="angkatan"
                                        name="Angkatan" value="<?php echo $data_update['Angkatan']; ?>" required>
                                    <label for="angkatan">Angkatan</label>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-2 mt-4">

                            <a href="dashboard.php?page=siswa" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" name="tombol_update" class="btn btn-primary">
                                <i class="bi bi-check-lg me-2"></i>Simpan
                            </button>

                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    /* Form Styling System  */
    .card {
        /* transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); */
        animation: fadeIn 0.5s ease-out;
    }

    .card:hover {
        /* transform: translateY(-5px); */
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }

    .custom-input {
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
    }

    .custom-input:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .form-floating>label {
        color: #6c757d;
        transition: all 0.3s ease;
    }

    .btn {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Animations
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    } */

    /* Responsive Design */
    @media (max-width: 1200px) {
        .col-xl-8 {
            width: 75%;
        }
    }

    @media (max-width: 992px) {
        .col-xl-8 {
            width: 85%;
        }
    }

    @media (max-width: 768px) {
        .col-xl-8 {
            width: 95%;
        }

        .card-body {
            padding: 1.5rem !important;
        }
    }

    @media (max-width: 576px) {
        .container {
            padding: 0.5rem;
        }

        .card-body {
            padding: 1rem !important;
        }

        .btn {
            padding: 0.5rem 1rem;
        }
    }
</style>

<script>
    // document.addEventListener('DOMContentLoaded', () => {
    //     // Form validation
    //     const form = document.querySelector('form');
    //     const inputs = document.querySelectorAll('.custom-input');

    //     // Add animation to inputs
    //     inputs.forEach((input, index) => {
    //         input.style.opacity = '0';
    //         input.style.transform = 'translateY(10px)';
    //         setTimeout(() => {
    //             input.style.transition = 'all 0.3s ease';
    //             input.style.opacity = '1';
    //             input.style.transform = 'translateY(0)';
    //         }, index * 100);
    //     });

    //     // Phone number formatter
    //     const phoneInput = document.querySelector('input[name="no_telp"]');
    //     phoneInput.addEventListener('input', (e) => {
    //         let x = e.target.value.replace(/\D/g, '').match(/(\d{0,4})(\d{0,4})(\d{0,4})/);
    //         e.target.value = !x[2] ? x[1] : `${x[1]}-${x[2]}${x[3] ? `-${x[3]}` : ''}`;
    //     });
    // });
</script>