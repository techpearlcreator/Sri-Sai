<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle ?? 'Sri Sai Mission') ?></title>
<meta name="description" content="<?= htmlspecialchars($pageDescription ?? 'Sri Sai Mission Religious & Charitable Trust - Serving humanity through Annadhanam, Temple Worship, Education & Spiritual Guidance.') ?>">

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&family=Kumbh+Sans:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

<!-- Icon Fonts -->
<link rel="stylesheet" href="<?= $assetUrl ?>/css/fontello.css">
<link rel="stylesheet" href="<?= $assetUrl ?>/css/trx_addons_icons.css">
<link rel="stylesheet" href="<?= $assetUrl ?>/css/elementor-icons.min.css">

<!-- Core CSS -->
<link rel="stylesheet" href="<?= $assetUrl ?>/css/swiper.min.css">
<link rel="stylesheet" href="<?= $assetUrl ?>/css/magnific-popup.min.css">

<!-- Theme CSS -->
<link rel="stylesheet" href="<?= $assetUrl ?>/css/__styles.css">
<link rel="stylesheet" href="<?= $assetUrl ?>/css/__plugins.css">
<link rel="stylesheet" href="<?= $assetUrl ?>/css/__responsive.css">

<!-- Component CSS -->
<link rel="stylesheet" href="<?= $assetUrl ?>/css/content.css">
<link rel="stylesheet" href="<?= $assetUrl ?>/css/blogger.css">
<link rel="stylesheet" href="<?= $assetUrl ?>/css/icons.css">
<link rel="stylesheet" href="<?= $assetUrl ?>/css/skills.css">
<link rel="stylesheet" href="<?= $assetUrl ?>/css/animation.css">

<!-- Widget CSS -->
<link rel="stylesheet" href="<?= $assetUrl ?>/css/widget-heading.min.css">
<link rel="stylesheet" href="<?= $assetUrl ?>/css/widget-divider.min.css">
<link rel="stylesheet" href="<?= $assetUrl ?>/css/widget-image.min.css">
<link rel="stylesheet" href="<?= $assetUrl ?>/css/widget-spacer.min.css">

<!-- Custom Overrides -->
<link rel="stylesheet" href="<?= $assetUrl ?>/css/__custom.css">

<!-- Sri Sai Mission Custom Styles -->
<style>
/* ========== Base & Typography ========== */
:root {
    --srisai-primary: #1D0427;
    --srisai-primary-light: #5F2C70;
    --srisai-secondary: #724D67;
    --srisai-accent: #9FA73E;
    --srisai-text: #333;
    --srisai-text-light: rgba(255,255,255,0.8);
    --srisai-bg-light: #f8f6f3;
    --srisai-bg-dark: #1D0427;
    --srisai-border: #e5e5e5;
    --srisai-radius: 8px;
    --srisai-shadow: 0 4px 20px rgba(29,4,39,0.1);
    --srisai-container: 1200px;
}

body {
    font-family: 'Nunito', sans-serif;
    color: var(--srisai-text);
    line-height: 1.7;
    overflow-x: hidden;
}

h1, h2, h3, h4, h5, h6 {
    font-family: 'Kumbh Sans', sans-serif;
    font-weight: 700;
    line-height: 1.3;
    color: var(--srisai-primary);
}

a { color: var(--srisai-primary-light); text-decoration: none; transition: color 0.3s; }
a:hover { color: var(--srisai-accent); }

/* ========== Layout ========== */
.srisai-container {
    max-width: var(--srisai-container);
    margin: 0 auto;
    padding: 0 20px;
}

.srisai-content-narrow { max-width: 800px; }

.srisai-section {
    padding: 80px 0;
}

.srisai-section--white { background: #fff; }
.srisai-section--light { background: var(--srisai-bg-light); }
.srisai-section--dark { background: var(--srisai-bg-dark); }
.srisai-section--dark h2, .srisai-section--dark h3, .srisai-section--dark p { color: #fff; }

.srisai-section__header {
    text-align: center;
    margin-bottom: 50px;
}

.srisai-section__header--light .srisai-section__label,
.srisai-section__header--light .srisai-section__title,
.srisai-section__header--light .srisai-section__desc { color: #fff; }

.srisai-section__label {
    display: inline-block;
    font-family: 'Kumbh Sans', sans-serif;
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: var(--srisai-accent);
    margin-bottom: 10px;
}

.srisai-section__title {
    font-size: 36px;
    margin: 0 0 15px;
}

.srisai-section__desc {
    font-size: 17px;
    color: #666;
    max-width: 650px;
    margin: 0 auto;
}

/* ========== Page Header (inner pages) ========== */
.srisai-page-header {
    background: var(--srisai-primary);
    padding: 50px 0 40px;
    text-align: center;
}

.srisai-page-header h1 {
    color: #fff;
    font-size: 36px;
    margin: 0 0 8px;
}

.srisai-page-header p {
    color: var(--srisai-text-light);
    font-size: 17px;
    margin: 0;
}

/* ========== Breadcrumb ========== */
.srisai-breadcrumb {
    font-size: 14px;
    color: rgba(255,255,255,0.6);
    padding: 15px 0;
}

.srisai-breadcrumb a { color: rgba(255,255,255,0.8); }
.srisai-breadcrumb a:hover { color: #fff; }
.srisai-breadcrumb span { color: var(--srisai-accent); }

/* ========== Buttons ========== */
.srisai-btn {
    display: inline-block;
    padding: 12px 30px;
    border-radius: 50px;
    font-family: 'Kumbh Sans', sans-serif;
    font-size: 15px;
    font-weight: 600;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
    border: 2px solid transparent;
    text-decoration: none;
}

.srisai-btn--primary {
    background: var(--srisai-accent);
    color: #fff;
    border-color: var(--srisai-accent);
}

.srisai-btn--primary:hover {
    background: #8a912f;
    border-color: #8a912f;
    color: #fff;
}

.srisai-btn--outline {
    background: transparent;
    color: var(--srisai-primary);
    border-color: var(--srisai-primary);
}

.srisai-btn--outline:hover {
    background: var(--srisai-primary);
    color: #fff;
}

.srisai-section--dark .srisai-btn--outline {
    color: #fff;
    border-color: #fff;
}

.srisai-section--dark .srisai-btn--outline:hover {
    background: #fff;
    color: var(--srisai-primary);
}

.srisai-btn--sm { padding: 8px 20px; font-size: 13px; }

/* ========== Hero ========== */
.srisai-hero {
    position: relative;
    min-height: 85vh;
    display: flex;
    align-items: center;
    background-size: cover;
    background-position: center;
}

.srisai-hero__overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(29,4,39,0.85) 0%, rgba(95,44,112,0.7) 100%);
}

.srisai-hero__content {
    position: relative;
    z-index: 2;
    text-align: center;
    color: #fff;
}

.srisai-hero__title {
    font-size: 60px;
    color: #fff;
    margin: 0 0 10px;
    letter-spacing: -1px;
}

.srisai-hero__subtitle {
    font-size: 22px;
    font-weight: 300;
    margin: 0 0 20px;
    opacity: 0.9;
}

.srisai-hero__desc {
    font-size: 18px;
    max-width: 600px;
    margin: 0 auto 35px;
    opacity: 0.85;
}

.srisai-hero__buttons { display: flex; gap: 15px; justify-content: center; flex-wrap: wrap; }
.srisai-hero__buttons .srisai-btn--outline { color: #fff; border-color: #fff; }
.srisai-hero__buttons .srisai-btn--outline:hover { background: #fff; color: var(--srisai-primary); }

/* ========== Services / Activities Grid ========== */
.srisai-services-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
}

.srisai-service-card {
    text-align: center;
    padding: 40px 25px;
    background: #fff;
    border-radius: var(--srisai-radius);
    box-shadow: var(--srisai-shadow);
    transition: transform 0.3s, box-shadow 0.3s;
}

.srisai-service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(29,4,39,0.15);
}

.srisai-service-card__icon { font-size: 40px; margin-bottom: 15px; }
.srisai-service-card h3 { font-size: 20px; margin: 0 0 10px; }
.srisai-service-card p { font-size: 15px; color: #666; margin: 0; }

/* ========== Temples Grid ========== */
.srisai-temples-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 30px;
}

.srisai-temple-card {
    background: rgba(255,255,255,0.08);
    border-radius: var(--srisai-radius);
    overflow: hidden;
    transition: transform 0.3s;
}

.srisai-temple-card:hover { transform: translateY(-5px); }
.srisai-temple-card__image img { width: 100%; height: 280px; object-fit: cover; display: block; }
.srisai-temple-card__body { padding: 25px; }
.srisai-temple-card__body h3 { color: #fff; font-size: 22px; margin: 0 0 10px; }
.srisai-temple-card__body p { color: var(--srisai-text-light); font-size: 15px; margin: 0; }

/* ========== Timings Table ========== */
.srisai-timings-table {
    max-width: 900px;
    margin: 0 auto;
    overflow-x: auto;
}

.srisai-timings-table table {
    width: 100%;
    border-collapse: collapse;
    font-size: 15px;
}

.srisai-timings-table thead { background: var(--srisai-primary); color: #fff; }
.srisai-timings-table th { padding: 14px 20px; text-align: left; font-family: 'Kumbh Sans', sans-serif; font-weight: 600; }
.srisai-timings-table td { padding: 12px 20px; border-bottom: 1px solid var(--srisai-border); }
.srisai-timings-table tbody tr:hover { background: var(--srisai-bg-light); }

/* ========== CTA Section ========== */
.srisai-cta {
    position: relative;
    text-align: center;
    background-size: cover;
    background-position: center;
}

.srisai-cta__overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(29,4,39,0.9) 0%, rgba(95,44,112,0.85) 100%);
}

.srisai-cta__content {
    position: relative;
    z-index: 2;
}

.srisai-cta__content h2 { color: #fff; font-size: 36px; margin: 0 0 15px; }
.srisai-cta__content p { color: var(--srisai-text-light); font-size: 17px; margin: 0 0 10px; max-width: 600px; margin-left: auto; margin-right: auto; }
.srisai-cta__content .srisai-btn { margin-top: 20px; }

/* ========== Trustees Grid ========== */
.srisai-trustees-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 30px;
}

.srisai-trustee-card {
    text-align: center;
    padding: 25px 15px;
}

.srisai-trustee-card__photo {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    overflow: hidden;
    margin: 0 auto 15px;
    box-shadow: 0 4px 15px rgba(29,4,39,0.15);
}

.srisai-trustee-card__photo img { width: 100%; height: 100%; object-fit: cover; }

.srisai-trustee-card__photo--placeholder {
    background: var(--srisai-primary);
    display: flex;
    align-items: center;
    justify-content: center;
}

.srisai-trustee-card__photo--placeholder span {
    font-size: 36px;
    color: #fff;
    font-family: 'Kumbh Sans', sans-serif;
    font-weight: 700;
}

.srisai-trustee-card h4 { font-size: 16px; margin: 0 0 5px; }
.srisai-trustee-card__role { font-size: 13px; color: var(--srisai-secondary); margin: 0; }

/* ========== Events Grid ========== */
.srisai-events-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 20px;
}

.srisai-event-card {
    display: flex;
    gap: 20px;
    padding: 20px;
    background: #fff;
    border-radius: var(--srisai-radius);
    box-shadow: var(--srisai-shadow);
    text-decoration: none;
    color: var(--srisai-text);
    transition: transform 0.3s, box-shadow 0.3s;
}

.srisai-event-card:hover { transform: translateY(-3px); box-shadow: 0 8px 30px rgba(29,4,39,0.15); color: var(--srisai-text); }

.srisai-event-card__date {
    flex-shrink: 0;
    width: 65px;
    text-align: center;
    background: var(--srisai-primary);
    color: #fff;
    border-radius: var(--srisai-radius);
    padding: 12px 8px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.srisai-event-card__day { font-size: 24px; font-weight: 700; font-family: 'Kumbh Sans', sans-serif; line-height: 1; }
.srisai-event-card__month { font-size: 13px; text-transform: uppercase; letter-spacing: 1px; }

.srisai-event-card__info h4 { font-size: 17px; margin: 0 0 5px; }
.srisai-event-card__info p { font-size: 14px; color: #888; margin: 0; }

/* ========== Blog Grid ========== */
.srisai-blog-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 30px;
}

.srisai-blog-card {
    display: block;
    text-decoration: none;
    color: var(--srisai-text);
    background: #fff;
    border-radius: var(--srisai-radius);
    overflow: hidden;
    box-shadow: var(--srisai-shadow);
    transition: transform 0.3s, box-shadow 0.3s;
}

.srisai-blog-card:hover { transform: translateY(-5px); box-shadow: 0 8px 30px rgba(29,4,39,0.15); color: var(--srisai-text); }

.srisai-blog-card__image img { width: 100%; height: 220px; object-fit: cover; display: block; }
.srisai-blog-card__image--placeholder { height: 220px; background: linear-gradient(135deg, var(--srisai-primary) 0%, var(--srisai-primary-light) 100%); }

.srisai-blog-card__body { padding: 20px; }
.srisai-blog-card__date { font-size: 13px; color: var(--srisai-accent); font-weight: 600; text-transform: uppercase; }
.srisai-blog-card__body h4 { font-size: 18px; margin: 8px 0; line-height: 1.4; }
.srisai-blog-card__body p { font-size: 14px; color: #666; margin: 0; line-height: 1.6; }

/* ========== Magazine Grid ========== */
.srisai-magazine-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 30px;
}

.srisai-magazine-card {
    display: block;
    text-decoration: none;
    color: var(--srisai-text);
    text-align: center;
    transition: transform 0.3s;
}

.srisai-magazine-card:hover { transform: translateY(-5px); color: var(--srisai-text); }

.srisai-magazine-card__cover img {
    width: 100%;
    height: 300px;
    object-fit: cover;
    border-radius: var(--srisai-radius);
    box-shadow: var(--srisai-shadow);
}

.srisai-magazine-card__cover--placeholder {
    height: 300px;
    background: linear-gradient(135deg, var(--srisai-primary) 0%, var(--srisai-primary-light) 100%);
    border-radius: var(--srisai-radius);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 18px;
    font-weight: 600;
}

.srisai-magazine-card__info { padding: 15px 5px; }
.srisai-magazine-card__info h4 { font-size: 16px; margin: 0 0 5px; }
.srisai-magazine-card__info p { font-size: 13px; color: #888; margin: 0; }

/* ========== Gallery Grid ========== */
.srisai-gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 25px;
}

.srisai-gallery-card {
    display: block;
    text-decoration: none;
    color: var(--srisai-text);
    border-radius: var(--srisai-radius);
    overflow: hidden;
    box-shadow: var(--srisai-shadow);
    transition: transform 0.3s;
}

.srisai-gallery-card:hover { transform: translateY(-5px); color: var(--srisai-text); }
.srisai-gallery-card__image img { width: 100%; height: 220px; object-fit: cover; display: block; }
.srisai-gallery-card__image--placeholder { height: 220px; background: var(--srisai-bg-light); display: flex; align-items: center; justify-content: center; color: #999; font-size: 14px; }
.srisai-gallery-card__body { padding: 15px; background: #fff; }
.srisai-gallery-card__body h4 { font-size: 16px; margin: 0 0 5px; }
.srisai-gallery-card__body p { font-size: 13px; color: #888; margin: 0; }

/* ========== Gallery Image Grid (show page) ========== */
.srisai-image-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 15px;
}

.srisai-image-grid a {
    display: block;
    border-radius: var(--srisai-radius);
    overflow: hidden;
}

.srisai-image-grid img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    display: block;
    transition: transform 0.3s;
}

.srisai-image-grid a:hover img { transform: scale(1.05); }

/* ========== Article (single post/event/page) ========== */
.srisai-article__header { margin-bottom: 25px; }
.srisai-article__header h1 { font-size: 32px; margin: 0 0 10px; }

.srisai-article__meta {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    font-size: 14px;
    color: #888;
}

.srisai-article__image { margin-bottom: 25px; }
.srisai-article__image img { width: 100%; border-radius: var(--srisai-radius); }

.srisai-article__content {
    font-size: 16px;
    line-height: 1.8;
}

.srisai-article__content img { max-width: 100%; height: auto; border-radius: var(--srisai-radius); }
.srisai-article__content h2 { font-size: 24px; margin: 30px 0 15px; }
.srisai-article__content h3 { font-size: 20px; margin: 25px 0 12px; }
.srisai-article__content p { margin: 0 0 18px; }
.srisai-article__content blockquote { border-left: 4px solid var(--srisai-accent); padding: 15px 20px; margin: 20px 0; background: var(--srisai-bg-light); font-style: italic; }
.srisai-article__content ul, .srisai-article__content ol { margin: 0 0 18px 25px; }

.srisai-article__nav { margin-top: 40px; padding-top: 25px; border-top: 1px solid var(--srisai-border); }

/* ========== Contact Form ========== */
.srisai-contact-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 50px;
}

.srisai-form-group { margin-bottom: 20px; }
.srisai-form-group label { display: block; font-weight: 600; margin-bottom: 6px; font-size: 14px; color: var(--srisai-primary); }

.srisai-form-group input,
.srisai-form-group textarea,
.srisai-form-group select {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid var(--srisai-border);
    border-radius: var(--srisai-radius);
    font-family: 'Nunito', sans-serif;
    font-size: 15px;
    transition: border-color 0.3s;
    box-sizing: border-box;
}

.srisai-form-group input:focus,
.srisai-form-group textarea:focus {
    outline: none;
    border-color: var(--srisai-primary-light);
}

.srisai-form-group textarea { resize: vertical; min-height: 120px; }

.srisai-contact-info h3 { font-size: 20px; margin: 0 0 20px; }

.srisai-contact-item {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
    align-items: flex-start;
}

.srisai-contact-item__icon {
    flex-shrink: 0;
    width: 45px;
    height: 45px;
    background: var(--srisai-primary);
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.srisai-contact-item h4 { font-size: 15px; margin: 0 0 3px; }
.srisai-contact-item p { font-size: 14px; color: #666; margin: 0; }

/* ========== Alert Messages ========== */
.alert { padding: 12px 18px; border-radius: var(--srisai-radius); margin: 15px 0; font-size: 14px; }
.alert-success { background: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; }
.alert-error { background: #fce4ec; color: #c62828; border: 1px solid #f8bbd0; }

/* ========== Pagination ========== */
.srisai-pagination {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
    margin-top: 40px;
}

.srisai-pagination__info { font-size: 14px; color: #888; }

/* ========== Empty State ========== */
.srisai-empty {
    text-align: center;
    padding: 60px 20px;
    font-size: 17px;
    color: #999;
}

/* ========== About Content ========== */
.srisai-about-content {
    max-width: 800px;
    margin: 0 auto;
    font-size: 16px;
    line-height: 1.8;
}

/* ========== Donation Info ========== */
.srisai-donation-methods {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 25px;
    margin-top: 30px;
}

.srisai-donation-card {
    padding: 30px;
    background: #fff;
    border-radius: var(--srisai-radius);
    box-shadow: var(--srisai-shadow);
    text-align: center;
}

.srisai-donation-card__icon { font-size: 36px; margin-bottom: 15px; }
.srisai-donation-card h4 { font-size: 18px; margin: 0 0 10px; }
.srisai-donation-card p { font-size: 14px; color: #666; margin: 0; }

/* ========== Scroll to Top ========== */
.scroll-to-top {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 45px;
    height: 45px;
    background: var(--srisai-primary);
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    cursor: pointer;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s;
    z-index: 999;
    text-decoration: none;
}

.scroll-to-top.visible { opacity: 1; visibility: visible; }
.scroll-to-top:hover { background: var(--srisai-accent); color: #fff; }

/* ========== Error Pages ========== */
.srisai-error {
    text-align: center;
    padding: 100px 20px;
}

.srisai-error__code {
    font-size: 120px;
    font-weight: 700;
    color: var(--srisai-primary);
    font-family: 'Kumbh Sans', sans-serif;
    line-height: 1;
    margin: 0;
}

.srisai-error__title { font-size: 28px; margin: 15px 0; }
.srisai-error__desc { font-size: 17px; color: #888; max-width: 500px; margin: 0 auto 30px; }

/* ========== Responsive ========== */
@media (max-width: 1023px) {
    .srisai-services-grid { grid-template-columns: repeat(2, 1fr); }
    .srisai-temples-grid { grid-template-columns: 1fr; }
    .srisai-hero__title { font-size: 42px; }
    .srisai-section { padding: 60px 0; }
    .srisai-contact-grid { grid-template-columns: 1fr; }
}

@media (max-width: 767px) {
    .srisai-services-grid { grid-template-columns: 1fr; }
    .srisai-blog-grid { grid-template-columns: 1fr; }
    .srisai-events-grid { grid-template-columns: 1fr; }
    .srisai-gallery-grid { grid-template-columns: 1fr; }
    .srisai-magazine-grid { grid-template-columns: repeat(2, 1fr); }
    .srisai-hero { min-height: 70vh; }
    .srisai-hero__title { font-size: 32px; }
    .srisai-hero__subtitle { font-size: 18px; }
    .srisai-section__title { font-size: 28px; }
    .srisai-page-header h1 { font-size: 28px; }
    .srisai-article__header h1 { font-size: 26px; }
    .srisai-section { padding: 45px 0; }
    .srisai-trustees-grid { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 480px) {
    .srisai-magazine-grid { grid-template-columns: 1fr; }
    .srisai-trustees-grid { grid-template-columns: 1fr; }
}
</style>
