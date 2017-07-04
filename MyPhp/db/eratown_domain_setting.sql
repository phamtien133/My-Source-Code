-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 28, 2017 at 11:58 PM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db_empty`
--

-- --------------------------------------------------------

--
-- Table structure for table `eratown_domain_setting`
--

CREATE TABLE IF NOT EXISTS `eratown_domain_setting` (
  `domain_id` smallint(5) NOT NULL,
  `page_type` varchar(20) COLLATE utf8_swedish_ci NOT NULL,
  `statistical_fucntion` tinyint(1) NOT NULL,
  `online_support` tinyint(1) NOT NULL,
  `chat_limit` tinyint(1) NOT NULL,
  `footer_copyright` varchar(225) COLLATE utf8_swedish_ci NOT NULL,
  `title` varchar(225) COLLATE utf8_swedish_ci NOT NULL,
  `description` varchar(400) COLLATE utf8_swedish_ci NOT NULL,
  `key_word` varchar(400) COLLATE utf8_swedish_ci NOT NULL,
  `template` varchar(225) COLLATE utf8_swedish_ci NOT NULL,
  `template_mobile` varchar(10) COLLATE utf8_swedish_ci NOT NULL,
  `template_admin` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
  `language` varchar(2) COLLATE utf8_swedish_ci NOT NULL,
  `language_acp` varchar(2) COLLATE utf8_swedish_ci NOT NULL,
  `google_analytics` varchar(20) COLLATE utf8_swedish_ci NOT NULL,
  `article_home_page_count` int(3) NOT NULL,
  `twitter_key` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
  `twitter_secret` varchar(100) COLLATE utf8_swedish_ci NOT NULL,
  `facebook_appid` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
  `facebook_appsecret` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
  `facebook_pageid` varchar(16) COLLATE utf8_swedish_ci NOT NULL,
  `facebook_message` varchar(225) COLLATE utf8_swedish_ci NOT NULL,
  `facebook_link_url` varchar(225) COLLATE utf8_swedish_ci NOT NULL,
  `facebook_link_pic` varchar(225) COLLATE utf8_swedish_ci NOT NULL,
  `facebook_link_des` varchar(225) COLLATE utf8_swedish_ci NOT NULL,
  `comment_facebook` tinyint(1) NOT NULL,
  `display_facebook_button` tinyint(1) NOT NULL,
  `display_twitter_button` tinyint(1) NOT NULL,
  `display_google_plus_button` tinyint(1) NOT NULL,
  `allow_register_member` tinyint(1) NOT NULL,
  `openid_login` tinyint(1) NOT NULL COMMENT '1 la cho phep dang nhap voi openid',
  `save_image_on_server` tinyint(1) NOT NULL,
  `wuswug_comment` tinyint(1) NOT NULL,
  `wuswug_editor` tinyint(1) NOT NULL,
  `filter_html` tinyint(1) NOT NULL,
  `send_email_when_comment` varchar(225) COLLATE utf8_swedish_ci NOT NULL,
  `total_view` int(9) NOT NULL,
  `top_view_article` varchar(225) COLLATE utf8_swedish_ci NOT NULL,
  `top_new_article` varchar(225) COLLATE utf8_swedish_ci NOT NULL,
  `size_allow` int(11) NOT NULL,
  `size_used` int(11) NOT NULL,
  `auto_get_article` tinyint(1) NOT NULL,
  `category_index` int(11) NOT NULL,
  `yahoo_notice` varchar(225) COLLATE utf8_swedish_ci NOT NULL,
  `check_recieve_email` tinyint(1) NOT NULL,
  `template_category` varchar(100) COLLATE utf8_swedish_ci NOT NULL,
  `setting_support` text COLLATE utf8_swedish_ci NOT NULL,
  `currency_id` int(11) NOT NULL,
  `email` varchar(500) COLLATE utf8_swedish_ci NOT NULL,
  `facebook` text COLLATE utf8_swedish_ci NOT NULL,
  `floor_link` text COLLATE utf8_swedish_ci NOT NULL,
  `avatar` text COLLATE utf8_swedish_ci NOT NULL,
  `domain_login` int(11) NOT NULL,
  `article_path_link` varchar(50) COLLATE utf8_swedish_ci NOT NULL COMMENT 'Quy định cấu trúc link câp 1 Quy định dùng mã số làm khóa {cat}/{top}.html {cat}/{top}-{key}.html {top}.html {top}-{key}.html',
  `top_article` tinytext COLLATE utf8_swedish_ci NOT NULL,
  `sms_marketing` text COLLATE utf8_swedish_ci NOT NULL,
  `email_marketing` text COLLATE utf8_swedish_ci NOT NULL,
  `order_time` text COLLATE utf8_swedish_ci,
  `default_field` text COLLATE utf8_swedish_ci NOT NULL,
  `security_setting` text COLLATE utf8_swedish_ci NOT NULL,
  PRIMARY KEY (`domain_id`),
  KEY `ten_mien_stt` (`domain_id`),
  KEY `ten_mien_stt_2` (`domain_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Dumping data for table `eratown_domain_setting`
--

INSERT INTO `eratown_domain_setting` (`domain_id`, `page_type`, `statistical_fucntion`, `online_support`, `chat_limit`, `footer_copyright`, `title`, `description`, `key_word`, `template`, `template_mobile`, `template_admin`, `language`, `language_acp`, `google_analytics`, `article_home_page_count`, `twitter_key`, `twitter_secret`, `facebook_appid`, `facebook_appsecret`, `facebook_pageid`, `facebook_message`, `facebook_link_url`, `facebook_link_pic`, `facebook_link_des`, `comment_facebook`, `display_facebook_button`, `display_twitter_button`, `display_google_plus_button`, `allow_register_member`, `openid_login`, `save_image_on_server`, `wuswug_comment`, `wuswug_editor`, `filter_html`, `send_email_when_comment`, `total_view`, `top_view_article`, `top_new_article`, `size_allow`, `size_used`, `auto_get_article`, `category_index`, `yahoo_notice`, `check_recieve_email`, `template_category`, `setting_support`, `currency_id`, `email`, `facebook`, `floor_link`, `avatar`, `domain_login`, `article_path_link`, `top_article`, `sms_marketing`, `email_marketing`, `order_time`, `default_field`, `security_setting`) VALUES
(1, 'shopping', 1, 1, 0, '<a href="http://fi.ai" rel="nofollow" target="_blank">Công ty TNHH Công Nghệ FI</a>', 'A Đây rồi', '', '', '4038', '', '', '1', '1', 'UA-59621450-5', 12, '', '', '', '', '', '', '', '', '', 1, 1, 1, 1, 1, 1, 1, 0, 1, 1, '', 4416, '', '', 0, 0, 0, 1, '', 0, '', 'a:2:{s:8:"loi_chao";s:165:"Chào bạn, bạn đang băn khoăn trưa nay ăn gì, hay chưa biết chọn thực phẩm nào cho bữa ăn hôm nay, chúng tôi có thể hỗ trợ bạn.";s:17:"thong_bao_lan_dau";s:503:"<span>Ch</span><span>ào b</span><span>ạn, b</span><span>ạn </span><span>đang b</span><span>ăn kho</span><span>ăn tr</span><span>ưa nay </span><span>ăn g</span><span>ì, hay ch</span><span>ưa bi</span><span>ết ch</span><span>ọn th</span><span>ực ph</span><span>ẩm n</span><span>ào cho b</span><span>ữa </span><span>ăn h</span><span>ôm nay, ch</span><span>úng t</span><span>ôi c</span><span>ó th</span><span>ể h</span><span>ỗ tr</span><span>ợ b</span><span>ạn.</span>";}', 0, '78dc9df65c3e117851a74c705ef40ff21aba7b7a67c95ad0a015534723b849b8b008186007fbb48280e036933cd6778f7bc59dc6bb3a458d7eb53f777aec9dcd784721fbeed5843b86009a', '', '', 'a:5:{s:8:"bai_viet";s:0:"";s:6:"de_tai";s:0:"";s:10:"thanh_vien";s:0:"";s:4:"logo";s:41:"http://img.thegioitaisan.com/sys/logo.png";s:7:"favicon";s:37:"http://img.thegioitaisan.com/icon.ico";}', 0, '{key}.html', 'a:6:{s:8:"tu_truoc";i:3600;s:7:"them_tu";i:31449600;s:14:"thoi_gian_dang";i:0;s:7:"het_han";i:3600;s:9:"toi_thieu";i:0;s:6:"toi_da";i:100;}', '', '', 'a:2:{s:8:"min_time";i:0;s:4:"list";a:12:{i:0;a:2:{s:4:"from";s:5:"08:00";s:2:"to";s:5:"09:00";}i:1;a:2:{s:4:"from";s:5:"09:00";s:2:"to";s:5:"10:00";}i:2;a:2:{s:4:"from";s:5:"10:00";s:2:"to";s:5:"11:00";}i:3;a:2:{s:4:"from";s:5:"11:00";s:2:"to";s:5:"12:00";}i:4;a:2:{s:4:"from";s:5:"12:00";s:2:"to";s:5:"13:00";}i:5;a:2:{s:4:"from";s:5:"13:00";s:2:"to";s:5:"14:00";}i:6;a:2:{s:4:"from";s:5:"14:00";s:2:"to";s:5:"15:00";}i:7;a:2:{s:4:"from";s:5:"15:00";s:2:"to";s:5:"16:00";}i:8;a:2:{s:4:"from";s:5:"16:00";s:2:"to";s:5:"17:00";}i:9;a:2:{s:4:"from";s:5:"17:00";s:2:"to";s:5:"18:00";}i:10;a:2:{s:4:"from";s:5:"18:00";s:2:"to";s:5:"19:00";}i:11;a:2:{s:4:"from";s:5:"19:00";s:2:"to";s:5:"20:00";}}}', 'a:10:{s:4:"code";a:2:{s:8:"register";i:0;s:7:"require";i:0;}s:8:"fullname";a:2:{s:8:"register";i:1;s:7:"require";i:1;}s:8:"password";a:2:{s:8:"register";i:1;s:7:"require";i:1;}s:5:"email";a:2:{s:8:"register";i:1;s:7:"require";i:1;}s:13:"profile_image";a:2:{s:8:"register";i:0;s:7:"require";i:0;}s:8:"birthday";a:2:{s:8:"register";i:0;s:7:"require";i:0;}s:7:"country";a:2:{s:8:"register";i:0;s:7:"require";i:0;}s:7:"address";a:2:{s:8:"register";i:0;s:7:"require";i:0;}s:3:"sex";a:2:{s:8:"register";i:0;s:7:"require";i:0;}s:12:"phone_number";a:2:{s:8:"register";i:1;s:7:"require";i:1;}}', 'a:5:{s:18:"two_layer_security";i:1;s:12:"security_sms";i:1;s:16:"security_gg_auth";i:1;s:7:"pincode";i:1;s:7:"default";s:12:"security_sms";}');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
