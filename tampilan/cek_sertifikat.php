<?php
// Check authentication and access level
if (!isset($_COOKIE['level_user'])) {
    echo "<script>alert('Silahkan login terlebih dahulu');window.location.href='../login.php';</script>";
    exit;
}

// Get certificate ID and file from URL
$pdfFile = isset($_GET['file']) ? $_GET['file'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';

// Validate parameters
if (empty($pdfFile) || empty($id)) {
    echo "<script>alert('Data tidak ditemukan');window.location.href='dashboard.php?page=sertifikat';</script>";
    exit;
}

// Enhanced query to get complete certificate and student data 
$query = "SELECT s.Nama_Siswa, s.NIS, s.Kelas, s.No_Telp, s.Email, s.Angkatan,
          j.Jurusan, k.Kategori, k.Sub_Kategori, kg.Jenis_Kegiatan, 
          srt.Status, srt.Catatan, srt.Sertifikat
          FROM sertifikat srt
          INNER JOIN siswa s ON srt.NIS = s.NIS
          INNER JOIN jurusan j ON s.Id_Jurusan = j.Id_Jurusan
          INNER JOIN kegiatan kg ON srt.Id_Kegiatan = kg.Id_Kegiatan
          INNER JOIN kategori k ON kg.Id_Kategori = k.Id_Kategori 
          WHERE srt.id_sertifikat = '$id'";

$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

// Handle status update 
if (isset($_POST['update_status'])) {
    $status = $_POST['status'];
    $catatan = isset($_POST['catatan']) ? mysqli_real_escape_string($koneksi, $_POST['catatan']) : '';
    $tanggal = date('Y-m-d H:i:s');

    $update_query = "UPDATE sertifikat SET 
                    Status = '$status', 
                    Catatan = '$catatan',
                    Tanggal_Status_Berubah = '$tanggal'
                    WHERE id_sertifikat = '$id'";

    if (mysqli_query($koneksi, $update_query)) {
        echo "<script>alert('Status berhasil diperbarui');window.location.href='dashboard.php?page=sertifikat';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui status');window.location.href='dashboard.php?page=sertifikat';</script>";
    }
}

?>

<!-- CSS Styles -->

<style>
    .pdf-viewer {
        width: 100%;
        height: calc(100vh - 120px);
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }

    .info-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        height: calc(100vh - 120px);
        overflow-y: auto;
    }

    .status-badge {
        padding: 8px 15px;
        border-radius: 50px;
        font-weight: 500;
    }

    .status-badge.pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-badge.invalid {
        background: #f8d7da;
        color: #721c24;
    }

    .status-badge.valid {
        background: #d4edda;
        color: #155724;
    }

    .certificate-info-item {
        margin-bottom: 1.5rem;
    }

    .certificate-info-item label {
        color: #6c757d;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }

    .certificate-info-item p {
        font-weight: 500;
        margin-bottom: 0;
    }

    .divider {
        height: 1px;
        background: rgba(0, 0, 0, 0.1);
        margin: 1.5rem 0;
    }
</style>

<!-- Main Container -->
<div class="container-fluid py-4">
    <div class="row justify-content-center g-4">
        <!-- PDF Viewer Column -->
        <div class="col-12 col-lg-8">
            <?php
            $file_path = "../sertifikat/" . htmlspecialchars($pdfFile);
            if (file_exists($file_path)) {
                echo '<embed src="' . $file_path . '" type="application/pdf" class="pdf-viewer">';
            } else {
                echo '<div class="alert alert-danger">File sertifikat tidak ditemukan</div>';
            }
            ?>
        </div>

        <!-- Information Column -->
        <div class="col-12 col-lg-4">
            <div class="info-card p-4">
                <!-- Certificate Status -->
                <div class="text-center mb-4">
                    <?php
                    $statusClass = match ($data['Status']) {
                        'Menunggu Validasi' => 'pending',
                        'Tidak Valid' => 'invalid',
                        'Valid' => 'valid',
                        default => 'pending'
                    };
                    ?>
                    <span class="status-badge <?= $statusClass ?>">
                        <?= htmlspecialchars($data['Status']) ?>
                    </span>
                </div>

                <!-- Student Information Section -->
                <div class="student-info mb-4">
                    <h6 class="text-muted mb-3">Informasi Siswa</h6>
                    <div class="certificate-info-item">
                        <label>Nama Siswa</label>
                        <p><?= htmlspecialchars($data['Nama_Siswa']) ?></p>
                    </div>
                    <div class="certificate-info-item">
                        <label>NIS</label>
                        <p><?= htmlspecialchars($data['NIS']) ?></p>
                    </div>
                    <div class="certificate-info-item">
                        <label>Kelas & Jurusan</label>
                        <p><?= htmlspecialchars($data['Jurusan']) . " " . $data["Kelas"] ?> </p>
                    </div>
                    <div class="certificate-info-item">
                        <label>No Telp</label>
                        <p><?= htmlspecialchars($data['No_Telp']) ?></p>
                    </div>
                    <div class="certificate-info-item">
                        <label>Email</label>
                        <p><?= htmlspecialchars($data['Email']) ?></p>
                    </div>
                    <div class="certificate-info-item">
                        <label>Angkatan</label>
                        <p><?= htmlspecialchars($data['Angkatan']) ?></p>
                    </div>
                </div>

                <!-- Certificate Details -->
                <div class="certificate-details mb-4">
                    <h6 class="text-muted mb-3">Detail Sertifikat</h6>
                    <div class="certificate-info-item">
                        <label>Kategori</label>
                        <p><?= htmlspecialchars($data['Kategori']) ?></p>
                    </div>
                    <div class="certificate-info-item">
                        <label>Sub Kategori</label>
                        <p><?= htmlspecialchars($data['Sub_Kategori']) ?></p>
                    </div>
                    <div class="certificate-info-item">
                        <label>Jenis Kegiatan</label>
                        <p><?= htmlspecialchars($data['Jenis_Kegiatan']) ?></p>
                    </div>
                </div>

                <?php if ($data['Status'] === 'Menunggu Validasi' && $_COOKIE['level_user'] === 'operator'): ?>
                    <!-- Validation Actions -->
                    <div class="validation-actions">
                        <div class="d-grid gap-2">
                            <!-- Direct Valid Button with form -->
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="status" value="Valid">
                                <button type="submit"
                                    name="update_status"
                                    class="btn btn-success w-100">
                                    <i class="bi bi-check-circle me-2"></i>Valid
                                </button>
                            </form>

                            <!-- Not Valid Button - Opens note form -->
                            <button class="btn btn-danger"
                                onclick="showNoteForm()">
                                <i class="bi bi-x-circle me-2"></i>Tidak Valid
                            </button>
                        </div>
                    </div>

                    <!-- Note Form for Invalid Status -->
                    <div id="noteForm" class="mt-4" style="display: none;">
                        <form method="POST" action="">
                            <input type="hidden" name="status" value="Tidak Valid">
                            <div class="mb-3">
                                <label class="form-label">Catatan Penolakan <span class="text-danger">*</span></label>
                                <textarea name="catatan"
                                    class="form-control"
                                    rows="3"
                                    placeholder="Masukkan alasan penolakan..."
                                    required></textarea>
                            </div>

                            <div class="d-grid">
                                <button type="submit"
                                    name="update_status"
                                    class="btn btn-primary">
                                    Update Status
                                </button>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>

                <?php if (!empty($data['Catatan'])): ?>
                    <div class="mt-4">
                        <!-- <h6 class="text-muted mb-3">
                            <i class="bi bi-clipboard-check me-2"></i>Catatan Validasi
                        </h6> -->
                        <div class="validation-note p-3 rounded-3 border-start border-4 border-warning bg-light-warning">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-info-circle text-warning me-2"></i>
                                <small class="text-muted">Catatan Validasi</small>
                            </div>
                            <p class="mb-0 fw-normal text-dark">
                                <?= htmlspecialchars($data['Catatan']) ?>
                            </p>
                        </div>
                    </div>

                    <style>
                        .bg-light-warning {
                            background-color: rgba(255, 243, 205, 0.3);
                        }

                        .validation-note {
                            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
                            transition: all 0.3s ease;
                        }

                        .validation-note:hover {
                            transform: translateY(-2px);
                            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                        }
                    </style>
                <?php endif; ?>


            </div>
        </div>
    </div>
</div>

<script>
    // Show/hide validation form and handle validation logic
    function showValidationForm(status) {
        const form = document.getElementById('validationForm');
        const statusInput = document.getElementById('statusInput');
        const catatanInput = document.getElementById('catatanInput');
        
        form.style.display = 'block';
        statusInput.value = status;

        // Make catatan required for 'Tidak Valid' status
        if (status === 'Tidak Valid') {
            catatanInput.setAttribute('required', 'required');
            catatanInput.focus();
        } else {
            catatanInput.removeAttribute('required');
        }
    }

    // Show/hide note form for invalid status
    function showNoteForm() {
        const form = document.getElementById('noteForm');
        form.style.display = 'block';
        form.querySelector('textarea').focus();
    }

    // Add confirmation for valid status
    document.querySelector('button[name="direct_validate"]')?.addEventListener('click', function(e) {
        if (!confirm('Apakah Anda yakin ingin memvalidasi sertifikat ini?')) {
            e.preventDefault();
        }
    });
</script>