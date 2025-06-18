-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 18, 2025 at 04:44 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `batiknusantara`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order`
--

CREATE TABLE `tbl_order` (
  `trans_id` int NOT NULL,
  `email` char(60) DEFAULT NULL,
  `tgl_order` date DEFAULT NULL,
  `subtotal` double DEFAULT NULL,
  `ongkir` double DEFAULT NULL,
  `total_bayar` double DEFAULT NULL,
  `alamat_kirim` varchar(100) DEFAULT NULL,
  `telp_kirim` varchar(15) DEFAULT NULL,
  `kota` varchar(30) DEFAULT NULL,
  `provinsi` varchar(30) DEFAULT NULL,
  `lamakirim` varchar(20) DEFAULT NULL,
  `kodepos` varchar(20) DEFAULT NULL,
  `metodebayar` int DEFAULT NULL,
  `buktipembayar` varchar(100) DEFAULT NULL,
  `status` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_detail`
--

CREATE TABLE `tbl_order_detail` (
  `trans_id` int NOT NULL,
  `kode_brg` char(10) NOT NULL,
  `harga_jual` double DEFAULT NULL,
  `qty` int DEFAULT NULL,
  `bayar` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pelanggan`
--

CREATE TABLE `tbl_pelanggan` (
  `id` int NOT NULL,
  `nama` varchar(200) DEFAULT NULL,
  `alamat` varchar(200) DEFAULT NULL,
  `kota` char(100) DEFAULT NULL,
  `provinsi` char(100) DEFAULT NULL,
  `kodepos` char(20) DEFAULT NULL,
  `telp` char(20) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `PASSWORD` varchar(255) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product`
--

CREATE TABLE `tbl_product` (
  `kode` char(10) NOT NULL,
  `merk` varchar(200) DEFAULT NULL,
  `kategori` char(20) DEFAULT NULL,
  `satuan` char(30) DEFAULT NULL,
  `hargabeli` double DEFAULT NULL,
  `diskonbeli` double DEFAULT NULL,
  `hargapokok` double DEFAULT NULL,
  `hargajual` double DEFAULT NULL,
  `diskonjual` double DEFAULT NULL,
  `stok` int DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `deskripsi` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_order`
--
ALTER TABLE `tbl_order`
  ADD PRIMARY KEY (`trans_id`);

--
-- Indexes for table `tbl_order_detail`
--
ALTER TABLE `tbl_order_detail`
  ADD PRIMARY KEY (`trans_id`,`kode_brg`),
  ADD KEY `kode_brg` (`kode_brg`);

--
-- Indexes for table `tbl_pelanggan`
--
ALTER TABLE `tbl_pelanggan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_product`
--
ALTER TABLE `tbl_product`
  ADD PRIMARY KEY (`kode`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_order`
--
ALTER TABLE `tbl_order`
  MODIFY `trans_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_pelanggan`
--
ALTER TABLE `tbl_pelanggan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_order_detail`
--
ALTER TABLE `tbl_order_detail`
  ADD CONSTRAINT `tbl_order_detail_ibfk_1` FOREIGN KEY (`trans_id`) REFERENCES `tbl_order` (`trans_id`),
  ADD CONSTRAINT `tbl_order_detail_ibfk_2` FOREIGN KEY (`kode_brg`) REFERENCES `tbl_product` (`kode`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
