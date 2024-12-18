<?php

// Cek apakah id_dokter sudah ada di session
if (!isset($_SESSION['id_dokter'])) {
    die("Anda belum login sebagai dokter.");
}
$id_dokter = $_SESSION['id_dokter'];

// Koneksi ke database
include "./function/connection.php";

// Query untuk mengambil data poli berdasarkan id_dokter
$query = mysqli_query($connection, "
    SELECT 
    dp.id_pasien, 
    p.nama,
    dp.id,
    dp.status,
    dp.no_antrian,
    dp.keluhan, 
    jp.id AS id_jadwal
    FROM 
        jadwal_periksa jp
    INNER JOIN 
        daftar_poli dp ON jp.id = dp.id_jadwal
    INNER JOIN 
        pasien p ON dp.id_pasien = p.id
    WHERE 
        jp.id_dokter = '$id_dokter'AND dp.status = '0';
");
?>

<div class="table-responsive">
    <table class="table table-bordered" id="table">
        <thead>
            <tr>
                <th>#</th>
                <th>No Antrian</th>
                <th>Nama Pasien</th>
                <th>Keluhan</th>
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
                        <td><?= $data['no_antrian'] ?></td>
                        <td><?= $data['nama'] ?></td>
                        <td><?= $data['keluhan'] ?></td>
                        <td>
                            <a class="btn btn-primary btn-sm" href="index.php?halaman=periksa_pasien&id=<?= $data['id'] ?>">Periksa</a>
                        </td>
                    </tr>
                <?php endwhile ?>
            <?php else : ?>
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data jadwal</td>
                </tr>
            <?php endif ?>
        </tbody>
    </table>
</div>
