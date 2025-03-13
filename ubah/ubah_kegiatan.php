<?php
// Check for Id_Kegiatan parameter and sanitize it
if (!isset($_GET['Id_Kegiatan'])) {
    echo "<script>alert('ID Kegiatan tidak ditemukan!');window.location.href='dashboard.php?halaman=kegiatan';</script>";
    exit;
}

$id_kegiatan = mysqli_real_escape_string($koneksi, $_GET['Id_Kegiatan']);

// Get kegiatan data joined with kategori
$query = mysqli_query($koneksi, "SELECT k.*, kg.Kategori, kg.Sub_Kategori 
                                FROM kegiatan k
                                INNER JOIN kategori kg ON k.Id_Kategori = kg.Id_Kategori
                                WHERE k.Id_Kegiatan = '$id_kegiatan'");
$data_kegiatan = mysqli_fetch_assoc($query);

if (!$data_kegiatan) {
    echo "<script>alert('Data kegiatan tidak ditemukan!');window.location.href='dashboard.php?halaman=kegiatan';</script>";
    exit;
}

if (isset($_POST['tombol_update'])) {
    $nama_kegiatan = mysqli_real_escape_string($koneksi, $_POST['nama_kegiatan']);
    $kredit = mysqli_real_escape_string($koneksi, $_POST['kredit']);
    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $sub_kategori = mysqli_real_escape_string($koneksi, $_POST['sub_kategori']);

    // Get Id_Kategori based on Sub_Kategori
    $get_kategori = mysqli_query($koneksi, "SELECT Id_Kategori FROM kategori WHERE Sub_Kategori = '$sub_kategori'");
    $id_kategori = mysqli_fetch_assoc($get_kategori)['Id_Kategori'];

    // Check if kegiatan name already exists except for current kegiatan
    $cek_kegiatan = mysqli_query($koneksi, "SELECT Id_Kegiatan FROM kegiatan 
                                           WHERE Jenis_Kegiatan = '$nama_kegiatan' 
                                           AND Id_Kategori = '$id_kategori'
                                           AND Id_Kegiatan != '$id_kegiatan'");

    if (mysqli_num_rows($cek_kegiatan) > 0) {
        echo "<script>alert('Kegiatan sudah ada dalam kategori ini!');</script>";
    } else {
        $hasil = mysqli_query($koneksi, "UPDATE kegiatan 
                                        SET Jenis_Kegiatan = '$nama_kegiatan',
                                            Angka_Kredit = '$kredit',
                                            Id_Kategori = '$id_kategori'
                                        WHERE Id_Kegiatan = '$id_kegiatan'");

        if ($hasil) {
            echo "<script>alert('Data berhasil diupdate!');window.location.href='dashboard.php?page=kegiatan';</script>";
        } else {
            echo "<script>alert('Gagal mengupdate data!');window.location.href='dashboard.php?page=kegiatan</script>";
        }
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
                        <i class="bi bi-pencil-square me-2"></i>Ubah Data Kegiatan
                    </h5>
                </div>

                <div class="card-body p-4">
                    <form action="" method="post" class="needs-validation" novalidate>
                        <!-- Kategori Selection -->
                        <!-- Kategori Display -->
                        <div class="form-floating mb-3">
                            <input type="text"
                                class="form-control"
                                value="<?= htmlspecialchars($data_kegiatan['Kategori']) ?>"
                                readonly>
                            <input type="hidden" name="kategori"
                                value="<?= htmlspecialchars($data_kegiatan['Kategori']) ?>">
                            <label>Kategori</label>
                        </div>

                        <!-- Sub Kategori Display -->
                        <div class="form-floating mb-3">
                            <input type="text"
                                class="form-control"
                                value="<?= htmlspecialchars($data_kegiatan['Sub_Kategori']) ?>"
                                readonly>
                            <input type="hidden" name="sub_kategori"
                                value="<?= htmlspecialchars($data_kegiatan['Sub_Kategori']) ?>">
                            <label>Sub Kategori</label>
                        </div>

                        <!-- Nama Kegiatan -->
                        <div class="form-floating mb-3">
                            <input type="text"
                                class="form-control"
                                name="nama_kegiatan"
                                value="<?= htmlspecialchars($data_kegiatan['Jenis_Kegiatan']) ?>"
                                required>
                            <label>Nama Kegiatan</label>
                            <div class="invalid-feedback">
                                Mohon isi nama kegiatan
                            </div>
                        </div>

                        <!-- Kredit Point -->
                        <div class="form-floating mb-3">
                            <input type="number"
                                class="form-control"
                                name="kredit"
                                value="<?= htmlspecialchars($data_kegiatan['Angka_Kredit']) ?>"
                                min="1"
                                max="100"
                                required>
                            <label>Kredit Point</label>
                            <div class="invalid-feedback">
                                Mohon isi kredit point 
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
    function pilihKategori(kategori) {
        if (!kategori) return;

        // Fetch sub categories for selected kategori
        fetch(`get_sub_kategori.php?kategori=${encodeURIComponent(kategori)}`)
            .then(response => response.json())
            .then(data => {
                const subKategoriSelect = document.getElementById('subKategoriSelect');
                subKategoriSelect.innerHTML = '<option value="">Pilih Sub Kategori</option>';

                data.forEach(sub => {
                    const option = document.createElement('option');
                    option.value = sub.Sub_Kategori;
                    option.textContent = sub.Sub_Kategori;
                    subKategoriSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error:', error));
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
                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>