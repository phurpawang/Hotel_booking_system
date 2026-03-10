-- Bhutan Hotel Booking System Database
-- Database: BHBS_db

-- Create database if not exists
CREATE DATABASE IF NOT EXISTS BHBS_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE BHBS_db;

-- Users table with role field
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('ADMIN','OWNER','MANAGER','RECEPTION') NOT NULL DEFAULT 'RECEPTION',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Hotels table
CREATE TABLE IF NOT EXISTS `hotels` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `owner_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `description` text,
  `status` enum('PENDING','APPROVED','REJECTED','DEREGISTERED') NOT NULL DEFAULT 'PENDING',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hotels_email_unique` (`email`),
  KEY `hotels_owner_id_foreign` (`owner_id`),
  CONSTRAINT `hotels_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Rooms table
CREATE TABLE IF NOT EXISTS `rooms` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `hotel_id` bigint(20) UNSIGNED NOT NULL,
  `room_number` varchar(255) NOT NULL,
  `room_type` varchar(255) NOT NULL,
  `capacity` int(11) NOT NULL,
  `price_per_night` decimal(10,2) NOT NULL,
  `description` text,
  `status` enum('AVAILABLE','OCCUPIED','MAINTENANCE') NOT NULL DEFAULT 'AVAILABLE',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rooms_hotel_id_foreign` (`hotel_id`),
  CONSTRAINT `rooms_hotel_id_foreign` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bookings table
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `guest_name` varchar(255) NOT NULL,
  `guest_email` varchar(255) NOT NULL,
  `guest_phone` varchar(255) NOT NULL,
  `check_in_date` date NOT NULL,
  `check_out_date` date NOT NULL,
  `num_guests` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('CONFIRMED','CHECKED_IN','CHECKED_OUT','CANCELLED') NOT NULL DEFAULT 'CONFIRMED',
  `special_requests` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bookings_room_id_foreign` (`room_id`),
  KEY `bookings_user_id_foreign` (`user_id`),
  CONSTRAINT `bookings_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Password reset tokens table (Laravel default)
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Failed jobs table (Laravel default)
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Personal access tokens table (Laravel Sanctum)
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data (password is 'password' hashed with bcrypt)
-- Note: You should run the seeder instead for proper password hashing
-- php artisan db:seed --class=RoleBasedUsersSeeder

INSERT INTO `users` (`name`, `email`, `role`, `password`) VALUES
('System Admin', 'admin@bhbs.bt', 'ADMIN', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Hotel Owner', 'owner@bhbs.bt', 'OWNER', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Hotel Manager', 'manager@bhbs.bt', 'MANAGER', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Reception Staff', 'reception@bhbs.bt', 'RECEPTION', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Sample Hotel
INSERT INTO `hotels` (`owner_id`, `name`, `address`, `phone`, `email`, `description`, `status`) VALUES
(2, 'Dragon Palace Hotel', 'Thimphu, Bhutan', '+975-2-123456', 'info@dragonpalace.bt', 'Luxury hotel in the heart of Thimphu', 'APPROVED');

-- Sample Rooms
INSERT INTO `rooms` (`hotel_id`, `room_number`, `room_type`, `capacity`, `price_per_night`, `description`, `status`) VALUES
(1, '101', 'Single', 1, 3000.00, 'Comfortable Single room with modern amenities', 'AVAILABLE'),
(1, '102', 'Single', 1, 3000.00, 'Comfortable Single room with modern amenities', 'AVAILABLE'),
(1, '201', 'Double', 2, 5000.00, 'Comfortable Double room with modern amenities', 'AVAILABLE'),
(1, '202', 'Double', 2, 5000.00, 'Comfortable Double room with modern amenities', 'AVAILABLE'),
(1, '301', 'Suite', 3, 8000.00, 'Comfortable Suite room with modern amenities', 'AVAILABLE'),
(1, '401', 'Deluxe', 4, 12000.00, 'Comfortable Deluxe room with modern amenities', 'AVAILABLE');

-- Sample Bookings
INSERT INTO `bookings` (`room_id`, `guest_name`, `guest_email`, `guest_phone`, `check_in_date`, `check_out_date`, `num_guests`, `total_price`, `status`) VALUES
(1, 'Tashi Dorji', 'tashi@example.bt', '+975-17-123456', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 3 DAY), 2, 15000.00, 'CONFIRMED'),
(2, 'Karma Wangchuk', 'karma@example.bt', '+975-17-234567', DATE_SUB(CURDATE(), INTERVAL 2 DAY), CURDATE(), 2, 10000.00, 'CHECKED_IN'),
(3, 'Pema Tshering', 'pema@example.bt', '+975-17-345678', DATE_ADD(CURDATE(), INTERVAL 5 DAY), DATE_ADD(CURDATE(), INTERVAL 7 DAY), 3, 16000.00, 'CONFIRMED');

-- Migrations table (Laravel tracks migrations)
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

COMMIT;
