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
        $select = mysqli_query($connection, "SELECT * FROM jadwal_periksa WHERE id = '$id'");
        $data = mysqli_fetch_assoc($select);

        if (!$data) {
            header('Location: index.php?halaman=periksa');
        }

        // Submit
        if (isset($_POST['submit'])) {
            $hari = htmlspecialchars($_POST['hari']);
            $jam_mulai = htmlspecialchars($_POST['jam_mulai']);
            $jam_selesai = htmlspecialchars($_POST['jam_selesai']);
            $status = htmlspecialchars($_POST['status']);

            // Mulai transaksi
            mysqli_begin_transaction($connection);

            try {
                // Jika status diubah menjadi "Aktif", ubah semua yang lain menjadi "Tidak Aktif"
                if ($status === 'Aktif') {
                    $updateAll = mysqli_query($connection, "UPDATE jadwal_periksa SET status = 'Tidak Aktif' WHERE id != '$id'");
                    if (!$updateAll) {
                        throw new Exception("Gagal mengubah status jadwal lainnya menjadi Tidak Aktif.");
                    }
                }

                // Update jadwal periksa yang dipilih
                $query = mysqli_query($connection, "UPDATE jadwal_periksa SET hari = '$hari', jam_mulai = '$jam_mulai', jam_selesai = '$jam_selesai', status = '$status' WHERE id = '$id'");
                if (!$query) {
                    throw new Exception("Gagal mengubah data jadwal periksa.");
                }

                // Commit transaksi
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
                    window.location.href = 'index.php?halaman=jadwal_periksa';
                })
                </script>
                ";
            } catch (Exception $e) {
                // Rollback jika ada kesalahan
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
                    window.location.href = 'index.php?halaman=jadwal_periksa';
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
        window.location.href = 'index.php?halaman=jadwal_periksa';
    })
    </script>
    ";
}
?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Jadwal Periksa</h3>
                <p class="text-subtitle text-muted">
                    Halaman Ubah Data Jadwal Periksa
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.php?halaman=periksa">Jadwal Periksa</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Ubah Data Jadwal Periksa
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <a href="index.php?halaman=periksa" class="btn btn-primary btn-sm mb-3">Kembali</a>
        <div class="card">
            <div class="card-body">
                <form action="" method="post">
                    <div class="form-floating mb-3">
                        <?php
                        // Daftar hari dalam seminggu
                        $days = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"];
                        ?>
                        <select class="form-select" id="hari" name="hari" required>
                            <option value="" disabled>Pilih Hari</option>
                            <?php foreach ($days as $day): ?>
                                <option value="<?= $day ?>" <?= $day === $data['hari'] ? 'selected' : '' ?>>
                                    <?= $day ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <label for="hari">Hari</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="time" class="form-control" id="jam_mulai" placeholder="Masukan Jam Mulai" name="jam_mulai" value="<?= $data['jam_mulai'] ?>" required>
                        <label for="jam_mulai">Jam Mulai</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="time" class="form-control" id="jam_selesai" placeholder="Masukan Jam Mulai" name="jam_selesai" value="<?= $data['jam_selesai'] ?>" required>
                        <label for="jam_selesai">Jam Selesai</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-select" id="status" name="status" required>
                            <option value="Aktif" <?= $data['status'] === 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                            <option value="Tidak Aktif" <?= $data['status'] === 'Tidak Aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
                        </select>
                        <label for="status">Status</label>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
