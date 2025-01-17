<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Ubah Password Pasien</title>
    <!-- Contoh Bootstrap CSS, sesuaikan jika sudah ada -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php
include "./function/connection.php";

try {
    // Pastikan session 'id_pasien' ter-set
    if (!isset($_SESSION['id_admin'])) {
        echo "
        <script>
            Swal.fire({
                title: 'Gagal',
                text: 'Session tidak ditemukan.',
                icon: 'error',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
            }).then(() => {
                window.location.href = 'index.php';
            });
        </script>
        ";
        exit();
    }

    $message = "";
    $id = $_SESSION['id_admin'];

    // Ambil data pasien beserta data user terkait
    $select = mysqli_query($connection, 
        "SELECT admin.*, user.username, user.password 
         FROM admin 
         JOIN user ON admin.user_id = user.id 
         WHERE admin.id = '$id'"
    );
    $data = mysqli_fetch_assoc($select);

    if (!$data) {
        // Jika data pasien tidak ditemukan, arahkan kembali
        header('Location: index.php?halaman=admin');
        exit();
    }

    // Proses jika form disubmit
    if (isset($_POST['submit'])) {
        $passwordLama     = $_POST['password_lama'];
        $passwordBaru     = $_POST['password_baru'];
        $konfirmasiPassword = $_POST['konfirmasi_password'];

        // Cek password lama
        if (!password_verify($passwordLama, $data['password'])) {
            $message = "Password lama salah.";
        } elseif ($passwordBaru !== $konfirmasiPassword) {
            $message = "Password baru dan konfirmasi tidak sama.";
        } elseif ($passwordBaru == $passwordLama) {
            $message = "Password baru tidak boleh sama dengan password lama.";
        } else {
            // Hash password baru
            $hashedPassword = password_hash($passwordBaru, PASSWORD_DEFAULT);

            // Mulai transaksi untuk menjaga konsistensi data
            mysqli_begin_transaction($connection);

            try {
                // Update password pada tabel user
                $updatePassword = mysqli_query($connection, 
                    "UPDATE user SET password = '$hashedPassword' WHERE id = '{$data['user_id']}'"
                );
                if (!$updatePassword) {
                    throw new Exception("Gagal memperbarui password.");
                }

                // Commit transaksi bila update berhasil
                mysqli_commit($connection);
                $message = "Password berhasil diubah.";

                // Tampilkan notifikasi sukses dengan SweetAlert2
                echo "
                <script>
                Swal.fire({
                    title: 'Berhasil',
                    text: '$message',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                }).then(() => {
                    window.location.href = 'index.php?halaman=beranda';
                });
                </script>
                ";
                exit();
            } catch (Exception $e) {
                // Rollback jika terjadi error
                mysqli_rollback($connection);
                $message = 'Gagal mengubah password: ' . $e->getMessage();
            }
        }

        // Tampilkan pesan error jika ada
        if ($message && strpos($message, 'berhasil') === false) {
            echo "
            <script>
            Swal.fire({
                title: 'Gagal',
                text: '$message',
                icon: 'error',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            }).then(() => {
                window.location.href = 'index.php?halaman=beranda';
            });
            </script>
            ";
        }
    }
} catch (Exception $e) {
    echo "
        <script>
        Swal.fire({
            title: 'Gagal',
            text: 'Server error: {$e->getMessage()}',
            icon: 'error',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
        }).then(() => {
            window.location.href = 'index.php?halaman=beranda';
        });
        </script>
    ";
}
?>

<div class="container mt-5">
    <div class="page-heading">
        <div class="page-title mb-4">
            <h3>Ubah Password</h3>
            <p class="text-muted">Halaman untuk mengganti password Anda</p>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-body p-4">
                    <form action="" method="post">
                        <div class="form-floating mb-3">
                            <input 
                                type="password" 
                                class="form-control" 
                                id="password_lama" 
                                placeholder="Masukkan Password Lama" 
                                name="password_lama" 
                                required 
                            />
                            <label for="password_lama">Password Lama</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input 
                                type="password" 
                                class="form-control" 
                                id="password_baru" 
                                placeholder="Masukkan Password Baru" 
                                name="password_baru" 
                                required 
                            />
                            <label for="password_baru">Password Baru</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input 
                                type="password" 
                                class="form-control" 
                                id="konfirmasi_password" 
                                placeholder="Ulangi Password Baru" 
                                name="konfirmasi_password" 
                                required 
                            />
                            <label for="konfirmasi_password">Ulangi Password Baru</label>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary" name="submit">
                                Ubah Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Contoh Bootstrap JS, sesuaikan jika sudah ada -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
