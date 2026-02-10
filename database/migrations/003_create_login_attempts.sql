-- ============================================
-- Login Attempts table for rate limiting
-- ============================================

USE `srisai_db`;

CREATE TABLE IF NOT EXISTS `login_attempts` (
    `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `email`       VARCHAR(150) NOT NULL,
    `ip_address`  VARCHAR(45) NOT NULL,
    `attempted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX `idx_email_time` (`email`, `attempted_at`),
    INDEX `idx_ip_time` (`ip_address`, `attempted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
