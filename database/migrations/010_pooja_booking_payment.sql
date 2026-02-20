-- Migration 010: Add num_persons + Razorpay payment fields to pooja_bookings
ALTER TABLE `pooja_bookings`
    ADD COLUMN `num_persons`          INT            DEFAULT 1      AFTER `notes`,
    ADD COLUMN `razorpay_order_id`    VARCHAR(100)   DEFAULT NULL   AFTER `num_persons`,
    ADD COLUMN `razorpay_payment_id`  VARCHAR(100)   DEFAULT NULL   AFTER `razorpay_order_id`,
    ADD COLUMN `razorpay_signature`   VARCHAR(255)   DEFAULT NULL   AFTER `razorpay_payment_id`,
    ADD COLUMN `payment_status`       ENUM('pending','paid','failed') DEFAULT 'pending' AFTER `razorpay_signature`;
