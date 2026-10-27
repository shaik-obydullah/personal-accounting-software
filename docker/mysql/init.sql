-- Database schema for Personal Accounting Software
-- Based on the existing SQL structure, adapted for Symfony

CREATE TABLE IF NOT EXISTS `admins` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `user_name` varchar(50) DEFAULT NULL,
  `sex` enum('male','female') DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `roles` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

INSERT INTO `admins` (`id`, `first_name`, `last_name`, `user_name`, `sex`, `email`, `password`, `image`, `mobile`, `address`, `status`, `roles`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`) VALUES
(1, 'Super', 'Admin', 'admin', 'male', 'admin@accounting.com', '$2y$10$o6fLGoJqo9EYFIMrKAU6/eIT9d8FSOYT4nOtJG7HYr/XJAAsinxlC', NULL, NULL, NULL, 'active', '[\"ROLE_SUPER_ADMIN\"]', '2024-01-01 00:00:00', 1, NULL, NULL, NULL);

CREATE TABLE IF NOT EXISTS `wallets` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `category` enum('income','expense') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wallets` (`id`, `name`, `category`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`) VALUES
(1, 'Snacks', 'expense', '2025-04-05 02:02:10', 1, '2025-04-29 01:35:18', 1, NULL),
(2, 'Bazar', 'expense', '2025-04-05 02:02:19', 1, '2025-04-29 01:35:25', 1, NULL),
(3, 'Mobile Recharge', 'expense', '2025-04-05 02:02:44', 1, '2025-04-05 02:02:44', NULL, NULL),
(4, 'Petrol', 'expense', '2025-04-05 02:02:58', 1, '2025-04-05 02:02:58', NULL, NULL),
(5, 'House Rent', 'expense', '2025-04-05 02:03:07', 1, '2025-04-05 02:03:07', NULL, NULL),
(6, 'Salary', 'income', '2025-04-05 02:03:15', 1, '2025-04-05 02:03:15', NULL, NULL),
(7, 'Freelance', 'income', '2025-04-05 02:03:25', 1, '2025-04-05 02:03:25', NULL, NULL);

CREATE TABLE IF NOT EXISTS `incomes` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fk_wallet_id` smallint(6) DEFAULT NULL,
  `description` text NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(30) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_incomes_created_by` (`created_by`),
  KEY `idx_incomes_deleted_at` (`deleted_at`),
  KEY `idx_incomes_created_at` (`created_at`),
  KEY `idx_incomes_wallet` (`fk_wallet_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

CREATE TABLE IF NOT EXISTS `expenses` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fk_wallet_id` smallint(6) DEFAULT NULL,
  `description` text NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(30) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_expenses_created_by` (`created_by`),
  KEY `idx_expenses_deleted_at` (`deleted_at`),
  KEY `idx_expenses_created_at` (`created_at`),
  KEY `idx_expenses_description` (`description`(255)),
  KEY `idx_expenses_wallet` (`fk_wallet_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

CREATE TABLE IF NOT EXISTS `cashbook` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `in_amount` decimal(10,2) DEFAULT NULL,
  `out_amount` decimal(10,2) DEFAULT NULL,
  `fk_reference_id` bigint(20) NOT NULL,
  `reference_type` varchar(20) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

CREATE TABLE IF NOT EXISTS `configurations` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `setting` mediumtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

INSERT INTO `configurations` (`id`, `name`, `setting`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`) VALUES
(1, 'app_name', 'Personal Accounting', NULL, NULL, NULL, NULL, NULL),
(2, 'default_currency', 'USD', NULL, NULL, NULL, NULL, NULL),
(3, 'currencies', '[{\"code\":\"USD\",\"name\":\"US Dollar\",\"symbol\":\"$\"},{\"code\":\"GBP\",\"name\":\"British Pound\",\"symbol\":\"\\u00a3\"},{\"code\":\"BDT\",\"name\":\"Bangladeshi Taka\",\"symbol\":\"\\u09f3\"}]', NULL, NULL, NULL, NULL, NULL),
(4, 'time_format', 'g:i A', NULL, NULL, NULL, NULL, NULL),
(5, 'date_format', 'F j, Y', NULL, NULL, NULL, NULL, NULL),
(6, 'time_zone', 'UTC', NULL, NULL, NULL, NULL, NULL),
(7, 'paginate_rows', '20', NULL, NULL, NULL, NULL, NULL);

CREATE TABLE IF NOT EXISTS `activities` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fk_admin_id` bigint(20) DEFAULT NULL,
  `type` enum('success','warning','error') NOT NULL,
  `name` varchar(150) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `visitor_country` varchar(50) DEFAULT NULL,
  `visitor_state` varchar(100) DEFAULT NULL,
  `visitor_city` varchar(100) DEFAULT NULL,
  `visitor_address` varchar(150) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
