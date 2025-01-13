<?php
include "./function/connection.php";

try {
    $message = "";
    $success = FALSE;
    $error = FALSE;

    if (isset($_GET['id'])) {
        $id = intval($_GET['id']); // Pastikan ID berupa integer untuk keamanan

        // Mulai transaksi
        mysqli_begin_transaction($connection);

        try {
            // Hapus data dokter
            $deletePoli = mysqli_query($connection, 
                "UPDATE poli SET status = '0' WHERE id = '$id'");
            if (!$deletePoli) {
                throw new Exception("Gagal menghapus data dokter.");
            }

            // Commit transaksi jika semua berhasil
            mysqli_commit($connection);

            $message = "Berhasil menghapus data poli.";
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
                window.location.href = 'index.php?halaman=poli';
            })
            </script>
            ";
        } catch (Exception $e) {
            // Rollback transaksi jika ada error
            mysqli_rollback($connection);

            $message = "Gagal menghapus data: " . $e->getMessage();
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
                window.location.href = 'index.php?halaman=poli';
            })
            </script>
            ";
        }
    } else {
        $message = "ID tidak ditemukan.";
    }
} catch (Exception $th) {
    echo "
    <script>
    Swal.fire({
        title: 'Gagal',
        text: 'Server error!',
        icon: 'error',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true,
    }).then(() => {
        window.location.href = 'index.php?halaman=poli';
    })
    </script>
    ";
}
