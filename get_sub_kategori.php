<?php
header('Content-Type: application/json');
require_once 'koneksi.php';

if (isset($_GET['kategori'])) {
    $kategori = mysqli_real_escape_string($koneksi, $_GET['kategori']);
    $query = mysqli_query($koneksi, "SELECT Id_Kategori, Sub_Kategori 
                                    FROM kategori 
                                    WHERE Kategori='$kategori' 
                                    ORDER BY Sub_Kategori");
    
    $data = array();
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = array(
            'id' => $row['Id_Kategori'],
            'name' => $row['Sub_Kategori']
        );
    }
    
    echo json_encode($data);
} else {
    echo json_encode(['error' => 'Missing kategori parameter']);
}
