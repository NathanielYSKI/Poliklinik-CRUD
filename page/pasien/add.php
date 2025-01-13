<?php
include "./function/connection.php";

try {
    $message = "";
    $success = FALSE;
    $error = FALSE;

    // Inisialisasi $rm sebelum form ditampilkan
    $lastIdResult = mysqli_query($connection, "SELECT MAX(id) AS last_id FROM pasien");

    if (!$lastIdResult) {
        die("Error: " . mysqli_error($connection));
    }

    $lastIdRow = mysqli_fetch_assoc($lastIdResult);
    $lastId = $lastIdRow['last_id'] ? $lastIdRow['last_id'] + 1 : 1;

    $rm = date("YmdH") . str_pad($lastId, 4, "0", STR_PAD_LEFT);

    if (isset($_POST['submit'])) {
        $nama = htmlspecialchars($_POST['nama']);
        $username = htmlspecialchars($_POST['username']);
        $alamat = htmlspecialchars($_POST['alamat']);
        $no_hp = htmlspecialchars($_POST['no_hp']);
        $no_ktp = htmlspecialchars($_POST['no_ktp']);
        $no_rm = htmlspecialchars($_POST['no_rm']);
        $pass = htmlspecialchars($_POST['pass']);

        // Enkripsi password
        $hashPassword = password_hash($pass, PASSWORD_DEFAULT);

        // Mulai transaksi
        mysqli_begin_transaction($connection);

        try {
            // Query untuk tabel user
            $userQuery = mysqli_query($connection, "INSERT INTO user (username, nama, password, role) VALUES ('$username', '$nama', '$hashPassword', 'Pasien')");

            if (!$userQuery) {
                throw new Exception("Gagal menambahkan data ke tabel user.");
            }

            // Dapatkan ID user yang baru ditambahkan
            $userId = mysqli_insert_id($connection);

            // Query untuk tabel pasien
            $pasienQuery = mysqli_query($connection, "INSERT INTO pasien (nama, alamat, no_ktp, no_hp, no_rm, user_id, status) VALUES ('$nama', '$alamat', '$no_ktp', '$no_hp', '$no_rm', '$userId', '1')");

            if (!$pasienQuery) {
                throw new Exception("Gagal menambahkan data ke tabel pasien.");
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
                window.location.href = 'index.php?halaman=pasien';
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
                window.location.href = 'index.php?halaman=pasien';
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
                    Halaman Tambah Data Pasien
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.php?halaman=pasien">Pasien</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Tambah Data Pasien
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
                <form action="" method="post">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="username" placeholder="Masukan Username Pasien" name="username" required>
                        <label for="username">Username</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="nama" placeholder="Masukan Nama Pasien" name="nama" required>
                        <label for="nama">Nama Pasien</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="alamat" placeholder="Masukan Alamat Pasien" name="alamat" required>
                        <label for="alamat">Alamat</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="no_ktp" placeholder="Masukan Nomor KTP Pasien" name="no_ktp" required>
                        <label for="no_ktp">Nomor KTP</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="no_hp" placeholder="Masukan Nomor Handphone Pasien" name="no_hp" required>
                        <label for="no_hp">Nomor Handphone</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="no_rm" placeholder="Masukan Nomor Rekam Medis Pasien" name="no_rm" value="<?php echo $rm; ?>" required readonly>
                        <label for="no_hp">Nomor Rekam Medis</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="pass" placeholder="Masukan Password Pasien" name="pass" required>
                        <label for="pass">Password</label>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
