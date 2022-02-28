SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `admins` (
  `admin_type` tinyint(1) NOT NULL DEFAULT 0,
  `username` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(72) COLLATE utf8mb4_unicode_ci NOT NULL,
  `admin_photo` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `sent_by` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sent_to` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `author` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` varchar(5000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `picture` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `admins`
  ADD PRIMARY KEY (`username`);

ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`);

ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;