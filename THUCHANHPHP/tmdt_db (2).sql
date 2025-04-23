-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 22, 2025 lúc 08:01 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `tmdt_db`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietdonhang`
--

CREATE TABLE `chitietdonhang` (
  `idctdh` int(11) NOT NULL,
  `iddh` int(11) DEFAULT NULL,
  `idsp` int(11) DEFAULT NULL,
  `soluong` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chitietdonhang`
--

INSERT INTO `chitietdonhang` (`idctdh`, `iddh`, `idsp`, `soluong`) VALUES
(4, 7, 31, 15),
(5, 8, 29, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `congty`
--

CREATE TABLE `congty` (
  `idcty` int(11) NOT NULL,
  `tencty` varchar(100) NOT NULL,
  `dienthoai` varchar(15) DEFAULT NULL,
  `fax` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `congty`
--

INSERT INTO `congty` (`idcty`, `tencty`, `dienthoai`, `fax`) VALUES
(1, 'Apple', NULL, NULL),
(2, 'Samsung', NULL, NULL),
(3, 'Xiaomi', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dathang`
--

CREATE TABLE `dathang` (
  `iddh` int(11) NOT NULL,
  `idkh` int(11) NOT NULL,
  `ngaydathang` datetime NOT NULL,
  `trangthai` enum('0','1') DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `dathang`
--

INSERT INTO `dathang` (`iddh`, `idkh`, `ngaydathang`, `trangthai`) VALUES
(1, 3, '2025-04-07 13:54:57', '0');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dathang_chitiet`
--

CREATE TABLE `dathang_chitiet` (
  `iddh` int(11) NOT NULL,
  `idsp` int(11) NOT NULL,
  `soluong` int(11) NOT NULL,
  `dongia` decimal(10,2) NOT NULL,
  `giamgia` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `dathang_chitiet`
--

INSERT INTO `dathang_chitiet` (`iddh`, `idsp`, `soluong`, `dongia`, `giamgia`) VALUES
(1, 19, 4, 18000000.00, 0.00),
(1, 24, 1, 20000000.00, 0.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `donhang`
--

CREATE TABLE `donhang` (
  `iddh` int(11) NOT NULL,
  `idkh` int(11) NOT NULL,
  `ngaydh` date NOT NULL,
  `trangthai` varchar(50) DEFAULT 'Chờ xử lý'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `donhang`
--

INSERT INTO `donhang` (`iddh`, `idkh`, `ngaydh`, `trangthai`) VALUES
(5, 3, '2025-04-09', 'Đã giao'),
(6, 2, '2025-04-09', 'Đã giao'),
(7, 2, '2025-04-09', 'Đã giao'),
(8, 4, '2025-04-09', 'Đã giao');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khachhang`
--

CREATE TABLE `khachhang` (
  `idkh` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `khoachinh` varchar(255) NOT NULL,
  `hodem` varchar(50) DEFAULT NULL,
  `ten` varchar(50) NOT NULL,
  `diachi` varchar(255) DEFAULT NULL,
  `dienthoai` varchar(15) DEFAULT NULL,
  `matkhau` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `khachhang`
--

INSERT INTO `khachhang` (`idkh`, `email`, `khoachinh`, `hodem`, `ten`, `diachi`, `dienthoai`, `matkhau`) VALUES
(2, 'user2@example.com', 'e10adc3949ba59abbe56e057f20f883e', 'Tran', 'Thi B', '456 Đường XYZ, Quận 2, TP.HCM', '0987654321', ''),
(3, 'admin@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'Nguyễn Hoàng', 'Khánh', 'HCM', '0827930928', ''),
(4, 'khanh@gmail.com', '', 'Nguyen Hoang', 'Khanh', 'HCM', '0123456789', '202cb962ac59075b964b07152d234b70'),
(5, 'khanglam@gmail.com', '', 'lam nhut ', 'khang', 'go vap', '0123456789', 'fd404db7fb3171a749c80411516d2017'),
(6, 'sank@gmail.com', '', 'lam', 'khang', '123', '147258369', '202cb962ac59075b964b07152d234b70');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sanpham`
--

CREATE TABLE `sanpham` (
  `idsp` int(11) NOT NULL,
  `tensp` varchar(100) NOT NULL,
  `gia` decimal(10,2) DEFAULT NULL,
  `mota` text DEFAULT NULL,
  `hinh` varchar(255) DEFAULT NULL,
  `giamgia` decimal(5,2) DEFAULT NULL,
  `idcty` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `sanpham`
--

INSERT INTO `sanpham` (`idsp`, `tensp`, `gia`, `mota`, `hinh`, `giamgia`, `idcty`) VALUES
(19, 'iPhone 16', 5000000.00, 'dfdfjd', '/THUCHANHPHP/TUAN6/images/ip16.jpg', NULL, 1),
(21, 'Samsung Galaxy A54', 9000000.00, NULL, '/THUCHANHPHP/TUAN6/images/ssa54.jpg', NULL, 2),
(23, 'Redmi Note 12', 6000000.00, NULL, '/THUCHANHPHP/TUAN6/images/xrn12.jpg', NULL, 3),
(24, 'iPhone 15', 20000000.00, NULL, '/THUCHANHPHP/TUAN6/images/ip151.jpg', NULL, 1),
(25, 'Samsung Galaxy S23', 18000000.00, NULL, '/THUCHANHPHP/TUAN6/images/sss23.jpg', NULL, 2),
(26, 'Xiaomi 13 Pro', 15000000.00, NULL, '/THUCHANHPHP/TUAN6/images/x13pro.jpg', NULL, 3),
(27, 'iPhone 14', 27000000.00, NULL, '/THUCHANHPHP/TUAN6/images/ip141.jpg', NULL, 1),
(28, 'Samsung Galaxy Z Fold5', 42000000.00, NULL, '/THUCHANHPHP/TUAN6/images/ssz5.jpg', NULL, 2),
(29, 'Xiaomi 13T', 12000000.00, NULL, '/THUCHANHPHP/TUAN6/images/x13t.jpg', NULL, 3),
(31, 'Samsung Galaxy A34', 7500000.00, NULL, '/THUCHANHPHP/TUAN6/images/ssa34.jpg', NULL, 2),
(32, 'Redmi 12C', 3500000.00, NULL, '/THUCHANHPHP/TUAN6/images/xr12c.jpg', NULL, 3),
(40, 'ipone 16e', 18000000.00, '150', '/THUCHANHPHP/TUAN7/images/iphone-16e-white-thumb-600x600.jpg', NULL, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `taikhoan`
--

CREATE TABLE `taikhoan` (
  `iduser` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `hoten` varchar(100) DEFAULT NULL,
  `ten` varchar(50) DEFAULT NULL,
  `phanquyen` varchar(20) DEFAULT NULL,
  `landangnhapcuoi` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `taikhoan`
--

INSERT INTO `taikhoan` (`iduser`, `username`, `password`, `hoten`, `ten`, `phanquyen`, `landangnhapcuoi`) VALUES
(1, 'user1', 'pass123', 'Nguyen Van A', 'A', 'admin', '2025-04-06 10:00:00'),
(2, 'user2', 'pass456', 'Tran Thi B', 'B', 'user', '2025-04-06 11:00:00'),
(3, 'user3', 'pass789', 'Le Van C', 'C', 'user', '2025-04-06 12:00:00'),
(4, 'user4', 'pass101', 'Pham Thi D', 'D', 'admin', '2025-04-06 13:00:00'),
(5, 'user5', 'pass202', 'Hoang Van E', 'E', 'user', '2025-04-06 14:00:00');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD PRIMARY KEY (`idctdh`),
  ADD KEY `iddh` (`iddh`),
  ADD KEY `idsp` (`idsp`);

--
-- Chỉ mục cho bảng `congty`
--
ALTER TABLE `congty`
  ADD PRIMARY KEY (`idcty`);

--
-- Chỉ mục cho bảng `dathang`
--
ALTER TABLE `dathang`
  ADD PRIMARY KEY (`iddh`),
  ADD KEY `idkh` (`idkh`);

--
-- Chỉ mục cho bảng `dathang_chitiet`
--
ALTER TABLE `dathang_chitiet`
  ADD PRIMARY KEY (`iddh`,`idsp`),
  ADD KEY `idsp` (`idsp`);

--
-- Chỉ mục cho bảng `donhang`
--
ALTER TABLE `donhang`
  ADD PRIMARY KEY (`iddh`),
  ADD KEY `idkh` (`idkh`);

--
-- Chỉ mục cho bảng `khachhang`
--
ALTER TABLE `khachhang`
  ADD PRIMARY KEY (`idkh`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  ADD PRIMARY KEY (`idsp`),
  ADD KEY `idcty` (`idcty`);

--
-- Chỉ mục cho bảng `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD PRIMARY KEY (`iduser`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  MODIFY `idctdh` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `congty`
--
ALTER TABLE `congty`
  MODIFY `idcty` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT cho bảng `dathang`
--
ALTER TABLE `dathang`
  MODIFY `iddh` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `donhang`
--
ALTER TABLE `donhang`
  MODIFY `iddh` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `khachhang`
--
ALTER TABLE `khachhang`
  MODIFY `idkh` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  MODIFY `idsp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT cho bảng `taikhoan`
--
ALTER TABLE `taikhoan`
  MODIFY `iduser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD CONSTRAINT `chitietdonhang_ibfk_1` FOREIGN KEY (`iddh`) REFERENCES `donhang` (`iddh`),
  ADD CONSTRAINT `chitietdonhang_ibfk_2` FOREIGN KEY (`idsp`) REFERENCES `sanpham` (`idsp`);

--
-- Các ràng buộc cho bảng `dathang`
--
ALTER TABLE `dathang`
  ADD CONSTRAINT `dathang_ibfk_1` FOREIGN KEY (`idkh`) REFERENCES `khachhang` (`idkh`);

--
-- Các ràng buộc cho bảng `dathang_chitiet`
--
ALTER TABLE `dathang_chitiet`
  ADD CONSTRAINT `dathang_chitiet_ibfk_1` FOREIGN KEY (`iddh`) REFERENCES `dathang` (`iddh`),
  ADD CONSTRAINT `dathang_chitiet_ibfk_2` FOREIGN KEY (`idsp`) REFERENCES `sanpham` (`idsp`);

--
-- Các ràng buộc cho bảng `donhang`
--
ALTER TABLE `donhang`
  ADD CONSTRAINT `donhang_ibfk_1` FOREIGN KEY (`idkh`) REFERENCES `khachhang` (`idkh`);

--
-- Các ràng buộc cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  ADD CONSTRAINT `sanpham_ibfk_1` FOREIGN KEY (`idcty`) REFERENCES `congty` (`idcty`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
