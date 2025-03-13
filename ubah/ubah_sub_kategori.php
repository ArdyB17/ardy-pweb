<?php
// Check for Id_Kategori parameter
if (!isset($_GET['Id_Kategori'])) {
    echo "<script>alert('ID Kategori tidak ditemukan!');window.location.href='dashboard.php?page=kegiatan';</script>";
    exit;
}

$id_kategori = mysqli_real_escape_string($koneksi, $_GET['Id_Kategori']);

// Get kategori data
$query = mysqli_query($koneksi, "SELECT * FROM kategori WHERE Id_Kategori = '$id_kategori'");
$data_kategori = mysqli_fetch_assoc($query);

if (!$data_kategori) {
    echo "<script>alert('Data kategori tidak ditemukan!');window.location.href='dashboard.php?page=kegiatan';</script>";
    exit;
}

if (isset($_POST['tombol_update'])) {
    $sub_kategori = mysqli_real_escape_string($koneksi, $_POST['sub_kategori']);

    // Check if sub_kategori already exists in the same main kategori
    $cek_sub = mysqli_query($koneksi, "SELECT Id_Kategori FROM kategori 
                                      WHERE Sub_Kategori = '$sub_kategori' 
                                      AND Kategori = '{$data_kategori['Kategori']}'
                                      AND Id_Kategori != '$id_kategori'");

    if (mysqli_num_rows($cek_sub) > 0) {
        echo "<script>alert('Sub Kategori sudah ada dalam kategori ini!');</script>";
    } else {
        $hasil = mysqli_query($koneksi, "UPDATE kategori 
                                       SET Sub_Kategori = '$sub_kategori'
                                       WHERE Id_Kategori = '$id_kategori'");

        if ($hasil) {
            echo "<script>
                alert('Sub Kategori berhasil diupdate!');
                window.location.href='dashboard.php?page=kegiatan';
            </script>";
        } else {
            echo "<script>alert('Gagal mengupdate Sub Kategori!');</script>";
        }
    }
}
?>

<div class="container py-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-dark bg-gradient text-white p-3">
                    <h5 class="mb-0">
                        <i class="bi bi-bookmark-fill me-2"></i>Ubah Sub Kategori
                    </h5>
                </div>

                <div class="card-body p-4">
                    <form action="" method="post" class="needs-validation" novalidate>
                        <!-- Kategori Display -->
                        <div class="form-floating mb-3">
                            <input type="text"
                                class="form-control"
                                value="<?= htmlspecialchars($data_kategori['Kategori']) ?>"
                                readonly>
                            <label>Kategori</label>
                        </div>

                        <!-- Sub Kategori Input -->
                        <div class="form-floating mb-3">
                            <input type="text"
                                class="form-control"
                                name="sub_kategori"
                                value="<?= htmlspecialchars($data_kategori['Sub_Kategori']) ?>"
                                required>
                            <label>Sub Kategori</label>
                            <div class="invalid-feedback">
                                Mohon isi sub kategori
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="dashboard.php?page=kegiatan" class="btn btn-secondary">
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
    // Form validation
    (function() {
        'use strict'
        const forms = document.querySelectorAll('.needs-validation')
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