<?php
if (isset($_POST['submit_operator'])) {

    $nama_lengkap   = htmlspecialchars($_POST['Nama_Lengkap']);
    $username       = htmlspecialchars($_POST['username']);
    $password       = htmlspecialchars($_POST['Password']);
    $konfirmasi_pass = htmlspecialchars($_POST['konfirmasi_pass']);

    if ($password != $konfirmasi_pass) {
        echo "<script>alert('Password dengan konfirmasi password tidak sama');window.location.href='dashboard.php?page=tambah_operator'</script>";
    } else {
        $hasil_pegawai = mysqli_query($koneksi, "INSERT INTO operator VALUES('$nama_lengkap', '$username')");
        $enkrip        = password_hash($password, PASSWORD_DEFAULT);
        $hasil_pengguna = mysqli_query($koneksi, "INSERT INTO pengguna VALUES(NULL, '$username', NULL, '$enkrip')");

        if (!$hasil_pengguna) {
            echo "<script>alert('Gagal Memasukkan Data');window.location.href='dashboard.php?page=tambah_operator'</script>";
        } else {
            echo "<script>alert('Berhasil Menambahkan Data');window.location.href='dashboard.php?page=operator'</script>";
        }
    }
}
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-lg">
                <div class="card-header text-white p-3" style="background: linear-gradient(45deg, #1a237e, #283593);">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-person-plus-fill fs-4 me-2"></i>
                        <h5 class="mb-0">Tambah Operator Baru</h5>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="" class="needs-validation" novalidate>
                        <!-- Nama Lengkap -->
                        <div class="form-floating mb-3">
                            <input type="text"
                                class="form-control"
                                name="Nama_Lengkap"
                                placeholder="Nama Lengkap"
                                required>
                            <label>Nama Lengkap</label>
                            <div class="invalid-feedback">
                                Masukkan nama lengkap
                            </div>
                        </div>

                        <!-- Username -->
                        <div class="form-floating mb-3">
                            <input type="text"
                                class="form-control"
                                name="username"
                                placeholder="Username"
                                required>
                            <label>Username</label>
                            <div class="invalid-feedback">
                                Masukkan username
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="form-floating mb-3">
                            <div class="input-group">
                                <input type="password"
                                    class="form-control"
                                    name="Password"
                                    id="password"
                                    placeholder="Password"
                                    required>
                                <button class="btn btn-outline-secondary"
                                    type="button"
                                    onclick="togglePassword('password')">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback">
                                Masukkan password
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-floating mb-4">
                            <div class="input-group">
                                <input type="password"
                                    class="form-control"
                                    name="konfirmasi_pass"
                                    id="confirm_password"
                                    placeholder="Konfirmasi Password"
                                    required>
                                <button class="btn btn-outline-secondary"
                                    type="button"
                                    onclick="togglePassword('confirm_password')">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback">
                                Konfirmasi password anda
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="dashboard.php?page=operator"
                                class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit"
                                name="submit_operator"
                                class="btn btn-primary">
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
    .form-control {
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        border: 1px solid #dee2e6;
        transition: all 0.2s ease-in-out;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
    }

    .form-floating>label {
        padding: 1rem;
    }

    .form-floating>.form-control {
        height: calc(3.5rem + 2px);
        line-height: 1.25;
    }

    .btn {
        padding: 0.5rem 1.5rem;
        font-weight: 500;
        border-radius: 10px;
        transition: all 0.2s ease-in-out;
    }

    .btn:hover {
        transform: translateY(-1px);
    }

    .card {
        border-radius: 15px;
        overflow: hidden;
    }

    .card-header {
        background: linear-gradient(135deg, #2563eb, #1e40af);
    }

    @media (max-width: 576px) {
        .container {
            padding: 1rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        .btn {
            width: 100%;
            margin-bottom: 0.5rem;
        }

        .d-flex {
            flex-direction: column;
        }
    }
</style>

<script>
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = input.nextElementSibling.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }

    // Form validation
    (function() {
        'use strict'
        const forms = document.querySelectorAll('.needs-validation')

        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                // Check if passwords match
                const password = form.querySelector('[name="Password"]');
                const confirm = form.querySelector('[name="confirm_password"]');

                if (password.value !== confirm.value) {
                    event.preventDefault();
                    alert('Password tidak cocok!');
                    return false;
                }

                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>