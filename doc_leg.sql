-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 28, 2023 at 02:13 AM
-- Server version: 8.0.32-0ubuntu0.20.04.2
-- PHP Version: 7.4.3-4ubuntu2.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


use doc_leg;
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `doc_leg`
--

-- --------------------------------------------------------

--
-- Table structure for table `analisis_hukum`
--

CREATE TABLE `analisis_hukum` (
  `id_analisis_hukum` int NOT NULL,
  `judul` text,
  `file` varchar(100) DEFAULT NULL,
  `taging` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `kategori_usulan`
--

CREATE TABLE `kategori_usulan` (
  `id_kategori_usulan` int NOT NULL,
  `nama_kategori` varchar(100) DEFAULT NULL,
  `teruskan_provinsi` enum('0','1') DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `kategori_usulan`
--

INSERT INTO `kategori_usulan` (`id_kategori_usulan`, `nama_kategori`, `teruskan_provinsi`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Peraturan Daerah (Perda)', NULL, '2022-04-11 09:06:06', NULL, NULL),
(2, 'Peraturan Bupati (Perbup)', NULL, '2022-04-11 09:06:06', NULL, NULL),
(3, 'Keputusan Bupati (Kepbup)', NULL, '2022-04-11 09:06:06', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `level_user`
--

CREATE TABLE `level_user` (
  `id_level_user` int NOT NULL,
  `nama_level_user` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `urutan_legalitas` int DEFAULT NULL,
  `class_color` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `level_user`
--

INSERT INTO `level_user` (`id_level_user`, `nama_level_user`, `urutan_legalitas`, `class_color`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'superadmin', 0, NULL, '2021-08-12 01:27:54', '2021-08-12 01:27:54', NULL),
(2, 'admin', 0, NULL, '2021-08-16 07:22:44', NULL, '2021-08-16 07:22:59'),
(3, 'admins', 0, NULL, '2021-08-16 07:23:31', '2021-08-16 07:23:34', '2021-08-16 07:23:37'),
(4, 'admin', 2, 'bg-secondary', '2021-08-16 07:23:45', NULL, NULL),
(5, 'Perancang UU', 1, 'bg-primary', '2021-08-16 07:48:08', NULL, NULL),
(6, 'Kabag Hukum', 2, 'bg-secondary', '2021-08-16 07:48:15', NULL, NULL),
(7, 'Kasubbag Per-UU', 3, 'bg-info', '2021-08-16 07:48:26', NULL, NULL),
(8, 'Asisten Pemerintahan & Kesra', 4, 'bg-warning', '2021-08-16 07:48:35', NULL, NULL),
(9, 'Sekda', 5, 'bg-danger', '2021-08-16 07:48:40', NULL, NULL),
(10, 'Wakil Bupati', 6, 'bg-success', '2021-08-16 07:48:48', NULL, NULL),
(11, 'Bupati', 7, 'bg-dark', '2021-08-16 07:48:52', NULL, NULL),
(12, 'Provinsi', 0, 'bg-danger', NULL, NULL, NULL),
(13, 'Admin Diskominfo', 0, NULL, NULL, NULL, NULL),
(14, 'Bupati1', NULL, NULL, '2023-01-12 09:50:06', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `link_analisis_hukum`
--

CREATE TABLE `link_analisis_hukum` (
  `id_link_analisis_hukum` int NOT NULL,
  `external_link` text,
  `analisis_hukum_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `master_satker`
--

CREATE TABLE `master_satker` (
  `id_master_satker` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `master_satker`
--

INSERT INTO `master_satker` (`id_master_satker`, `nama`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Sekretariat DPRD Kabupaten Katingan', NULL, NULL, NULL),
(2, 'Dinas Pendidikan Kabupaten Katingan', NULL, NULL, NULL),
(3, 'Dinas Kesehatan Kabupaten Katingan', NULL, NULL, NULL),
(4, 'Dinas Pekerjaan Umum dan Penataan Ruang Kabupaten Katingan', NULL, NULL, NULL),
(5, 'Dinas Perumahan Rakyat, Kawasan Permukiman serta Pertanahan Kabupaten Katingan', NULL, NULL, NULL),
(6, 'Satuan Polisi Pamong Praja dan Pemadam Kebakaran Kabupaten Katingan', NULL, NULL, NULL),
(7, 'Dinas Perhubungan dan Perikanan Kabupaten Katingan', NULL, NULL, NULL),
(8, 'Dinas Pemberdayaan Perempuan dan Perlindungan Anak serta Pengendalian Penduduk dan Keluarga Berencana Kabupaten Katingan', NULL, NULL, NULL),
(9, 'Dinas Lingkungan Hidup Kabupaten Katingan', NULL, NULL, NULL),
(10, 'Dinas Kependudukan dan Pencatatan Sipil Kabupaten Katingan', NULL, NULL, NULL),
(11, 'Dinas Sosial Kabupaten Katingan', NULL, NULL, NULL),
(12, 'Dinas Pemberdayaan Masyarakat dan Desa Kabupaten Katingan', NULL, NULL, NULL),
(13, 'Dinas Komunikasi Informatika, Statistik dan Persandian Kabupaten Katingan', NULL, NULL, NULL),
(14, 'Dinas Koperasi, Usaha Kecil Menengah dan Perdagangan Kabupaten Katingan', NULL, NULL, NULL),
(15, 'Dinas Perindustrian, Transmigrasi dan Tenaga Kerja Kabupaten Katingan', NULL, NULL, NULL),
(16, 'Dinas Penanaman Modal, Pelayanan Terpadu Satu Pintu Kabupaten Katingan', NULL, NULL, NULL),
(17, 'Dinas Kebudayaan, Kepemudaan dan Olahraga serta Pariwisata Kabupaten Katingan', NULL, NULL, NULL),
(18, 'Dinas Ketahanan Pangan dan Pertanian Kabupaten Katingan', NULL, NULL, NULL),
(19, 'Badan Perencanaan Pembangunan Daerah, Penelitian dan Pengembangan Kabupaten Katingan', NULL, NULL, NULL),
(20, 'Badan Keuangan dan Aset Daerah Kabupaten Katingan', NULL, NULL, NULL),
(21, 'Badan Pendapatan Daerah Kabupaten Katingan', NULL, NULL, NULL),
(22, 'Badan Kepegawaian dan Pengembangan Sumber Daya Manusia Kabupaten Katingan', NULL, NULL, NULL),
(23, 'Badan Kesatuan Bangsa dan Politik Kabupaten Katingan', NULL, NULL, NULL),
(24, 'Badan Penanggulangan Bencana Daerah Kabupaten Katingan', NULL, NULL, NULL),
(25, 'Kecamatan Katingan Kuala', NULL, NULL, NULL),
(26, 'Kecamatan Mendawai', NULL, NULL, NULL),
(27, 'Kecamatan Kamipang', NULL, NULL, NULL),
(28, 'Kecamatan Tasik Payawan', NULL, NULL, NULL),
(29, 'Kecamatan Katingan Hilir', NULL, NULL, NULL),
(30, 'Kecamatan Tewang Sangalang Garing', NULL, NULL, NULL),
(31, 'Kecamatan Pulau Malan', NULL, NULL, NULL),
(32, 'Kecamatan Katingan Tengah', NULL, NULL, NULL),
(33, 'Kecamatan Sanaman Mantikei', NULL, NULL, NULL),
(34, 'Kecamatan Marikit', NULL, NULL, NULL),
(35, 'Kecamatan Katingan Hulu', NULL, NULL, NULL),
(36, 'Kecamatan Petak Malai', NULL, NULL, NULL),
(37, 'Kecamatan Bukit Raya.', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id_menu` int NOT NULL,
  `nama_menu` varchar(50) DEFAULT NULL,
  `id_parent_menu` int DEFAULT NULL,
  `nama_module` varchar(100) DEFAULT NULL,
  `nama_class` varchar(100) DEFAULT NULL,
  `class_icon` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `order_menu` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id_menu`, `nama_menu`, `id_parent_menu`, `nama_module`, `nama_class`, `class_icon`, `order_menu`, `created_at`, `deleted_at`, `updated_at`) VALUES
(1, 'User', 0, 'user', 'User', 'fas fa-user', 2, '2020-01-14 10:16:58', NULL, '2020-01-26 11:44:32'),
(5, 'Dashboard', 0, 'dashboard', 'Dashboard', 'fas fa-tachometer-alt', 1, '2020-01-14 14:06:31', NULL, NULL),
(6, 'Level User', 0, 'level_user', 'Level_user', 'icon-accessibility', 3, '2020-01-14 14:21:49', NULL, '2020-01-26 11:45:08'),
(7, 'Menu', 0, 'menu', 'Menu', 'icon-menu4', 4, '2020-01-19 13:40:22', NULL, '2020-01-26 11:47:37'),
(9, 'Privilege Menu', 0, 'privilege_level', 'Privilege_level', 'icon-list', 5, '2020-01-19 11:22:27', NULL, '2020-01-26 11:47:04'),
(10, 'Menu 1', 0, 'menu_1', 'Menu_1', 'fas fa-columns', 6, '2021-08-13 08:41:01', '2021-08-16 03:31:25', NULL),
(11, 'Sub Menu 1', 10, 'sub_menu_1', 'Sub_menu_1', 'fas fa-columns', 1, '2021-08-13 08:41:29', '2021-08-16 01:41:29', NULL),
(12, 'de', 0, 'de', 'de', NULL, 23, '2021-08-16 01:22:03', '2021-08-16 01:38:43', NULL),
(13, 'Usulan', 0, 'usulan_raperbup', 'Usulan_raperbup', 'ion ion-paper-airpla', 7, '2021-08-16 07:54:59', NULL, NULL),
(14, 'Monitoring Usulan', 0, 'monitoring_raperbup', 'Monitoring_raperbup', 'far fa-copy', 9, '2021-08-17 21:47:50', NULL, '2021-08-22 20:19:32'),
(15, 'Nomor Register', 0, 'nomor_register', 'Nomor_register', 'fas fa-list-ol', 8, '2021-08-22 20:19:24', NULL, NULL),
(16, 'Analisis Hukum', 0, 'analisis_hukum', 'Analisis_hukum', 'ion ion-ios-paper', 10, '2022-06-22 03:37:22', NULL, NULL),
(17, 'Template Usulan', 0, 'template_usulan', 'Template_usulan', 'ion ion-ios-bookmarks', 10, '2022-12-20 09:41:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `privilege_level_menu`
--

CREATE TABLE `privilege_level_menu` (
  `id_privilege` int NOT NULL,
  `level_user_id` int DEFAULT NULL,
  `menu_id` int DEFAULT NULL,
  `create_content` enum('1','0') DEFAULT NULL,
  `update_content` enum('1','0') DEFAULT NULL,
  `delete_content` enum('1','0') DEFAULT NULL,
  `view_content` enum('1','0') DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `privilege_level_menu`
--

INSERT INTO `privilege_level_menu` (`id_privilege`, `level_user_id`, `menu_id`, `create_content`, `update_content`, `delete_content`, `view_content`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, '1', '1', '1', '1', '2020-01-17 14:20:33', '2021-08-16 07:17:29', NULL),
(2, 1, 5, '1', '1', '1', '1', '2020-01-17 14:20:54', '2021-08-16 07:17:29', '2021-01-05 08:44:39'),
(4, 1, 6, '1', '1', '1', '1', '2020-01-17 14:33:15', '2020-09-20 03:05:31', '2020-09-04 02:02:23'),
(5, 1, 7, '1', '1', '1', '1', '2020-01-19 13:41:58', '2021-08-16 07:17:29', NULL),
(6, 1, 9, '1', '1', '1', '1', '2020-01-19 23:23:38', '2021-08-16 07:17:29', NULL),
(7, 1, 5, '1', '1', '1', '1', '2021-01-05 08:44:52', '2021-08-16 07:17:29', NULL),
(8, 1, 10, '1', '1', '1', '1', '2021-08-13 08:41:47', NULL, NULL),
(9, 1, 11, '1', '1', '1', '1', '2021-08-13 08:41:56', NULL, NULL),
(10, 1, 6, '1', '1', '1', '1', '2021-08-16 07:17:29', NULL, NULL),
(11, 4, 1, '1', '1', '1', '1', '2021-08-16 07:42:07', '2022-12-20 09:41:08', NULL),
(12, 4, 5, '1', '1', '1', '1', '2021-08-16 07:42:07', '2022-12-20 09:41:08', NULL),
(13, 8, 5, '1', '1', '1', '1', '2021-08-16 07:52:23', '2021-08-18 08:58:14', NULL),
(14, 11, 5, '1', '1', '1', '1', '2021-08-16 07:52:33', '2021-08-18 08:58:41', NULL),
(15, 6, 5, '1', '1', '1', '1', '2021-08-16 07:52:41', '2021-08-22 20:19:39', NULL),
(16, 7, 5, '1', '1', '1', '1', '2021-08-16 07:52:48', '2021-08-18 08:58:21', NULL),
(17, 5, 5, '1', '1', '1', '1', '2021-08-16 07:52:55', '2021-08-16 07:55:10', NULL),
(18, 9, 5, '1', '1', '1', '1', '2021-08-16 07:53:01', '2021-08-18 08:58:29', NULL),
(19, 10, 5, '1', '1', '1', '1', '2021-08-16 07:53:06', '2021-08-18 08:58:35', NULL),
(20, 5, 13, '1', '1', '1', '1', '2021-08-16 07:55:10', NULL, NULL),
(21, 6, 14, '1', '1', '1', '1', '2021-08-17 21:47:59', '2021-08-22 20:19:39', NULL),
(22, 8, 14, '1', '1', '1', '1', '2021-08-18 08:58:14', NULL, NULL),
(23, 7, 14, '1', '1', '1', '1', '2021-08-18 08:58:21', NULL, NULL),
(24, 9, 14, '1', '1', '1', '1', '2021-08-18 08:58:29', NULL, NULL),
(25, 10, 14, '1', '1', '1', '1', '2021-08-18 08:58:35', NULL, NULL),
(26, 11, 14, '1', '1', '1', '1', '2021-08-18 08:58:41', NULL, NULL),
(27, 6, 15, '1', '1', '1', '1', '2021-08-22 20:19:39', NULL, NULL),
(28, 4, 14, '1', '1', '1', '1', '2022-03-09 09:23:01', '2022-12-20 09:41:08', NULL),
(29, 4, 15, '1', '1', '1', '1', '2022-03-09 09:23:01', '2022-12-20 09:41:08', NULL),
(30, 12, 5, '1', '1', '1', '1', '2022-04-11 13:14:22', NULL, NULL),
(31, 12, 14, '1', '1', '1', '1', '2022-04-11 13:14:22', NULL, NULL),
(32, 4, 16, '1', '1', '1', '1', '2022-06-22 03:37:30', '2022-12-20 09:41:08', NULL),
(33, 13, 14, '1', '1', '1', '1', '2022-06-22 07:20:26', '2022-06-22 07:20:43', NULL),
(34, 13, 5, '1', '1', '1', '1', '2022-06-22 07:20:43', NULL, NULL),
(35, 4, 17, '1', '1', '1', '1', '2022-12-20 09:41:08', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sub_master_satker`
--

CREATE TABLE `sub_master_satker` (
  `id_sub_master_satker` int NOT NULL,
  `id_master_satker` int DEFAULT NULL,
  `nama_sub_master_satker` varchar(200) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `edited_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trx_raperbup`
--

CREATE TABLE `trx_raperbup` (
  `id_trx_raperbup` int NOT NULL,
  `file_usulan_raperbup` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT 'file usulan dan perbaikan dari perancang',
  `file_catatan_perbaikan` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT 'file upload dari kasubbag',
  `file_perbaikan` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT 'file upload perbaikan dari perancang uu',
  `file_final` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `usulan_raperbup_id` int DEFAULT NULL,
  `level_user_id_status` int DEFAULT NULL,
  `status_tracking` enum('1','2','3','4','5','6') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '1 = perancang UU upload dokumen, 2 = kabag hukum disposisi ke kasubbag untuk diperiksa, 3 = koreksi bagian kasubbag => ada upload dokumen, 4 = upload perbaikan dari perancang => ada upload dokumen',
  `kasubbag_agree_disagree` enum('1','2') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `kabag_agree_disagree` enum('1','2') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `asisten_agree_disagree` enum('1','2') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `sekda_agree_disagree` enum('1','2') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `wabup_agree_disagree` enum('1','2') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `bupati_agree_disagree` enum('1','2') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `provinsi_agree_disagree` enum('1','2') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `catatan_ditolak` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `file_lampiran_provinsi` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `id_user_created` int DEFAULT NULL,
  `id_user_updated` int DEFAULT NULL,
  `id_user_deleted` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int NOT NULL,
  `nama_lengkap` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `level_user_id` int DEFAULT NULL,
  `master_satker_id` int DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `nama_lengkap`, `username`, `password`, `level_user_id`, `master_satker_id`, `keterangan`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Handri Darmawan', 'superadmin', '$2y$12$2d2GEFgIkv/WhniVgh4aFuQFRLryQ1BOn6CnZBmft2XP2WR.MP7ua', 1, NULL, NULL, '2021-08-12 01:28:02', '2021-08-12 01:28:02', NULL),
(2, 'admin', 'admin', '$2y$12$awtMUBHouGTrcm.MlPkPpOFw9b4wb5uUnlALApaMDOxrWSWR9aaAu', 4, NULL, NULL, '2021-08-16 07:44:14', '2022-12-20 09:44:53', NULL),
(4, 'Kabag Hukum', 'kabag_hukum', '$2y$12$neqUg7cfS8FBSWN7QCyAZuZtImSEszWkezilqHdTBBcawPZw9diuy', 6, NULL, NULL, '2021-08-16 07:49:55', '2022-12-20 09:46:15', NULL),
(5, 'Kasubbag 1', 'kasubbag1', '$2y$12$e4xJeCv6xxHTzTq.2RddTOSqRmvpN5U04dR0PzBB1/EENz0YGOX9C', 7, NULL, 'kasubag 1', '2021-08-16 07:51:14', '2022-12-20 09:45:23', NULL),
(6, 'Asisten Pemerintahan & Kesra', 'a1', '$2y$12$tk3A/fqKZ7yuT.56uya7qumyzMFqF7Sp4EdCFF1kWKtWT/RIN/4O.', 8, NULL, NULL, '2021-08-16 07:51:37', '2022-12-20 09:47:07', NULL),
(7, 'wabup', 'wabup', '$2y$12$l3mxCHn.CQSE3VqW9LuvauhnUnOjB7WzjF5MxMR8krxtw4RY2Gm9.', 10, NULL, NULL, '2021-08-16 07:51:51', '2022-12-20 09:47:49', NULL),
(8, 'Bupati', 'bupati', '$2y$12$xjwgDjtOD5MzxlSE7iUpLe8SOGv6MpC6M84L8iSeZsxuQ.rNIQD7.', 11, NULL, NULL, '2021-08-16 07:52:06', '2022-12-20 09:47:58', NULL),
(9, 'Sekda', 'sekda', '$2y$12$7YD8Vl50cYBdcDmKrVrti.IJM7kOlGntYK4ZMCdLaxIQg9HVv0m..', 9, NULL, NULL, '2021-08-19 07:41:19', '2022-12-20 09:47:25', NULL),
(10, 'kasubbag 2', 'kasubbag2', '$2y$12$gqkTIQXp9wnkJqYNa/.Mnu2Jky02goUcUEacI/ON4D5jPzuDS6Pky', 7, NULL, 'kasubag 2', '2021-08-22 21:42:30', '2022-12-20 09:45:48', NULL),
(11, 'kasubbag3', 'kasubbag3', '$2y$12$NoehOkWEIcZxqROwHTI5W.KC2QHzq8Q3iJrGgOsgTyzcvv4ZRCSEW', 7, NULL, 'kasubag 3', '2021-08-22 21:42:53', '2022-12-20 09:45:59', NULL),
(15, 'Admin Diskominfo', 'admin_dis', '$2y$12$Vym7.MCzxHbQwzyRRIio4Ot9tUX4.UTgxnFddUve6BY6mmqFioLd.', 13, NULL, NULL, '2022-06-22 07:05:44', NULL, NULL),
(16, 'Dinas Komunikasi Informatika Statistik dan Persandian', 'diskominfo', '$2y$12$iECC79SZblYqQTSglJc/aeUWoUdt3vfRJve6CsQn3CR8fC5jOsuJq', 5, 1, NULL, '2022-12-20 10:03:06', NULL, NULL),
(17, 'DINAS KOMINFO', 'KOMINFO', '$2y$12$en6CNDswmGUaAdfLDd4bDObTECBzxfpHOgUfmWR7Q25vHejHucKOG', 5, 1, NULL, '2023-01-07 15:48:50', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `usulan_raperbup`
--

CREATE TABLE `usulan_raperbup` (
  `id_usulan_raperbup` int NOT NULL,
  `nama_peraturan` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `nomor_register` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `lampiran` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `lampiran_sk_tim` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `lampiran_daftar_hadir` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `id_user_kasubbag` int DEFAULT NULL,
  `last_level_user` int DEFAULT NULL,
  `kategori_usulan_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `id_user_created` int DEFAULT NULL,
  `id_user_updated` int DEFAULT NULL,
  `id_user_deleted` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `analisis_hukum`
--
ALTER TABLE `analisis_hukum`
  ADD PRIMARY KEY (`id_analisis_hukum`);

--
-- Indexes for table `kategori_usulan`
--
ALTER TABLE `kategori_usulan`
  ADD PRIMARY KEY (`id_kategori_usulan`);

--
-- Indexes for table `level_user`
--
ALTER TABLE `level_user`
  ADD PRIMARY KEY (`id_level_user`);

--
-- Indexes for table `link_analisis_hukum`
--
ALTER TABLE `link_analisis_hukum`
  ADD PRIMARY KEY (`id_link_analisis_hukum`);

--
-- Indexes for table `master_satker`
--
ALTER TABLE `master_satker`
  ADD PRIMARY KEY (`id_master_satker`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id_menu`);

--
-- Indexes for table `privilege_level_menu`
--
ALTER TABLE `privilege_level_menu`
  ADD PRIMARY KEY (`id_privilege`),
  ADD KEY `fk_privilage_level_menu` (`level_user_id`),
  ADD KEY `fk_privilage_menu_id` (`menu_id`);

--
-- Indexes for table `sub_master_satker`
--
ALTER TABLE `sub_master_satker`
  ADD PRIMARY KEY (`id_sub_master_satker`);

--
-- Indexes for table `trx_raperbup`
--
ALTER TABLE `trx_raperbup`
  ADD PRIMARY KEY (`id_trx_raperbup`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `usulan_raperbup`
--
ALTER TABLE `usulan_raperbup`
  ADD PRIMARY KEY (`id_usulan_raperbup`),
  ADD UNIQUE KEY `UNIQUE` (`nomor_register`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `analisis_hukum`
--
ALTER TABLE `analisis_hukum`
  MODIFY `id_analisis_hukum` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategori_usulan`
--
ALTER TABLE `kategori_usulan`
  MODIFY `id_kategori_usulan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `level_user`
--
ALTER TABLE `level_user`
  MODIFY `id_level_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `link_analisis_hukum`
--
ALTER TABLE `link_analisis_hukum`
  MODIFY `id_link_analisis_hukum` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_satker`
--
ALTER TABLE `master_satker`
  MODIFY `id_master_satker` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id_menu` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `privilege_level_menu`
--
ALTER TABLE `privilege_level_menu`
  MODIFY `id_privilege` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `sub_master_satker`
--
ALTER TABLE `sub_master_satker`
  MODIFY `id_sub_master_satker` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trx_raperbup`
--
ALTER TABLE `trx_raperbup`
  MODIFY `id_trx_raperbup` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `usulan_raperbup`
--
ALTER TABLE `usulan_raperbup`
  MODIFY `id_usulan_raperbup` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `privilege_level_menu`
--
ALTER TABLE `privilege_level_menu`
  ADD CONSTRAINT `fk_privilage_level_menu` FOREIGN KEY (`level_user_id`) REFERENCES `level_user` (`id_level_user`),
  ADD CONSTRAINT `fk_privilage_menu_id` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id_menu`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
