<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Ubah Password Dokter</title>
    <!-- Contoh Bootstrap CSS, silakan ganti/ sesuaikan jika sudah ada -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php
include "./function/connection.php";

try {
    // Pastikan session 'id_dokter' ter-set
    if (!isset($_SESSION['id_dokter'])) {
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
            })
        </script>
        ";
        exit();
    }

    $message = "";
    $id = $_SESSION['id_dokter'];

    // Ambil data dokter beserta user terkait
    $select = mysqli_query($connection, 
        "SELECT dokter.*, user.username, user.password 
         FROM dokter 
         JOIN user ON dokter.user_id = user.id 
         WHERE dokter.id = '$id'"
    );
    $data = mysqli_fetch_assoc($select);

    if (!$data) {
        // Jika data tidak ditemukan, arahkan balik
        header('Location: index.php?halaman=dokter');
        exit();
    }

    // Proses jika form disubmit
    if (isset($_POST['submit'])) {
        $passwordLama = $_POST['password_lama'];
        $passwordBaru = $_POST['password_baru'];
        $konfirmasiPassword = $_POST['konfirmasi_password'];

        // Cek password lama
        if (!password_verify($passwordLama, $data['password'])) {
            $message = "Password lama salah.";
        } elseif ($passwordBaru !== $konfirmasiPassword) {
            $message = "Password baru dan konfirmasi tidak sama.";
        } elseif ($passwordBaru == $passwordLama) {
            $message = "Password baru tidak boleh sama dengan password lama";
        }else {
            // Hash password baru
            $hashedPassword = password_hash($passwordBaru, PASSWORD_DEFAULT);

            // Mulai transaksi untuk jaga konsistensi data
            mysqli_begin_transaction($connection);

            try {
                // Update password di tabel user
                $updatePassword = mysqli_query($connection, 
                    "UPDATE user SET password = '$hashedPassword' WHERE id = '{$data['user_id']}'");
                if (!$updatePassword) {
                    throw new Exception("Gagal memperbarui password.");
                }

                // Commit transaksi jika berhasil
                mysqli_commit($connection);
                $message = "Password berhasil diubah.";

                // Tampilkan SweetAlert2 sukses
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
            } catch (Exception $e) {
                // Rollback jika ada kendala
                mysqli_rollback($connection);
                $message = 'Gagal mengubah password: ' . $e->getMessage();
            }
        }

        // Jika ada pesan error
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
        })
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
                        <button type="submit" class="btn btn-primary" name="submit">
                            Ubah Password
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Contoh Bootstrap JS, silakan ganti/ sesuaikan jika sudah ada -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
