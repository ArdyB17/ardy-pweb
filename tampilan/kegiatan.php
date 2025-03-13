<?php
if (isset($_GET['Id_Kegiatan'])) {

    $Id_Kegiatan = $_GET['Id_Kegiatan'];
    $hasil_kegiatan = mysqli_query($koneksi, "DELETE FROM kegiatan WHERE Id_Kegiatan='$Id_Kegiatan'");

    if ($hasil_kegiatan) {
        echo "<script>alert('Data berhasil dihapus!')</script>";
        echo "<script>location='dashboard.php?page=kegiatan';</script>";
    } else {
        echo "<script>alert('Data gagal dihapus!')</script>";
        echo "<script>location='dashboard.php?page=kegiatan';</script>";
    }
}

// Add search functionality
$search_condition = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($koneksi, $_GET['search']);
    $search_condition = "WHERE kegiatan.Jenis_Kegiatan LIKE '%$search%' 
                        OR kegiatan.Angka_Kredit LIKE '%$search%'
                        OR kategori.Kategori LIKE '%$search%'
                        OR kategori.Sub_Kategori LIKE '%$search%'";
}

// Modified query with search condition
$query = mysqli_query($koneksi, "SELECT * FROM kategori 
                                INNER JOIN kegiatan ON kategori.Id_Kategori = kegiatan.Id_Kategori 
                                $search_condition 
                                ORDER BY kategori.Sub_Kategori");
?>

<style>
    <?php include '../css/style_kegiatan.css'; ?>
</style>


<div class="container-fluid py-4 px-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-11 col-md-8">

            <!-- Enhanced Header Card with Search -->
            <div class="card border-0 shadow-lg rounded-4 mb-4">
                <div class="card-header bg-dark bg-gradient text-white p-4 rounded-top-4">
                    <div class="d-flex flex-column gap-3">
                        <!-- Title and Add Button -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-folder2-open fs-4 me-2"></i>
                                <h4 class="mb-0 fw-semibold">Data Kegiatan & Kategori</h4>
                            </div>

                            <a href="dashboard.php?page=tambah_kegiatan"
                                class="btn btn-light btn-sm px-3 py-2 d-flex align-items-center gap-2 hover-elevate">
                                <i class="bi bi-plus-circle"></i>
                                <span>Tambah Kategori</span>
                            </a>
                        </div>

                        <!-- Search Form -->
                        <div class="search-wrapper">
                            <form method="GET" action="">
                                <input type="hidden" name="page" value="kegiatan">
                                <div class="input-group search-container">
                                    <input type="text"
                                        class="form-control search-bar"
                                        placeholder="Cari berdasarkan kegiatan, kategori, atau jumlah point..."
                                        name="search"
                                        value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                                        autocomplete="off">
                                    <button class="btn search-button" type="submit">
                                        <i class="bi bi-search search-icon"></i>
                                        <span class="search-text">Cari</span>
                                    </button>
                                </div>
                            </form>
                        </div>


                    </div>
                </div>
            </div>

            <!-- No Results Message -->
            <?php if (mysqli_num_rows($query) == 0) : ?>
                <div class="no-results">
                    <i class="bi bi-search"></i>
                    <h5>Tidak ada hasil yang ditemukan</h5>
                    <p>Coba gunakan kata kunci yang berbeda</p>
                </div>
            <?php endif; ?>

            <!-- Table Card -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-0">
                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead>
                                <tr>
                                    <th class="px-4 py-3 fs-5 text-center" width="10%">No</th>
                                    <th class="px-4 py-3 fs-5" width="40%">Jenis Kegiatan</th>
                                    <th class="px-4 py-3 fs-5" width="25%">Point</th>
                                    <th class="px-4 py-3 fs-5 text-center" width="25%">Actions</th>
                                </tr>
                            </thead>

                            <style>
                                .table {
                                    border-spacing: 0 8px;
                                    border-collapse: separate;
                                }

                                .table tbody tr {
                                    background: #fff;
                                    transition: all 0.2s ease;
                                }

                                .table tbody tr:hover {
                                    transform: translateY(-2px);
                                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                                }

                                .table th,
                                .table td {
                                    padding: 16px 24px;
                                    vertical-align: middle;
                                }

                                .table th {
                                    font-size: 0.9rem;
                                    letter-spacing: 0.5px;
                                }
                            </style>

                            <tbody>

                                <?php
                                $last_kategori_id = null;
                                $no = 1;

                                while ($baris = mysqli_fetch_assoc($query)) {
                                    if ($last_kategori_id != $baris['Id_Kategori']) {
                                        if ($last_kategori_id != null) { ?>
                                            <tr>
                                                <td colspan='4' class='spacer'></td>
                                            </tr>
                                        <?php } ?>

                                        <tr class='category-header'>
                                            <td colspan='4' class='py-3 px-4'>
                                                <div class='d-flex align-items-center justify-content-between'>
                                                    <div class='d-flex align-items-center'>
                                                        <i class='bi bi-bookmark-fill me-2 text-primary fs-5'></i>
                                                        <span class='fw-semibold fs-5'><?php echo htmlspecialchars($baris['Sub_Kategori']); ?></span>
                                                    </div>
                                                    <a href="dashboard.php?page=ubah_sub_kategori&Id_Kategori=<?= htmlspecialchars($baris['Id_Kategori']) ?>"
                                                        class="btn btn-outline-primary btn-sm">
                                                        <i class="bi bi-pencil-square me-1"></i>Edit Sub Kategori
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>

                                    <?php
                                        $no = 1;
                                    }
                                    ?>
                                    <tr>
                                        <td class="px-4 py-3 text-center">
                                            <span class="badge bg-dark-subtle text-dark rounded-pill px-3 py-2 fs-6">
                                                <?php echo $no++; ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($baris['Jenis_Kegiatan']); ?></td>
                                        <td>
                                            <span class="badge bg-primary px-3 py-2 fs-6">
                                                <?php echo htmlspecialchars($baris['Angka_Kredit']); ?> point
                                            </span>
                                        </td>
                                        <td class="text-center">

                                            <div class="d-flex gap-2 justify-content-center">

                                                <a href="dashboard.php?page=ubah_kegiatan&Id_Kegiatan=<?= htmlspecialchars($baris['Id_Kegiatan']) ?>"
                                                    class="btn btn-warning btn-sm px-3">
                                                    <i class="bi bi-pencil-square me-1"></i>Edit
                                                </a>

                                                <a href="dashboard.php?page=kegiatan&Id_Kegiatan=<?php echo htmlspecialchars($baris['Id_Kegiatan']); ?>"
                                                    class="btn btn-danger btn-sm px-3"
                                                    onclick="return confirm('Yakin ingin menghapus kegiatan ini?');">
                                                    <i class="bi bi-trash me-1"></i>Hapus
                                                </a>

                                            </div>

                                        </td>
                                    </tr>
                                <?php
                                    // Perbarui ID kategori terakhir
                                    $last_kategori_id = $baris['Id_Kategori'];
                                }
                                ?>
                            </tbody>


                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>