<?php
if (isset($_POST['tombol_tambah'])) {

    $id_jurusan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT Id_Jurusan FROM jurusan ORDER BY Id_Jurusan DESC LIMIT 1"));

    if ($id_jurusan) {
        $latestNumber = intval(substr($id_jurusan['Id_Jurusan'], 1));
        $noUrut = $latestNumber + 1;
    } else {
        $noUrut = 1;
    }

    $Id = 'J' . $noUrut;
    $jurusan = $_POST['Jurusan'];

    // Insert jurusan data
    $hasil = mysqli_query($koneksi, "INSERT INTO jurusan VALUES ('$Id', '$jurusan')");

    if ($hasil) {
        echo "<script>alert('Data jurusan berhasil ditambahkan');window.location='dashboard.php?page=jurusan'</script>";
    } else {
        echo "<script>alert('Data jurusan gagal ditambahkan');window.location='dashboard.php?page=tambah_jurusan'</script>";
    }
}
?>

<style>
    :root {
        --primary-gradient: linear-gradient(45deg, #1a237e, #283593);
        --form-bg: #ffffff;
        --input-border: #dee2e6;
        --input-focus: #86b7fe;
        --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.08);
        --shadow-md: 0 8px 16px rgba(0, 0, 0, 0.12);
    }

    /* Form Card Styling */
    .form-card {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: var(--shadow-md);
    }

    /* Header Styling */
    .card-header.bg-gradient {
        background: var(--primary-gradient) !important;
    }

    .header-icon {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .header-icon i {
        font-size: 1.5rem;
        color: white;
    }

    /* Form Controls */
    .form-floating {
        margin-bottom: 1.5rem;
    }

    .custom-input {
        border: 1px solid var(--input-border);
        border-radius: 8px;
    }

    .custom-input:focus {
        border-color: var(--input-focus);
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    /* Button Styling - Keeping hover effects */
    .btn {
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .back-button:hover {
        background: #e9ecef;
        transform: translateX(-3px);
    }

    .submit-button:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
    }


    /* ========================================
   Responsive Design System
   ======================================== */
    /* 4K Displays */
    @media (min-width: 2560px) {
        .container {
            max-width: 2400px;
        }

        .form-card {
            max-width: 1800px;
            margin: 0 auto;
        }
    }

    /* Large Desktops */
    @media (min-width: 1920px) and (max-width: 2559px) {
        .container {
            max-width: 1800px;
        }

        .card-body {
            padding: 2.5rem;
        }
    }

    /* Standard Desktops */
    @media (min-width: 1400px) and (max-width: 1919px) {
        .container {
            max-width: 1320px;
        }

        .card-body {
            padding: 2rem;
        }
    }

    /* Small Desktops */
    @media (min-width: 1200px) and (max-width: 1399px) {
        .card-body {
            padding: 1.75rem;
        }
    }

    /* Tablets */
    @media (min-width: 768px) and (max-width: 1199px) {
        .card-body {
            padding: 1.5rem;
        }

        .form-floating {
            margin-bottom: 1.25rem;
        }
    }

    /* Mobile Devices */
    @media (max-width: 767px) {
        .container {
            padding: 1rem;
        }

        .card-body {
            padding: 1.25rem;
        }

        .form-floating {
            margin-bottom: 1rem;
        }

        .btn {
            padding: 0.5rem 1rem;
        }
    }

    /* Small Mobile Devices */
    @media (max-width: 576px) {
        .container {
            padding: 0.75rem;
        }

        .header-icon {
            width: 35px;
            height: 35px;
        }

        .header-icon i {
            font-size: 1.25rem;
        }
    }
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
                            <i class="bi bi-layers-fill"></i>
                        </div>
                        <h5 class="mb-0 ms-3 text-white">Tambah Jurusan</h5>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body p-4">
                    <form action="" method="post" class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-12">

                                <div class="form-floating form-group">
                                    <input type="text" class="form-control custom-input"
                                        id="namaJurusan" name="Jurusan" required>
                                    <label for="namaJurusan">Nama Jurusan</label>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="dashboard.php?page=jurusan"
                                class="btn btn-light btn-sm px-4 back-button">
                                <i class="bi bi-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" name="tombol_tambah"
                                class="btn btn-primary btn-sm px-4 submit-button">
                                <i class="bi bi-check-lg me-2"></i>Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>