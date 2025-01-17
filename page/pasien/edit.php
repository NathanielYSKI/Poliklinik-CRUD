<?php
include "./function/connection.php";

if (!isset($_SESSION['nama'])) {
    header('Location: index.php?halaman=login');
}

try {
    $message = "";
    $success = FALSE;
    $error = FALSE;

    if (isset($_GET['id'])) {
        $id = htmlspecialchars($_GET['id']);

        // Ambil data pasien beserta user terkait
        $select = mysqli_query($connection, 
            "SELECT pasien.*, user.username, user.foto
             FROM pasien 
             JOIN user ON pasien.user_id = user.id 
             WHERE pasien.id = '$id'");
        $data = mysqli_fetch_assoc($select);

        if (!$data) {
            header('Location: index.php?halaman=pasien');
            exit();
        }

        // Submit form untuk update data
        if (isset($_POST['submit'])) {
            $nama = htmlspecialchars($_POST['nama']);
            $alamat = htmlspecialchars($_POST['alamat']);
            $no_hp = htmlspecialchars($_POST['no_hp']);
            $no_ktp = intval($_POST['no_ktp']);
            $username = htmlspecialchars($_POST['username']);

            // Mulai transaksi untuk menjaga konsistensi data
            mysqli_begin_transaction($connection);

            try {
                // Update tabel user (username)
                $userId = $data['user_id']; // Ambil ID user dari pasien
                $updateUser = mysqli_query($connection, 
                    "UPDATE user SET username = '$username' WHERE id = '$userId'");
                if (!$updateUser) {
                    throw new Exception("Gagal memperbarui username di tabel user.");
                }

                // Menangani upload foto
                $fotoBaru = $_FILES['foto']['name'];
                $fotoTmp = $_FILES['foto']['tmp_name'];
                $fotoError = $_FILES['foto']['error'];

                if ($fotoError === UPLOAD_ERR_OK) {
                    // Tentukan folder penyimpanan foto
                    $fotoDestination = 'uploads/profiles/' . basename($fotoBaru);

                    // Cek apakah foto sudah ada sebelumnya, jika ada hapus
                    if (file_exists($data['foto'])) {
                        unlink($data['foto']); // Hapus file foto lama
                    }

                    // Pindahkan file foto ke folder tujuan
                    if (move_uploaded_file($fotoTmp, $fotoDestination)) {
                        // Update foto di tabel user
                        $updateFoto = mysqli_query($connection, "UPDATE user SET foto = '$fotoDestination' WHERE id = '$userId'");
                        if (!$updateFoto) {
                            throw new Exception("Gagal memperbarui foto user.");
                        }
                    } else {
                        throw new Exception("Gagal mengunggah foto.");
                    }
                }

                // Update tabel pasien
                $updatePasien = mysqli_query($connection, 
                    "UPDATE pasien SET nama = '$nama', alamat = '$alamat', no_hp = '$no_hp', no_ktp = '$no_ktp' WHERE id = '$id'");
                if (!$updatePasien) {
                    throw new Exception("Gagal memperbarui data pasien.");
                }

                // Commit transaksi jika semua berhasil
                mysqli_commit($connection);
                $message = "Berhasil mengubah data";
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
                    window.location.href = 'index.php?halaman=pasien';
                })
                </script>
                ";
            } catch (Exception $e) {
                // Rollback jika ada error
                mysqli_rollback($connection);
                $message = "Gagal mengubah data: " . $e->getMessage();
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
                    window.location.href = 'index.php?halaman=pasien';
                })
                </script>
                ";
            }
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
            window.location.href = 'index.php?halaman=pasien';
        })
        </script>
        ";
}
?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Pasien</h3>
                <p class="text-subtitle text-muted">
                    Halaman Ubah Data Pasien
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.php?halaman=pasien">Pasien</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Ubah Data Pasien
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <a href="index.php?halaman=pasien" class="btn btn-primary btn-sm mb-3">Kembali</a>
        <div class="card">
            <div class="card-body">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="username" placeholder="Masukan Username Pasien" name="username" value="<?= $data['username'] ?>" required>
                        <label for="username">Username</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="nama" placeholder="Masukan Nama Pasien" name="nama" value="<?= $data['nama'] ?>" required>
                        <label for="nama">Nama Pasien</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="alamat" placeholder="Masukan Alamat Pasien" name="alamat" value="<?= $data['alamat'] ?>" required>
                        <label for="alamat">Alamat</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="no_hp" placeholder="Masukan Nomor Handphone Pasien" name="no_hp" value="<?= $data['no_hp'] ?>" required>
                        <label for="no_hp">Nomor Handphone</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="no_ktp" placeholder="Masukan Nomor KTP Pasien" name="no_ktp" value="<?= $data['no_ktp'] ?>" required>
                        <label for="no_ktp">Nomor KTP</label>
                    </div>
                    <div class="mb-3">
                        <label for="foto">Foto Profil</label><br>
                        <img src="<?= $data['foto'] ?>" alt="Foto Profil" class="img-thumbnail" width="100"><br>
                        <input type="file" name="foto" id="foto" class="form-control mt-2">
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
