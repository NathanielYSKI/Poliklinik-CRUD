<?php

if (isset($_GET['halaman'])) {
    $halaman = $_GET['halaman'];
    switch ($halaman) {
        case 'beranda':
            include "page/index.php";
            break;
        case 'logout':
            include "page/logout.php";
            break;
        case 'dokter':
            include "page/dokter/view.php";
            break;
        case 'tambah_dokter':
            include "page/dokter/add.php";
            break;
        case 'ubah_dokter':
            include "page/dokter/edit.php";
            break;
        case 'hapus_dokter':
            include "page/dokter/delete.php";
            break;
        case 'tambah_poli':
            include "page/poli/add.php";
                break;
        case 'ubah_poli':
            include "page/poli/edit.php";
            break;
        case 'hapus_poli':
            include "page/poli/delete.php";
            break;
        case 'tambah_obat':
            include "page/obat/add.php";
                break;
        case 'ubah_obat':
            include "page/obat/edit.php";
            break;
        case 'hapus_obat':
            include "page/obat/delete.php";
            break;
        case 'tambah_pasien':
            include "page/pasien/add.php";
            break;
        case 'ubah_pasien':
            include "page/pasien/edit.php";
            break;
        case 'hapus_pasien':
            include "page/pasien/delete.php";
            break;
        case 'ubah_data':
            include "page/dokter/edit_data.php";
            break;
        case 'periksa_pasien':
            include "page/periksa/add_periksa.php";
            break;
        case 'tambah_jadwal_periksa':
            include "page/jadwal_periksa/add.php";
            break;
        case 'ubah_jadwal_periksa':
            include "page/jadwal_periksa/edit.php";
            break;
        case 'detail_periksa':
            include "page/periksa/add_obat.php";
            break;
        case 'tambah_daftar':
            include "page/daftar/add.php";
            break;
        case 'daftar':
            include "page/daftar/view.php";
            break;
        case 'pasien':
            include "page/pasien/view.php";
            break;
        case 'poli':
            include "page/poli/view.php";
            break;
        case 'obat':
            include "page/obat/view.php";
            break;
        case 'periksa':
            include "page/periksa/view.php";
            break;
        case 'jadwal_periksa':
            include "page/jadwal_periksa/view.php";
            break;
        case 'ubah_periksa':
            include "page/jadwal_periksa/edit.php";
            break;
        case 'riwayat':
            include "page/riwayat/view.php";
            break;
        case 'detail_riwayat':
            include "page/riwayat/detail_view.php";
            break;
        case 'riwayat_pasien':
            include "page/riwayat/view_pasien.php";
            break;
        case 'detail_riwayat_pasien':
            include "page/riwayat/detail_view_pasien.php";
            break;
        default:
            include "page/error.php";
    }
} else {
    include "page/index.php";
}
