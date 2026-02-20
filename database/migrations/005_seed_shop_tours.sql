-- Seed: Shop Products
INSERT INTO products (name, slug, category, description, price, stock_qty, is_active, sort_order) VALUES
('Sai Satcharita (Tamil)',      'sai-satcharita-tamil',    'book',       'Sacred life story of Shirdi Sai Baba in Tamil. 600+ pages hardcover edition.',              250.00, 50,  1, 1),
('Sai Satcharita (English)',    'sai-satcharita-english',  'book',       'Sacred life story of Shirdi Sai Baba in English. 600+ pages hardcover edition.',            280.00, 40,  1, 2),
('Sri Sai Dharisanam (Annual)', 'sai-dharisanam-annual',   'book',       'Annual subscription to Sri Sai Dharisanam monthly magazine.',                               120.00, 100, 1, 3),
('Vibhuti Packet (100g)',       'vibhuti-packet-100g',     'pooja_item', 'Sacred ash (Vibhuti) from the temple homa. Blessed and packed in a 100g sachet.',           50.00,  200, 1, 4),
('Haldi-Kumkum Set',            'haldi-kumkum-set',        'pooja_item', 'Traditional haldi and kumkum set for daily pooja. Comes in a decorative box.',              80.00,  150, 1, 5),
('Camphor (Karpoora) Pack',     'camphor-karpoora-pack',   'pooja_item', 'Pure camphor for aarti. 50g pack, highly fragrant.',                                        35.00,  300, 1, 6),
('Sai Baba Photo (8x10)',       'sai-baba-photo-8x10',     'other',      'High-quality laminated photo of Shirdi Sai Baba. 8x10 inch, gold border frame.',           150.00, 60,  1, 7),
('Pooja Thali Set (Brass)',     'pooja-thali-set-brass',   'other',      'Brass pooja thali with 5-piece accessories set. Hand-crafted, temple quality.',            450.00, 25,  1, 8);

-- Seed: Pooja Types
INSERT INTO pooja_types (name, temple, price, duration, description, is_active, sort_order) VALUES
('Abhishekam',           'perungalathur', 500.00,  '45 mins', 'Sacred bathing of the deity with milk, curd, honey, and rosewater. Includes flowers and aarti.', 1, 1),
('Archana (108 names)',  'perungalathur', 200.00,  '30 mins', 'Chanting of 108 names of Sai Baba with flowers offered at each name.',                          1, 2),
('Sahasranama Archana',  'perungalathur', 500.00,  '1 hour',  '1008 names archana with full pooja and prasad.',                                                  1, 3),
('Annadhanam Sponsorship','perungalathur',2100.00, '—',       'Sponsor a full Annadhanam (free meal) for all devotees on an auspicious day.',                    1, 4),
('Abhishekam',           'keerapakkam',  500.00,  '45 mins', 'Sacred bathing of the deity with milk, curd, honey, and rosewater. Includes flowers and aarti.', 1, 5),
('Archana (108 names)',  'keerapakkam',  200.00,  '30 mins', 'Chanting of 108 names of Sai Baba with flowers offered at each name.',                          1, 6),
('Vishesh Pooja',        'keerapakkam',  1100.00, '1.5 hrs', 'Special full pooja with abhishekam, archana, homam, and prasad for the family.',                  1, 7),
('Homam (Havan)',        'both',         3100.00, '2 hours', 'Sacred fire ceremony (homam/havan) performed at the temple by experienced priests.',              1, 8);

-- Seed: Tours
INSERT INTO tours (title, destination, description, start_date, end_date, price_per_person, max_seats, booked_seats, status, is_active) VALUES
('Shirdi Sai Baba Pilgrimage 2026',
 'Shirdi, Maharashtra',
 'A blessed 5-day pilgrimage to the sacred town of Shirdi, the abode of Shirdi Sai Baba. Visit the famous Samadhi Mandir, Dwarkamai mosque, Chavadi, and Gurusthan. Includes morning abhishekam darshan and aarti participation.\n\nIncludes:\n- AC bus from Chennai\n- Hotel accommodation (3-star)\n- All meals (vegetarian)\n- Temple entry passes\n- Experienced tour guide',
 '2026-04-10', '2026-04-14', 4500.00, 40, 12, 'upcoming', 1),

('Tirupati–Tirumala Darshan Tour',
 'Tirupati, Andhra Pradesh',
 'A 3-day spiritual tour to the sacred Tirumala hills, home of Lord Venkateswara (Balaji). Includes special darshan arrangements, Kalyanotsavam participation, and visit to Padmavathi temple in Tiruchanur.\n\nIncludes:\n- AC bus from Chennai\n- Accommodation near temple\n- Vegetarian meals\n- Special darshan tickets\n- Guide service',
 '2026-03-15', '2026-03-17', 2800.00, 35, 18, 'upcoming', 1),

('Rameswaram–Madurai Temple Tour',
 'Rameswaram & Madurai, Tamil Nadu',
 'A sacred 4-day tour covering the famous Ramnathaswamy Temple in Rameswaram (one of the 12 Jyotirlingas) and the Meenakshi Amman Temple in Madurai.\n\nIncludes:\n- AC bus from Chennai\n- Hotel accommodation\n- All meals\n- Temple rituals participation\n- Guide service',
 '2026-05-01', '2026-05-04', 3200.00, 45, 5, 'upcoming', 1),

('Varanasi–Prayagraj Pilgrimage',
 'Varanasi & Prayagraj, Uttar Pradesh',
 'A 7-day North India pilgrimage to the holiest city of Varanasi (Kashi) and the Triveni Sangam at Prayagraj. Experience the famous Ganga Aarti, Kashi Vishwanath darshan, and a holy dip at Sangam.\n\nIncludes:\n- Flight from Chennai\n- Hotel accommodation\n- All meals\n- Boat ride on Ganga\n- All entry and ritual fees\n- Experienced guide',
 '2026-06-12', '2026-06-18', 12500.00, 30, 8, 'upcoming', 1),

('Arunachalam–Tiruvannamalai Tour',
 'Tiruvannamalai, Tamil Nadu',
 'A 2-day tour to the sacred Arunachaleswarar Temple and Ramana Maharshi Ashram at Tiruvannamalai. Participate in the Girivalam (circumambulation of the sacred hill).\n\nIncludes:\n- AC bus from Chennai\n- Accommodation\n- Meals\n- Guide service',
 '2026-03-02', '2026-03-03', 1500.00, 50, 50, 'completed', 1);
