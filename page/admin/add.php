<?php
include "./function/connection.php";

try {
    $message = "";
    $success = FALSE;
    $error = FALSE;

    if (isset($_POST['submit'])) {
        $nama      = htmlspecialchars($_POST['nama']);
        $username  = htmlspecialchars($_POST['username']);
        $alamat    = htmlspecialchars($_POST['alamat']);
        $no_hp     = htmlspecialchars($_POST['no_hp']);
        $pass      = htmlspecialchars($_POST['pass']);

        // Enkripsi password
        $hashPassword = password_hash($pass, PASSWORD_DEFAULT);

        // Proses upload foto
        $foto         = $_FILES['foto'];
        $fotoName     = $foto['name'];
        $fotoTmpName  = $foto['tmp_name'];
        $fotoSize     = $foto['size'];
        $fotoError    = $foto['error'];

        // Validasi file gambar
        $allowedExt   = ['jpg', 'jpeg', 'png', 'gif'];
        $fotoExt      = strtolower(pathinfo($fotoName, PATHINFO_EXTENSION));
        
        // Buat nama file baru secara unik
        $newFotoName  = uniqid('', true) . '.' . $fotoExt;
        // Lokasi simpan file, pastikan folder uploads/profiles/ sudah ada
        $fotoDestination = 'uploads/profiles/' . $newFotoName;

        if ($fotoError === 0) {
            if (in_array($fotoExt, $allowedExt)) {
                if ($fotoSize < 5000000) { // Maksimal 5MB
                    if (!move_uploaded_file($fotoTmpName, $fotoDestination)) {
                        echo "
                        <script>
                        Swal.fire({
                            title: 'Gagal',
                            text: 'Gagal mengunggah foto.',
                            icon: 'error',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                        }).then(() => {
                            window.location.href = 'index.php?halaman=pasien';
                        });
                        </script>
                        ";
                        exit;
                    }
                } else {
                    echo "
                    <script>
                    Swal.fire({
                        title: 'Gagal',
                        text: 'Ukuran file terlalu besar.',
                        icon: 'error',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                    }).then(() => {
                        window.location.href = 'index.php?halaman=pasien';
                    });
                    </script>
                    ";
                    exit;
                }
            } else {
                echo "
                <script>
                Swal.fire({
                    title: 'Gagal',
                    text: 'Format file tidak diperbolehkan.',
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                }).then(() => {
                    window.location.href = 'index.php?halaman=pasien';
                });
                </script>
                ";
                exit;
            }
        } else {
            echo "
            <script>
            Swal.fire({
                title: 'Gagal',
                text: 'Terjadi kesalahan saat meng-upload foto.',
                icon: 'error',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
            }).then(() => {
                window.location.href = 'index.php?halaman=pasien';
            });
            </script>
            ";
            exit;
        }
        
        // Mulai transaksi database
        mysqli_begin_transaction($connection);
        
        try {
            // Query untuk tabel user
            // Menyimpan foto di tabel user agar profil pasien dapat menampilkan foto
            $userQuery = mysqli_query($connection, "INSERT INTO user (username, nama, password, role, foto, status) VALUES ('$username', '$nama', '$hashPassword', 'Admin', '$fotoDestination', '1')");
            if (!$userQuery) {
                throw new Exception("Gagal menambahkan data ke tabel user.");
            }
            
            // Dapatkan ID user yang baru ditambahkan
            $userId = mysqli_insert_id($connection);
            
            // Query untuk tabel pasien
            $pasienQuery = mysqli_query($connection, "INSERT INTO admin (nama, alamat, no_hp, user_id, status) VALUES ('$nama', '$alamat', '$no_hp', '$userId', '1')");
            if (!$pasienQuery) {
                throw new Exception("Gagal menambahkan data ke tabel admin.");
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
                window.location.href = 'index.php?halaman=admin';
            });
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
                window.location.href = 'index.php?halaman=admin';
            });
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
        window.location.href = 'index.php?halaman=admin';
    });
    </script>
    ";
}
?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Admin</h3>
                <p class="text-subtitle text-muted">
                    Halaman Tambah Data Admin
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.php?halaman=pasien">Admin</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Tambah Data Admin
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
                    <!-- Username -->
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="username" placeholder="Masukan Username Pasien" name="username" required>
                        <label for="username">Username</label>
                    </div>
                    <!-- Nama -->
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="nama" placeholder="Masukan Nama Admin" name="nama" required>
                        <label for="nama">Nama Admin</label>
                    </div>
                    <!-- Alamat -->
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="alamat" placeholder="Masukan Alamat Admin" name="alamat" required>
                        <label for="alamat">Alamat</label>
                    </div>
                    <!-- Nomor Handphone -->
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="no_hp" placeholder="Masukan Nomor Handphone Admin" name="no_hp" required>
                        <label for="no_hp">Nomor Handphone</label>
                    </div>
                    <!-- Password -->
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="pass" placeholder="Masukan Password Admin" name="pass" required>
                        <label for="pass">Password</label>
                    </div>
                    <!-- Input Foto -->
                    <div class="mb-3">
                        <input type="file" class="form-control" id="foto" name="foto" accept="image/*" required>
                    </div>
                    <!-- Tombol Submit -->
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
</body>
</html>
