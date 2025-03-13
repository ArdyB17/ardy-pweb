<?php
// Check for Id_Jurusan parameter and sanitize it
if (!isset($_GET['Id_Jurusan'])) {
    echo "<script>alert('ID Jurusan tidak ditemukan!');window.location.href='dashboard.php?page=jurusan';</script>";
    exit;
}

$id_jurusan = mysqli_real_escape_string($koneksi, $_GET['Id_Jurusan']);
$query = mysqli_query($koneksi, "SELECT * FROM jurusan WHERE Id_Jurusan = '$id_jurusan'");
$data_update = mysqli_fetch_assoc($query);

if (!$data_update) {
    echo "<script>alert('Data jurusan tidak ditemukan!');window.location.href='dashboard.php?page=jurusan';</script>";
    exit;
}

if (isset($_POST['tombol_update'])) {
    $jurusan = mysqli_real_escape_string($koneksi, $_POST['Jurusan']);

    $hasil = mysqli_query($koneksi, "UPDATE jurusan SET Jurusan = '$jurusan' WHERE Id_Jurusan = '$id_jurusan'");

    if ($hasil) {
        echo "<script>alert('Data berhasil diupdate!');window.location.href='dashboard.php?page=jurusan';</script>";
    } else {
        echo "<script>alert('Data gagal diupdate!');window.location.href='dashboard.php?page=ubah_jurusan&Id_Jurusan=$id_jurusan';</script>";
    }
}
?>

<div class="container py-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-xl-8">
            <!-- Form Card -->
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-dark bg-gradient text-white p-3">
                    <h5 class="mb-0">
                        <i class="bi bi-pencil-square me-2"></i>Ubah Data Jurusan
                    </h5>
                </div>

                <div class="card-body p-4">
                    <form action="" method="post" class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="namaJurusan"
                                        name="Jurusan"
                                        value="<?php echo htmlspecialchars($data_update['Jurusan']); ?>"
                                        required>
                                    <label for="namaJurusan">Nama Jurusan</label>
                                    <div class="invalid-feedback">
                                        Nama jurusan tidak boleh kosong
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="dashboard.php?page=jurusan" class="btn btn-secondary">
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

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Form validation
        const form = document.querySelector('form');
        const inputs = document.querySelectorAll('.custom-input');

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

        // Phone number formatter
        const phoneInput = document.querySelector('input[name="no_telp"]');
        phoneInput.addEventListener('input', (e) => {
            let x = e.target.value.replace(/\D/g, '').match(/(\d{0,4})(\d{0,4})(\d{0,4})/);
            e.target.value = !x[2] ? x[1] : `${x[1]}-${x[2]}${x[3] ? `-${x[3]}` : ''}`;
        });
    });
</script>