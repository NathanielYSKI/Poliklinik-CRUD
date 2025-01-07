<?php

// Cek apakah id_pasien sudah ada di session
if (!isset($_SESSION['id_pasien'])) {
    die("Anda belum login sebagai pasien.");
}
$id_pasien = $_SESSION['id_pasien'];

// Cek apakah id_periksa sudah ada di URL
if (!isset($_GET['id_periksa']) || empty($_GET['id_periksa'])) {
    throw new Exception("ID Periksa tidak ditemukan di URL.");
}
$id_periksa = htmlspecialchars($_GET['id_periksa']);

// Koneksi ke database
include "./function/connection.php";

// Query untuk mengambil data periksa dan detail periksa berdasarkan id_periksa
$query = mysqli_query($connection, "
    SELECT 
        p.nama AS nama_pasien, 
        dp.id AS id_periksa,  
        d.keluhan, 
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
        dp.id = '$id_periksa' AND d.id_pasien = '$id_pasien'
    GROUP BY 
        dp.id
");

?>

<div class="container mt-4">
    <h3>Detail Periksa Pasien</h3>
    <?php if (mysqli_num_rows($query) > 0) : ?>
        <?php
        $data = mysqli_fetch_assoc($query);
        ?>
        <div class="card">
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th>Nama Pasien</th>
                        <td><?= $data['nama_pasien'] ?></td>
                    </tr>
                    <tr>
                        <th>Keluhan</th>
                        <td><?= $data['keluhan'] ?></td>
                    </tr>
                    <tr>
                        <th>Obat yang Diberikan</th>
                        <td><?= nl2br($data['obat_nama']) ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal Periksa</th>
                        <td><?= $data['tgl_periksa'] ?></td>
                    </tr>
                    <tr>
                        <th>Catatan</th>
                        <td><?= $data['catatan'] ?></td>
                    </tr>
                </table>
                <a href="index.php?halaman=riwayat_pasien" class="btn btn-primary">Kembali ke Riwayat Pasien</a>
            </div>
        </div>
    <?php else : ?>
        <p>Tidak ada data periksa untuk pasien ini.</p>
    <?php endif ?>
</div>
