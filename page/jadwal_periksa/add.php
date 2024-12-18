<?php
include "./function/connection.php";

try {
    $message = "";

    if (isset($_POST['submit'])) {
        $hari = htmlspecialchars($_POST['hari']);
        $jam_mulai = htmlspecialchars($_POST['jam_mulai']);
        $jam_selesai = htmlspecialchars($_POST['jam_selesai']);

        $id_dokter = $_SESSION['id_dokter']; // Ambil dari session
        if (!$id_dokter) {
            throw new Exception("ID Dokter tidak ditemukan di session.");
        }

        // Mulai transaksi
        mysqli_begin_transaction($connection);

        try {
            // Ubah status jadwal lain menjadi "Tidak Aktif"
            $updateStatusQuery = mysqli_query($connection, "UPDATE jadwal_periksa SET status = 'Tidak Aktif' WHERE id_dokter = '$id_dokter'");
            if (!$updateStatusQuery) {
                throw new Exception("Gagal mengubah status jadwal lain.");
            }

            // Tambahkan jadwal baru dengan status "Aktif"
            $jadwalQuery = mysqli_query($connection, "INSERT INTO jadwal_periksa (id_dokter, hari, jam_mulai, jam_selesai, status) VALUES ('$id_dokter', '$hari', '$jam_mulai', '$jam_selesai', 'Aktif')");
            if (!$jadwalQuery) {
                throw new Exception("Gagal menambahkan data ke tabel jadwal_periksa.");
            }

            // Commit transaksi jika semua berhasil
            mysqli_commit($connection);
            $message = "Berhasil menambahkan jadwal periksa";
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
                window.location.href = 'index.php?halaman=periksa';
            })
            </script>
            ";
        } catch (Exception $e) {
            // Rollback jika ada error
            mysqli_rollback($connection);
            $message = "Gagal menambahkan jadwal: " . $e->getMessage();
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
                window.location.href = 'index.php?halaman=periksa';
            })
            </script>
            ";
        }
    }
} catch (\Throwable $th) {
    $message = "Gagal menambahkan jadwal: " . $th->getMessage();
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
            window.location.href = 'index.php?halaman=periksa';
        })
        </script>
        ";
}
?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Jadwal Periksa</h3>
                <p class="text-subtitle text-muted">
                    Halaman Tambah Jadwal Periksa
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.php?halaman=jadwal_periksa">Jadwal Periksa</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Tambah Jadwal
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <a href="index.php?halaman=jadwal_periksa" class="btn btn-primary btn-sm mb-3">Kembali</a>
        <div class="card">
            <div class="card-body">
                <form action="" method="post">
                    <!-- Input untuk hari -->
                    <div class="form-floating mb-3">
                        <select class="form-select" id="hari" name="hari" required>
                            <option value="" disabled selected>Pilih Hari</option>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                            <option value="Minggu">Minggu</option>
                        </select>
                        <label for="hari">Hari</label>
                    </div>
                    <!-- Input untuk jam_mulai -->
                    <div class="form-floating mb-3">
                        <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" required>
                        <label for="jam_mulai">Jam Mulai</label>
                    </div>
                    <!-- Input untuk jam_selesai -->
                    <div class="form-floating mb-3">
                        <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" required>
                        <label for="jam_selesai">Jam Selesai</label>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
