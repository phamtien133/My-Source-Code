-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th6 07, 2017 lúc 03:54 CH
-- Phiên bản máy phục vụ: 10.1.21-MariaDB
-- Phiên bản PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `product_management`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tb_login`
--

CREATE TABLE `tb_login` (
  `login_id` int(11) NOT NULL,
  `login_user` varchar(40) NOT NULL,
  `login_password` varchar(40) NOT NULL,
  `login_role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `tb_login`
--

INSERT INTO `tb_login` (`login_id`, `login_user`, `login_password`, `login_role`) VALUES
(1, 'admin', 'admin', 1),
(2, 'staff', 'staff', 2),
(3, 'admin2', 'admin2', 1),
(4, 'staff2', 'staff2', 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tb_product`
--

CREATE TABLE `tb_product` (
  `product_id` int(11) NOT NULL,
  `product_name` text NOT NULL,
  `product_detail` text NOT NULL,
  `product_price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `tb_product`
--

INSERT INTO `tb_product` (`product_id`, `product_name`, `product_detail`, `product_price`) VALUES
(1, 'Bánh', 'Bánh ngọt tự làm', 400000),
(2, 'Kẹo', 'Kẹo Bến Tre', 20000),
(3, 'Áo', 'Áo thun cho mùa hè', 50000),
(4, 'Quần', 'Quần short', 50000),
(5, 'Bánh chocolate', 'Bánh ngọt tự làm', 400000),
(6, 'Kẹo mút', 'Kẹo mikita', 20000),
(7, 'Áo Mabư', 'Áo thun cho mùa hè', 50000),
(8, 'Quần Jogger', 'Quần style', 50000);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tb_user`
--

CREATE TABLE `tb_user` (
  `user_id` int(11) NOT NULL,
  `user_name` text NOT NULL,
  `user_phone` varchar(12) NOT NULL,
  `user_identify_card` varchar(12) NOT NULL,
  `user_address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `tb_user`
--

INSERT INTO `tb_user` (`user_id`, `user_name`, `user_phone`, `user_identify_card`, `user_address`) VALUES
(1, 'Nguyễn Văn A', '0169966993', '12548792', '23 Nguyễn Văn Cừ, Quận 5'),
(2, 'Hoàng Hữu Huân', '0125486344', '01254862323', '128 Nguyễn Công Trứ');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `tb_login`
--
ALTER TABLE `tb_login`
  ADD PRIMARY KEY (`login_id`);

--
-- Chỉ mục cho bảng `tb_product`
--
ALTER TABLE `tb_product`
  ADD PRIMARY KEY (`product_id`);

--
-- Chỉ mục cho bảng `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `tb_login`
--
ALTER TABLE `tb_login`
  MODIFY `login_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT cho bảng `tb_product`
--
ALTER TABLE `tb_product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT cho bảng `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
