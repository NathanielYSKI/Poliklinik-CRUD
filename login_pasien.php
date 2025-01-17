<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>

    <link rel="shortcut icon" href="./assets/compiled/svg/favicon.svg" type="image/x-icon" />
    <link rel="stylesheet" href="./assets/compiled/css/app.css" />
    <link rel="stylesheet" href="./assets/compiled/css/app-dark.css" />
    <link rel="stylesheet" href="./assets/compiled/css/auth.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
<?php
include "./function/connection.php";

session_start();

if (isset($_SESSION['nama'])) {
    header('Location: index.php?halaman=beranda');
}

try {
    if (isset($_POST['login'])) {
        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password']);
    
        // Query untuk mengecek user
        $cekUser = mysqli_query($connection, "SELECT * FROM user WHERE username = '$username'");
        $data = mysqli_fetch_assoc($cekUser);
    
        if ($cekUser->num_rows > 0) {
            if (password_verify($password, $data['password'])) {
                // Periksa apakah role user adalah Admin atau Dokter
                if ($data['role'] === 'Pasien') {
                    // Set session umum
                    $_SESSION['nama'] = $data['nama'];
                    $_SESSION['role'] = $data['role'];
                    $_SESSION['id'] = $data['id'];
                    $_SESSION['foto'] = $data['foto'];
                    $_SESSION["timeout"] = time() + (24 * 60 * 60);
        
                    // Cek jika role adalah dokter, tambahkan session id dokter
                    if ($data['role'] === 'Pasien') {
                        $userId = $data['id']; // Ambil user_id dari tabel user
                        $cekPasien = mysqli_query($connection, "SELECT * FROM pasien WHERE user_id = '$userId'");
                        $pasienData = mysqli_fetch_assoc($cekPasien);
                        $_SESSION['id_pasien'] = $pasienData['id']; // Menyimpan id_dokter dalam session
                    }
                    // Redirect jika login sukses
                    echo "
                    <script>
                    Swal.fire({
                        title: 'Berhasil',
                        text: 'Berhasil Login!',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                    }).then(() => {
                        window.location.href = 'index.php?halaman=beranda';
                    })
                    </script>
                    ";
                } else {
                    // Jika role bukan Admin atau Dokter
                    echo "
                    <script>
                    Swal.fire({
                        title: 'Akses Ditolak',
                        text: 'Hanya Pasien yang dapat masuk.',
                        icon: 'error',
                        showConfirmButton: true,
                        timer: 2000,
                        timerProgressBar: true,
                    }).then(() => {
                        window.location.href = 'login_pasien.php';
                    })
                    </script>
                    ";
                }
            } else {
                echo "
                <script>
                Swal.fire({
                    title: 'Gagal',
                    text: 'Username / password yang anda masukkan salah!',
                    icon: 'error',
                    showConfirmButton: true,
                    timer: 2000,
                    timerProgressBar: true,
                }).then(() => {
                    window.location.href = 'login_pasien.php';
                })
                </script>
                ";
            }
        } else {
            echo "
            <script>
            Swal.fire({
                title: 'Gagal',
                text: 'Akun yang anda masukkan tidak ada!',
                icon: 'error',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
            }).then(() => {
                window.location.href = 'index.php?halaman=login';
            })
            </script>
            ";
        }
    }        
} catch (\Throwable $th) {
    echo "
        <script>
        Swal.fire({
            title: 'Gagal',
            text: 'Server error!',
            icon: 'error',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
        }).then(() => {
            window.location.href = 'login_pasien.php';
        })
        </script>
        ";
}
?>

    <script src="assets/static/js/initTheme.js"></script>
    <div id="auth">
        <div class="row h-100">
            <div class="col-lg-5 col-12">
                <div id="auth-left">
                    <div class="auth-logo">
                        <a href="login.php"><img src="./assets/compiled/svg/POLI.svg" alt="Logo" /></a>
                    </div>
                    <h1 class="auth-title">Masuk.</h1>
                    <p class="auth-subtitle mb-5">
                        Masuk dengan data yang sudah anda daftarkan pada halaman register.
                    </p>

                    <form action="" method="post">
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text" class="form-control form-control-xl" placeholder="Username" name="username" required />
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                        </div>
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="password" class="form-control form-control-xl" placeholder="Password" name="password" required />
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5" name="login">
                            Masuk
                        </button>
                    </form>
                    <div class="text-center mt-5 text-lg fs-4">
                        <p class="text-gray-600">
                            Belum punya akun?
                            <a href="register.php" class="font-bold">Daftar</a>.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right"></div>
            </div>
        </div>
    </div>
</body>

</html>
