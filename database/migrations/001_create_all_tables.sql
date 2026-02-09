-- ============================================
-- Sri Sai Mission — Complete Database Schema
-- Run this file in phpMyAdmin or via CLI
-- ============================================

CREATE DATABASE IF NOT EXISTS `srisai_db`
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_unicode_ci;

USE `srisai_db`;

-- ============================================
-- 1. ROLES
-- ============================================
CREATE TABLE IF NOT EXISTS `roles` (
    `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`        VARCHAR(50) NOT NULL UNIQUE,
    `slug`        VARCHAR(50) NOT NULL UNIQUE,
    `permissions` JSON NOT NULL,
    `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 2. ADMIN USERS
-- ============================================
CREATE TABLE IF NOT EXISTS `admin_users` (
    `id`              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `role_id`         INT UNSIGNED NOT NULL,
    `name`            VARCHAR(100) NOT NULL,
    `email`           VARCHAR(150) NOT NULL UNIQUE,
    `password_hash`   VARCHAR(255) NOT NULL,
    `phone`           VARCHAR(20) DEFAULT NULL,
    `avatar`          VARCHAR(255) DEFAULT NULL,
    `is_active`       TINYINT(1) DEFAULT 1,
    `last_login_at`   TIMESTAMP NULL DEFAULT NULL,
    `last_login_ip`   VARCHAR(45) DEFAULT NULL,
    `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX `idx_email` (`email`),
    INDEX `idx_role` (`role_id`),
    CONSTRAINT `fk_admin_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 3. CATEGORIES
-- ============================================
CREATE TABLE IF NOT EXISTS `categories` (
    `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`        VARCHAR(100) NOT NULL,
    `slug`        VARCHAR(120) NOT NULL UNIQUE,
    `type`        ENUM('blog', 'magazine', 'gallery', 'event') NOT NULL,
    `description` TEXT DEFAULT NULL,
    `parent_id`   INT UNSIGNED DEFAULT NULL,
    `sort_order`  INT DEFAULT 0,
    `is_active`   TINYINT(1) DEFAULT 1,
    `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX `idx_type` (`type`),
    INDEX `idx_slug` (`slug`),
    INDEX `idx_parent` (`parent_id`),
    CONSTRAINT `fk_categories_parent` FOREIGN KEY (`parent_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 4. BLOGS
-- ============================================
CREATE TABLE IF NOT EXISTS `blogs` (
    `id`              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `category_id`     INT UNSIGNED DEFAULT NULL,
    `created_by`      INT UNSIGNED NOT NULL,
    `title`           VARCHAR(255) NOT NULL,
    `slug`            VARCHAR(280) NOT NULL UNIQUE,
    `excerpt`         TEXT DEFAULT NULL,
    `content`         LONGTEXT NOT NULL,
    `featured_image`  VARCHAR(255) DEFAULT NULL,
    `status`          ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    `is_featured`     TINYINT(1) DEFAULT 0,
    `view_count`      INT UNSIGNED DEFAULT 0,
    `published_at`    TIMESTAMP NULL DEFAULT NULL,
    `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX `idx_status` (`status`),
    INDEX `idx_slug` (`slug`),
    INDEX `idx_published` (`published_at`),
    INDEX `idx_featured` (`is_featured`),
    INDEX `idx_category` (`category_id`),
    FULLTEXT `idx_search` (`title`, `excerpt`, `content`),
    CONSTRAINT `fk_blogs_category` FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_blogs_author` FOREIGN KEY (`created_by`) REFERENCES `admin_users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 5. MAGAZINES
-- ============================================
CREATE TABLE IF NOT EXISTS `magazines` (
    `id`              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `category_id`     INT UNSIGNED DEFAULT NULL,
    `created_by`      INT UNSIGNED NOT NULL,
    `title`           VARCHAR(255) NOT NULL,
    `slug`            VARCHAR(280) NOT NULL UNIQUE,
    `excerpt`         TEXT DEFAULT NULL,
    `content`         LONGTEXT NOT NULL,
    `featured_image`  VARCHAR(255) DEFAULT NULL,
    `issue_number`    VARCHAR(50) DEFAULT NULL,
    `issue_date`      DATE DEFAULT NULL,
    `pdf_file`        VARCHAR(255) DEFAULT NULL,
    `status`          ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    `is_featured`     TINYINT(1) DEFAULT 0,
    `view_count`      INT UNSIGNED DEFAULT 0,
    `published_at`    TIMESTAMP NULL DEFAULT NULL,
    `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX `idx_status` (`status`),
    INDEX `idx_slug` (`slug`),
    INDEX `idx_issue_date` (`issue_date`),
    INDEX `idx_category` (`category_id`),
    FULLTEXT `idx_search` (`title`, `excerpt`, `content`),
    CONSTRAINT `fk_magazines_category` FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_magazines_author` FOREIGN KEY (`created_by`) REFERENCES `admin_users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 6. GALLERY ALBUMS
-- ============================================
CREATE TABLE IF NOT EXISTS `gallery_albums` (
    `id`              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `category_id`     INT UNSIGNED DEFAULT NULL,
    `created_by`      INT UNSIGNED NOT NULL,
    `title`           VARCHAR(255) NOT NULL,
    `slug`            VARCHAR(280) NOT NULL UNIQUE,
    `description`     TEXT DEFAULT NULL,
    `cover_image`     VARCHAR(255) DEFAULT NULL,
    `status`          ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    `sort_order`      INT DEFAULT 0,
    `image_count`     INT UNSIGNED DEFAULT 0,
    `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX `idx_status` (`status`),
    INDEX `idx_slug` (`slug`),
    INDEX `idx_sort` (`sort_order`),
    CONSTRAINT `fk_albums_category` FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_albums_author` FOREIGN KEY (`created_by`) REFERENCES `admin_users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 7. GALLERY IMAGES
-- ============================================
CREATE TABLE IF NOT EXISTS `gallery_images` (
    `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `album_id`    INT UNSIGNED NOT NULL,
    `file_path`   VARCHAR(255) NOT NULL,
    `thumbnail`   VARCHAR(255) DEFAULT NULL,
    `caption`     VARCHAR(500) DEFAULT NULL,
    `alt_text`    VARCHAR(255) DEFAULT NULL,
    `sort_order`  INT DEFAULT 0,
    `file_size`   INT UNSIGNED DEFAULT 0,
    `width`       INT UNSIGNED DEFAULT NULL,
    `height`      INT UNSIGNED DEFAULT NULL,
    `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX `idx_album` (`album_id`),
    INDEX `idx_sort` (`sort_order`),
    CONSTRAINT `fk_images_album` FOREIGN KEY (`album_id`) REFERENCES `gallery_albums`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 8. EVENTS
-- ============================================
CREATE TABLE IF NOT EXISTS `events` (
    `id`              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `created_by`      INT UNSIGNED NOT NULL,
    `title`           VARCHAR(255) NOT NULL,
    `slug`            VARCHAR(280) NOT NULL UNIQUE,
    `description`     LONGTEXT DEFAULT NULL,
    `featured_image`  VARCHAR(255) DEFAULT NULL,
    `event_date`      DATE NOT NULL,
    `event_time`      TIME DEFAULT NULL,
    `end_date`        DATE DEFAULT NULL,
    `end_time`        TIME DEFAULT NULL,
    `location`        VARCHAR(255) DEFAULT NULL,
    `is_recurring`    TINYINT(1) DEFAULT 0,
    `recurrence_rule` VARCHAR(100) DEFAULT NULL,
    `status`          ENUM('upcoming', 'ongoing', 'completed', 'cancelled') DEFAULT 'upcoming',
    `is_featured`     TINYINT(1) DEFAULT 0,
    `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX `idx_event_date` (`event_date`),
    INDEX `idx_status` (`status`),
    INDEX `idx_slug` (`slug`),
    INDEX `idx_featured` (`is_featured`),
    CONSTRAINT `fk_events_author` FOREIGN KEY (`created_by`) REFERENCES `admin_users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 9. PAGES
-- ============================================
CREATE TABLE IF NOT EXISTS `pages` (
    `id`              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `created_by`      INT UNSIGNED NOT NULL,
    `title`           VARCHAR(255) NOT NULL,
    `slug`            VARCHAR(280) NOT NULL UNIQUE,
    `content`         LONGTEXT NOT NULL,
    `featured_image`  VARCHAR(255) DEFAULT NULL,
    `template`        VARCHAR(50) DEFAULT 'default',
    `status`          ENUM('draft', 'published') DEFAULT 'draft',
    `sort_order`      INT DEFAULT 0,
    `show_in_menu`    TINYINT(1) DEFAULT 0,
    `menu_position`   INT DEFAULT 0,
    `parent_id`       INT UNSIGNED DEFAULT NULL,
    `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX `idx_slug` (`slug`),
    INDEX `idx_status` (`status`),
    INDEX `idx_menu` (`show_in_menu`, `menu_position`),
    CONSTRAINT `fk_pages_author` FOREIGN KEY (`created_by`) REFERENCES `admin_users`(`id`),
    CONSTRAINT `fk_pages_parent` FOREIGN KEY (`parent_id`) REFERENCES `pages`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 10. TRUSTEES
-- ============================================
CREATE TABLE IF NOT EXISTS `trustees` (
    `id`              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`            VARCHAR(150) NOT NULL,
    `designation`     VARCHAR(100) NOT NULL,
    `trustee_type`    ENUM('main', 'co-opted') NOT NULL DEFAULT 'co-opted',
    `bio`             TEXT DEFAULT NULL,
    `photo`           VARCHAR(255) DEFAULT NULL,
    `phone`           VARCHAR(20) DEFAULT NULL,
    `email`           VARCHAR(150) DEFAULT NULL,
    `qualification`   VARCHAR(255) DEFAULT NULL,
    `sort_order`      INT DEFAULT 0,
    `is_active`       TINYINT(1) DEFAULT 1,
    `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX `idx_type` (`trustee_type`),
    INDEX `idx_active` (`is_active`),
    INDEX `idx_sort` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 11. DONATIONS
-- ============================================
CREATE TABLE IF NOT EXISTS `donations` (
    `id`              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `donor_name`      VARCHAR(150) NOT NULL,
    `donor_email`     VARCHAR(150) DEFAULT NULL,
    `donor_phone`     VARCHAR(20) DEFAULT NULL,
    `donor_address`   TEXT DEFAULT NULL,
    `donor_pan`       VARCHAR(10) DEFAULT NULL,
    `amount`          DECIMAL(10,2) NOT NULL,
    `currency`        VARCHAR(3) DEFAULT 'INR',
    `purpose`         VARCHAR(255) DEFAULT NULL,
    `payment_method`  ENUM('online', 'cash', 'cheque', 'bank_transfer') DEFAULT 'online',
    `transaction_id`  VARCHAR(100) DEFAULT NULL,
    `receipt_number`  VARCHAR(50) DEFAULT NULL,
    `status`          ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    `notes`           TEXT DEFAULT NULL,
    `donated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX `idx_status` (`status`),
    INDEX `idx_donor_email` (`donor_email`),
    INDEX `idx_donated_at` (`donated_at`),
    INDEX `idx_receipt` (`receipt_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 12. CONTACT MESSAGES
-- ============================================
CREATE TABLE IF NOT EXISTS `contact_messages` (
    `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`        VARCHAR(100) NOT NULL,
    `last_name`   VARCHAR(100) DEFAULT NULL,
    `email`       VARCHAR(150) NOT NULL,
    `phone`       VARCHAR(20) DEFAULT NULL,
    `subject`     VARCHAR(255) DEFAULT NULL,
    `message`     TEXT NOT NULL,
    `source_page` VARCHAR(100) DEFAULT 'contact',
    `ip_address`  VARCHAR(45) DEFAULT NULL,
    `status`      ENUM('unread', 'read', 'replied', 'archived') DEFAULT 'unread',
    `admin_notes` TEXT DEFAULT NULL,
    `replied_at`  TIMESTAMP NULL DEFAULT NULL,
    `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX `idx_status` (`status`),
    INDEX `idx_email` (`email`),
    INDEX `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 13. MEDIA
-- ============================================
CREATE TABLE IF NOT EXISTS `media` (
    `id`              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `uploaded_by`     INT UNSIGNED NOT NULL,
    `file_name`       VARCHAR(255) NOT NULL,
    `file_path`       VARCHAR(255) NOT NULL,
    `thumbnail_path`  VARCHAR(255) DEFAULT NULL,
    `file_type`       VARCHAR(50) NOT NULL,
    `file_size`       INT UNSIGNED NOT NULL,
    `width`           INT UNSIGNED DEFAULT NULL,
    `height`          INT UNSIGNED DEFAULT NULL,
    `alt_text`        VARCHAR(255) DEFAULT NULL,
    `used_in`         VARCHAR(50) DEFAULT NULL,
    `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX `idx_type` (`file_type`),
    INDEX `idx_uploaded_by` (`uploaded_by`),
    INDEX `idx_used_in` (`used_in`),
    CONSTRAINT `fk_media_uploader` FOREIGN KEY (`uploaded_by`) REFERENCES `admin_users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 14. SEO META
-- ============================================
CREATE TABLE IF NOT EXISTS `seo_meta` (
    `id`              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `entity_type`     VARCHAR(50) NOT NULL,
    `entity_id`       INT UNSIGNED NOT NULL,
    `meta_title`      VARCHAR(70) DEFAULT NULL,
    `meta_description` VARCHAR(160) DEFAULT NULL,
    `meta_keywords`   VARCHAR(255) DEFAULT NULL,
    `og_title`        VARCHAR(100) DEFAULT NULL,
    `og_description`  VARCHAR(200) DEFAULT NULL,
    `og_image`        VARCHAR(255) DEFAULT NULL,
    `canonical_url`   VARCHAR(255) DEFAULT NULL,
    `no_index`        TINYINT(1) DEFAULT 0,
    `no_follow`       TINYINT(1) DEFAULT 0,
    `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE INDEX `idx_entity` (`entity_type`, `entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 15. SETTINGS
-- ============================================
CREATE TABLE IF NOT EXISTS `settings` (
    `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `group_name`  VARCHAR(50) NOT NULL DEFAULT 'general',
    `key_name`    VARCHAR(100) NOT NULL UNIQUE,
    `value`       TEXT DEFAULT NULL,
    `type`        ENUM('text', 'textarea', 'number', 'boolean', 'json', 'image') DEFAULT 'text',
    `label`       VARCHAR(150) DEFAULT NULL,
    `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX `idx_group` (`group_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 16. TEMPLE TIMINGS
-- ============================================
CREATE TABLE IF NOT EXISTS `temple_timings` (
    `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title`       VARCHAR(100) NOT NULL,
    `day_type`    ENUM('daily', 'monday', 'tuesday', 'wednesday', 'thursday',
                       'friday', 'saturday', 'sunday', 'special') DEFAULT 'daily',
    `start_time`  TIME NOT NULL,
    `end_time`    TIME NOT NULL,
    `description` VARCHAR(255) DEFAULT NULL,
    `location`    VARCHAR(150) DEFAULT NULL,
    `is_active`   TINYINT(1) DEFAULT 1,
    `sort_order`  INT DEFAULT 0,
    `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 17. ACTIVITY LOG
-- ============================================
CREATE TABLE IF NOT EXISTS `activity_log` (
    `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id`     INT UNSIGNED DEFAULT NULL,
    `action`      VARCHAR(50) NOT NULL,
    `entity_type` VARCHAR(50) DEFAULT NULL,
    `entity_id`   INT UNSIGNED DEFAULT NULL,
    `description` VARCHAR(255) DEFAULT NULL,
    `ip_address`  VARCHAR(45) DEFAULT NULL,
    `user_agent`  VARCHAR(255) DEFAULT NULL,
    `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX `idx_user` (`user_id`),
    INDEX `idx_entity` (`entity_type`, `entity_id`),
    INDEX `idx_created` (`created_at`),
    CONSTRAINT `fk_log_user` FOREIGN KEY (`user_id`) REFERENCES `admin_users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
