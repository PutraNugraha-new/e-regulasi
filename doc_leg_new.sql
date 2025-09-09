-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 03, 2024 at 04:53 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `doc_leg`
--

-- --------------------------------------------------------

--
-- Table structure for table `analisis_hukum`
--

CREATE TABLE `analisis_hukum` (
  `id_analisis_hukum` int(11) NOT NULL,
  `judul` text DEFAULT NULL,
  `file` varchar(100) DEFAULT NULL,
  `taging` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `analisis_hukum`
--

INSERT INTO `analisis_hukum` (`id_analisis_hukum`, `judul`, `file`, `taging`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Analisis Hukum 1', 'f952ce6999d46e848bf83e7ecaa72597.pdf', 'hukum,contoh', '2024-03-03 00:14:17', '2024-03-03 01:03:21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kategori_usulan`
--

CREATE TABLE `kategori_usulan` (
  `id_kategori_usulan` int(11) NOT NULL,
  `nama_kategori` varchar(100) DEFAULT NULL,
  `teruskan_provinsi` enum('0','1') DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `id_level_user` int(11) NOT NULL,
  `nama_level_user` varchar(255) DEFAULT NULL,
  `urutan_legalitas` int(11) DEFAULT NULL,
  `class_color` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `id_link_analisis_hukum` int(11) NOT NULL,
  `external_link` text DEFAULT NULL,
  `analisis_hukum_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `link_analisis_hukum`
--

INSERT INTO `link_analisis_hukum` (`id_link_analisis_hukum`, `external_link`, `analisis_hukum_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'https://e-regulasi.katingankab.go.id/analisis_hukum/tambah_analisis_hukum', 1, '2024-03-03 00:14:17', '2024-03-03 01:03:21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `master_satker`
--

CREATE TABLE `master_satker` (
  `id_master_satker` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `id_menu` int(11) NOT NULL,
  `nama_menu` varchar(50) DEFAULT NULL,
  `id_parent_menu` int(11) DEFAULT NULL,
  `nama_module` varchar(100) DEFAULT NULL,
  `nama_class` varchar(100) DEFAULT NULL,
  `class_icon` varchar(100) DEFAULT NULL,
  `order_menu` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `id_privilege` int(11) NOT NULL,
  `level_user_id` int(11) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `create_content` enum('1','0') DEFAULT NULL,
  `update_content` enum('1','0') DEFAULT NULL,
  `delete_content` enum('1','0') DEFAULT NULL,
  `view_content` enum('1','0') DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
(11, 4, 1, '1', '1', '1', '1', '2021-08-16 07:42:07', '2024-03-03 10:51:23', NULL),
(12, 4, 5, '1', '1', '1', '1', '2021-08-16 07:42:07', '2024-03-03 10:51:23', NULL),
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
(28, 4, 14, '1', '1', '1', '1', '2022-03-09 09:23:01', '2024-03-03 10:51:23', NULL),
(29, 4, 15, '1', '1', '1', '1', '2022-03-09 09:23:01', '2024-03-03 10:51:23', NULL),
(30, 12, 5, '1', '1', '1', '1', '2022-04-11 13:14:22', NULL, NULL),
(31, 12, 14, '1', '1', '1', '1', '2022-04-11 13:14:22', NULL, NULL),
(32, 4, 16, '1', '1', '1', '1', '2022-06-22 03:37:30', '2024-03-03 10:51:23', NULL),
(33, 13, 14, '1', '1', '1', '1', '2022-06-22 07:20:26', '2022-06-22 07:20:43', NULL),
(34, 13, 5, '1', '1', '1', '1', '2022-06-22 07:20:43', NULL, NULL),
(35, 4, 17, '1', '1', '1', '1', '2022-12-20 09:41:08', '2024-03-03 10:51:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sub_master_satker`
--

CREATE TABLE `sub_master_satker` (
  `id_sub_master_satker` int(11) NOT NULL,
  `id_master_satker` int(11) DEFAULT NULL,
  `nama_sub_master_satker` varchar(200) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `edited_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `template_usulan`
--

CREATE TABLE `template_usulan` (
  `id_template_usulan` int(11) NOT NULL,
  `nama_template` varchar(100) NOT NULL,
  `file_template` int(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `trx_raperbup`
--

CREATE TABLE `trx_raperbup` (
  `id_trx_raperbup` int(11) NOT NULL,
  `file_usulan_raperbup` varchar(200) DEFAULT NULL COMMENT 'file usulan dan perbaikan dari perancang',
  `file_catatan_perbaikan` varchar(200) DEFAULT NULL COMMENT 'file upload dari kasubbag',
  `file_perbaikan` varchar(200) DEFAULT NULL COMMENT 'file upload perbaikan dari perancang uu',
  `file_final` varchar(200) DEFAULT NULL,
  `usulan_raperbup_id` int(11) DEFAULT NULL,
  `level_user_id_status` int(11) DEFAULT NULL,
  `status_tracking` enum('1','2','3','4','5','6') DEFAULT NULL COMMENT '1 = perancang UU upload dokumen, 2 = kabag hukum disposisi ke kasubbag untuk diperiksa, 3 = koreksi bagian kasubbag => ada upload dokumen, 4 = upload perbaikan dari perancang => ada upload dokumen',
  `kasubbag_agree_disagree` enum('1','2') DEFAULT NULL,
  `kabag_agree_disagree` enum('1','2') DEFAULT NULL,
  `asisten_agree_disagree` enum('1','2') DEFAULT NULL,
  `sekda_agree_disagree` enum('1','2') DEFAULT NULL,
  `wabup_agree_disagree` enum('1','2') DEFAULT NULL,
  `bupati_agree_disagree` enum('1','2') DEFAULT NULL,
  `provinsi_agree_disagree` enum('1','2') DEFAULT NULL,
  `catatan_ditolak` text DEFAULT NULL,
  `file_lampiran_provinsi` varchar(100) DEFAULT NULL,
  `status_pesan` enum('1','2') NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `id_user_created` int(11) DEFAULT NULL,
  `id_user_updated` int(11) DEFAULT NULL,
  `id_user_deleted` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `trx_raperbup`
--

INSERT INTO `trx_raperbup` (`id_trx_raperbup`, `file_usulan_raperbup`, `file_catatan_perbaikan`, `file_perbaikan`, `file_final`, `usulan_raperbup_id`, `level_user_id_status`, `status_tracking`, `kasubbag_agree_disagree`, `kabag_agree_disagree`, `asisten_agree_disagree`, `sekda_agree_disagree`, `wabup_agree_disagree`, `bupati_agree_disagree`, `provinsi_agree_disagree`, `catatan_ditolak`, `file_lampiran_provinsi`, `status_pesan`, `created_at`, `updated_at`, `deleted_at`, `id_user_created`, `id_user_updated`, `id_user_deleted`) VALUES
(11, 'd36f611026f936352bc0663d0f946689.docx', NULL, NULL, NULL, 3, 5, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2', '2023-08-22 16:35:37', NULL, NULL, 16, NULL, NULL),
(12, 'd36f611026f936352bc0663d0f946689.docx', NULL, NULL, NULL, 3, 4, '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'lengkap', NULL, '2', '2023-08-22 16:37:28', '2023-08-22 16:39:08', NULL, 2, 2, NULL),
(13, 'd36f611026f936352bc0663d0f946689.docx', NULL, NULL, NULL, 3, 7, '3', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2', '2023-08-22 16:41:00', NULL, NULL, 23, NULL, NULL),
(14, 'd36f611026f936352bc0663d0f946689.docx', NULL, NULL, NULL, 3, 6, '3', '1', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2', '2023-08-22 16:44:53', NULL, NULL, 4, NULL, NULL),
(15, 'd36f611026f936352bc0663d0f946689.docx', NULL, NULL, NULL, 3, 8, '3', '1', '1', '1', NULL, NULL, NULL, '1', NULL, NULL, '2', '2023-08-22 16:46:58', NULL, NULL, 6, NULL, NULL),
(16, 'd36f611026f936352bc0663d0f946689.docx', NULL, NULL, NULL, 3, 9, '3', '1', '1', '1', '1', NULL, NULL, '1', NULL, NULL, '2', '2023-08-22 16:47:23', NULL, NULL, 9, NULL, NULL),
(17, 'd36f611026f936352bc0663d0f946689.docx', NULL, NULL, NULL, 3, 10, '3', '1', '1', '1', '1', '1', NULL, '1', NULL, NULL, '2', '2023-08-22 16:47:44', NULL, NULL, 7, NULL, NULL),
(18, 'd36f611026f936352bc0663d0f946689.docx', NULL, NULL, NULL, 3, 11, '3', '1', '1', '1', '1', '1', '1', '1', NULL, NULL, '2', '2023-08-22 16:48:05', NULL, NULL, 8, NULL, NULL),
(19, '19f8476cb8b44d236db21b26f456dba0.docx', NULL, NULL, NULL, 4, 5, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2', '2023-08-22 16:51:38', NULL, NULL, 16, NULL, NULL),
(20, '19f8476cb8b44d236db21b26f456dba0.docx', NULL, NULL, NULL, 4, 4, '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'lengkap', NULL, '2', '2023-08-22 16:52:42', NULL, NULL, 2, NULL, NULL),
(21, '19f8476cb8b44d236db21b26f456dba0.docx', '1517f174dac812332c4828cbd56b34d9.docx', NULL, NULL, 4, 7, '3', '2', NULL, NULL, NULL, NULL, NULL, NULL, 'Mohon dikoreksi pada bagian Diktum KESATU', NULL, '2', '2023-08-22 16:57:34', NULL, NULL, 23, NULL, NULL),
(22, '5f0c0964b64e9e4e3fed8ee7495865cb.doc', NULL, NULL, NULL, 5, 5, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '2023-12-28 16:06:43', NULL, '2023-12-28 16:07:08', 16, NULL, 16),
(23, '0297d081c15e586d9db9d2b1675f8542.pdf', NULL, NULL, NULL, 6, 5, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '2024-03-02 21:24:48', '2024-03-02 22:45:51', NULL, 30, 30, NULL),
(24, '0297d081c15e586d9db9d2b1675f8542.pdf', NULL, NULL, NULL, 6, 4, '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'lengkapsd', NULL, '1', '2024-03-02 21:39:56', '2024-03-02 23:45:20', NULL, 29, 29, NULL),
(25, 'bd258bdffef392feb3c3fea82823536f.pdf', NULL, NULL, NULL, 7, 5, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '2024-03-02 23:05:45', '2024-03-02 23:23:53', NULL, 30, 30, NULL),
(28, 'bd258bdffef392feb3c3fea82823536f.pdf', NULL, NULL, NULL, 7, 6, '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'silahkan diproses', NULL, '1', '2024-03-03 00:01:27', NULL, NULL, 31, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nama_lengkap` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `level_user_id` int(11) DEFAULT NULL,
  `master_satker_id` int(11) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `nama_lengkap`, `username`, `password`, `level_user_id`, `master_satker_id`, `keterangan`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Otto ', 'superadmin', '$2y$12$jQDhRUVvAVqXpvSUMoAsa.bUGBoyKUKle6NGfrlPTUAI5cizRPUKq', 1, NULL, NULL, '2021-08-12 01:28:02', '2023-08-14 09:16:25', NULL),
(2, 'admin', 'admin', '$2y$12$4ZqupWZvyHfbK2XGDAd1FuHQlbRpXMSyPO1.4s/QjC3Nnog0Hn0yW', 4, NULL, NULL, '2021-08-16 07:44:14', '2023-08-14 09:59:48', NULL),
(4, 'Kabag Hukum', 'kabag', '$2y$12$oJAJG7TynUEZaVU1gmIKfeK/YhokWpngIlyCHjkI41IKycxsd9sdu', 6, NULL, NULL, '2021-08-16 07:49:55', '2023-08-22 18:46:18', NULL),
(5, 'Kasubbag 1', 'kasubbag1', '$2y$12$e4xJeCv6xxHTzTq.2RddTOSqRmvpN5U04dR0PzBB1/EENz0YGOX9C', 7, NULL, 'kasubag 1', '2021-08-16 07:51:14', '2022-12-20 09:45:23', '2023-08-22 18:47:17'),
(6, 'Asisten Pemerintahan & Kesra', 'a1', '$2y$12$bzzRcNDl6LpKcC15/zNSVuAIwVlBdkJD7briCHQtaEycAqF324k6O', 8, NULL, NULL, '2021-08-16 07:51:37', '2023-08-22 16:45:33', NULL),
(7, 'wabup', 'wabup', '$2y$12$4YoFmDZ6g0X8g3vf2h8D7OEZV9MAoUQ2kjOMW1h5WB6KVBXdD8a06', 10, NULL, NULL, '2021-08-16 07:51:51', '2023-08-22 16:46:12', NULL),
(8, 'Bupati', 'bupati', '$2y$12$Na8CybeYKFQcgT.sWZcTz.bWQVP4y3udKXtWH4qOG4xcbxFUd0cCu', 11, NULL, NULL, '2021-08-16 07:52:06', '2023-08-22 16:46:22', NULL),
(9, 'Sekda', 'sekda', '$2y$12$XXSQ4nBQ2HoTLlxRupdH9eyDNYleoSksC2tvnazUc7sa7lrzI3bSy', 9, NULL, NULL, '2021-08-19 07:41:19', '2023-08-22 16:45:47', NULL),
(10, 'kasubbag 2', 'kasubbag2', '$2y$12$gqkTIQXp9wnkJqYNa/.Mnu2Jky02goUcUEacI/ON4D5jPzuDS6Pky', 7, NULL, 'kasubag 2', '2021-08-22 21:42:30', '2022-12-20 09:45:48', '2023-08-22 18:47:21'),
(11, 'kasubbag3', 'kasubbag3', '$2y$12$NoehOkWEIcZxqROwHTI5W.KC2QHzq8Q3iJrGgOsgTyzcvv4ZRCSEW', 7, NULL, 'kasubag 3', '2021-08-22 21:42:53', '2022-12-20 09:45:59', '2023-08-22 18:47:30'),
(15, 'Admin Diskominfo', 'admin_dis', '$2y$12$Vym7.MCzxHbQwzyRRIio4Ot9tUX4.UTgxnFddUve6BY6mmqFioLd.', 13, NULL, NULL, '2022-06-22 07:05:44', NULL, NULL),
(16, 'Dinas Komunikasi Informatika Statistik dan Persandian', 'kominfo', '$2y$12$h07uRtjOGJPkWmdKukV7uesNqr9BURnEZdN3d9CZj8Nebk9wfakOi', 5, 1, NULL, '2022-12-20 10:03:06', '2024-03-02 21:55:08', NULL),
(17, 'DINAS KOMINFO', 'KOMINFO', '$2y$12$en6CNDswmGUaAdfLDd4bDObTECBzxfpHOgUfmWR7Q25vHejHucKOG', 5, 1, NULL, '2023-01-07 15:48:50', NULL, '2023-08-14 10:50:49'),
(18, 'admin', 'admin1', '$2y$12$MeY3CNjKMY/RUeAFRtBIQeNw9XqVmwpl8oi.QlJuF26H/1mqXya4C', 4, NULL, NULL, '2023-08-14 20:05:15', NULL, '2023-08-22 16:28:45'),
(19, 'Diskominfo Katingan', 'diskominfo_katingan', '$2y$12$766mxiTk0DZBC80jS6e5bOiMkYjbtidZWk3Nb9TYvx2AuCH2vb1qG', 5, 13, NULL, '2023-08-14 20:06:19', NULL, '2023-08-22 18:47:46'),
(20, 'Kasubbag Katingan', 'kasubbag_katingan', '$2y$12$NUGWBPqzjLar/owJtUWuOuRUKPnJqILwY0lRrLZ28xcTzBN5Bwxku', 7, NULL, 'Kassubag', '2023-08-14 20:07:48', NULL, '2023-08-22 18:47:26'),
(21, 'Yohana', 'nanavz', '$2y$12$2Gk9AdGNT0sZDqWvlYcQJOPXz75hpvkmBF5qgQB58SRSCzg.2yhPy', 4, NULL, NULL, '2023-08-22 16:27:51', NULL, NULL),
(22, 'Simamora', 'NYMR_KZO', '$2y$12$Cw1n/jfVwdJHOZkUjNCvRuqLjOfeU7Jz.pyi9iRnQ6ThiDJsVrNie', 4, NULL, NULL, '2023-08-22 16:28:32', NULL, NULL),
(23, 'Korektor', 'Korektor', '$2y$12$wFDR4Nz7N.J7hCKsdw22gu5OVgjOij.lQn5ZnW6DGYksViHYHoyQC', 7, NULL, 'Andre', '2023-08-22 16:31:13', '2024-03-02 21:42:49', NULL),
(24, 'ASPK', 'aspk', '$2y$12$IW6ntwkJKzniEzuQx4tKMuZe4X4QrStnlhAI2TTFge1VKT3DAs/vu', 8, NULL, NULL, '2023-12-04 15:06:49', NULL, '2023-12-04 15:16:36'),
(25, 'bupati1', 'bupati1', '$2y$12$1Be2MYAwpuopkhWRCBxps.IIol5lrYx.9hmLGis9OvASXFJOAYo1K', 11, NULL, NULL, '2023-12-04 15:07:11', NULL, NULL),
(26, 'kabag1', 'kabag1', '$2y$12$6c8laGfeV9x2GM2NIveDP.9l1WaUIVOblvNoB8D19Ntr3CxIPh8X6', 6, NULL, NULL, '2023-12-04 15:07:29', NULL, '2023-12-04 15:16:50'),
(27, 'korektor1', 'korektor1', '$2y$12$e5FL9SAd2Y9KjdPMxRYC/.A1IcK9ymrUumuNAI/sNBNZGs1IlJ.92', 7, NULL, 'korektor', '2023-12-04 15:08:06', NULL, '2023-12-04 15:16:57'),
(28, 'Kominfo - Super', 'dev-super-admin', '$2y$12$4ZqupWZvyHfbK2XGDAd1FuHQlbRpXMSyPO1.4s/QjC3Nnog0Hn0yW', 1, NULL, NULL, '2021-08-16 07:44:14', '2023-08-14 09:59:48', NULL),
(29, 'Kominfo - Admin Hukum', 'dev-admin', '$2y$12$4ZqupWZvyHfbK2XGDAd1FuHQlbRpXMSyPO1.4s/QjC3Nnog0Hn0yW', 4, NULL, NULL, '2021-08-16 07:44:14', '2023-08-14 09:59:48', NULL),
(30, 'Kominfo - Perancang UU', 'dev-perancang', '$2y$12$4ZqupWZvyHfbK2XGDAd1FuHQlbRpXMSyPO1.4s/QjC3Nnog0Hn0yW', 5, 13, NULL, '2021-08-16 07:44:14', '2023-08-14 09:59:48', NULL),
(31, 'Kominfo - Kabag Hukum', 'dev-kabag', '$2y$12$4ZqupWZvyHfbK2XGDAd1FuHQlbRpXMSyPO1.4s/QjC3Nnog0Hn0yW', 6, NULL, NULL, '2021-08-16 07:44:14', '2023-08-14 09:59:48', NULL),
(32, 'Kominfo - Kasubag Hukum', 'dev-kasubag', '$2y$12$4ZqupWZvyHfbK2XGDAd1FuHQlbRpXMSyPO1.4s/QjC3Nnog0Hn0yW', 7, NULL, NULL, '2021-08-16 07:44:14', '2023-08-14 09:59:48', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `usulan_raperbup`
--

CREATE TABLE `usulan_raperbup` (
  `id_usulan_raperbup` int(11) NOT NULL,
  `nama_peraturan` text DEFAULT NULL,
  `nomor_register` varchar(255) DEFAULT NULL,
  `lampiran` varchar(100) DEFAULT NULL,
  `lampiran_sk_tim` varchar(100) DEFAULT NULL,
  `lampiran_daftar_hadir` varchar(100) DEFAULT NULL,
  `id_user_kasubbag` int(11) DEFAULT NULL,
  `last_level_user` int(11) DEFAULT NULL,
  `kategori_usulan_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `id_user_created` int(11) DEFAULT NULL,
  `id_user_updated` int(11) DEFAULT NULL,
  `id_user_deleted` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `usulan_raperbup`
--

INSERT INTO `usulan_raperbup` (`id_usulan_raperbup`, `nama_peraturan`, `nomor_register`, `lampiran`, `lampiran_sk_tim`, `lampiran_daftar_hadir`, `id_user_kasubbag`, `last_level_user`, `kategori_usulan_id`, `created_at`, `updated_at`, `deleted_at`, `id_user_created`, `id_user_updated`, `id_user_deleted`) VALUES
(3, 'Surat Keputusan tentang Family Kit dan Kid Wear 2023', '180/1/HUK/2023', '09241afb5ae7f9d9bb5d65226fda09e4.docx', NULL, NULL, 23, NULL, 3, '2023-08-22 16:35:37', '2023-08-22 16:39:08', NULL, 16, 2, NULL),
(4, 'Surat Keputusan tentang Family Kit dan Kid Wear 2023', '180/2/HUK/2023', '70ed9bc7bf54e6c0ecdd6cbed0a3a77b.docx', NULL, NULL, 23, NULL, 3, '2023-08-22 16:51:38', '2023-08-22 16:52:42', NULL, 16, 2, NULL),
(5, 'bcb', NULL, '8e1cd76f3da907b8bd1d3fa20c6c2632.doc', NULL, NULL, NULL, NULL, 3, '2023-12-28 16:06:43', NULL, '2023-12-28 16:07:08', 16, NULL, 16),
(6, 'Contoh Nama Peraturan 1', '12313', 'aeb7f6196b83e88cacbd5ddae7903931.docx', NULL, NULL, 23, NULL, 3, '2024-03-02 21:24:48', '2024-03-02 23:45:20', NULL, 30, 29, NULL),
(7, 'Contoh Nama Peraturan 2 - Edit 1', '12314', '8333aae6ce0ef7347aa2fc9f038122f4.pdf', '9038af623f5a0c446c498d4734d6ed08.pdf', 'f01ab97474fe9a058746d6bfa668cf08.pdf', 23, NULL, 2, '2024-03-02 23:05:45', '2024-03-03 00:01:27', NULL, 30, 31, NULL);

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
-- Indexes for table `template_usulan`
--
ALTER TABLE `template_usulan`
  ADD PRIMARY KEY (`id_template_usulan`);

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
  MODIFY `id_analisis_hukum` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `kategori_usulan`
--
ALTER TABLE `kategori_usulan`
  MODIFY `id_kategori_usulan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `level_user`
--
ALTER TABLE `level_user`
  MODIFY `id_level_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `link_analisis_hukum`
--
ALTER TABLE `link_analisis_hukum`
  MODIFY `id_link_analisis_hukum` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `master_satker`
--
ALTER TABLE `master_satker`
  MODIFY `id_master_satker` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `privilege_level_menu`
--
ALTER TABLE `privilege_level_menu`
  MODIFY `id_privilege` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `sub_master_satker`
--
ALTER TABLE `sub_master_satker`
  MODIFY `id_sub_master_satker` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `template_usulan`
--
ALTER TABLE `template_usulan`
  MODIFY `id_template_usulan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `trx_raperbup`
--
ALTER TABLE `trx_raperbup`
  MODIFY `id_trx_raperbup` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `usulan_raperbup`
--
ALTER TABLE `usulan_raperbup`
  MODIFY `id_usulan_raperbup` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
