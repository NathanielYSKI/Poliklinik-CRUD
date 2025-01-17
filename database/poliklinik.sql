-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 17 Jan 2025 pada 04.41
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `poliklinik`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id`, `nama`, `alamat`, `no_hp`, `user_id`, `status`) VALUES
(1, 'Nathaniel', 'Jagalan no 88', '089509466544', 26, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `daftar_poli`
--

CREATE TABLE `daftar_poli` (
  `id` int(11) NOT NULL,
  `id_pasien` int(11) NOT NULL,
  `id_jadwal` int(11) NOT NULL,
  `keluhan` text NOT NULL,
  `no_antrian` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `daftar_poli`
--

INSERT INTO `daftar_poli` (`id`, `id_pasien`, `id_jadwal`, `keluhan`, `no_antrian`, `status`) VALUES
(1, 2, 1, 'hehe', 1, 1),
(3, 2, 1, 'Sakit Kepala', 2, 1),
(4, 2, 1, 'Sakit Kepala', 3, 1),
(5, 2, 1, 'sakit mata', 4, 1),
(6, 2, 1, 'Sakit mata', 5, 1),
(7, 2, 1, 'Mata merah', 1, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_periksa`
--

CREATE TABLE `detail_periksa` (
  `id` int(11) NOT NULL,
  `id_periksa` int(11) NOT NULL,
  `id_obat` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail_periksa`
--

INSERT INTO `detail_periksa` (`id`, `id_periksa`, `id_obat`, `quantity`) VALUES
(3, 5, 3, 0),
(4, 6, 3, 0),
(5, 6, 4, 0),
(6, 7, 3, 0),
(7, 7, 4, 0),
(8, 9, 3, 3),
(9, 10, 3, 3),
(10, 10, 4, 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `dokter`
--

CREATE TABLE `dokter` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `id_poli` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `dokter`
--

INSERT INTO `dokter` (`id`, `nama`, `alamat`, `no_hp`, `id_poli`, `user_id`, `status`) VALUES
(8, 'Elkaf', 'Hawa', '089567436455', 1, 4, 1),
(9, 'Budi', 'Arjuna No 3, Semarang', '08958436389', 1, 5, 1),
(10, 'Eva', 'Arjuna', '081974563422', 1, 6, 1),
(11, 'Andi', 'Arjuna No 1, Semarang', '08164587821', 2, 17, 1),
(13, 'Edi', 'Hawa', '978679807967', 2, 21, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal_periksa`
--

CREATE TABLE `jadwal_periksa` (
  `id` int(11) NOT NULL,
  `id_dokter` int(11) NOT NULL,
  `hari` varchar(10) NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jadwal_periksa`
--

INSERT INTO `jadwal_periksa` (`id`, `id_dokter`, `hari`, `jam_mulai`, `jam_selesai`, `status`) VALUES
(1, 9, 'Selasa', '10:00:00', '15:30:00', 'Aktif'),
(2, 9, 'Rabu', '12:00:00', '15:00:00', 'Tidak Aktif'),
(3, 9, 'Jumat', '07:00:00', '12:00:00', 'Tidak Aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `obat`
--

CREATE TABLE `obat` (
  `id` int(11) NOT NULL,
  `nama_obat` varchar(255) NOT NULL,
  `harga` int(11) NOT NULL,
  `kemasan` varchar(255) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `obat`
--

INSERT INTO `obat` (`id`, `nama_obat`, `harga`, `kemasan`, `keterangan`, `status`) VALUES
(3, 'Panadol', 1500, 'Strip', 'Diminum pagi dan malam', 1),
(4, 'Paramex', 2000, 'Strip', 'Diminimu dua kali sehari', 0),
(6, 'Komix', 2000, 'Sachet', 'Obat batuk', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pasien`
--

CREATE TABLE `pasien` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `no_ktp` bigint(20) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `no_rm` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pasien`
--

INSERT INTO `pasien` (`id`, `nama`, `alamat`, `no_ktp`, `no_hp`, `no_rm`, `user_id`, `status`) VALUES
(2, 'Ari', 'Hawa', 3374022354120002, '081978235633', 2147483647, 8, 0),
(6, 'Evan', 'Arjuna No 1, Semarang', 3374022354120001, '89509466544', 2025010003, 18, 0),
(7, 'Aris', 'Arjuna No 1, Semarang', 3374022354120001, '089509466544', 2025010007, 23, 1),
(8, 'Ivan', 'Arjuna No 3, Semarang', 3374022354120003, '089509466555', 2025010008, 24, 1),
(9, 'Keti', 'Hawa', 3374022354120005, '08958436389', 2025010009, 27, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `periksa`
--

CREATE TABLE `periksa` (
  `id` int(11) NOT NULL,
  `id_daftarpoli` int(11) NOT NULL,
  `tgl_periksa` date NOT NULL,
  `catatan` text NOT NULL,
  `biaya_periksa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `periksa`
--

INSERT INTO `periksa` (`id`, `id_daftarpoli`, `tgl_periksa`, `catatan`, `biaya_periksa`) VALUES
(5, 3, '2024-12-18', 'Istirahat yang cukup', 150000),
(6, 1, '2024-12-24', 'Istirahat', 150000),
(7, 4, '2024-12-24', 'Istirahat', 150000),
(9, 5, '2025-01-07', 'istirahat', 150000),
(10, 6, '2025-01-07', 'istirahat', 150000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `poli`
--

CREATE TABLE `poli` (
  `id` int(11) NOT NULL,
  `nama_poli` varchar(255) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `poli`
--

INSERT INTO `poli` (`id`, `nama_poli`, `keterangan`, `status`) VALUES
(1, 'Poli Mata', 'Buka jam 08:00-12:00', 1),
(2, 'Poli Telinga', 'Buka jam 08:00-11:00', 1),
(3, 'Poli Mulut', 'Buka jam 08:00-12:00', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(150) NOT NULL,
  `role` varchar(10) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id`, `nama`, `username`, `password`, `role`, `foto`, `status`) VALUES
(1, 'Nathan', 'Nathan', '$2y$10$5Kzv80J15Z/dElyjxyv2o.z0r2wVxiT2QurLwhXajy2RDtJlsiI0K', 'Admin', 'uploads/profiles/1.jpg', 1),
(4, 'Elkaf', 'Elkaf', '$2y$10$oczl3oKjnNbhGJOuX1CA.OIQBY6UNYQ9Yuxu0frwVnTM1c5XdgjxK', 'Dokter', 'uploads/profiles/8.jpg', 1),
(5, 'Budi', 'Budi', '$2y$10$8a4uB2fOup6.0CkjGKXRIufXNPJQM8Yd5Zsh4rLwU7XRR6Y9eW946', 'Dokter', 'uploads/profiles/2.jpg', 1),
(6, 'Eva', 'Eva', '$2y$10$QDZ43cMDdZ9lJvBkle1.4O79tURtZ1L5SKIoPTCPLPifbyveOxn7m', 'Dokter', 'uploads/profiles/1.jpg', 1),
(8, 'Ari', 'Ari', '$2y$10$tOsdNnuu3OvvIwCQXGa9tedWVZU490qU4/Sw6LMIJ4UPmxveUYxyW', 'Pasien', 'uploads/profiles/1.jpg', 0),
(17, 'Andi', 'Andi', '$2y$10$rOKEwqUeljm5DvF7QmfLkOQ2CZz.947NGt1mBlZ5d/xehYUhBh5Lq', 'Dokter', 'uploads/profiles/1.jpg', 1),
(18, 'Evan', 'evan', '$2y$10$0BYgt93JR10wqIXa6qrT8ua61oBZot6N7amYbiczvckG.GAaS0he2', 'Pasien', 'uploads/profiles/677cd41377fcd4.13482439.png', 0),
(21, 'Edi', 'edi', '$2y$10$6P6fkMtwvQ3dNr/sWMMQl.D8ZTjusVltV6fRML.HRaqvm3sf.1bSS', 'Dokter', 'uploads/profiles/677cdac135f8b4.52363749.jpg', 0),
(23, 'Aris', 'aris', '$2y$10$ez1Izf8VmwASpydv/cVrQO8VYn25sr.5G2CKSO1EnIW83WQZVJMo.', 'Pasien', 'uploads/profiles/8.jpg', 1),
(24, 'Ivan', 'ivan', '$2y$10$CUyXB6cJ2W.fhqvznNFo8O/.ihWZlFF5SEJ8cUlCTduX/xNavK4KG', 'Pasien', 'uploads/profiles/6789c0b62a1510.65735770.jpg', 1),
(26, 'Nathaniel', 'nathaniel', '$2y$10$etyCxfx9gkW/lIed9Kq3WuoaQ3aNLcEgawQ6jPJdFkypnP5cA1tZq', 'Admin', 'uploads/profiles/2.jpg', 1),
(27, 'Keti', 'Keti', '$2y$10$Rq2rxs9cK55FXc3BFyTCN.zUr9FHcupT1mAKUXtsTsUACQKyNVVhG', 'Pasien', 'uploads/profiles/5.jpg', 1);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `daftar_poli`
--
ALTER TABLE `daftar_poli`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pasien` (`id_pasien`,`id_jadwal`),
  ADD KEY `id_jadwal` (`id_jadwal`);

--
-- Indeks untuk tabel `detail_periksa`
--
ALTER TABLE `detail_periksa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_periksa` (`id_periksa`,`id_obat`),
  ADD KEY `id_obat` (`id_obat`);

--
-- Indeks untuk tabel `dokter`
--
ALTER TABLE `dokter`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `id_poli` (`id_poli`);

--
-- Indeks untuk tabel `jadwal_periksa`
--
ALTER TABLE `jadwal_periksa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_dokter` (`id_dokter`);

--
-- Indeks untuk tabel `obat`
--
ALTER TABLE `obat`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pasien`
--
ALTER TABLE `pasien`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `user_id_2` (`user_id`);

--
-- Indeks untuk tabel `periksa`
--
ALTER TABLE `periksa`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `poli`
--
ALTER TABLE `poli`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `daftar_poli`
--
ALTER TABLE `daftar_poli`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `detail_periksa`
--
ALTER TABLE `detail_periksa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `dokter`
--
ALTER TABLE `dokter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `jadwal_periksa`
--
ALTER TABLE `jadwal_periksa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `obat`
--
ALTER TABLE `obat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `pasien`
--
ALTER TABLE `pasien`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `periksa`
--
ALTER TABLE `periksa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `poli`
--
ALTER TABLE `poli`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `daftar_poli`
--
ALTER TABLE `daftar_poli`
  ADD CONSTRAINT `daftar_poli_ibfk_1` FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal_periksa` (`id`),
  ADD CONSTRAINT `daftar_poli_ibfk_2` FOREIGN KEY (`id_pasien`) REFERENCES `pasien` (`id`);

--
-- Ketidakleluasaan untuk tabel `detail_periksa`
--
ALTER TABLE `detail_periksa`
  ADD CONSTRAINT `detail_periksa_ibfk_1` FOREIGN KEY (`id_periksa`) REFERENCES `periksa` (`id`),
  ADD CONSTRAINT `detail_periksa_ibfk_2` FOREIGN KEY (`id_obat`) REFERENCES `obat` (`id`);

--
-- Ketidakleluasaan untuk tabel `dokter`
--
ALTER TABLE `dokter`
  ADD CONSTRAINT `dokter_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `dokter_ibfk_2` FOREIGN KEY (`id_poli`) REFERENCES `poli` (`id`);

--
-- Ketidakleluasaan untuk tabel `jadwal_periksa`
--
ALTER TABLE `jadwal_periksa`
  ADD CONSTRAINT `jadwal_periksa_ibfk_1` FOREIGN KEY (`id_dokter`) REFERENCES `dokter` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
