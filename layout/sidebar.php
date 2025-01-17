<?php
// Cek apakah pengguna sudah login dan ada session 'role'
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;

$user_name = $_SESSION['nama'];  // Nama pengguna
$user_role = $_SESSION['role'];  // Peran pengguna

// Menyandikan data untuk digunakan di URL
$user_name_encoded = urlencode($user_name);
$user_role_encoded = urlencode($user_role);

// Nomor WhatsApp tujuan (gunakan nomor internasional)
$whatsapp_number = "6289509466544";  // Ganti dengan nomor WhatsApp yang benar

// Membuat link WhatsApp
$whatsapp_link = "https://wa.me/$whatsapp_number?text=Halo,%20saya%20$user_name_encoded,%20saya%20adalah%20$user_role_encoded.%20Saya%20butuh%20bantuan.";

$beranda = false;
$dokter = false;
$pasien = false;
$poli = false;
$obat = false;
$tambah_poli = false;
$ubah_foli = false;
$tambah_obat = false;
$ubah_obat = false;

if (isset($_GET['halaman'])) {
    $halaman = $_GET['halaman'];
    switch ($halaman) {
        case 'beranda':
            $beranda = true;
            break;
        case 'admin':
            $admin = true;
            break;
        case 'tambah_admin':
            $tambah_admin = true;
            break;
        case 'ubah_admin':
            $ubah_admin = true;
            break;
        case 'dokter':
            $dokter = true;
            break;
        case 'tambah_dokter':
            $tambah_dokter = true;
            break;
        case 'ubah_dokter':
            $ubah_dokter = true;
            break;
        case 'tambah_poli':
            $tambah_poli = true;
            break;
        case 'ubah_obat':
            $ubah_obat = true;
            break;
        case 'tambah_obat':
            $tambah_obat = true;
            break;
        case 'ubah_pasien':
            $ubah_pasien = true;
            break;
        case 'tambah_pasien':
            $tambah_pasien = true;
            break;
        case 'periksa_pasien':
            $ubah_periksa = true;
            break;
        case 'ubah__jadwal_periksa':
            $ubah_jadwal_periksa = true;
            break;
        case 'tambah_jadwal_periksa':
            $tambah_jadwal_periksa = true;
            break;
        case 'tambah_daftar':
            $tambah_daftar = true;
            break;
        case 'ubah_dokter':
            $ubah_dokter = true;
            break;
        case 'ubah_data_dokter':
            $ubah_data_dokter = true;
            break;
        case 'ubah_password_dokter':
            $ubah_password_dokter = true;
            break;
        case 'ubah_data_pasien':
            $ubah_data_pasien = true;
            break;
        case 'ubah_password_pasien':
            $ubah_password_pasien = true;
            break;
        case 'ubah_data_admin':
            $ubah_data_admin = true;
            break;
        case 'ubah_password_admin':
            $ubah_password_admin = true;
            break;
        case 'pasien':
            $pasien = true;
            break;
        case 'periksa':
            $periksa = true;
            break;
        case 'jadwal_periksa':
            $jadwal_periksa = true;
            break;
        case 'daftar':
            $daftar = true;
            break;
        case 'poli':
            $poli = true;
            break;
        case 'obat':
            $obat = true;
            break;
        case 'riwayat':
            $riwayat = true;
            break;
        case 'detail_riwayat':
            $detail_riwayat = true;
            break;
        case 'riwayat_pasien':
            $riwayat_pasien = true;
            break;
        case 'detail_riwayat_pasien':
            $detail_riwayat_pasien = true;
            break;
        default:
            $beranda = false;
            $dokter = false;
            $pasien = false;
            $tambah_poli = false;
            $ubah_poli = false;
            $tambah_dokter = false;
            $ubah_dokter = false;
    }
} else {
    $beranda = false;
    $dokter = false;
    $pasien = false;
    $tambah_poli = false;
    $ubah_poli = false;
    $tambah_dokter = false;
    $ubah_dokter = false;
}

?>

<div id="sidebar">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    <a href="index.php?halaman=beranda"><img src="./assets/compiled/svg/POLI.svg" alt="Logo" srcset="" /></a>
                </div>
                <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
                    <!-- Tema dan switch untuk dark mode -->
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--system-uicons" width="20" height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21">
                        <g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2" opacity=".3"></path>
                            <g transform="translate(-210 -1)">
                                <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                                <circle cx="220.5" cy="11.5" r="4"></circle>
                                <path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2"></path>
                            </g>
                        </g>
                    </svg>
                    <div class="form-check form-switch fs-6">
                        <input class="form-check-input me-0" type="checkbox" id="toggle-dark" style="cursor: pointer" />
                        <label class="form-check-label"></label>
                    </div>
                </div>
                <div class="sidebar-toggler x">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu</li>

                <li class="sidebar-item <?= $beranda ? 'active' : '' ?>">
                    <a href="index.php?halaman=beranda" class="sidebar-link">
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <?php if (isset($_SESSION['nama'])) : ?>
                    <?php if ($role == 'Dokter') : ?>
                        <!-- Hanya tampilkan menu dokter jika peran adalah dokter -->
                        <li class="sidebar-title">Profil</li>
                        <li class="sidebar-item <?= $ubah_data_dokter ? 'active' : '' ?>">
                            <a href="index.php?halaman=ubah_data_dokter" class="sidebar-link">
                                <i class="bi bi-person-workspace"></i>
                                <span>Ubah Data</span>
                            </a>
                        </li>
                        <li class="sidebar-item <?= $ubah_password_dokter ? 'active' : '' ?>">
                            <a href="index.php?halaman=ubah_password_dokter" class="sidebar-link">
                                <i class="bi bi-lock"></i>
                                <span>Ubah Password</span>
                            </a>
                        </li>
                        <li class="sidebar-item <?= $jadwal_periksa || $tambah_jadwal_periksa || $ubah_jadwal_periksa ? 'active' : '' ?>">
                            <a href="index.php?halaman=jadwal_periksa" class="sidebar-link">
                                <i class="bi bi-calendar-check"></i>
                                <span>Jadwal Periksa</span>
                            </a>
                        </li>
                        <li class="sidebar-title">Data</li>
                        <li class="sidebar-item <?= $periksa || $ubah_periksa ? 'active' : '' ?>">
                            <a href="index.php?halaman=periksa" class="sidebar-link">
                                <i class="bi bi-file-earmark-medical"></i>
                                <span>Periksa</span>
                            </a>
                        </li>
                        <li class="sidebar-item <?= $riwayat || $detail_riwayat ? 'active' : '' ?>">
                            <a href="index.php?halaman=riwayat" class="sidebar-link">
                                <i class="bi bi-archive"></i>
                                <span>Riwayat</span>
                            </a>
                        </li>
                    <?php elseif ($role == 'Admin') : ?>
                        <!-- Jika bukan dokter, tampilkan menu lainnya -->
                        <li class="sidebar-title">Profil</li>
                        <li class="sidebar-item <?= $ubah_data_admin ? 'active' : '' ?>">
                            <a href="index.php?halaman=ubah_data_admin" class="sidebar-link">
                                <i class="bi bi-person-workspace"></i>
                                <span>Ubah Data</span>
                            </a>
                        </li>
                        <li class="sidebar-item <?= $ubah_password_admin ? 'active' : '' ?>">
                            <a href="index.php?halaman=ubah_password_admin" class="sidebar-link">
                                <i class="bi bi-lock"></i>
                                <span>Ubah Password</span>
                            </a>
                        </li>
                        <li class="sidebar-title">Data</li>
                        <li class="sidebar-item <?= $admin || $tambah_admin || $ubah_admin ? 'active' : '' ?>">
                            <a href="index.php?halaman=admin" class="sidebar-link">
                                <i class="bi bi-person-lines-fill"></i>
                                <span>Admin</span>
                            </a>
                        </li>
                        <li class="sidebar-item <?= $dokter || $tambah_dokter || $ubah_dokter ? 'active' : '' ?>">
                            <a href="index.php?halaman=dokter" class="sidebar-link">
                                <i class="bi bi-person-workspace"></i>
                                <span>Dokter</span>
                            </a>
                        </li>
                        <li class="sidebar-item <?= $poli || $tambah_poli || $ubah_poli ? 'active' : '' ?>">
                            <a href="index.php?halaman=poli" class="sidebar-link">
                                <i class="bi bi-hospital"></i>
                                <span>Poliklinik</span>
                            </a>
                        </li>
                        <li class="sidebar-item <?= $pasien || $tambah_pasien || $ubah_pasien ? 'active' : '' ?>">
                            <a href="index.php?halaman=pasien" class="sidebar-link">
                                <i class="bi bi-person-fill"></i>
                                <span>Pasien</span>
                            </a>
                        </li>
                        <li class="sidebar-item <?= $obat || $tambah_obat || $ubah_obat ? 'active' : '' ?>">
                            <a href="index.php?halaman=obat" class="sidebar-link">
                                <i class="bi bi-capsule"></i>
                                <span>Obat</span>
                            </a>
                        </li>
                        <?php elseif ($role == 'Pasien') : ?>
                        <li class="sidebar-title">Profil</li>
                        <li class="sidebar-item <?= $ubah_data_pasien ? 'active' : '' ?>">
                            <a href="index.php?halaman=ubah_data_pasien" class="sidebar-link">
                                <i class="bi bi-person-workspace"></i>
                                <span>Ubah Data</span>
                            </a>
                        </li>
                        <li class="sidebar-item <?= $ubah_password_pasien ? 'active' : '' ?>">
                            <a href="index.php?halaman=ubah_password_pasien" class="sidebar-link">
                                <i class="bi bi-lock"></i>
                                <span>Ubah Password</span>
                            </a>
                        </li>
                        <li class="sidebar-title">Data</li>
                            <li class="sidebar-item <?= $daftar || $tambah_daftar ? 'active' : '' ?>">
                                <a href="index.php?halaman=daftar" class="sidebar-link">
                                    <i class="bi bi-bag-plus-fill"></i>
                                    <span>Daftar</span>
                                </a>
                            </li>
                            <li class="sidebar-item <?= $riwayat_pasien || $detail_riwayat_pasien ? 'active' : '' ?>">
                                <a href="index.php?halaman=riwayat_pasien" class="sidebar-link">
                                    <i class="bi bi-archive"></i>
                                    <span>Riwayat</span>
                                </a>
                            </li>
                        </li>
                    <?php endif; ?>
                <?php endif ?>
                <li class="sidebar-title">Call Center</li>
                    <li class="sidebar-item">
                        <a href="https://mail.google.com/mail/?view=cm&fs=1&to=support@example.com&su=Support%20Request&body=Halo,%20saya%20<?php echo $user_name_encoded; ?>,%20saya%20adalah%20<?php echo $user_role_encoded; ?>.%20Saya%20butuh%20bantuan." class="sidebar-link" target="_blank">
                            <i class="bi bi-envelope-fill"></i>
                            <span>Email</span>
                        </a>
                    </li>
                        <li class="sidebar-item">
                            <a href="<?php echo $whatsapp_link; ?>" class="sidebar-link" target="_blank">
                                <i class="bi bi-whatsapp"></i>
                                <span>WhatsApp</span>
                            </a>
                        </li>
                </li>   
            </ul>
        </div>
    </div>
</div>
