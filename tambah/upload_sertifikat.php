<?php

/**
 * Certificate Upload Management System
 * 
 * This file handles the certificate upload process for students including:
 * - User authentication check
 * - File upload validation and processing
 * - Database integration
 * - Dynamic form interactions
 */

// Authentication Check
// Verify if student is logged in by checking NIS cookie
if (!isset($_COOKIE['NIS'])) {
    echo "<script>alert('Access denied!');window.location.href='../login.php';</script>";
    exit;
}

// Form Submission Handler
if (isset($_POST['upload_sertifikat'])) {
    // Get user and activity data
    $nis = $_COOKIE['NIS'];
    $id_kegiatan = mysqli_real_escape_string($koneksi, $_POST['kegiatan']);
    $tanggal_upload = date('Y-m-d H:i:s');

    // File Upload Configuration
    $file = $_FILES['sertifikat_file'];
    $file_name = $file['name'];
    $file_size = $file['size'];
    $file_tmp = $file['tmp_name'];
    $file_type = $file['type'];

    // File Validation Parameters
    $max_size = 2 * 1024 * 1024; // Maximum file size: 2MB
    $allowed_type = 'application/pdf'; // Only allow PDF files

    // File Type and Size Validation
    if ($file_type != $allowed_type) {
        echo "<script>alert('Hanya file PDF yang diperbolehkan!');</script>";
    } elseif ($file_size > $max_size) {
        echo "<script>alert('Ukuran file maksimal 2MB!');</script>";
    } else {
        // Generate unique filename to prevent duplicates
        $new_filename = $nis . '_' . time() . '.pdf';
        $upload_path = '../sertifikat/' . $new_filename;

        // Process File Upload
        if (move_uploaded_file($file_tmp, $upload_path)) {
            // Database insertion query
            $query = "INSERT INTO sertifikat (NIS, Id_Kegiatan, Sertifikat, Status, Tanggal_Upload) 
                     VALUES ('$nis', '$id_kegiatan', '$new_filename', 'Menunggu Validasi', '$tanggal_upload')";

            // Execute query and handle response
            if (mysqli_query($koneksi, $query)) {
                echo "<script>alert('Sertifikat berhasil diupload!');window.location.href='dashboard.php';</script>";
            } else {
                echo "<script>alert('Gagal menyimpan data sertifikat!');</script>";
                unlink($upload_path); // Clean up: remove file if database insert fails
            }
        } else {
            echo "<script>alert('Gagal mengupload file!');</script>";
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
            <div class="card border-0 shadow-lg form-card">
                <!-- Card Header -->
                <div class="card-header bg-gradient border-0 p-3">
                    <div class="d-flex align-items-center">
                        <div class="header-icon">
                            <i class="bi bi-file-earmark-arrow-up"></i>
                        </div>
                        <h5 class="mb-0 ms-3 text-white">Upload Sertifikat</h5>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body p-4">
                    <form action="" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <div class="row justify-content-center g-3">
                            <!-- Kategori Selection -->
                            <div class="col-md-8">
                                <div class="form-floating">
                                    <select name="kategori" id="kategoriSelect" class="form-select" required>
                                        <option value="">Pilih Kategori</option>
                                        <?php
                                        $kategori_query = mysqli_query($koneksi, "SELECT DISTINCT Kategori FROM kategori ORDER BY Kategori");
                                        while ($kategori = mysqli_fetch_assoc($kategori_query)) {
                                            echo "<option value='{$kategori['Kategori']}'>{$kategori['Kategori']}</option>";
                                        }
                                        ?>
                                    </select>
                                    <label>Kategori</label>
                                </div>
                            </div>

                            <!-- Sub Kategori Selection (Dynamic) -->
                            <div class="col-md-8">
                                <div class="form-floating">
                                    <select name="sub_kategori" id="subKategoriSelect" class="form-select" required disabled>
                                        <option value="">Pilih Sub Kategori</option>
                                    </select>
                                    <label>Sub Kategori</label>
                                </div>
                            </div>

                            <!-- Kegiatan Selection (Dynamic) -->
                            <div class="col-md-8">
                                <div class="form-floating">
                                    <select name="kegiatan" id="kegiatanSelect" class="form-select" required disabled>
                                        <option value="">Pilih Jenis Kegiatan</option>
                                    </select>
                                    <label>Jenis Kegiatan</label>
                                </div>
                            </div>

                            <!-- File Upload -->
                            <div class="col-md-8">
                                <div class="upload-box p-3 border rounded-3">
                                    <label class="form-label mb-2">Upload Sertifikat</label>
                                    <input type="file"
                                        name="sertifikat_file"
                                        class="form-control"
                                        accept="application/pdf"
                                        required>
                                    <div class="form-text mt-2">
                                        <i class="bi bi-info-circle me-1"></i>
                                        File harus berformat PDF dengan ukuran maksimal 2MB
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-md-8">
                                <div class="d-grid gap-2">
                                    <button type="submit"
                                        name="upload_sertifikat"
                                        class="btn btn-primary">
                                        <i class="bi bi-cloud-arrow-up me-2"></i>
                                        Upload Sertifikat
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    /**
     * Dynamic Form Handling
     * 
     * This script manages:
     * 1. Dynamic dropdown population
     * 2. Form validation
     * 3. AJAX requests for category/subcategory data
     * 4. File upload validation
     */

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize form elements
        const kategoriSelect = document.getElementById('kategoriSelect');
        const subKategoriSelect = document.getElementById('subKategoriSelect');
        const kegiatanSelect = document.getElementById('kegiatanSelect');

        /**
         * Generic data fetcher with error handling
         * @param {string} url - The endpoint URL
         * @returns {Promise} - JSON response or empty array on error
         */
        async function fetchData(url) {
            try {
                const response = await fetch(url);
                if (!response.ok) throw new Error('Network response was not ok');
                return await response.json();
            } catch (error) {
                console.error('Error:', error);
                return [];
            }
        }

        /**
         * Category Change Event Handler
         * Populates sub-category dropdown based on selected category
         */
        kategoriSelect.addEventListener('change', async function() {
            const kategori = this.value;
            subKategoriSelect.disabled = true;
            kegiatanSelect.disabled = true;

            if (kategori) {
                subKategoriSelect.innerHTML = '<option value="">Loading...</option>';

                const data = await fetchData(`../get_sub_kategori.php?kategori=${encodeURIComponent(kategori)}`);

                subKategoriSelect.innerHTML = '<option value="">Pilih Sub Kategori</option>';
                data.forEach(item => {
                    subKategoriSelect.innerHTML += `
                <option value="${item.id}">${item.name}</option>
            `;
                });

                subKategoriSelect.disabled = false;
            } else {
                subKategoriSelect.innerHTML = '<option value="">Pilih Sub Kategori</option>';
                kegiatanSelect.innerHTML = '<option value="">Pilih Jenis Kegiatan</option>';
            }
        });

        /**
         * Sub-Category Change Event Handler
         * Populates activity dropdown based on selected sub-category
         */
        subKategoriSelect.addEventListener('change', async function() {
            const subKategori = this.value;
            kegiatanSelect.disabled = true;

            if (subKategori) {
                kegiatanSelect.innerHTML = '<option value="">Loading...</option>';

                const data = await fetchData(`../get_kegiatan.php?sub_kategori=${encodeURIComponent(subKategori)}`);

                kegiatanSelect.innerHTML = '<option value="">Pilih Jenis Kegiatan</option>';
                data.forEach(item => {
                    kegiatanSelect.innerHTML += `
                <option value="${item.id}">${item.name}</option>
            `;
                });

                kegiatanSelect.disabled = false;
            } else {
                kegiatanSelect.innerHTML = '<option value="">Pilih Jenis Kegiatan</option>';
            }
        });

        /**
         * Form Submission Validator
         * Validates file size before submission
         */
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const file = document.querySelector('input[type="file"]');
            if (file.files.length > 0) {
                const fileSize = file.files[0].size / 1024 / 1024; // in MB
                if (fileSize > 2) {
                    e.preventDefault();
                    alert('Ukuran file tidak boleh lebih dari 2MB');
                }
            }
        });
    });
</script>

<style>
    /**
 * Form Styling System
 * 
 * Defines styles for:
 * - Form inputs and dropdowns
 * - Upload box styling
 * - Loading states
 * - Disabled states
 */
    .form-floating {
        margin-bottom: 1rem;
    }

    .form-select:disabled {
        background-color: #e9ecef;
        cursor: not-allowed;
    }

    .form-select option {
        padding: 8px;
    }

    .upload-box {
        background-color: #f8f9fa;
        border: 2px dashed #dee2e6;
        transition: all 0.3s ease;
    }

    .upload-box:hover {
        border-color: var(--primary-color);
        background-color: #fff;
    }

    /* Loading State Styling */
    .form-select option[value=""] {
        color: #6c757d;
        font-style: italic;
    }
</style>