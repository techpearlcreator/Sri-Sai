-- Performance indexes for shop, tours, and public user tables

-- Products: filter by category and active status
ALTER TABLE products
    ADD INDEX idx_category  (category),
    ADD INDEX idx_active    (is_active),
    ADD INDEX idx_sort      (sort_order);

-- Pooja Types: filter by temple and active status
ALTER TABLE pooja_types
    ADD INDEX idx_temple    (temple),
    ADD INDEX idx_active    (is_active),
    ADD INDEX idx_sort      (sort_order);

-- Pooja Bookings: filter by status, temple
ALTER TABLE pooja_bookings
    ADD INDEX idx_status        (status),
    ADD INDEX idx_temple        (temple),
    ADD INDEX idx_created       (created_at),
    ADD INDEX idx_pooja_name    (pooja_type_id, status);

-- Shop Enquiries: filter by status
ALTER TABLE shop_enquiries
    ADD INDEX idx_status    (status),
    ADD INDEX idx_created   (created_at);

-- Tours: filter by status and active
ALTER TABLE tours
    ADD INDEX idx_status    (status),
    ADD INDEX idx_active    (is_active),
    ADD INDEX idx_dates     (start_date, end_date);

-- Tour Bookings: filter by payment_status and status
ALTER TABLE tour_bookings
    ADD INDEX idx_payment_status    (payment_status),
    ADD INDEX idx_status            (status),
    ADD INDEX idx_created           (created_at);

-- Public Users: lookup by active status
ALTER TABLE public_users
    ADD INDEX idx_active    (is_active),
    ADD INDEX idx_created   (created_at);

-- Temple Timings: lookup by temple and day
ALTER TABLE temple_timings
    ADD INDEX idx_temple    (temple),
    ADD INDEX idx_day       (day_of_week);
