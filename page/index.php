<?php
include "./function/connection.php";

$role = $_SESSION['role']; // Asumsi role disimpan dalam session

try {
    $query_dokter = mysqli_query($connection, "SELECT nama FROM dokter");
    $data_dokter = mysqli_fetch_all($query_dokter);
    $count_dokter = count($data_dokter);

    $query_poli = mysqli_query($connection, "SELECT nama_poli FROM poli");
    $data_poli = mysqli_fetch_all($query_poli);
    $count_poli = count($data_poli);

    $query_pasien = mysqli_query($connection, "SELECT nama FROM pasien");
    $data_pasien = mysqli_fetch_all($query_pasien);
    $count_pasien = count($data_pasien);

    $query_obat = mysqli_query($connection, "SELECT nama_obat FROM obat");
    $data_obat = mysqli_fetch_all($query_obat);
    $count_obat = count($data_obat);
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
        })
        </script>
        ";
}
?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Dashboard</h3>
                <p class="text-subtitle text-muted">
                    Halaman Dashboard
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.html">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Home
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="row">
        <div class="col-12 col-lg-12">
            <div class="row">
                <?php if ($role == 'Admin'): ?>
                    <div class="col-12 col-lg-2 col-md-4">
                        <div class="card">
                            <div class="card-body px-3 py-3">
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="text-muted font-semibold">Dokter</h6>
                                        <h6 class="font-extrabold mb-0"><?= $count_dokter ?></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-2 col-md-4">
                        <div class="card">
                            <div class="card-body px-3 py-3">
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="text-muted font-semibold">Poliklinik</h6>
                                        <h6 class="font-extrabold mb-0"><?= $count_poli ?></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-2 col-md-4">
                        <div class="card">
                            <div class="card-body px-3 py-3">
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="text-muted font-semibold">Pasien</h6>
                                        <h6 class="font-extrabold mb-0"><?= $count_pasien ?></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-2 col-md-4">
                        <div class="card">
                            <div class="card-body px-3 py-3">
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="text-muted font-semibold">Obat</h6>
                                        <h6 class="font-extrabold mb-0"><?= $count_obat ?></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php elseif ($role == 'Dokter'): ?>
                    <div class="col-12 col-lg-2 col-md-4">
                        <div class="card">
                            <div class="card-body px-3 py-3">
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="text-muted font-semibold">Dokter</h6>
                                        <h6 class="font-extrabold mb-0"><?= $count_dokter ?></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>
