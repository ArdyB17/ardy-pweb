<?php
include '../koneksi.php';

// Check if user is logged in
if (!isset($_COOKIE['level_user'])) {
    echo "<script>alert('Silakan login terlebih dahulu!');window.location.href='../login.php';</script>";
    exit;
}

// ====================================== //
//  Page Title Configuration              //
// ====================================== //


$title = 'Home';  // Default title

if (isset($_GET['page'])) {
    switch ($_GET['page']) {

        // siswa section
        case 'siswa':
            $title = 'Data Siswa';
            break;

        case 'tambah_siswa':
            $title = 'Tambah Siswa Baru';
            break;
        case 'ubah_siswa':
            $title = 'Edit Data Siswa';
            break;

        // jurusan section
        case 'jurusan':
            $title = 'Data Jurusan';
            break;
        case 'tambah_jurusan':
            $title = 'Tambah Jurusan Baru';
            break;
        case 'ubah_jurusan':
            $title = 'Edit Data Jurusan';
            break;

        // kategori kegiatan section
        case 'tambah_kegiatan':
            $title = 'Tambah Kategori Kegiatan';
            break;
        case 'kegiatan':
            $title = 'Data Kegiatan';
            break;
        case 'ubah_kegiatan':
            $title = 'Edit Data Kegiatan';
            break;
        case 'ubah_sub_kategori':
            $title = 'Edit Sub kategori';
            break;

        // sertifikat section
        case 'sertifikat':
            $title = 'Sertifikat';
            break;
        case 'cek_sertifikat':
            $title = 'Cek Sertifikat';
            break;
        case 'upload_sertifikat':
            $title = 'Upload Sertifikat';
            break;
        case 'cek_sertifikat_siswa':
            $title = 'Cek Sertifikat Siswa';
            break;
        case 'sertifikat_siswa':
            $title = 'Sertifikat Siswa';
            break;

        // operator section
        case 'operator':
            $title = 'Operator';
            break;
        case 'tambah_operator':
            $title = 'Tambah Operator Baru';
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="../bootstrap/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- another important link -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>SKKPD - <?php echo $title; ?></title>
</head>

<style>
    <?php include '../css/style_dashboard.css'; ?><?php include '../css/style_dashboard_siswa.css'; ?>
</style>

<body>

    <!-- /*=============================================================================
    * NAVIGATION BAR SECTION
    *============================================================================*/ -->

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">

            <?php if ($_COOKIE['level_user'] == 'operator'): ?>
                <!-- Brand - left position -->
                <a class="navbar-brand d-flex align-items-center gap-2" href="dashboard.php">
                    🏫 <span class="brand-text">SKKPd</span>
                </a>
            <?php else: ?>
                <!-- Brand - left position -->
                <a class="navbar-brand d-flex align-items-center gap-2" href="dashboard.php">
                    🏫 <span class="brand-text">SKKPd</span>
                </a>
            <?php endif; ?>
            <!-- Mobile Toggle -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
                <i class="bi bi-list fs-4"></i>
            </button>

            <!-- Navbar Content -->
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <!-- center and spacing ? -->
                <ul class="navbar-nav mx-auto">

                    <?php if ($_COOKIE['level_user'] == 'operator'): ?>
                        <!-- Menu items for operator -->
                        <li class="nav-item">
                            <a class="nav-link <?php echo (isset($_GET['page']) && $_GET['page'] === 'siswa') ? 'active' : ''; ?>"
                                href="dashboard.php?page=siswa">
                                <i class="bi bi-mortarboard-fill me-1"></i>Siswa
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?php echo (isset($_GET['page']) && $_GET['page'] === 'jurusan') ? 'active' : ''; ?>"
                                href="dashboard.php?page=jurusan">
                                <i class="bi bi-building-fill me-1"></i>Jurusan
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?php echo (isset($_GET['page']) && $_GET['page'] === 'kegiatan') ? 'active' : ''; ?>"
                                href="dashboard.php?page=kegiatan">
                                <i class="bi bi-clipboard2-data-fill me-1"></i>Kategori
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?php echo (isset($_GET['page']) && $_GET['page'] === 'sertifikat') ? 'active' : ''; ?>"
                                href="dashboard.php?page=sertifikat">
                                <i class="bi bi-file-earmark-check-fill me-1"></i>Sertifikat
                            </a>
                        </li>

                    <?php else: ?>
                        <!-- Nav for siswaaa  -->
                        <li class="nav-item">
                            <a class="nav-link <?php echo (isset($_GET['page']) && $_GET['page'] === 'sertifikat_siswa') ? 'active' : ''; ?>"
                                href="dashboard.php?page=sertifikat_siswa">
                                <i class="bi bi-file-earmark-check-fill me-1"></i>Sertifikat Saya
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?php echo (isset($_GET['page']) && $_GET['page'] === 'upload_sertifikat') ? 'active' : ''; ?>"
                                href="dashboard.php?page=upload_sertifikat">
                                <i class="bi bi-file-earmark-arrow-up-fill"></i>Upload Sertifikat
                            </a>
                        </li>


                    <?php endif; ?>
                </ul>

                <!-- Profile Dropdown - right position maybe -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle fs-5"></i>
                            <span class="d-none d-sm-inline"><?= $_COOKIE['Nama_Lengkap'] ?></span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm">
                            <?php if ($_COOKIE['level_user'] == 'operator'): ?>
                                <!-- Operator Profile Edit Option -->
                                <li class="operator">
                                    <a class="dropdown-item d-flex align-items-center" href="dashboard.php?page=operator">
                                        <i class="bi bi-gear me-2"></i>
                                        <span>Edit Profil</span>
                                    </a>
                                </li>
                            <?php else: ?>
                                <!-- Regular User Password Change Option -->
                                <li class="user">
                                    <a class="dropdown-item d-flex align-items-center" href="dashboard.php?page=ganti_password">
                                        <i class="bi bi-key me-2"></i>
                                        <span>Ganti Password</span>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <!-- Divider before logout -->
                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <!-- Logout Option -->
                            <li>
                                <a class="dropdown-item d-flex align-items-center text-danger" href="../logout.php">
                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    <span>Logout</span>
                                </a>
                            </li>
                        </ul>

                    </li>
                </ul>

            </div>
        </div>
    </nav>

    <?php
    // ====================================== //
    //  Page cases for dashboard              //
    // ====================================== //
    if (isset($_GET['page'])) {
        switch ($_GET['page']) {

            // the landing page
            // case 'dashboard':
            //     include 'dashboard.php';
            //     break;

            // siswa section
            case 'siswa':
                include 'siswa.php';
                break;
            case 'tambah_siswa':
                include '../tambah/tambah_siswa.php';
                break;
            case 'ubah_siswa':
                include '../ubah/ubah_siswa.php';
                break;


            // jurusan section
            case 'jurusan':
                include 'jurusan.php';
                break;
            case 'tambah_jurusan':
                include '../tambah/tambah_jurusan.php';
                break;
            case 'ubah_jurusan':
                include '../ubah/ubah_jurusan.php';
                break;

            // kategori kegiatan section
            case 'kegiatan':
                include 'kegiatan.php';
                break;

            case 'tambah_kegiatan':
                include '../tambah/tambah_kategori_kegiatan.php';
                break;

            case 'ubah_kegiatan':
                include '../ubah/ubah_kegiatan.php';
                break;

            case 'ubah_sub_kategori':
                include '../ubah/ubah_sub_kategori.php';
                break;

            // operator section
            case 'operator':
                include 'operator.php';
                break;

            case 'tambah_operator':
                include '../tambah/tambah_operator.php';
                break;

            case 'ganti_password':
                include '../ubah/ganti_password.php';
                break;

            // sertificate section
            case 'sertifikat':
                include 'sertifikat.php';
                break;

            case 'upload_sertifikat':
                include '../tambah/upload_sertifikat.php';
                break;

            case 'cek_sertifikat':
                include 'cek_sertifikat.php';
                break;

            case 'sertifikat_siswa':
                include 'sertifikat_siswa.php';
                break;

            case 'cek_sertifikat_siswa':
                include 'cek_sertifikat_siswa.php';
                break;

            default:
                // Show default content for unknown pages
                include 'dashboard.php';
                break;
        }
    } else {
        // Different landing pages based on user role
        if ($_COOKIE['level_user'] == 'operator') {
            // Operator Landing Page Content (existing content)
    ?>
            <?php
            /*=============================================================================
            * OPERATOR DASHBOARD VIEW
            * Contains welcome message, statistics, and system overview
            *============================================================================*/
            ?>
            <main>
                <!-- Enhanced Hero Section -->

                <!-- <section class="hero-section position-relative">
                    <div class="container">
                        <div class="row align-items-center min-vh-75">
                            <div class="col-lg-6 animate-fadeInUp">
                                <span class="badge bg-primary px-3 py-2 mb-3">SMK TI Bali Global Denpasar</span>
                                <h1 class="hero-title mb-4">
                                    Satuan Kredit Kegiatan <span class="text-primary">Peserta Didik</span>
                                </h1>
                                <p class="lead text-secondary mb-4">
                                    Sistem penilaian berbasis poin untuk meningkatkan soft skills, kesiapan kerja, dan jiwa wirausaha yang mendukung Kurikulum Merdeka dan Teaching Factory.
                                </p>
                                <div class="d-flex gap-3">
                                    <a href="#about-skkpd" class="btn btn-primary btn-lg px-4 py-2">
                                        Pelajari SKKPd <i class="bi bi-arrow-right ms-2"></i>
                                    </a>
                                    <a href="#categories" class="btn btn-outline-primary btn-lg px-4 py-2">
                                        Explore fitur
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-6 d-none d-lg-block animate-fadeInUp" style="animation-delay: 0.2s">
                                <div class="position-relative">
                                    <img src="../gambar/hero.jpg" alt="Education Illustration" class="img-fluid rounded-4 shadow-lg">
                                    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10 rounded-4"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section> -->

                <!-- Welcome Section -->
                <section class="welcome-section">
                    <!-- Personalized welcome message with operator name -->
                    <div class="container">
                        <div class="welcome-message">
                            👋 Selamat datang, <?php echo $_COOKIE['Nama_Lengkap']; ?>!
                        </div>
                        <div class="welcome-subtitle">
                            Berikut adalah ringkasan data SKKPd hari ini
                        </div>
                    </div>
                </section>


                <!-- /*=============================================================================
                * STATISTICS CARDS
                * Display key metrics: students, departments, activities, certificates
                *============================================================================*/ -->

                <!-- Statistics Section -->
                <div class="container mb-1">

                    <div class="stats-grid">
                        <!-- Murid Stats -->
                        <div class="stats-card shadow-sm">
                            <div class="card-body d-flex align-items-center">
                                <div class="stats-icon-box stats-bg-primary me-3">
                                    <i class="bi bi-people-fill"></i>
                                </div>
                                <div class="stats-content">
                                    <?php
                                    // Mengambil total jumlah siswa dari database
                                    // mysqli_query() - Menjalankan query SQL untuk menghitung total siswa
                                    // mysqli_fetch_array() - Mengambil hasil query dalam bentuk array
                                    // [0] - Mengambil nilai pertama dari array hasil (total count)
                                    $total_siswa = mysqli_fetch_array(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM siswa"))[0];
                                    ?>
                                    <h3><?php echo $total_siswa; ?></h3>
                                    <p>Total Siswa</p>
                                </div>
                            </div>
                        </div>

                        <!-- Jurusan Stats -->
                        <div class="stats-card shadow-sm">
                            <div class="card-body d-flex align-items-center">
                                <div class="stats-icon-box stats-bg-success me-3">
                                    <i class="bi bi-diagram-3-fill"></i>
                                </div>
                                <div class="stats-content">
                                    <?php
                                    // Mengambil total jumlah jurusan dari database
                                    // COUNT(*) - Menghitung semua record dalam tabel jurusan
                                    // mysqli_fetch_array()[0] - Mengambil nilai count dari hasil query
                                    $total_jurusan = mysqli_fetch_array(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM jurusan"))[0];
                                    ?>
                                    <h3><?php echo $total_jurusan; ?></h3>
                                    <p>Total Jurusan</p>
                                </div>
                            </div>
                        </div>

                        <!-- Kegiatan Stats -->
                        <div class="stats-card shadow-sm">
                            <div class="card-body d-flex align-items-center">
                                <div class="stats-icon-box stats-bg-warning me-3">
                                    <i class="bi bi-calendar-event-fill"></i>
                                </div>
                                <div class="stats-content">
                                    <?php
                                    // Mengambil total jumlah kegiatan dari database
                                    // Query menghitung seluruh record di tabel kegiatan
                                    // mysqli_fetch_array mengkonversi hasil query menjadi array
                                    $total_kegiatan = mysqli_fetch_array(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM kegiatan"))[0];
                                    ?>
                                    <h3><?php echo $total_kegiatan; ?></h3>
                                    <p>Total Kegiatan</p>
                                </div>
                            </div>
                        </div>

                        <!-- Certificates Stats -->
                        <div class="stats-card shadow-sm">
                            <div class="card-body d-flex align-items-center">
                                <div class="stats-icon-box stats-bg-info me-3">
                                    <i class="bi bi-award-fill"></i>
                                </div>
                                <div class="stats-content">
                                    <?php
                                    // Mengambil total jumlah sertifikat dari database
                                    // COUNT(*) menghitung semua baris dalam tabel sertifikat
                                    // mysqli_query menjalankan query SQL ke database
                                    // mysqli_fetch_array mengambil hasil dalam bentuk array
                                    $total_sertifikat = mysqli_fetch_array(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM sertifikat"))[0];
                                    ?>
                                    <h3><?php echo $total_sertifikat; ?></h3>
                                    <p>Total Sertifikat</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="charts-grid">
                        <div class="chart-card">
                            <div class="chart-header">
                                <h5>Statistik Kategori Kegiatan</h5>
                            </div>
                            <div class="chart-body">
                                <canvas id="activityStats"></canvas>
                            </div>
                        </div>

                        <div class="chart-card">
                            <div class="chart-header">
                                <h5>Status Sertifikat</h5>
                            </div>
                            <div class="chart-body">
                                <canvas id="certificateStatus"></canvas>
                            </div>
                        </div>


                    </div>
                </div>

                <!-- /*=============================================================================
                * CHARTS INITIALIZATION
                * JavaScript for initializing and configuring Chart.js
                *============================================================================*/ -->
                <script>
                    // Certificate Stats Chart
                    // const certStats = document.getElementById('certificateStats');
                    // new Chart(certStats, {
                    //     type: 'bar',
                    //     data: {
                    //         labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    //         datasets: [{
                    //             label: 'Sertifikat Diupload',
                    //             data: [12, 19, 3, 5, 2, 3],
                    //             backgroundColor: '#4e73df'
                    //         }]
                    //     },
                    //     options: {
                    //         responsive: true,
                    //         maintainAspectRatio: false
                    //     }
                    // });

                    // Certificate Status Chart
                    const certStatus = document.getElementById('certificateStatus');
                    new Chart(certStatus, {
                        type: 'doughnut',
                        data: {
                            labels: ['Valid', 'Pending', 'Tidak Valid'],
                            datasets: [{
                                data: [
                                    <?php echo mysqli_fetch_array(mysqli_query($koneksi, "SELECT COUNT(*) FROM sertifikat WHERE Status='Valid'"))[0]; ?>,
                                    <?php echo mysqli_fetch_array(mysqli_query($koneksi, "SELECT COUNT(*) FROM sertifikat WHERE Status='Menunggu Validasi'"))[0]; ?>,
                                    <?php echo mysqli_fetch_array(mysqli_query($koneksi, "SELECT COUNT(*) FROM sertifikat WHERE Status='Tidak Valid'"))[0]; ?>
                                ],
                                backgroundColor: ['#10b981', '#f59e0b', '#ef4444']
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false
                        }
                    });

                    // Activity Statistics Chart
                    const activityStats = document.getElementById('activityStats');

                    // Fetch activity data from database
                    <?php
                    $activity_query = mysqli_query($koneksi, "
                        SELECT k.Sub_Kategori, COUNT(kg.Id_Kegiatan) as total_kegiatan 
                        FROM kategori k
                        LEFT JOIN kegiatan kg ON k.Id_Kategori = kg.Id_Kategori
                        GROUP BY k.Sub_Kategori
                        ORDER BY k.Sub_Kategori
                    ");

                    $labels = [];
                    $data = [];

                    while ($row = mysqli_fetch_assoc($activity_query)) {
                        $labels[] = $row['Sub_Kategori'];
                        $data[] = $row['total_kegiatan'];
                    }
                    ?>

                    new Chart(activityStats, {
                        type: 'bar',
                        data: {
                            labels: <?php echo json_encode($labels); ?>,
                            datasets: [{
                                label: 'Jumlah Kegiatan',
                                data: <?php echo json_encode($data); ?>,
                                backgroundColor: [
                                    'rgba(59, 130, 246, 0.8)', // Blue
                                    'rgba(16, 185, 129, 0.8)', // Green
                                    'rgba(245, 158, 11, 0.8)', // Orange
                                    'rgba(99, 102, 241, 0.8)' // Indigo
                                ],
                                borderColor: [
                                    'rgb(59, 130, 246)',
                                    'rgb(16, 185, 129)',
                                    'rgb(245, 158, 11)',
                                    'rgb(99, 102, 241)'
                                ],
                                borderWidth: 1,
                                borderRadius: 8,
                                maxBarThickness: 50
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    padding: 12,
                                    titleFont: {
                                        size: 14,
                                        weight: 'bold'
                                    },
                                    bodyFont: {
                                        size: 13
                                    },
                                    bodySpacing: 4,
                                    caretSize: 6,
                                    cornerRadius: 8
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        display: true,
                                        drawBorder: false,
                                        color: 'rgba(0, 0, 0, 0.05)'
                                    },
                                    ticks: {
                                        font: {
                                            size: 12
                                        },
                                        color: '#64748b'
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        font: {
                                            size: 12
                                        },
                                        color: '#64748b'
                                    }
                                }
                            },
                            animation: {
                                duration: 2000,
                                easing: 'easeOutQuart'
                            }
                        }
                    });

                    // Lazy Loading Implementation
                    document.addEventListener('DOMContentLoaded', function() {
                        const lazyElements = document.querySelectorAll('.lazy-load');

                        const lazyLoadObserver = new IntersectionObserver((entries) => {
                            entries.forEach(entry => {
                                if (entry.isIntersecting) {
                                    entry.target.classList.add('loaded');
                                }
                            });
                        });

                        lazyElements.forEach(element => {
                            lazyLoadObserver.observe(element);
                        });
                    });

                    // Enhanced Charts Configuration
                    const categoryStats = {
                        type: 'doughnut',
                        data: {
                            labels: ['Akademik', 'Organisasi', 'Prestasi', 'Keterampilan'],
                            datasets: [{
                                data: [30, 25, 20, 25],
                                backgroundColor: [
                                    '#3b82f6',
                                    '#10b981',
                                    '#f59e0b',
                                    '#6366f1'
                                ]
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }
                    };

                    // Initialize charts when DOM is loaded
                    document.addEventListener('DOMContentLoaded', function() {
                        const ctx = document.getElementById('categoryStats').getContext('2d');
                        new Chart(ctx, categoryStats);
                    });
                </script>

                <!-- /*=============================================================================
                * SYSTEM OVERVIEW SECTION
                * Information about SKKPD features and functionality
                *============================================================================*/ -->

                <section id="about-skkpd" class="overview-section py-5">

                    <div class="container">
                        <!-- overview text -->
                        <div class="row justify-content-center mb-1">
                            <div class="col-lg-8 text-center">
                                <div class="title-wrapper">

                                    <h2 class="section-title display-4 mb-3 fw-bold ">
                                        Memahami <span class="highlight">SKKPd</span>
                                    </h2>
                                    <p class="lead text-secondary mb-4">
                                        Sistem penilaian modern berbasis poin untuk mengembangkan
                                        <span class="text-primary fw-semibold">soft skills</span> dan
                                        <span class="text-primary fw-semibold">kompetensi</span> siswa
                                    </p>
                                    <div class="title-decoration mx-auto mb-4"></div>
                                </div>
                            </div>
                        </div>


                        <div class="row g-4">
                            <!-- Card 1 -->
                            <div class="col-md-6 col-lg-3">
                                <div class="feature-card">
                                    <div class="card-content">
                                        <div class="feature-emoji">🏆</div>
                                        <h5>Poin Prestasi</h5>
                                        <p>Sistem kredit poin untuk mengukur dan menghargai prestasi akademik dan non-akademik siswa.</p>
                                        <div class="card-overlay"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 2 -->
                            <div class="col-md-6 col-lg-3">
                                <div class="feature-card">
                                    <div class="card-content">
                                        <div class="feature-emoji">⚡</div>
                                        <h5>Pengembangan Skill</h5>
                                        <p>Mendorong pengembangan soft skills, leadership, dan kesiapan kerja industri.</p>
                                        <div class="card-overlay"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 3 -->
                            <div class="col-md-6 col-lg-3">
                                <div class="feature-card">
                                    <div class="card-content">
                                        <div class="feature-emoji">📈</div>
                                        <h5>Tracking Progress</h5>
                                        <p>Pemantauan kemajuan siswa secara real-time melalui sistem manajemen poin digital.</p>
                                        <div class="card-overlay"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 4 -->
                            <div class="col-md-6 col-lg-3">
                                <div class="feature-card">
                                    <div class="card-content">
                                        <div class="feature-emoji">🎯</div>
                                        <h5>Sertifikasi</h5>
                                        <p>Validasi pencapaian melalui sertifikat digital yang diakui industri.</p>
                                        <div class="card-overlay"></div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>

                </section>

            </main>

        <?php
        } else {
            // /*=============================================================================
            // * STUDENT DASHBOARD VIEW
            // * Personal information, credit points, and certificate status
            // *============================================================================*/
        ?>
            <main class="dashboard-main py-4">
                <div class="container">
                    <!-- Profile Section -->
                    <div class="row justify-content-center mb-4">
                        <div class="col-12 col-lg-8">
                            <div class="card h-100 border-0 shadow-sm hover-card">

                                <div class="card-header custom-header border-0 position-relative overflow-hidden">
                                    <div class="header-background"></div>
                                    <div class="d-flex align-items-center position-relative z-1 p-4">
                                        <!-- Avatar Section -->
                                        <div class="header-avatar-wrapper">
                                            <div class="header-avatar">
                                                <div class="avatar-inner">
                                                    <?php
                                                    $initials = explode(' ', $_COOKIE['Nama_Lengkap']);
                                                    $display = '';
                                                    if (count($initials) >= 2) {
                                                        $display = strtoupper(substr($initials[0], 0, 1) . substr($initials[1], 0, 1));
                                                    } else {
                                                        $display = strtoupper(substr($_COOKIE['Nama_Lengkap'], 0, 2));
                                                    }
                                                    echo $display;
                                                    ?>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- User Info Section -->
                                        <div class="user-info ms-4">
                                            <h4 class="mb-1 fw-bold text-white">
                                                <?php echo $_COOKIE['Nama_Lengkap']; ?>
                                            </h4>
                                            <div class="d-flex align-items-center gap-3">
                                                <span class="user-badge">
                                                    <i class="bi bi-person-badge me-2"></i>
                                                    <?php echo $_COOKIE['NIS']; ?>
                                                </span>
                                                <!-- <span class="user-role">
                                                    <i class="bi bi-mortarboard-fill me-2"></i>
                                                    Siswa
                                                </span> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                // Fetch student data with credit points
                                $nis = $_COOKIE['NIS'];
                                $query = mysqli_query($koneksi, "SELECT s.*, j.Jurusan,
                                    (SELECT COALESCE(SUM(k.Angka_Kredit), 0)
                                     FROM sertifikat srt 
                                     INNER JOIN kegiatan k ON srt.Id_Kegiatan = k.Id_Kegiatan 
                                     WHERE srt.NIS = s.NIS AND srt.Status = 'Valid') as total_kredit
                                    FROM siswa s 
                                    INNER JOIN jurusan j ON s.Id_Jurusan = j.Id_Jurusan 
                                    WHERE s.NIS = '$nis'");
                                $siswa = mysqli_fetch_assoc($query);
                                ?>

                                <div class="info-section">
                                    <div class="row g-4">
                                        <!-- Left Column -->
                                        <div class="col-md-6">
                                            <div class="info-group">
                                                <div class="info-label"><i class="bi bi-person-badge me-2"></i>No. Absen</div>
                                                <div class="info-value"><?php echo $siswa['No_Absen']; ?></div>
                                            </div>

                                            <div class="info-group">
                                                <div class="info-label"><i class="bi bi-envelope me-2"></i>Email</div>
                                                <div class="info-value"><?php echo $siswa['Email']; ?></div>
                                            </div>

                                            <div class="info-group">
                                                <div class="info-label"><i class="bi bi-telephone me-2"></i>Nomor Telepon</div>
                                                <a href="https://wa.me/<?php echo preg_replace("/[^0-9]/", "", $siswa['No_Telp']); ?>"
                                                    class="info-value contact-link" target="_blank">
                                                    <i class="bi bi-whatsapp me-2"></i><?php echo $siswa['No_Telp']; ?>
                                                </a>
                                            </div>
                                        </div>

                                        <!-- Right Column -->
                                        <div class="col-md-6">
                                            <div class="info-group">
                                                <div class="info-label"><i class="bi bi-mortarboard me-2"></i>Jurusan & Kelas</div>
                                                <div class="badge bg-primary px-3 py-2">
                                                    <?php echo $siswa['Jurusan']; ?> <?php echo $siswa['Kelas']; ?>
                                                </div>
                                            </div>

                                            <div class="info-group">
                                                <div class="info-label"><i class="bi bi-calendar3 me-2"></i>Angkatan</div>
                                                <div class="info-value"><?php echo $siswa['Angkatan']; ?></div>
                                            </div>

                                            <div class="info-group credit-section">
                                                <div class="info-label"><i class="bi bi-star me-2"></i>Total Kredit Point</div>
                                                <div class="credit-display">
                                                    <span class="credit-number"><?php echo $siswa['total_kredit']; ?></span>
                                                    <span class="credit-total"> points</span>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="row justify-content-center g-4 mb-4">
                        <?php
                        // Fetch certificate statistics

                        // Get total certificates uploaded by student
                        $total_certs = mysqli_num_rows(mysqli_query(
                            $koneksi,
                            "SELECT * FROM sertifikat WHERE NIS='$nis'"
                        ));

                        // Get valid certificates count
                        $valid_certs = mysqli_num_rows(mysqli_query(
                            $koneksi,
                            "SELECT * FROM sertifikat WHERE NIS='$nis' AND Status='Valid'"
                        ));

                        // Get pending certificates count 
                        $pending_certs = mysqli_num_rows(mysqli_query(
                            $koneksi,
                            "SELECT * FROM sertifikat WHERE NIS='$nis' AND Status='Menunggu Validasi'"
                        ));

                        // Get invalid certificates count
                        $invalid_certs = mysqli_num_rows(mysqli_query(
                            $koneksi,
                            "SELECT * FROM sertifikat WHERE NIS='$nis' AND Status='Tidak Valid'"
                        ));

                        // Array of statistics cards configuration
                        $stat_cards = [
                            // Total certificates card
                            ['title' => 'Total Sertifikat', 'count' => $total_certs, 'icon' => 'file-earmark-text-fill', 'color' => 'primary'],
                            // Valid certificates card
                            ['title' => 'Sertifikat Valid', 'count' => $valid_certs, 'icon' => 'check-circle-fill', 'color' => 'success'],
                            // Pending certificates card
                            ['title' => 'Menunggu Validasi', 'count' => $pending_certs, 'icon' => 'clock-fill', 'color' => 'warning'],
                            // Invalid certificates card
                            ['title' => 'Tidak Valid', 'count' => $invalid_certs, 'icon' => 'x-circle-fill', 'color' => 'danger']
                        ];

                        // Loop through and display all statistics cards
                        foreach ($stat_cards as $card) : ?>
                            <div class="col-12 col-md-6 col-lg-3">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="stats-icon bg-<?php echo $card['color']; ?>-subtle rounded-circle p-3">
                                                <i class="bi bi-<?php echo $card['icon']; ?> text-<?php echo $card['color']; ?> fs-4"></i>
                                            </div>
                                            <div class="ms-3">
                                                <h3 class="fw-bold mb-0"><?php echo $card['count']; ?></h3>
                                                <p class="text-muted mb-0"><?php echo $card['title']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Action Button -->
                    <div class="text-center">
                        <a href="dashboard.php?page=upload_sertifikat" class="btn btn-primary btn-lg">
                            <i class="bi bi-file-earmark-arrow-up-fill"></i> Upload Sertifikat Baru
                        </a>
                    </div>
                </div>
            </main>

    <?php
        }
    }
    ?>

    <!-- /*=============================================================================
    * FOOTER SECTION
    * Copyright information and developer credits
    *============================================================================*/ -->

    <!-- Modern Footer -->
    <footer class="footer mt-auto py-3">
        <div class="container">
            <div class="row align-items-center justify-content-center text-center">
                <div class="col-12">
                    <div class="footer-divider mb-2"></div>
                    <p class="mb-0" style="font-size: 0.75rem;">
                        <span class="copyright-text">
                            &copy; <?php echo date('Y'); ?> Project SKKPd
                        </span>
                        <span class="mx-1">•</span>
                        <span class="credit-text">
                            Dikembangkan oleh
                            <a href="https://github.com/ArdyB17/skkpd_ardy" target="_blank" rel="noopener noreferrer" class="developer-link">Ardy Berata</a>
                            dengan <span class="heart">💓</span>
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="../bootstrap/bootstrap.js"></script>
</body>

</html>

<?php
// Close database connection
mysqli_close($koneksi);
?>