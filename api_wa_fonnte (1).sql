-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 27, 2026 at 01:26 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `api_wa_fonnte`
--

-- --------------------------------------------------------

--
-- Table structure for table `log_whatsapp`
--

CREATE TABLE `log_whatsapp` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `no_tujuan` varchar(20) NOT NULL,
  `pesan` text NOT NULL,
  `status` enum('pending','success','failed') NOT NULL DEFAULT 'pending',
  `response` longtext DEFAULT NULL COMMENT 'Respons dari API Fonnte atau keterangan error',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `log_whatsapp`
--

INSERT INTO `log_whatsapp` (`id`, `user_id`, `no_tujuan`, `pesan`, `status`, `response`, `created_at`) VALUES
(1, 1, '6285792383520', '🎉 REGISTRASI BERHASIL\n\nHalo, airin ka!\nAkun Anda berhasil dibuat.\n\nNama: airin ka\nEmail: airinkachintyaka@gmail.com\nWhatsApp: 6285792383520\nAlamat: Ponorogo\n\nTerima kasih telah melakukan registrasi.', 'success', '{\"http_code\":200,\"body\":{\"detail\":\"success! message in queue\",\"id\":[195897163],\"process\":\"pending\",\"quota\":{\"085792383520\":{\"details\":\"deduced from total quota\",\"quota\":1000,\"remaining\":999,\"used\":1}},\"requestid\":1136116688,\"status\":true,\"target\":[\"6285792383520\"]}}', '2026-11-27 00:19:07'),
(2, 2, '6285737428117', '🎉 REGISTRASI BERHASIL\n\nHalo, Chintya!\nAkun Anda berhasil dibuat.\n\nNama: Chintya\nEmail: chintya@gmail.com\nWhatsApp: 6285737428117\nAlamat: Surabaya, Jawa Timur, Indonesia\n\nTerima kasih telah melakukan registrasi.', 'success', '{\"http_code\":200,\"body\":{\"detail\":\"success! message in queue\",\"id\":[195897791],\"process\":\"pending\",\"quota\":{\"085792383520\":{\"details\":\"deduced from total quota\",\"quota\":999,\"remaining\":998,\"used\":1}},\"requestid\":1136120269,\"status\":true,\"target\":[\"6285737428117\"]}}', '2026-11-27 00:22:20');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `alamat` text NOT NULL,
  `password` varchar(255) NOT NULL COMMENT 'Simpan hasil password_hash(), bukan password asli',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `no_hp`, `alamat`, `password`, `created_at`) VALUES
(1, 'airin ka', 'airinkachintyaka@gmail.com', '6285792383520', 'Ponorogo', '$2y$10$EbBfScexebsl4stauN1ND.nQpkRKKfymG943O2ItyyAonxRmoKSPq', '2026-11-27 00:19:06'),
(2, 'Chintya', 'chintya@gmail.com', '6285737428117', 'Surabaya, Jawa Timur, Indonesia', '$2y$10$E.VjLjiEI7jcawJbdUBeae3S9/8gZhwVThkdaA/1AokFh.M1uBo3S', '2026-11-27 00:22:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `log_whatsapp`
--
ALTER TABLE `log_whatsapp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_log_whatsapp_user_id` (`user_id`),
  ADD KEY `idx_log_whatsapp_status` (`status`),
  ADD KEY `idx_log_whatsapp_created_at` (`created_at`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_users_email` (`email`),
  ADD UNIQUE KEY `uq_users_no_hp` (`no_hp`),
  ADD KEY `idx_users_created_at` (`created_at`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `log_whatsapp`
--
ALTER TABLE `log_whatsapp`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `log_whatsapp`
--
ALTER TABLE `log_whatsapp`
  ADD CONSTRAINT `fk_log_whatsapp_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
