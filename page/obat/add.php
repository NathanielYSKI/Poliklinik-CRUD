<?php
include "./function/connection.php";

try {
    $message = "";
    $success = FALSE;
    $error = FALSE;

    if (isset($_POST['submit'])) {
        $nama_obat = htmlspecialchars($_POST['nama_obat']);
        $kemasan = htmlspecialchars($_POST['kemasan']);
        $keterangan = htmlspecialchars($_POST['keterangan']);

        $query = mysqli_query($connection, "INSERT INTO obat VALUES (null, '$nama_obat', '$kemasan', '$keterangan')");

        if ($query == TRUE) {
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
            window.location.href = 'index.php?halaman=obat';
        })
        </script>
        ";
        } else {
            $message = "Gagal menambahkan data";
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
            window.location.href = 'index.php?halaman=obat';
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
            window.location.href = 'index.php?halaman=obat';
        })
        </script>
        ";
}
?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Obat</h3>
                <p class="text-subtitle text-muted">
                    Halaman Tambah Data Obat
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.php?halaman=obat">Obat</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Tambah Data Obat
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <a href="index.php?halaman=obat" class="btn btn-primary btn-sm mb-3">Kembali</a>
        <div class="card">
            <div class="card-body">
                <form action="" method="post">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="nama_obat" placeholder="Masukan Nama Obat" name="nama_obat" required>
                        <label for="nama_obat">Nama Obat</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="kemasan" placeholder="Masukan Kemasan Obat" name="kemasan" required>
                        <label for="kemasan">Kemasan</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="keterangan" placeholder="Masukan Keterangan" name="keterangan" required>
                        <label for="keterangan">Keterangan</label>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>