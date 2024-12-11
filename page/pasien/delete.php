<?php
include "./function/connection.php";

try {
    $message = "";
    $success = FALSE;
    $error = FALSE;

    if (isset($_GET['id'])) {
        $id = intval($_GET['id']); // Pastikan ID berupa integer untuk keamanan

        // Ambil data dokter beserta user ID
        $select = mysqli_query($connection, 
            "SELECT user_id FROM pasien WHERE id = '$id'");
        $data = mysqli_fetch_assoc($select);

        if (!$data) {
            // Jika data tidak ditemukan, kembalikan ke halaman dokter
            header('Location: index.php?halaman=pasien');
            exit();
        }

        $userId = intval($data['user_id']); // Ambil ID user dari dokter

        // Mulai transaksi
        mysqli_begin_transaction($connection);

        try {
            // Hapus data dokter
            $deleteDokter = mysqli_query($connection, 
                "DELETE FROM pasien WHERE id = '$id'");
            if (!$deleteDokter) {
                throw new Exception("Gagal menghapus data pasien.");
            }

            // Hapus data user
            $deleteUser = mysqli_query($connection, 
                "DELETE FROM user WHERE id = '$userId'");
            if (!$deleteUser) {
                throw new Exception("Gagal menghapus data user.");
            }

            // Commit transaksi jika semua berhasil
            mysqli_commit($connection);

            $message = "Berhasil menghapus data pasien.";
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
                window.location.href = 'index.php?halaman=pasien';
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
                window.location.href = 'index.php?halaman=pasien';
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
        window.location.href = 'index.php?halaman=pasien';
    })
    </script>
    ";
}
