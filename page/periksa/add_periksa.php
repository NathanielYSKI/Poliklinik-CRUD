<?php
include "./function/connection.php";

try {
    $message = "";

    // Ambil `id_periksa` dari URL (bukan id_daftarpoli)
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        throw new Exception("ID Periksa tidak ditemukan di URL.");
    }
    $id_daftarpoli = htmlspecialchars($_GET['id']);

    // Ambil semua obat dari tabel obat
    $query_obat = mysqli_query($connection, "SELECT * FROM obat");
    if (!$query_obat) {
        throw new Exception("Gagal mengambil data obat.");
    }

    if (isset($_POST['submit'])) {
        // Ambil data dari form
        $tgl_periksa = htmlspecialchars($_POST['tgl_periksa']);
        $catatan = htmlspecialchars($_POST['catatan']);
        $biaya_periksa = htmlspecialchars($_POST['biaya_periksa']);
        $obat_ids = $_POST['obat_ids'] ?? []; // Obat yang dipilih
        $obat_quantities = $_POST['obat_quantity'] ?? []; // Quantity obat yang dipilih

        // Validasi data wajib
        if (empty($tgl_periksa) || empty($catatan) || empty($biaya_periksa)) {
            throw new Exception("Semua field wajib diisi.");
        }

        // Validasi obat yang dipilih
        if (empty($obat_ids)) {
            throw new Exception("Pilih setidaknya satu obat.");
        }

        // Mulai transaksi
        mysqli_begin_transaction($connection);

        try {
            // Tambahkan data ke tabel periksa
            $query_periksa = mysqli_query($connection, "INSERT INTO periksa (id_daftarpoli, tgl_periksa, catatan, biaya_periksa) 
                                                      VALUES ('$id_daftarpoli', '$tgl_periksa', '$catatan', '$biaya_periksa')");
            if (!$query_periksa) {
                throw new Exception("Gagal menambahkan data ke tabel periksa.");
            }

            // Ambil ID periksa yang baru ditambahkan
            $id_periksa_baru = mysqli_insert_id($connection);

            // Tambahkan detail periksa (obat)
            foreach ($obat_ids as $index => $id_obat) {
                $quantity = $obat_quantities[$id_obat]; // Ambil quantity obat
                $query_detail = mysqli_query($connection, "INSERT INTO detail_periksa (id_periksa, id_obat, quantity) 
                                                        VALUES ('$id_periksa_baru', '$id_obat', '$quantity')");
                if (!$query_detail) {
                    throw new Exception("Gagal menambahkan detail obat ke tabel periksa.");
                }
            }

            $query_update_status = mysqli_query($connection, "UPDATE daftar_poli SET status = 1 WHERE id = '$id_daftarpoli'");
            if (!$query_update_status) {
                throw new Exception("Gagal mengubah status di tabel daftar_poli.");
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
                window.location.href = 'index.php?halaman=periksa';
            });
            </script>
            ";
        } catch (Exception $e) {
            // Rollback jika ada error
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
                window.location.href = 'index.php?halaman=periksa';
            });
            </script>
            ";
        }
    }
} catch (\Throwable $th) {
    $message = "Gagal menambahkan data periksa: " . $th->getMessage();
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
        window.location.href = 'index.php?halaman=periksa';
    });
    </script>
    ";
}
?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tambah Data Periksa</h3>
                <p class="text-subtitle text-muted">
                    Halaman untuk menambahkan data pemeriksaan
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.php?halaman=periksa">Data Periksa</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Tambah Periksa
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
                    <!-- Input untuk tgl_periksa -->
                    <div class="form-floating mb-3">
                        <input type="date" class="form-control" id="tgl_periksa" name="tgl_periksa" required>
                        <label for="tgl_periksa">Tanggal Periksa</label>
                    </div>
                    <!-- Input untuk catatan -->
                    <div class="form-floating mb-3">
                        <textarea class="form-control" id="catatan" name="catatan" rows="3" required></textarea>
                        <label for="catatan">Catatan</label>
                    </div>
                    <!-- Input untuk biaya_periksa -->
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="biaya_periksa" name="biaya_periksa" value="150000" readonly>
                        <label for="biaya_periksa">Biaya Periksa</label>
                    </div>

                    <!-- Pilih Obat -->
                    <div class="mb-3">
                        <label for="obat_ids">Pilih Obat</label>

                        <!-- Search Box untuk mencari nama obat -->
                        <input type="text" id="search-obat" class="form-control mb-2" placeholder="Cari obat...">

                        <!-- Daftar Obat -->
                        <div style="max-height: 200px; overflow-y: auto; padding-left: 10px;" id="obat-list">
                            <?php while ($row = mysqli_fetch_assoc($query_obat)) { ?>
                                <div class="obat-item">
                                    <input type="checkbox" class="obat-checkbox" name="obat_ids[]" value="<?= $row['id'] ?>" 
                                        data-nama="<?= $row['nama_obat'] ?>" data-harga="<?= $row['harga'] ?>"> 
                                    <?= $row['nama_obat'] ?> (Rp <?= number_format($row['harga'], 0, ',', '.') ?>)
                                    
                                    <!-- Input Quantity -->
                                    <input type="number" class="obat-quantity" name="obat_quantity[<?= $row['id'] ?>]" value="1" min="1" style="width: 60px; display: none;">
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- Daftar Obat yang Dipilih -->
                    <div class="mb-3">
                        <h5>Obat yang Dipilih:</h5>
                        <ul id="selected-obat-list"></ul>
                    </div>

                    <!-- Total Harga -->
                    <div class="mb-3">
                        <h5>Total Harga: Rp <span id="total-harga">150,000</span></h5>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<script>
    // Fungsi untuk menghitung dan memperbarui total harga
    function updateTotalHarga() {
        const totalHargaElement = document.getElementById('total-harga');
        let totalHarga = 0;
        
        // Tambahkan biaya periksa
        const biayaPeriksa = parseInt(document.getElementById('biaya_periksa').value, 10);
        totalHarga += biayaPeriksa;
        
        // Loop untuk setiap item dalam daftar obat yang dipilih
        let selectedObat = '';
        document.querySelectorAll('.obat-checkbox:checked').forEach(function(checkbox) {
            const obatNama = checkbox.getAttribute('data-nama');
            const obatHarga = parseInt(checkbox.getAttribute('data-harga'), 10);
            const quantityInput = checkbox.closest('.obat-item').querySelector('.obat-quantity');
            const quantity = parseInt(quantityInput.value, 10);

            // Tambahkan nama obat yang dipilih ke list
            selectedObat += `<li>${obatNama} x${quantity}</li>`;

            // Tambahkan harga total (harga x quantity)
            totalHarga += obatHarga * quantity;
        });

        // Perbarui nama obat yang dipilih
        document.getElementById('selected-obat-list').innerHTML = selectedObat;

        // Perbarui total harga yang ditampilkan
        totalHargaElement.textContent = totalHarga.toLocaleString();
    }

    // Event listener untuk checkbox obat
    document.querySelectorAll('.obat-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            // Tampilkan input quantity ketika checkbox dipilih
            const quantityInput = checkbox.closest('.obat-item').querySelector('.obat-quantity');
            quantityInput.style.display = checkbox.checked ? 'inline' : 'none';

            updateTotalHarga();
        });
    });

    // Event listener untuk input quantity
    document.querySelectorAll('.obat-quantity').forEach(function(input) {
        input.addEventListener('input', updateTotalHarga);
    });

    // Event listener untuk search box
    document.getElementById('search-obat').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        document.querySelectorAll('.obat-item').forEach(function(item) {
            const itemName = item.querySelector('label').textContent.toLowerCase();
            item.style.display = itemName.includes(searchTerm) ? 'block' : 'none';
        });
    });
</script>
