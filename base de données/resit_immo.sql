-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 06, 2026 at 11:24 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `resit_immo`
--

-- --------------------------------------------------------

--
-- Table structure for table `biens`
--

CREATE TABLE `biens` (
  `id` bigint UNSIGNED NOT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('maison','appartement') COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ville` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prix` decimal(10,2) NOT NULL,
  `proprietaire_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `biens`
--

INSERT INTO `biens` (`id`, `titre`, `type`, `adresse`, `ville`, `prix`, `proprietaire_id`, `created_at`, `updated_at`) VALUES
(1, 'Appartement T3 — Centre Paris', 'appartement', '15 Rue de Rivoli', 'Paris', 1200.00, 1, '2026-03-06 09:22:03', '2026-03-06 09:22:03'),
(2, 'Studio meublé — Montmartre', 'appartement', '7 Rue Lepic', 'Paris', 650.00, 1, '2026-03-06 09:22:03', '2026-03-06 09:22:03'),
(3, 'Maison 4 pièces avec jardin', 'maison', '22 Chemin des Roses', 'Lyon', 950.00, 2, '2026-03-06 09:22:03', '2026-03-06 09:22:03'),
(4, 'Appartement T2 — Part-Dieu', 'appartement', '3 Rue Garibaldi', 'Lyon', 720.00, 2, '2026-03-06 09:22:03', '2026-03-06 09:22:03'),
(5, 'Villa avec piscine — Vieux-Port', 'maison', '45 Boulevard du Bompard', 'Marseille', 1500.00, 3, '2026-03-06 09:22:03', '2026-03-06 09:22:03'),
(6, 'Appartement T4 — Haussmann', 'appartement', '10 Boulevard Haussmann', 'Paris', 2200.00, 4, '2026-03-06 09:22:03', '2026-03-06 09:22:03'),
(7, 'Maison de ville — Pigalle', 'maison', '2 Rue Fontaine', 'Paris', 1800.00, 4, '2026-03-06 09:22:03', '2026-03-06 09:22:03');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contrats`
--

CREATE TABLE `contrats` (
  `id` bigint UNSIGNED NOT NULL,
  `bien_id` bigint UNSIGNED NOT NULL,
  `locataire_id` bigint UNSIGNED NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date DEFAULT NULL,
  `montant_mensuel` decimal(10,2) NOT NULL,
  `statut` enum('actif','termine','resilie') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'actif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contrats`
--

INSERT INTO `contrats` (`id`, `bien_id`, `locataire_id`, `date_debut`, `date_fin`, `montant_mensuel`, `statut`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-01-01', '2025-12-31', 1200.00, 'actif', '2026-03-06 09:22:03', '2026-03-06 09:22:03'),
(2, 2, 2, '2025-03-01', NULL, 650.00, 'actif', '2026-03-06 09:22:03', '2026-03-06 09:22:03'),
(3, 3, 3, '2025-06-01', '2026-05-31', 950.00, 'actif', '2026-03-06 09:22:03', '2026-03-06 09:22:03'),
(4, 4, 4, '2023-09-01', '2024-08-31', 700.00, 'termine', '2026-03-06 09:22:03', '2026-03-06 09:22:03'),
(5, 4, 5, '2024-09-01', '2025-08-31', 720.00, 'actif', '2026-03-06 09:22:03', '2026-03-06 09:22:03'),
(6, 5, 6, '2024-01-01', '2024-06-30', 1500.00, 'resilie', '2026-03-06 09:22:03', '2026-03-06 09:22:03'),
(7, 6, 1, '2026-01-01', '2026-12-31', 2200.00, 'actif', '2026-03-06 09:22:03', '2026-03-06 09:22:03');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `locataires`
--

CREATE TABLE `locataires` (
  `id` bigint UNSIGNED NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `locataires`
--

INSERT INTO `locataires` (`id`, `nom`, `prenom`, `email`, `telephone`, `created_at`, `updated_at`) VALUES
(1, 'Durand', 'Alice', 'alice.durand@example.com', '07 12 34 56 78', '2026-03-06 09:22:03', '2026-03-06 09:22:03'),
(2, 'Moreau', 'Kevin', 'kevin.moreau@example.com', '06 23 45 67 89', '2026-03-06 09:22:03', '2026-03-06 09:22:03'),
(3, 'Petit', 'Camille', 'camille.petit@example.com', '07 34 56 78 90', '2026-03-06 09:22:03', '2026-03-06 09:22:03'),
(4, 'Simon', 'Hugo', 'hugo.simon@example.com', '06 45 67 89 01', '2026-03-06 09:22:03', '2026-03-06 09:22:03'),
(5, 'Laurent', 'Emma', 'emma.laurent@example.com', '07 56 78 90 12', '2026-03-06 09:22:03', '2026-03-06 09:22:03'),
(6, 'Rousseau', 'Maxime', 'maxime.rousseau@example.com', '06 67 89 01 23', '2026-03-06 09:22:03', '2026-03-06 09:22:03');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_03_06_095001_create_proprietaires_table', 1),
(5, '2026_03_06_095014_create_biens_table', 1),
(6, '2026_03_06_095032_create_locataires_table', 1),
(7, '2026_03_06_095040_create_contrats_table', 1),
(8, '2026_03_06_100059_create_personal_access_tokens_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'auth_token', '415b9496709b2a30918f3b8a10026b0c6b8e18ae6a30349d2564d1178f4aa534', '[\"*\"]', NULL, NULL, '2026-03-06 09:23:43', '2026-03-06 09:23:43'),
(2, 'App\\Models\\User', 3, 'auth_token', '1cf60fc6f1bca38d7c1fbffc77a1b4dfec3419c8fefe642f64476185229d8e3f', '[\"*\"]', NULL, NULL, '2026-03-06 09:24:13', '2026-03-06 09:24:13');

-- --------------------------------------------------------

--
-- Table structure for table `proprietaires`
--

CREATE TABLE `proprietaires` (
  `id` bigint UNSIGNED NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `proprietaires`
--

INSERT INTO `proprietaires` (`id`, `nom`, `prenom`, `email`, `telephone`, `adresse`, `created_at`, `updated_at`) VALUES
(1, 'Martin', 'Sophie', 'sophie.martin@example.com', '06 12 34 56 78', '12 Rue de la Paix, 75001 Paris', '2026-03-06 09:22:03', '2026-03-06 09:22:03'),
(2, 'Bernard', 'Luc', 'luc.bernard@example.com', '06 98 76 54 32', '5 Avenue des Fleurs, 69001 Lyon', '2026-03-06 09:22:03', '2026-03-06 09:22:03'),
(3, 'Dubois', 'Marie', 'marie.dubois@example.com', '07 11 22 33 44', '3 Impasse des Lilas, 13001 Marseille', '2026-03-06 09:22:03', '2026-03-06 09:22:03'),
(4, 'Lefevre', 'Thomas', 'thomas.lefevre@example.com', '06 55 66 77 88', '8 Boulevard Haussmann, 75009 Paris', '2026-03-06 09:22:03', '2026-03-06 09:22:03');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin RESIT', 'admin@resit-immo.fr', NULL, '$2y$12$PcqGYiP4FPrkYGMw9jnPIOXmfNy3m7f0XNJelX6nxi7PjrhrGoRvK', NULL, '2026-03-06 09:22:03', '2026-03-06 09:22:03'),
(2, 'Jean Dupont', 'jean@example.com', NULL, '$2y$12$nS81syDUSL6KvebTqIpVfur7sQpsYtfiDXBFcPXGuI3UgZ/dEGxQq', NULL, '2026-03-06 09:22:03', '2026-03-06 09:22:03'),
(3, 'julesDupont', 'jules@example.com', NULL, '$2y$12$373CerkG15e3eYS0MVRPV.iHWItoUl3CRFo1DfIS.h8AtEBPh.C8a', NULL, '2026-03-06 09:24:13', '2026-03-06 09:24:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `biens`
--
ALTER TABLE `biens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `biens_proprietaire_id_foreign` (`proprietaire_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `contrats`
--
ALTER TABLE `contrats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contrats_bien_id_foreign` (`bien_id`),
  ADD KEY `contrats_locataire_id_foreign` (`locataire_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `locataires`
--
ALTER TABLE `locataires`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `proprietaires`
--
ALTER TABLE `proprietaires`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `proprietaires_email_unique` (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `biens`
--
ALTER TABLE `biens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `contrats`
--
ALTER TABLE `contrats`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `locataires`
--
ALTER TABLE `locataires`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `proprietaires`
--
ALTER TABLE `proprietaires`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `biens`
--
ALTER TABLE `biens`
  ADD CONSTRAINT `biens_proprietaire_id_foreign` FOREIGN KEY (`proprietaire_id`) REFERENCES `proprietaires` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `contrats`
--
ALTER TABLE `contrats`
  ADD CONSTRAINT `contrats_bien_id_foreign` FOREIGN KEY (`bien_id`) REFERENCES `biens` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `contrats_locataire_id_foreign` FOREIGN KEY (`locataire_id`) REFERENCES `locataires` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
