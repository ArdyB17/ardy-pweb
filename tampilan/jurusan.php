<?php
/*=============================================================================
* JURUSAN (DEPARTMENT) MANAGEMENT SYSTEM
*============================================================================*/

// for delete jurusan
if (isset($_GET['Id_Jurusan'])) {
    $Id_Jurusan = $_GET['Id_Jurusan'];
    $delete_jurusan = mysqli_query($koneksi, "DELETE FROM jurusan WHERE Id_Jurusan = '$Id_Jurusan'");

    if ($delete_jurusan) {
        echo "<script>alert('Data berhasil dihapus!');window.location.href='dashboard.php?page=jurusan';</script>";
    } else {
        echo "<script>alert('Data gagal dihapus!');window.location.href='dashboard.php?page=jurusan';</script>";
    }
}
?>


<!-- Main Container  -->
<div class="container-fluid py-4 px-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">

            <!-- Header Card -->
            <div class="card border-0 shadow-lg rounded-4 mb-4">
                <div class="card-header bg-dark bg-gradient text-white p-4 rounded-top-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold fs-4 "><i class="bi bi-tags-fill fs-4"></i> Data Jurusan</h5>
                        <a href="dashboard.php?page=tambah_jurusan"
                            class="btn btn-light btn-sm px-3 py-2 shadow-sm fw-semibold">
                            <i class="bi bi-plus-lg me-2"></i>Tambah Jurusan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr class="bg-light border-bottom">
                                    <th class="px-4 py-3 text-center text-uppercase" width="15%">No</th>
                                    <th class="px-4 py-3 text-uppercase" width="65%">Nama Jurusan</th>
                                    <th class="px-4 py-3 text-center text-uppercase" width="20%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = mysqli_query($koneksi, "SELECT * FROM jurusan ORDER BY CAST(SUBSTRING(Id_Jurusan, 2) AS UNSIGNED)");
                                $no = 1;
                                while ($data = mysqli_fetch_assoc($query)) {
                                ?>
                                    <tr>
                                        <td class="px-4 py-3 text-center">
                                            <span class="badge bg-dark-subtle text-dark rounded-pill px-3 py-2 fs-6">
                                                <?php echo $no++; ?>
                                            </span>
                                        </td>

                                        <td class="px-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="department-icon me-3">
                                                    <i class="bi bi-tags-fill fs-4 text-primary"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold fs-5"><?php echo strtoupper($data['Jurusan']); ?></h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="dashboard.php?page=ubah_jurusan&Id_Jurusan=<?php echo $data['Id_Jurusan']; ?>"
                                                    class="btn btn-sm px-3 btn-edit fw-semibold">
                                                    <i class="bi bi-pencil-square me-1"></i>
                                                    Edit
                                                </a>

                                                <?php if (mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa WHERE Id_Jurusan = '{$data['Id_Jurusan']}'")) == 0) { ?>
                                                    <button type="button"
                                                        class="btn btn-sm px-3 btn-delete fw-semibold"
                                                        onclick="confirmDelete('<?php echo $data['Id_Jurusan']; ?>', '<?php echo $data['Jurusan']; ?>')">
                                                        <i class="bi bi-trash me-1"></i>
                                                        Hapus
                                                    </button>
                                                <?php } ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <style>
                /* Enhanced Typography & Table Styles */

                .table {
                    border-spacing: 0 8px;
                    border-collapse: separate;
                    margin: 0;
                }

                .table tbody tr {
                    background: #fff;
                    transition: all 0.3s ease;
                    border-radius: 8px;
                }

                .table tbody tr:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                }

                .table th {
                    font-size: 0.95rem;
                    font-weight: 700;
                    color: #334155;
                    text-transform: uppercase;
                    letter-spacing: 0.6px;
                }

                .table td {
                    font-size: 1rem;
                    font-weight: 500;
                    color: #1e293b;
                }

                /* Enhanced Button Styles */
                .btn-edit,
                .btn-delete {
                    font-size: 0.85rem;
                    text-transform: uppercase;
                    letter-spacing: 0.6px;
                    font-weight: 600;
                    transition: all 0.3s ease;
                    border-radius: 10px;
                    padding: 0.6rem 1.2rem;
                    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.12);
                }

                .btn-edit {
                    background-color: #fbbf24;
                    color: #1e293b;
                    border: 2px solid #fbbf24;
                }

                .btn-edit:hover {
                    background-color: #f59e0b;
                    border-color: #f59e0b;
                    color: #1e293b;
                    transform: translateY(-2px);
                    box-shadow: 0 5px 8px rgba(0, 0, 0, 0.18);
                }

                .btn-delete {
                    background-color: #ef4444;
                    color: white;
                    border: 2px solid #ef4444;
                }

                .btn-delete:hover {
                    background-color: #dc2626;
                    border-color: #dc2626;
                    color: white;
                    transform: translateY(-2px);
                    box-shadow: 0 5px 8px rgba(0, 0, 0, 0.18);
                }

                /* Enhanced Department Icon */
                .department-icon {
                    width: 50px;
                    height: 50px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    background-color: rgba(14, 165, 233, 0.18);
                    border-radius: 14px;
                    transition: all 0.3s ease;
                }

                .department-icon:hover {
                    transform: scale(1.08);
                    background-color: rgba(14, 165, 233, 0.25);
                }

                /* Enhanced Badge Styling */
                .badge {
                    font-weight: 600;
                    font-size: 0.9rem;
                    padding: 0.6rem 1.2rem;
                    border-radius: 25px;
                    background-color: #f1f5f9;
                    color: #334155;
                    letter-spacing: 0.6px;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);
                }
            </style>

            <script>
                function confirmDelete(id, name) {
                    if (confirm(`Apakah Anda yakin ingin menghapus jurusan "${name}"?`)) {
                        window.location.href = `dashboard.php?page=jurusan&Id_Jurusan=${id}`;
                    }
                }
            </script>

        </div>
    </div>
</div>