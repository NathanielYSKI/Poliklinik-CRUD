<?php

if (!isset($_GET['id']) || empty($_GET['id'])) {
    throw new Exception("ID Daftar Poli tidak ditemukan di URL.");
}
$id_daftarpoli = htmlspecialchars($_GET['id']);

// Koneksi ke database
include "./function/connection.php";

// Query untuk mengambil data periksa dan detail periksa berdasarkan id_daftarpoli
$query = mysqli_query($connection, "
    SELECT 
        p.nama, 
        dp.id AS id_periksa,  
        d.keluhan, 
        d.status, 
        dp.tgl_periksa, 
        dp.catatan,
        GROUP_CONCAT(o.nama_obat SEPARATOR '<br>') AS obat_nama,  -- Menambahkan <br> sebagai pemisah
        GROUP_CONCAT(o.harga SEPARATOR '<br>') AS obat_harga  -- Menambahkan <br> sebagai pemisah
    FROM 
        periksa dp
    INNER JOIN 
        daftar_poli d ON dp.id_daftarpoli = d.id
    INNER JOIN 
        pasien p ON d.id_pasien = p.id
    LEFT JOIN 
        detail_periksa dp1 ON dp.id = dp1.id_periksa
    LEFT JOIN 
        obat o ON dp1.id_obat = o.id
    WHERE 
        dp.id_daftarpoli = '$id_daftarpoli' AND d.status = '1'
    GROUP BY 
        dp.id
");
?>

<div class="table-responsive">
    <table class="table table-bordered" id="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Pasien</th>
                <th>Keluhan</th>
                <th>Obat yang Diberikan</th>
                <th>Tanggal Periksa</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($query) > 0) : ?>
                <?php
                $i = 1;
                while ($data = mysqli_fetch_assoc($query)) : ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= $data['nama'] ?></td>
                        <td><?= $data['keluhan'] ?></td>
                        <td>
                            <!-- Menampilkan obat dalam format terpisah dengan <br> -->
                            <?= nl2br($data['obat_nama']) ?>
                        </td>
                        <td><?= $data['tgl_periksa'] ?></td>
                        <td><?= $data['catatan'] ?></td>
                    </tr>
                <?php endwhile ?>
            <?php else : ?>
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data periksa</td>
                </tr>
            <?php endif ?>
        </tbody>
    </table>
</div>
