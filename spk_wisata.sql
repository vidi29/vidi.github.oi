-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 22, 2024 at 04:51 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spk_wisata`
--

-- --------------------------------------------------------

--
-- Table structure for table `hasil_seleksi`
--

CREATE TABLE `hasil_seleksi` (
  `id_hasil` int(11) NOT NULL,
  `id_wisata` int(11) NOT NULL,
  `vektor_s` float NOT NULL,
  `vektor_v` float NOT NULL,
  `ranking` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hasil_seleksi`
--

INSERT INTO `hasil_seleksi` (`id_hasil`, `id_wisata`, `vektor_s`, `vektor_v`, `ranking`) VALUES
(1, 1, 3.57, 0.0712, 7),
(2, 2, 3.52, 0.07, 8),
(3, 3, 3.67, 0.0731, 6),
(4, 4, 2.29, 0.0456, 15),
(5, 5, 4.28, 0.0852, 1),
(6, 6, 3.78, 0.0752, 4),
(7, 7, 3.91, 0.0779, 2),
(8, 8, 2.9, 0.0578, 11),
(9, 9, 3.45, 0.0688, 9),
(10, 10, 3.81, 0.0759, 3),
(11, 11, 2.84, 0.0566, 12),
(12, 12, 2.8, 0.0557, 13),
(13, 13, 2.41, 0.048, 14),
(14, 14, 3.28, 0.0653, 10),
(15, 15, 3.7, 0.0738, 5);

-- --------------------------------------------------------

--
-- Table structure for table `kriteria`
--

CREATE TABLE `kriteria` (
  `id_kriteria` int(11) NOT NULL,
  `nama_kriteria` varchar(50) NOT NULL,
  `bobot` float NOT NULL,
  `keterangan` enum('Benefit','Cost') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kriteria`
--

INSERT INTO `kriteria` (`id_kriteria`, `nama_kriteria`, `bobot`, `keterangan`) VALUES
(1, 'Lokasi', 40, 'Benefit'),
(2, 'Fasilitas', 30, 'Benefit'),
(3, 'Biaya', 20, 'Cost'),
(4, 'Keamanan', 10, 'Benefit');

-- --------------------------------------------------------

--
-- Table structure for table `kriteria_nilai`
--

CREATE TABLE `kriteria_nilai` (
  `id_kriteria_nilai` int(11) NOT NULL,
  `id_kriteria` int(11) NOT NULL,
  `kinerja` varchar(100) NOT NULL,
  `nilai_kriteria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kriteria_nilai`
--

INSERT INTO `kriteria_nilai` (`id_kriteria_nilai`, `id_kriteria`, `kinerja`, `nilai_kriteria`) VALUES
(1, 1, 'Sangat Strategis', 10),
(2, 1, 'Strategis', 8),
(3, 1, 'Cukup Strategis', 6),
(4, 1, 'Kurang Strategis', 4),
(5, 2, '4 Fasilitas', 10),
(6, 2, '3 Fasilitas', 8),
(7, 2, '2 Fasilitas', 6),
(8, 2, '1 Fasilitas', 4),
(9, 3, 'Biaya/1000', 0),
(10, 4, 'Security, Rak Loker, CCTV', 10),
(11, 4, 'Security, Rak Loker', 8),
(12, 4, 'Security', 6);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`) VALUES
(1, 'admin', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `wisata`
--

CREATE TABLE `wisata` (
  `id_wisata` int(11) NOT NULL,
  `nama_wisata` varchar(100) NOT NULL,
  `lokasi` enum('Sangat Strategis','Strategis','Cukup Strategis','Kurang Strategis') NOT NULL,
  `fasilitas` enum('4 Fasilitas','3 Fasilitas','2 Fasilitas','1 Fasilitas') NOT NULL,
  `biaya` int(11) NOT NULL,
  `keamanan` enum('Security, Rak Loker, CCTV','Security, Rak Loker','Security') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wisata`
--

INSERT INTO `wisata` (`id_wisata`, `nama_wisata`, `lokasi`, `fasilitas`, `biaya`, `keamanan`) VALUES
(1, 'Museum Purbakala Sangiran', 'Sangat Strategis', '2 Fasilitas', 8000, 'Security, Rak Loker, CCTV'),
(2, 'Gemolong Edupark (Taman Kota)', 'Cukup Strategis', '4 Fasilitas', 6000, 'Security, Rak Loker'),
(3, 'Permandian Air Panas Ngunut', 'Strategis', '2 Fasilitas', 4000, 'Security, Rak Loker'),
(4, 'Gunung Kemukus', 'Kurang Strategis', '1 Fasilitas', 5000, 'Security'),
(5, 'Ndayu Park', 'Sangat Strategis', '3 Fasilitas', 5000, 'Security, Rak Loker, CCTV'),
(6, 'Zensho Family Karaoke ', 'Strategis', '2 Fasilitas', 3000, 'Security'),
(7, 'Kedung Grujug', 'Strategis', '4 Fasilitas', 7000, 'Security, Rak Loker, CCTV'),
(8, 'Taman Kridoanggo Sragen', 'Kurang Strategis', '3 Fasilitas', 5000, 'Security, Rak Loker'),
(9, 'Gemolong Edupark (Taman Kota)', 'Sangat Strategis', '1 Fasilitas', 4000, 'Security'),
(10, 'Waduk Kembangan', 'Strategis', '4 Fasilitas', 8000, 'Security, Rak Loker, CCTV'),
(11, 'Indo Waterboom', 'Cukup Strategis', '2 Fasilitas', 7000, 'Security'),
(12, 'Komplek Rumah Tua Belanda', 'Kurang Strategis', '3 Fasilitas', 6000, 'Security, Rak Loker'),
(13, 'Museum Manusia Purba Klaster Bukuran', 'Kurang Strategis', '1 Fasilitas', 5000, 'Security, Rak Loker, CCTV'),
(14, 'Taman Bunga Ganesha Sukowati', 'Sangat Strategis', '1 Fasilitas', 6000, 'Security, Rak Loker'),
(15, 'Alaska Kedawung', 'Cukup Strategis', '4 Fasilitas', 4000, 'Security');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hasil_seleksi`
--
ALTER TABLE `hasil_seleksi`
  ADD PRIMARY KEY (`id_hasil`),
  ADD KEY `id_wisata` (`id_wisata`);

--
-- Indexes for table `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id_kriteria`);

--
-- Indexes for table `kriteria_nilai`
--
ALTER TABLE `kriteria_nilai`
  ADD PRIMARY KEY (`id_kriteria_nilai`),
  ADD KEY `id_kriteria` (`id_kriteria`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `wisata`
--
ALTER TABLE `wisata`
  ADD PRIMARY KEY (`id_wisata`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hasil_seleksi`
--
ALTER TABLE `hasil_seleksi`
  MODIFY `id_hasil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id_kriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kriteria_nilai`
--
ALTER TABLE `kriteria_nilai`
  MODIFY `id_kriteria_nilai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wisata`
--
ALTER TABLE `wisata`
  MODIFY `id_wisata` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hasil_seleksi`
--
ALTER TABLE `hasil_seleksi`
  ADD CONSTRAINT `hasil_seleksi_ibfk_1` FOREIGN KEY (`id_wisata`) REFERENCES `wisata` (`id_wisata`);

--
-- Constraints for table `kriteria_nilai`
--
ALTER TABLE `kriteria_nilai`
  ADD CONSTRAINT `kriteria_nilai_ibfk_1` FOREIGN KEY (`id_kriteria`) REFERENCES `kriteria` (`id_kriteria`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
