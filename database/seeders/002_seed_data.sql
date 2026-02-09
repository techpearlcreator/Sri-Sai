-- ============================================
-- Sri Sai Mission — Seed Data
-- ============================================

USE `srisai_db`;

-- ============================================
-- ROLES
-- ============================================
INSERT INTO `roles` (`id`, `name`, `slug`, `permissions`) VALUES
(1, 'Super Admin', 'super_admin', '{"blogs":["create","read","update","delete"],"magazines":["create","read","update","delete"],"gallery":["create","read","update","delete"],"events":["create","read","update","delete"],"pages":["create","read","update","delete"],"trustees":["create","read","update","delete"],"donations":["create","read","update","delete"],"contacts":["read","update","delete"],"media":["create","read","delete"],"settings":["read","update"],"users":["create","read","update","delete"]}'),
(2, 'Admin', 'admin', '{"blogs":["create","read","update","delete"],"magazines":["create","read","update","delete"],"gallery":["create","read","update","delete"],"events":["create","read","update","delete"],"pages":["create","read","update","delete"],"trustees":["create","read","update","delete"],"donations":["create","read","update","delete"],"contacts":["read","update","delete"],"media":["create","read","delete"],"settings":["read","update"],"users":[]}'),
(3, 'Editor', 'editor', '{"blogs":["create","read","update"],"magazines":["create","read","update"],"gallery":["create","read","update"],"events":["create","read","update"],"pages":["read"],"trustees":["read"],"donations":["read"],"contacts":["read"],"media":["create","read"],"settings":[],"users":[]}'),
(4, 'Viewer', 'viewer', '{"blogs":["read"],"magazines":["read"],"gallery":["read"],"events":["read"],"pages":["read"],"trustees":["read"],"donations":["read"],"contacts":["read"],"media":["read"],"settings":[],"users":[]}');

-- ============================================
-- DEFAULT ADMIN USER
-- Password: SriSai@2026 (bcrypt hash)
-- ============================================
INSERT INTO `admin_users` (`id`, `role_id`, `name`, `email`, `password_hash`, `phone`, `is_active`) VALUES
(1, 1, 'Sri Sai Varadharajan', 'admin@srisaimission.org', '$2y$12$dmKi0IXHHODpPbZxdsekBusdHTUE9BRmTaPGc12zDG/9XeYPMExWm', '9841203311', 1);

-- ============================================
-- TRUSTEES (14 from PDF)
-- ============================================
INSERT INTO `trustees` (`name`, `designation`, `trustee_type`, `qualification`, `sort_order`, `is_active`) VALUES
('G. Varadharajan',          'Managing Trustee',   'main',     'Mr.',  1,  1),
('V. Jayanthi',              'Financial Trustee',  'main',     'Mrs.', 2,  1),
('B. Veeramani',             'Secretary',          'main',     'Mr.',  3,  1),
('G.V. Loga Adhithya',      'Co-opted Trustee',   'co-opted', 'Dr.',  4,  1),
('P. Nandhivarman',          'Co-opted Trustee',   'co-opted', 'Dr.',  5,  1),
('B. Muruganandham',         'Co-opted Trustee',   'co-opted', 'Mr.',  6,  1),
('N. Balu',                  'Co-opted Trustee',   'co-opted', 'Mr.',  7,  1),
('Saravanakumar',            'Co-opted Trustee',   'co-opted', 'Mr.',  8,  1),
('Meera Bai',                'Co-opted Trustee',   'co-opted', 'Mrs.', 9,  1),
('Anandhi Tholkappiyan',     'Co-opted Trustee',   'co-opted', 'Ms.',  10, 1),
('Durgadevi',                'Co-opted Trustee',   'co-opted', 'Mrs.', 11, 1),
('Lalitha Srinivasan',       'Co-opted Trustee',   'co-opted', 'Mrs.', 12, 1),
('Usha Jagadeesan',          'Co-opted Trustee',   'co-opted', 'Mrs.', 13, 1),
('K. Murugan',               'Co-opted Trustee',   'co-opted', 'Mr.',  14, 1),
('Ambika Balaji',            'Co-opted Trustee',   'co-opted', 'Ms.',  15, 1);

-- ============================================
-- PAGES (from PDF content)
-- ============================================
INSERT INTO `pages` (`created_by`, `title`, `slug`, `content`, `status`, `show_in_menu`, `menu_position`, `sort_order`) VALUES
(1, 'About Us', 'about-us',
'<p>Sri Sai Mission Charitable Trust is a Non-Profitable Organization dedicated for helping needy and neglected Man, Women &amp; children. It has been working in India since 2014. The main object being propagation of the Life and Teachings of Sri Sai Baba of Shirdi.</p><p>Sri Sai Mission Charitable Trust was founded by Sri Sai Varadharajan, a pious Sai Devotee, philanthropist, writer and journalist of Chennai. It is a Public Charitable Trust under Income Tax act Section 10(23c) iv of the Income Tax act 1961 for carrying out activities in the areas of basic education, health care, Helping in Disaster, Skill Development, intervention, treatment, Care and lot more objects of general public Utility.</p><p>The Trust has been granted recognition for Tax Exemption under section 80G of the Income Tax.</p>',
'published', 1, 1, 1),

(1, 'Our Mission', 'mission',
'<p>Sri Sai Baba''s Mission was to bring together all religions under a common fold, that Baba promoted a religion of love with peace and harmony and that Baba belonged to no particular religion or faith.</p><p>Sri Sai Mission Charitable Trust visualizes a society in which peace, justice, and equality prevail and strives to build an India where all people have access to education, healthcare, employment, housing &amp; sanitation and economic self-reliance, and where all Indians can realize their full potential offsetting barriers of caste, creed, color, language and gender.</p><p>Our Mission to create awareness among the fellow citizens towards the well being of old age people, woman empowerment, and helpless children. We also work to spread awareness about the seasonal diseases and deadly diseases like AIDS, cancer, etc.</p>',
'published', 1, 2, 2),

(1, 'Our Vision', 'vision',
'<p>We aim to offer non-profitable selfless charity and service to all who are in need of basic care, regardless of their religion and caste. Our wide range of offerings include medical assistance, shelter, food, clothes, education to poor/less fortunate children, encourage community development programs, aid physically challenged people - this and much more.</p><p>Rebuild the human dignity of the poor and marginalized through an empowerment process, Education, food security, Health Care, employment to unemployed and create opportunities for a sustainable society.</p><p>Fostering community development through sustainable projects that promote self-reliance, environmental stewardship, and economic empowerment.</p><p>Through our unwavering commitment to these principles, Sri Sai Mission Trust endeavors to create a more equitable, inclusive, and compassionate society where every individual has the opportunity to thrive and fulfill their potential.</p>',
'published', 1, 3, 3),

(1, 'Annadhanam', 'annadhanam',
'<p>Annadhanam (free food distribution) is one of the core activities of Sri Sai Mission Charitable Trust. We believe that serving food to the hungry is the highest form of service.</p>',
'published', 1, 4, 4),

(1, 'Cow Saala', 'cow-saala',
'<p>Sri Sai Mission maintains a Cow Saala (cow shelter) as part of our commitment to animal welfare and the protection of cows, which hold a sacred place in Indian culture.</p>',
'published', 1, 5, 5),

(1, 'Temple Pooja', 'temple-pooja',
'<p>Daily pooja is conducted at our temples. Devotees are welcome to participate in the daily prayers and special ceremonies.</p><p><strong>Daily Pooja Times:</strong> 8:00 AM, 12:00 Noon, 6:00 PM</p>',
'published', 1, 6, 6),

(1, 'Tourism', 'tourism',
'<p>Sri Sai Mission organizes spiritual tourism and temple visits for devotees. Visit our sacred temples and experience divine bliss.</p><p><strong>Perungalathur Athma Sai Temple</strong> — Morning 7 AM to 12:30 PM, Evening 4:00 to 7:30 PM</p><p><strong>Keerapakkam Baba Temple</strong> — Palki Procession every Sunday at 4 PM</p>',
'published', 1, 7, 7),

(1, 'Donations', 'donations',
'<p>Your generous donations help us continue our charitable activities including Annadhanam, Cow Saala, Temple maintenance, and community development programs.</p><p>Sri Sai Mission Charitable Trust is registered under Section 80G of the Income Tax Act. All donations are eligible for tax exemption.</p><p><strong>Trust Registration:</strong> 106/2014</p><p><strong>Tax Exemption:</strong> Section 80G of the Income Tax Act</p>',
'published', 1, 8, 8);

-- ============================================
-- SETTINGS
-- ============================================
INSERT INTO `settings` (`group_name`, `key_name`, `value`, `type`, `label`) VALUES
('general', 'site_name',           'Sri Sai Mission',                                              'text',     'Site Name'),
('general', 'site_tagline',        'Religious & Charitable Trust (Regd) 106/2014',                  'text',     'Site Tagline'),
('general', 'site_logo',           '/assets/images/logo.png',                                      'image',    'Site Logo'),
('general', 'site_favicon',        '/assets/images/favicon.png',                                   'image',    'Favicon'),
('general', 'tax_exemption_info',  'Section 80G of the Income Tax Act',                            'text',     'Tax Exemption Info'),
('general', 'trust_registration',  '106/2014',                                                     'text',     'Trust Registration Number'),
('general', 'founded_year',        '2014',                                                         'text',     'Founded Year'),

('contact', 'contact_phone_1',     '9841203311',                                                   'text',     'Phone Number 1'),
('contact', 'contact_phone_2',     '9094033288',                                                   'text',     'Phone Number 2'),
('contact', 'contact_phone_3',     '044-48589900',                                                 'text',     'Landline'),
('contact', 'contact_email',       'srisaimission@gmail.com',                                      'text',     'Contact Email'),
('contact', 'contact_address',     '3E/A, 2nd Street, Buddhar Nagar, New Perungalathur, Chennai- 600 063', 'textarea', 'Address'),

('social',  'facebook_url',        '',                                                             'text',     'Facebook URL'),
('social',  'instagram_url',       '',                                                             'text',     'Instagram URL'),
('social',  'youtube_url',         '',                                                             'text',     'YouTube URL'),
('social',  'twitter_url',         '',                                                             'text',     'Twitter URL'),

('temple',  'temple_name_1',       'Perungalathur Athma Sai Temple',                               'text',     'Temple 1 Name'),
('temple',  'temple_name_2',       'Keerapakkam Baba Temple',                                      'text',     'Temple 2 Name');

-- ============================================
-- TEMPLE TIMINGS
-- ============================================
INSERT INTO `temple_timings` (`title`, `day_type`, `start_time`, `end_time`, `description`, `location`, `sort_order`) VALUES
('Morning Session',   'daily',   '07:00:00', '12:30:00', 'Morning darshan and pooja',        'Perungalathur Athma Sai Temple', 1),
('Evening Session',   'daily',   '16:00:00', '19:30:00', 'Evening darshan and pooja',        'Perungalathur Athma Sai Temple', 2),
('Morning Pooja',     'daily',   '08:00:00', '08:30:00', 'Daily morning pooja',              'Perungalathur Athma Sai Temple', 3),
('Noon Pooja',        'daily',   '12:00:00', '12:30:00', 'Daily noon pooja',                 'Perungalathur Athma Sai Temple', 4),
('Evening Pooja',     'daily',   '18:00:00', '18:30:00', 'Daily evening pooja',              'Perungalathur Athma Sai Temple', 5),
('Palki Procession',  'sunday',  '16:00:00', '18:00:00', 'Weekly Palki Procession',          'Keerapakkam Baba Temple',        6),
('Sathya Narayana Pooja', 'special', '10:00:00', '12:00:00', 'Every Full Moon Day',          'Perungalathur Athma Sai Temple', 7);

-- ============================================
-- DEFAULT CATEGORIES
-- ============================================
INSERT INTO `categories` (`name`, `slug`, `type`, `sort_order`) VALUES
('General',        'general',         'blog',     1),
('Sai Teachings',  'sai-teachings',   'blog',     2),
('Events',         'events',          'blog',     3),
('Trust News',     'trust-news',      'blog',     4),
('Monthly Issue',  'monthly-issue',   'magazine', 1),
('Special Issue',  'special-issue',   'magazine', 2),
('Temple Events',  'temple-events',   'gallery',  1),
('Annadhanam',     'annadhanam-gallery', 'gallery', 2),
('Festivals',      'festivals',       'gallery',  3),
('Pooja',          'pooja-events',    'event',    1),
('Festival',       'festival-events', 'event',    2),
('Community',      'community-events','event',    3);
