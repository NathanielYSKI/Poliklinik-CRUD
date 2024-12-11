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
        $id = $_GET['id'];

        // Select Data
        $select = mysqli_query($connection, "SELECT * FROM obat WHERE id = '$id'");
        $data = mysqli_fetch_assoc($select);

        if (!$data) {
            header('Location: index.php?halaman=obat');
        }

        // Submit
        if (isset($_POST['submit'])) {
            $nama_obat = htmlspecialchars($_POST['nama_obat']);
            $kemasan = htmlspecialchars($_POST['kemasan']);
            $keterangan = htmlspecialchars($_POST['keterangan']);

            $query = mysqli_query($connection, "UPDATE obat SET nama_obat = '$nama_obat', kemasan = '$kemasan', keterangan = '$keterangan' WHERE id = '$id'");

            if ($query == TRUE) {
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
                window.location.href = 'index.php?halaman=obat';
            })
            </script>
            ";
            } else {
                $message = "Gagal mengubah data";
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
                    Halaman Ubah Data Obat
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.php?halaman=obat">Obat</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Ubah Data Obat
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <a href="index.php?halaman=kontak" class="btn btn-primary btn-sm mb-3">Kembali</a>
        <div class="card">
            <div class="card-body">
                <form action="" method="post">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="nama_obat" placeholder="Masukan Nama Obat" name="nama_obat" value="<?= $data['nama_obat'] ?>" required>
                        <label for="nama_obat">Nama Kontak</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="kemasan" placeholder="Masukan Kemasan Obat" name="kemasan" value="<?= $data['kemasan'] ?>" required>
                        <label for="kemasan">Kemasan</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="keterangan" placeholder="Masukan Keterangan" name="keterangan" value="<?= $data['keterangan'] ?>" required>
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