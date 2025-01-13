<?php
include "./function/connection.php"; // Koneksi ke database

// Query untuk mendapatkan data jadwal periksa yang aktif
$jadwalQuery = mysqli_query($connection, "SELECT id, hari, jam_mulai, jam_selesai FROM jadwal_periksa WHERE status = 'Aktif'");

?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Daftar Jadwal Poli dan Keluhan</h3>
                <p class="text-subtitle text-muted">
                    Data Periksa
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">
                            Daftar
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <a href="index.php?halaman=tambah_daftar" class="btn btn-primary btn-sm mb-3">Daftar</a>
        <div class="card">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Jadwal</th>
                            <th>Keluhan</th>
                            <th>No. Antrian</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Query untuk mendapatkan daftar pasien dan keluhannya berdasarkan jadwal yang dipilih
                        $periksaQuery = mysqli_query($connection, "
                            SELECT dp.no_antrian, jp.hari, jp.jam_mulai, jp.jam_selesai, dp.keluhan
                            FROM daftar_poli dp
                            JOIN jadwal_periksa jp ON dp.id_jadwal = jp.id
                            WHERE jp.status = 'Aktif' AND dp.status = '0'
                        ");
                        $counter = 1;
                        while ($data = mysqli_fetch_assoc($periksaQuery)) :
                        ?>
                            <tr>
                                <td><?= $counter++; ?></td>
                                <td><?= $data['hari'] . " - " . $data['jam_mulai'] . " s/d " . $data['jam_selesai']; ?></td>
                                <td><?= $data['keluhan']; ?></td>
                                <td><?= $data['no_antrian']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
