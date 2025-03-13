<?php
// Authentication check
if (!isset($_COOKIE['level_user'])) {
    echo "<script>alert('Silahkan login terlebih dahulu');window.location.href='../login.php';</script>";
    exit;
}

// Get logged in student's NIS
$nis = $_COOKIE['NIS'];

// Function to get student certificates
function getSertifikat($status, $koneksi, $nis, $kegiatan = null)
{
    // Build WHERE clause
    $whereClause = "WHERE sertifikat.NIS = '$nis'";
    if ($status !== '') {
        $whereClause .= " AND sertifikat.Status = '$status'";
    }
    if (!empty($kegiatan)) {
        $whereClause .= " AND kegiatan.Jenis_Kegiatan LIKE '%" . mysqli_real_escape_string($koneksi, $kegiatan) . "%'";
    }

    // Fixed query - removed duplicate query execution and fixed table references
    $query = "SELECT sertifikat.*, kegiatan.*, kategori.*, siswa.*
              FROM sertifikat 
              INNER JOIN kegiatan ON sertifikat.Id_Kegiatan = kegiatan.Id_Kegiatan
              INNER JOIN kategori ON kegiatan.Id_Kategori = kategori.Id_Kategori
              INNER JOIN siswa ON sertifikat.NIS = siswa.NIS
              $whereClause 
              ORDER BY sertifikat.Tanggal_Upload DESC";

    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "<div class='row g-4'>";
        while ($data = mysqli_fetch_assoc($result)) {
?>
            <div class='col-12'>
                <div class='card certificate-card border-0 shadow-sm'>
                    <div class='row g-0'>
                        <!-- Certificate Info -->
                        <div class='col-md-8 p-4'>
                            <div class='certificate-info'>
                                <!-- Category & Activity Info -->
                                <div class='mb-3'>
                                    <span class='badge bg-primary mb-2'><?= htmlspecialchars($data['Kategori']) ?></span>
                                    <h5 class='card-title mb-1'><?= htmlspecialchars($data['Sub_Kategori']) ?></h5>
                                    <p class='text-primary fw-bold mb-0'><?= htmlspecialchars($data['Jenis_Kegiatan']) ?></p>
                                </div>



                                <!-- Meta Info -->
                                <div class='certificate-meta mt-auto'>
                                    <p class='text-muted mb-1'>
                                        <i class='bi bi-calendar3 me-2'></i>
                                        <?= date('d M Y', strtotime($data['Tanggal_Upload'])) ?>
                                    </p>
                                    <?php if (!empty($data['Catatan'])): ?>
                                        <p class='text-danger mb-0'>
                                            <i class='bi bi-info-circle me-2'></i>
                                            Catatan: <?= htmlspecialchars($data['Catatan']) ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Preview & Actions -->
                        <div class='col-md-4 p-4 bg-light'>
                            <div class='d-flex flex-column h-100'>
                                <!-- PDF Preview -->
                                <div class='preview-section text-center mb-4'>
                                    <?php
                                    $file_path = '../sertifikat/' . $data['Sertifikat'];
                                    if (file_exists($file_path)) {
                                        echo '<i class="bi bi-file-pdf fs-1 text-danger"></i>';
                                    } else {
                                        echo '<i class="bi bi-file-earmark-x fs-1 text-muted"></i>';
                                    }
                                    ?>
                                    <p class='text-muted small mb-0'>
                                        <?= pathinfo($data['Sertifikat'], PATHINFO_FILENAME) ?>
                                    </p>
                                </div>

                                <!-- Action Buttons -->
                                <div class='action-buttons mt-auto'>
                                    <div class='d-grid gap-2'>
                                        <a href='dashboard.php?page=cek_sertifikat_siswa&file=<?= urlencode($data['Sertifikat']) ?>&id=<?= $data['id_sertifikat'] ?>'
                                            class='btn btn-primary'>
                                            <i class='bi bi-eye me-2'></i>
                                            Lihat Sertifikat
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
        echo "</div>";
    } else {
        ?>
        <div class='text-center py-5'>
            <i class='bi bi-file-earmark-x fs-1 text-muted mb-3'></i>
            <h5 class='text-muted'>Tidak ada sertifikat ditemukan</h5>
        </div>
<?php
    }
}
?>

<style>
    <?php include '../css/style_sertifikat.css' ?>
</style>

<div class='container-fluid py-4'>
    <div class='row justify-content-center'>
        <div class='col-xl-10'>
            <div class='card border-0 shadow-lg rounded-6'>
                <!-- Header Section -->
                <div class='card-header bg-white border-0 p-4 rounded-5'>
                    <div class='text-center mb-4'>
                        <h2 class='display-6 fw-bold text-primary'>
                            <i class='bi bi-card-heading me-2'></i>Sertifikat Saya
                        </h2>
                    </div>

                    <!-- Enhanced Search Form with Dropdown -->
                    <div class='row justify-content-center align-items-center g-3'>

                        <div class='col-md-8 col-lg-6'>
                            <div class="search-wrapper">
                                <datalist id='kegiatan'>
                                    <?php
                                    $list_kategori = mysqli_query($koneksi, "SELECT Jenis_Kegiatan FROM kegiatan");
                                    while ($data_kegiatan = mysqli_fetch_assoc($list_kategori)) {
                                    ?>
                                        <option value='<?= $data_kegiatan['Jenis_Kegiatan'] ?>'></option>
                                    <?php
                                    }
                                    ?>
                                </datalist>
                                <form method='GET' action=''>
                                    <input type="hidden" name="page" value="sertifikat_siswa">
                                    <?php if (isset($_GET['tab'])): ?>
                                        <input type="hidden" name="tab" value="<?= htmlspecialchars($_GET['tab']) ?>">
                                    <?php endif; ?>
                                    <div class="input-group">
                                        <input name='kegiatan'
                                            class='form-control form-control-lg shadow-none'
                                            autocomplete='off'
                                            list='kegiatan'
                                            type='search'
                                            placeholder='Cari Jenis Kegiatan...'
                                            value="<?php echo isset($_GET['kegiatan']) ? htmlspecialchars($_GET['kegiatan']) : ''; ?>">
                                        <button class='btn btn-primary px-4' type='submit'>
                                            <i class='bi bi-search'></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Print Button -->
                        <div class=' col-md-4'>
                            <a href='../cetak/generate_sertifikat.php'
                                class='btn btn-warning w-100 btn-lg d-flex align-items-center justify-content-center gap-2'>
                                <i class='bi bi-printer-fill'></i>
                                <span>Cetak Sertifikat</span>
                            </a>
                        </div>

                    </div>
                </div>

                <!-- Navigation Tabs -->
                <div class='card-body p-4'>
                    <?php
                    $activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'semua';
                    ?>
                    <nav class='nav-tabs-wrapper'>
                        <div class='nav nav-tabs nav-tabs-custom border-0 mb-3 d-flex flex-wrap justify-content-start' id='nav-tab' role='tablist'>
                            <!-- All Certificates Tab -->
                            <button class='nav-link <?= $activeTab === 'semua' ? 'active' : '' ?> p-3 me-2 mb-2'
                                style='width: 160px; white-space: nowrap;'
                                data-bs-toggle='tab'
                                data-bs-target='#semua'
                                onclick="updateURL('semua')">
                                <i class='bi bi-grid me-2'></i>
                                <span>Semua</span>
                            </button>

                            <!-- Pending Certificates Tab -->
                            <button class='nav-link <?= $activeTab === 'menunggu' ? 'active' : '' ?> p-3 me-2 mb-2'
                                style='width: 160px; white-space: nowrap;'
                                data-bs-toggle='tab'
                                data-bs-target='#menunggu-validasi'
                                onclick="updateURL('menunggu')">
                                <i class='bi bi-clock-history me-2'></i>
                                <span>Menunggu</span>
                            </button>

                            <!-- Invalid Certificates Tab -->
                            <button class='nav-link <?= $activeTab === 'tidak-valid' ? 'active' : '' ?> p-3 me-2 mb-2'
                                style='width: 160px; white-space: nowrap;'
                                data-bs-toggle='tab'
                                data-bs-target='#tidak-valid'
                                onclick="updateURL('tidak-valid')">
                                <i class='bi bi-x-circle me-2'></i>
                                <span>Tidak Valid</span>
                            </button>

                            <!-- Valid Certificates Tab -->
                            <button class='nav-link <?= $activeTab === 'valid' ? 'active' : '' ?> p-3 me-2 mb-2'
                                style='width: 160px; white-space: nowrap;'
                                data-bs-toggle='tab'
                                data-bs-target='#valid'
                                onclick="updateURL('valid')">
                                <i class='bi bi-check-circle me-2'></i>
                                <span>Valid</span>
                            </button>
                        </div>
                    </nav>

                    <!-- Tab Content -->
                    <div class='tab-content p-4 border-0 bg-light rounded-4' id='nav-tabContent'
                        style='max-height: 600px; overflow-y: auto;'>
                        <?php $kegiatan = isset($_GET['kegiatan']) ? $_GET['kegiatan'] : null; ?>

                        <!-- Tab Panes -->
                        <div class='tab-pane fade <?= $activeTab === 'semua' ? 'active show' : '' ?>' id='semua'>
                            <?php getSertifikat('', $koneksi, $nis, $kegiatan); ?>
                        </div>
                        <div class='tab-pane fade <?= $activeTab === 'menunggu' ? 'active show' : '' ?>' id='menunggu-validasi'>
                            <?php getSertifikat('Menunggu Validasi', $koneksi, $nis, $kegiatan); ?>
                        </div>
                        <div class='tab-pane fade <?= $activeTab === 'tidak-valid' ? 'active show' : '' ?>' id='tidak-valid'>
                            <?php getSertifikat('Tidak Valid', $koneksi, $nis, $kegiatan); ?>
                        </div>
                        <div class='tab-pane fade <?= $activeTab === 'valid' ? 'active show' : '' ?>' id='valid'>
                            <?php getSertifikat('Valid', $koneksi, $nis, $kegiatan); ?>
                        </div>
                    </div>
                </div>

                <!-- URL Update Script -->
                <script>
                    function updateURL(tab) {
                        let url = new URL(window.location.href);
                        url.searchParams.set('tab', tab);
                        window.history.pushState({}, '', url);
                    }

                    // Preserve tab state and search parameters on form submission
                    document.querySelector('form').addEventListener('submit', function(e) {
                        e.preventDefault();
                        let currentTab = new URL(window.location.href).searchParams.get('tab') || 'semua';
                        let formData = new FormData(this);
                        let url = this.action + (this.action.includes('?') ? '&' : '?') +
                            'tab=' + currentTab;
                        window.location.href = url + '&kegiatan=' + encodeURIComponent(formData.get('kegiatan'));
                    });
                </script>
            </div>
        </div>
    </div>
</div>