<?php
// Include database connection file
include 'koneksi.php';

// Check if login form is submitted
if (isset($_POST['submit_login'])) {
    // Get username and password from form
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Query to check if user exists as operator
    // Searches in pengguna table where username matches input
    $cek_operator = mysqli_query($koneksi, "SELECT username, Password FROM pengguna WHERE username='$user'");
    $data_operator = mysqli_fetch_assoc($cek_operator);

    // Query to check if user exists as student
    // Searches in pengguna table where NIS (student ID) matches input
    $cek_siswa = mysqli_query($koneksi, "SELECT NIS, Password FROM pengguna WHERE NIS='$user'");
    $data_siswa = mysqli_fetch_assoc($cek_siswa);

    // Password Hashing Generation - Comment this when not generating hash
    // echo $enkrip = password_hash($pass, PASSWORD_DEFAULT);

    /*
 Password Hashing Verification - Comment this when not verifying hash
    */

    // Login Authentication Logic
    // First checks if user is an operator
    if (mysqli_num_rows($cek_operator) > 0) {
        // Verify password for operator
        if (password_verify($pass, $data_operator['Password'])) {
            $user_operator = $data_operator['username'];
            // Get operator's full name from operator table
            $nama_operator = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT Nama_Lengkap FROM operator WHERE username='$user_operator'"));

            // Set cookies for operator session
            setcookie('username', $data_operator['username'], time() + (60 * 60 * 24 * 7), '/');
            setcookie('Nama_Lengkap', $nama_operator['Nama_Lengkap'], time() + (60 * 60 * 24 * 7), '/');
            setcookie('level_user', 'operator', time() + (60 * 60 * 24 * 7), '/');

            // Redirect to dashboard on successful login
            echo "<script>alert('Login Berhasil');window.location.href='tampilan/dashboard.php?'</script>";
        } else {
            // Password doesn't match
            echo "<script>alert('Password salah!');</script>";
        }
    }
    // Check if user is a student
    elseif (mysqli_num_rows($cek_siswa) > 0) {
        // Verify password for student
        if (password_verify($pass, $data_siswa['Password'])) {
            $user_siswa = $data_siswa['NIS'];
            // Get student's name from siswa table
            $nama_siswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT Nama_Siswa FROM siswa WHERE NIS='$user_siswa'"));

            // Set cookies for student session
            setcookie('NIS', $data_siswa['NIS'], time() + (60 * 60 * 24 * 7), '/');
            setcookie('level_user', 'siswa', time() + (60 * 60 * 24 * 7), '/');
            setcookie('Nama_Lengkap', $nama_siswa['Nama_Siswa'], time() + (60 * 60 * 24 * 7), '/');

            // Redirect to dashboard on successful login
            echo "<script>alert('Login Berhasil');window.location.href='tampilan/dashboard.php'</script>";
        } else {
            // Password doesn't match
            echo "<script>alert('Gagal login, Password salah!');window.location.href='login.php'</script>";
        }
    } else {
        // User not found in database
        echo "<script>alert('Gagal login,Username atau password salah!');window.location.href='login.php'</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SKKPD</title>

    <!-- External CSS Dependencies -->
    <link rel="stylesheet" href="bootstrap/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* Base Styles - Sets the foundation for the entire page */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #71b7e6, #9b59b6);
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Login Container Styles */
        .login-container {
            width: 100%;
            max-width: 450px;
            /* Reduced width since we removed image section */
            background: #ffffff;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        /* Header Styles */
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-title {
            color: #2C3E50;
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 0.5rem;
            position: relative;
            display: inline-block;
        }

        .login-title::after {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            bottom: -10px;
            width: 50px;
            height: 4px;
            background: linear-gradient(135deg, #3498DB, #9b59b6);
            border-radius: 2px;
            margin: 0 auto;
        }

        /* Form Field Styles */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 500;
            color: #34495E;
            font-size: 0.95rem;
            margin-bottom: 0.75rem;
            display: block;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #3498DB;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.15);
            outline: none;
        }

        /* Password Input Group Styles */
        .password-group {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
            background: none;
            border: none;
            padding: 0;
        }

        /* Login Button Styles */
        .login-button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #3498DB, #2980B9);
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
        }

        /* Responsive Design */
        @media screen and (max-width: 480px) {
            .login-container {
                padding: 1.5rem;
            }

            .login-title {
                font-size: 1.75rem;
            }
        }
    </style>
</head>

<body>
    <!-- Main Container -->
    <div class="login-container">
        <!-- Login Header -->
        <div class="login-header">
            <h3 class="login-title">Selamat Datang 👋</h3>
            <p class="text-muted">Silahkan login ke akun anda</p>
        </div>

        <!-- Login Form -->
        <form method="POST" action="">
            <!-- Username Field -->
            <div class="form-group">
                <label for="username" class="form-label">
                    <i class="fas fa-user-circle me-2"></i>Username
                </label>
                <input
                    type="text"
                    class="form-control"
                    id="username"
                    name="username"
                    autocomplete="off"
                    required
                    placeholder="Enter your username">
            </div>

            <!-- Password Field -->
            <div class="form-group">
                <label for="password" class="form-label">
                    <i class="fas fa-lock me-2"></i>Password
                </label>
                <div class="password-group">
                    <input
                        type="password"
                        class="form-control"
                        id="password"
                        name="password"
                        autocomplete="off"
                        required
                        placeholder="Enter your password">
                    <button type="button" class="toggle-password" onclick="togglePassword()">
                        <i class="fas fa-eye" id="toggleIcon"></i>
                    </button>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" name="submit_login" class="login-button">
                <i class="fas fa-sign-in-alt me-2"></i>Login
            </button>
        </form>
    </div>

    <!-- Password Toggle Functionality -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            // Toggle password visibility
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>