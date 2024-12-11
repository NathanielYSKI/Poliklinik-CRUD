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
        case 'ubah_pasien':
            include "page/pasien/edit.php";
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
        default:
            include "page/error.php";
    }
} else {
    include "page/index.php";
}
