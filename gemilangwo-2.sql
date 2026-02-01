-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 01 Feb 2026 pada 05.44
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gemilangwo`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `availabilities`
--

CREATE TABLE `availabilities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `owner_id` bigint(20) UNSIGNED NOT NULL,
  `available_from` date NOT NULL,
  `available_to` date NOT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `banks`
--

CREATE TABLE `banks` (
  `id` bigint(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(80) NOT NULL,
  `account_number` varchar(255) NOT NULL,
  `account_holder` varchar(255) NOT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `instruction` text DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `banks`
--

INSERT INTO `banks` (`id`, `name`, `code`, `account_number`, `account_holder`, `logo_path`, `instruction`, `active`, `created_at`, `updated_at`) VALUES
(1, 'BCA', 'bca', '1234567890', 'PT Gemilang WO', NULL, 'Transfer via BCA ATM, Mobile Banking, atau Counter', 1, '2026-01-27 06:54:52', '2026-01-27 06:54:52'),
(2, 'BNI', 'bni', '0987654321', 'PT Gemilang WO', NULL, 'Transfer via BNI ATM, Mobile Banking, atau Counter', 1, '2026-01-27 06:54:52', '2026-01-27 06:54:52'),
(3, 'Mandiri', 'mandiri', '1122334455', 'PT Gemilang WO', NULL, 'Transfer via ATM, e-Banking, atau Counter', 1, '2026-01-27 06:54:52', '2026-01-27 06:54:52'),
(4, 'Permata', 'permata', '5544332211', 'PT Gemilang WO', NULL, 'Transfer via ATM, Mobile Banking, atau Counter', 1, '2026-01-27 06:54:52', '2026-01-27 06:54:52');

-- --------------------------------------------------------

--
-- Struktur dari tabel `blocked_dates`
--

CREATE TABLE `blocked_dates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_id` bigint(20) UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `block_type` enum('unavailable','maintenance','reserved','personal') NOT NULL DEFAULT 'unavailable',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(80) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(80) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `calendar_events`
--

CREATE TABLE `calendar_events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `package_id` bigint(20) UNSIGNED NOT NULL,
  `event_date` date NOT NULL,
  `event_start` date DEFAULT NULL,
  `event_end` date DEFAULT NULL,
  `status` enum('pending','confirmed','in_progress','completed') NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `is_confirmed` tinyint(1) NOT NULL DEFAULT 0,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `calendar_events`
--

INSERT INTO `calendar_events` (`id`, `order_id`, `package_id`, `event_date`, `event_start`, `event_end`, `status`, `notes`, `is_confirmed`, `confirmed_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 2, '2026-02-06', '2026-02-06', '2026-02-06', 'pending', NULL, 1, '2026-01-16 20:10:13', '2026-01-16 18:49:20', '2026-01-16 20:10:13', NULL),
(2, 12, 6, '2026-02-28', '2026-02-28', '2026-02-28', 'pending', NULL, 1, '2026-01-27 19:10:46', '2026-01-27 19:10:30', '2026-01-27 19:10:46', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `support_ticket_id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `sender_type` enum('customer','admin') NOT NULL DEFAULT 'customer',
  `attachments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachments`)),
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `support_ticket_id`, `sender_id`, `message`, `sender_type`, `attachments`, `is_read`, `read_at`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 'permasalahan pembayaran wedding', 'customer', NULL, 0, NULL, '2026-01-28 04:33:34', '2026-01-28 04:33:34');

-- --------------------------------------------------------

--
-- Struktur dari tabel `discounts`
--

CREATE TABLE `discounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('percentage','fixed') NOT NULL DEFAULT 'percentage',
  `value` decimal(10,2) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `usage_limit` int(11) DEFAULT NULL,
  `usage_count` int(11) NOT NULL DEFAULT 0,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `discounts`
--

INSERT INTO `discounts` (`id`, `name`, `description`, `type`, `value`, `start_date`, `end_date`, `is_active`, `usage_limit`, `usage_count`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Year End Sale 2025', 'Celebration ending - Get 30% discount on all wedding packages!', 'percentage', 30.00, '2026-01-07 03:25:23', '2026-02-07 03:25:23', 1, NULL, 0, 1, '2026-01-06 20:25:23', '2026-01-06 20:25:23'),
(2, 'Valentine Special', 'Limited time offer for Valentine\'s Day bookings', 'fixed', 1000000.00, '2026-02-07 03:25:23', '2026-03-07 03:25:23', 1, NULL, 0, 1, '2026-01-06 20:25:23', '2026-01-06 20:25:23'),
(3, 'Early Bird Special', 'Book 3+ months in advance and save 20%', 'percentage', 20.00, '2026-01-07 03:25:23', NULL, 1, NULL, 0, 1, '2026-01-06 20:25:23', '2026-01-06 20:25:23'),
(4, 'Flash Sale - Limited Time!', 'Flash sale with limited slots - only 50 bookings!', 'percentage', 15.00, '2026-01-07 03:25:23', '2026-01-14 03:25:23', 1, 50, 0, 1, '2026-01-06 20:25:23', '2026-01-06 20:25:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `discount_package`
--

CREATE TABLE `discount_package` (
  `discount_id` bigint(20) UNSIGNED NOT NULL,
  `package_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `discount_package`
--

INSERT INTO `discount_package` (`discount_id`, `package_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(2, 1),
(2, 2),
(3, 1),
(4, 1),
(4, 2),
(4, 3),
(4, 4),
(4, 5),
(4, 6);

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(11) UNSIGNED NOT NULL,
  `uuid` varchar(80) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `gallery_images`
--

CREATE TABLE `gallery_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_id` bigint(20) UNSIGNED NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(1, 'default', '{\"uuid\":\"1423a76c-a082-4537-b111-2b14206f230d\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:8;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:19:\\\"alc@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1769516011,\"delay\":null}', 0, NULL, 1769516011, 1769516011),
(2, 'default', '{\"uuid\":\"e175657c-2cf1-4a4a-951c-863e037b5667\",\"displayName\":\"App\\\\Mail\\\\AdminNotificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AdminNotificationMail\\\":4:{s:4:\\\"type\\\";s:9:\\\"new_order\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:8;s:13:\\\"customer_name\\\";s:3:\\\"Alc\\\";s:14:\\\"customer_email\\\";s:19:\\\"alc@gemilangwo.test\\\";s:14:\\\"customer_phone\\\";N;s:12:\\\"package_name\\\";s:6:\\\"Silver\\\";s:11:\\\"total_price\\\";s:11:\\\"57000000.00\\\";s:10:\\\"event_date\\\";s:11:\\\"20 May 2026\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1769516011,\"delay\":null}', 0, NULL, 1769516011, 1769516011),
(3, 'default', '{\"uuid\":\"509af1f8-9b6c-46e0-8804-34074de1c2a3\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:9;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:19:\\\"alc@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1769516183,\"delay\":null}', 0, NULL, 1769516183, 1769516183),
(4, 'default', '{\"uuid\":\"381e564d-f487-4113-a4d7-9340b244e16a\",\"displayName\":\"App\\\\Mail\\\\AdminNotificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AdminNotificationMail\\\":4:{s:4:\\\"type\\\";s:9:\\\"new_order\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:9;s:13:\\\"customer_name\\\";s:3:\\\"Alc\\\";s:14:\\\"customer_email\\\";s:19:\\\"alc@gemilangwo.test\\\";s:14:\\\"customer_phone\\\";N;s:12:\\\"package_name\\\";s:6:\\\"Silver\\\";s:11:\\\"total_price\\\";s:11:\\\"57000000.00\\\";s:10:\\\"event_date\\\";s:11:\\\"31 May 2026\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1769516183,\"delay\":null}', 0, NULL, 1769516183, 1769516183),
(5, 'default', '{\"uuid\":\"0b8f59d6-5baa-4062-81a1-7847f624957a\",\"displayName\":\"App\\\\Mail\\\\OrderStatusMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:24:\\\"App\\\\Mail\\\\OrderStatusMail\\\":4:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:9;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"previousStatus\\\";s:7:\\\"pending\\\";s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:19:\\\"alc@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1769516282,\"delay\":null}', 0, NULL, 1769516282, 1769516282),
(6, 'default', '{\"uuid\":\"de3a9f61-c1c7-4095-a8b2-5d5e9952bd98\",\"displayName\":\"App\\\\Mail\\\\OrderStatusMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:24:\\\"App\\\\Mail\\\\OrderStatusMail\\\":4:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:8;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"previousStatus\\\";s:7:\\\"pending\\\";s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:19:\\\"alc@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1769516287,\"delay\":null}', 0, NULL, 1769516287, 1769516287),
(7, 'default', '{\"uuid\":\"8d4cfdc9-4271-4fe0-93fc-bf49a95ece94\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:10;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:19:\\\"alc@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1769516838,\"delay\":null}', 0, NULL, 1769516838, 1769516838),
(8, 'default', '{\"uuid\":\"30ea176e-3370-4585-b6b9-71780709ba60\",\"displayName\":\"App\\\\Mail\\\\AdminNotificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AdminNotificationMail\\\":4:{s:4:\\\"type\\\";s:9:\\\"new_order\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:10;s:13:\\\"customer_name\\\";s:3:\\\"Alc\\\";s:14:\\\"customer_email\\\";s:19:\\\"alc@gemilangwo.test\\\";s:14:\\\"customer_phone\\\";N;s:12:\\\"package_name\\\";s:6:\\\"Silver\\\";s:11:\\\"total_price\\\";s:11:\\\"57000000.00\\\";s:10:\\\"event_date\\\";s:11:\\\"20 May 2026\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1769516838,\"delay\":null}', 0, NULL, 1769516838, 1769516838),
(9, 'default', '{\"uuid\":\"6592b0c1-0a2d-4362-b84b-16f2b64df830\",\"displayName\":\"App\\\\Mail\\\\OrderStatusMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:24:\\\"App\\\\Mail\\\\OrderStatusMail\\\":4:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:10;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"previousStatus\\\";s:7:\\\"pending\\\";s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:19:\\\"alc@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1769517484,\"delay\":null}', 0, NULL, 1769517484, 1769517484),
(10, 'default', '{\"uuid\":\"46991da4-ee4a-49c6-8fb9-19735e2e7c8b\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:11;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:19:\\\"alc@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1769517505,\"delay\":null}', 0, NULL, 1769517505, 1769517505),
(11, 'default', '{\"uuid\":\"67090e17-edc2-4d63-a39d-3e2bc9cb9d3c\",\"displayName\":\"App\\\\Mail\\\\AdminNotificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AdminNotificationMail\\\":4:{s:4:\\\"type\\\";s:9:\\\"new_order\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:11;s:13:\\\"customer_name\\\";s:3:\\\"Alc\\\";s:14:\\\"customer_email\\\";s:19:\\\"alc@gemilangwo.test\\\";s:14:\\\"customer_phone\\\";N;s:12:\\\"package_name\\\";s:6:\\\"Silver\\\";s:11:\\\"total_price\\\";s:11:\\\"57000000.00\\\";s:10:\\\"event_date\\\";s:11:\\\"20 May 2026\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1769517505,\"delay\":null}', 0, NULL, 1769517505, 1769517505),
(12, 'default', '{\"uuid\":\"17db913a-aa12-4ac9-9132-5d53d16deaeb\",\"displayName\":\"App\\\\Mail\\\\OrderStatusMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:24:\\\"App\\\\Mail\\\\OrderStatusMail\\\":4:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:11;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"previousStatus\\\";s:7:\\\"pending\\\";s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:19:\\\"alc@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1769517680,\"delay\":null}', 0, NULL, 1769517680, 1769517680),
(13, 'default', '{\"uuid\":\"66bb3e15-aee6-4fec-bbb4-eb81a9fb8315\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:12;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:19:\\\"alc@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1769521440,\"delay\":null}', 0, NULL, 1769521440, 1769521440),
(14, 'default', '{\"uuid\":\"f020e31d-12b9-41eb-a9b0-a4f3136f53dd\",\"displayName\":\"App\\\\Mail\\\\AdminNotificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AdminNotificationMail\\\":4:{s:4:\\\"type\\\";s:9:\\\"new_order\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:12;s:13:\\\"customer_name\\\";s:3:\\\"Alc\\\";s:14:\\\"customer_email\\\";s:19:\\\"alc@gemilangwo.test\\\";s:14:\\\"customer_phone\\\";N;s:12:\\\"package_name\\\";s:6:\\\"Silver\\\";s:11:\\\"total_price\\\";s:11:\\\"57000000.00\\\";s:10:\\\"event_date\\\";s:16:\\\"28 February 2026\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1769521440,\"delay\":null}', 0, NULL, 1769521440, 1769521440),
(15, 'default', '{\"uuid\":\"192f568e-7bcb-44d1-aca3-8238a4e936ec\",\"displayName\":\"App\\\\Mail\\\\PaymentInstructionMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:31:\\\"App\\\\Mail\\\\PaymentInstructionMail\\\":5:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:12;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:7:\\\"payment\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Payment\\\";s:2:\\\"id\\\";i:7;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:4:\\\"bank\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\Bank\\\";s:2:\\\"id\\\";i:2;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:19:\\\"alc@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1769522583,\"delay\":null}', 0, NULL, 1769522583, 1769522583),
(16, 'default', '{\"uuid\":\"2da249a7-ae9c-4f94-8df3-c9e6cdffb283\",\"displayName\":\"App\\\\Mail\\\\PaymentInstructionMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:31:\\\"App\\\\Mail\\\\PaymentInstructionMail\\\":5:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:12;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:7:\\\"payment\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Payment\\\";s:2:\\\"id\\\";i:8;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:4:\\\"bank\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\Bank\\\";s:2:\\\"id\\\";i:2;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:19:\\\"alc@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1769560256,\"delay\":null}', 0, NULL, 1769560256, 1769560256),
(17, 'default', '{\"uuid\":\"cd8d2574-49c0-43df-9331-dc221e87f4ad\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:13;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1769561740,\"delay\":null}', 0, NULL, 1769561740, 1769561740),
(18, 'default', '{\"uuid\":\"7ec24aed-6cb5-4ef3-b86b-2bced94c03cf\",\"displayName\":\"App\\\\Mail\\\\AdminNotificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AdminNotificationMail\\\":4:{s:4:\\\"type\\\";s:9:\\\"new_order\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:13;s:13:\\\"customer_name\\\";s:10:\\\"Admin User\\\";s:14:\\\"customer_email\\\";s:21:\\\"admin@gemilangwo.test\\\";s:14:\\\"customer_phone\\\";s:11:\\\"08123456789\\\";s:12:\\\"package_name\\\";s:5:\\\"Semar\\\";s:11:\\\"total_price\\\";s:10:\\\"4500000.00\\\";s:10:\\\"event_date\\\";s:13:\\\"28 April 2026\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1769561740,\"delay\":null}', 0, NULL, 1769561740, 1769561740),
(19, 'default', '{\"uuid\":\"0a318634-d095-4b90-b4a2-8c5ea8be572d\",\"displayName\":\"App\\\\Mail\\\\PaymentInstructionMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:31:\\\"App\\\\Mail\\\\PaymentInstructionMail\\\":5:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:13;s:9:\\\"relations\\\";a:2:{i:0;s:4:\\\"user\\\";i:1;s:7:\\\"package\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:7:\\\"payment\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Payment\\\";s:2:\\\"id\\\";i:9;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:4:\\\"bank\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\Bank\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1769561740,\"delay\":null}', 0, NULL, 1769561740, 1769561740),
(20, 'default', '{\"uuid\":\"9a0623ce-858c-49ec-a3fd-44a319c0f156\",\"displayName\":\"App\\\\Mail\\\\PaymentReceivedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:28:\\\"App\\\\Mail\\\\PaymentReceivedMail\\\":3:{s:7:\\\"payment\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Payment\\\";s:2:\\\"id\\\";i:9;s:9:\\\"relations\\\";a:2:{i:0;s:5:\\\"order\\\";i:1;s:10:\\\"order.user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1769561740,\"delay\":null}', 0, NULL, 1769561740, 1769561740),
(21, 'default', '{\"uuid\":\"34b9b275-d993-4053-a51a-1ef428310019\",\"displayName\":\"App\\\\Mail\\\\AdminNotificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AdminNotificationMail\\\":4:{s:4:\\\"type\\\";s:16:\\\"payment_received\\\";s:4:\\\"data\\\";a:5:{s:10:\\\"payment_id\\\";i:9;s:8:\\\"order_id\\\";i:13;s:6:\\\"amount\\\";s:10:\\\"4500000.00\\\";s:14:\\\"payment_method\\\";s:13:\\\"bank_transfer\\\";s:13:\\\"customer_name\\\";s:10:\\\"Admin User\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1769561740,\"delay\":null}', 0, NULL, 1769561740, 1769561740),
(22, 'default', '{\"uuid\":\"c2fcaf4e-57db-4598-88ff-e31a97c79252\",\"displayName\":\"App\\\\Mail\\\\OrderStatusMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:24:\\\"App\\\\Mail\\\\OrderStatusMail\\\":4:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:13;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"previousStatus\\\";s:7:\\\"pending\\\";s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1769561740,\"delay\":null}', 0, NULL, 1769561740, 1769561740),
(23, 'default', '{\"uuid\":\"c583cba9-a599-45fe-9512-ad0312314e9e\",\"displayName\":\"App\\\\Mail\\\\PaymentVerifiedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:28:\\\"App\\\\Mail\\\\PaymentVerifiedMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:13;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1769561740,\"delay\":null}', 0, NULL, 1769561740, 1769561740),
(24, 'default', '{\"uuid\":\"2c391b8f-c1be-4f2b-95c6-71498379d847\",\"displayName\":\"App\\\\Mail\\\\PaymentInstructionMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:31:\\\"App\\\\Mail\\\\PaymentInstructionMail\\\":5:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:12;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:7:\\\"payment\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Payment\\\";s:2:\\\"id\\\";i:10;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:4:\\\"bank\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\Bank\\\";s:2:\\\"id\\\";i:2;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:19:\\\"alc@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1769565793,\"delay\":null}', 0, NULL, 1769565793, 1769565793),
(25, 'default', '{\"uuid\":\"eb0cf305-94b9-4873-8908-f748e1ad864f\",\"displayName\":\"App\\\\Mail\\\\PaymentInstructionMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:31:\\\"App\\\\Mail\\\\PaymentInstructionMail\\\":5:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:12;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:7:\\\"payment\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Payment\\\";s:2:\\\"id\\\";i:11;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:4:\\\"bank\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\Bank\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:19:\\\"alc@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1769565987,\"delay\":null}', 0, NULL, 1769565987, 1769565987),
(26, 'default', '{\"uuid\":\"a0806550-ae08-4584-9800-351908a641ba\",\"displayName\":\"App\\\\Mail\\\\PaymentReceivedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:28:\\\"App\\\\Mail\\\\PaymentReceivedMail\\\":3:{s:7:\\\"payment\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Payment\\\";s:2:\\\"id\\\";i:7;s:9:\\\"relations\\\";a:2:{i:0;s:5:\\\"order\\\";i:1;s:10:\\\"order.user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:19:\\\"alc@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1769566047,\"delay\":null}', 0, NULL, 1769566047, 1769566047),
(27, 'default', '{\"uuid\":\"66a52326-b1a2-4ecf-990d-9837769371f9\",\"displayName\":\"App\\\\Mail\\\\AdminNotificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AdminNotificationMail\\\":4:{s:4:\\\"type\\\";s:16:\\\"payment_received\\\";s:4:\\\"data\\\";a:5:{s:10:\\\"payment_id\\\";i:7;s:8:\\\"order_id\\\";i:12;s:6:\\\"amount\\\";s:11:\\\"57000000.00\\\";s:14:\\\"payment_method\\\";s:13:\\\"bank_transfer\\\";s:13:\\\"customer_name\\\";s:3:\\\"Alc\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1769566047,\"delay\":null}', 0, NULL, 1769566047, 1769566047),
(28, 'default', '{\"uuid\":\"79e00cb8-7863-49f4-a0ff-2ea99013a150\",\"displayName\":\"App\\\\Mail\\\\OrderStatusMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:24:\\\"App\\\\Mail\\\\OrderStatusMail\\\":4:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:12;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"previousStatus\\\";s:7:\\\"pending\\\";s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:19:\\\"alc@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1769566047,\"delay\":null}', 0, NULL, 1769566047, 1769566047),
(29, 'default', '{\"uuid\":\"f46f3070-bcd8-421d-ac37-c97fc3390a0a\",\"displayName\":\"App\\\\Mail\\\\PaymentVerifiedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:28:\\\"App\\\\Mail\\\\PaymentVerifiedMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:12;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:19:\\\"alc@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1769566047,\"delay\":null}', 0, NULL, 1769566047, 1769566047);

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(80) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(80) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_01_04_000001_add_role_to_users_table', 1),
(5, '2024_01_04_000002_create_packages_table', 1),
(6, '2024_01_04_000003_create_orders_table', 1),
(7, '2024_01_04_000004_create_payments_table', 1),
(8, '2024_01_04_000005_create_reviews_table', 1),
(9, '2026_01_04_082100_create_discounts_table', 1),
(10, '2026_01_04_082128_create_discount_package_table', 1),
(11, '2026_01_04_091903_update_reviews_table_add_new_fields', 1),
(12, '2026_01_04_093302_add_fields_to_users_table', 1),
(13, '2026_01_04_093302_create_wishlists_table', 1),
(14, '2026_01_04_093440_create_availability_table', 1),
(15, '2026_01_04_093440_create_gallery_images_table', 1),
(16, '2026_01_04_100000_create_notifications_table', 1),
(17, '2026_01_04_110000_add_sms_preferences_to_users_table', 1),
(18, '2026_01_04_110001_create_sms_logs_table', 1),
(19, '2026_01_04_120000_create_support_tickets_table', 1),
(20, '2026_01_04_120001_create_chat_messages_table', 1),
(21, '2026_01_05_012609_add_owner_id_to_packages_table', 1),
(22, '2026_01_05_014710_create_videos_table', 1),
(23, '2026_01_05_014711_create_video_testimonials_table', 1),
(24, '2026_01_05_120000_create_blocked_dates_table', 1),
(25, '2026_01_05_120001_create_calendar_events_table', 1),
(26, '2026_01_05_120002_add_columns_to_orders_table', 1),
(27, '2026_01_27_add_va_fields_to_payments_table', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `action_url` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id` bigint(11) UNSIGNED NOT NULL,
  `user_id` bigint(11) UNSIGNED NOT NULL,
  `package_id` bigint(11) UNSIGNED NOT NULL,
  `order_number` varchar(80) NOT NULL,
  `event_date` date NOT NULL,
  `pre_event_days` int(11) NOT NULL DEFAULT 0,
  `post_event_days` int(11) NOT NULL DEFAULT 0,
  `calendar_confirmed` tinyint(1) NOT NULL DEFAULT 0,
  `event_location` varchar(255) NOT NULL,
  `guest_count` int(11) NOT NULL,
  `special_request` text DEFAULT NULL,
  `total_price` decimal(12,2) NOT NULL,
  `status` enum('pending','confirmed','in_progress','completed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `package_id`, `order_number`, `event_date`, `pre_event_days`, `post_event_days`, `calendar_confirmed`, `event_location`, `guest_count`, `special_request`, `total_price`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 3, 2, 'WO-1767756323001', '2026-02-06', 0, 0, 0, 'Bandung Convention Center', 250, 'Ingin tema warna emas dan putih, dengan dekorasi bunga mawar merah', 100000000.00, 'confirmed', '2026-01-06 20:25:23', '2026-01-06 20:25:23', NULL),
(2, 4, 1, 'WO-1767756323002', '2026-02-21', 0, 0, 0, 'Yogyakarta Palace Hall', 350, 'Menggabungkan tradisi Jawa dengan modern. Perlu live gamelan', 150000000.00, 'pending', '2026-01-06 20:25:23', '2026-01-06 20:25:23', NULL),
(3, 5, 3, 'WO-1767756323003', '2026-03-08', 0, 0, 0, 'Medan Grand Hotel', 200, 'Tema modern minimalis. Dokumentasi lengkap dimulai dari pengiring pengantin', 60000000.00, 'confirmed', '2026-01-06 20:25:23', '2026-01-06 20:25:23', NULL),
(4, 6, 4, 'WO-1767756323004', '2026-01-27', 0, 0, 0, 'Makassar Seaside Resort', 500, 'Tema tepi pantai dengan sunset ceremony. Perlu coordinator yang responsif', 250000000.00, 'in_progress', '2026-01-06 20:25:23', '2026-01-06 20:25:23', NULL),
(5, 7, 1, 'WO-1767756323005', '2026-01-22', 0, 0, 0, 'Malang City Hotel', 100, 'Acara sederhana dan hangat dengan keluarga besar', 35000000.00, 'completed', '2026-01-06 20:25:23', '2026-01-06 20:25:23', NULL),
(6, 8, 6, 'WO-1767756323006', '2026-02-26', 0, 0, 0, 'Semarang International Convention Center', 400, 'Menginginkan dokumentasi cinematic berkualitas tinggi dan koordinator profesional', 180000000.00, 'confirmed', '2026-01-06 20:25:23', '2026-01-06 20:25:23', NULL),
(7, 3, 3, 'WO-1767756323007', '2026-04-07', 0, 0, 0, 'Bandung City Resort', 150, 'Tema outdoor garden party', 60000000.00, 'cancelled', '2026-01-06 20:25:23', '2026-01-06 20:25:23', NULL),
(8, 9, 6, 'WO-1769516011-9058', '2026-05-20', 0, 0, 0, 'Hotel Griptha', 950, 'aa', 57000000.00, 'cancelled', '2026-01-27 05:13:31', '2026-01-27 05:18:07', NULL),
(9, 9, 6, 'WO-1769516183-5763', '2026-05-31', 0, 0, 0, 'Hotel Griptha', 950, 'aa', 57000000.00, 'cancelled', '2026-01-27 05:16:23', '2026-01-27 05:18:02', NULL),
(10, 9, 6, 'WO-6951683861', '2026-05-20', 0, 0, 0, 'Griptha', 950, 'aa', 57000000.00, 'cancelled', '2026-01-27 05:27:18', '2026-01-27 05:38:04', NULL),
(11, 9, 6, 'WO-6951750441', '2026-05-20', 0, 0, 0, 'Griptha', 950, '111', 57000000.00, 'cancelled', '2026-01-27 05:38:24', '2026-01-27 05:41:20', NULL),
(12, 9, 6, 'WO-6952144064', '2026-02-28', 0, 0, 0, 'Griptha', 960, 'aa', 57000000.00, 'confirmed', '2026-01-27 06:44:00', '2026-01-27 19:07:27', NULL),
(13, 1, 1, 'TEST-1769561740', '2026-04-28', 0, 0, 0, 'Test Location', 100, NULL, 4500000.00, 'confirmed', '2026-01-27 17:55:40', '2026-01-27 17:55:40', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `packages`
--

CREATE TABLE `packages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `max_guests` int(11) DEFAULT NULL,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features`)),
  `image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `owner_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `packages`
--

INSERT INTO `packages` (`id`, `name`, `description`, `price`, `max_guests`, `features`, `image`, `status`, `created_at`, `updated_at`, `deleted_at`, `owner_id`) VALUES
(1, 'Semar', 'Akad Nikah | Pemberkatan | Lamaran | Siraman', 4500000.00, 150, '\"[\\\"7 Professional Crew\\\",\\\"Jasa Wedding Organizer\\\",\\\"Konsultasi Persiapan Acara\\\",\\\"20 Buku Panduan Acara\\\",\\\"Koordinasi dengan All Vendor\\\",\\\"1x Meeting All Vendor & Keluarga\\\",\\\"Pendampingan & Kontrol Loading Pra Acara\\\"]\"', NULL, 'active', '2026-01-06 20:25:23', '2026-01-22 18:33:16', NULL, 2),
(2, 'Gareng', 'Resepsi | Unduh Mantu', 5500000.00, 500, '\"[\\\"8 Professional Crew\\\",\\\"Jasa Wedding Organizer\\\",\\\"Konsultasi Persiapan Acara\\\",\\\"25 Buku Panduan Acara\\\",\\\"Koordinasi dengan All Vendor\\\",\\\"1x Meeting All Vendor & Keluarga\\\",\\\"Pendampingan & Kontrol Loading Pra Acara\\\"]\"', NULL, 'active', '2026-01-06 20:25:23', '2026-01-22 18:35:29', NULL, 2),
(3, 'Petruk', 'Akad Nikah / Pemberkatan & Resepsi', 7500000.00, 750, '\"[\\\"10 Professional Crew\\\",\\\"Jasa Wedding Organizer\\\",\\\"Konsultasi Persiapan Acara\\\",\\\"Kinerja 1 Hari di 2 Lokasi Maksimal\\\",\\\"1x Visit Lokasi Pra Acara\\\",\\\"1x Meeting Keluarga & Vendor\\\",\\\"Kontrol Loading Pra Acara\\\"]\"', NULL, 'active', '2026-01-06 20:25:23', '2026-01-22 18:40:31', NULL, 2),
(4, 'Bagong', 'Akad Nikah / Pemberkatan & Resepsi', 10000000.00, 1000, '\"[\\\"1 Master of Ceremony\\\",\\\"1 Pranatacara Akad Nikah\\\",\\\"12 Professional Crew\\\",\\\"Outfit WO by Request\\\",\\\"Jasa WO Akad Nikah \\\\\\/ Pemberkatan & Resepsi\\\",\\\"30 Buku Panduan Acara\\\",\\\"Kinerja 1 Hari di 2 Lokasi Maksimal\\\",\\\"1x Visit Lokasi Pra Acara\\\",\\\"1x Meeting Keluarga & Vendor\\\",\\\"Kontrol Loading Pra Acara\\\"]\"', NULL, 'active', '2026-01-06 20:25:23', '2026-01-22 18:43:26', NULL, 2),
(5, 'Deluxe', 'Jasa Wedding Organizer & Entertainment', 17000000.00, 1000, '\"[\\\"12 Professional Crew\\\",\\\"Outfit WO by Request\\\",\\\"Jasa WO Akad Nikah \\\\\\/ Pemberkatan & Resepsi\\\",\\\"30 Buku Panduan Acara\\\",\\\"Kinerja 1 Hari di 2 Lokasi Maksimal\\\",\\\"1x Visit Lokasi Pra Acara\\\",\\\"1x Meeting Keluarga & Vendor\\\",\\\"Kontrol Loading Pra Acara\\\",\\\"Konsep Full Band\\\",\\\"Sound System\\\"]\"', NULL, 'active', '2026-01-06 20:25:23', '2026-01-22 18:47:03', NULL, 2),
(6, 'Silver', 'Akad Nikah / Pemberkatan & Resepsi', 57000000.00, 1000, '\"[\\\"Wedding Organizer\\\",\\\"Dekorasi Full Package\\\",\\\"1 Pranata Acara\\\",\\\"1 Master of Ceremony\\\",\\\"Music Entertainment\\\",\\\"Sound System\\\",\\\"6 pcs Confetti\\\",\\\"Photography & Videography\\\",\\\"Prewedding Photo\\\",\\\"4 Kipas Blower\\\",\\\"14 Spot Dry Ice\\\",\\\"4 Spot Pyro Effect\\\",\\\"30 Pcs Balon Udara\\\",\\\"2 Pcs Guest Book\\\"]\"', NULL, 'active', '2026-01-06 20:25:23', '2026-01-22 18:49:43', NULL, 2),
(7, 'Premium', 'Akad Nikah / Pemberkatan & Resepsi', 77000000.00, 1000, '\"[\\\"Wedding Organizer\\\",\\\"Makeup & Gown\\\",\\\"Dekorasi Full Package\\\",\\\"1 Pranata Acara\\\",\\\"1 Master of Ceremony\\\",\\\"Music Entertainment\\\",\\\"Sound System\\\",\\\"6 Pcs Confetti & 2 Merpati\\\",\\\"Photography & Videography\\\",\\\"Prewedding Photo\\\",\\\"4 Kipas Blower\\\",\\\"2 Ac Pelaminan\\\",\\\"14 Spot Dry Ice\\\",\\\"4 Spot Pyro Effect\\\",\\\"30 Pcs Balon Udara\\\",\\\"2 Pcs Guest Boook\\\"]\"', NULL, 'active', '2026-01-22 18:52:38', '2026-01-22 18:52:38', NULL, NULL),
(8, 'All in 1', 'Akad Nikah / Pemberkatan & Resepsi', 125000000.00, 1000, '\"[\\\"Wedding Organizer\\\",\\\"Makeup & Gown\\\",\\\"Catering 500 Pax\\\",\\\"50 Pax Nasi Box\\\",\\\"Dekorasi Full Package\\\",\\\"1 Pranata Acara\\\",\\\"1 Master of Ceremony\\\",\\\"Music Entertainment\\\",\\\"Sound System\\\",\\\"Photography & Videography\\\",\\\"Prewedding Photo\\\",\\\"4 Kipas Blower\\\",\\\"2 Ac Pelaminan\\\",\\\"Lighting Effect\\\",\\\"14 Spot Dry Ice\\\",\\\"4 Spot Pyro Effect\\\",\\\"30 Pcs Balon Udara\\\",\\\"2 Pcs Guest Boook\\\",\\\"1 Guest House or Family\\\"]\"', NULL, 'active', '2026-01-22 18:55:48', '2026-01-22 18:55:48', NULL, NULL),
(9, 'All in 2', 'Akad Nikah / Pemberkatan & Resepsi', 170000000.00, 1000, '\"[\\\"Wedding Organizer\\\",\\\"Makeup & Gown\\\",\\\"Catering 1000 Pax\\\",\\\"50 Pax Nasi Box\\\",\\\"Dekorasi Full Package\\\",\\\"1 Pranatacara\\\",\\\"1 Master of Ceremony\\\",\\\"Music Entertainment\\\",\\\"Sound System\\\",\\\"Photobooth Cetak\\\",\\\"Photography & Videography\\\",\\\"Prewedding Photo\\\",\\\"4 Kipas Blower\\\",\\\"2 Ac Pelaminan\\\",\\\"Lighting Effect\\\",\\\"14 Spot Dry Ice\\\",\\\"4 Spot Pyro Effect\\\",\\\"Wedding Coffee Corner\\\",\\\"30 Pcs Balon Udara\\\",\\\"2 Pcs Guest Book\\\",\\\"1 Guest House for Family\\\"]\"', NULL, 'active', '2026-01-22 18:59:10', '2026-01-22 18:59:10', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(80) NOT NULL,
  `token` varchar(80) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `payment_id` varchar(80) NOT NULL,
  `bank_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payment_method` enum('credit_card','bank_transfer','e_wallet','cash') DEFAULT NULL,
  `payment_proof_path` varchar(255) DEFAULT NULL,
  `va_number` varchar(255) DEFAULT NULL,
  `bank` varchar(255) DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `status` enum('pending','processing','success','failed','cancelled') NOT NULL DEFAULT 'pending',
  `verification_status` enum('pending','verified','rejected') NOT NULL DEFAULT 'pending',
  `verified_by` bigint(20) UNSIGNED DEFAULT NULL,
  `verification_notes` text DEFAULT NULL,
  `midtrans_response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`midtrans_response`)),
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `payment_id`, `bank_id`, `payment_method`, `payment_proof_path`, `va_number`, `bank`, `amount`, `status`, `verification_status`, `verified_by`, `verification_notes`, `midtrans_response`, `paid_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'MIDTRANS-1767756323001', NULL, 'credit_card', NULL, NULL, NULL, 100000000.00, 'success', 'pending', NULL, NULL, NULL, '2026-01-01 20:25:23', '2026-01-06 20:25:23', '2026-01-06 20:25:23'),
(2, 2, 'MIDTRANS-1767756323002', NULL, NULL, NULL, NULL, NULL, 150000000.00, 'pending', 'pending', NULL, NULL, NULL, NULL, '2026-01-06 20:25:23', '2026-01-06 20:25:23'),
(3, 3, 'MIDTRANS-1767756323003', NULL, 'bank_transfer', NULL, NULL, NULL, 60000000.00, 'success', 'pending', NULL, NULL, NULL, '2025-12-27 20:25:23', '2026-01-06 20:25:23', '2026-01-06 20:25:23'),
(4, 4, 'MIDTRANS-1767756323004', NULL, 'e_wallet', NULL, NULL, NULL, 250000000.00, 'success', 'pending', NULL, NULL, NULL, '2025-12-22 20:25:23', '2026-01-06 20:25:23', '2026-01-06 20:25:23'),
(5, 5, 'MIDTRANS-1767756323005', NULL, 'bank_transfer', NULL, NULL, NULL, 35000000.00, 'success', 'pending', NULL, NULL, NULL, '2025-12-17 20:25:23', '2026-01-06 20:25:23', '2026-01-06 20:25:23'),
(6, 6, 'MIDTRANS-1767756323006', NULL, 'credit_card', NULL, NULL, NULL, 180000000.00, 'success', 'pending', NULL, NULL, NULL, '2026-01-03 20:25:23', '2026-01-06 20:25:23', '2026-01-06 20:25:23'),
(7, 12, 'MANUAL-WO-6952144064-Jxgogdk7', 2, 'bank_transfer', NULL, NULL, NULL, 57000000.00, 'success', 'verified', 1, NULL, NULL, '2026-01-27 19:07:26', '2026-01-27 07:03:03', '2026-01-27 19:07:27'),
(8, 12, 'MANUAL-WO-6952144064-rSBK1Um8', 2, 'bank_transfer', NULL, NULL, NULL, 57000000.00, 'pending', 'pending', NULL, NULL, NULL, NULL, '2026-01-27 17:30:56', '2026-01-27 17:30:56'),
(9, 13, 'MANUAL-TEST-1769561740-xqFjKjip', 1, 'bank_transfer', NULL, NULL, NULL, 4500000.00, 'success', 'verified', 1, 'Test approval', NULL, '2026-01-27 17:55:40', '2026-01-27 17:55:40', '2026-01-27 17:55:40'),
(10, 12, 'MANUAL-WO-6952144064-BgiEX9lZ', 2, 'bank_transfer', NULL, NULL, NULL, 57000000.00, 'pending', 'pending', NULL, NULL, NULL, NULL, '2026-01-27 19:03:13', '2026-01-27 19:03:13'),
(11, 12, 'MANUAL-WO-6952144064-ioRMf7Pe', 1, 'bank_transfer', NULL, NULL, NULL, 57000000.00, 'pending', 'pending', NULL, NULL, NULL, NULL, '2026-01-27 19:06:26', '2026-01-27 19:06:26');

-- --------------------------------------------------------

--
-- Struktur dari tabel `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `rating` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `content` text DEFAULT NULL,
  `helpful_count` int(11) NOT NULL DEFAULT 0,
  `unhelpful_count` int(11) NOT NULL DEFAULT 0,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `package_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `reviews`
--

INSERT INTO `reviews` (`id`, `order_id`, `user_id`, `rating`, `title`, `comment`, `content`, `helpful_count`, `unhelpful_count`, `is_verified`, `is_approved`, `is_featured`, `created_at`, `updated_at`, `deleted_at`, `package_id`) VALUES
(1, 5, 7, 3, 'Paket yang Cukup', NULL, 'Paketnya menawarkan nilai uang yang cukup baik. Timnya profesional, meskipun tidak luar biasa luar biasa. Ada beberapa masalah kecil yang tidak ditangani dengan cepat. Untuk harganya, itu dapat diterima tetapi tidak istimewa.', 5, 3, 1, 1, 0, '2026-01-06 20:25:23', '2026-01-06 20:25:23', NULL, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(80) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('EGLHouBRtVWPmVjUsxlC7EwOjLxM4tcYsIQsDD9E', 2, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUzlDalRCZFFRb0ZZWlNvS2pZZWw1TVAzbE5FeGF1b0lCcjIxRVRtMCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9', 1769600158);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sms_logs`
--

CREATE TABLE `sms_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `phone_number` varchar(80) NOT NULL,
  `message` text NOT NULL,
  `type` enum('sms','whatsapp') NOT NULL DEFAULT 'sms',
  `status` enum('pending','sent','failed') NOT NULL DEFAULT 'pending',
  `template_key` varchar(255) DEFAULT NULL,
  `template_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`template_data`)),
  `twilio_sid` varchar(255) DEFAULT NULL,
  `error_message` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category` enum('general','order','payment','complaint','suggestion','other') NOT NULL DEFAULT 'general',
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `status` enum('open','in_progress','waiting_customer','resolved','closed') NOT NULL DEFAULT 'open',
  `internal_notes` text DEFAULT NULL,
  `response_count` int(11) NOT NULL DEFAULT 0,
  `first_response_at` timestamp NULL DEFAULT NULL,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `support_tickets`
--

INSERT INTO `support_tickets` (`id`, `user_id`, `assigned_to`, `order_id`, `subject`, `description`, `category`, `priority`, `status`, `internal_notes`, `response_count`, `first_response_at`, `resolved_at`, `closed_at`, `created_at`, `updated_at`) VALUES
(1, 3, NULL, 7, 'permasalahan pembayaran', 'permasalahan pembayaran wedding', 'payment', 'medium', 'open', NULL, 0, NULL, NULL, NULL, '2026-01-28 04:33:34', '2026-01-28 04:33:34');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(80) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','customer','owner') NOT NULL DEFAULT 'customer',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `wedding_date` date DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `prefer_whatsapp` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Prefer WhatsApp notifications',
  `prefer_sms` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Prefer SMS notifications',
  `prefer_email` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Prefer Email notifications'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`, `address`, `city`, `bio`, `profile_image`, `wedding_date`, `deleted_at`, `prefer_whatsapp`, `prefer_sms`, `prefer_email`) VALUES
(1, 'Admin User', 'admin@gemilangwo.test', '08123456789', '2026-01-06 20:25:21', '$2y$12$OYrF5QtQgRgFPvFlsTc5OurPUqMQE1Y5z37sGmSLt09JKHGtU6hbm', 'admin', NULL, '2026-01-06 20:25:22', '2026-01-06 20:25:22', 'Jakarta, Indonesia', NULL, NULL, NULL, NULL, NULL, 1, 1, 1),
(2, 'Owner Business', 'owner@gemilangwo.test', '08234567890', '2026-01-06 20:25:22', '$2y$12$OYrF5QtQgRgFPvFlsTc5OurPUqMQE1Y5z37sGmSLt09JKHGtU6hbm', 'owner', NULL, '2026-01-06 20:25:22', '2026-01-06 20:25:22', 'Surabaya, Indonesia', NULL, NULL, NULL, NULL, NULL, 1, 1, 1),
(3, 'Budi Santoso', 'budi@gemilangwo.test', '08111111111', '2026-01-06 20:25:22', '$2y$12$OYrF5QtQgRgFPvFlsTc5OurPUqMQE1Y5z37sGmSLt09JKHGtU6hbm', 'customer', NULL, '2026-01-06 20:25:22', '2026-01-06 20:25:22', 'Bandung, Jawa Barat', NULL, NULL, NULL, NULL, NULL, 1, 1, 1),
(4, 'Siti Rahayu', 'siti@gemilangwo.test', '08222222222', '2026-01-06 20:25:22', '$2y$12$tCEGBVuUq0UzgEhx2UYo5.xfScs63zSyXjZD5op/sPv3k4E5FW8x.', 'customer', NULL, '2026-01-06 20:25:22', '2026-01-06 20:25:22', 'Yogyakarta, DI Yogyakarta', NULL, NULL, NULL, NULL, NULL, 1, 1, 1),
(5, 'Ahmad Wijaya', 'ahmad@gemilangwo.test', '08333333333', '2026-01-06 20:25:22', '$2y$12$DWitm1Cw4KPva7g3EwED5.uDRjgLV5Z5rgLDLv8ZiwGa0P4P9qvnq', 'customer', NULL, '2026-01-06 20:25:23', '2026-01-06 20:25:23', 'Medan, Sumatera Utara', NULL, NULL, NULL, NULL, NULL, 1, 1, 1),
(6, 'Dewi Lestari', 'dewi@gemilangwo.test', '08444444444', '2026-01-06 20:25:23', '$2y$12$3v3cfQHLAAuo8zlGWFIz/OLRhZvAu.lY8aJzZPbBFYLYdjRwj.CiO', 'customer', NULL, '2026-01-06 20:25:23', '2026-01-06 20:25:23', 'Makassar, Sulawesi Selatan', NULL, NULL, NULL, NULL, NULL, 1, 1, 1),
(7, 'Rinto Harahap', 'rinto@gemilangwo.test', '08555555555', '2026-01-06 20:25:23', '$2y$12$HqOQY4GHNOMyfVknhRVd9.dWENw.HiNBrZCeRSP4HJyx211hPRopK', 'customer', NULL, '2026-01-06 20:25:23', '2026-01-06 20:25:23', 'Malang, Jawa Timur', NULL, NULL, NULL, NULL, NULL, 1, 1, 1),
(8, 'Nina Kusuma', 'nina@gemilangwo.test', '08666666666', '2026-01-06 20:25:23', '$2y$12$K63yWEvjrLwzIv8RR4xun.zDL8..AfJ9J00DLbRcs6YnpDunRjCU6', 'customer', NULL, '2026-01-06 20:25:23', '2026-01-06 20:25:23', 'Semarang, Jawa Tengah', NULL, NULL, NULL, NULL, NULL, 1, 1, 1),
(9, 'Alc', 'alc@gemilangwo.test', NULL, NULL, '$2y$12$/hSnchAyh0KPdTo4XiuVNO3yyhiopWzUTSvQjo9T38RAlp3NcvXH6', 'customer', NULL, '2026-01-27 05:12:27', '2026-01-27 05:12:27', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `videos`
--

CREATE TABLE `videos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('upload','youtube') NOT NULL DEFAULT 'upload',
  `video_path` varchar(255) DEFAULT NULL,
  `youtube_url` varchar(255) DEFAULT NULL,
  `thumbnail_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `video_testimonials`
--

CREATE TABLE `video_testimonials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('upload','youtube') NOT NULL DEFAULT 'upload',
  `video_path` varchar(255) DEFAULT NULL,
  `youtube_url` varchar(255) DEFAULT NULL,
  `thumbnail_path` varchar(255) DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `views` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `wishlists`
--

CREATE TABLE `wishlists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `package_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `availabilities`
--
ALTER TABLE `availabilities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `availabilities_owner_id_available_from_available_to_index` (`owner_id`,`available_from`,`available_to`);

--
-- Indeks untuk tabel `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `banks_code_unique` (`code`);

--
-- Indeks untuk tabel `blocked_dates`
--
ALTER TABLE `blocked_dates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blocked_dates_package_id_start_date_end_date_index` (`package_id`,`start_date`,`end_date`),
  ADD KEY `blocked_dates_package_id_is_active_index` (`package_id`,`is_active`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `calendar_events`
--
ALTER TABLE `calendar_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `calendar_events_package_id_event_date_index` (`package_id`,`event_date`),
  ADD KEY `calendar_events_order_id_index` (`order_id`),
  ADD KEY `calendar_events_status_index` (`status`);

--
-- Indeks untuk tabel `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_messages_support_ticket_id_index` (`support_ticket_id`),
  ADD KEY `chat_messages_sender_id_index` (`sender_id`),
  ADD KEY `chat_messages_is_read_index` (`is_read`),
  ADD KEY `chat_messages_created_at_index` (`created_at`);

--
-- Indeks untuk tabel `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `discounts_created_by_foreign` (`created_by`);

--
-- Indeks untuk tabel `discount_package`
--
ALTER TABLE `discount_package`
  ADD PRIMARY KEY (`discount_id`,`package_id`),
  ADD KEY `discount_package_package_id_foreign` (`package_id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `gallery_images`
--
ALTER TABLE `gallery_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gallery_images_package_id_foreign` (`package_id`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_index` (`user_id`),
  ADD KEY `notifications_is_read_index` (`is_read`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_number_unique` (`order_number`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_package_id_foreign` (`package_id`);

--
-- Indeks untuk tabel `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `packages_owner_id_foreign` (`owner_id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payments_payment_id_unique` (`payment_id`),
  ADD KEY `payments_order_id_foreign` (`order_id`);

--
-- Indeks untuk tabel `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_order_id_foreign` (`order_id`),
  ADD KEY `reviews_user_id_foreign` (`user_id`),
  ADD KEY `reviews_package_id_foreign` (`package_id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `sms_logs`
--
ALTER TABLE `sms_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sms_logs_user_id_index` (`user_id`),
  ADD KEY `sms_logs_status_index` (`status`),
  ADD KEY `sms_logs_type_index` (`type`),
  ADD KEY `sms_logs_phone_number_index` (`phone_number`);

--
-- Indeks untuk tabel `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `support_tickets_order_id_foreign` (`order_id`),
  ADD KEY `support_tickets_user_id_index` (`user_id`),
  ADD KEY `support_tickets_assigned_to_index` (`assigned_to`),
  ADD KEY `support_tickets_status_index` (`status`),
  ADD KEY `support_tickets_priority_index` (`priority`),
  ADD KEY `support_tickets_category_index` (`category`),
  ADD KEY `support_tickets_created_at_index` (`created_at`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indeks untuk tabel `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `videos_package_id_foreign` (`package_id`);

--
-- Indeks untuk tabel `video_testimonials`
--
ALTER TABLE `video_testimonials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `video_testimonials_user_id_foreign` (`user_id`),
  ADD KEY `video_testimonials_order_id_foreign` (`order_id`);

--
-- Indeks untuk tabel `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `wishlists_user_id_package_id_unique` (`user_id`,`package_id`),
  ADD KEY `wishlists_package_id_foreign` (`package_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `availabilities`
--
ALTER TABLE `availabilities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `banks`
--
ALTER TABLE `banks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `blocked_dates`
--
ALTER TABLE `blocked_dates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `calendar_events`
--
ALTER TABLE `calendar_events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `gallery_images`
--
ALTER TABLE `gallery_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT untuk tabel `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `packages`
--
ALTER TABLE `packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `sms_logs`
--
ALTER TABLE `sms_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `videos`
--
ALTER TABLE `videos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `video_testimonials`
--
ALTER TABLE `video_testimonials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `availabilities`
--
ALTER TABLE `availabilities`
  ADD CONSTRAINT `availabilities_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `blocked_dates`
--
ALTER TABLE `blocked_dates`
  ADD CONSTRAINT `blocked_dates_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `calendar_events`
--
ALTER TABLE `calendar_events`
  ADD CONSTRAINT `calendar_events_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `calendar_events_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `chat_messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_messages_support_ticket_id_foreign` FOREIGN KEY (`support_ticket_id`) REFERENCES `support_tickets` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `discounts`
--
ALTER TABLE `discounts`
  ADD CONSTRAINT `discounts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `discount_package`
--
ALTER TABLE `discount_package`
  ADD CONSTRAINT `discount_package_discount_id_foreign` FOREIGN KEY (`discount_id`) REFERENCES `discounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `discount_package_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `gallery_images`
--
ALTER TABLE `gallery_images`
  ADD CONSTRAINT `gallery_images_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `packages`
--
ALTER TABLE `packages`
  ADD CONSTRAINT `packages_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `sms_logs`
--
ALTER TABLE `sms_logs`
  ADD CONSTRAINT `sms_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD CONSTRAINT `support_tickets_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `support_tickets_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `support_tickets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `videos`
--
ALTER TABLE `videos`
  ADD CONSTRAINT `videos_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `video_testimonials`
--
ALTER TABLE `video_testimonials`
  ADD CONSTRAINT `video_testimonials_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `video_testimonials_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
