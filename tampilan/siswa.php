<?php
/*=============================================================================
* Data Siswa Page
*============================================================================*/

if (isset($_GET['NIS'])) {
    // Get student ID from URL parameter
    $nis = $_GET['NIS'];

    // Delete student data from database
    $delete_pengguna = mysqli_query($koneksi, "DELETE FROM pengguna WHERE NIS = '$nis'");
    $delete_sertifikat = mysqli_query($koneksi, "DELETE FROM sertifikat WHERE NIS = '$nis'");
    $delete_siswa = mysqli_query($koneksi, "DELETE FROM siswa WHERE NIS = '$nis'");

    if ($delete_siswa) {
        echo "<script>alert('Data berhasil dihapus!');window.location.href='dashboard.php?page=siswa';</script>";
    } else {
        echo "<script>alert('Data gagal dihapus!');window.location.href='dashboard.php?page=siswa';</script>";
    }
}

// Enhanced search functionality
$search_condition = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($koneksi, $_GET['search']);
    $search_condition = "WHERE siswa.NIS LIKE '%$search%' 
                        OR siswa.Nama_Siswa LIKE '%$search%'
                        OR siswa.No_Absen LIKE '%$search%'
                        OR siswa.No_Telp LIKE '%$search%'
                        OR siswa.Email LIKE '%$search%'
                        OR siswa.Kelas LIKE '%$search%'
                        OR siswa.Angkatan LIKE '%$search%'
                        OR jurusan.Jurusan LIKE '%$search%'";
}

// Single query for all student data
$data_siswa = mysqli_query(
    $koneksi,
    "SELECT siswa.*, jurusan.Jurusan 
     FROM siswa 
     INNER JOIN jurusan ON siswa.Id_Jurusan = jurusan.Id_Jurusan 
     $search_condition
     ORDER BY siswa.NIS ASC"
);
?>
<style>
    <?php include '../css/style_siswa.css'; ?>.card-header .input-group .form-control {
        background-color: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
    }

    .card-header .input-group .form-control::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }

    .card-header .input-group .form-control:focus {
        background-color: rgba(255, 255, 255, 0.15);
        border-color: rgba(255, 255, 255, 0.3);
        color: white;
        box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.1);
    }

    .card-header .input-group .btn-light {
        background-color: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
    }

    .card-header .input-group .btn-light:hover {
        background-color: rgba(255, 255, 255, 0.2);
    }
</style>

<!-- Updated search styling -->
<style>
    .search-wrapper {
        position: relative;
        margin-top: 1.5rem;
    }

    .search-container {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50px;
        padding: 5px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .search-bar {
        background: transparent !important;
        border: none !important;
        color: white !important;
        padding: 12px 20px;
        width: 100%;
        font-size: 1rem;
    }

    .search-bar:focus {
        outline: none;
        box-shadow: none;
    }

    .search-bar::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }

    .search-button {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        border-radius: 50px;
        padding: 10px 25px;
        color: white;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .search-button:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-1px);
    }

    .search-icon {
        margin-right: 8px;
    }

    .no-results {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        text-align: center;
        margin-top: 2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .no-results i {
        font-size: 3rem;
        color: #6c757d;
        margin-bottom: 1rem;
    }

    .no-results h5 {
        color: #343a40;
        margin-bottom: 0.5rem;
    }

    .no-results p {
        color: #6c757d;
    }
</style>

<div class="container-fluid dashboard-container py-4 px-4">

    <div class="row justify-content-center">
        <div class="col-12 col-xxl-10">

            <!-- Aesthetic Header Card -->
            <div class="card border-0 shadow-lg rounded-4 mb-4">
                <div class="card-header bg-dark bg-gradient text-white p-4 rounded-top-4">

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h4 class="mb-0 fw-semibold fs-5">
                            <i class="bi bi-people-fill me-2"></i>Data Siswa
                        </h4>

                        <a href="dashboard.php?page=tambah_siswa" class="btn btn-light btn-sm px-3 py-2 shadow-sm">
                            <i class="bi bi-plus-lg me-2"></i>Tambah Siswa
                        </a>
                    </div>

                    <!-- Updated search form -->
                    <div class="search-wrapper">
                        <form method="GET" action="">
                            <input type="hidden" name="page" value="siswa">
                            <div class="input-group search-container">
                                <input type="text"
                                    class="form-control search-bar"
                                    placeholder="Cari berdasarkan NIS, nama, kelas, atau info lainnya..."
                                    name="search"
                                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                                    autocomplete="off">
                                <button class="btn search-button" type="submit">
                                    <i class="bi bi-search search-icon"></i>
                                    Cari
                                </button>
                            </div>
                        </form>
                    </div>


                </div>
            </div>

            <!-- No results message -->
            <?php if (mysqli_num_rows($data_siswa) == 0) : ?>
                <div class="no-results">
                    <i class="bi bi-search"></i>
                    <h5>Tidak ada hasil yang ditemukan</h5>
                    <p>Coba gunakan kata kunci yang berbeda</p>
                </div>
            <?php endif; ?>

            <!-- Cards Grid -->
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php
                while ($data = mysqli_fetch_assoc($data_siswa)) { ?>
                    <!-- main col for my card -->
                    <div class="col-12">

                        <!-- the main card div -->
                        <div class="card h-100 border-0 shadow-sm hover-card ">

                            <!-- Card Header with super cool avatar -->
                            <div class="card-header custom-header border-0">
                                <div class="d-flex align-items-center">
                                    <div class="header-avatar-wrapper">
                                        <div class="header-avatar">
                                            <?php echo strtoupper(substr($data['Nama_Siswa'], 0, 1)); ?>
                                        </div>
                                    </div>
                                    <div class="ms-3 text-white">
                                        <h6 class="mb-1 fw-bold"><?php echo $data['Nama_Siswa']; ?></h6>
                                        <div class="subtitle">
                                            <span class="badge bg-light bg-opacity-25 border-0">
                                                NIS: <?php echo $data['NIS']; ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="card-body">
                                <!-- row with 2 column -->
                                <div class="row g-3">
                                    <!-- Left Column -->
                                    <div class="col-6">
                                        <p class="text-muted mb-1 ">No. Absen</p>
                                        <p class="fw-medium mb-3"><?php echo $data['No_Absen']; ?></p>

                                        <p class="text-muted mb-1 ">No. Telp</p>
                                        <p class="fw-medium mb-3"><?php echo $data['No_Telp']; ?></p>

                                        <p class="text-muted mb-1 ">Email</p>
                                        <p class=" fw-medium mb-3 email-text" title="<?php echo $data['Email']; ?>">
                                            <?php echo $data['Email']; ?>
                                        </p>

                                    </div>

                                    <!-- Right Column -->
                                    <div class="col-6">

                                        <p class="text-muted mb-1 ">Jurusan</p>
                                        <span class="badge bg-info mb-3"><?php echo $data['Jurusan'] ?> <?= $data['Kelas'] ?></span>

                                        <p class="text-muted mb-1 fs-6">Angkatan</p>
                                        <p class="fw-medium mb-0"><?php echo $data['Angkatan']; ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Footer with Actions -->
                            <div class="card-footer bg-white border-0 pt-0 pb-3">
                                <div class=" d-flex justify-content-end gap-2">

                                    <!-- View Details Button -->
                                    <!-- <button type="button"
                                        class="btn btn-info btn-sm text-white view-details"
                                        data-bs-toggle="modal"
                                        data-bs-target="#detailModal<?php echo $data['NIS']; ?>">
                                        <i class="bi bi-eye-fill me-1"></i>Details
                                    </button> -->

                                    <!-- Edit Button -->
                                    <a href="dashboard.php?page=ubah_siswa&NIS=<?php echo $data['NIS']; ?>"
                                        class="btn btn-warning btn-sm text-white">
                                        <!-- <i class="bi bi-pencil-square me-1"></i>Edit -->📝 Edit
                                    </a>

                                    <!-- Delete Button -->
                                    <a href="dashboard.php?page=siswa&NIS=<?php echo $data['NIS']; ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Hapus data ini?')">
                                        <!-- <i class="bi bi-trash me-1"></i>Delete -->🗑️ Delete
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>


                    <!-- Detail Modal -->
                    <div class="modal fade" id="detailModal<?php echo $data['NIS']; ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg">

                                <!-- Modal Header -->
                                <div class="modal-header modal-custom-header border-0">

                                    <div class="d-flex align-items-center">
                                        <div class="modal-avatar-wrapper">
                                            <div class="modal-avatar">
                                                <?php echo strtoupper(substr($data['Nama_Siswa'], 0, 1)); ?>
                                            </div>
                                        </div>
                                        <div class="ms-3 text-white">
                                            <h5 class="modal-title fw-bold mb-1"><?php echo $data['Nama_Siswa']; ?></h5>
                                            <div class="modal-subtitle">
                                                <span class="badge bg-light bg-opacity-25 border-0">
                                                    NIS: <?php echo $data['NIS']; ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>


                                </div>


                                <!-- Modal Body -->
                                <div class="modal-body p-4">

                                    <div class="row g-4">
                                        <!-- left  -->
                                        <div class="col-md-6">
                                            <div class="detail-group">
                                                <span class="detail-label">No. Absen</span>
                                                <span class="detail-value"><?php echo $data['No_Absen']; ?></span>
                                            </div>
                                            <div class="detail-group">
                                                <span class="detail-label">No. Telp</span>
                                                <span class="detail-value"><?php echo $data['No_Telp']; ?></span>
                                            </div>
                                            <div class="detail-group">
                                                <span class="detail-label">Email</span>
                                                <span class="detail-value text-break"><?php echo $data['Email']; ?></span>
                                            </div>
                                        </div>

                                        <!-- right -->
                                        <div class="col-md-6">
                                            <div class="detail-group">
                                                <span class="detail-label">Jurusan</span>
                                                <span class="detail-value">
                                                    <span class="badge bg-info"><?php echo $data['Jurusan']; ?> <?= $data['Kelas'] ?></span>
                                                </span>
                                            </div>

                                            <div class="detail-group">
                                                <span class="detail-label">Angkatan</span>
                                                <span class="detail-value"><?php echo $data['Angkatan']; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Footer -->
                                <div class="modal-footer border-0 pt-0">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <a href="dashboard.php?page=ubah_siswa&NIS=<?php echo $data['NIS']; ?>"
                                        class="btn btn-primary">
                                        <i class="bi bi-pencil-square me-1"></i>Edit Data
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>

                <?php } ?>

            </div>
        </div>
    </div>
</div>