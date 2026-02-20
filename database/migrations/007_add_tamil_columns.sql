-- ============================================================
-- Migration 007: Add Tamil (_ta) language columns
-- Run this in phpMyAdmin or MySQL CLI
-- ============================================================

-- Blogs
ALTER TABLE `blogs`
  ADD COLUMN `title_ta`   VARCHAR(500) NULL DEFAULT NULL AFTER `title`,
  ADD COLUMN `excerpt_ta` TEXT         NULL DEFAULT NULL AFTER `excerpt`,
  ADD COLUMN `content_ta` LONGTEXT     NULL DEFAULT NULL AFTER `content`;

-- Gallery Albums
ALTER TABLE `gallery_albums`
  ADD COLUMN `title_ta`       VARCHAR(255) NULL DEFAULT NULL AFTER `title`,
  ADD COLUMN `description_ta` TEXT         NULL DEFAULT NULL AFTER `description`;

-- Events
ALTER TABLE `events`
  ADD COLUMN `title_ta`       VARCHAR(500) NULL DEFAULT NULL AFTER `title`,
  ADD COLUMN `description_ta` TEXT         NULL DEFAULT NULL AFTER `description`;

-- Magazines
ALTER TABLE `magazines`
  ADD COLUMN `title_ta`   VARCHAR(255) NULL DEFAULT NULL AFTER `title`,
  ADD COLUMN `excerpt_ta` TEXT         NULL DEFAULT NULL AFTER `excerpt`,
  ADD COLUMN `content_ta` LONGTEXT     NULL DEFAULT NULL AFTER `content`;

-- Pages
ALTER TABLE `pages`
  ADD COLUMN `title_ta`   VARCHAR(255) NULL DEFAULT NULL AFTER `title`,
  ADD COLUMN `content_ta` LONGTEXT     NULL DEFAULT NULL AFTER `content`;

-- Temple Timings
ALTER TABLE `temple_timings`
  ADD COLUMN `title_ta` VARCHAR(255) NULL DEFAULT NULL AFTER `title`;

-- Products
ALTER TABLE `products`
  ADD COLUMN `name_ta`        VARCHAR(255) NULL DEFAULT NULL AFTER `name`,
  ADD COLUMN `description_ta` TEXT         NULL DEFAULT NULL AFTER `description`;

-- Tours
ALTER TABLE `tours`
  ADD COLUMN `title_ta`       VARCHAR(255) NULL DEFAULT NULL AFTER `title`,
  ADD COLUMN `description_ta` TEXT         NULL DEFAULT NULL AFTER `description`,
  ADD COLUMN `destination_ta` VARCHAR(255) NULL DEFAULT NULL AFTER `destination`;
