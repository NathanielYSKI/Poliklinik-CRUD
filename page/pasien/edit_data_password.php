<?php
include "./function/connection.php";

try {
    $message = "";
    $success = FALSE;
    $error = FALSE;

    // Ambil `id_dokter` dari session
    $id = $_SESSION['id_pasien'];

    // Ambil data dokter beserta user terkait
    $select = mysqli_query($connection, 
        "SELECT pasien.*, user.username, user.password 
         FROM pasien 
         JOIN user ON pasien.user_id = user.id 
         WHERE pasien.id = '$id'");
    $data = mysqli_fetch_assoc($select);

    if (!$data) {
        header('Location: index.php?halaman=pasien');
        exit();
    }

    // Submit form
    if (isset($_POST['submit'])) {
        $passwordLama = $_POST['password_lama'];
        $passwordBaru = $_POST['password_baru'];
        $konfirmasiPassword = $_POST['konfirmasi_password'];

        // Cek apakah password lama benar
        if (!password_verify($passwordLama, $data['password'])) {
            $message = "Password lama salah.";
        } elseif ($passwordBaru !== $konfirmasiPassword) {
            $message = "Password baru dan konfirmasi password tidak cocok.";
        } else {
            // Hash password baru
            $hashedPassword = password_hash($passwordBaru, PASSWORD_DEFAULT);

            // Mulai transaksi untuk menjaga konsistensi data
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
                echo "
                <script>
                Swal.fire({
                    title: 'Berhasil',
                    text: '$message',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                }).then(() => {
                    window.location.href = 'index.php?halaman=beranda';
                })
                </script>
                ";
            } catch (Exception $e) {
                // Rollback jika ada error
                mysqli_rollback($connection);
                $message = "Gagal mengubah password: " . $e->getMessage();
            }
        }

        // Menampilkan pesan error
        if ($message) {
            echo "
            <script>
            Swal.fire({
                title: 'Gagal',
                text: '$message',
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

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Ubah Password</h3>
                <p class="text-subtitle text-muted">Halaman untuk mengganti password Anda</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">
                            Ubah Password
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-body">
                <form action="" method="post">
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="password_lama" placeholder="Masukkan Password Lama" name="password_lama" required>
                        <label for="password_lama">Password Lama</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="password_baru" placeholder="Masukkan Password Baru" name="password_baru" required>
                        <label for="password_baru">Password Baru</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="konfirmasi_password" placeholder="Ulangi Password Baru" name="konfirmasi_password" required>
                        <label for="konfirmasi_password">Ulangi Password Baru</label>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary" name="submit">Ubah Password</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
