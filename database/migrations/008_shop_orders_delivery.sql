-- Migration 008: Delivery Zones + Shop Orders
-- Run: mysql -u root srisai_db < database/migrations/008_shop_orders_delivery.sql

-- Delivery Zones: admin configures pincode-based shipping charges
CREATE TABLE IF NOT EXISTS `delivery_zones` (
    `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`       VARCHAR(100) NOT NULL COMMENT 'e.g. Local (0-5 km)',
    `pincodes`   TEXT NOT NULL COMMENT 'Comma-separated pincodes e.g. 600001,600002',
    `min_km`     DECIMAL(8,2) DEFAULT 0 COMMENT 'Display only — min km range',
    `max_km`     DECIMAL(8,2) DEFAULT 0 COMMENT 'Display only — max km range',
    `charge`     DECIMAL(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Delivery charge in INR',
    `is_active`  TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed a few sample zones
INSERT INTO `delivery_zones` (`name`, `pincodes`, `min_km`, `max_km`, `charge`) VALUES
('Local Chennai (0-5 km)',    '600001,600002,600003,600004,600005,600006', 0, 5, 0.00),
('Near (5-10 km)',            '600007,600008,600009,600010,600011,600012,600013,600014,600015,600016,600017,600018,600019,600020', 5, 10, 50.00),
('City (10-20 km)',           '600021,600022,600023,600024,600025,600026,600027,600028,600029,600030,600031,600032,600033,600034,600035,600040,600041,600042,600043,600044,600045', 10, 20, 100.00),
('Suburb (20-40 km)',         '600050,600051,600052,600053,600054,600055,600056,600057,600058,600059,600060,600061,600062,600063,600064,600065,600066,600067,600068,600069,600070,600071,600072,600073,600074,600075,600076,600077,600078,600079,600080,600081,600082,600083,600084,600085,600086,600087,600088,600089,600090,600091,600092,600093,600094,600095,600096,600097,600098,600099,600100', 20, 40, 150.00),
('Outside City (40+ km)',     '600101,600102,600103,600104,600105,600106,600107,600108,600109,600110,600111,600112,600113,600114,600115,600116,600117,600118,600119,600120,600121,600122,600123,600124,600125,600126,600127,600128,600129,600130', 40, 0, 200.00);

-- Shop Orders: stores completed Razorpay + order details
CREATE TABLE IF NOT EXISTS `shop_orders` (
    `id`                   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `product_id`           INT UNSIGNED NOT NULL,
    `customer_name`        VARCHAR(200) NOT NULL,
    `customer_phone`       VARCHAR(20)  NOT NULL,
    `customer_email`       VARCHAR(200) DEFAULT NULL,
    `delivery_address`     TEXT NOT NULL,
    `pincode`              VARCHAR(10)  NOT NULL,
    `zone_id`              INT UNSIGNED DEFAULT NULL,
    `quantity`             INT NOT NULL DEFAULT 1,
    `product_price`        DECIMAL(10,2) NOT NULL,
    `delivery_charge`      DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `total_amount`         DECIMAL(10,2) NOT NULL,
    `payment_status`       ENUM('pending','paid','failed','refunded') DEFAULT 'pending',
    `razorpay_order_id`    VARCHAR(100) DEFAULT NULL,
    `razorpay_payment_id`  VARCHAR(100) DEFAULT NULL,
    `razorpay_signature`   VARCHAR(200) DEFAULT NULL,
    `order_status`         ENUM('pending','confirmed','shipped','delivered','cancelled') DEFAULT 'pending',
    `notes`                TEXT DEFAULT NULL,
    `created_at`           TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE RESTRICT,
    FOREIGN KEY (`zone_id`)    REFERENCES `delivery_zones`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
