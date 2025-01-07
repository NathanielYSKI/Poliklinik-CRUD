<?php
include "./function/connection.php";

try {
    $message = "";
    $success = FALSE;
    $error = FALSE;

    // Ambil data dari tabel poli untuk dropdown
    $poliQuery = mysqli_query($connection, "SELECT id, nama_poli FROM poli");

    if (isset($_POST['submit'])) {
        $nama = htmlspecialchars($_POST['nama']);
        $username = htmlspecialchars($_POST['username']);
        $alamat = htmlspecialchars($_POST['alamat']);
        $no_hp = htmlspecialchars($_POST['no_hp']);
        $id_poli = htmlspecialchars($_POST['id_poli']);
        $pass = htmlspecialchars($_POST['pass']);
        
        // Enkripsi password
        $hashPassword = password_hash($pass, PASSWORD_DEFAULT);
        
        // Proses upload foto
        $foto = $_FILES['foto'];
        $fotoName = $foto['name'];
        $fotoTmpName = $foto['tmp_name'];
        $fotoSize = $foto['size'];
        $fotoError = $foto['error'];
        
        // Validasi file gambar
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $fotoExt = strtolower(pathinfo($fotoName, PATHINFO_EXTENSION));
        
        if ($fotoError === 0) {
            if (in_array($fotoExt, $allowedExtensions)) {
                if ($fotoSize < 5000000) { // Maksimal 5MB
                    $newFotoName = uniqid('', true) . '.' . $fotoExt; // Nama file unik
                    $fotoDestination = 'uploads/profiles/' . $newFotoName; // Perubahan di sini
                    
                    // Pindahkan foto ke folder
                    move_uploaded_file($fotoTmpName, $fotoDestination);
                } else {
                    echo "Ukuran file terlalu besar.";
                    exit;
                }
            } else {
                echo "Format file tidak diperbolehkan.";
                exit;
            }
        } else {
            echo "Terjadi kesalahan saat meng-upload foto.";
            exit;
        }
        
        // Mulai transaksi
        mysqli_begin_transaction($connection);
        
        try {
            // Query untuk tabel user
            $userQuery = mysqli_query($connection, "INSERT INTO user (username, nama, password, role, foto) VALUES ('$username', '$nama', '$hashPassword', 'Dokter', '$fotoDestination')");
            
            if (!$userQuery) {
                throw new Exception("Gagal menambahkan data ke tabel user.");
            }
            
            // Dapatkan ID user yang baru ditambahkan
            $userId = mysqli_insert_id($connection);
            
            // Query untuk tabel dokter dengan foto
            $dokterQuery = mysqli_query($connection, "INSERT INTO dokter (nama, alamat, no_hp, id_poli, user_id) VALUES ('$nama', '$alamat', '$no_hp', '$id_poli', '$userId')");
            
            if (!$dokterQuery) {
                throw new Exception("Gagal menambahkan data ke tabel dokter.");
            }
            
            // Commit transaksi jika semua berhasil
            mysqli_commit($connection);
            $message = "Berhasil menambahkan data";
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
                window.location.href = 'index.php?halaman=dokter';
            })
            </script>
            ";
        } catch (Exception $e) {
            // Rollback jika ada error
            mysqli_rollback($connection);
            $message = "Gagal menambahkan data: " . $e->getMessage();
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
                window.location.href = 'index.php?halaman=dokter';
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
            window.location.href = 'index.php?halaman=dokter';
        })
        </script>
        ";
}
?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Dokter</h3>
                <p class="text-subtitle text-muted">
                    Halaman Tambah Data Dokter
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.php?halaman=dokter">Dokter</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Tambah Data Dokter
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <a href="index.php?halaman=dokter" class="btn btn-primary btn-sm mb-3">Kembali</a>
        <div class="card">
            <div class="card-body">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="username" placeholder="Masukan Username Dokter" name="username" required>
                        <label for="username">Username</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="nama" placeholder="Masukan Nama Dokter" name="nama" required>
                        <label for="nama">Nama Dokter</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="alamat" placeholder="Masukan Alamat Dokter" name="alamat" required>
                        <label for="alamat">Alamat</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="no_hp" placeholder="Masukan Nomor Handphone Dokter" name="no_hp" required>
                        <label for="no_hp">Nomor Handphone</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="pass" placeholder="Masukan Password Dokter" name="pass" required>
                        <label for="pass">Password</label>
                    </div>
                    <div class="mb-3">
                        <select class="form-select mb-3" id="id_poli" name="id_poli" required>
                            <option value="" disabled selected>Poliklinik</option>
                            <?php while ($row = mysqli_fetch_assoc($poliQuery)) : ?>
                                <option value="<?= $row['id'] ?>"><?= $row['nama_poli'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <!-- Input untuk foto -->
                    <div class="mb-3">
                        <input type="file" class="form-control" id="foto" name="foto" accept="image/*" required>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
