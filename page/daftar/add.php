<?php
include "./function/connection.php"; // Koneksi ke database

// Query untuk mendapatkan data dari tabel data_periksa
$jadwalQuery = mysqli_query($connection, "SELECT id, hari, jam_mulai, jam_selesai FROM jadwal_periksa WHERE status = 'Aktif'");

try {
    $message = "";

    if (isset($_POST['submit'])) {
        $id_jadwal = htmlspecialchars($_POST['id_jadwal']);
        $keluhan = htmlspecialchars($_POST['keluhan']);

        // Ambil ID pasien dari session
        $id_pasien = $_SESSION['id_pasien']; 
        if (!$id_pasien) {
            throw new Exception("ID Pasien tidak ditemukan di session.");
        }

        // Ambil nomor antrian terakhir untuk jadwal yang sama
        $antrianQuery = mysqli_query(
            $connection,
            "SELECT MAX(no_antrian) AS max_antrian FROM daftar_poli WHERE id_jadwal = '$id_jadwal'"
        );
        $antrianData = mysqli_fetch_assoc($antrianQuery);
        $no_antrian = $antrianData['max_antrian'] ? $antrianData['max_antrian'] + 1 : 1;

        // Mulai transaksi
        mysqli_begin_transaction($connection);

        try {
            // Tambahkan data periksa
            $periksaQuery = mysqli_query(
                $connection,
                "INSERT INTO daftar_poli (id_jadwal, id_pasien, keluhan, no_antrian, status) 
                VALUES ('$id_jadwal', '$id_pasien', '$keluhan', '$no_antrian', '0')"
            );

            if (!$periksaQuery) {
                throw new Exception("Gagal menambahkan data ke tabel periksa.");
            }

            // Commit transaksi jika semua berhasil
            mysqli_commit($connection);
            $message = "Berhasil menambahkan data periksa.";
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
                window.location.href = 'index.php?halaman=daftar';
            })
            </script>
            ";
        } catch (Exception $e) {
            // Rollback jika ada error
            mysqli_rollback($connection);
            $message = "Gagal menambahkan data periksa: " . $e->getMessage();
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
                window.location.href = 'index.php?halaman=daftar';
            })
            </script>
            ";
        }
    }
} catch (\Throwable $th) {
    $message = "Gagal menambahkan data periksa: " . $th->getMessage();
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
            window.location.href = 'index.php?halaman=daftar';
        })
        </script>
        ";
}
?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tambah Data Periksa</h3>
                <p class="text-subtitle text-muted">
                    Halaman Tambah Data Periksa
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.php?halaman=daftar">Daftar</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Tambah Data Periksa
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
                    <!-- Input untuk jadwal -->
                    <div class="form-floating mb-3">
                        <select class="form-select" id="id_jadwal" name="id_jadwal" required>
                            <option value="" disabled selected>Pilih Jadwal</option>
                            <?php while ($jadwal = mysqli_fetch_assoc($jadwalQuery)) : ?>
                                <option value="<?= $jadwal['id'] ?>">
                                    <?= $jadwal['hari'] . " - " . $jadwal['jam_mulai'] . " s/d " . $jadwal['jam_selesai'] ?>
                                </option>
                            <?php endwhile ?>
                        </select>
                        <label for="id_jadwal">Jadwal</label>
                    </div>
                    <!-- Input untuk keluhan -->
                    <div class="form-floating mb-3">
                        <textarea class="form-control" id="keluhan" name="keluhan" placeholder="Tuliskan keluhan Anda" required></textarea>
                        <label for="keluhan">Keluhan</label>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
