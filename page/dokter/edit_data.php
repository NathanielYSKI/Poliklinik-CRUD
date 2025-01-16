<?php
include "./function/connection.php";

try {
    $message = "";
    $success = FALSE;
    $error = FALSE;

    // Ambil `id_dokter` dari session
    $id = $_SESSION['id_dokter'];

    // Ambil data dokter beserta user terkait
    $select = mysqli_query($connection, 
        "SELECT dokter.*, user.username, user.foto 
         FROM dokter 
         JOIN user ON dokter.user_id = user.id 
         WHERE dokter.id = '$id'");
    $data = mysqli_fetch_assoc($select);

    if (!$data) {
        header('Location: index.php?halaman=dokter');
        exit();
    }

    // Ambil data poliklinik untuk dropdown
    $poliQuery = mysqli_query($connection, "SELECT id, nama_poli FROM poli WHERE status = '1'");

    // Submit
    if (isset($_POST['submit'])) {
        $nama = htmlspecialchars($_POST['nama']);
        $alamat = htmlspecialchars($_POST['alamat']);
        $no_hp = htmlspecialchars($_POST['no_hp']);
        $id_poli = intval($_POST['id_poli']);
        $username = htmlspecialchars($_POST['username']);

        // Menangani file upload foto profil
        $fotoBaru = $_FILES['foto']['name'];
        $fotoTmp = $_FILES['foto']['tmp_name'];
        $fotoError = $_FILES['foto']['error'];

        // Mulai transaksi untuk menjaga konsistensi data
        mysqli_begin_transaction($connection);

        try {
            // Update tabel user
            $userId = $data['user_id']; // Ambil ID user dari dokter
            $updateUser = mysqli_query($connection, 
                "UPDATE user SET username = '$username' WHERE id = '$userId'");
            if (!$updateUser) {
                throw new Exception("Gagal memperbarui username di tabel user.");
            }

            // Jika ada foto baru, proses upload
            if ($fotoError === 0) {
                // Tentukan path penyimpanan foto baru
                $fotoDestination = 'uploads/profiles/' . basename($fotoBaru);

                // Pindahkan file ke folder tujuan
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

            // Update tabel dokter
            $updateDokter = mysqli_query($connection, 
                "UPDATE dokter SET nama = '$nama', alamat = '$alamat', no_hp = '$no_hp', id_poli = '$id_poli' WHERE id = '$id'");
            if (!$updateDokter) {
                throw new Exception("Gagal memperbarui data dokter.");
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
                window.location.href = 'index.php?halaman=beranda';
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
                <h3>Data <?= $data['nama'] ?></h3>
                <p class="text-subtitle text-muted">
                    Halaman Ubah Data <?= $data['nama'] ?>
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">
                            Ubah Data Dokter
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-body">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="username" placeholder="Masukan Username Dokter" name="username" value="<?= $data['username'] ?>" required>
                        <label for="username">Username</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="nama" placeholder="Masukan Nama Dokter" name="nama" value="<?= $data['nama'] ?>" required>
                        <label for="nama">Nama Dokter</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="alamat" placeholder="Masukan Alamat Dokter" name="alamat" value="<?= $data['alamat'] ?>" required>
                        <label for="alamat">Alamat</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="no_hp" placeholder="Masukan Nomor Handphone Dokter" name="no_hp" value="<?= $data['no_hp'] ?>" required>
                        <label for="no_hp">Nomor Handphone</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-select" id="id_poli" name="id_poli" required>
                            <option value="" disabled>Pilih Poliklinik</option>
                            <?php while ($row = mysqli_fetch_assoc($poliQuery)) : ?>
                                <option value="<?= $row['id'] ?>" <?= $data['id_poli'] == $row['id'] ? 'selected' : '' ?>>
                                    <?= $row['nama_poli'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                        <label for="id_poli">Poliklinik</label>
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
