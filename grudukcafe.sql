-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 16, 2025 at 06:37 AM
-- Server version: 8.2.0
-- PHP Version: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `grudukcafe`
--

-- --------------------------------------------------------

--
-- Table structure for table `absen_karyawan`
--

CREATE TABLE `absen_karyawan` (
  `id_absen` int NOT NULL,
  `id_detail` int NOT NULL,
  `tanggal_absen` datetime DEFAULT CURRENT_TIMESTAMP,
  `action` enum('diterima','ditolak','pending') DEFAULT 'pending',
  `foto_absen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `absen_karyawan`
--

INSERT INTO `absen_karyawan` (`id_absen`, `id_detail`, `tanggal_absen`, `action`, `foto_absen`) VALUES
(10, 1, '2025-10-15 12:45:54', 'diterima', 'absen_1_2025-10-15_05-45-54.png');

-- --------------------------------------------------------

--
-- Table structure for table `akun_login`
--

CREATE TABLE `akun_login` (
  `id_login` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','hrd','karyawan') NOT NULL DEFAULT 'karyawan',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `akun_login`
--

INSERT INTO `akun_login` (`id_login`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'maulana', '$2y$10$YZRqZCoB6.yRI270I8ZEa.5bGu7EV.pI.tgT.oRkGWUOh2qoP3DPq', 'karyawan', '2025-10-15 04:51:16'),
(2, 'fahras', '$2y$10$9MTL9skPRS60ui/rHgzL.umADTKcGbe5nAfPi4lAj846Oj5XWQI1K', 'hrd', '2025-10-15 04:52:23');

-- --------------------------------------------------------

--
-- Table structure for table `cuti_izin_karyawan`
--

CREATE TABLE `cuti_izin_karyawan` (
  `id_cuti` int NOT NULL,
  `id_detail` int NOT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_akhir` date DEFAULT NULL,
  `tipe` enum('izin','cuti','sakit') DEFAULT NULL,
  `keterangan` text,
  `tanggal_pengajuan` datetime DEFAULT CURRENT_TIMESTAMP,
  `action` enum('diterima','ditolak','pending') DEFAULT 'pending',
  `foto_cuti_izin` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cuti_izin_karyawan`
--

INSERT INTO `cuti_izin_karyawan` (`id_cuti`, `id_detail`, `tanggal_mulai`, `tanggal_akhir`, `tipe`, `keterangan`, `tanggal_pengajuan`, `action`, `foto_cuti_izin`) VALUES
(6, 1, '2025-10-15', '2025-10-16', 'cuti', 'istri melahirkan', '2025-10-15 14:38:59', 'diterima', '');

-- --------------------------------------------------------

--
-- Table structure for table `detail_akun`
--

CREATE TABLE `detail_akun` (
  `id_detail` int NOT NULL,
  `id_login` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` text,
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('Laki-Laki','Perempuan') NOT NULL,
  `agama` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `departemen` varchar(100) DEFAULT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `foto_profile` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `detail_akun`
--

INSERT INTO `detail_akun` (`id_detail`, `id_login`, `nama`, `alamat`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `email`, `departemen`, `jabatan`, `status`, `role`, `foto_profile`) VALUES
(1, 1, 'Maulana Afrizki', 'Perumahan Sidoarjo Indah No 7, Sidoarjo', 'Jakarta', '2009-12-30', 'Laki-Laki', 'Islam', 'mafrizki@gmail.com', 'Store Manager', 'Kepala Departemen', 'kepala', 'karyawan', 'profile_1_2025-10-16_02-14-41.jpg'),
(2, 2, 'Fahras Zeilicha', 'Perumahan Citraland No 5, Surabaya', 'Surabaya', '2010-12-30', 'Perempuan', 'Islam', 'fahrasdt16@gmail.com', 'Human Resources', 'HRD', 'Aktif', 'hrd', 'profile_2_2025-10-15_07-01-33.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absen_karyawan`
--
ALTER TABLE `absen_karyawan`
  ADD PRIMARY KEY (`id_absen`),
  ADD KEY `id_detail` (`id_detail`);

--
-- Indexes for table `akun_login`
--
ALTER TABLE `akun_login`
  ADD PRIMARY KEY (`id_login`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `cuti_izin_karyawan`
--
ALTER TABLE `cuti_izin_karyawan`
  ADD PRIMARY KEY (`id_cuti`),
  ADD KEY `id_detail` (`id_detail`);

--
-- Indexes for table `detail_akun`
--
ALTER TABLE `detail_akun`
  ADD PRIMARY KEY (`id_detail`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_login` (`id_login`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absen_karyawan`
--
ALTER TABLE `absen_karyawan`
  MODIFY `id_absen` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `akun_login`
--
ALTER TABLE `akun_login`
  MODIFY `id_login` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `cuti_izin_karyawan`
--
ALTER TABLE `cuti_izin_karyawan`
  MODIFY `id_cuti` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `detail_akun`
--
ALTER TABLE `detail_akun`
  MODIFY `id_detail` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absen_karyawan`
--
ALTER TABLE `absen_karyawan`
  ADD CONSTRAINT `absen_karyawan_ibfk_1` FOREIGN KEY (`id_detail`) REFERENCES `detail_akun` (`id_detail`) ON DELETE CASCADE;

--
-- Constraints for table `cuti_izin_karyawan`
--
ALTER TABLE `cuti_izin_karyawan`
  ADD CONSTRAINT `cuti_izin_karyawan_ibfk_1` FOREIGN KEY (`id_detail`) REFERENCES `detail_akun` (`id_detail`) ON DELETE CASCADE;

--
-- Constraints for table `detail_akun`
--
ALTER TABLE `detail_akun`
  ADD CONSTRAINT `detail_akun_ibfk_1` FOREIGN KEY (`id_login`) REFERENCES `akun_login` (`id_login`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
