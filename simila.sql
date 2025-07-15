-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 24, 2025 at 10:10 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simila`
--

-- --------------------------------------------------------

--
-- Table structure for table `anggota_risets`
--

CREATE TABLE `anggota_risets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_risets` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `beasiswas`
--

CREATE TABLE `beasiswas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nama_siswa` varchar(191) NOT NULL,
  `raport` text NOT NULL,
  `surat_rekomendasi` text NOT NULL,
  `surat_motivasi` text NOT NULL,
  `portofolio` text NOT NULL,
  `status` enum('lolos','tidak lolos','proses') NOT NULL,
  `batch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `direkomendasikan` tinyint(1) NOT NULL DEFAULT 0,
  `catatan_rekomendasi` text DEFAULT NULL,
  `tanggal_rekomendasi` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `beasiswas`
--

INSERT INTO `beasiswas` (`id`, `user_id`, `nama_siswa`, `raport`, `surat_rekomendasi`, `surat_motivasi`, `portofolio`, `status`, `batch_id`, `created_at`, `updated_at`, `direkomendasikan`, `catatan_rekomendasi`, `tanggal_rekomendasi`) VALUES
(1, 2, 'siswa', 'beasiswa/raport/eBIgDxPneHUFG7T9hyc8v7sqWtCST99U6i4e6zBu.pdf', 'beasiswa/rekomendasi/bPGxQpypwjcJCFFwSmVkNAzwHpvbIYDKIEFzR5uS.pdf', 'beasiswa/motivasi/5ShYgUKbiWtK0eZKtsy0lqa2EKQxYkN2EyVc74dY.pdf', 'beasiswa/portofolio/uV8MnEC86oK8N14Ik4Z3ZdydRiABbDG1QvcVOgjy.pdf', 'tidak lolos', 3, '2025-06-22 22:12:35', '2025-06-22 22:14:32', 1, NULL, '2025-06-22 22:13:39');

-- --------------------------------------------------------

--
-- Table structure for table `beasiswa_batches`
--

CREATE TABLE `beasiswa_batches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `batch` varchar(191) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `status` enum('open','closed') NOT NULL DEFAULT 'open',
  `perusahaan_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `beasiswa_batches`
--

INSERT INTO `beasiswa_batches` (`id`, `batch`, `tanggal_mulai`, `tanggal_selesai`, `status`, `perusahaan_id`, `created_at`, `updated_at`) VALUES
(3, 'xsxs', '2025-06-23', '2025-06-28', 'open', 4, '2025-06-22 22:11:57', '2025-06-22 22:11:57');

-- --------------------------------------------------------

--
-- Table structure for table `certification_exams`
--

CREATE TABLE `certification_exams` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_ujian` varchar(191) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `kompetensi_terkait` varchar(191) DEFAULT NULL,
  `pembuat_user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `certification_exams`
--

INSERT INTO `certification_exams` (`id`, `nama_ujian`, `deskripsi`, `kompetensi_terkait`, `pembuat_user_id`, `created_at`, `updated_at`) VALUES
(2, 'Ujian', 'Best Friend', 'Coding', 4, '2025-06-22 22:04:48', '2025-06-23 13:46:49'),
(3, 'NIKI', 'ZEFANYA', 'CODING', 4, '2025-06-23 13:52:27', '2025-06-23 13:52:27'),
(4, 'Ocean', 'Engines', 'NIKI', 4, '2025-06-23 13:59:49', '2025-06-23 13:59:49'),
(5, 'Jilan', 'Nafihanan', 'Coding', 8, '2025-06-23 14:19:07', '2025-06-23 14:19:07');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(191) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guru_tamus`
--

CREATE TABLE `guru_tamus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_karyawan` varchar(191) NOT NULL,
  `jabatan` varchar(191) NOT NULL,
  `keahlian` varchar(191) NOT NULL,
  `deskripsi` text NOT NULL,
  `jadwal` text NOT NULL,
  `file_cv` varchar(191) DEFAULT NULL,
  `file_materi` varchar(191) NOT NULL,
  `status` enum('disetujui','proses') NOT NULL,
  `submitted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `guru_tamus`
--

INSERT INTO `guru_tamus` (`id`, `nama_karyawan`, `jabatan`, `keahlian`, `deskripsi`, `jadwal`, `file_cv`, `file_materi`, `status`, `submitted_by`, `created_at`, `updated_at`) VALUES
(1, 'sw', 'xs', 'xsw', 'ws', '2025-06-27 00:00:00', 'guru_tamu/cv//V2kn4nf7ptHBqLPIuaGQa47xfLfmVaN3qvnVcoSg.pdf', 'guru_tamu/materi//DkfPW1deZiBYEhmBRPAyi42JszPSzzNwbuEpPOS6.pdf', 'disetujui', 4, '2025-06-22 21:29:07', '2025-06-22 21:29:56');

-- --------------------------------------------------------

--
-- Table structure for table `kurikulums`
--

CREATE TABLE `kurikulums` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pengirim_id` bigint(20) UNSIGNED NOT NULL,
  `perusahaan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nama_kurikulum` varchar(191) NOT NULL,
  `deskripsi` longtext NOT NULL,
  `file_kurikulum` varchar(191) NOT NULL,
  `tahun_ajaran` longtext NOT NULL,
  `komentar` longtext DEFAULT NULL,
  `validasi_sekolah` enum('disetujui','proses','tidak_disetujui') DEFAULT NULL,
  `validasi_perusahaan` enum('disetujui','proses','tidak_disetujui') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kurikulums`
--

INSERT INTO `kurikulums` (`id`, `pengirim_id`, `perusahaan_id`, `nama_kurikulum`, `deskripsi`, `file_kurikulum`, `tahun_ajaran`, `komentar`, `validasi_sekolah`, `validasi_perusahaan`, `created_at`, `updated_at`) VALUES
(1, 4, NULL, 'aku', 'aku', 'kurikulum//WPA2xdA0Z05DHuWjAXdOnizyFuoJXZqASeivKvR9.pdf', '2019', NULL, 'disetujui', 'disetujui', '2025-06-22 21:22:11', '2025-06-22 21:22:38');

-- --------------------------------------------------------

--
-- Table structure for table `logbooks`
--

CREATE TABLE `logbooks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `siswa_id` bigint(20) UNSIGNED NOT NULL,
  `pkl_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('proses','disetujui','revisi') NOT NULL,
  `komentar_pembimbing` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `logbooks`
--

INSERT INTO `logbooks` (`id`, `siswa_id`, `pkl_id`, `status`, `komentar_pembimbing`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'proses', NULL, '2025-06-22 21:32:57', '2025-06-22 21:32:57');

-- --------------------------------------------------------

--
-- Table structure for table `logbook_content`
--

CREATE TABLE `logbook_content` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `nama` varchar(191) NOT NULL,
  `detail` text NOT NULL,
  `dokumentasi` text NOT NULL,
  `tanggal` date NOT NULL,
  `logbook_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `logbook_content`
--

INSERT INTO `logbook_content` (`id`, `created_at`, `updated_at`, `nama`, `detail`, `dokumentasi`, `tanggal`, `logbook_id`) VALUES
(2, '2025-06-22 21:37:42', '2025-06-22 21:37:42', 'xsx', 'xsx', 'logbook/HSjaKrkqqd8CxQXXY7jpkdBwewQ7DuJOjb6LB1kP.jpg', '2025-06-23', 1);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_06_03_192233_create_scouting_batches', 1),
(6, '2025_06_04_184623_create_kurikulums_table', 1),
(7, '2025_06_04_184711_create_projects_table', 1),
(8, '2025_06_04_184735_create_guru_tamus_table', 1),
(9, '2025_06_04_184757_create_pkls_table', 1),
(10, '2025_06_04_184758_create_logbooks_table', 1),
(11, '2025_06_04_184816_create_talent__scoutings_table', 1),
(12, '2025_06_04_184832_create_moocs_table', 1),
(13, '2025_06_04_184845_create_mooc_evals_table', 1),
(14, '2025_06_04_184907_create_sertifikasis_table', 1),
(15, '2025_06_04_184933_create_kuis_lsps_table', 1),
(16, '2025_06_04_184941_create_risets_table', 1),
(17, '2025_06_04_184945_create_soal_lsps_table', 1),
(18, '2025_06_04_184956_create_anggota_risets_table', 1),
(19, '2025_06_04_185016_create_beasiswas_table', 1),
(20, '2025_06_06_123351_create_permission_tables', 1),
(21, '2025_06_13_105515_add_reference_on_user_pkl_siswa', 1),
(22, '2025_06_17_173706_add_upload_fields_to_projects_table', 1),
(23, '2025_06_19_111225_create_table_logbook_content', 1),
(24, '2025_06_21_031751_add_submitted_by_to_guru_tamus_table', 1),
(25, '2025_06_21_141526_create_mooc_modules_table', 1),
(26, '2025_06_21_155840_create_beasiswa_batches_table', 1),
(27, '2025_06_21_163145_add_batch_id_to_beasiswas_table', 1),
(28, '2025_06_21_174927_add_user_id_to_beasiswas_table', 1),
(29, '2025_06_21_201458_add_rekomendasi_to_beasiswas_table', 1),
(30, '2025_06_22_000000_add_perusahaan_id_to_projects_table', 1),
(31, '2025_06_22_083005_add_sekolah_id_to_users_table', 1),
(32, '2025_06_22_220040_setup_certification_features', 2),
(33, '2025_06_22_234501_add_final_status_to_certification_exams_status', 3),
(34, '2025_06_23_143125_create_mooc_scores_table', 4),
(35, '2025_06_23_201748_revise_certification_features_schema', 5),
(36, '2025_06_23_165128_create_mooc_reflection', 6);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 5),
(3, 'App\\Models\\User', 4),
(3, 'App\\Models\\User', 9),
(3, 'App\\Models\\User', 10),
(4, 'App\\Models\\User', 2),
(5, 'App\\Models\\User', 3),
(6, 'App\\Models\\User', 6),
(7, 'App\\Models\\User', 7),
(8, 'App\\Models\\User', 8),
(9, 'App\\Models\\User', 1),
(9, 'App\\Models\\User', 2),
(9, 'App\\Models\\User', 3),
(9, 'App\\Models\\User', 4),
(9, 'App\\Models\\User', 5),
(9, 'App\\Models\\User', 6),
(9, 'App\\Models\\User', 7),
(9, 'App\\Models\\User', 8),
(9, 'App\\Models\\User', 9),
(9, 'App\\Models\\User', 10);

-- --------------------------------------------------------

--
-- Table structure for table `moocs`
--

CREATE TABLE `moocs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `judul_pelatihan` varchar(191) NOT NULL,
  `deskripsi` text NOT NULL,
  `perusahaan_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `moocs`
--

INSERT INTO `moocs` (`id`, `judul_pelatihan`, `deskripsi`, `perusahaan_id`, `created_at`, `updated_at`) VALUES
(1, 'adf', 'xdcfvtgjui', 4, '2025-06-22 21:47:53', '2025-06-22 21:47:53');

-- --------------------------------------------------------

--
-- Table structure for table `mooc_evals`
--

CREATE TABLE `mooc_evals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `soal` varchar(191) NOT NULL,
  `pilihan_jawaban_1` varchar(191) NOT NULL,
  `pilihan_jawaban_2` varchar(191) NOT NULL,
  `pilihan_jawaban_3` varchar(191) NOT NULL,
  `pilihan_jawaban_4` varchar(191) NOT NULL,
  `jawaban_benar` varchar(191) NOT NULL,
  `nilai_akhir` bigint(20) NOT NULL,
  `mooc_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mooc_modules`
--

CREATE TABLE `mooc_modules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `module_name` varchar(191) NOT NULL,
  `link_materi` varchar(191) NOT NULL,
  `dokumen_materi` varchar(191) NOT NULL,
  `mooc_id` bigint(20) UNSIGNED NOT NULL,
  `question` text NOT NULL,
  `pilihan_jawaban_1` text NOT NULL,
  `pilihan_jawaban_2` text NOT NULL,
  `pilihan_jawaban_3` text NOT NULL,
  `pilihan_jawaban_4` text NOT NULL,
  `answer` varchar(191) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mooc_modules`
--

INSERT INTO `mooc_modules` (`id`, `created_at`, `updated_at`, `module_name`, `link_materi`, `dokumen_materi`, `mooc_id`, `question`, `pilihan_jawaban_1`, `pilihan_jawaban_2`, `pilihan_jawaban_3`, `pilihan_jawaban_4`, `answer`) VALUES
(1, '2025-06-22 21:52:53', '2025-06-22 21:52:53', 'wxswx', 'https://docs.google.com/document/d/1KIUhVHVBYuwnn3tne2L91fcYL0OR8_L4vycvi-qtjqw/edit?tab=t.0', 'mooc_modules/wdJZPginmxJIwp1zJwjKOCVjaGL0zE7Y0QcH3th6.pdf', 1, 'edcd', 'ccc', 'sss', 'aaa', 'ddd', '1');

-- --------------------------------------------------------

--
-- Table structure for table `mooc_reflection`
--

CREATE TABLE `mooc_reflection` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `mooc_id` bigint(20) UNSIGNED NOT NULL,
  `reflection` varchar(191) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mooc_scores`
--

CREATE TABLE `mooc_scores` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `mooc_id` bigint(20) UNSIGNED NOT NULL,
  `score` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `guard_name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(191) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pkls`
--

CREATE TABLE `pkls` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(191) NOT NULL,
  `laporan_akhir` text DEFAULT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `perusahaan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pembimbing_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status_pembimbing` enum('disetujui','revisi','proses') NOT NULL,
  `status_waka_humas` enum('disetujui','proses') NOT NULL,
  `status` enum('proses','berjalan','selesai') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pkls`
--

INSERT INTO `pkls` (`id`, `nama`, `laporan_akhir`, `tanggal_mulai`, `tanggal_selesai`, `perusahaan_id`, `pembimbing_id`, `status_pembimbing`, `status_waka_humas`, `status`, `created_at`, `updated_at`) VALUES
(1, 'wsxswx', NULL, '2025-06-23', '2025-06-27', 4, NULL, 'proses', 'proses', 'berjalan', '2025-06-22 21:30:55', '2025-06-23 23:10:57');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `perusahaan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `judul` varchar(191) NOT NULL,
  `deskripsi` text NOT NULL,
  `file_brief` varchar(191) NOT NULL,
  `file_laporan` varchar(191) DEFAULT NULL,
  `is_manual_upload` tinyint(1) NOT NULL DEFAULT 0,
  `upload_notes` text DEFAULT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `perusahaan_id`, `judul`, `deskripsi`, `file_brief`, `file_laporan`, `is_manual_upload`, `upload_notes`, `tanggal_mulai`, `tanggal_selesai`, `created_at`, `updated_at`) VALUES
(1, 4, 'abc', 'abc', 'project/brief//r71PIxbC6yNmtZlCj1V5jS4GOiAydTSu7gYh5Lh6.pdf', 'project/laporan/hQvaVfTkrORnfKnrZih9ueZpsO3mmHtaiA8ARMms.pdf', 0, NULL, '2025-06-23', '2025-06-28', '2025-06-22 21:25:35', '2025-06-22 21:27:25');

-- --------------------------------------------------------

--
-- Table structure for table `risets`
--

CREATE TABLE `risets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `topik` varchar(191) NOT NULL,
  `deskripsi` varchar(191) NOT NULL,
  `tim_riset` varchar(191) NOT NULL,
  `file_proposal` text NOT NULL,
  `dokumentasi` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `guard_name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', '2025-06-22 15:00:20', '2025-06-22 15:00:20'),
(2, 'waka_kurikulum', 'web', '2025-06-22 15:00:20', '2025-06-22 15:00:20'),
(3, 'perusahaan', 'web', '2025-06-22 15:00:20', '2025-06-22 15:00:20'),
(4, 'siswa', 'web', '2025-06-22 15:00:20', '2025-06-22 15:00:20'),
(5, 'guru', 'web', '2025-06-22 15:00:20', '2025-06-22 15:00:20'),
(6, 'waka_humas', 'web', '2025-06-22 15:00:20', '2025-06-22 15:00:20'),
(7, 'alumni', 'web', '2025-06-22 15:00:20', '2025-06-22 15:00:20'),
(8, 'lsp', 'web', '2025-06-22 15:00:21', '2025-06-22 15:00:21'),
(9, 'user', 'web', '2025-06-22 15:00:21', '2025-06-22 15:00:21');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scouting_batches`
--

CREATE TABLE `scouting_batches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `batch` varchar(191) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `status` enum('open','close') NOT NULL,
  `perusahaan_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `scouting_batches`
--

INSERT INTO `scouting_batches` (`id`, `created_at`, `updated_at`, `batch`, `tanggal_mulai`, `tanggal_selesai`, `status`, `perusahaan_id`) VALUES
(1, '2025-06-22 21:42:55', '2025-06-22 21:42:55', 'Kerja', '2025-06-23', '2025-06-27', 'open', 4);

-- --------------------------------------------------------

--
-- Table structure for table `sertifikasis`
--

CREATE TABLE `sertifikasis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `lsp_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `perusahaan_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `certification_exam_id` bigint(20) UNSIGNED NOT NULL,
  `kompetensi` varchar(191) DEFAULT NULL,
  `dokumen_persyaratan` text NOT NULL,
  `nilai` int(11) DEFAULT NULL,
  `sertifikat_kelulusan` text DEFAULT NULL,
  `status_pendaftaran_ujian` enum('terdaftar','selesai_ujian','lulus','tidak_lulus') NOT NULL DEFAULT 'terdaftar',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sertifikasis`
--

INSERT INTO `sertifikasis` (`id`, `user_id`, `lsp_user_id`, `perusahaan_user_id`, `certification_exam_id`, `kompetensi`, `dokumen_persyaratan`, `nilai`, `sertifikat_kelulusan`, `status_pendaftaran_ujian`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, 4, 2, NULL, 'sertifikasi_dokumen_persyaratan/KzZX6DPoR08Yn8mszRH7Ja5atHXKDf1ThSrL7B69.pdf', 90, 'sertifikat_kelulusan/SjK4E9m5Yv23d2WBmCLPYo5vYeHdFzFD8g00AGxN.pdf', 'lulus', '2025-06-23 13:42:47', '2025-06-23 13:44:00'),
(2, 2, NULL, 4, 3, NULL, 'sertifikasi_dokumen_persyaratan/arBwwnLpMUDTgVN7C1sRcBCacfArKA7bvxh53DWc.pdf', 90, 'sertifikat_kelulusan/2ckld5vPNbVq5nC9Qb6m5euy1DmEw9DBEBGb95B1.pdf', 'tidak_lulus', '2025-06-23 13:53:00', '2025-06-23 13:53:30'),
(3, 2, NULL, 4, 4, 'NIKI', 'sertifikasi_dokumen_persyaratan/bkTiIShQu4IpA8UR3tcn9fpWMngSYxq26aZCFbL2.pdf', NULL, NULL, 'terdaftar', '2025-06-23 14:00:10', '2025-06-23 14:00:10'),
(4, 2, NULL, 8, 5, 'Coding', 'sertifikasi_dokumen_persyaratan/uStXlPMI2GfQK6Msg5A9FjcJtZa6mB8hqv1sf2bl.pdf', 90, 'sertifikat_kelulusan/e8wyR4CqEVh9C9J5fe6fgfFJ5qT2o22mvnyU2PVT.pdf', 'lulus', '2025-06-23 14:19:24', '2025-06-23 14:19:50');

-- --------------------------------------------------------

--
-- Table structure for table `talent_scoutings`
--

CREATE TABLE `talent_scoutings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `file_cv` varchar(191) NOT NULL,
  `file_ijazah` varchar(191) NOT NULL,
  `file_pernyataan` varchar(191) NOT NULL,
  `status_seleksi` enum('lolos','proses','tidak lolos') NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `batch_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `talent_scoutings`
--

INSERT INTO `talent_scoutings` (`id`, `file_cv`, `file_ijazah`, `file_pernyataan`, `status_seleksi`, `user_id`, `batch_id`, `created_at`, `updated_at`) VALUES
(1, 'talent_scoutings/cv/sSVjnG11mxmc9dKrMsihRbFbZGOvdLLx1MrGHeR7.pdf', 'talent_scoutings/ijazah/d0CootvlmzAdtAa4jdv65Ebk9DrTE4SG55YQYkdT.pdf', 'talent_scoutings/pernyataan/GXsPf3LAFqO9MaLSgiBhEiz62yWuFFLBO1FKe1lX.pdf', 'lolos', 7, 1, '2025-06-22 21:46:08', '2025-06-22 21:46:48');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sekolah_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `jenis_guru` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `pkl_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pkl_status` enum('disetujui','proses','tidak_disetujui') DEFAULT NULL,
  `laporan_pkl` varchar(191) DEFAULT NULL,
  `nilai_pkl` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `sekolah_id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `jenis_guru`, `created_at`, `updated_at`, `pkl_id`, `pkl_status`, `laporan_pkl`, `nilai_pkl`) VALUES
(1, NULL, 'admin', 'admin@example.com', NULL, '$2y$10$CguZ4r/Yn5iOwW5G1B367ep9eLVkxV9wj0G4qowypgpDkDdFGt.0S', NULL, NULL, '2025-06-22 15:00:21', '2025-06-22 15:00:21', NULL, NULL, NULL, NULL),
(2, NULL, 'siswa', 'siswa@example.com', NULL, '$2y$10$MJ7ZwPmyEazeEKlUQub/BecbY5KEyywWb8CQ48o07fMtlxBOCpgCK', NULL, NULL, '2025-06-22 15:00:21', '2025-06-22 21:32:57', 1, 'disetujui', NULL, NULL),
(3, NULL, 'guru', 'guru@example.com', NULL, '$2y$10$jZf99b54rMK3rtaeLY.qIed.cZyI8q5gffOtVnP24WL20pGudW.UK', NULL, NULL, '2025-06-22 15:00:21', '2025-06-22 15:00:21', NULL, NULL, NULL, NULL),
(4, NULL, 'perusahaan', 'perusahaan@example.com', NULL, '$2y$10$6Pa93BmIdlUI0KO94MeMtuX30rYaDqIls.kEGMKa2SyKjHzHrjjzW', NULL, NULL, '2025-06-22 15:00:21', '2025-06-22 15:00:21', NULL, NULL, NULL, NULL),
(5, NULL, 'waka_kurikulum', 'waka_kurikulum@example.com', NULL, '$2y$10$Pv/f3iIKx4uMuwvesGGiQ.kUAo0bPjPtdBK2YEmYSq5fyajmE8aom', NULL, NULL, '2025-06-22 15:00:21', '2025-06-22 15:00:21', NULL, NULL, NULL, NULL),
(6, NULL, 'waka_humas', 'waka_humas@example.com', NULL, '$2y$10$bM7fjDLHr8GpLuRcQ7IJhOR3wXHhGU2yNYT1wYuJn8IAAn.0.ekc.', NULL, NULL, '2025-06-22 15:00:21', '2025-06-22 15:00:21', NULL, NULL, NULL, NULL),
(7, NULL, 'alumni', 'alumni@example.com', NULL, '$2y$10$SDtuX0AVRti3DezXAuSLN.gIHNOL0fHatwQJAzGBeZ1SEsn.eY/ti', NULL, NULL, '2025-06-22 15:00:21', '2025-06-22 15:00:21', NULL, NULL, NULL, NULL),
(8, NULL, 'lsp', 'lsp@example.com', NULL, '$2y$10$TNB6uT5jB9PI7Q5pQGyeV.EHXlM4jLjigc7h23iAihNo/HsCod2l2', NULL, NULL, '2025-06-22 15:00:21', '2025-06-22 15:00:21', NULL, NULL, NULL, NULL),
(9, NULL, 'alifian', 'alifian@example.com', NULL, '$2y$10$rq74DsYfjquNQU41jY1Ja.YocT6EzzKRpkvF6U80UOMUmx7Vf0LXm', NULL, NULL, '2025-06-22 15:00:21', '2025-06-22 15:00:21', NULL, NULL, NULL, NULL),
(10, NULL, 'viantech', 'viantech@example.com', NULL, '$2y$10$4SBBnMPkFIRX.zIxzyDtru/c8DqeAHQ2OsOrddsQQf2o93Luf49QO', NULL, NULL, '2025-06-22 15:00:21', '2025-06-22 15:00:21', NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anggota_risets`
--
ALTER TABLE `anggota_risets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `anggota_risets_id_risets_foreign` (`id_risets`),
  ADD KEY `anggota_risets_user_id_foreign` (`user_id`);

--
-- Indexes for table `beasiswas`
--
ALTER TABLE `beasiswas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `beasiswas_batch_id_foreign` (`batch_id`),
  ADD KEY `beasiswas_user_id_foreign` (`user_id`);

--
-- Indexes for table `beasiswa_batches`
--
ALTER TABLE `beasiswa_batches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `beasiswa_batches_perusahaan_id_foreign` (`perusahaan_id`);

--
-- Indexes for table `certification_exams`
--
ALTER TABLE `certification_exams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `certification_exams_pembuat_user_id_foreign` (`pembuat_user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `guru_tamus`
--
ALTER TABLE `guru_tamus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guru_tamus_submitted_by_foreign` (`submitted_by`);

--
-- Indexes for table `kurikulums`
--
ALTER TABLE `kurikulums`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kurikulums_pengirim_id_foreign` (`pengirim_id`),
  ADD KEY `kurikulums_perusahaan_id_foreign` (`perusahaan_id`);

--
-- Indexes for table `logbooks`
--
ALTER TABLE `logbooks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `logbooks_siswa_id_foreign` (`siswa_id`),
  ADD KEY `logbooks_pkl_id_foreign` (`pkl_id`);

--
-- Indexes for table `logbook_content`
--
ALTER TABLE `logbook_content`
  ADD PRIMARY KEY (`id`),
  ADD KEY `logbook_content_logbook_id_foreign` (`logbook_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `moocs`
--
ALTER TABLE `moocs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `moocs_perusahaan_id_foreign` (`perusahaan_id`);

--
-- Indexes for table `mooc_evals`
--
ALTER TABLE `mooc_evals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mooc_evals_mooc_id_foreign` (`mooc_id`);

--
-- Indexes for table `mooc_modules`
--
ALTER TABLE `mooc_modules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mooc_modules_mooc_id_foreign` (`mooc_id`);

--
-- Indexes for table `mooc_reflection`
--
ALTER TABLE `mooc_reflection`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mooc_reflection_user_id_foreign` (`user_id`),
  ADD KEY `mooc_reflection_mooc_id_foreign` (`mooc_id`);

--
-- Indexes for table `mooc_scores`
--
ALTER TABLE `mooc_scores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mooc_scores_user_id_foreign` (`user_id`),
  ADD KEY `mooc_scores_mooc_id_foreign` (`mooc_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `pkls`
--
ALTER TABLE `pkls`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pkls_perusahaan_id_foreign` (`perusahaan_id`),
  ADD KEY `pkls_pembimbing_id_foreign` (`pembimbing_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `projects_perusahaan_id_foreign` (`perusahaan_id`);

--
-- Indexes for table `risets`
--
ALTER TABLE `risets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `scouting_batches`
--
ALTER TABLE `scouting_batches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `scouting_batches_perusahaan_id_foreign` (`perusahaan_id`);

--
-- Indexes for table `sertifikasis`
--
ALTER TABLE `sertifikasis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sertifikasis_user_id_foreign` (`user_id`),
  ADD KEY `sertifikasis_lsp_user_id_foreign` (`lsp_user_id`),
  ADD KEY `sertifikasis_perusahaan_user_id_foreign` (`perusahaan_user_id`),
  ADD KEY `sertifikasis_certification_exam_id_foreign` (`certification_exam_id`);

--
-- Indexes for table `talent_scoutings`
--
ALTER TABLE `talent_scoutings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `talent_scoutings_user_id_foreign` (`user_id`),
  ADD KEY `talent_scoutings_batch_id_foreign` (`batch_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_pkl_id_foreign` (`pkl_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anggota_risets`
--
ALTER TABLE `anggota_risets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `beasiswas`
--
ALTER TABLE `beasiswas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `beasiswa_batches`
--
ALTER TABLE `beasiswa_batches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `certification_exams`
--
ALTER TABLE `certification_exams`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guru_tamus`
--
ALTER TABLE `guru_tamus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `kurikulums`
--
ALTER TABLE `kurikulums`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `logbooks`
--
ALTER TABLE `logbooks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `logbook_content`
--
ALTER TABLE `logbook_content`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `moocs`
--
ALTER TABLE `moocs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `mooc_evals`
--
ALTER TABLE `mooc_evals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mooc_modules`
--
ALTER TABLE `mooc_modules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `mooc_reflection`
--
ALTER TABLE `mooc_reflection`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mooc_scores`
--
ALTER TABLE `mooc_scores`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pkls`
--
ALTER TABLE `pkls`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `risets`
--
ALTER TABLE `risets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `scouting_batches`
--
ALTER TABLE `scouting_batches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sertifikasis`
--
ALTER TABLE `sertifikasis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `talent_scoutings`
--
ALTER TABLE `talent_scoutings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `anggota_risets`
--
ALTER TABLE `anggota_risets`
  ADD CONSTRAINT `anggota_risets_id_risets_foreign` FOREIGN KEY (`id_risets`) REFERENCES `risets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `anggota_risets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `beasiswas`
--
ALTER TABLE `beasiswas`
  ADD CONSTRAINT `beasiswas_batch_id_foreign` FOREIGN KEY (`batch_id`) REFERENCES `beasiswa_batches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `beasiswas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `beasiswa_batches`
--
ALTER TABLE `beasiswa_batches`
  ADD CONSTRAINT `beasiswa_batches_perusahaan_id_foreign` FOREIGN KEY (`perusahaan_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `certification_exams`
--
ALTER TABLE `certification_exams`
  ADD CONSTRAINT `certification_exams_pembuat_user_id_foreign` FOREIGN KEY (`pembuat_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `guru_tamus`
--
ALTER TABLE `guru_tamus`
  ADD CONSTRAINT `guru_tamus_submitted_by_foreign` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `kurikulums`
--
ALTER TABLE `kurikulums`
  ADD CONSTRAINT `kurikulums_pengirim_id_foreign` FOREIGN KEY (`pengirim_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kurikulums_perusahaan_id_foreign` FOREIGN KEY (`perusahaan_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `logbooks`
--
ALTER TABLE `logbooks`
  ADD CONSTRAINT `logbooks_pkl_id_foreign` FOREIGN KEY (`pkl_id`) REFERENCES `pkls` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `logbooks_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `logbook_content`
--
ALTER TABLE `logbook_content`
  ADD CONSTRAINT `logbook_content_logbook_id_foreign` FOREIGN KEY (`logbook_id`) REFERENCES `logbooks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `moocs`
--
ALTER TABLE `moocs`
  ADD CONSTRAINT `moocs_perusahaan_id_foreign` FOREIGN KEY (`perusahaan_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mooc_evals`
--
ALTER TABLE `mooc_evals`
  ADD CONSTRAINT `mooc_evals_mooc_id_foreign` FOREIGN KEY (`mooc_id`) REFERENCES `moocs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mooc_modules`
--
ALTER TABLE `mooc_modules`
  ADD CONSTRAINT `mooc_modules_mooc_id_foreign` FOREIGN KEY (`mooc_id`) REFERENCES `moocs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mooc_reflection`
--
ALTER TABLE `mooc_reflection`
  ADD CONSTRAINT `mooc_reflection_mooc_id_foreign` FOREIGN KEY (`mooc_id`) REFERENCES `moocs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mooc_reflection_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mooc_scores`
--
ALTER TABLE `mooc_scores`
  ADD CONSTRAINT `mooc_scores_mooc_id_foreign` FOREIGN KEY (`mooc_id`) REFERENCES `moocs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mooc_scores_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pkls`
--
ALTER TABLE `pkls`
  ADD CONSTRAINT `pkls_pembimbing_id_foreign` FOREIGN KEY (`pembimbing_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pkls_perusahaan_id_foreign` FOREIGN KEY (`perusahaan_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_perusahaan_id_foreign` FOREIGN KEY (`perusahaan_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `scouting_batches`
--
ALTER TABLE `scouting_batches`
  ADD CONSTRAINT `scouting_batches_perusahaan_id_foreign` FOREIGN KEY (`perusahaan_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sertifikasis`
--
ALTER TABLE `sertifikasis`
  ADD CONSTRAINT `sertifikasis_certification_exam_id_foreign` FOREIGN KEY (`certification_exam_id`) REFERENCES `certification_exams` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sertifikasis_lsp_user_id_foreign` FOREIGN KEY (`lsp_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sertifikasis_perusahaan_user_id_foreign` FOREIGN KEY (`perusahaan_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sertifikasis_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `talent_scoutings`
--
ALTER TABLE `talent_scoutings`
  ADD CONSTRAINT `talent_scoutings_batch_id_foreign` FOREIGN KEY (`batch_id`) REFERENCES `scouting_batches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `talent_scoutings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_pkl_id_foreign` FOREIGN KEY (`pkl_id`) REFERENCES `pkls` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
