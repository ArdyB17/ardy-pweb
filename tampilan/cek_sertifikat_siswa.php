<?php
// Check if user is logged in and has appropriate access
if (!isset($_COOKIE['level_user'])) {
    echo "<script>alert('Silahkan login terlebih dahulu');window.location.href='../login.php';</script>";
    exit;
}

// Initialize variables from GET parameters
$pdfFile = isset($_GET['file']) ? $_GET['file'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';

// Validate if file parameter exists
if (empty($pdfFile) || empty($id)) {
    echo "<script>alert('Data tidak ditemukan');window.location.href='dashboard.php?page=sertifikat';</script>";
    exit;
}

// Fetch certificate and related data
$query = "SELECT Kategori, Sub_Kategori, Jenis_Kegiatan, Status, Catatan, Sertifikat FROM sertifikat INNER JOIN kegiatan USING(Id_Kegiatan) INNER JOIN kategori USING(Id_Kategori) WHERE id_sertifikat = '$id'";

$data = mysqli_fetch_assoc(mysqli_query($koneksi, $query));

// Check if data exists
if (!$data) {
    echo "<script>alert('Data sertifikat tidak ditemukan');window;.location.href='dashboard.php?page=sertifikat';</script>";
    exit;
}

// Handle file upload if submitted
if (isset($_POST['upload_sertifikat']) && isset($_FILES["sertifikat"])) {
    $tgl = date('Y-m-d');
    $sertifikat             = $_FILES['sertifikat']['name'];
    $file                   = $_FILES['sertifikat'];
    $folder                 = '../sertifikat/';
    $ekstensi               = strtolower(pathinfo($_FILES['sertifikat']['name'], PATHINFO_EXTENSION));
    $ukuran                 = $file['size'];
    $nis                    = $_COOKIE['nis'];

    // validasi file atau cek file
    if ($ekstensi != 'pdf') {
        echo "<script>alert('Hanya file .pdf yang diperbolehkan!');window.location.href='dashboard.php?page=sertifikat';</script>";
    } elseif ($ukuran > 2097152) {
        echo "<script>alert('Ukuran File terlalu besar');window.location.href='dashboard.php?page=sertifikat';</script>";
    } else {
        // generate nama file   
        do {
            $randomString = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 5);
            $newFileName = $nis . $randomString . ".pdf";
            $targetFile = $folder . $newFileName;
        } while (file_exists($targetFile));

        // Hapus file lama jika ada
        $file_path = "../sertifikat/" . $data['Sertifikat'];
        if (!empty($data['Sertifikat']) && file_exists($file_path)) {
            unlink($file_path);
        }

        // upload file dengan nama baru
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            $update = mysqli_query($koneksi, "UPDATE sertifikat SET Sertifikat = '$newFileName', Status = 'Menunggu Validasi', Tanggal_Status_Berubah= '$tgl' WHERE id_sertifikat = '$id'");


            if ($update) {
                echo "<script>alert('Sertifikat berhasil diupload');window.location.href='dashboard.php?page=sertifikat';</script>";
            } else {
                echo "<script>alert('Sertifikat gagal diupload');window.location.href='dashboard.php?page=sertifikat';</script>" . mysqli_error($koneksi);
            }
        } else {
            echo "<script>alert('Sertifikat gagal diupload');window.location.href='dashboard.php?page=sertifikat';</script>";
        }
    }
}

?>


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

                <!-- Certificate Details -->
                <div class="certificate-details mb-4">
                    <h5 class="text-primary fw-bold mb-4">Detail Sertifikat</h5>
                    <div class="certificate-info-item">
                        <label class=" text-muted">Kategori</label>
                        <p class=" fw-semibold text-dark"><?= htmlspecialchars($data['Kategori']) ?></p>
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

                <div class="mt-4">
                    <a href="dashboard.php?page=sertifikat_siswa" class="btn btn-secondary ">
                        <i class="bi bi-arrow-left me-2"></i>Kembali
                    </a>
                </div>

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