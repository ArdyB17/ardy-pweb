<?php
include 'koneksi.php';

$id_kategori = mysqli_real_escape_string($koneksi, $_GET['sub_kategori']);
$query = mysqli_query($koneksi, "SELECT Id_Kegiatan, Jenis_Kegiatan FROM kegiatan WHERE Id_Kategori='$id_kategori' ORDER BY Jenis_Kegiatan");

$data = array();
while ($row = mysqli_fetch_assoc($query)) {
    $data[] = array(
        'id' => $row['Id_Kegiatan'],
        'name' => $row['Jenis_Kegiatan']
    );
}

header('Content-Type: application/json');
echo json_encode($data);
