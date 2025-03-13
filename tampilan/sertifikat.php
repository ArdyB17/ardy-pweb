<?php
// Fungsi untuk mendapatkan sertifikat berdasarkan status dan kegiatan
function getSertifikat($status, $koneksi, $kegiatan = null)
{
    $whereClause = "";
    if ($status !== '') {
        $whereClause = "WHERE Status='$status'";
        if (!empty($kegiatan)) {
            $whereClause .= " AND Jenis_Kegiatan LIKE '%" . $kegiatan . "%'";
        }
    } else {
        if (!empty($kegiatan)) {
            $whereClause = "WHERE Jenis_Kegiatan LIKE '%" . $kegiatan . "%'";
        }
    }

    $query = "SELECT * FROM sertifikat
              INNER JOIN kegiatan USING(Id_Kegiatan)
              INNER JOIN kategori USING(Id_Kategori)
              INNER JOIN siswa USING(NIS)
              $whereClause
              ORDER BY Sub_Kategori, Tanggal_Upload DESC";

    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "<div class='row g-4'>";
        while ($data = mysqli_fetch_assoc($result)) {
?>
            <div class='col-12'>
                <div class='card certificate-card border-0 shadow-sm'>
                    <div class='row g-0'>
                        <!-- Left Column -->
                        <div class='col-md-8 p-4'>
                            <div class='certificate-info'>
                                <!-- Category & Activity Info -->
                                <div class='mb-3'>
                                    <span class='badge bg-primary mb-2'><?= $data['Kategori'] ?></span>
                                    <h5 class='card-title mb-1'><?= $data['Sub_Kategori'] ?></h5>
                                    <p class='text-primary fw-bold mb-0'><?= $data['Jenis_Kegiatan'] ?></p>
                                </div>

                                <!-- Student Info with enhanced layout -->
                                <div class='mb-3'>
                                    <div class='d-flex align-items-start gap-3'>
                                        <div class='student-avatar'>
                                            <?= strtoupper(substr($data['Nama_Siswa'], 0, 1)) ?>
                                        </div>
                                        <div class='student-details'>
                                            <div class='d-flex align-items-center gap-2 mb-2'>
                                                <h6 class='fw-medium mb-0'><?= $data['Nama_Siswa'] ?></h6>
                                                <span class='badge bg-primary rounded-pill fs-xs'>
                                                    <i class='bi bi-person-badge me-1'></i><?= $data['NIS'] ?>
                                                </span>
                                            </div>
                                            <a href='https://wa.me/<?= preg_replace("/[^0-9]/", "", $data['No_Telp']) ?>'
                                                target='_blank'
                                                class='text-decoration-none contact-link'>
                                                <i class='bi bi-whatsapp me-1'></i>
                                                <?= $data['No_Telp'] ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Meta Info -->
                                <div class='certificate-meta mt-auto'>
                                    <p class='text-muted mb-1'>
                                        <i class='bi bi-calendar3 me-2'></i>
                                        <?= date('d M Y', strtotime($data['Tanggal_Upload'])) ?>
                                    </p>
                                    <p class='text-muted mb-0'>
                                        <i class='bi bi-mortarboard me-2'></i>
                                        Angkatan <?= $data['Angkatan'] ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class='col-md-4 p-4 bg-light'>
                            <div class='d-flex flex-column h-100'>
                                <div class='preview-section text-center mb-4'>
                                    <?php
                                    $file_path = '../sertifikat/' . $data['Sertifikat'];
                                    if (file_exists($file_path)) {
                                        // Generate thumbnail for PDF
                                        $pdf_thumbnail = '../thumbnails/' . pathinfo($data['Sertifikat'], PATHINFO_FILENAME) . '.jpg';
                                        if (!file_exists($pdf_thumbnail)) { ?>
                                            <i class="bi bi-file-pdf document-icon"></i>
                                        <?php } else { ?>
                                            <img src="<?= $pdf_thumbnail ?>" alt="PDF Preview" class="pdf-preview mb-2">
                                        <?php }
                                    } else { ?>
                                        <i class="bi bi-file-earmark-x document-icon"></i>
                                    <?php } ?>
                                    <p class='text-muted small mb-0'>
                                        <?= pathinfo($data['Sertifikat'], PATHINFO_FILENAME) ?>
                                    </p>
                                </div>

                                <div class='action-buttons mt-auto'>
                                    <div class='d-grid gap-2'>
                                        <a href='dashboard.php?page=cek_sertifikat&file=<?= $data['Sertifikat'] ?>&id=<?= $data['id_sertifikat'] ?>'
                                            class='btn btn-primary d-flex align-items-center justify-content-center gap-2'>
                                            <i class='bi bi-eye'></i>
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
        showNoDataMessage();
    }
}

function showNoDataMessage()
{
    $html = <<<HTML
    <div class='text-center py-5'>
    <!-- <img src='../gambar/no-data.svg' 
         alt='No Data' 
         class='no-data-img mb-3' 
         style='width: 200px;'> -->
    <h5 class='text-muted'>Tidak Ada Data</h5>
    </div>
    HTML;

    echo $html;
}

?>

<style>
    <?php include '../css/style_sertifikat.css' ?>
</style>

<div class='container-fluid py-4'>
    <div class='row justify-content-center'>
        <div class='col-xl-10'>
            <div class='card border-0 shadow-lg rounded-6'>

                <!-- Improved Header Section -->
                <div class='card-header bg-white border-0 p-4 rounded-5'>
                    <!-- Title Centered at Top -->
                    <div class='text-center mb-4'>
                        <h2 class='display-6 fw-bold text-primary'>
                            <i class='bi bi-card-heading me-2'></i>Sertifikat
                        </h2>
                    </div>

                    <!-- Search and Print Controls -->
                    <div class='row justify-content-center align-items-center g-3'>
                        <!-- Search Field -->
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
                        <div class='col-md-4 col-lg-3'>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#reportModal"
                                class='btn btn-warning w-100 btn-lg d-flex align-items-center justify-content-center gap-2'>
                                <i class='bi bi-printer-fill'></i>
                                <span>Cetak Laporan</span>
                            </button>
                        </div>

                        <!-- Report Modal -->
                        <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="reportModalLabel">Pilih Filter Laporan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="reportForm">
                                            <div class="mb-3">
                                                <label for="angkatan" class="form-label">Angkatan</label>
                                                <select class="form-select" id="angkatan" name="angkatan">
                                                    <option hidden value="">Pilih Angkatan</option>
                                                    <option value="semua">Semua Angkatan</option>
                                                    <?php
                                                    $query = mysqli_query($koneksi, "SELECT DISTINCT Angkatan FROM siswa ORDER BY Angkatan DESC");
                                                    while ($row = mysqli_fetch_assoc($query)) {
                                                        echo "<option value='" . $row['Angkatan'] . "'>" . $row['Angkatan'] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="status" class="form-label">Status</label>
                                                <select class="form-select" id="status" name="status">
                                                    <option hidden value="">Pilih Status</option>
                                                    <option value="semua">Semua Status</option>
                                                    <?php
                                                    $query = mysqli_query($koneksi, "SELECT DISTINCT Status FROM sertifikat WHERE Status IS NOT NULL ORDER BY Status");
                                                    while ($row = mysqli_fetch_assoc($query)) {
                                                        echo "<option value='" . $row['Status'] . "'>" . $row['Status'] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        <button type="button" class="btn btn-primary" onclick="generateReport()">Cetak Laporan</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                            function generateReport() {
                                const angkatan = document.getElementById('angkatan').value;
                                const status = document.getElementById('status').value;
                                window.open(`../cetak/cetak_laporan.php?angkatan=${angkatan}&status=${status}`, '_blank');
                                $('#reportModal').modal('hide');
                            }
                        </script>

                        <?php
                        if (@$_POST['tombol_cetak_laporan']) {
                            setcookie('angkatan', $_POST['angkatan'], time() + (60 * 60 * 24 * 7), '/');
                            setcookie('status', $_POST['status'], time() + (60 * 60 * 24 * 7), '/');
                            echo "<script>window.location='../cetak/cetak_laporan.php'</script>";
                        }
                        ?>

                    </div>
                </div>

                <!-- Updated Navigation Tabs -->
                <div class='card-body p-4'>
                    <?php
                    $activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'menunggu';
                    ?>
                    <nav class='nav-tabs-wrapper'>
                        <div class='nav nav-tabs nav-tabs-custom border-0 mb-3 d-flex flex-wrap justify-content-start' id='nav-tab' role='tablist'>
                            <button class='nav-link <?= $activeTab === 'semua' ? 'active' : '' ?> p-3 me-2 mb-2'
                                style='width: 160px; white-space: nowrap;'
                                data-bs-toggle='tab'
                                data-bs-target='#semua'
                                onclick="updateURL('semua')">
                                <i class='bi bi-grid me-2'></i>
                                <span>Semua</span>
                            </button>
                            <button class='nav-link <?= $activeTab === 'menunggu' ? 'active' : '' ?> p-3 me-2 mb-2'
                                style='width: 160px; white-space: nowrap;'
                                data-bs-toggle='tab'
                                data-bs-target='#menunggu-validasi'
                                onclick="updateURL('menunggu')">
                                <i class='bi bi-clock-history me-2'></i>
                                <span>Menunggu</span>
                            </button>
                            <button class='nav-link <?= $activeTab === 'tidak-valid' ? 'active' : '' ?> p-3 me-2 mb-2'
                                style='width: 160px; white-space: nowrap;'
                                data-bs-toggle='tab'
                                data-bs-target='#tidak-valid'
                                onclick="updateURL('tidak-valid')">
                                <i class='bi bi-x-circle me-2'></i>
                                <span>Tidak Valid</span>
                            </button>
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
                        <?php $kegiatan = isset($_POST['kegiatan']) ? $_POST['kegiatan'] : null; ?>

                        <!-- Tab Panes -->
                        <div class='tab-pane fade <?= $activeTab === 'semua' ? 'active show' : '' ?>' id='semua'>
                            <?php getSertifikat('', $koneksi, $kegiatan); ?>
                        </div>
                        <div class='tab-pane fade <?= $activeTab === 'menunggu' ? 'active show' : '' ?>' id='menunggu-validasi'>
                            <?php getSertifikat('Menunggu Validasi', $koneksi, $kegiatan); ?>
                        </div>
                        <div class='tab-pane fade <?= $activeTab === 'tidak-valid' ? 'active show' : '' ?>' id='tidak-valid'>
                            <?php getSertifikat('Tidak Valid', $koneksi, $kegiatan); ?>
                        </div>
                        <div class='tab-pane fade <?= $activeTab === 'valid' ? 'active show' : '' ?>' id='valid'>
                            <?php getSertifikat('Valid', $koneksi, $kegiatan); ?>
                        </div>
                    </div>
                </div>

                <!-- for the nav system -->
                <script>
                    function updateURL(tab) {
                        // Get current URL
                        let url = new URL(window.location.href);
                        // Update or add the tab parameter
                        url.searchParams.set('tab', tab);
                        // Update browser history without reloading
                        window.history.pushState({}, '', url);
                    }

                    // Preserve tab state on form submission
                    document.querySelector('form').addEventListener('submit', function(e) {
                        e.preventDefault();
                        let currentTab = new URL(window.location.href).searchParams.get('tab') || 'menunggu';
                        let formData = new FormData(this);
                        let url = this.action + (this.action.includes('?') ? '&' : '?') + 'tab=' + currentTab;
                        this.action = url;
                        this.submit();
                    });

                    // Function to handle report printing with filters
                    function printReport() {
                        // Get active tab (status) and any search parameters
                        let currentTab = new URL(window.location.href).searchParams.get('tab') || 'semua';
                        let statusMap = {
                            'semua': 'semua',
                            'menunggu': 'Menunggu Validasi',
                            'valid': 'Valid',
                            'tidak-valid': 'Tidak Valid'
                        };

                        // Map tab name to actual status value
                        let status = statusMap[currentTab];

                        // Default angkatan to 'semua' or get from a selector if implemented
                        let angkatan = 'semua';

                        // Redirect to print page with parameters
                        window.open(`../cetak/cetak_laporan.php?angkatan=${angkatan}&status=${status}`, '_blank');
                    }
                </script>

            </div>
        </div>
    </div>
</div>