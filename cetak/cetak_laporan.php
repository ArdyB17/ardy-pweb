<?php
// Prevent any output before PDF generation
ob_start();

include "../fpdf/fpdf.php";
include "../koneksi.php";

// Define defaults for missing parameters
$angkatan = isset($_GET['angkatan']) ? $_GET['angkatan'] : 'semua';
$status = isset($_GET['status']) ? $_GET['status'] : 'semua';

// Buat objek PDF
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();

// ===== HEADER (KOP SURAT) ===== //
$pdf->Image('../gambar/logoti.png', 10, 6, 20); // Logo
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(190, 7, 'SMK TI Bali Global Denpasar', 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(190, 7, 'Jl. Tukad Citarum No. 44 Denpasar. Bali', 0, 1, 'C');
$pdf->Cell(190, 7, 'website: https://smkti-baliglobal.sch.id | email: info@smkti-baliglobal.sch.id', 0, 1, 'C');

// garis bawah
$pdf->Ln(5);
$pdf->Cell(190, 0, '', 'T', 1, 'C');
$pdf->Ln(10);

// ===== Fungsi Tampilkan Sertifikat ===== //
function tampilSertifikat($pdf, $koneksi, $angkatan, $status = NULL)
{
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(95, 7, 'Angkatan : ' . strtoupper($angkatan), 0, 1, 'L');
    $pdf->SetFont('Arial', '', 10);

    // Header Tabel
    $pdf->Cell(8, 7, 'No', 1);
    $pdf->Cell(13, 7, 'NIS', 1);
    $pdf->Cell(55, 7, 'Nama Siswa', 1);
    $pdf->Cell(54, 7, 'Jenis Kegiatan', 1);
    $pdf->Cell(28, 7, 'No Telp', 1);
    $pdf->Cell(13, 7, 'Kelas', 1);
    $pdf->Cell(19, 7, 'Status', 1, 1);

    $where = "WHERE Angkatan = '$angkatan'";
    if (!empty($status) && $status != 'semua') {
        $where .= " AND Status = '$status'";
    }

    $result = mysqli_query(($koneksi), "SELECT NIS, Nama_Siswa, Jenis_Kegiatan, Kelas, No_Telp, Angkatan, Jurusan, Status FROM sertifikat 
    INNER JOIN siswa USING(NIS) 
    INNER JOIN kegiatan USING(Id_Kegiatan) 
    INNER JOIN jurusan USING(Id_Jurusan) $where");

    $no = 1;
    $data = [];

    // mengelompokkan data berdasarkan NIS
    while ($row = mysqli_fetch_assoc($result)) {
        $data[$row['NIS']]['Nama_Siswa'] = $row['Nama_Siswa'];
        $data[$row['NIS']]['Data'][] = [
            'Jenis_Kegiatan' => $row['Jenis_Kegiatan'],
            'No_Telp' => $row['No_Telp'],
            'Jurusan' => $row['Jurusan'],
            'Kelas' => $row['Kelas'],
            'Status' => $row['Status'],
        ];
    }

    foreach ($data as $nis => $info) {
        // hitung jumlah kegiatan per siswa
        $rowspan = count($info['Data']);

        $pdf->Cell(8, $rowspan * 7, $no++, 1, 0, 'C');
        $pdf->Cell(13, $rowspan * 7, $nis, 1, 0, 'C');
        $pdf->Cell(55, $rowspan * 7, $info['Nama_Siswa'], 1, 0);

        $firstRow = true;
        foreach ($info['Data'] as $kegiatan) {
            if (!$firstRow) {
                // kosongkan kolom no,nis, dan nama tanpa border
                $pdf->Cell(8, 7, '', 0, 0);
                $pdf->Cell(13, 7, '', 0, 0);
                $pdf->Cell(55, 7, '', 0, 0);
            }

            $pdf->Cell(54, 7, $kegiatan['Jenis_Kegiatan'], 1);
            $pdf->Cell(28, 7, $kegiatan['No_Telp'], 1);
            $pdf->Cell(13, 7, $kegiatan['Jurusan'] . " " . $kegiatan['Kelas'], 1);

            // menentukan simbol status
            $status_icon = ($kegiatan['Status'] == 'Menunggu Validasi') ? "Menunggu" : (($kegiatan['Status'] == 'Tidak Valid') ? 'Tidak Valid' : 'Valid');

            $pdf->Cell(19, 7, $status_icon, 1, 1, 'C');

            $firstRow = false;
        }
    }
    $pdf->Ln(5);
}

function tampilRekapKegiatan($pdf, $koneksi, $angkatan = NULL, $status = NULL)
{
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(190, 7, 'Rekap Jenis Kegiatan Sertifikat Kegiatan', 0, 1, 'C');
    $pdf->Ln(2);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(120, 7, 'Jenis Kegiatan', 1, 0, 'C');
    $pdf->Cell(70, 7, 'Total Sertifikat', 1, 1, 'C');

    // perbaiki where agar data sesuai
    $where = [];
    if (!empty($angkatan) && $angkatan != 'semua') {
        $where[] = "siswa.Angkatan = '$angkatan'";
    }
    if (!empty($status) && $status != 'semua') {
        $where[] = "sertifikat.Status = '$status'";
    }

    $where_sql = "";
    if (!empty($where)) {
        $where_sql = "WHERE " . implode(" AND ", $where);
    }

    $result = mysqli_query($koneksi, "SELECT kegiatan.Jenis_Kegiatan, COUNT(sertifikat.Id_Kegiatan) AS Total FROM sertifikat
    INNER JOIN kegiatan ON sertifikat.Id_Kegiatan = kegiatan.Id_Kegiatan
    INNER JOIN siswa ON sertifikat.NIS = siswa.NIS 
    $where_sql
    GROUP BY kegiatan.Jenis_Kegiatan
    ORDER BY Total DESC");

    $pdf->SetFont('Arial', '', 10);

    while ($row = mysqli_fetch_assoc($result)) {
        $pdf->Cell(120, 7, $row['Jenis_Kegiatan'], 1, 0);
        $pdf->Cell(70, 7, $row['Total'], 1, 1, 'C');
    }
    $pdf->Ln(5);
}

// Use GET parameters instead of cookies
if ($angkatan == 'semua' && $status == 'semua') {
    $result_angkatan = mysqli_query($koneksi, "SELECT DISTINCT Angkatan FROM siswa ORDER BY Angkatan ASC");
    while ($row = mysqli_fetch_assoc($result_angkatan)) {
        tampilSertifikat($pdf, $koneksi, $row['Angkatan']);
    }
    tampilRekapKegiatan($pdf, $koneksi);
} elseif ($angkatan != 'semua' && $status == 'semua') {
    tampilSertifikat($pdf, $koneksi, $angkatan);
    tampilRekapKegiatan($pdf, $koneksi, $angkatan);
} elseif ($angkatan == 'semua' && $status != 'semua') {
    $result_angkatan = mysqli_query($koneksi, "SELECT DISTINCT Angkatan FROM siswa ORDER BY Angkatan ASC");
    while ($row = mysqli_fetch_assoc($result_angkatan)) {
        tampilSertifikat($pdf, $koneksi, $row['Angkatan'], $status);
    }
    tampilRekapKegiatan($pdf, $koneksi, NULL, $status);
} else {
    tampilSertifikat($pdf, $koneksi, $angkatan, $status);
    tampilRekapKegiatan($pdf, $koneksi, $angkatan, $status);
}

// Clear output buffer and generate PDF
ob_end_clean();
$pdf->Output();
