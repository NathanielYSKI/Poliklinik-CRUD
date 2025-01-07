<?php
include "./function/connection.php"; // Koneksi ke database

// Query untuk mendapatkan data Poliklinik dan Dokter
$poliDokterQuery = mysqli_query($connection, "
    SELECT p.id AS id_poli, p.nama_poli, d.id AS id_dokter, d.nama AS nama_dokter
    FROM poli p
    JOIN dokter d ON p.id = d.id_poli
");

// Query untuk mendapatkan semua jadwal
$jadwalQueryAll = mysqli_query($connection, "
    SELECT id, id_dokter, hari, jam_mulai, jam_selesai 
    FROM jadwal_periksa 
    WHERE status = 'Aktif'
");

// Fetch semua data jadwal untuk dioper ke JavaScript
$jadwalData = mysqli_fetch_all($jadwalQueryAll, MYSQLI_ASSOC);

$message = ""; // Variabel untuk pesan notifikasi

if (isset($_POST['submit'])) {
    // Ambil data dari form
    $id_jadwal = htmlspecialchars($_POST['id_jadwal']);
    $keluhan = htmlspecialchars($_POST['keluhan']);

    // Ambil ID pasien dari session
    $id_pasien = $_SESSION['id_pasien'];
    if (!$id_pasien) {
        $message = "ID Pasien tidak ditemukan di session.";
        echo "<script>alert('$message');</script>";
        exit;
    }

    // Mulai transaksi
    mysqli_begin_transaction($connection);

    try {
        // Ambil nomor antrian terakhir untuk jadwal yang sama
        $antrianQuery = mysqli_query(
            $connection,
            "SELECT MAX(no_antrian) AS max_antrian FROM daftar_poli WHERE id_jadwal = '$id_jadwal'"
        );
        $antrianData = mysqli_fetch_assoc($antrianQuery);
        $no_antrian = $antrianData['max_antrian'] ? $antrianData['max_antrian'] + 1 : 1;

        // Insert data ke tabel daftar_poli
        $insertQuery = mysqli_query(
            $connection,
            "INSERT INTO daftar_poli (id_jadwal, id_pasien, keluhan, no_antrian, status) 
            VALUES ('$id_jadwal', '$id_pasien', '$keluhan', '$no_antrian', '0')"
        );

        if (!$insertQuery) {
            throw new Exception("Gagal menambahkan data ke tabel daftar_poli.");
        }

        // Commit transaksi jika berhasil
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
            });
        </script>";
    } catch (Exception $e) {
        // Rollback transaksi jika gagal
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
            });
        </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Periksa</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
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
                        <!-- Dropdown Poliklinik + Dokter -->
                        <div class="form-floating mb-3">
                            <select class="form-select" id="id_poli_dokter" name="id_poli_dokter" required>
                                <option value="" disabled selected>Pilih Poliklinik dan Dokter</option>
                                <?php while ($poliDokter = mysqli_fetch_assoc($poliDokterQuery)) : ?>
                                    <option value="<?= $poliDokter['id_dokter'] ?>">
                                        <?= $poliDokter['nama_poli'] . " - " . $poliDokter['nama_dokter'] ?>
                                    </option>
                                <?php endwhile ?>
                            </select>
                            <label for="id_poli_dokter">Poliklinik dan Dokter</label>
                        </div>

                        <!-- Dropdown Jadwal -->
                        <div class="form-floating mb-3">
                            <select class="form-select" id="id_jadwal" name="id_jadwal" required>
                                <option value="" disabled selected>Pilih Jadwal</option>
                            </select>
                            <label for="id_jadwal">Jadwal</label>
                        </div>

                        <!-- Input Keluhan -->
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

    <!-- Data Jadwal di JavaScript -->
    <script>
        const jadwalData = <?= json_encode($jadwalData); ?>;

        // Event listener untuk Poliklinik + Dokter
        document.getElementById('id_poli_dokter').addEventListener('change', function () {
            const dokterId = this.value; // Ambil ID Dokter
            const jadwalSelect = document.getElementById('id_jadwal');

            // Kosongkan jadwal sebelumnya
            jadwalSelect.innerHTML = '<option value="" disabled selected>Pilih Jadwal</option>';

            // Filter jadwal berdasarkan ID Dokter
            const filteredJadwal = jadwalData.filter(j => j.id_dokter == dokterId);

            // Tambahkan opsi jadwal ke dropdown
            filteredJadwal.forEach(j => {
                const option = document.createElement('option');
                option.value = j.id;
                option.textContent = `${j.hari} - ${j.jam_mulai} s/d ${j.jam_selesai}`;
                jadwalSelect.appendChild(option);
            });
        });
    </script>
</body>
</html>
