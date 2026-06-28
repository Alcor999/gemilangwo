-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 28 Jun 2026 pada 07.52
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
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
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
(1, 'BCA', 'bca', '1234567890', 'PT Gemilang WO', NULL, 'Transfer via BCA ATM, Mobile Banking, atau Counter', 1, '2026-02-08 14:27:21', '2026-02-08 14:27:21'),
(2, 'BNI', 'bni', '0987654321', 'PT Gemilang WO', NULL, 'Transfer via BNI ATM, Mobile Banking, atau Counter', 1, '2026-02-08 14:27:21', '2026-02-08 14:27:21'),
(3, 'Mandiri', 'mandiri', '1122334455', 'PT Gemilang WO', NULL, 'Transfer via ATM, e-Banking, atau Counter', 1, '2026-02-08 14:27:21', '2026-02-08 14:27:21'),
(4, 'Permata', 'permata', '5544332211', 'PT Gemilang WO', NULL, 'Transfer via ATM, Mobile Banking, atau Counter', 1, '2026-02-08 14:27:21', '2026-02-08 14:27:21');

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
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
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
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
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
(29, 'default', '{\"uuid\":\"f46f3070-bcd8-421d-ac37-c97fc3390a0a\",\"displayName\":\"App\\\\Mail\\\\PaymentVerifiedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:28:\\\"App\\\\Mail\\\\PaymentVerifiedMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:12;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:19:\\\"alc@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1769566047,\"delay\":null}', 0, NULL, 1769566047, 1769566047),
(30, 'default', '{\"uuid\":\"39c5cbac-9bb2-4d33-8107-dd329779a7c2\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:14;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:20:\\\"budi@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1776645531,\"delay\":null}', 0, NULL, 1776645531, 1776645531),
(31, 'default', '{\"uuid\":\"dd0b030a-ad79-41f0-bb10-63d1c019382e\",\"displayName\":\"App\\\\Mail\\\\AdminNotificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AdminNotificationMail\\\":4:{s:4:\\\"type\\\";s:9:\\\"new_order\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:14;s:13:\\\"customer_name\\\";s:12:\\\"Budi Santoso\\\";s:14:\\\"customer_email\\\";s:20:\\\"budi@gemilangwo.test\\\";s:14:\\\"customer_phone\\\";s:11:\\\"08111111111\\\";s:12:\\\"package_name\\\";s:6:\\\"Silver\\\";s:11:\\\"total_price\\\";s:11:\\\"57000000.00\\\";s:10:\\\"event_date\\\";s:11:\\\"21 May 2026\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1776645531,\"delay\":null}', 0, NULL, 1776645531, 1776645531),
(32, 'default', '{\"uuid\":\"b37e757e-5009-4f1c-8d66-eeaf590ccb06\",\"displayName\":\"App\\\\Mail\\\\OrderStatusMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:24:\\\"App\\\\Mail\\\\OrderStatusMail\\\":4:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:14;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"previousStatus\\\";s:7:\\\"pending\\\";s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:20:\\\"budi@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1776645625,\"delay\":null}', 0, NULL, 1776645625, 1776645625);
INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(33, 'default', '{\"uuid\":\"c667e218-31ac-4f4b-8537-ef3785311618\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:15;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:20:\\\"budi@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1776645730,\"delay\":null}', 0, NULL, 1776645730, 1776645730),
(34, 'default', '{\"uuid\":\"fbca2562-00f7-4b84-b9de-9ab5d98a6171\",\"displayName\":\"App\\\\Mail\\\\AdminNotificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AdminNotificationMail\\\":4:{s:4:\\\"type\\\";s:9:\\\"new_order\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:15;s:13:\\\"customer_name\\\";s:12:\\\"Budi Santoso\\\";s:14:\\\"customer_email\\\";s:20:\\\"budi@gemilangwo.test\\\";s:14:\\\"customer_phone\\\";s:11:\\\"08111111111\\\";s:12:\\\"package_name\\\";s:6:\\\"Silver\\\";s:11:\\\"total_price\\\";s:11:\\\"57000000.00\\\";s:10:\\\"event_date\\\";s:11:\\\"20 May 2026\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1776645730,\"delay\":null}', 0, NULL, 1776645730, 1776645730),
(35, 'default', '{\"uuid\":\"0bb2ac06-7da2-4ab1-ade6-5677fcb43d25\",\"displayName\":\"App\\\\Mail\\\\OrderStatusMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:24:\\\"App\\\\Mail\\\\OrderStatusMail\\\":4:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:15;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"previousStatus\\\";s:7:\\\"pending\\\";s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:20:\\\"budi@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1776645764,\"delay\":null}', 0, NULL, 1776645764, 1776645764),
(36, 'default', '{\"uuid\":\"38c7158c-9611-4664-9d27-422d00fe8bc8\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:16;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:20:\\\"budi@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1776645794,\"delay\":null}', 0, NULL, 1776645794, 1776645794),
(37, 'default', '{\"uuid\":\"5857b3a4-ab1c-4454-8735-f2936bbb28a0\",\"displayName\":\"App\\\\Mail\\\\AdminNotificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AdminNotificationMail\\\":4:{s:4:\\\"type\\\";s:9:\\\"new_order\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:16;s:13:\\\"customer_name\\\";s:12:\\\"Budi Santoso\\\";s:14:\\\"customer_email\\\";s:20:\\\"budi@gemilangwo.test\\\";s:14:\\\"customer_phone\\\";s:11:\\\"08111111111\\\";s:12:\\\"package_name\\\";s:6:\\\"Deluxe\\\";s:11:\\\"total_price\\\";s:11:\\\"17000000.00\\\";s:10:\\\"event_date\\\";s:11:\\\"19 May 2026\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1776645794,\"delay\":null}', 0, NULL, 1776645794, 1776645794),
(38, 'default', '{\"uuid\":\"875c0edd-9e30-486b-8462-2523f4437875\",\"displayName\":\"App\\\\Mail\\\\OrderStatusMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:24:\\\"App\\\\Mail\\\\OrderStatusMail\\\":4:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:16;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"previousStatus\\\";s:7:\\\"pending\\\";s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:20:\\\"budi@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1776645980,\"delay\":null}', 0, NULL, 1776645980, 1776645980),
(39, 'default', '{\"uuid\":\"dfb730ad-8ce0-46cf-a9be-23510f4b5e06\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:17;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:20:\\\"budi@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1776646011,\"delay\":null}', 0, NULL, 1776646011, 1776646011),
(40, 'default', '{\"uuid\":\"9b218df4-cf94-4232-8c78-acf478330836\",\"displayName\":\"App\\\\Mail\\\\AdminNotificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AdminNotificationMail\\\":4:{s:4:\\\"type\\\";s:9:\\\"new_order\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:17;s:13:\\\"customer_name\\\";s:12:\\\"Budi Santoso\\\";s:14:\\\"customer_email\\\";s:20:\\\"budi@gemilangwo.test\\\";s:14:\\\"customer_phone\\\";s:11:\\\"08111111111\\\";s:12:\\\"package_name\\\";s:6:\\\"Silver\\\";s:11:\\\"total_price\\\";s:11:\\\"57000000.00\\\";s:10:\\\"event_date\\\";s:11:\\\"20 May 2026\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1776646011,\"delay\":null}', 0, NULL, 1776646011, 1776646011),
(41, 'default', '{\"uuid\":\"27710648-d895-4a0b-a56a-a1b386e573e7\",\"displayName\":\"App\\\\Mail\\\\OrderStatusMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:24:\\\"App\\\\Mail\\\\OrderStatusMail\\\":4:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:17;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"previousStatus\\\";s:7:\\\"pending\\\";s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:20:\\\"budi@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1776646897,\"delay\":null}', 0, NULL, 1776646897, 1776646897),
(42, 'default', '{\"uuid\":\"66aafd47-864f-4433-a319-be7b3b2af93a\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:18;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:20:\\\"budi@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1776646926,\"delay\":null}', 0, NULL, 1776646926, 1776646926),
(43, 'default', '{\"uuid\":\"7e768ae3-54bf-4d33-aa53-dc11229aaf5b\",\"displayName\":\"App\\\\Mail\\\\AdminNotificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AdminNotificationMail\\\":4:{s:4:\\\"type\\\";s:9:\\\"new_order\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:18;s:13:\\\"customer_name\\\";s:12:\\\"Budi Santoso\\\";s:14:\\\"customer_email\\\";s:20:\\\"budi@gemilangwo.test\\\";s:14:\\\"customer_phone\\\";s:11:\\\"08111111111\\\";s:12:\\\"package_name\\\";s:6:\\\"Silver\\\";s:11:\\\"total_price\\\";s:11:\\\"57000000.00\\\";s:10:\\\"event_date\\\";s:11:\\\"22 May 2026\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1776646926,\"delay\":null}', 0, NULL, 1776646926, 1776646926),
(44, 'default', '{\"uuid\":\"0cc2a0f5-994f-4b75-9219-1182ac6fde97\",\"displayName\":\"App\\\\Mail\\\\OrderStatusMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:24:\\\"App\\\\Mail\\\\OrderStatusMail\\\":4:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:18;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"previousStatus\\\";s:7:\\\"pending\\\";s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:20:\\\"budi@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1776647002,\"delay\":null}', 0, NULL, 1776647002, 1776647002),
(45, 'default', '{\"uuid\":\"ff9bc3ec-4765-4b80-aeac-6055988d805c\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:19;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:20:\\\"budi@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1776647030,\"delay\":null}', 0, NULL, 1776647030, 1776647030),
(46, 'default', '{\"uuid\":\"b62269d9-9322-489d-9f06-b572b91a9674\",\"displayName\":\"App\\\\Mail\\\\AdminNotificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AdminNotificationMail\\\":4:{s:4:\\\"type\\\";s:9:\\\"new_order\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:19;s:13:\\\"customer_name\\\";s:12:\\\"Budi Santoso\\\";s:14:\\\"customer_email\\\";s:20:\\\"budi@gemilangwo.test\\\";s:14:\\\"customer_phone\\\";s:11:\\\"08111111111\\\";s:12:\\\"package_name\\\";s:6:\\\"Silver\\\";s:11:\\\"total_price\\\";s:11:\\\"57000000.00\\\";s:10:\\\"event_date\\\";s:11:\\\"20 May 2026\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1776647030,\"delay\":null}', 0, NULL, 1776647030, 1776647030),
(47, 'default', '{\"uuid\":\"7d322bc6-2ba5-4610-bdd6-ef572aedc728\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:20;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:20:\\\"budi@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1776647917,\"delay\":null}', 0, NULL, 1776647917, 1776647917),
(48, 'default', '{\"uuid\":\"1f12e537-7144-496c-994c-ad72695e6e07\",\"displayName\":\"App\\\\Mail\\\\AdminNotificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AdminNotificationMail\\\":4:{s:4:\\\"type\\\";s:9:\\\"new_order\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:20;s:13:\\\"customer_name\\\";s:12:\\\"Budi Santoso\\\";s:14:\\\"customer_email\\\";s:20:\\\"budi@gemilangwo.test\\\";s:14:\\\"customer_phone\\\";s:11:\\\"08111111111\\\";s:12:\\\"package_name\\\";s:6:\\\"Silver\\\";s:11:\\\"total_price\\\";s:11:\\\"57000000.00\\\";s:10:\\\"event_date\\\";s:11:\\\"21 May 2026\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1776647917,\"delay\":null}', 0, NULL, 1776647917, 1776647917),
(49, 'default', '{\"uuid\":\"b165bb88-9d97-4678-8ade-995d6a15b577\",\"displayName\":\"App\\\\Mail\\\\OrderStatusMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:24:\\\"App\\\\Mail\\\\OrderStatusMail\\\":4:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:19;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"previousStatus\\\";s:7:\\\"pending\\\";s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:20:\\\"budi@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1776648093,\"delay\":null}', 0, NULL, 1776648093, 1776648093),
(50, 'default', '{\"uuid\":\"ae3122ef-6b8f-4218-b3ad-3f9f9c2085d0\",\"displayName\":\"App\\\\Mail\\\\OrderStatusMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:24:\\\"App\\\\Mail\\\\OrderStatusMail\\\":4:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:20;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"previousStatus\\\";s:7:\\\"pending\\\";s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:20:\\\"budi@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1776648099,\"delay\":null}', 0, NULL, 1776648099, 1776648099),
(51, 'default', '{\"uuid\":\"6438a705-ee00-49c6-9209-934bc9479b73\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:21;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:20:\\\"budi@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1776648129,\"delay\":null}', 0, NULL, 1776648129, 1776648129),
(52, 'default', '{\"uuid\":\"1323a7a2-6085-4c92-bf38-f211fd03bbce\",\"displayName\":\"App\\\\Mail\\\\AdminNotificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AdminNotificationMail\\\":4:{s:4:\\\"type\\\";s:9:\\\"new_order\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:21;s:13:\\\"customer_name\\\";s:12:\\\"Budi Santoso\\\";s:14:\\\"customer_email\\\";s:20:\\\"budi@gemilangwo.test\\\";s:14:\\\"customer_phone\\\";s:11:\\\"08111111111\\\";s:12:\\\"package_name\\\";s:6:\\\"Bagong\\\";s:11:\\\"total_price\\\";s:11:\\\"10000000.00\\\";s:10:\\\"event_date\\\";s:11:\\\"22 May 2026\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1776648129,\"delay\":null}', 0, NULL, 1776648129, 1776648129),
(53, 'default', '{\"uuid\":\"b9f6cdbe-2dcf-4328-a9ac-54c3214e2720\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:22;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:20:\\\"budi@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1780842007,\"delay\":null}', 0, NULL, 1780842007, 1780842007),
(54, 'default', '{\"uuid\":\"67a7277a-afff-4297-966d-30f1cfab0390\",\"displayName\":\"App\\\\Mail\\\\AdminNotificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AdminNotificationMail\\\":4:{s:4:\\\"type\\\";s:9:\\\"new_order\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:22;s:13:\\\"customer_name\\\";s:12:\\\"Budi Santoso\\\";s:14:\\\"customer_email\\\";s:20:\\\"budi@gemilangwo.test\\\";s:14:\\\"customer_phone\\\";s:11:\\\"08111111111\\\";s:12:\\\"package_name\\\";s:6:\\\"Silver\\\";s:11:\\\"total_price\\\";s:11:\\\"57000000.00\\\";s:10:\\\"event_date\\\";s:15:\\\"02 October 2026\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1780842007,\"delay\":null}', 0, NULL, 1780842007, 1780842007),
(55, 'default', '{\"uuid\":\"48a5ab95-3028-4168-92e3-6a288dc899ff\",\"displayName\":\"App\\\\Mail\\\\PaymentInstructionMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:31:\\\"App\\\\Mail\\\\PaymentInstructionMail\\\":5:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:22;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:7:\\\"payment\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Payment\\\";s:2:\\\"id\\\";i:12;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:4:\\\"bank\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\Bank\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:20:\\\"budi@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1780842064,\"delay\":null}', 0, NULL, 1780842064, 1780842064),
(56, 'default', '{\"uuid\":\"5b8c7b3d-ea6d-4682-befb-f84f5692e8f5\",\"displayName\":\"App\\\\Mail\\\\PaymentReceivedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:28:\\\"App\\\\Mail\\\\PaymentReceivedMail\\\":3:{s:7:\\\"payment\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Payment\\\";s:2:\\\"id\\\";i:12;s:9:\\\"relations\\\";a:2:{i:0;s:5:\\\"order\\\";i:1;s:10:\\\"order.user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:20:\\\"budi@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1780842474,\"delay\":null}', 0, NULL, 1780842474, 1780842474),
(57, 'default', '{\"uuid\":\"f5cf8516-fef2-4812-b380-74172d2917fb\",\"displayName\":\"App\\\\Mail\\\\AdminNotificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AdminNotificationMail\\\":4:{s:4:\\\"type\\\";s:16:\\\"payment_received\\\";s:4:\\\"data\\\";a:5:{s:10:\\\"payment_id\\\";i:12;s:8:\\\"order_id\\\";i:22;s:6:\\\"amount\\\";s:11:\\\"17100000.00\\\";s:14:\\\"payment_method\\\";s:13:\\\"bank_transfer\\\";s:13:\\\"customer_name\\\";s:12:\\\"Budi Santoso\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1780842474,\"delay\":null}', 0, NULL, 1780842474, 1780842474),
(58, 'default', '{\"uuid\":\"005922fd-8f68-4742-9abc-4a8554db2d54\",\"displayName\":\"App\\\\Mail\\\\OrderStatusMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:24:\\\"App\\\\Mail\\\\OrderStatusMail\\\":4:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:22;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"previousStatus\\\";s:7:\\\"pending\\\";s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:20:\\\"budi@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1780842474,\"delay\":null}', 0, NULL, 1780842474, 1780842474),
(59, 'default', '{\"uuid\":\"f3081d73-8803-461b-ad9b-942497f380df\",\"displayName\":\"App\\\\Mail\\\\PaymentVerifiedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:28:\\\"App\\\\Mail\\\\PaymentVerifiedMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:22;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:20:\\\"budi@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1780842474,\"delay\":null}', 0, NULL, 1780842474, 1780842474),
(60, 'default', '{\"uuid\":\"e434d47c-d3dc-49c6-9392-a07a1d321695\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:23;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:30:\\\"erlyn.june2026@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781822161,\"delay\":null}', 0, NULL, 1781822161, 1781822161),
(61, 'default', '{\"uuid\":\"f884f9f5-6b3d-4d77-a1ff-390bba1f3a5f\",\"displayName\":\"App\\\\Mail\\\\AdminNotificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AdminNotificationMail\\\":4:{s:4:\\\"type\\\";s:9:\\\"new_order\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:23;s:13:\\\"customer_name\\\";s:5:\\\"Erlyn\\\";s:14:\\\"customer_email\\\";s:30:\\\"erlyn.june2026@gemilangwo.test\\\";s:14:\\\"customer_phone\\\";s:12:\\\"081234560001\\\";s:12:\\\"package_name\\\";s:8:\\\"All in 2\\\";s:11:\\\"total_price\\\";i:183333333;s:10:\\\"event_date\\\";s:12:\\\"01 June 2026\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781822161,\"delay\":null}', 0, NULL, 1781822161, 1781822161),
(62, 'default', '{\"uuid\":\"a6ceb7f9-73bb-4fc8-b8d2-91410d16949e\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:24;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:30:\\\"erlyn.june2026@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781822264,\"delay\":null}', 0, NULL, 1781822264, 1781822264),
(63, 'default', '{\"uuid\":\"0aa30fe5-8e01-4625-b4be-523aa20b415c\",\"displayName\":\"App\\\\Mail\\\\AdminNotificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AdminNotificationMail\\\":4:{s:4:\\\"type\\\";s:9:\\\"new_order\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:24;s:13:\\\"customer_name\\\";s:5:\\\"Erlyn\\\";s:14:\\\"customer_email\\\";s:30:\\\"erlyn.june2026@gemilangwo.test\\\";s:14:\\\"customer_phone\\\";s:12:\\\"081234560001\\\";s:12:\\\"package_name\\\";s:8:\\\"All in 2\\\";s:11:\\\"total_price\\\";i:183333333;s:10:\\\"event_date\\\";s:12:\\\"01 June 2026\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781822264,\"delay\":null}', 0, NULL, 1781822264, 1781822264),
(64, 'default', '{\"uuid\":\"fa17e7f3-2bad-46e4-aba7-24dd0c362653\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:25;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:30:\\\"erlyn.june2026@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781822294,\"delay\":null}', 0, NULL, 1781822294, 1781822294);
INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(65, 'default', '{\"uuid\":\"c0994f14-f08e-451d-bd1e-6694fa1a6ef0\",\"displayName\":\"App\\\\Mail\\\\AdminNotificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AdminNotificationMail\\\":4:{s:4:\\\"type\\\";s:9:\\\"new_order\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:25;s:13:\\\"customer_name\\\";s:5:\\\"Erlyn\\\";s:14:\\\"customer_email\\\";s:30:\\\"erlyn.june2026@gemilangwo.test\\\";s:14:\\\"customer_phone\\\";s:12:\\\"081234560001\\\";s:12:\\\"package_name\\\";s:8:\\\"All in 2\\\";s:11:\\\"total_price\\\";i:183333333;s:10:\\\"event_date\\\";s:12:\\\"01 June 2026\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781822294,\"delay\":null}', 0, NULL, 1781822294, 1781822294),
(66, 'default', '{\"uuid\":\"5bb5a97a-eae2-42ce-b94c-b01ef5ae1a51\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:26;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:30:\\\"nadin.june2026@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781822294,\"delay\":null}', 0, NULL, 1781822294, 1781822294),
(67, 'default', '{\"uuid\":\"d243ca09-21c1-480a-89bf-9aad8290f62e\",\"displayName\":\"App\\\\Mail\\\\AdminNotificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AdminNotificationMail\\\":4:{s:4:\\\"type\\\";s:9:\\\"new_order\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:26;s:13:\\\"customer_name\\\";s:9:\\\"Kak Nadin\\\";s:14:\\\"customer_email\\\";s:30:\\\"nadin.june2026@gemilangwo.test\\\";s:14:\\\"customer_phone\\\";s:12:\\\"081234560002\\\";s:12:\\\"package_name\\\";s:6:\\\"Bagong\\\";s:11:\\\"total_price\\\";i:10000000;s:10:\\\"event_date\\\";s:12:\\\"03 June 2026\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781822294,\"delay\":null}', 0, NULL, 1781822294, 1781822294),
(68, 'default', '{\"uuid\":\"664c1d5c-23ca-4b68-87c3-3d40462e8e4a\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:27;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:30:\\\"nadya.june2026@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781822295,\"delay\":null}', 0, NULL, 1781822295, 1781822295),
(69, 'default', '{\"uuid\":\"687777fc-f20b-4c58-8358-a93a843464ce\",\"displayName\":\"App\\\\Mail\\\\AdminNotificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AdminNotificationMail\\\":4:{s:4:\\\"type\\\";s:9:\\\"new_order\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:27;s:13:\\\"customer_name\\\";s:9:\\\"Kak Nadya\\\";s:14:\\\"customer_email\\\";s:30:\\\"nadya.june2026@gemilangwo.test\\\";s:14:\\\"customer_phone\\\";s:12:\\\"081234560003\\\";s:12:\\\"package_name\\\";s:6:\\\"Bagong\\\";s:11:\\\"total_price\\\";i:10000000;s:10:\\\"event_date\\\";s:12:\\\"10 June 2026\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781822295,\"delay\":null}', 0, NULL, 1781822295, 1781822295),
(70, 'default', '{\"uuid\":\"67ec2157-3c20-4262-8839-31a670a81aa0\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:28;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:32:\\\"nadya.silver2026@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781822295,\"delay\":null}', 0, NULL, 1781822295, 1781822295),
(71, 'default', '{\"uuid\":\"5cb1115e-909d-45ce-9ef7-8a3e6ad8141d\",\"displayName\":\"App\\\\Mail\\\\AdminNotificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AdminNotificationMail\\\":4:{s:4:\\\"type\\\";s:9:\\\"new_order\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:28;s:13:\\\"customer_name\\\";s:9:\\\"Kak Nadya\\\";s:14:\\\"customer_email\\\";s:32:\\\"nadya.silver2026@gemilangwo.test\\\";s:14:\\\"customer_phone\\\";s:12:\\\"081234560004\\\";s:12:\\\"package_name\\\";s:6:\\\"Silver\\\";s:11:\\\"total_price\\\";i:9550000;s:10:\\\"event_date\\\";s:12:\\\"10 June 2026\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781822295,\"delay\":null}', 0, NULL, 1781822295, 1781822295),
(72, 'default', '{\"uuid\":\"b71c1358-fe35-4557-a856-587a796dce5f\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:29;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:31:\\\"hayyun.june2026@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781822295,\"delay\":null}', 0, NULL, 1781822295, 1781822295),
(73, 'default', '{\"uuid\":\"436a596f-46ce-41c7-88b3-26cbf7b1fb01\",\"displayName\":\"App\\\\Mail\\\\AdminNotificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AdminNotificationMail\\\":4:{s:4:\\\"type\\\";s:9:\\\"new_order\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:29;s:13:\\\"customer_name\\\";s:10:\\\"Kak Hayyun\\\";s:14:\\\"customer_email\\\";s:31:\\\"hayyun.june2026@gemilangwo.test\\\";s:14:\\\"customer_phone\\\";s:12:\\\"081234560005\\\";s:12:\\\"package_name\\\";s:6:\\\"Deluxe\\\";s:11:\\\"total_price\\\";i:17000000;s:10:\\\"event_date\\\";s:12:\\\"13 June 2026\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781822295,\"delay\":null}', 0, NULL, 1781822295, 1781822295),
(74, 'default', '{\"uuid\":\"006b3f20-002d-45e2-8143-74003a09e154\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:30;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:32:\\\"futikha.june2026@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781822296,\"delay\":null}', 0, NULL, 1781822296, 1781822296),
(75, 'default', '{\"uuid\":\"06972f79-330e-48eb-b4a4-20315670aafe\",\"displayName\":\"App\\\\Mail\\\\AdminNotificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AdminNotificationMail\\\":4:{s:4:\\\"type\\\";s:9:\\\"new_order\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:30;s:13:\\\"customer_name\\\";s:11:\\\"Kak Futikha\\\";s:14:\\\"customer_email\\\";s:32:\\\"futikha.june2026@gemilangwo.test\\\";s:14:\\\"customer_phone\\\";s:12:\\\"081234560006\\\";s:12:\\\"package_name\\\";s:8:\\\"All in 2\\\";s:11:\\\"total_price\\\";i:164000000;s:10:\\\"event_date\\\";s:12:\\\"14 June 2026\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781822296,\"delay\":null}', 0, NULL, 1781822296, 1781822296),
(76, 'default', '{\"uuid\":\"857ffb69-eda3-4c4f-abb8-498e60186fc4\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:31;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:27:\\\"navi.june14@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781822296,\"delay\":null}', 0, NULL, 1781822296, 1781822296),
(77, 'default', '{\"uuid\":\"ec8f6557-eb68-49b6-a043-e590e0f9845b\",\"displayName\":\"App\\\\Mail\\\\AdminNotificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AdminNotificationMail\\\":4:{s:4:\\\"type\\\";s:9:\\\"new_order\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:31;s:13:\\\"customer_name\\\";s:8:\\\"Kak Navi\\\";s:14:\\\"customer_email\\\";s:27:\\\"navi.june14@gemilangwo.test\\\";s:14:\\\"customer_phone\\\";s:12:\\\"081234560007\\\";s:12:\\\"package_name\\\";s:6:\\\"Petruk\\\";s:11:\\\"total_price\\\";i:9500000;s:10:\\\"event_date\\\";s:12:\\\"14 June 2026\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781822296,\"delay\":null}', 0, NULL, 1781822296, 1781822296),
(78, 'default', '{\"uuid\":\"00a56786-e4e6-41c2-926b-8ef6f3c802e0\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:32;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:30:\\\"arina.june2026@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781822296,\"delay\":null}', 0, NULL, 1781822296, 1781822296),
(79, 'default', '{\"uuid\":\"1a5fe72a-ad49-4f8e-bf13-2c8994bae45f\",\"displayName\":\"App\\\\Mail\\\\AdminNotificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AdminNotificationMail\\\":4:{s:4:\\\"type\\\";s:9:\\\"new_order\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:32;s:13:\\\"customer_name\\\";s:9:\\\"Kak Arina\\\";s:14:\\\"customer_email\\\";s:30:\\\"arina.june2026@gemilangwo.test\\\";s:14:\\\"customer_phone\\\";s:12:\\\"081234560008\\\";s:12:\\\"package_name\\\";s:6:\\\"Bagong\\\";s:11:\\\"total_price\\\";i:9500000;s:10:\\\"event_date\\\";s:12:\\\"20 June 2026\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781822296,\"delay\":null}', 0, NULL, 1781822296, 1781822296),
(80, 'default', '{\"uuid\":\"cfda05a5-bd4f-4ffa-8597-8c95c28ae623\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:33;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:30:\\\"risma.june2026@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781822297,\"delay\":null}', 0, NULL, 1781822297, 1781822297),
(81, 'default', '{\"uuid\":\"9edfd0d8-bd32-4c7c-a044-bdffadf1bb9b\",\"displayName\":\"App\\\\Mail\\\\AdminNotificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AdminNotificationMail\\\":4:{s:4:\\\"type\\\";s:9:\\\"new_order\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:33;s:13:\\\"customer_name\\\";s:9:\\\"Kak Risma\\\";s:14:\\\"customer_email\\\";s:30:\\\"risma.june2026@gemilangwo.test\\\";s:14:\\\"customer_phone\\\";s:12:\\\"081234560009\\\";s:12:\\\"package_name\\\";s:6:\\\"Bagong\\\";s:11:\\\"total_price\\\";i:10000000;s:10:\\\"event_date\\\";s:12:\\\"21 June 2026\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781822297,\"delay\":null}', 0, NULL, 1781822297, 1781822297),
(82, 'default', '{\"uuid\":\"8abbb1a5-f95b-4a65-b99b-ca1eccfd7d8c\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:34;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:27:\\\"navi.june27@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781822297,\"delay\":null}', 0, NULL, 1781822297, 1781822297),
(83, 'default', '{\"uuid\":\"e4248f28-184a-4044-9693-0a00f9591b9d\",\"displayName\":\"App\\\\Mail\\\\AdminNotificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AdminNotificationMail\\\":4:{s:4:\\\"type\\\";s:9:\\\"new_order\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:34;s:13:\\\"customer_name\\\";s:8:\\\"Kak Navi\\\";s:14:\\\"customer_email\\\";s:27:\\\"navi.june27@gemilangwo.test\\\";s:14:\\\"customer_phone\\\";s:12:\\\"081234560010\\\";s:12:\\\"package_name\\\";s:6:\\\"Petruk\\\";s:11:\\\"total_price\\\";i:9500000;s:10:\\\"event_date\\\";s:12:\\\"27 June 2026\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781822297,\"delay\":null}', 0, NULL, 1781822297, 1781822297),
(84, 'default', '{\"uuid\":\"4af91fba-8135-4cab-86d5-edc6649b7f0e\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:35;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:30:\\\"erlyn.june2026@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781823193,\"delay\":null}', 0, NULL, 1781823193, 1781823193),
(85, 'default', '{\"uuid\":\"173a3489-cee2-42e5-aa61-7977940d3eca\",\"displayName\":\"App\\\\Mail\\\\AdminNotificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AdminNotificationMail\\\":4:{s:4:\\\"type\\\";s:9:\\\"new_order\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:35;s:13:\\\"customer_name\\\";s:5:\\\"Erlyn\\\";s:14:\\\"customer_email\\\";s:30:\\\"erlyn.june2026@gemilangwo.test\\\";s:14:\\\"customer_phone\\\";s:12:\\\"081234560001\\\";s:12:\\\"package_name\\\";s:8:\\\"All in 2\\\";s:11:\\\"total_price\\\";i:183333333;s:10:\\\"event_date\\\";s:12:\\\"01 June 2026\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781823193,\"delay\":null}', 0, NULL, 1781823193, 1781823193),
(86, 'default', '{\"uuid\":\"f0dd4ae7-581b-4498-a847-7998741d010a\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:36;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:30:\\\"nadin.june2026@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781823193,\"delay\":null}', 0, NULL, 1781823193, 1781823193),
(87, 'default', '{\"uuid\":\"c695b8cf-c383-4b4b-bc08-ddbdb1c1c6b2\",\"displayName\":\"App\\\\Mail\\\\AdminNotificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AdminNotificationMail\\\":4:{s:4:\\\"type\\\";s:9:\\\"new_order\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:36;s:13:\\\"customer_name\\\";s:9:\\\"Kak Nadin\\\";s:14:\\\"customer_email\\\";s:30:\\\"nadin.june2026@gemilangwo.test\\\";s:14:\\\"customer_phone\\\";s:12:\\\"081234560002\\\";s:12:\\\"package_name\\\";s:6:\\\"Bagong\\\";s:11:\\\"total_price\\\";i:10000000;s:10:\\\"event_date\\\";s:12:\\\"03 June 2026\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781823193,\"delay\":null}', 0, NULL, 1781823193, 1781823193),
(88, 'default', '{\"uuid\":\"abe74028-9fca-442a-9bf1-44be70ce78a2\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:37;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:30:\\\"nadya.june2026@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781823194,\"delay\":null}', 0, NULL, 1781823194, 1781823194),
(89, 'default', '{\"uuid\":\"c0f0ee80-8f16-4016-8857-6ad780120a4e\",\"displayName\":\"App\\\\Mail\\\\AdminNotificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AdminNotificationMail\\\":4:{s:4:\\\"type\\\";s:9:\\\"new_order\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:37;s:13:\\\"customer_name\\\";s:9:\\\"Kak Nadya\\\";s:14:\\\"customer_email\\\";s:30:\\\"nadya.june2026@gemilangwo.test\\\";s:14:\\\"customer_phone\\\";s:12:\\\"081234560003\\\";s:12:\\\"package_name\\\";s:6:\\\"Bagong\\\";s:11:\\\"total_price\\\";i:10000000;s:10:\\\"event_date\\\";s:12:\\\"10 June 2026\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781823194,\"delay\":null}', 0, NULL, 1781823194, 1781823194),
(90, 'default', '{\"uuid\":\"a0abf425-d097-436b-b4d9-c938fe1f8cbc\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:38;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:30:\\\"nadya.june2026@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781823194,\"delay\":null}', 0, NULL, 1781823194, 1781823194),
(91, 'default', '{\"uuid\":\"56b2e124-b376-499e-8252-4acb1fe84a20\",\"displayName\":\"App\\\\Mail\\\\AdminNotificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AdminNotificationMail\\\":4:{s:4:\\\"type\\\";s:9:\\\"new_order\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:38;s:13:\\\"customer_name\\\";s:9:\\\"Kak Nadya\\\";s:14:\\\"customer_email\\\";s:30:\\\"nadya.june2026@gemilangwo.test\\\";s:14:\\\"customer_phone\\\";s:12:\\\"081234560003\\\";s:12:\\\"package_name\\\";s:6:\\\"Silver\\\";s:11:\\\"total_price\\\";i:9550000;s:10:\\\"event_date\\\";s:12:\\\"10 June 2026\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781823194,\"delay\":null}', 0, NULL, 1781823194, 1781823194),
(92, 'default', '{\"uuid\":\"98c174b5-1e80-45d4-869a-76f2a8568ee4\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:39;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:31:\\\"hayyun.june2026@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781823194,\"delay\":null}', 0, NULL, 1781823194, 1781823194),
(93, 'default', '{\"uuid\":\"29c17554-d71d-4a0c-b70e-13de4c3f3c1c\",\"displayName\":\"App\\\\Mail\\\\AdminNotificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AdminNotificationMail\\\":4:{s:4:\\\"type\\\";s:9:\\\"new_order\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:39;s:13:\\\"customer_name\\\";s:10:\\\"Kak Hayyun\\\";s:14:\\\"customer_email\\\";s:31:\\\"hayyun.june2026@gemilangwo.test\\\";s:14:\\\"customer_phone\\\";s:12:\\\"081234560005\\\";s:12:\\\"package_name\\\";s:6:\\\"Deluxe\\\";s:11:\\\"total_price\\\";i:17000000;s:10:\\\"event_date\\\";s:12:\\\"13 June 2026\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781823194,\"delay\":null}', 0, NULL, 1781823194, 1781823194),
(94, 'default', '{\"uuid\":\"a36b4002-67c7-48af-a829-95ca98f22af5\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:40;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:32:\\\"futikha.june2026@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781823195,\"delay\":null}', 0, NULL, 1781823195, 1781823195),
(95, 'default', '{\"uuid\":\"b29d90fb-7fb2-45fe-a639-f789dd9e4014\",\"displayName\":\"App\\\\Mail\\\\AdminNotificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AdminNotificationMail\\\":4:{s:4:\\\"type\\\";s:9:\\\"new_order\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:40;s:13:\\\"customer_name\\\";s:11:\\\"Kak Futikha\\\";s:14:\\\"customer_email\\\";s:32:\\\"futikha.june2026@gemilangwo.test\\\";s:14:\\\"customer_phone\\\";s:12:\\\"081234560006\\\";s:12:\\\"package_name\\\";s:8:\\\"All in 2\\\";s:11:\\\"total_price\\\";i:164000000;s:10:\\\"event_date\\\";s:12:\\\"14 June 2026\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781823195,\"delay\":null}', 0, NULL, 1781823195, 1781823195),
(96, 'default', '{\"uuid\":\"bba03edb-d2e3-4551-87a3-6a2335749c31\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:41;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:29:\\\"navi.june2026@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781823195,\"delay\":null}', 0, NULL, 1781823195, 1781823195);
INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(97, 'default', '{\"uuid\":\"f61fe362-54ea-4a90-941f-9792ca2b9c3d\",\"displayName\":\"App\\\\Mail\\\\AdminNotificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AdminNotificationMail\\\":4:{s:4:\\\"type\\\";s:9:\\\"new_order\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:41;s:13:\\\"customer_name\\\";s:8:\\\"Kak Navi\\\";s:14:\\\"customer_email\\\";s:29:\\\"navi.june2026@gemilangwo.test\\\";s:14:\\\"customer_phone\\\";s:12:\\\"081234560007\\\";s:12:\\\"package_name\\\";s:6:\\\"Petruk\\\";s:11:\\\"total_price\\\";i:9500000;s:10:\\\"event_date\\\";s:12:\\\"14 June 2026\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781823195,\"delay\":null}', 0, NULL, 1781823195, 1781823195),
(98, 'default', '{\"uuid\":\"513994c5-d5b8-4e2e-a3c6-c84b7d8c04b4\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:42;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:30:\\\"arina.june2026@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781823196,\"delay\":null}', 0, NULL, 1781823196, 1781823196),
(99, 'default', '{\"uuid\":\"779eecda-b8e4-4f63-b79d-e941326eb031\",\"displayName\":\"App\\\\Mail\\\\AdminNotificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AdminNotificationMail\\\":4:{s:4:\\\"type\\\";s:9:\\\"new_order\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:42;s:13:\\\"customer_name\\\";s:9:\\\"Kak Arina\\\";s:14:\\\"customer_email\\\";s:30:\\\"arina.june2026@gemilangwo.test\\\";s:14:\\\"customer_phone\\\";s:12:\\\"081234560008\\\";s:12:\\\"package_name\\\";s:6:\\\"Bagong\\\";s:11:\\\"total_price\\\";i:9500000;s:10:\\\"event_date\\\";s:12:\\\"20 June 2026\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781823196,\"delay\":null}', 0, NULL, 1781823196, 1781823196),
(100, 'default', '{\"uuid\":\"4cc9d484-b876-46f4-a5c4-42dd59720ad0\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:43;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:30:\\\"risma.june2026@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781823196,\"delay\":null}', 0, NULL, 1781823196, 1781823196),
(101, 'default', '{\"uuid\":\"c7ba0ea2-fb6f-4ba0-b6b6-78791a33af31\",\"displayName\":\"App\\\\Mail\\\\AdminNotificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AdminNotificationMail\\\":4:{s:4:\\\"type\\\";s:9:\\\"new_order\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:43;s:13:\\\"customer_name\\\";s:9:\\\"Kak Risma\\\";s:14:\\\"customer_email\\\";s:30:\\\"risma.june2026@gemilangwo.test\\\";s:14:\\\"customer_phone\\\";s:12:\\\"081234560009\\\";s:12:\\\"package_name\\\";s:6:\\\"Bagong\\\";s:11:\\\"total_price\\\";i:10000000;s:10:\\\"event_date\\\";s:12:\\\"21 June 2026\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781823196,\"delay\":null}', 0, NULL, 1781823196, 1781823196),
(102, 'default', '{\"uuid\":\"879e4621-30e2-41ea-abb6-57cb7fd8dde6\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:44;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:29:\\\"navi.june2026@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781823196,\"delay\":null}', 0, NULL, 1781823196, 1781823196),
(103, 'default', '{\"uuid\":\"ed947869-dc84-4c0e-a460-64b4eb60d299\",\"displayName\":\"App\\\\Mail\\\\AdminNotificationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\AdminNotificationMail\\\":4:{s:4:\\\"type\\\";s:9:\\\"new_order\\\";s:4:\\\"data\\\";a:7:{s:8:\\\"order_id\\\";i:44;s:13:\\\"customer_name\\\";s:8:\\\"Kak Navi\\\";s:14:\\\"customer_email\\\";s:29:\\\"navi.june2026@gemilangwo.test\\\";s:14:\\\"customer_phone\\\";s:12:\\\"081234560007\\\";s:12:\\\"package_name\\\";s:6:\\\"Petruk\\\";s:11:\\\"total_price\\\";i:9500000;s:10:\\\"event_date\\\";s:12:\\\"27 June 2026\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"admin@gemilangwo.test\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1781823196,\"delay\":null}', 0, NULL, 1781823196, 1781823196);

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
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
  `migration` varchar(255) NOT NULL,
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
(27, '2026_01_27_add_va_fields_to_payments_table', 2),
(29, '2026_01_27_100001_create_banks_table', 3),
(30, '2026_02_01_100001_create_vendor_categories_table', 3),
(31, '2026_02_01_100002_create_vendors_table', 3),
(32, '2026_02_01_100003_create_package_vendor_category_table', 3),
(33, '2026_02_01_100004_create_order_vendors_table', 3),
(34, '2026_04_20_100000_add_default_vendor_id_to_package_vendor_category_table', 4),
(35, '2026_06_07_204519_add_payment_scheme_to_orders_table', 5),
(36, '2026_06_07_204540_add_installment_fields_to_payments_table', 5),
(37, '2026_06_07_210000_create_payment_schemes_table', 6),
(38, '2026_06_07_210100_backfill_order_payment_totals', 6),
(39, '2026_06_19_100000_expand_payment_scheme_enum', 7);

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
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `package_id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(255) NOT NULL,
  `event_date` date NOT NULL,
  `pre_event_days` int(11) NOT NULL DEFAULT 0,
  `post_event_days` int(11) NOT NULL DEFAULT 0,
  `calendar_confirmed` tinyint(1) NOT NULL DEFAULT 0,
  `event_location` varchar(255) NOT NULL,
  `guest_count` int(11) NOT NULL,
  `special_request` text DEFAULT NULL,
  `total_price` decimal(12,2) NOT NULL,
  `payment_scheme` enum('full_payment','dp_20','dp_30','dp_40','dp_50','installment_3x','installment_5x') DEFAULT 'full_payment',
  `dp_percentage` decimal(5,2) DEFAULT NULL,
  `total_paid` decimal(12,2) NOT NULL DEFAULT 0.00,
  `remaining_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `payment_status` enum('unpaid','dp_paid','partially_paid','fully_paid') NOT NULL DEFAULT 'unpaid',
  `status` enum('pending','confirmed','in_progress','completed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `package_id`, `order_number`, `event_date`, `pre_event_days`, `post_event_days`, `calendar_confirmed`, `event_location`, `guest_count`, `special_request`, `total_price`, `payment_scheme`, `dp_percentage`, `total_paid`, `remaining_amount`, `payment_status`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 3, 2, 'WO-1767756323001', '2026-02-06', 0, 0, 0, 'Bandung Convention Center', 250, 'Ingin tema warna emas dan putih, dengan dekorasi bunga mawar merah', 100000000.00, 'full_payment', NULL, 100000000.00, 0.00, 'fully_paid', 'confirmed', '2026-01-06 20:25:23', '2026-06-07 14:04:24', NULL),
(2, 4, 1, 'WO-1767756323002', '2026-02-21', 0, 0, 0, 'Yogyakarta Palace Hall', 350, 'Menggabungkan tradisi Jawa dengan modern. Perlu live gamelan', 150000000.00, 'full_payment', NULL, 0.00, 150000000.00, 'unpaid', 'pending', '2026-01-06 20:25:23', '2026-06-07 14:04:24', NULL),
(3, 5, 3, 'WO-1767756323003', '2026-03-08', 0, 0, 0, 'Medan Grand Hotel', 200, 'Tema modern minimalis. Dokumentasi lengkap dimulai dari pengiring pengantin', 60000000.00, 'full_payment', NULL, 60000000.00, 0.00, 'fully_paid', 'confirmed', '2026-01-06 20:25:23', '2026-06-07 14:04:24', NULL),
(4, 6, 4, 'WO-1767756323004', '2026-01-27', 0, 0, 0, 'Makassar Seaside Resort', 500, 'Tema tepi pantai dengan sunset ceremony. Perlu coordinator yang responsif', 250000000.00, 'full_payment', NULL, 250000000.00, 0.00, 'fully_paid', 'in_progress', '2026-01-06 20:25:23', '2026-06-07 14:04:24', NULL),
(5, 7, 1, 'WO-1767756323005', '2026-01-22', 0, 0, 0, 'Malang City Hotel', 100, 'Acara sederhana dan hangat dengan keluarga besar', 35000000.00, 'full_payment', NULL, 35000000.00, 0.00, 'fully_paid', 'completed', '2026-01-06 20:25:23', '2026-06-07 14:04:24', NULL),
(6, 8, 6, 'WO-1767756323006', '2026-02-26', 0, 0, 0, 'Semarang International Convention Center', 400, 'Menginginkan dokumentasi cinematic berkualitas tinggi dan koordinator profesional', 180000000.00, 'full_payment', NULL, 180000000.00, 0.00, 'fully_paid', 'confirmed', '2026-01-06 20:25:23', '2026-06-07 14:04:24', NULL),
(7, 3, 3, 'WO-1767756323007', '2026-04-07', 0, 0, 0, 'Bandung City Resort', 150, 'Tema outdoor garden party', 60000000.00, 'full_payment', NULL, 0.00, 60000000.00, 'unpaid', 'cancelled', '2026-01-06 20:25:23', '2026-06-07 14:04:24', NULL),
(8, 9, 6, 'WO-1769516011-9058', '2026-05-20', 0, 0, 0, 'Hotel Griptha', 950, 'aa', 57000000.00, 'full_payment', NULL, 0.00, 57000000.00, 'unpaid', 'cancelled', '2026-01-27 05:13:31', '2026-06-07 14:04:24', NULL),
(9, 9, 6, 'WO-1769516183-5763', '2026-05-31', 0, 0, 0, 'Hotel Griptha', 950, 'aa', 57000000.00, 'full_payment', NULL, 0.00, 57000000.00, 'unpaid', 'cancelled', '2026-01-27 05:16:23', '2026-06-07 14:04:24', NULL),
(10, 9, 6, 'WO-6951683861', '2026-05-20', 0, 0, 0, 'Griptha', 950, 'aa', 57000000.00, 'full_payment', NULL, 0.00, 57000000.00, 'unpaid', 'cancelled', '2026-01-27 05:27:18', '2026-06-07 14:04:24', NULL),
(11, 9, 6, 'WO-6951750441', '2026-05-20', 0, 0, 0, 'Griptha', 950, '111', 57000000.00, 'full_payment', NULL, 0.00, 57000000.00, 'unpaid', 'cancelled', '2026-01-27 05:38:24', '2026-06-07 14:04:24', NULL),
(12, 9, 6, 'WO-6952144064', '2026-02-28', 0, 0, 0, 'Griptha', 960, 'aa', 57000000.00, 'full_payment', NULL, 57000000.00, 0.00, 'fully_paid', 'confirmed', '2026-01-27 06:44:00', '2026-06-07 14:04:24', NULL),
(13, 1, 1, 'TEST-1769561740', '2026-04-28', 0, 0, 0, 'Test Location', 100, NULL, 4500000.00, 'full_payment', NULL, 4500000.00, 0.00, 'fully_paid', 'confirmed', '2026-01-27 17:55:40', '2026-06-07 14:04:24', NULL),
(14, 3, 6, 'WO-7664553169', '2026-05-21', 0, 0, 0, 'Venue', 500, NULL, 57000000.00, 'full_payment', NULL, 0.00, 57000000.00, 'unpaid', 'cancelled', '2026-04-20 00:38:51', '2026-06-07 14:04:24', NULL),
(15, 3, 6, 'WO-7664573020', '2026-05-20', 0, 0, 0, 'Venue', 500, NULL, 57000000.00, 'full_payment', NULL, 0.00, 57000000.00, 'unpaid', 'cancelled', '2026-04-20 00:42:10', '2026-06-07 14:04:25', NULL),
(16, 3, 5, 'WO-7664579460', '2026-05-19', 0, 0, 0, 'Venue', 120, NULL, 17000000.00, 'full_payment', NULL, 0.00, 17000000.00, 'unpaid', 'cancelled', '2026-04-20 00:43:14', '2026-06-07 14:04:25', NULL),
(17, 3, 6, 'WO-7664601061', '2026-05-20', 0, 0, 0, 'Venue', 500, NULL, 57000000.00, 'full_payment', NULL, 0.00, 57000000.00, 'unpaid', 'cancelled', '2026-04-20 00:46:50', '2026-06-07 14:04:25', NULL),
(18, 3, 6, 'WO-7664692623', '2026-05-22', 0, 0, 0, 'Venue', 500, NULL, 57000000.00, 'full_payment', NULL, 0.00, 57000000.00, 'unpaid', 'cancelled', '2026-04-20 01:02:06', '2026-06-07 14:04:25', NULL),
(19, 3, 6, 'WO-7664702925', '2026-05-20', 0, 0, 0, 'Venue', 500, NULL, 57000000.00, 'full_payment', NULL, 0.00, 57000000.00, 'unpaid', 'cancelled', '2026-04-20 01:03:49', '2026-06-07 14:04:25', NULL),
(20, 3, 6, 'WO-7664791740', '2026-05-21', 0, 0, 0, 'Venue', 500, NULL, 57000000.00, 'full_payment', NULL, 0.00, 57000000.00, 'unpaid', 'cancelled', '2026-04-20 01:18:37', '2026-06-07 14:04:25', NULL),
(21, 3, 4, 'WO-7664812964', '2026-05-22', 0, 0, 0, 'Venue', 100, NULL, 10000000.00, 'full_payment', NULL, 0.00, 10000000.00, 'unpaid', 'pending', '2026-04-20 01:22:09', '2026-06-07 14:04:25', NULL),
(22, 3, 6, 'WO-8084200719', '2026-10-02', 0, 0, 0, 'Venue', 300, 'Test', 57000000.00, 'dp_30', 30.00, 17100000.00, 39900000.00, 'dp_paid', 'confirmed', '2026-06-07 14:20:07', '2026-06-07 14:27:54', NULL),
(35, 20, 9, 'WO-20260601-ALL_IN_2', '2026-06-01', 0, 0, 0, 'Gedung Serbaguna Kudus', 800, NULL, 183333333.00, 'dp_30', 30.00, 55000000.00, 128333333.00, 'dp_paid', 'confirmed', '2026-06-18 22:53:13', '2026-06-18 22:53:13', NULL),
(36, 21, 4, 'WO-20260603-BAGONG', '2026-06-03', 0, 0, 0, 'Gedung Wisma Halim Demak', 500, NULL, 10000000.00, 'dp_30', 30.00, 3000000.00, 7000000.00, 'dp_paid', 'confirmed', '2026-06-18 22:53:13', '2026-06-18 22:53:13', NULL),
(37, 22, 4, 'WO-20260610-BAGONG', '2026-06-10', 0, 0, 0, 'Gedung Wisma Haji Dempet Demak', 500, 'Paket Bagong', 10000000.00, 'dp_20', 20.00, 2000000.00, 8000000.00, 'dp_paid', 'confirmed', '2026-06-18 22:53:14', '2026-06-18 22:53:14', NULL),
(38, 22, 6, 'WO-20260610-SILVER_CUSTOM', '2026-06-10', 0, 0, 0, 'Gedung Wisma Halim DEMAK', 400, 'Paket Silver - Add on Dekor tambah tenda, kursi, charge dekor Utama, genset, lightning, standing ac, transport team wo, pranata cara, talent music', 9550000.00, 'dp_20', 20.00, 2250000.00, 7300000.00, 'dp_paid', 'confirmed', '2026-06-18 22:53:14', '2026-06-18 22:53:14', NULL),
(39, 23, 5, 'WO-20260613-DELUXE', '2026-06-13', 0, 0, 0, 'Sapphire Hotel Lingkar', 300, 'Custom dari paket deluxe', 17000000.00, 'dp_30', 30.00, 5100000.00, 11900000.00, 'dp_paid', 'confirmed', '2026-06-18 22:53:14', '2026-06-18 22:53:14', NULL),
(40, 24, 9, 'WO-20260614-ALL_IN_2', '2026-06-14', 0, 0, 0, 'Sapphire Hotel Lingkar', 1000, 'Add on: Photo booth, lightning, MUA, Domas, Charge Venue, Dekor Utama, Catering, Package Catering', 164000000.00, 'installment_5x', NULL, 10100000.00, 153900000.00, 'partially_paid', 'confirmed', '2026-06-18 22:53:15', '2026-06-18 22:53:15', NULL),
(41, 25, 3, 'WO-20260614-PETRUK', '2026-06-14', 0, 0, 0, 'Gedung DPRD Kudus', 400, 'Charge tamu, add on kipas blower', 9500000.00, 'dp_20', 20.00, 2250000.00, 7250000.00, 'dp_paid', 'confirmed', '2026-06-18 22:53:15', '2026-06-18 22:53:15', NULL),
(42, 26, 4, 'WO-20260620-BAGONG', '2026-06-20', 0, 0, 0, 'Gedung Mitua Vie Tahunan Jepara', 500, 'Paket Bagong Resepsi Only', 9500000.00, 'dp_20', 20.00, 1500000.00, 8000000.00, 'dp_paid', 'confirmed', '2026-06-18 22:53:16', '2026-06-18 22:53:16', NULL),
(43, 27, 4, 'WO-20260621-BAGONG', '2026-06-21', 0, 0, 0, 'Gedung Wisma Halim Demak', 500, 'Paket Bagong Resepsi Only', 10000000.00, 'dp_30', 30.00, 3000000.00, 7000000.00, 'dp_paid', 'confirmed', '2026-06-18 22:53:16', '2026-06-18 22:53:16', NULL),
(44, 25, 3, 'WO-20260627-PETRUK', '2026-06-27', 0, 0, 0, 'Sekuro Village Venue', 300, NULL, 9500000.00, 'dp_30', 30.00, 2850000.00, 6650000.00, 'dp_paid', 'confirmed', '2026-06-18 22:53:16', '2026-06-18 22:53:16', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_vendors`
--

CREATE TABLE `order_vendors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `vendor_id` bigint(20) UNSIGNED NOT NULL,
  `vendor_category_id` bigint(20) UNSIGNED NOT NULL,
  `vendor_name` varchar(255) NOT NULL,
  `vendor_category_name` varchar(255) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `order_vendors`
--

INSERT INTO `order_vendors` (`id`, `order_id`, `vendor_id`, `vendor_category_id`, `vendor_name`, `vendor_category_name`, `price`, `created_at`, `updated_at`) VALUES
(1, 14, 3, 1, 'Catering Nusantara', 'Catering', 20000000.00, '2026-04-20 00:38:51', '2026-04-20 00:38:51'),
(2, 14, 6, 2, 'Dekorasi Minimalis', 'Dekorasi', 10000000.00, '2026-04-20 00:38:51', '2026-04-20 00:38:51'),
(3, 14, 7, 3, 'Studio Foto Pro', 'Fotografi & Videografi', 12000000.00, '2026-04-20 00:38:51', '2026-04-20 00:38:51'),
(4, 14, 11, 4, 'Live Band', 'Musik & DJ', 15000000.00, '2026-04-20 00:38:51', '2026-04-20 00:38:51'),
(5, 14, 13, 5, 'MC Profesional', 'Master of Ceremony', 5000000.00, '2026-04-20 00:38:51', '2026-04-20 00:38:51'),
(6, 14, 15, 6, 'Beauty Studio', 'Hair & Make Up', 3000000.00, '2026-04-20 00:38:51', '2026-04-20 00:38:51'),
(7, 14, 17, 7, 'Rental Mobil Mewah', 'Transportasi', 8000000.00, '2026-04-20 00:38:51', '2026-04-20 00:38:51'),
(8, 15, 3, 1, 'Catering Nusantara', 'Catering', 20000000.00, '2026-04-20 00:42:10', '2026-04-20 00:42:10'),
(9, 15, 6, 2, 'Dekorasi Minimalis', 'Dekorasi', 10000000.00, '2026-04-20 00:42:10', '2026-04-20 00:42:10'),
(10, 15, 7, 3, 'Studio Foto Pro', 'Fotografi & Videografi', 12000000.00, '2026-04-20 00:42:10', '2026-04-20 00:42:10'),
(11, 15, 11, 4, 'Live Band', 'Musik & DJ', 15000000.00, '2026-04-20 00:42:10', '2026-04-20 00:42:10'),
(12, 15, 13, 5, 'MC Profesional', 'Master of Ceremony', 5000000.00, '2026-04-20 00:42:10', '2026-04-20 00:42:10'),
(13, 15, 15, 6, 'Beauty Studio', 'Hair & Make Up', 3000000.00, '2026-04-20 00:42:10', '2026-04-20 00:42:10'),
(14, 15, 17, 7, 'Rental Mobil Mewah', 'Transportasi', 8000000.00, '2026-04-20 00:42:10', '2026-04-20 00:42:10'),
(15, 16, 3, 1, 'Catering Nusantara', 'Catering', 20000000.00, '2026-04-20 00:43:14', '2026-04-20 00:43:14'),
(16, 16, 6, 2, 'Dekorasi Minimalis', 'Dekorasi', 10000000.00, '2026-04-20 00:43:14', '2026-04-20 00:43:14'),
(17, 16, 7, 3, 'Studio Foto Pro', 'Fotografi & Videografi', 12000000.00, '2026-04-20 00:43:14', '2026-04-20 00:43:14'),
(18, 16, 11, 4, 'Live Band', 'Musik & DJ', 15000000.00, '2026-04-20 00:43:14', '2026-04-20 00:43:14'),
(19, 16, 13, 5, 'MC Profesional', 'Master of Ceremony', 5000000.00, '2026-04-20 00:43:14', '2026-04-20 00:43:14'),
(20, 16, 15, 6, 'Beauty Studio', 'Hair & Make Up', 3000000.00, '2026-04-20 00:43:14', '2026-04-20 00:43:14'),
(21, 17, 3, 1, 'Catering Nusantara', 'Catering', 20000000.00, '2026-04-20 00:46:51', '2026-04-20 00:46:51'),
(22, 17, 6, 2, 'Dekorasi Minimalis', 'Dekorasi', 10000000.00, '2026-04-20 00:46:51', '2026-04-20 00:46:51'),
(23, 17, 7, 3, 'Studio Foto Pro', 'Fotografi & Videografi', 12000000.00, '2026-04-20 00:46:51', '2026-04-20 00:46:51'),
(24, 17, 11, 4, 'Live Band', 'Musik & DJ', 15000000.00, '2026-04-20 00:46:51', '2026-04-20 00:46:51'),
(25, 17, 13, 5, 'MC Profesional', 'Master of Ceremony', 5000000.00, '2026-04-20 00:46:51', '2026-04-20 00:46:51'),
(26, 17, 15, 6, 'Beauty Studio', 'Hair & Make Up', 3000000.00, '2026-04-20 00:46:51', '2026-04-20 00:46:51'),
(27, 17, 17, 7, 'Rental Mobil Mewah', 'Transportasi', 8000000.00, '2026-04-20 00:46:51', '2026-04-20 00:46:51'),
(28, 18, 3, 1, 'Catering Nusantara', 'Catering', 20000000.00, '2026-04-20 01:02:06', '2026-04-20 01:02:06'),
(29, 18, 6, 2, 'Dekorasi Minimalis', 'Dekorasi', 10000000.00, '2026-04-20 01:02:06', '2026-04-20 01:02:06'),
(30, 18, 7, 3, 'Studio Foto Pro', 'Fotografi & Videografi', 12000000.00, '2026-04-20 01:02:06', '2026-04-20 01:02:06'),
(31, 18, 11, 4, 'Live Band', 'Musik & DJ', 15000000.00, '2026-04-20 01:02:06', '2026-04-20 01:02:06'),
(32, 18, 13, 5, 'MC Profesional', 'Master of Ceremony', 5000000.00, '2026-04-20 01:02:06', '2026-04-20 01:02:06'),
(33, 18, 15, 6, 'Beauty Studio', 'Hair & Make Up', 3000000.00, '2026-04-20 01:02:06', '2026-04-20 01:02:06'),
(34, 18, 17, 7, 'Rental Mobil Mewah', 'Transportasi', 8000000.00, '2026-04-20 01:02:06', '2026-04-20 01:02:06'),
(35, 19, 3, 1, 'Catering Nusantara', 'Catering', 20000000.00, '2026-04-20 01:03:50', '2026-04-20 01:03:50'),
(36, 19, 6, 2, 'Dekorasi Minimalis', 'Dekorasi', 10000000.00, '2026-04-20 01:03:50', '2026-04-20 01:03:50'),
(37, 19, 7, 3, 'Studio Foto Pro', 'Fotografi & Videografi', 12000000.00, '2026-04-20 01:03:50', '2026-04-20 01:03:50'),
(38, 19, 11, 4, 'Live Band', 'Musik & DJ', 15000000.00, '2026-04-20 01:03:50', '2026-04-20 01:03:50'),
(39, 19, 13, 5, 'MC Profesional', 'Master of Ceremony', 5000000.00, '2026-04-20 01:03:50', '2026-04-20 01:03:50'),
(40, 19, 15, 6, 'Beauty Studio', 'Hair & Make Up', 3000000.00, '2026-04-20 01:03:50', '2026-04-20 01:03:50'),
(41, 19, 17, 7, 'Rental Mobil Mewah', 'Transportasi', 8000000.00, '2026-04-20 01:03:50', '2026-04-20 01:03:50'),
(42, 20, 3, 1, 'Catering Nusantara', 'Catering', 20000000.00, '2026-04-20 01:18:38', '2026-04-20 01:18:38'),
(43, 20, 6, 2, 'Dekorasi Minimalis', 'Dekorasi', 10000000.00, '2026-04-20 01:18:38', '2026-04-20 01:18:38'),
(44, 20, 7, 3, 'Studio Foto Pro', 'Fotografi & Videografi', 12000000.00, '2026-04-20 01:18:38', '2026-04-20 01:18:38'),
(45, 20, 11, 4, 'Live Band', 'Musik & DJ', 15000000.00, '2026-04-20 01:18:38', '2026-04-20 01:18:38'),
(46, 20, 13, 5, 'MC Profesional', 'Master of Ceremony', 5000000.00, '2026-04-20 01:18:38', '2026-04-20 01:18:38'),
(47, 20, 15, 6, 'Beauty Studio', 'Hair & Make Up', 3000000.00, '2026-04-20 01:18:38', '2026-04-20 01:18:38'),
(48, 20, 17, 7, 'Rental Mobil Mewah', 'Transportasi', 8000000.00, '2026-04-20 01:18:38', '2026-04-20 01:18:38'),
(49, 21, 3, 1, 'Catering Nusantara', 'Catering', 20000000.00, '2026-04-20 01:22:09', '2026-04-20 01:22:09'),
(50, 21, 6, 2, 'Dekorasi Minimalis', 'Dekorasi', 10000000.00, '2026-04-20 01:22:09', '2026-04-20 01:22:09'),
(51, 21, 7, 3, 'Studio Foto Pro', 'Fotografi & Videografi', 12000000.00, '2026-04-20 01:22:09', '2026-04-20 01:22:09'),
(52, 21, 10, 4, 'DJ Entertainment', 'Musik & DJ', 5000000.00, '2026-04-20 01:22:09', '2026-04-20 01:22:09'),
(53, 21, 13, 5, 'MC Profesional', 'Master of Ceremony', 5000000.00, '2026-04-20 01:22:09', '2026-04-20 01:22:09'),
(54, 22, 3, 1, 'Catering Nusantara', 'Catering', 20000000.00, '2026-06-07 14:20:07', '2026-06-07 14:20:07'),
(55, 22, 6, 2, 'Dekorasi Minimalis', 'Dekorasi', 10000000.00, '2026-06-07 14:20:07', '2026-06-07 14:20:07'),
(56, 22, 7, 3, 'Studio Foto Pro', 'Fotografi & Videografi', 12000000.00, '2026-06-07 14:20:07', '2026-06-07 14:20:07'),
(57, 22, 11, 4, 'Live Band', 'Musik & DJ', 15000000.00, '2026-06-07 14:20:07', '2026-06-07 14:20:07'),
(58, 22, 13, 5, 'MC Profesional', 'Master of Ceremony', 5000000.00, '2026-06-07 14:20:07', '2026-06-07 14:20:07'),
(59, 22, 15, 6, 'Beauty Studio', 'Hair & Make Up', 3000000.00, '2026-06-07 14:20:07', '2026-06-07 14:20:07'),
(60, 22, 17, 7, 'Rental Mobil Mewah', 'Transportasi', 8000000.00, '2026-06-07 14:20:07', '2026-06-07 14:20:07');

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
-- Struktur dari tabel `package_vendor_category`
--

CREATE TABLE `package_vendor_category` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_id` bigint(20) UNSIGNED NOT NULL,
  `vendor_category_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `default_vendor_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `package_vendor_category`
--

INSERT INTO `package_vendor_category` (`id`, `package_id`, `vendor_category_id`, `created_at`, `updated_at`, `default_vendor_id`) VALUES
(1, 6, 1, NULL, NULL, 3),
(2, 6, 2, NULL, NULL, 6),
(3, 6, 3, NULL, NULL, 7),
(4, 6, 4, NULL, NULL, 11),
(5, 6, 5, NULL, NULL, 13),
(6, 6, 6, NULL, NULL, 15),
(7, 6, 7, NULL, NULL, 17),
(8, 1, 1, NULL, NULL, 3),
(9, 1, 5, NULL, NULL, 13),
(10, 2, 1, NULL, NULL, 3),
(11, 2, 2, NULL, NULL, 6),
(12, 2, 5, NULL, NULL, 13),
(13, 3, 1, NULL, NULL, 3),
(14, 3, 2, NULL, NULL, 6),
(15, 3, 3, NULL, NULL, 7),
(16, 3, 5, NULL, NULL, 13),
(17, 4, 1, NULL, NULL, 3),
(18, 4, 2, NULL, NULL, 6),
(19, 4, 3, NULL, NULL, 7),
(20, 4, 4, NULL, NULL, 10),
(21, 4, 5, NULL, NULL, 13),
(22, 5, 1, NULL, NULL, 3),
(23, 5, 2, NULL, NULL, 6),
(24, 5, 3, NULL, NULL, 7),
(25, 5, 4, NULL, NULL, 11),
(26, 5, 5, NULL, NULL, 13),
(27, 5, 6, NULL, NULL, 15),
(28, 7, 1, NULL, NULL, 1),
(29, 7, 2, NULL, NULL, 5),
(30, 7, 3, NULL, NULL, 8),
(31, 7, 4, NULL, NULL, 11),
(32, 7, 5, NULL, NULL, 14),
(33, 7, 6, NULL, NULL, 16),
(34, 7, 7, NULL, NULL, 18),
(35, 8, 1, NULL, NULL, 2),
(36, 8, 2, NULL, NULL, 4),
(37, 8, 3, NULL, NULL, 9),
(38, 8, 4, NULL, NULL, 12),
(39, 8, 5, NULL, NULL, 14),
(40, 8, 6, NULL, NULL, 16),
(41, 8, 7, NULL, NULL, 18),
(42, 9, 1, NULL, NULL, 2),
(43, 9, 2, NULL, NULL, 4),
(44, 9, 3, NULL, NULL, 9),
(45, 9, 4, NULL, NULL, 12),
(46, 9, 5, NULL, NULL, 14),
(47, 9, 6, NULL, NULL, 16),
(48, 9, 7, NULL, NULL, 18);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `payment_id` varchar(255) NOT NULL,
  `bank_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payment_method` enum('credit_card','bank_transfer','e_wallet','cash') DEFAULT NULL,
  `payment_type` enum('full','dp','installment','remaining') NOT NULL DEFAULT 'full',
  `installment_number` int(11) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `payment_proof_path` varchar(255) DEFAULT NULL,
  `va_number` varchar(255) DEFAULT NULL,
  `bank` varchar(255) DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `status` enum('pending','processing','success','failed','cancelled') NOT NULL DEFAULT 'pending',
  `verification_status` enum('pending','verified','rejected') NOT NULL DEFAULT 'pending',
  `verified_by` bigint(20) UNSIGNED DEFAULT NULL,
  `verification_notes` text DEFAULT NULL,
  `payment_note` text DEFAULT NULL,
  `midtrans_response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`midtrans_response`)),
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `payment_id`, `bank_id`, `payment_method`, `payment_type`, `installment_number`, `due_date`, `payment_proof_path`, `va_number`, `bank`, `amount`, `status`, `verification_status`, `verified_by`, `verification_notes`, `payment_note`, `midtrans_response`, `paid_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'MIDTRANS-1767756323001', NULL, 'credit_card', 'full', NULL, NULL, NULL, NULL, NULL, 100000000.00, 'success', 'pending', NULL, NULL, NULL, NULL, '2026-01-01 20:25:23', '2026-01-06 20:25:23', '2026-01-06 20:25:23'),
(2, 2, 'MIDTRANS-1767756323002', NULL, NULL, 'full', NULL, NULL, NULL, NULL, NULL, 150000000.00, 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, '2026-01-06 20:25:23', '2026-01-06 20:25:23'),
(3, 3, 'MIDTRANS-1767756323003', NULL, 'bank_transfer', 'full', NULL, NULL, NULL, NULL, NULL, 60000000.00, 'success', 'pending', NULL, NULL, NULL, NULL, '2025-12-27 20:25:23', '2026-01-06 20:25:23', '2026-01-06 20:25:23'),
(4, 4, 'MIDTRANS-1767756323004', NULL, 'e_wallet', 'full', NULL, NULL, NULL, NULL, NULL, 250000000.00, 'success', 'pending', NULL, NULL, NULL, NULL, '2025-12-22 20:25:23', '2026-01-06 20:25:23', '2026-01-06 20:25:23'),
(5, 5, 'MIDTRANS-1767756323005', NULL, 'bank_transfer', 'full', NULL, NULL, NULL, NULL, NULL, 35000000.00, 'success', 'pending', NULL, NULL, NULL, NULL, '2025-12-17 20:25:23', '2026-01-06 20:25:23', '2026-01-06 20:25:23'),
(6, 6, 'MIDTRANS-1767756323006', NULL, 'credit_card', 'full', NULL, NULL, NULL, NULL, NULL, 180000000.00, 'success', 'pending', NULL, NULL, NULL, NULL, '2026-01-03 20:25:23', '2026-01-06 20:25:23', '2026-01-06 20:25:23'),
(7, 12, 'MANUAL-WO-6952144064-Jxgogdk7', 2, 'bank_transfer', 'full', NULL, NULL, NULL, NULL, NULL, 57000000.00, 'success', 'verified', 1, NULL, NULL, NULL, '2026-01-27 19:07:26', '2026-01-27 07:03:03', '2026-01-27 19:07:27'),
(8, 12, 'MANUAL-WO-6952144064-rSBK1Um8', 2, 'bank_transfer', 'full', NULL, NULL, NULL, NULL, NULL, 57000000.00, 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, '2026-01-27 17:30:56', '2026-01-27 17:30:56'),
(9, 13, 'MANUAL-TEST-1769561740-xqFjKjip', 1, 'bank_transfer', 'full', NULL, NULL, NULL, NULL, NULL, 4500000.00, 'success', 'verified', 1, 'Test approval', NULL, NULL, '2026-01-27 17:55:40', '2026-01-27 17:55:40', '2026-01-27 17:55:40'),
(10, 12, 'MANUAL-WO-6952144064-BgiEX9lZ', 2, 'bank_transfer', 'full', NULL, NULL, NULL, NULL, NULL, 57000000.00, 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, '2026-01-27 19:03:13', '2026-01-27 19:03:13'),
(11, 12, 'MANUAL-WO-6952144064-ioRMf7Pe', 1, 'bank_transfer', 'full', NULL, NULL, NULL, NULL, NULL, 57000000.00, 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, '2026-01-27 19:06:26', '2026-01-27 19:06:26'),
(12, 22, 'MANUAL-WO-8084200719-vBj4MjLN', 1, 'bank_transfer', 'dp', NULL, '2026-06-08', 'payment-proofs/nCCXpGuM2L3L6F6LYIiR4avMw2icscfBopxElEy7.png', NULL, NULL, 17100000.00, 'success', 'verified', 1, NULL, 'Bukti transfer diunggah pelanggan pada 07 Jun 2026 21:24', NULL, '2026-06-07 14:27:54', '2026-06-07 14:21:04', '2026-06-07 14:27:54'),
(25, 35, 'PAY-DP-6A3476D958497-353', NULL, 'bank_transfer', 'dp', NULL, NULL, NULL, NULL, NULL, 55000000.00, 'success', 'verified', NULL, NULL, NULL, NULL, '2026-04-30 17:00:00', '2026-06-18 22:53:13', '2026-06-18 22:53:13'),
(26, 36, 'PAY-DP-6A3476D9ADC13-449', NULL, 'bank_transfer', 'dp', NULL, NULL, NULL, NULL, NULL, 3000000.00, 'success', 'verified', NULL, NULL, NULL, NULL, '2026-05-02 17:00:00', '2026-06-18 22:53:13', '2026-06-18 22:53:13'),
(27, 37, 'PAY-DP-6A3476DA0B725-399', NULL, 'bank_transfer', 'dp', NULL, NULL, NULL, NULL, NULL, 2000000.00, 'success', 'verified', NULL, NULL, NULL, NULL, '2026-05-09 17:00:00', '2026-06-18 22:53:14', '2026-06-18 22:53:14'),
(28, 38, 'PAY-DP-6A3476DA6F354-795', NULL, 'bank_transfer', 'dp', NULL, NULL, NULL, NULL, NULL, 2250000.00, 'success', 'verified', NULL, NULL, NULL, NULL, '2026-05-09 17:00:00', '2026-06-18 22:53:14', '2026-06-18 22:53:14'),
(29, 39, 'PAY-DP-6A3476DAC6310-642', NULL, 'bank_transfer', 'dp', NULL, NULL, NULL, NULL, NULL, 5100000.00, 'success', 'verified', NULL, NULL, NULL, NULL, '2026-05-12 17:00:00', '2026-06-18 22:53:14', '2026-06-18 22:53:14'),
(30, 40, 'PAY-INST-6A3476DB32AE1-986', NULL, 'bank_transfer', 'installment', 1, NULL, NULL, NULL, NULL, 5100000.00, 'success', 'verified', NULL, NULL, NULL, NULL, '2026-04-13 17:00:00', '2026-06-18 22:53:15', '2026-06-18 22:53:15'),
(31, 40, 'PAY-INST-6A3476DB33455-550', NULL, 'bank_transfer', 'installment', 2, NULL, NULL, NULL, NULL, 5000000.00, 'success', 'verified', NULL, NULL, NULL, NULL, '2026-04-13 17:00:00', '2026-06-18 22:53:15', '2026-06-18 22:53:15'),
(32, 40, 'PAY-INST-6A3476DB33FD3-790', NULL, 'bank_transfer', 'installment', 3, NULL, NULL, NULL, NULL, 25000000.00, 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, '2026-06-18 22:53:15', '2026-06-18 22:53:15'),
(33, 41, 'PAY-DP-6A3476DB93A3D-878', NULL, 'bank_transfer', 'dp', NULL, NULL, NULL, NULL, NULL, 2250000.00, 'success', 'verified', NULL, NULL, NULL, NULL, '2026-05-13 17:00:00', '2026-06-18 22:53:15', '2026-06-18 22:53:15'),
(34, 42, 'PAY-DP-6A3476DC1712C-682', NULL, 'bank_transfer', 'dp', NULL, NULL, NULL, NULL, NULL, 1500000.00, 'success', 'verified', NULL, NULL, NULL, NULL, '2026-05-19 17:00:00', '2026-06-18 22:53:16', '2026-06-18 22:53:16'),
(35, 43, 'PAY-DP-6A3476DC7E4E8-879', NULL, 'bank_transfer', 'dp', NULL, NULL, NULL, NULL, NULL, 3000000.00, 'success', 'verified', NULL, NULL, NULL, NULL, '2026-05-20 17:00:00', '2026-06-18 22:53:16', '2026-06-18 22:53:16'),
(36, 44, 'PAY-DP-6A3476DCD994B-232', NULL, 'bank_transfer', 'dp', NULL, NULL, NULL, NULL, NULL, 2850000.00, 'success', 'verified', NULL, NULL, NULL, NULL, '2026-05-26 17:00:00', '2026-06-18 22:53:16', '2026-06-18 22:53:16');

-- --------------------------------------------------------

--
-- Struktur dari tabel `payment_schemes`
--

CREATE TABLE `payment_schemes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `breakdown` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`breakdown`)),
  `min_days_before_event` int(11) NOT NULL DEFAULT 14,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `payment_schemes`
--

INSERT INTO `payment_schemes` (`id`, `name`, `code`, `breakdown`, `min_days_before_event`, `is_active`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Bayar Lunas', 'full_payment', '[{\"percentage\":100,\"label\":\"Lunas Penuh\",\"days_before_event\":null}]', 0, 1, 'Bayar 100% sekaligus saat checkout.', '2026-06-07 14:04:28', '2026-06-07 14:04:28'),
(2, 'DP 30% + Pelunasan', 'dp_30', '[{\"percentage\":30,\"label\":\"Uang Muka (DP)\",\"days_before_event\":null},{\"percentage\":70,\"label\":\"Pelunasan\",\"days_before_event\":14}]', 30, 1, 'DP 30% di awal, pelunasan sebelum H-14 acara.', '2026-06-07 14:04:28', '2026-06-07 14:04:28'),
(3, 'DP 50% + Pelunasan', 'dp_50', '[{\"percentage\":50,\"label\":\"Uang Muka (DP)\",\"days_before_event\":null},{\"percentage\":50,\"label\":\"Pelunasan\",\"days_before_event\":14}]', 30, 1, 'DP 50% di awal, pelunasan sebelum H-14 acara.', '2026-06-07 14:04:28', '2026-06-07 14:04:28'),
(4, 'Cicilan 3x', 'installment_3x', '[{\"percentage\":40,\"label\":\"Cicilan ke-1\",\"days_before_event\":null},{\"percentage\":30,\"label\":\"Cicilan ke-2\",\"days_before_event\":30},{\"percentage\":30,\"label\":\"Cicilan ke-3\",\"days_before_event\":14}]', 60, 1, 'Pembayaran bertahap 40% + 30% + 30%.', '2026-06-07 14:04:28', '2026-06-07 14:04:28'),
(5, 'DP 20% + Pelunasan', 'dp_20', '[{\"percentage\":20,\"label\":\"Uang Muka (DP)\",\"days_before_event\":null},{\"percentage\":80,\"label\":\"Pelunasan\",\"days_before_event\":14}]', 30, 1, 'DP 20% di awal, pelunasan sebelum H-14 acara.', '2026-06-18 22:34:10', '2026-06-18 22:34:10'),
(6, 'DP 40% + Pelunasan', 'dp_40', '[{\"percentage\":40,\"label\":\"Uang Muka (DP)\",\"days_before_event\":null},{\"percentage\":60,\"label\":\"Pelunasan\",\"days_before_event\":14}]', 30, 1, 'DP 40% di awal, pelunasan sebelum H-14 acara.', '2026-06-18 22:34:10', '2026-06-18 22:34:10'),
(7, 'Cicilan 5x', 'installment_5x', '[{\"percentage\":30,\"label\":\"Cicilan ke-1\",\"days_before_event\":null},{\"percentage\":20,\"label\":\"Cicilan ke-2\",\"days_before_event\":60},{\"percentage\":20,\"label\":\"Cicilan ke-3\",\"days_before_event\":45},{\"percentage\":15,\"label\":\"Cicilan ke-4\",\"days_before_event\":30},{\"percentage\":15,\"label\":\"Cicilan ke-5\",\"days_before_event\":14}]', 90, 1, 'Pembayaran bertahap 30% + 20% + 20% + 15% + 15%.', '2026-06-18 22:34:10', '2026-06-18 22:34:10');

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
  `id` varchar(255) NOT NULL,
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
('hAX2POWcIsA5W16la6z0G2eBZj30hw8fbm6NmVW2', 1, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoieHFNY0pSVWJ2S2JydkRJdGxDZzRaVko4aWo4bUVzZlRhMkJIRkxGRyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9hZG1pbi9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6MTU6ImFkbWluLmRhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1782625921),
('xqdInlc7nSP6iNn46N0eH0pIsyo0vmLcGEBqem9N', 20, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMG5FQVZkenFtWFphZ25qVVpsdkFTOG9VQ2RCYkt3Q2hlNjBzeVROdCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jdXN0b21lci9vcmRlcnMvMzUiO3M6NToicm91dGUiO3M6MjA6ImN1c3RvbWVyLm9yZGVycy5zaG93Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjA7fQ==', 1781828743);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sms_logs`
--

CREATE TABLE `sms_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `phone_number` varchar(255) NOT NULL,
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
  `email` varchar(255) NOT NULL,
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
(9, 'Alc', 'alc@gemilangwo.test', NULL, NULL, '$2y$12$/hSnchAyh0KPdTo4XiuVNO3yyhiopWzUTSvQjo9T38RAlp3NcvXH6', 'customer', NULL, '2026-01-27 05:12:27', '2026-01-27 05:12:27', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1),
(20, 'Erlyn', 'erlyn.june2026@gemilangwo.test', '081234560001', '2026-06-18 22:53:13', '$2y$12$uveKezptc4hBDmLsJaj6HeGZSZFThP1kqUYv/l.1FYd2ekPeFnKHm', 'customer', NULL, '2026-06-18 22:53:13', '2026-06-18 22:53:13', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1),
(21, 'Kak Nadin', 'nadin.june2026@gemilangwo.test', '081234560002', '2026-06-18 22:53:13', '$2y$12$oaAYbOwAddnppfLT4whLKOftelEoSuRQIcTibKtbsS3vxW.zsgIiu', 'customer', NULL, '2026-06-18 22:53:13', '2026-06-18 22:53:13', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1),
(22, 'Kak Nadya', 'nadya.june2026@gemilangwo.test', '081234560003', '2026-06-18 22:53:14', '$2y$12$7.pd3ZmA3otp2w9jDFy.2.5jbeB12j97DLw5a7G6BuKriuq3AdPay', 'customer', NULL, '2026-06-18 22:53:14', '2026-06-18 22:53:14', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1),
(23, 'Kak Hayyun', 'hayyun.june2026@gemilangwo.test', '081234560005', '2026-06-18 22:53:14', '$2y$12$B5yVIkgv/73ZKebXtczu0ujZOVFEZZ5D1.FXpUF9BbDoGHkMQjHGS', 'customer', NULL, '2026-06-18 22:53:14', '2026-06-18 22:53:14', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1),
(24, 'Kak Futikha', 'futikha.june2026@gemilangwo.test', '081234560006', '2026-06-18 22:53:15', '$2y$12$JMV8G1ZTKZML.OQ78goOyeL0.2r1CccEWvx//Iar/APfq84CGzcSm', 'customer', NULL, '2026-06-18 22:53:15', '2026-06-18 22:53:15', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1),
(25, 'Kak Navi', 'navi.june2026@gemilangwo.test', '081234560007', '2026-06-18 22:53:15', '$2y$12$jLMJ8UzNr1Gpn.dPxijlCegLnbEN7bfSCcWVKB0cTKRBYWz0LX.B6', 'customer', NULL, '2026-06-18 22:53:15', '2026-06-18 22:53:15', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1),
(26, 'Kak Arina', 'arina.june2026@gemilangwo.test', '081234560008', '2026-06-18 22:53:16', '$2y$12$FhHkxTk.t6NoA17gEHHZOe2iSu9JHEw8X5/oTMFxRQA.fbUx9oeru', 'customer', NULL, '2026-06-18 22:53:16', '2026-06-18 22:53:16', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1),
(27, 'Kak Risma', 'risma.june2026@gemilangwo.test', '081234560009', '2026-06-18 22:53:16', '$2y$12$P8UFLPOxlUH.SrE68eOuuOw9SNRtBriihg/9lSnTZyLLCQUtdnnXy', 'customer', NULL, '2026-06-18 22:53:16', '2026-06-18 22:53:16', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `vendors`
--

CREATE TABLE `vendors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vendor_category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(255) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `vendors`
--

INSERT INTO `vendors` (`id`, `vendor_category_id`, `name`, `description`, `price`, `image`, `contact_phone`, `contact_email`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Catering Prima', 'Layanan Catering Prima', 25000000.00, NULL, NULL, NULL, 1, 1, '2026-02-08 14:28:10', '2026-04-20 00:27:40'),
(2, 1, 'Catering Royal', 'Layanan Catering Royal', 35000000.00, NULL, NULL, NULL, 2, 1, '2026-02-08 14:28:10', '2026-04-20 00:27:40'),
(3, 1, 'Catering Nusantara', 'Layanan Catering Nusantara', 20000000.00, NULL, NULL, NULL, 3, 1, '2026-02-08 14:28:10', '2026-04-20 00:27:40'),
(4, 2, 'Dekorasi Mewah', 'Layanan Dekorasi Mewah', 15000000.00, NULL, NULL, NULL, 1, 1, '2026-02-08 14:28:10', '2026-04-20 00:27:40'),
(5, 2, 'Dekorasi Elegan', 'Layanan Dekorasi Elegan', 12000000.00, NULL, NULL, NULL, 2, 1, '2026-02-08 14:28:10', '2026-04-20 00:27:40'),
(6, 2, 'Dekorasi Minimalis', 'Layanan Dekorasi Minimalis', 10000000.00, NULL, NULL, NULL, 3, 1, '2026-02-08 14:28:10', '2026-04-20 00:27:40'),
(7, 3, 'Studio Foto Pro', 'Layanan Studio Foto Pro', 12000000.00, NULL, NULL, NULL, 1, 1, '2026-02-08 14:28:10', '2026-04-20 00:27:40'),
(8, 3, 'Cinematic Video', 'Layanan Cinematic Video', 15000000.00, NULL, NULL, NULL, 2, 1, '2026-02-08 14:28:10', '2026-04-20 00:27:40'),
(9, 3, 'Foto & Video Paket', 'Layanan Foto & Video Paket', 20000000.00, NULL, NULL, NULL, 3, 1, '2026-02-08 14:28:10', '2026-04-20 00:27:40'),
(10, 4, 'DJ Entertainment', 'Layanan DJ Entertainment', 5000000.00, NULL, NULL, NULL, 1, 1, '2026-02-08 14:28:10', '2026-04-20 00:27:40'),
(11, 4, 'Live Band', 'Layanan Live Band', 15000000.00, NULL, NULL, NULL, 2, 1, '2026-02-08 14:28:10', '2026-04-20 00:27:40'),
(12, 4, 'Orchestra', 'Layanan Orchestra', 25000000.00, NULL, NULL, NULL, 3, 1, '2026-02-08 14:28:10', '2026-04-20 00:27:40'),
(13, 5, 'MC Profesional', 'Layanan MC Profesional', 5000000.00, NULL, NULL, NULL, 1, 1, '2026-02-08 14:28:10', '2026-04-20 00:27:40'),
(14, 5, 'MC Berpengalaman', 'Layanan MC Berpengalaman', 8000000.00, NULL, NULL, NULL, 2, 1, '2026-02-08 14:28:10', '2026-04-20 00:27:40'),
(15, 6, 'Beauty Studio', 'Layanan Beauty Studio', 3000000.00, NULL, NULL, NULL, 1, 1, '2026-02-08 14:28:10', '2026-04-20 00:27:40'),
(16, 6, 'Make Up Artist Premium', 'Layanan Make Up Artist Premium', 5000000.00, NULL, NULL, NULL, 2, 1, '2026-02-08 14:28:10', '2026-04-20 00:27:40'),
(17, 7, 'Rental Mobil Mewah', 'Layanan Rental Mobil Mewah', 8000000.00, NULL, NULL, NULL, 1, 1, '2026-02-08 14:28:10', '2026-04-20 00:27:40'),
(18, 7, 'Transport Premium', 'Layanan Transport Premium', 12000000.00, NULL, NULL, NULL, 2, 1, '2026-02-08 14:28:10', '2026-04-20 00:27:40');

-- --------------------------------------------------------

--
-- Struktur dari tabel `vendor_categories`
--

CREATE TABLE `vendor_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `vendor_categories`
--

INSERT INTO `vendor_categories` (`id`, `name`, `slug`, `description`, `icon`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Catering', 'catering', 'Layanan catering dan makanan', 'fa-utensils', 1, 1, '2026-02-08 14:28:10', '2026-02-08 14:28:10'),
(2, 'Dekorasi', 'dekorasi', 'Dekorasi dan penataan venue', 'fa-palette', 2, 1, '2026-02-08 14:28:10', '2026-02-08 14:28:10'),
(3, 'Fotografi & Videografi', 'fotografi-videografi', 'Dokumentasi foto dan video', 'fa-camera', 3, 1, '2026-02-08 14:28:10', '2026-02-08 14:28:10'),
(4, 'Musik & DJ', 'musik-dj', 'Entertainment musik dan DJ', 'fa-music', 4, 1, '2026-02-08 14:28:10', '2026-02-08 14:28:10'),
(5, 'Master of Ceremony', 'master-of-ceremony', 'Pembawa acara profesional', 'fa-microphone', 5, 1, '2026-02-08 14:28:10', '2026-04-20 00:27:40'),
(6, 'Hair & Make Up', 'hair-makeup', 'Tata rias dan styling', 'fa-spa', 6, 1, '2026-02-08 14:28:10', '2026-02-08 14:28:10'),
(7, 'Transportasi', 'transportasi', 'Transport pengantin dan tamu', 'fa-car', 7, 1, '2026-02-08 14:28:10', '2026-02-08 14:28:10');

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
-- Indeks untuk tabel `order_vendors`
--
ALTER TABLE `order_vendors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_vendors_order_id_vendor_category_id_unique` (`order_id`,`vendor_category_id`),
  ADD KEY `order_vendors_vendor_id_foreign` (`vendor_id`),
  ADD KEY `order_vendors_vendor_category_id_foreign` (`vendor_category_id`);

--
-- Indeks untuk tabel `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `packages_owner_id_foreign` (`owner_id`);

--
-- Indeks untuk tabel `package_vendor_category`
--
ALTER TABLE `package_vendor_category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `package_vendor_category_package_id_vendor_category_id_unique` (`package_id`,`vendor_category_id`),
  ADD KEY `package_vendor_category_vendor_category_id_foreign` (`vendor_category_id`),
  ADD KEY `package_vendor_category_default_vendor_id_foreign` (`default_vendor_id`);

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
-- Indeks untuk tabel `payment_schemes`
--
ALTER TABLE `payment_schemes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_schemes_code_unique` (`code`);

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
-- Indeks untuk tabel `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendors_vendor_category_id_foreign` (`vendor_category_id`);

--
-- Indeks untuk tabel `vendor_categories`
--
ALTER TABLE `vendor_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vendor_categories_slug_unique` (`slug`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT untuk tabel `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT untuk tabel `order_vendors`
--
ALTER TABLE `order_vendors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT untuk tabel `packages`
--
ALTER TABLE `packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `package_vendor_category`
--
ALTER TABLE `package_vendor_category`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT untuk tabel `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT untuk tabel `payment_schemes`
--
ALTER TABLE `payment_schemes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT untuk tabel `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `vendor_categories`
--
ALTER TABLE `vendor_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
-- Ketidakleluasaan untuk tabel `order_vendors`
--
ALTER TABLE `order_vendors`
  ADD CONSTRAINT `order_vendors_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_vendors_vendor_category_id_foreign` FOREIGN KEY (`vendor_category_id`) REFERENCES `vendor_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_vendors_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `packages`
--
ALTER TABLE `packages`
  ADD CONSTRAINT `packages_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `package_vendor_category`
--
ALTER TABLE `package_vendor_category`
  ADD CONSTRAINT `package_vendor_category_default_vendor_id_foreign` FOREIGN KEY (`default_vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `package_vendor_category_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `package_vendor_category_vendor_category_id_foreign` FOREIGN KEY (`vendor_category_id`) REFERENCES `vendor_categories` (`id`) ON DELETE CASCADE;

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
-- Ketidakleluasaan untuk tabel `vendors`
--
ALTER TABLE `vendors`
  ADD CONSTRAINT `vendors_vendor_category_id_foreign` FOREIGN KEY (`vendor_category_id`) REFERENCES `vendor_categories` (`id`) ON DELETE CASCADE;

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
