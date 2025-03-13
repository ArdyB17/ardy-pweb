<?php
if (isset($_POST['tombol_tambah'])) {
    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $sub_kategori = mysqli_real_escape_string($koneksi, $_POST['sub_kategori']);
    $kegiatan = mysqli_real_escape_string($koneksi, $_POST['nama_kegiatan']);
    $point = mysqli_real_escape_string($koneksi, $_POST['kredit']);

    // Validate if kegiatan already exists
    $cek_kegiatan = mysqli_query($koneksi, "SELECT Jenis_Kegiatan FROM kegiatan WHERE Jenis_Kegiatan = '$kegiatan'");

    if (mysqli_num_rows($cek_kegiatan) > 0) {
        echo "<script>alert('Kegiatan sudah ada dalam database!');window.location.href='dashboard.php?page=tambah_kegiatan&kategori=" . $kategori . "&sub_kategori=" . $sub_kategori . "';</script>";
    } else {
        // Get Id_Kategori based on Sub_Kategori
        $get_kategori = mysqli_query($koneksi, "SELECT Id_Kategori FROM kategori WHERE Sub_Kategori = '$sub_kategori'");
        $data_kategori = mysqli_fetch_assoc($get_kategori);
        $id_kategori = $data_kategori['Id_Kategori'];

        // Insert new kegiatan
        $hasil = mysqli_query($koneksi, "INSERT INTO kegiatan (Jenis_Kegiatan, Angka_Kredit, Id_Kategori) VALUES ('$kegiatan', '$point', '$id_kategori')");

        if ($hasil) {
            echo "<script>alert('Data berhasil ditambahkan!');window.location.href='dashboard.php?page=kegiatan';</script>";
        } else {
            echo "<script>alert('Data gagal ditambahkan!');window.location.href='dashboard.php?page=tambah_kegiatan';</script>";
        }
    }
}
?>

<style>
    <?php include '../css/style_form_siswa.css' ?>
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-xl-8">

            <!-- Form Card -->
            <div class="card border-0 shadow-lg form-card">

                <!-- Card Header -->
                <div class="card-header bg-gradient border-0 p-3">
                    <div class="d-flex align-items-center">
                        <div class="header-icon">
                            <i class="bi bi-list-check"></i>
                        </div>
                        <h5 class="mb-0 ms-3 text-white">Tambah Kategori & Kegiatan</h5>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body p-4">
                    <!-- Info Alert -->

                    <!-- <div class="alert alert-info mb-4" role="alert">
                        <i class="bi bi-info-circle me-2"></i>
                        Pilih kategori terlebih dahulu untuk menampilkan sub kategori
                    </div> -->

                    <!-- Main Form -->
                    <form action="" method="post" class="needs-validation" novalidate>
                        <!-- tandain ini (really important)-->
                        <div class="row justify-content-center g-3">
                            <!-- Kategori Selection -->
                            <div class="col-md-8">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="form-floating flex-grow-1">

                                        <select name="kategori" id="kategoriSelect" class="form-select" onchange="pilihKategori(this.value)" required>
                                            <option value="">Pilih Kategori</option>
                                            <?php
                                            $list_kategori = mysqli_query($koneksi, "SELECT DISTINCT Kategori FROM kategori ORDER BY Kategori");
                                            while ($data_kategori = mysqli_fetch_assoc($list_kategori)) {
                                                $selected = (@$_GET['kategori'] == $data_kategori['Kategori']) ? 'selected' : '';
                                                echo "<option value='" . $data_kategori['Kategori'] . "' " . $selected . ">" . $data_kategori['Kategori'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                        <label for="kategoriSelect">Kategori</label>

                                    </div>


                                </div>
                            </div>

                            <script>
                                function pilihKategori(value) {
                                    if (value) {
                                        window.location.href = `dashboard.php?page=tambah_kegiatan&kategori=${value}`;
                                    }
                                }
                            </script>

                            <!-- Sub Kategori Selection -->
                            <?php
                            if (@$_GET['kategori']) {
                                $kategori = $_GET['kategori'];
                            ?>
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="form-floating flex-grow-1">

                                            <select class="form-select" id="subKategoriSelect" name="sub_kategori" required onchange="pilihSubKategori(this.value)">
                                                <option value="">Pilih Sub Kategori</option>
                                                <?php
                                                $list_sub = mysqli_query($koneksi, "SELECT Sub_Kategori FROM kategori WHERE Kategori='$kategori' ORDER BY Sub_Kategori");
                                                while ($data_sub = mysqli_fetch_assoc($list_sub)) {
                                                    $selected = (@$_GET['sub_kategori'] == $data_sub['Sub_Kategori']) ? 'selected' : '';
                                                    echo "<option value='" . $data_sub['Sub_Kategori'] . "' " . $selected . ">" . $data_sub['Sub_Kategori'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                            <label for="subKategoriSelect">Sub Kategori</label>
                                        </div>


                                    </div>
                                </div>
                            <?php
                            }
                            ?>

                            <script>
                                function pilihSubKategori(value) {
                                    if (value) {
                                        const urlParams = new URLSearchParams(window.location.search);
                                        const kategori = urlParams.get('kategori');
                                        window.location.href = `dashboard.php?page=tambah_kegiatan&kategori=${kategori}&sub_kategori=${value}`;
                                    }
                                }
                            </script>

                            <!-- nama Kegiatan dan kredit -->
                            <?php
                            if (@$_GET['sub_kategori']) {
                                $kategori = $_GET['kategori'];
                                $sub_kategori = $_GET['sub_kategori'];
                            ?>
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="form-floating flex-grow-1">

                                            <!-- Hidden inputs -->
                                            <input type="hidden" name="kategori" value="<?= $kategori ?>">
                                            <input type="hidden" name="sub_kategori" value="<?= $sub_kategori ?>">

                                            <!-- Daftar Kegiatan Dropdown -->
                                            <select class="form-select" id="kegiatanList" name="kegiatan_list">
                                                <option value="" selected disabled>Daftar Kegiatan yang Ada</option>
                                                <?php
                                                $list_kategori = mysqli_query($koneksi, "SELECT Sub_Kategori, Jenis_Kegiatan 
                                                                                       FROM kegiatan 
                                                                                       INNER JOIN kategori USING(Id_Kategori) 
                                                                                       WHERE Sub_Kategori='$sub_kategori'
                                                                                       ORDER BY Jenis_Kegiatan ASC");
                                                while ($data_kegiatan = mysqli_fetch_assoc($list_kategori)) {
                                                ?>
                                                    <option value="<?= $data_kegiatan['Jenis_Kegiatan']; ?>">
                                                        <?= $data_kegiatan['Jenis_Kegiatan']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                            <label for="kegiatanList">Kegiatan yang Sudah Ada</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Input Nama Kegiatan -->
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="form-floating flex-grow-1">
                                            <input type="text"
                                                class="form-control"
                                                id="namaKegiatan"
                                                name="nama_kegiatan"
                                                placeholder="Masukkan nama kegiatan"
                                                required>
                                            <label for="namaKegiatan">Nama Kegiatan</label>
                                            <div class="invalid-feedback">
                                                Mohon isi nama kegiatan
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Input Kredit -->
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="form-floating flex-grow-1">
                                            <input type="number"
                                                class="form-control"
                                                id="kreditInput"
                                                name="kredit"
                                                min="1"
                                                max="15"
                                                placeholder="Masukkan jumlah kredit"
                                                required>
                                            <label for="kreditInput">Kredit</label>
                                            <div class="invalid-feedback">
                                                Mohon isi kredit point (1-100)
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Form Actions -->
                                <div class="col-md-8">
                                    <div class="d-flex justify-content-end gap-2 mt-4">

                                        <button type="submit"
                                            name="tombol_tambah"
                                            class="btn btn-primary btn-sm px-4">
                                            <i class="bi bi-check-lg me-2"></i>Simpan
                                        </button>
                                    </div>
                                </div>
                            <?php } ?>

                            <style>
                                /* .form-control,
                                .form-select {
                                    height: 40px;
                                    border-radius: 8px;
                                    border: 1px solid #dee2e6;
                                    transition: all 0.2s ease-in-out;
                                }

                                .form-control:focus,
                                .form-select:focus {
                                    border-color: #86b7fe;
                                    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, .25);
                                }

                                .form-floating {
                                    margin-bottom: 1rem;
                                }

                                .form-floating>.form-control,
                                .form-floating>.form-select {
                                    padding-top: 1.625rem;
                                    padding-bottom: 0.625rem;
                                }

                                .form-floating>label {
                                    padding: 1rem 0.75rem;
                                    color: #6c757d;
                                } */
                            </style>


                        </div>

                        <!-- Form Actions -->
                        <!-- <div class="d-flex justify-content-center gap-2 mt-4">
                            <a href="dashboard.php?page=kategori_kegiatan" class="btn btn-light btn-sm px-4">
                                <i class="bi bi-arrow-left me-2"></i>Kembali
                            </a>
                            
                        </div> -->

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

    // Prevent form resubmission on page refresh
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>