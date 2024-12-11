<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register</title>
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

    $lastIdResult = mysqli_query($connection, "SELECT MAX(id) AS last_id FROM pasien");

    if (!$lastIdResult) {
        die("Error: " . mysqli_error($connection));
    }

    $lastIdRow = mysqli_fetch_assoc($lastIdResult);
    $lastId = $lastIdRow['last_id'] ? $lastIdRow['last_id'] + 1 : 1;

    $rm = date("YmdH") . str_pad($lastId, 4, "0", STR_PAD_LEFT);

    try {
        if (isset($_POST['register'])) {
            $nama = htmlspecialchars($_POST['nama']);
            $username = htmlspecialchars($_POST['username']);
            $password = htmlspecialchars($_POST['password']);
            $alamat = htmlspecialchars($_POST['alamat']);
            $no_ktp = htmlspecialchars($_POST['no_ktp']);
            $no_hp = htmlspecialchars($_POST['no_hp']);
            $no_rm = htmlspecialchars($_POST['no_rm']);

            $hashPassword = password_hash($password, PASSWORD_DEFAULT);

            // Mulai transaksi
            mysqli_begin_transaction($connection);

            $queryUser = mysqli_query($connection, "INSERT INTO user VALUES (null, '$nama', '$username', '$hashPassword', 'Pasien')");
            if ($queryUser) {
                $user_id = mysqli_insert_id($connection);
                $queryPasien = mysqli_query($connection, "INSERT INTO pasien (nama, alamat, no_ktp, no_hp, no_rm, user_id) VALUES ('$nama', '$alamat', '$no_ktp', '$no_hp', '$no_rm', '$userId')");
                if ($queryPasien) {
                    mysqli_commit($connection);
                    echo "
                    <script>
                        Swal.fire({
                            title: 'Berhasil',
                            text: 'Berhasil Register!',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                        }).then(() => {
                            window.location.href = 'index.php?halaman=login';
                        })
                    </script>
                    ";
                } else {
                    mysqli_rollback($connection);
                    throw new Exception('Gagal menambahkan data pasien');
                }
            } else {
                mysqli_rollback($connection);
                throw new Exception('Gagal menambahkan data user');
            }
        }
    } catch (\Throwable $th) {
        echo "
            <script>
                Swal.fire({
                    title: 'Gagal',
                    text: 'Gagal Register!',
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                }).then(() => {
                    window.location.href = 'register.php';
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
                        <a href="register.php"><img src="./assets/compiled/svg/POLI.svg" alt="Logo" /></a>
                    </div>
                    <h1 class="auth-title">Daftar</h1>
                    <p class="auth-subtitle mb-5">Isi data berikut untuk melakukan regristasi.</p>

                    <form action="" method="post">
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text" class="form-control form-control-xl" placeholder="Nama" name="nama" required />
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                        </div>
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text" class="form-control form-control-xl" placeholder="Username" name="username" required />
                            <div class="form-control-icon">
                                <i class="bi bi-envelope"></i>
                            </div>
                        </div>
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="password" class="form-control form-control-xl" placeholder="Password" name="password" required />
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                        </div>
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text" class="form-control form-control-xl" placeholder="Alamat" name="alamat" required />
                            <div class="form-control-icon">
                                <i class="bi bi-geo-alt"></i>
                            </div>
                        </div>
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text" class="form-control form-control-xl" placeholder="No KTP" name="no_ktp" required />
                            <div class="form-control-icon">
                                <i class="bi bi-card-text"></i>
                            </div>
                        </div>
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text" class="form-control form-control-xl" placeholder="No HP" name="no_hp" required />
                            <div class="form-control-icon">
                                <i class="bi bi-telephone"></i>
                            </div>
                        </div>
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text" class="form-control form-control-xl" placeholder="No RM" name="no_rm" value="<?php echo $rm; ?>" required readonly />
                            <div class="form-control-icon">
                                <i class="bi bi-hospital"></i>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5" name="register">
                            Daftar
                        </button>
                    </form>
                    <div class="text-center mt-5 text-lg fs-4">
                        <p class="text-gray-600">
                            Sudah punya akun? <a href="login.php" class="font-bold">Masuk</a>.
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
