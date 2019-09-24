-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 24, 2019 at 03:51 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wisata_batu`
--

-- --------------------------------------------------------

--
-- Table structure for table `wisata`
--

CREATE TABLE `wisata` (
  `idwisata` int(11) NOT NULL,
  `nama_wisata` varchar(500) NOT NULL,
  `informasi` varchar(2500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wisata`
--

INSERT INTO `wisata` (`idwisata`, `nama_wisata`, `informasi`) VALUES
(1, 'Museum Angkut', 'Museum Angkut merupakan museum transportasi dan tempat wisata modern yang terletak di Kota Batu, Jawa Timur, sekitar 20 km dari Kota Malang. Museum ini terletak di kawasan seluas 3,8 hektar di lereng Gunung Panderman dan memiliki lebih dari 300 koleksi jenis angkutan tradisional hingga modern.\r\n\r\n\r\nHarga Tiket: Rp100.000\r\n\r\n\r\nAlamat : Jl. Terusan Sultan Agung No.2, Ngaglik, Kec. Batu, Kota Batu\r\n'),
(2, 'Jawa Timur Park 2', 'Taman hiburan dengan kegiatan pendidikan seputar ilmu pengetahuan alam & biologi, termasuk kebun binatang.\r\n\r\n\r\nHarga Tiket : Weekend : Rp. 100.000 Weekday: 90.000 \r\n\r\n\r\nAlamat : Jl. Oro-Oro Ombo No.9, Temas, Kec. Batu, Kota Batu,\r\n'),
(3, 'Omah Kayu', 'Omah kayu merupakan\r\n\r\nHarga Tiket: Rp5.000/orang\r\n\r\n\r\n\r\nAlamat : Jl. Gn. Banyak, Gunungsari, Bumiaji, Kota Batu\r\n\r\n'),
(4, 'Eco Green Park', 'Eco Green Park merupakan Taman margasatwa outdoor dengan penangkaran burung, atraksi mengelus hewan, pusat belajar sains, & bioskop 3D. Eco green park masih merupakan bagian dari Jatim Park 2\r\n\r\n\r\nHarga Tiket : weekday Rp. 55.000/orang weekend  Rp. 75.000/orang\r\n\r\n\r\nAlamat : Jl. Oro-Oro Ombo No.9A, Sisir, Kec. Batu, Kota Batu\r\n');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wisata`
--
ALTER TABLE `wisata`
  ADD PRIMARY KEY (`idwisata`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wisata`
--
ALTER TABLE `wisata`
  MODIFY `idwisata` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
