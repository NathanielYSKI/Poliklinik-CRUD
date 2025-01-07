<?php
// Cek apakah id_pasien sudah ada di session
if (!isset($_SESSION['id_pasien'])) {
    die("Anda belum login sebagai pasien.");
}
$id_pasien = $_SESSION['id_pasien'];

// Koneksi ke database
include "./function/connection.php";

// Query untuk mengambil data riwayat pasien
$query = mysqli_query($connection, "
    SELECT 
        pe.id AS id_periksa, 
        p.nama AS nama_pasien, 
        d.nama AS nama_dokter, 
        jp.hari, 
        jp.jam_mulai, 
        jp.jam_selesai, 
        dp.keluhan, 
        dp.status
    FROM 
        daftar_poli dp
    INNER JOIN 
        jadwal_periksa jp ON dp.id_jadwal = jp.id
    INNER JOIN 
        periksa pe ON dp.id = pe.id_daftarpoli
    INNER JOIN 
        dokter d ON jp.id_dokter = d.id
    INNER JOIN 
        pasien p ON dp.id_pasien = p.id
    WHERE 
        dp.id_pasien = '$id_pasien'
");
?>

<div class="table-responsive">
    <table class="table table-bordered" id="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Dokter</th>
                <th>Jadwal</th>
                <th>Keluhan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($query) > 0) : ?>
                <?php
                $i = 1;
                while ($data = mysqli_fetch_assoc($query)) : ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= $data['nama_dokter'] ?></td>
                        <td><?= $data['hari'] . " - " . $data['jam_mulai'] . " s/d " . $data['jam_selesai'] ?></td>
                        <td><?= $data['keluhan'] ?></td>
                        <td>
                            <?php
                                // Menentukan status dan kelas Bootstrap berdasarkan status
                                if ($data['status'] == '0') {
                                    echo '<span class="badge bg-warning text-dark">Menunggu</span>';
                                } elseif ($data['status'] == '1') {
                                    echo '<span class="badge bg-success text-white">Selesai</span>';
                                } else {
                                    echo '<span class="badge bg-danger text-white">Dibatalkan</span>';
                                }
                            ?>
                        </td>
                        <td>
                            <?php
                                // Menambahkan kelas 'disabled' jika status Menunggu atau Dibatalkan
                                if ($data['status'] == '0' || $data['status'] == '2') {
                                    $disabledClass = 'disabled';
                                    $disabledStyle = 'pointer-events: none; opacity: 0.5;';
                                } else {
                                    $disabledClass = '';
                                    $disabledStyle = '';
                                }
                            ?>
                            <!-- Aksi: Menonaktifkan tombol dengan CSS -->
                            <a class="btn btn-primary btn-sm <?= $disabledClass ?>" href="index.php?halaman=detail_riwayat_pasien&id_periksa=<?= $data['id_periksa'] ?>" style="<?= $disabledStyle ?>">Detail</a>
                        </td>
                    </tr>
                <?php endwhile ?>
            <?php else : ?>
                <tr>
                    <td colspan="6" class="text-center">Tidak ada riwayat periksa</td>
                </tr>
            <?php endif ?>
        </tbody>
    </table>
</div>
