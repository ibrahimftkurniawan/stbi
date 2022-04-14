-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 14, 2022 at 05:34 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.3.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbstbi`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbberita`
--

CREATE TABLE `tbberita` (
  `Id` int(11) NOT NULL,
  `Judul` varchar(100) NOT NULL,
  `Berita` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbberita`
--

INSERT INTO `tbberita` (`Id`, `Judul`, `Berita`) VALUES
(1, 'Dokumen 1', 'Perusahaan Daerah Air Minum (PDAM) Kota Magelang memiliki pekerjaan rumah yang cukup kompleks yakni kehilangan air sebanyak 50 persen dari total yang diproduksi. Tingginya potensi air bersih yang hilang ini membuat pendapatkan perusahaan pelat merah inipun tidak maksimal.'),
(2, 'Dokumen 2', '\"Faktor fisik adalah dari pipanya yang bocor dan tidak kelihatan. Karena kalau kelihatan, pasti langsung kita tangani, \" katanya, Senin (2/11/2020) usai pelantikan direktur utama PDAM Kota Magelang di Pendopo Pengabdian Rumah Dinas Wali Kota Magelang. Angka ini diketahui setelah terpasang meter induk di setiap sumber.'),
(3, 'Dokumen 3', 'Koordinasikan dengan Dewan Pengawas, didampingi Asisten 2 dan Kabag Perekonomian. Lalu tenaga-tenaga muda profesional yang ada di PDAM harus dikerahkan untuk terus merumuskan,\" katanya, Senin (2/11). Dikatakannya, sistem pengelolaan air minum di Kota Magelang sudah dibangun sejak zaman Belanda dan saat ini tinggal meneruskan saja, namun kenyataannya mengelola air itu tidak sesederhana yang dibayangkan. Terlebih ada laporan estimasi kebocoran sudah mencapai hampir 50%.');

-- --------------------------------------------------------

--
-- Table structure for table `tbcache`
--

CREATE TABLE `tbcache` (
  `Id` int(11) NOT NULL,
  `Query` varchar(100) NOT NULL,
  `DocId` int(11) NOT NULL,
  `Value` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbcache`
--

INSERT INTO `tbcache` (`Id`, `Query`, `DocId`, `Value`) VALUES
(3, 'asisten uangan', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbindex`
--

CREATE TABLE `tbindex` (
  `Id` int(11) NOT NULL,
  `Term` varchar(30) NOT NULL,
  `DocId` int(11) NOT NULL,
  `Count` int(11) NOT NULL,
  `Bobot` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbindex`
--

INSERT INTO `tbindex` (`Id`, `Term`, `DocId`, `Count`, `Bobot`) VALUES
(1, 'perusahaan', 1, 2, 0),
(2, 'daerah', 1, 1, 0),
(3, 'air', 1, 3, 0),
(4, 'minum', 1, 1, 0),
(5, 'pdam', 1, 1, 0),
(6, 'kota', 1, 1, 0),
(7, 'magelang', 1, 1, 0),
(8, 'memiliki', 1, 1, 0),
(9, 'perjaan', 1, 1, 0),
(10, 'rumah', 1, 1, 0),
(11, 'cukup', 1, 1, 0),
(12, 'kompleks', 1, 1, 0),
(13, 'yakni', 1, 1, 0),
(14, 'hilangan', 1, 1, 0),
(15, 'sebanyak', 1, 1, 0),
(16, '50', 1, 1, 0),
(17, 'persen', 1, 1, 0),
(18, 'total', 1, 1, 0),
(19, 'diproduksi', 1, 1, 0),
(20, 'tingginya', 1, 1, 0),
(21, 'potensi', 1, 1, 0),
(22, 'bersih', 1, 1, 0),
(23, 'hilang', 1, 1, 0),
(24, 'membuat', 1, 1, 0),
(25, 'pendapatkan', 1, 1, 0),
(26, 'pelat', 1, 1, 0),
(27, 'merah', 1, 1, 0),
(28, 'pun', 1, 1, 0),
(29, 'tidak', 1, 1, 0),
(30, 'maksimal', 1, 1, 0),
(31, 'faktor', 2, 1, 0),
(32, 'fisik', 2, 1, 0),
(33, 'pipanya', 2, 1, 0),
(34, 'bocor', 2, 1, 0),
(35, 'tidak', 2, 1, 0),
(36, 'lihatan', 2, 2, 0),
(37, 'karena', 2, 1, 0),
(38, 'kalau', 2, 1, 0),
(39, 'pasti', 2, 1, 0),
(40, 'langsung', 2, 1, 0),
(41, 'kita', 2, 1, 0),
(42, 'tangani', 2, 1, 0),
(43, 'katanya', 2, 1, 0),
(44, 'senin', 2, 1, 0),
(45, '2', 2, 1, 0),
(46, '11', 2, 1, 0),
(47, '2020', 2, 1, 0),
(48, 'usai', 2, 1, 0),
(49, 'pelantikan', 2, 1, 0),
(50, 'direktur', 2, 1, 0),
(51, 'utama', 2, 1, 0),
(52, 'pdam', 2, 1, 0),
(53, 'kota', 2, 2, 0),
(54, 'magelang', 2, 2, 0),
(55, 'di', 2, 2, 0),
(56, 'pendopo', 2, 1, 0),
(57, 'pengabn', 2, 1, 0),
(58, 'rumah', 2, 1, 0),
(59, 'dinas', 2, 1, 0),
(60, 'wali', 2, 1, 0),
(61, 'angka', 2, 1, 0),
(62, 'ditahui', 2, 1, 0),
(63, 'setelah', 2, 1, 0),
(64, 'terpasang', 2, 1, 0),
(65, 'meter', 2, 1, 0),
(66, 'induk', 2, 1, 0),
(67, 'setiap', 2, 1, 0),
(68, 'sumber', 2, 1, 0),
(69, 'koordinasikan', 3, 1, 0),
(70, 'dewan', 3, 1, 0),
(71, 'pengawas', 3, 1, 0),
(72, 'didampingi', 3, 1, 0),
(73, 'asisten', 3, 1, 0),
(74, '2', 3, 2, 0),
(75, 'kabag', 3, 1, 0),
(76, 'perekonomian', 3, 1, 0),
(77, 'lalu', 3, 1, 0),
(78, 'tenaga', 3, 2, 0),
(79, 'muda', 3, 1, 0),
(80, 'profesional', 3, 1, 0),
(81, 'ada', 3, 2, 0),
(82, 'di', 3, 2, 0),
(83, 'pdam', 3, 1, 0),
(84, 'harus', 3, 1, 0),
(85, 'dirahkan', 3, 1, 0),
(86, 'untuk', 3, 1, 0),
(87, 'terus', 3, 1, 0),
(88, 'merumuskan', 3, 1, 0),
(89, 'katanya', 3, 1, 0),
(90, 'senin', 3, 1, 0),
(91, '11', 3, 1, 0),
(92, 'dikatakannya', 3, 1, 0),
(93, 'sistem', 3, 1, 0),
(94, 'pengelolaan', 3, 1, 0),
(95, 'air', 3, 2, 0),
(96, 'minum', 3, 1, 0),
(97, 'kota', 3, 1, 0),
(98, 'magelang', 3, 1, 0),
(99, 'sudah', 3, 2, 0),
(100, 'dibangun', 3, 1, 0),
(101, 'sejak', 3, 1, 0),
(102, 'zaman', 3, 1, 0),
(103, 'belanda', 3, 1, 0),
(104, 'saat', 3, 1, 0),
(105, 'tinggal', 3, 1, 0),
(106, 'meneruskan', 3, 1, 0),
(107, 'saja', 3, 1, 0),
(108, 'namun', 3, 1, 0),
(109, 'nyataannya', 3, 1, 0),
(110, 'mengelola', 3, 1, 0),
(111, 'tidak', 3, 1, 0),
(112, 'sesederhana', 3, 1, 0),
(113, 'dibakan', 3, 1, 0),
(114, 'terlebih', 3, 1, 0),
(115, 'laporan', 3, 1, 0),
(116, 'estimasi', 3, 1, 0),
(117, 'bocoran', 3, 1, 0),
(118, 'mencapai', 3, 1, 0),
(119, 'hampir', 3, 1, 0),
(120, '50%', 3, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbstem`
--

CREATE TABLE `tbstem` (
  `Id` int(11) NOT NULL,
  `Term` varchar(30) NOT NULL,
  `Stem` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbstem`
--

INSERT INTO `tbstem` (`Id`, `Term`, `Stem`) VALUES
(1, 'pertemuan', 'temu'),
(2, 'bertemu', 'temu');

-- --------------------------------------------------------

--
-- Table structure for table `tbvektor`
--

CREATE TABLE `tbvektor` (
  `DocId` int(11) NOT NULL,
  `Panjang` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbvektor`
--

INSERT INTO `tbvektor` (`DocId`, `Panjang`) VALUES
(1, 5.43766),
(2, 4.83995),
(3, 5.91145);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbberita`
--
ALTER TABLE `tbberita`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tbcache`
--
ALTER TABLE `tbcache`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tbindex`
--
ALTER TABLE `tbindex`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tbstem`
--
ALTER TABLE `tbstem`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tbvektor`
--
ALTER TABLE `tbvektor`
  ADD PRIMARY KEY (`DocId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbberita`
--
ALTER TABLE `tbberita`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tbcache`
--
ALTER TABLE `tbcache`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbindex`
--
ALTER TABLE `tbindex`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `tbstem`
--
ALTER TABLE `tbstem`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
