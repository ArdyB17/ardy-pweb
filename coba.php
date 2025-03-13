<?php
ob_start();

if(isset($_COOKIE['Status']) && $_COOKIE['Status'] != 'Operator'){
    echo"<script>alert('Anda Tidak Memiliki Akses Halaman Ini!!!!');window.location.href='halaman_utama.php?page=home'</script>";
}
else{
$ambildata=mysqli_query($koneksi,"SELECT * FROM pegawai INNER JOIN pengguna using(Username) WHERE Username='$_COOKIE[Username]'");
$hasil=mysqli_fetch_assoc($ambildata);

// Determine edit mode
$edit_mode = isset($_GET['edit']) ? true : false;

// Hapus akun_operator (Delete Account) Logic
if(isset($_POST['hapus_akun'])){
    $password_input = $_POST['password_hapus'];
    
    // Verify password
    $cek_password = mysqli_query($koneksi, "SELECT Password FROM pengguna WHERE Username='$_COOKIE[Username]'");
    $data_password = mysqli_fetch_assoc($cek_password);
    
    if(password_verify($password_input, $data_password['Password'])){
        // Delete related records first
        $delete_pengguna=mysqli_query($koneksi, "DELETE FROM pengguna WHERE Username='$_COOKIE[Username]'");
        $detele_pegawai=mysqli_query($koneksi, "DELETE FROM pegawai WHERE Username='$_COOKIE[Username]'");
        if(!$delete_pengguna && !$detele_pegawai){
            echo "<script>alert('Gagal Menghapus akun!'); window.location.href='halaman_utama.php?page=akun_operator';</script>";
           
        }
        else{
        // setcookie('Username', '', time() - 3600, "/");
        // setcookie('Status', '', time() - 3600, "/");
        
        echo "<script>alert('akun Berhasil Dihapus!'); window.location.href='../logout.php';</script>";
       
        }
    } else {
        echo "<script>alert('Password Salah. akun Tidak Dapat Dihapus!'); window.location.href='halaman_utama.php?page=akun_operator';</script>";
       
    }
}

if(isset($_POST['ubah_operator'])){
    $Nama_Lengkap       =$_POST['Nama_Lengkap'];
    $Username           =$_POST['Username'];
    $password           =$_POST['password'];
    $Konfirmasi_Password=$_POST['Konfirmasi_Password'];
    if($password=='' && $Konfirmasi_Password==''){
        $tambah_operator=mysqli_query($koneksi,"UPDATE pegawai SET Nama_Lengkap='$Nama_Lengkap' WHERE Username='$_COOKIE[Username]'");
        if(!$tambah_operator){
            echo"<script>alert('Gagal Mengubah Data Pegawai !!!!');window.location.href='halaman_utama.php?page=akun_operator'</script>";
        }
        else{
            echo"<script>window.location.href='halaman_utama.php?page=akun_operator'</script>";
        }
    }
    else if($password!=$Konfirmasi_Password){
        echo"<script>alert('Password Tidak Sama!!!!');window.location.href='halaman_utama.php?page=akun_operator'</script>";
    }
    else if($password==$Konfirmasi_Password){
        $password = password_hash($password, PASSWORD_DEFAULT);
        $ubah_pegawai=mysqli_query($koneksi,"UPDATE pegawai SET Nama_Lengkap='$Nama_Lengkap' WHERE Username='$_COOKIE[Username]'");
        $ubah_pengguna=mysqli_query($koneksi,"UPDATE pengguna SET Password='$password' WHERE Username='$_COOKIE[Username]'");
        if(!$ubah_pegawai){
            echo"<script>alert('Gagal Menambahkan Data !!!!');window.location.href='halaman_utama.php?page=akun_operator'</script>";
        }
        else if(!$ubah_pengguna){
            echo"<script>alert('Gagal Mengubah Data Pengguna!!!!');window.location.href='halaman_utama.php?page=akun_operator'</script>";
        }
        else{
            echo"<script>alert('Berhasil Merubah Data, Silahkan Login Ulang Terimakasih!!!!');window.location.href='../login.php'</script>";
        }
    }   
}   
?>

<form action="" method="post" enctype="multipart/form-data">

    <!-- Header Section -->
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <a class="text-decoration-none" href="halaman_utama.php?page=home_operator">

                    <!-- <hr class="mb-5" style="border-top: 5px solid #FFC107; opacity: 0.5;"> -->
                </a>
            </div>
        </div>

        <div class="card_wrapper p-4">
            <!-- Form Section -->

            <div class="row">
                <div class="container-fluid">
                    <a href="halaman_utama.php?page=tambah pegawai" class="btn d-flex float-end btn-success ">
                        <i class="fa fa-plus me-2 mt-1"></i>Tambah Akun
                    </a>

                    <h4>Profil Akun </h4>
                    <hr style="border-top: 0.1rem solid black;">
                </div>
                <!-- Left Column -->
                <div class="col-12 col-lg-6">
                    <!-- </div> -->
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="Nama_Lengkap" autocomplete="off"
                            value="<?php echo $hasil['Nama_Lengkap'];?>" <?php if(!$edit_mode) echo 'readonly'; ?>>
                        <label for="Nama_Lengkap"><i class="fas fa-id-card me-2"></i>Nama Lengkap</label>
                    </div>
                    <div class="form-floating mb-3 <?php if(!$edit_mode) echo 'd-none'; ?>">
                        <input type="password" class="form-control" id="password" name="password" autocomplete="off"
                            value="">
                        <label for="password"><i class="fas fa-lock me-2"></i> Password</label>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-12 col-lg-6">
                    <div class="form-floating mb-3">
                        <input type="text" readonly class="form-control" name="Username" autocomplete="off"
                            value="<?php echo $hasil['Username'];?>" required>
                        <label for="Username"><i class="fas fa-user-circle me-2"></i>Username</label>
                    </div>
                    <div class="form-floating mb-3 <?php if(!$edit_mode) echo 'd-none'; ?>">
                        <input type="password" class="form-control" id="Konfirmasi_Password" name="Konfirmasi_Password"
                            autocomplete="off" value="">
                        <label for="Konformasi_Password"><i class="fas fa-lock me-2"></i>Konfirmasi Password</label>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="row mt-4">
                <div class="col-12">
                    <?php if(!$edit_mode): ?>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <a href="halaman_utama.php?page=profil operator&edit=1" class="btn btn-primary w-100">
                                <i class="fas fa-edit me-2"></i>Ubah Profil
                            </a>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal"
                                data-bs-target="#hapusakunModal">
                                <i class="fas fa-trash me-2"></i>Hapus
                            </button>
                        </div>
                    </div>
                    <?php else: ?>
                    <button type="submit" name="ubah_operator" class="btn btn-success w-100">
                        <i class="fas fa-paper-plane me-2"></i>Simpan
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</form>


<!-- Modal Konfirmasi Hapus akun_operator -->
<div class="modal fade" id="hapusakunModal" tabindex="-1" aria-labelledby="hapusakunModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hapusakunModalLabel">Konfirmasi Hapus akun</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post">
                <div class="modal-body">
                    <p>Masukkan password untuk mengkonfirmasi penghapusan akun:</p>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="password_hapus" name="password_hapus" required>
                        <label for="password_hapus">Password</label>
                    </div>
                    <div class="alert alert-warning">
                        <strong>Peringatan!</strong> Menghapus akun akan Membuat Anda Kehilanagn Akses ke
                        Halaman Ini!!!
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="hapus_akun" class="btn btn-danger">Hapus akun</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
}
?>