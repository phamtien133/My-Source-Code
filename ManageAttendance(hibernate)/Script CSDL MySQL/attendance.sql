-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 15, 2017 lúc 01:12 CH
-- Phiên bản máy phục vụ: 10.1.21-MariaDB
-- Phiên bản PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `attendance`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tb_classrooms`
--

CREATE TABLE `tb_classrooms` (
  `classrooms_id` int(11) NOT NULL,
  `classrooms_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `tb_classrooms`
--

INSERT INTO `tb_classrooms` (`classrooms_id`, `classrooms_name`) VALUES
(1, 'P001'),
(2, 'P002'),
(3, 'P003'),
(4, 'P004'),
(5, 'P005'),
(6, 'P006');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tb_login`
--

CREATE TABLE `tb_login` (
  `login_id` int(11) NOT NULL,
  `login_user` varchar(30) NOT NULL,
  `login_pass` text NOT NULL,
  `login_role` int(11) NOT NULL,
  `login_first_login` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `tb_login`
--

INSERT INTO `tb_login` (`login_id`, `login_user`, `login_pass`, `login_role`, `login_first_login`) VALUES
(1, 'gv01', 'dedf0c085cfd36ebed0aa7ee1c69538d', 1, 1),
(2, 'sv01', '4ba504d9fd4c13fd01ac07f054335912', 2, 1),
(3, 'sv03', '52853cebe4bbbd27d059af54a7e0f196', 2, 1),
(4, 'sv04', 'e8946cb6fe7594d27489a5b76ffb3883', 2, 1),
(5, 'sv007', 'ab11a8dfdea0a1e1f33441b9157901c6', 2, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tb_schedule`
--

CREATE TABLE `tb_schedule` (
  `schedule_id` int(11) NOT NULL,
  `schedule_start_date` text NOT NULL,
  `schedule_end_date` text NOT NULL,
  `schedule_day` int(11) NOT NULL,
  `schedule_start_time` int(11) NOT NULL,
  `schedule_end_time` int(11) NOT NULL,
  `schedule_classroom_id` int(11) NOT NULL,
  `schedule_subjects_id` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `tb_schedule`
--

INSERT INTO `tb_schedule` (`schedule_id`, `schedule_start_date`, `schedule_end_date`, `schedule_day`, `schedule_start_time`, `schedule_end_time`, `schedule_classroom_id`, `schedule_subjects_id`) VALUES
(1, '14/04/2017', '14/04/2017', 2, 7, 9, 1, 'mh01'),
(2, '12/04/2012', '12/04/2012', 2, 6, 9, 2, 'mh03'),
(3, '12/04/2016', '12/04/2016', 2, 3, 7, 1, 'mh04'),
(5, '08/04/2017', '22/07/2017', 7, 25200000, 32400000, 6, 'LTJV'),
(6, '01/04/2017', '15/07/2017', 7, 32400000, 39600000, 5, 'LTW');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tb_students`
--

CREATE TABLE `tb_students` (
  `students_id` varchar(4) NOT NULL,
  `students_name` text NOT NULL,
  `students_login_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `tb_students`
--

INSERT INTO `tb_students` (`students_id`, `students_name`, `students_login_id`) VALUES
('s001', 'Nguyễn Văn A', 2),
('s002', 'Nguyễn Văn B', 2),
('sv03', 'Nguyễn Văn Ba', 3),
('sv04', 'Nguyễn Văn Bốn', 4);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tb_subjects`
--

CREATE TABLE `tb_subjects` (
  `subjects_id` varchar(4) NOT NULL,
  `subjects_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `tb_subjects`
--

INSERT INTO `tb_subjects` (`subjects_id`, `subjects_name`) VALUES
('LTJV', 'Lập trình Java'),
('LTW', 'Lập trình Windows'),
('mh01', 'Tâm lý đại cương'),
('mh03', 'Giải Tích B1'),
('mh04', 'Lập trình Java');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tb_subjects_students`
--

CREATE TABLE `tb_subjects_students` (
  `subjects_students_id` int(11) NOT NULL,
  `subjects_students_stuid` varchar(4) NOT NULL,
  `subjects_students_subid` varchar(4) NOT NULL,
  `week1` int(11) NOT NULL,
  `week2` int(11) NOT NULL,
  `week3` int(11) NOT NULL,
  `week4` int(11) NOT NULL,
  `week5` int(11) NOT NULL,
  `week6` int(11) NOT NULL,
  `week7` int(11) NOT NULL,
  `week8` int(11) NOT NULL,
  `week9` int(11) NOT NULL,
  `week10` int(11) NOT NULL,
  `week11` int(11) NOT NULL,
  `week12` int(11) NOT NULL,
  `week13` int(11) NOT NULL,
  `week14` int(11) NOT NULL,
  `week15` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `tb_subjects_students`
--

INSERT INTO `tb_subjects_students` (`subjects_students_id`, `subjects_students_stuid`, `subjects_students_subid`, `week1`, `week2`, `week3`, `week4`, `week5`, `week6`, `week7`, `week8`, `week9`, `week10`, `week11`, `week12`, `week13`, `week14`, `week15`) VALUES
(1, 's001', 'mh01', 0, 0, 0, 0, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0),
(2, 's002', 'mh03', 0, 0, 0, 0, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0),
(3, 'sv03', 'mh01', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(4, 'sv04', 'mh01', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tb_teachers`
--

CREATE TABLE `tb_teachers` (
  `teachers_id` varchar(4) NOT NULL,
  `teachers_name` text NOT NULL,
  `teachers_login_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `tb_teachers`
--

INSERT INTO `tb_teachers` (`teachers_id`, `teachers_name`, `teachers_login_id`) VALUES
('gv01', 'Hồ Thị Anh', 1);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `tb_classrooms`
--
ALTER TABLE `tb_classrooms`
  ADD PRIMARY KEY (`classrooms_id`);

--
-- Chỉ mục cho bảng `tb_login`
--
ALTER TABLE `tb_login`
  ADD PRIMARY KEY (`login_id`);

--
-- Chỉ mục cho bảng `tb_schedule`
--
ALTER TABLE `tb_schedule`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `schedule_subjects_id` (`schedule_subjects_id`),
  ADD KEY `schedule_classroom_id` (`schedule_classroom_id`);

--
-- Chỉ mục cho bảng `tb_students`
--
ALTER TABLE `tb_students`
  ADD PRIMARY KEY (`students_id`),
  ADD KEY `students_login_id` (`students_login_id`);

--
-- Chỉ mục cho bảng `tb_subjects`
--
ALTER TABLE `tb_subjects`
  ADD PRIMARY KEY (`subjects_id`);

--
-- Chỉ mục cho bảng `tb_subjects_students`
--
ALTER TABLE `tb_subjects_students`
  ADD PRIMARY KEY (`subjects_students_id`),
  ADD KEY `subjects_students_stuid` (`subjects_students_stuid`),
  ADD KEY `subjects_students_subid` (`subjects_students_subid`);

--
-- Chỉ mục cho bảng `tb_teachers`
--
ALTER TABLE `tb_teachers`
  ADD PRIMARY KEY (`teachers_id`),
  ADD KEY `teachers_login_id` (`teachers_login_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `tb_classrooms`
--
ALTER TABLE `tb_classrooms`
  MODIFY `classrooms_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT cho bảng `tb_login`
--
ALTER TABLE `tb_login`
  MODIFY `login_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT cho bảng `tb_schedule`
--
ALTER TABLE `tb_schedule`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT cho bảng `tb_subjects_students`
--
ALTER TABLE `tb_subjects_students`
  MODIFY `subjects_students_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `tb_schedule`
--
ALTER TABLE `tb_schedule`
  ADD CONSTRAINT `tb_schedule_ibfk_2` FOREIGN KEY (`schedule_subjects_id`) REFERENCES `tb_subjects` (`subjects_id`),
  ADD CONSTRAINT `tb_schedule_ibfk_3` FOREIGN KEY (`schedule_classroom_id`) REFERENCES `tb_classrooms` (`classrooms_id`);

--
-- Các ràng buộc cho bảng `tb_students`
--
ALTER TABLE `tb_students`
  ADD CONSTRAINT `tb_students_ibfk_1` FOREIGN KEY (`students_login_id`) REFERENCES `tb_login` (`login_id`);

--
-- Các ràng buộc cho bảng `tb_subjects_students`
--
ALTER TABLE `tb_subjects_students`
  ADD CONSTRAINT `tb_subjects_students_ibfk_1` FOREIGN KEY (`subjects_students_stuid`) REFERENCES `tb_students` (`students_id`),
  ADD CONSTRAINT `tb_subjects_students_ibfk_2` FOREIGN KEY (`subjects_students_subid`) REFERENCES `tb_subjects` (`subjects_id`);

--
-- Các ràng buộc cho bảng `tb_teachers`
--
ALTER TABLE `tb_teachers`
  ADD CONSTRAINT `tb_teachers_ibfk_1` FOREIGN KEY (`teachers_login_id`) REFERENCES `tb_login` (`login_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
