-- Migration 009: Map-based distance delivery
-- Replaces pincode-based delivery with lat/lng + Haversine distance slabs.
-- Run: mysql -u root srisai_db < database/migrations/009_map_delivery.sql

-- ─── 1. Delivery zones — drop pincodes, use km slabs ───────────────────────
ALTER TABLE `delivery_zones` DROP COLUMN `pincodes`;

-- Re-seed with clean distance slabs
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE `delivery_zones`;
SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO `delivery_zones` (`name`, `min_km`, `max_km`, `charge`, `is_active`) VALUES
('Free Local (0 – 5 km)',      0.0,   5.0,   0.00, 1),
('Near (5 – 10 km)',           5.0,  10.0,  50.00, 1),
('City (10 – 20 km)',         10.0,  20.0, 100.00, 1),
('Suburb (20 – 40 km)',       20.0,  40.0, 150.00, 1),
('Long Distance (40+ km)',    40.0,   0.0, 250.00, 1);

-- ─── 2. Shop orders — add lat/lng/distance, make pincode optional ───────────
ALTER TABLE `shop_orders`
    MODIFY `pincode`    VARCHAR(10) DEFAULT NULL,
    ADD COLUMN `lat`         DECIMAL(10,8) DEFAULT NULL AFTER `delivery_address`,
    ADD COLUMN `lng`         DECIMAL(11,8) DEFAULT NULL AFTER `lat`,
    ADD COLUMN `distance_km` DECIMAL(8,2)  DEFAULT NULL AFTER `lng`;

-- ─── 3. Settings — add shop dispatch location ────────────────────────────────
INSERT INTO `settings` (`group_name`, `key_name`, `value`, `type`, `label`) VALUES
('shop', 'shop_dispatch_lat',     '12.97350', 'text', 'Dispatch Latitude'),
('shop', 'shop_dispatch_lng',     '80.14840', 'text', 'Dispatch Longitude'),
('shop', 'shop_dispatch_address', 'New Perungalathur, Chennai - 600063', 'text', 'Dispatch Address')
ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);
