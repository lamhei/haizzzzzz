-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 11, 2025 at 05:49 AM
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
-- Database: `db_nhanluc`
--

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `ghi_chu` varchar(255) DEFAULT NULL,
  `ID` int(11) UNSIGNED NOT NULL COMMENT 'mã định danh(tự động tăng)',
  `ngay_nhan` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ho_ten` varchar(255) NOT NULL COMMENT 'họ tên user',
  `nam_sinh` int(4) NOT NULL COMMENT 'năm sinh ',
  `dia_chi` varchar(255) NOT NULL COMMENT 'nơi ở user',
  `chuong_trinh` varchar(255) NOT NULL COMMENT 'chương trình đã lựa chọn',
  `quoc_gia` varchar(255) NOT NULL COMMENT 'chương trình ở nước nào',
  `sdt` varchar(20) NOT NULL COMMENT 'số điện thoại có zalo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='bảng lưu trữ thông tin khách hàng đăng ký';

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`ghi_chu`, `ID`, `ngay_nhan`, `ho_ten`, `nam_sinh`, `dia_chi`, `chuong_trinh`, `quoc_gia`, `sdt`) VALUES
('null', 3, '2025-12-11 09:59:58', 'g', 3432, 'fdsfsdfd', 'Xuất khẩu lao động', 'Nhật Bản', 'e6e6535265'),
('null', 4, '2025-12-11 10:04:21', 'g', 3432, 'fdsfsdfdssssdad', 'Đào tạo ngoại ngữ', 'Hàn Quốc', '0928454744'),
(NULL, 10, '2025-12-11 10:13:10', 'ggh', 2313, 'fdsfsdfd', 'Du học Nhật Bản', 'Nhật Bản', '0928454744');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'mã định danh(tự động tăng)', AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
