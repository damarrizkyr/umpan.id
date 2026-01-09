-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 09 Jan 2026 pada 08.04
-- Versi server: 8.4.3
-- Versi PHP: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `umpan.id`
--

--
-- Dumping data untuk tabel `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `field_id`, `schedule_id`, `customer_name`, `customer_phone`, `booking_date`, `total_price`, `booking_code`, `status`, `payment_status`, `expired_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(14, 4, 7, 37, 'Kyra', '088334267534', '2026-01-01', 200000, 'BKG20260101BAD1C7', 'confirmed', 'paid', '2026-01-01 17:00:00', '2026-01-01 09:39:07', '2026-01-01 09:40:04', NULL),
(15, 4, 6, 22, 'Kyra', '088334267534', '2026-01-02', 180000, 'BKG20260102D1019A', 'confirmed', 'paid', '2026-01-02 17:00:00', '2026-01-01 23:37:17', '2026-01-08 16:38:37', NULL),
(16, NULL, 7, 35, 'user2', '085876588756', '2026-01-08', 150000, 'BKG20260107542001', 'confirmed', 'paid', '2026-01-08 17:00:00', '2026-01-07 06:41:25', '2026-01-07 06:41:52', NULL),
(25, 10, 7, 39, 'userr', '084578437587', '2026-01-10', 300000, 'BKG20260109CEE970', 'pending', 'paid', '2026-01-10 17:00:00', '2026-01-08 17:03:24', '2026-01-08 17:03:24', NULL),
(27, 10, 8, 53, 'user5', '084578437587', '2026-01-10', 300000, 'BKG202601090147D7', 'confirmed', 'paid', '2026-01-10 17:00:00', '2026-01-08 17:14:56', '2026-01-08 17:29:20', NULL),
(28, 10, 7, 40, 'user5', '084578437587', '2026-01-10', 300000, 'BKG20260109BD07DA', 'pending', 'paid', '2026-01-10 17:00:00', '2026-01-08 17:53:47', '2026-01-08 17:53:47', NULL),
(31, 10, 7, 43, 'user5', '084578437587', '2026-01-11', 300000, 'BKG20260109621F5E', 'pending', 'paid', '2026-01-11 17:00:00', '2026-01-08 17:57:10', '2026-01-08 17:57:10', NULL),
(32, 10, 6, 22, 'user5', '084578437587', '2026-01-09', 180000, 'BKG202601091A1EAD', 'confirmed', 'paid', '2026-01-09 17:00:00', '2026-01-08 17:57:53', '2026-01-08 18:14:06', NULL);

--
-- Dumping data untuk tabel `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('umpan-cache-userr@gmail.com|127.0.0.1', 'i:1;', 1767894683),
('umpan-cache-userr@gmail.com|127.0.0.1:timer', 'i:1767894683;', 1767894683);

--
-- Dumping data untuk tabel `fields`
--

INSERT INTO `fields` (`id`, `venue_id`, `name`, `image`, `status`, `created_at`, `updated_at`) VALUES
(6, 9, 'Lapangan 1', NULL, 'active', '2026-01-01 08:49:21', '2026-01-01 08:49:21'),
(7, 10, 'Lapangan 1', NULL, 'active', '2026-01-01 09:13:42', '2026-01-01 09:13:42'),
(8, 10, 'Lapangan 2', NULL, 'active', '2026-01-01 09:13:42', '2026-01-01 09:13:42'),
(9, 11, 'Lapangan 1', NULL, 'active', '2026-01-08 12:50:28', '2026-01-08 12:50:28'),
(10, 12, 'Lapangan 1', NULL, 'active', '2026-01-08 12:54:48', '2026-01-08 12:54:48');

--
-- Dumping data untuk tabel `field_schedules`
--

INSERT INTO `field_schedules` (`id`, `field_id`, `day`, `time_slot`, `price`, `created_at`, `updated_at`) VALUES
(10, 6, 'senin', '12:00-13:00', 150000, '2026-01-01 08:49:21', '2026-01-02 00:08:05'),
(11, 6, 'senin', '15:00-17:00', 150000, '2026-01-01 08:49:21', '2026-01-02 00:08:05'),
(12, 6, 'senin', '18:00-19:00', 180000, '2026-01-01 08:49:21', '2026-01-02 00:08:05'),
(13, 6, 'senin', '21:00-22:00', 200000, '2026-01-01 08:49:21', '2026-01-02 00:08:05'),
(14, 6, 'selasa', '12:00-13:00', 150000, '2026-01-01 08:49:21', '2026-01-02 00:08:05'),
(15, 6, 'selasa', '15:00-17:00', 150000, '2026-01-01 08:49:21', '2026-01-02 00:08:05'),
(16, 6, 'selasa', '18:00-19:00', 180000, '2026-01-01 08:49:21', '2026-01-02 00:08:05'),
(17, 6, 'selasa', '21:00-22:00', 200000, '2026-01-01 08:49:21', '2026-01-02 00:08:05'),
(18, 6, 'rabu', '12:00-13:00', 150000, '2026-01-01 08:49:21', '2026-01-02 00:08:05'),
(19, 6, 'rabu', '15:00-17:00', 150000, '2026-01-01 08:49:21', '2026-01-02 00:08:05'),
(20, 6, 'rabu', '18:00-19:00', 180000, '2026-01-01 08:49:21', '2026-01-02 00:08:05'),
(21, 6, 'rabu', '21:00-22:00', 180000, '2026-01-01 08:49:21', '2026-01-02 00:08:05'),
(22, 6, 'jumat', '15:00-17:00', 180000, '2026-01-01 08:49:21', '2026-01-02 00:08:06'),
(23, 6, 'jumat', '18:00-19:00', 20000, '2026-01-01 08:49:21', '2026-01-02 00:08:06'),
(24, 6, 'jumat', '21:00-22:00', 200000, '2026-01-01 08:49:21', '2026-01-02 00:08:06'),
(25, 6, 'sabtu', '12:00-13:00', 200000, '2026-01-01 08:49:21', '2026-01-02 00:08:06'),
(26, 6, 'sabtu', '15:00-17:00', 200000, '2026-01-01 08:49:21', '2026-01-02 00:08:06'),
(27, 6, 'sabtu', '18:00-19:00', 220000, '2026-01-01 08:49:21', '2026-01-02 00:08:06'),
(28, 6, 'sabtu', '21:00-22:00', 250000, '2026-01-01 08:49:21', '2026-01-02 00:08:06'),
(29, 6, 'minggu', '12:00-13:00', 200000, '2026-01-01 08:49:21', '2026-01-02 00:08:06'),
(30, 6, 'minggu', '15:00-17:00', 250000, '2026-01-01 08:49:21', '2026-01-02 00:08:06'),
(31, 6, 'minggu', '18:00-19:00', 280000, '2026-01-01 08:49:21', '2026-01-02 00:08:06'),
(32, 6, 'minggu', '21:00-22:00', 300000, '2026-01-01 08:49:21', '2026-01-02 00:08:06'),
(33, 7, 'senin', '12:00-13:00', 150000, '2026-01-01 09:13:42', '2026-01-08 18:11:12'),
(34, 7, 'senin', '15:00-17:00', 150000, '2026-01-01 09:13:42', '2026-01-08 18:11:12'),
(35, 7, 'kamis', '12:00-13:00', 150000, '2026-01-01 09:13:42', '2026-01-08 18:11:12'),
(36, 7, 'kamis', '15:00-17:00', 200000, '2026-01-01 09:13:42', '2026-01-08 18:11:12'),
(37, 7, 'kamis', '18:00-19:00', 200000, '2026-01-01 09:13:42', '2026-01-08 18:11:12'),
(38, 7, 'kamis', '21:00-22:00', 250000, '2026-01-01 09:13:42', '2026-01-08 18:11:12'),
(39, 7, 'sabtu', '12:00-13:00', 300000, '2026-01-01 09:13:42', '2026-01-08 18:11:12'),
(40, 7, 'sabtu', '15:00-17:00', 300000, '2026-01-01 09:13:42', '2026-01-08 18:11:12'),
(41, 7, 'sabtu', '18:00-19:00', 350000, '2026-01-01 09:13:42', '2026-01-08 18:11:12'),
(42, 7, 'sabtu', '21:00-22:00', 350000, '2026-01-01 09:13:42', '2026-01-08 18:11:12'),
(43, 7, 'minggu', '12:00-13:00', 300000, '2026-01-01 09:13:42', '2026-01-08 18:11:12'),
(44, 7, 'minggu', '15:00-17:00', 300000, '2026-01-01 09:13:42', '2026-01-08 18:11:12'),
(45, 7, 'minggu', '18:00-19:00', 350000, '2026-01-01 09:13:42', '2026-01-08 18:11:12'),
(46, 7, 'minggu', '21:00-22:00', 350000, '2026-01-01 09:13:42', '2026-01-08 18:11:12'),
(47, 8, 'senin', '12:00-13:00', 150000, '2026-01-01 09:13:42', '2026-01-08 18:11:12'),
(48, 8, 'senin', '15:00-17:00', 150000, '2026-01-01 09:13:42', '2026-01-08 18:11:12'),
(49, 8, 'kamis', '12:00-13:00', 180000, '2026-01-01 09:13:42', '2026-01-08 18:11:12'),
(50, 8, 'kamis', '15:00-17:00', 200000, '2026-01-01 09:13:42', '2026-01-08 18:11:12'),
(51, 8, 'kamis', '18:00-19:00', 200000, '2026-01-01 09:13:42', '2026-01-08 18:11:12'),
(52, 8, 'kamis', '21:00-22:00', 250000, '2026-01-01 09:13:42', '2026-01-08 18:11:12'),
(53, 8, 'sabtu', '12:00-13:00', 300000, '2026-01-01 09:13:42', '2026-01-08 18:11:12'),
(54, 8, 'sabtu', '15:00-17:00', 300000, '2026-01-01 09:13:42', '2026-01-08 18:11:12'),
(55, 8, 'sabtu', '18:00-19:00', 350000, '2026-01-01 09:13:42', '2026-01-08 18:11:13'),
(56, 8, 'sabtu', '21:00-22:00', 350000, '2026-01-01 09:13:42', '2026-01-08 18:11:13'),
(57, 8, 'minggu', '12:00-13:00', 300000, '2026-01-01 09:13:42', '2026-01-08 18:11:13'),
(58, 8, 'minggu', '15:00-17:00', 300000, '2026-01-01 09:13:42', '2026-01-08 18:11:13'),
(59, 8, 'minggu', '18:00-19:00', 350000, '2026-01-01 09:13:42', '2026-01-08 18:11:13'),
(60, 8, 'minggu', '21:00-22:00', 350000, '2026-01-01 09:13:42', '2026-01-08 18:11:13'),
(65, 9, 'senin', '12:00-13:00', 120000, '2026-01-08 12:50:28', '2026-01-08 12:50:28'),
(66, 9, 'senin', '15:00-17:00', 120000, '2026-01-08 12:50:28', '2026-01-08 12:50:28'),
(67, 9, 'senin', '18:00-19:00', 130000, '2026-01-08 12:50:28', '2026-01-08 12:50:28'),
(68, 9, 'senin', '21:00-22:00', 150000, '2026-01-08 12:50:28', '2026-01-08 12:50:28'),
(69, 9, 'selasa', '12:00-13:00', 120000, '2026-01-08 12:50:28', '2026-01-08 12:50:28'),
(70, 9, 'selasa', '15:00-17:00', 120000, '2026-01-08 12:50:28', '2026-01-08 12:50:28'),
(71, 9, 'selasa', '18:00-19:00', 130000, '2026-01-08 12:50:28', '2026-01-08 12:50:28'),
(72, 9, 'selasa', '21:00-22:00', 150000, '2026-01-08 12:50:28', '2026-01-08 12:50:28'),
(73, 9, 'kamis', '12:00-13:00', 130000, '2026-01-08 12:50:28', '2026-01-08 12:50:28'),
(74, 9, 'kamis', '15:00-17:00', 130000, '2026-01-08 12:50:28', '2026-01-08 12:50:28'),
(75, 9, 'kamis', '18:00-19:00', 150000, '2026-01-08 12:50:28', '2026-01-08 12:50:28'),
(76, 9, 'kamis', '21:00-22:00', 150000, '2026-01-08 12:50:28', '2026-01-08 12:50:28'),
(77, 9, 'sabtu', '12:00-13:00', 150000, '2026-01-08 12:50:28', '2026-01-08 12:50:28'),
(78, 9, 'sabtu', '15:00-17:00', 150000, '2026-01-08 12:50:28', '2026-01-08 12:50:28'),
(79, 9, 'sabtu', '18:00-19:00', 180000, '2026-01-08 12:50:28', '2026-01-08 12:50:28'),
(80, 9, 'sabtu', '21:00-22:00', 180000, '2026-01-08 12:50:28', '2026-01-08 12:50:28'),
(81, 9, 'minggu', '12:00-13:00', 150000, '2026-01-08 12:50:28', '2026-01-08 12:50:28'),
(82, 9, 'minggu', '15:00-17:00', 180000, '2026-01-08 12:50:28', '2026-01-08 12:50:28'),
(83, 9, 'minggu', '18:00-19:00', 180000, '2026-01-08 12:50:28', '2026-01-08 12:50:28'),
(84, 9, 'minggu', '21:00-22:00', 200000, '2026-01-08 12:50:28', '2026-01-08 12:50:28'),
(85, 10, 'sabtu', '12:00-13:00', 250000, '2026-01-08 12:54:48', '2026-01-08 12:54:48'),
(86, 10, 'sabtu', '15:00-17:00', 250000, '2026-01-08 12:54:48', '2026-01-08 12:54:48'),
(87, 10, 'sabtu', '18:00-19:00', 300000, '2026-01-08 12:54:48', '2026-01-08 12:54:48'),
(88, 10, 'sabtu', '21:00-22:00', 300000, '2026-01-08 12:54:48', '2026-01-08 12:54:48'),
(89, 10, 'minggu', '12:00-13:00', 280000, '2026-01-08 12:54:48', '2026-01-08 12:54:48'),
(90, 10, 'minggu', '15:00-17:00', 280000, '2026-01-08 12:54:48', '2026-01-08 12:54:48'),
(91, 10, 'minggu', '18:00-19:00', 350000, '2026-01-08 12:54:48', '2026-01-08 12:54:48'),
(92, 10, 'minggu', '21:00-22:00', 350000, '2026-01-08 12:54:48', '2026-01-08 12:54:48');

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(6, '2025_12_20_225200_create_venues_table', 2),
(7, '2025_12_20_225427_create_venue_images_table', 3),
(10, '2025_12_21_022905_create_fields_table', 4),
(11, '2025_12_21_040407_create_field_schedules_table', 4),
(12, '2025_12_21_053202_create_bookings_table', 5),
(13, '2025_12_25_110435_add_soft_deletes_to_bookings_table', 6),
(14, '2026_01_01_145312_add_role_to_users_table', 7),
(15, '2026_01_01_151015_change_role_column_type_in_users_table', 8),
(16, '2026_01_06_191524_create_reviews_table', 9);

--
-- Dumping data untuk tabel `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `venue_id`, `rating`, `comment`, `created_at`, `updated_at`) VALUES
(1, 4, 10, 5, 'Lapangannya terbaik, Pelayanannya sangat bagus', '2026-01-06 12:34:16', '2026-01-06 12:34:16'),
(2, 10, 10, 5, 'rer', '2026-01-08 17:35:24', '2026-01-08 17:35:24');

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('3mebVTJ90T9M5kPuhtdixizRKhENFXhmPM2chJoq', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWjVBbXh1Q3pGdklWUVV2ejc5Y0FHNjJVeVlSR3ZYeVdnSWRHV0xRbCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC92ZW51ZXMvOS9ib29raW5ncyI7czo1OiJyb3V0ZSI7czoxNToidmVudWVzLmJvb2tpbmdzIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTt9', 1767896048),
('NKOZVmQiY0e13By0r9sEymghaW9elwDmeh6FiHCJ', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiamxHZkdRRmd5cnZGaFlLb2JhQUtwZFZDWkEzRldram83SVZsNlZkZSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo5OiJkYXNoYm9hcmQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo1O30=', 1767945402);

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `phone`, `remember_token`, `created_at`, `updated_at`) VALUES
(4, 'Kyra', 'gokil@gmail.com', NULL, '$2y$12$VjzKTuLKIbAc/WMntWZmkudn0iMpcj65JXCMx44Ri1yKR/.brCXKG', 'user', '088334267534', NULL, '2026-01-01 08:15:35', '2026-01-01 08:15:35'),
(5, 'Ucok', 'dong@gmail.com', NULL, '$2y$12$BkFO9j89MWFywGSc9A15uuPXyQG1lJWcooMXwO6MpzGeU605xCHkW', 'admin', '085373360647', NULL, '2026-01-01 08:28:52', '2026-01-01 08:28:52'),
(7, 'user2', 'user2@gmail.com', NULL, '$2y$12$JePElMAOls2cMhniPXG3NeiaIGy3jODmkdjrwtfhh4hYxZtklwgvW', 'user', '085643737334', NULL, '2026-01-07 07:05:26', '2026-01-07 07:05:26'),
(8, 'admin2', 'admin@gmail.com', NULL, '$2y$12$JjzRrdxeO4dRKTaDs9OAWee2CyO.dg6eibk/8J4ga.hlERjk5WyrW', 'admin', '0856436534634', NULL, '2026-01-08 12:43:14', '2026-01-08 12:43:14'),
(9, 'userrr', 'userrr@gmail.com', NULL, '$2y$12$j5OIWl3Eipe8P4LA588jnO/jgXkYT.c9uq1UaZacTiVhDHzAnLCaO', 'user', '0847583784545', NULL, '2026-01-08 16:47:51', '2026-01-08 16:47:51'),
(10, 'user5', 'userr2@gmail.com', NULL, '$2y$12$CKQVLvm9J3nEEh7PIlUNwO6hEjfeiLy2GoitEhFAZisdLWyVTyYPS', 'user', '084578437587', NULL, '2026-01-08 16:54:49', '2026-01-08 17:13:03');

--
-- Dumping data untuk tabel `venues`
--

INSERT INTO `venues` (`id`, `user_id`, `name`, `slug`, `description`, `facilities`, `address`, `city`, `open_time`, `close_time`, `rating`, `total_reviews`, `created_at`, `updated_at`) VALUES
(9, 5, 'Mini Soccer Waluyo', 'mini-soccer-waluyo-1767257361', '7vs7', 'Parkir Luas, Free Wifi', 'Jl. Apa ya', 'medan', '10:00:00', '23:00:00', 0.0, 0, '2026-01-01 08:49:21', '2026-01-02 00:08:05'),
(10, 5, 'Mini Soccer Sentosa', 'mini-soccer-sentosa-1767258822', '7vs7', 'Musolah, Parkir luas, Free Wifi', 'Jl. gak ya', 'medan', '10:00:00', '23:00:00', 0.0, 0, '2026-01-01 09:13:42', '2026-01-08 18:11:12'),
(11, 8, 'Mini Soccer FufuFafa', 'mini-soccer-fufufafa-1767876626', '7vs7', 'Mushola, Rumput sintetis, Parkiran luas', 'Jl. Kemana', 'medan', '10:00:00', '23:00:00', 0.0, 0, '2026-01-08 12:50:26', '2026-01-08 12:50:26'),
(12, 8, 'Mini Soccer Mulyo', 'mini-soccer-mulyo-1767876888', 'Gorong - gorong', 'Mushola', 'Jl. Mawar', 'medan', '10:00:00', '23:00:00', 0.0, 0, '2026-01-08 12:54:48', '2026-01-08 12:54:48');

--
-- Dumping data untuk tabel `venue_images`
--

INSERT INTO `venue_images` (`id`, `venue_id`, `image_path`, `is_primary`, `created_at`, `updated_at`) VALUES
(13, 9, 'venues/images/733ItlvS4B5ucsuxC2FKfUNc8G6aXqpDwhZiXQQ0.jpg', 1, '2026-01-01 08:49:21', '2026-01-01 08:49:21'),
(14, 10, 'venues/images/RX3cTdJB1OXtu06GglO4jwbYDq5AhirNfRFSZhBL.jpg', 1, '2026-01-01 09:13:42', '2026-01-01 09:13:42'),
(15, 10, 'venues/images/wHy8Zqm0Ae6Wwkl7X7UWshgUeWZxd4hQdsOWkRdu.jpg', 0, '2026-01-01 09:13:42', '2026-01-01 09:13:42'),
(17, 11, 'venues/images/NfvP044R3mOXCcsgdPuI2qz2ORGYKRGSJCzzcSYv.jpg', 1, '2026-01-08 12:50:28', '2026-01-08 12:50:28'),
(18, 11, 'venues/images/x77P4hJ7DrqdTNlBrM3rpOAoEVmEmtPex4I1X8fH.jpg', 0, '2026-01-08 12:50:28', '2026-01-08 12:50:28'),
(19, 11, 'venues/images/osFIDlry6IcNoXXNoXSH2H3Q5Hwbzf2R9Owum7qk.jpg', 0, '2026-01-08 12:50:28', '2026-01-08 12:50:28'),
(20, 12, 'venues/images/V6PuHEC986S43TmQeSGnaVsgO4Qcb1Yg6TSjoyOs.jpg', 1, '2026-01-08 12:54:48', '2026-01-08 12:54:48'),
(24, 10, 'venues/images/iq4aqZltcCkO47OjyfQF8YxkL91fleTfjjOP6Xf2.jpg', 0, '2026-01-08 18:05:55', '2026-01-08 18:05:55'),
(25, 10, 'venues/images/mJ29Jj0OQM2SuJSrLfh2VpDRm7ux85BN1OEfmCae.jpg', 0, '2026-01-08 18:11:12', '2026-01-08 18:11:12');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
