<?php
ob_start();

if (isset($_COOKIE['Status']) && $_COOKIE['Status'] != 'Operator') {
    echo "<script>alert('Anda Tidak Memiliki Akses Halaman Ini!!!!');window.location.href='dashboard.php?page=home'</script>";
} else {
    $ambildata = mysqli_query($koneksi, "SELECT * FROM operator INNER JOIN pengguna using(username) WHERE username='$_COOKIE[username]'");
    $hasil = mysqli_fetch_assoc($ambildata);

    // Determine edit mode
    $edit_mode = isset($_GET['edit']) ? true : false;

    // Hapus operator (Delete Account) Logic
    if (isset($_POST['hapus_akun'])) {
        $password_input = $_POST['password_hapus'];

        // Verify password
        $cek_password = mysqli_query($koneksi, "SELECT Password FROM pengguna WHERE username='$_COOKIE[username]'");
        $data_password = mysqli_fetch_assoc($cek_password);

        if (password_verify($password_input, $data_password['Password'])) {
            // Delete related records first
            $delete_pengguna = mysqli_query($koneksi, "DELETE FROM pengguna WHERE username='$_COOKIE[username]'");
            $detele_pegawai = mysqli_query($koneksi, "DELETE FROM operator WHERE username='$_COOKIE[username]'");
            if (!$delete_pengguna && !$detele_pegawai) {
                echo "<script>alert('Gagal Menghapus akun!'); window.location.href='dashboard.php?page=operator';</script>";
            } else {
                // setcookie('Username', '', time() - 3600, "/");
                // setcookie('Status', '', time() - 3600, "/");

                echo "<script>alert('akun Berhasil Dihapus!'); window.location.href='../logout.php';</script>";
            }
        } else {
            echo "<script>alert('Password Salah. akun Tidak Dapat Dihapus!'); window.location.href='dashboard.php?page=operator';</script>";
        }
    }

    if (isset($_POST['ubah_operator'])) {
        $Nama_Lengkap       = $_POST['Nama_Lengkap'];
        $Username           = $_POST['Username'];
        $password           = $_POST['Password'];
        $Konfirmasi_Password = $_POST['Konfirmasi_Password'];
        if ($password == '' && $Konfirmasi_Password == '') {
            $tambah_operator = mysqli_query($koneksi, "UPDATE operator SET Nama_Lengkap='$Nama_Lengkap' WHERE Username='$_COOKIE[Username]'");
            if (!$tambah_operator) {
                echo "<script>alert('Gagal Mengubah Data Pegawai !!!!');window.location.href='dashboard.php?page=operator'</script>";
            } else {
                echo "<script>window.location.href='dashboard.php?page=operator'</script>";
            }
        } else if ($password != $Konfirmasi_Password) {
            echo "<script>alert('Password Tidak Sama!!!!');window.location.href='dashboard.php?page=operator'</script>";
        } else if ($password == $Konfirmasi_Password) {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $ubah_operator = mysqli_query($koneksi, "UPDATE pegawai SET Nama_Lengkap='$Nama_Lengkap' WHERE Username='$_COOKIE[Username]'");
            $ubah_pengguna = mysqli_query($koneksi, "UPDATE pengguna SET Password='$password' WHERE Username='$_COOKIE[Username]'");
            if (!$ubah_pegawai) {
                echo "<script>alert('Gagal Menambahkan Data !!!!');window.location.href='dashboard.php?page=operator'</script>";
            } else if (!$ubah_pengguna) {
                echo "<script>alert('Gagal Mengubah Data Pengguna!!!!');window.location.href='dashboard.php?page=operator'</script>";
            } else {
                echo "<script>alert('Berhasil Merubah Data, Silahkan Login Ulang Terimakasih!!!!');window.location.href='../login.php'</script>";
            }
        }
    }

    // Get current operator data
    $current_username = $_COOKIE['username'];
    $operators_query = mysqli_query($koneksi, "SELECT * FROM operator ORDER BY Nama_Lengkap");
    $current_operator = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM operator WHERE username='$current_username'"));

    // Handle password update
    if (isset($_POST['update_password'])) {
        $username = $_COOKIE['username'];
        $new_password = mysqli_real_escape_string($koneksi, $_POST['new_password']);
        $confirm_password = mysqli_real_escape_string($koneksi, $_POST['confirm_password']);

        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            mysqli_begin_transaction($koneksi);

            try {
                $update = mysqli_query($koneksi, "UPDATE pengguna SET Password='$hashed_password' WHERE username='$username'");

                if (!$update) {
                    throw new Exception('Failed to update password');
                }

                mysqli_commit($koneksi);

                // Clear cookies
                setcookie('username', '', time() - 3600, '/');
                setcookie('level_user', '', time() - 3600, '/');
                setcookie('Nama_Lengkap', '', time() - 3600, '/');

                echo "<script>alert('Password berhasil diupdate! Silakan login kembali.');window.location.href='../login.php';</script>";
                exit;
            } catch (Exception $e) {
                mysqli_rollback($koneksi);
                echo "<script>alert('Gagal mengupdate password!');</script>";
            }
        } else {
            echo "<script>alert('Password baru tidak cocok!');</script>";
        }
    }

    // Handle account deletion 
    if (isset($_POST['delete_account'])) {
        $username = $_COOKIE['username'];
        $password = mysqli_real_escape_string($koneksi, $_POST['password']);

        // Verify password first
        $query = mysqli_query($koneksi, "SELECT Password FROM pengguna WHERE username='$username'");
        $user = mysqli_fetch_assoc($query);

        if (password_verify($password, $user['Password'])) {
            mysqli_begin_transaction($koneksi);

            try {
                // Check if last operator
                $operator_count = mysqli_fetch_array(mysqli_query($koneksi, "SELECT COUNT(*) FROM operator"))[0];
                if ($operator_count <= 1) {
                    throw new Exception('Tidak dapat menghapus operator terakhir');
                }

                // First delete from pengguna table (child)
                $delete_pengguna = mysqli_query($koneksi, "DELETE FROM pengguna WHERE username='$username'");
                if (!$delete_pengguna) {
                    throw new Exception('Gagal menghapus dari tabel pengguna');
                }

                // Then delete from operator table (parent)
                $delete_operator = mysqli_query($koneksi, "DELETE FROM operator WHERE username='$username'");
                if (!$delete_operator) {
                    throw new Exception('Gagal menghapus dari tabel operator');
                }

                mysqli_commit($koneksi);

                // Clear cookies
                setcookie('username', '', time() - 3600, '/');
                setcookie('level_user', '', time() - 3600, '/');
                setcookie('Nama_Lengkap', '', time() - 3600, '/');

                echo "<script>alert('Akun berhasil dihapus!');window.location.href='../login.php';</script>";
                exit;
            } catch (Exception $e) {
                mysqli_rollback($koneksi);
                echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
            }
        } else {
            echo "<script>alert('Password salah!');</script>";
        }
    }

?>

    <style>
        <?php include '../css/style_operator.css'; ?>
    </style>

    <div class="container py-4">
        <!-- Header Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-gradient bg-dark text-white p-4 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <i class="bi bi-gear me-2"></i>
                    <h4 class="mb-0">Pengaturan</h4>
                </div>
                <a href="dashboard.php?page=tambah_operator" class="btn btn-light">
                    <i class="bi bi-person-plus-fill me-2"></i>Tambah Operator
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Left: Operators List -->
            <div class="col-lg-5 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <h6 class="mb-0 fw-bold text-primary">
                                <i class="bi bi-people me-2"></i>Daftar Operator
                            </h6>
                            <span class="badge bg-primary rounded-pill">
                                <?php echo mysqli_num_rows($operators_query); ?> Operator
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="operators-table-wrapper">
                            <div class="operator-list">
                                <?php while ($operator = mysqli_fetch_assoc($operators_query)) { ?>
                                    <div class="operator-item <?= $operator['username'] === $current_username ? 'active' : '' ?>">
                                        <div class="d-flex align-items-center">
                                            <div class="operator-avatar me-3">
                                                <div class="avatar-content">
                                                    <?php echo strtoupper(substr($operator['Nama_Lengkap'], 0, 2)); ?>
                                                </div>
                                            </div>
                                            <div class="operator-info">
                                                <h6 class="operator-name mb-1"><?= htmlspecialchars($operator['Nama_Lengkap']) ?></h6>
                                                <span class="operator-username">@<?= htmlspecialchars($operator['username']) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Right: Password Management -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-dark text-white p-4">
                        <h5 class="mb-0">
                            <i class="bi bi-shield-lock me-2"></i>
                            Pengaturan Akun
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <!-- Current User Info -->
                        <div class="text-center mb-4">
                            <div class="operator-avatar-lg mx-auto mb-3">
                                <i class="bi bi-person-circle"></i>
                            </div>
                            <h5 class="mb-1"><?= htmlspecialchars($current_operator['Nama_Lengkap']) ?></h5>
                            <p class="text-muted mb-0">@<?= htmlspecialchars($current_operator['username']) ?></p>
                        </div>

                        <hr class="my-4">

                        <!-- Password Update Form -->
                        <form method="POST" action="" class="needs-validation" novalidate>
                            <div class="mb-4">
                                <label class="form-label">Password Baru</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="new_password" id="newPassword" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('newPassword')">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Konfirmasi Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="confirm_password" id="confirmPassword" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirmPassword')">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" name="update_password" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-2"></i>Update Password
                                </button>
                            </div>
                        </form>

                        <!-- Danger Zone -->
                        <div class="mt-4 pt-4 border-top">
                            <h6 class="text-danger d-flex align-items-center mb-3">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                Danger Zone
                            </h6>
                            <div class="d-grid">
                                <button type="button" class="btn btn-outline-danger" onclick="confirmDelete()">
                                    <i class="bi bi-trash me-2"></i>Hapus Akun
                                </button>
                            </div>
                        </div>

                        <form id="deleteForm" method="POST" style="display: none;">
                            <input type="hidden" name="delete_account" value="true">
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Konfirmasi Hapus Akun
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <p>Masukkan password Anda untuk konfirmasi:</p>
                        <div class="input-group">
                            <input type="password"
                                name="password"
                                class="form-control"
                                required>
                            <button type="button"
                                class="btn btn-outline-secondary"
                                onclick="toggleDeletePassword()">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="delete_account" class="btn btn-danger">
                            <i class="bi bi-trash me-2"></i>Hapus Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


<?php
}
?>
<script>
    function togglePassword(id) {
        const input = document.getElementById(id);
        const icon = input.nextElementSibling.querySelector('i');
        input.type = input.type === 'password' ? 'text' : 'password';
        icon.classList.toggle('bi-eye');
        icon.classList.toggle('bi-eye-slash');
    }

    // Show confirmation dialog with additional warning
    function confirmDelete() {
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }

    function toggleDeletePassword() {
        const input = document.querySelector('input[name="password"]');
        const icon = document.querySelector('.btn-outline-secondary i');

        input.type = input.type === 'password' ? 'text' : 'password';
        icon.classList.toggle('bi-eye');
        icon.classList.toggle('bi-eye-slash');
    }

    // Form validation
    (function() {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>