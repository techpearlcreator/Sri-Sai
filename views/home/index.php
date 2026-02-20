<!-- Hero Slider Section -->
<section class="hero-section">
    <div class="swiper hero-swiper">
        <div class="swiper-wrapper">
            <div class="swiper-slide hero-slide" style="background-image: url('<?= $assetUrl ?>/images/hero-slider-1.jpg');">
                <div class="hero-overlay"></div>
                <div class="hero-content">
                    <span class="hero-tagline"><?= __('home.hero1_tagline') ?></span>
                    <h1 class="hero-title"><?= __('home.hero1_title1') ?><br><?= __('home.hero1_title2') ?></h1>
                    <a href="<?= $baseUrl ?>/about" class="btn btn-primary"><?= __('btn.read_more') ?></a>
                </div>
            </div>
            <div class="swiper-slide hero-slide" style="background-image: url('<?= $assetUrl ?>/images/hero-slider-2.jpg');">
                <div class="hero-overlay"></div>
                <div class="hero-content">
                    <span class="hero-tagline"><?= __('home.hero2_tagline') ?></span>
                    <h1 class="hero-title"><?= __('home.hero2_title1') ?><br><?= __('home.hero2_title2') ?></h1>
                    <a href="<?= $baseUrl ?>/donations" class="btn btn-primary"><?= __('btn.donate_now') ?></a>
                </div>
            </div>
            <div class="swiper-slide hero-slide" style="background-image: url('<?= $assetUrl ?>/images/hero-slider-3.jpg');">
                <div class="hero-overlay"></div>
                <div class="hero-content">
                    <span class="hero-tagline"><?= __('home.hero3_tagline') ?></span>
                    <h1 class="hero-title"><?= __('home.hero3_title1') ?><br><?= __('home.hero3_title2') ?></h1>
                    <a href="<?= $baseUrl ?>/events" class="btn btn-outline"><?= __('btn.upcoming_events') ?></a>
                </div>
            </div>
        </div>
        <div class="hero-slider-pagination"></div>
        <div class="hero-slider-button-prev"></div>
        <div class="hero-slider-button-next"></div>
    </div>
</section>

<!-- Services / Activities Section -->
<section class="section bg-white" id="services">
    <div class="section-container">
        <div class="section-header">
            <h2 class="section-title"><?= __('home.activities_title') ?></h2>
        </div>
        <div class="sc_icons sc_icons_figure sc_icons_qw-stylish sc_align_center">
            <div class="sc_icons_columns_wrap trx_addons_columns_wrap">
                <!-- Annadhanam -->
                <div class="trx_addons_column-1_4">
                    <div class="sc_icons_item sc_icons_item_linked with_more">
                        <div class="sc_icons_item_shine"></div>
                        <div class="sc_icons_item_background">
                            <div class="sc_icons_item_tiles">
                                <div class="sc_icons_item_tile sc_icons_item_tile-1"></div>
                                <div class="sc_icons_item_tile sc_icons_item_tile-2"></div>
                                <div class="sc_icons_item_tile sc_icons_item_tile-3"></div>
                                <div class="sc_icons_item_tile sc_icons_item_tile-4"></div>
                                <div class="sc_icons_item_tile sc_icons_item_tile-5"></div>
                                <div class="sc_icons_item_tile sc_icons_item_tile-6"></div>
                                <div class="sc_icons_item_tile sc_icons_item_tile-7"></div>
                                <div class="sc_icons_item_tile sc_icons_item_tile-8"></div>
                                <div class="sc_icons_item_tile sc_icons_item_tile-9"></div>
                                <div class="sc_icons_item_tile sc_icons_item_tile-10"></div>
                            </div>
                            <div class="sc_icons_item_lines">
                                <div class="sc_icons_item_line sc_icons_item_line-1"></div>
                                <div class="sc_icons_item_line sc_icons_item_line-2"></div>
                                <div class="sc_icons_item_line sc_icons_item_line-3"></div>
                            </div>
                        </div>
                        <div class="sc_icons_icon">
                            <span class="icon-lotus-1"></span>
                        </div>
                        <div class="sc_icons_item_details">
                            <h4 class="sc_icons_item_title">
                                <a href="<?= $baseUrl ?>/about"><?= __('home.annadhanam') ?></a>
                            </h4>
                            <div class="sc_icons_item_description">
                                <span><?= __('home.annadhanam_desc') ?></span>
                            </div>
                            <a href="<?= $baseUrl ?>/about" class="sc_icons_item_more_link">
                                <span class="link_text"><?= __('btn.read_more_lc') ?></span>
                                <span class="link_icon"></span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Temple Pooja -->
                <div class="trx_addons_column-1_4">
                    <div class="sc_icons_item sc_icons_item_linked with_more">
                        <div class="sc_icons_item_shine"></div>
                        <div class="sc_icons_item_background">
                            <div class="sc_icons_item_tiles">
                                <div class="sc_icons_item_tile sc_icons_item_tile-1"></div>
                                <div class="sc_icons_item_tile sc_icons_item_tile-2"></div>
                                <div class="sc_icons_item_tile sc_icons_item_tile-3"></div>
                                <div class="sc_icons_item_tile sc_icons_item_tile-4"></div>
                                <div class="sc_icons_item_tile sc_icons_item_tile-5"></div>
                                <div class="sc_icons_item_tile sc_icons_item_tile-6"></div>
                                <div class="sc_icons_item_tile sc_icons_item_tile-7"></div>
                                <div class="sc_icons_item_tile sc_icons_item_tile-8"></div>
                                <div class="sc_icons_item_tile sc_icons_item_tile-9"></div>
                                <div class="sc_icons_item_tile sc_icons_item_tile-10"></div>
                            </div>
                            <div class="sc_icons_item_lines">
                                <div class="sc_icons_item_line sc_icons_item_line-1"></div>
                                <div class="sc_icons_item_line sc_icons_item_line-2"></div>
                                <div class="sc_icons_item_line sc_icons_item_line-3"></div>
                            </div>
                        </div>
                        <div class="sc_icons_icon">
                            <span class="icon-oil-lamp"></span>
                        </div>
                        <div class="sc_icons_item_details">
                            <h4 class="sc_icons_item_title">
                                <a href="<?= $baseUrl ?>/about"><?= __('home.temple_pooja') ?></a>
                            </h4>
                            <div class="sc_icons_item_description">
                                <span><?= __('home.temple_pooja_desc') ?></span>
                            </div>
                            <a href="<?= $baseUrl ?>/about" class="sc_icons_item_more_link">
                                <span class="link_text"><?= __('btn.read_more_lc') ?></span>
                                <span class="link_icon"></span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Spiritual Events -->
                <div class="trx_addons_column-1_4">
                    <div class="sc_icons_item sc_icons_item_linked with_more">
                        <div class="sc_icons_item_shine"></div>
                        <div class="sc_icons_item_background">
                            <div class="sc_icons_item_tiles">
                                <div class="sc_icons_item_tile sc_icons_item_tile-1"></div>
                                <div class="sc_icons_item_tile sc_icons_item_tile-2"></div>
                                <div class="sc_icons_item_tile sc_icons_item_tile-3"></div>
                                <div class="sc_icons_item_tile sc_icons_item_tile-4"></div>
                                <div class="sc_icons_item_tile sc_icons_item_tile-5"></div>
                                <div class="sc_icons_item_tile sc_icons_item_tile-6"></div>
                                <div class="sc_icons_item_tile sc_icons_item_tile-7"></div>
                                <div class="sc_icons_item_tile sc_icons_item_tile-8"></div>
                                <div class="sc_icons_item_tile sc_icons_item_tile-9"></div>
                                <div class="sc_icons_item_tile sc_icons_item_tile-10"></div>
                            </div>
                            <div class="sc_icons_item_lines">
                                <div class="sc_icons_item_line sc_icons_item_line-1"></div>
                                <div class="sc_icons_item_line sc_icons_item_line-2"></div>
                                <div class="sc_icons_item_line sc_icons_item_line-3"></div>
                            </div>
                        </div>
                        <div class="sc_icons_icon">
                            <span class="icon-mandala"></span>
                        </div>
                        <div class="sc_icons_item_details">
                            <h4 class="sc_icons_item_title">
                                <a href="<?= $baseUrl ?>/events"><?= __('home.spiritual_events') ?></a>
                            </h4>
                            <div class="sc_icons_item_description">
                                <span><?= __('home.spiritual_events_desc') ?></span>
                            </div>
                            <a href="<?= $baseUrl ?>/events" class="sc_icons_item_more_link">
                                <span class="link_text"><?= __('btn.read_more_lc') ?></span>
                                <span class="link_icon"></span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Sri Sai Dharisanam -->
                <div class="trx_addons_column-1_4">
                    <div class="sc_icons_item sc_icons_item_linked with_more">
                        <div class="sc_icons_item_shine"></div>
                        <div class="sc_icons_item_background">
                            <div class="sc_icons_item_tiles">
                                <div class="sc_icons_item_tile sc_icons_item_tile-1"></div>
                                <div class="sc_icons_item_tile sc_icons_item_tile-2"></div>
                                <div class="sc_icons_item_tile sc_icons_item_tile-3"></div>
                                <div class="sc_icons_item_tile sc_icons_item_tile-4"></div>
                                <div class="sc_icons_item_tile sc_icons_item_tile-5"></div>
                                <div class="sc_icons_item_tile sc_icons_item_tile-6"></div>
                                <div class="sc_icons_item_tile sc_icons_item_tile-7"></div>
                                <div class="sc_icons_item_tile sc_icons_item_tile-8"></div>
                                <div class="sc_icons_item_tile sc_icons_item_tile-9"></div>
                                <div class="sc_icons_item_tile sc_icons_item_tile-10"></div>
                            </div>
                            <div class="sc_icons_item_lines">
                                <div class="sc_icons_item_line sc_icons_item_line-1"></div>
                                <div class="sc_icons_item_line sc_icons_item_line-2"></div>
                                <div class="sc_icons_item_line sc_icons_item_line-3"></div>
                            </div>
                        </div>
                        <div class="sc_icons_icon">
                            <span class="icon-hamsa"></span>
                        </div>
                        <div class="sc_icons_item_details">
                            <h4 class="sc_icons_item_title">
                                <a href="<?= $baseUrl ?>/magazine"><?= __('home.dharisanam') ?></a>
                            </h4>
                            <div class="sc_icons_item_description">
                                <span><?= __('home.dharisanam_desc') ?></span>
                            </div>
                            <a href="<?= $baseUrl ?>/magazine" class="sc_icons_item_more_link">
                                <span class="link_text"><?= __('btn.read_more_lc') ?></span>
                                <span class="link_icon"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="section bg-light" id="about">
    <div class="section-container">
        <div class="about-grid">
            <div class="about-text">
                <span class="section-subtitle"><?= __('home.about_subtitle') ?></span>
                <h2 class="section-title" style="text-align:left;"><?= __('home.about_title') ?></h2>
            </div>
            <div class="about-content animate-on-scroll">
                <?php if (!empty($aboutPage)): ?>
                    <div class="srisai-about-content"><?= langField($aboutPage, 'content') ?></div>
                <?php else: ?>
                    <p><?= __('home.about_default_1') ?></p>
                    <p><?= __('home.about_default_2') ?></p>
                <?php endif; ?>
                <div style="margin-top: 2rem;">
                    <a href="<?= $baseUrl ?>/about" class="btn btn-primary"><?= __('btn.view_more') ?></a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Image Gallery Slider -->
<section class="section bg-white" style="padding: 40px 0;">
    <div class="gallery-slider-wrap">
        <div class="swiper gallery-swiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide"><img src="<?= $assetUrl ?>/images/gallery-1.jpg" alt="Gallery Image 1"></div>
                <div class="swiper-slide"><img src="<?= $assetUrl ?>/images/gallery-2.jpg" alt="Gallery Image 2"></div>
                <div class="swiper-slide"><img src="<?= $assetUrl ?>/images/gallery-3.jpg" alt="Gallery Image 3"></div>
                <div class="swiper-slide"><img src="<?= $assetUrl ?>/images/gallery-4.jpg" alt="Gallery Image 4"></div>
                <div class="swiper-slide"><img src="<?= $assetUrl ?>/images/gallery-5.jpg" alt="Gallery Image 5"></div>
                <div class="swiper-slide"><img src="<?= $assetUrl ?>/images/gallery-6.jpg" alt="Gallery Image 6"></div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Counter Section -->
<section class="section stats-section" id="stats">
    <div class="section-container">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-bg-number" data-count="5000">0</div>
                <div class="stat-label"><?= __('home.stat_devotees') ?></div>
            </div>
            <div class="stat-item">
                <div class="stat-bg-number" data-count="2">0</div>
                <div class="stat-label"><?= __('home.stat_temples') ?></div>
            </div>
            <div class="stat-item">
                <div class="stat-bg-number" data-count="15">0</div>
                <div class="stat-label"><?= __('home.stat_trustees') ?></div>
            </div>
            <div class="stat-item">
                <div class="stat-bg-number" data-count="50">0</div>
                <div class="stat-label"><?= __('home.stat_events') ?></div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="section bg-primary" style="padding: 60px 0;">
    <div class="section-container">
        <div class="cta-row">
            <div class="cta-text">
                <h3><?= __('home.cta_title') ?></h3>
            </div>
            <div class="cta-action">
                <a href="<?= $baseUrl ?>/about" class="btn btn-outline"><?= __('btn.join_group') ?></a>
            </div>
        </div>
    </div>
</section>

<!-- Video Section -->
<section class="section video-section" id="video-section" style="background-image: url('<?= $assetUrl ?>/images/hero-slider-1.jpg'); min-height: 500px; background-size: cover; background-position: center;">
    <div class="video-overlay"></div>
    <div class="section-container" style="text-align: center; position: relative; z-index: 2;">
        <button type="button" class="video-play-btn" id="video-play-btn">
            <span>PLAY</span>
        </button>
    </div>
</section>

<!-- Prayer Times + Temples Section -->
<section class="section bg-white">
    <div class="section-container">
        <div class="prayer-grid">
            <!-- Prayer Times Card -->
            <div class="prayer-card prayer-slide-left floating-card">
                <h3 class="prayer-card-title"><?= __('home.prayer_title') ?></h3>
                <p class="prayer-card-desc"><?= __('home.prayer_desc') ?></p>
                <ul class="prayer-times-list">
                    <?php if (!empty($timings)): ?>
                        <?php foreach (array_slice($timings, 0, 3) as $t): ?>
                            <li><?= htmlspecialchars(langField($t, 'title')) ?>: <?= htmlspecialchars($t->start_time) ?></li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li><?= __('home.morning_pooja') ?></li>
                        <li><?= __('home.evening_pooja') ?></li>
                        <li><?= __('home.special_events') ?></li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Temple Image -->
            <div class="prayer-image">
                <img src="<?= $assetUrl ?>/images/prayer-times-bg.jpg" alt="Sri Sai Temple">
            </div>

            <!-- Book a Visit Card -->
            <div class="visit-card prayer-slide-right floating-card">
                <h3 class="visit-card-title"><?= __('home.visit_title') ?></h3>
                <p class="visit-card-desc"><?= __('home.visit_desc') ?></p>
                <a href="<?= $baseUrl ?>/contact" class="btn btn-outline"><?= __('btn.contact_us') ?></a>
            </div>
        </div>
    </div>
</section>

<!-- Donations Section -->
<section class="section bg-light" id="donations">
    <div class="section-container">
        <div class="section-header">
            <span class="section-subtitle"><?= __('home.donations_subtitle') ?></span>
            <h2 class="section-title"><?= __('home.donations_title') ?></h2>
            <p class="section-description"><?= __('home.donations_desc') ?></p>
        </div>

        <div class="donation-form">
            <form id="srisai-donation-form">
                <div class="donation-amount-wrap">
                    <label class="donation-label"><?= __('home.donation_amount_label') ?></label>
                    <input class="donation-amount-input" id="donation-amount" name="amount" type="number" value="100" min="10" required>
                </div>

                <div class="donation-amounts">
                    <button type="button" class="amount-btn active" data-amount="100">&#8377;100</button>
                    <button type="button" class="amount-btn" data-amount="500">&#8377;500</button>
                    <button type="button" class="amount-btn" data-amount="1000">&#8377;1000</button>
                    <button type="button" class="amount-btn" data-amount="custom"><?= __('donations.custom') ?></button>
                </div>

                <fieldset class="donation-personal-info">
                    <legend><?= __('home.personal_info') ?></legend>
                    <div class="form-row-group">
                        <div class="form-row">
                            <label for="donor-first-name"><?= __('home.first_name') ?> <span class="required">*</span></label>
                            <input type="text" id="donor-first-name" name="first_name" required>
                        </div>
                        <div class="form-row">
                            <label for="donor-last-name"><?= __('home.last_name') ?></label>
                            <input type="text" id="donor-last-name" name="last_name">
                        </div>
                    </div>
                    <div class="form-row">
                        <label for="donor-email"><?= __('home.email_address') ?> <span class="required">*</span></label>
                        <input type="email" id="donor-email" name="email" required>
                    </div>
                    <div class="form-row">
                        <label for="donor-phone"><?= __('home.phone_number') ?></label>
                        <input type="tel" id="donor-phone" name="phone">
                    </div>
                    <div class="form-row">
                        <label for="donor-comment"><?= __('home.comment') ?></label>
                        <textarea id="donor-comment" name="comment" rows="4" placeholder="<?= __('home.comment_placeholder') ?>"></textarea>
                    </div>
                </fieldset>

                <div class="donation-total-wrap">
                    <span class="donation-total-label"><?= __('home.donation_total_label') ?></span>
                    <span class="donation-total-amount">&#8377;<span id="donation-total-display">100</span></span>
                </div>

                <div class="donation-submit-wrap">
                    <button type="submit" class="btn btn-accent donation-submit-btn"><?= __('home.donate_btn') ?></button>
                    <div class="donation-message"></div>
                    <p style="margin-top:10px; font-size:12px; color:#888;"><?= __('home.secure_razorpay') ?></p>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Upcoming Events Section -->
<?php if (!empty($events)): ?>
<section class="section bg-white">
    <div class="section-container">
        <div class="events-header-row">
            <div>
                <span class="section-subtitle"><?= __('home.events_subtitle') ?></span>
                <h2 class="section-title" style="text-align:left;"><?= __('home.events_title') ?></h2>
            </div>
            <div>
                <p><?= __('home.events_desc') ?></p>
            </div>
            <div style="text-align: right;">
                <a href="<?= $baseUrl ?>/events" class="btn btn-primary"><?= __('btn.view_all_events') ?></a>
            </div>
        </div>

        <div class="events-list">
            <?php foreach (array_slice($events, 0, 3) as $ev): ?>
            <div class="event-card animate-on-scroll">
                <div class="event-card-inner">
                    <div class="event-date">
                        <div class="event-day"><?= date('d', strtotime($ev->event_date)) ?></div>
                        <div class="event-month"><?= date('M, Y', strtotime($ev->event_date)) ?></div>
                    </div>
                    <div class="event-details">
                        <div class="event-thumb">
                            <?php if (!empty($ev->featured_image)): ?>
                                <img src="<?= $baseUrl ?>/storage/uploads/<?= htmlspecialchars($ev->featured_image) ?>" alt="<?= htmlspecialchars(langField($ev, 'title')) ?>">
                            <?php else: ?>
                                <img src="<?= $assetUrl ?>/images/gallery-1.jpg" alt="<?= htmlspecialchars(langField($ev, 'title')) ?>">
                            <?php endif; ?>
                        </div>
                        <div class="event-info">
                            <h4><a href="<?= $baseUrl ?>/events/<?= htmlspecialchars($ev->slug) ?>"><?= htmlspecialchars(langField($ev, 'title')) ?></a></h4>
                            <p class="event-meta">
                                <span><?= htmlspecialchars($ev->location ?? 'Chennai') ?></span>
                                <span><?= date('M d, Y H:i', strtotime($ev->event_date . ' ' . ($ev->event_time ?? '00:00'))) ?></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Blog Section -->
<?php if (!empty($blogs)): ?>
<section class="section bg-light">
    <div class="section-container">
        <div class="section-header">
            <span class="section-subtitle"><?= __('home.blog_subtitle') ?></span>
            <h2 class="section-title"><?= __('home.blog_title') ?></h2>
        </div>

        <div class="blog-grid">
            <?php foreach (array_slice($blogs, 0, 3) as $post): ?>
            <div class="blog-card animate-on-scroll">
                <div class="blog-card-image">
                    <?php if (!empty($post->featured_image)): ?>
                        <img src="<?= $baseUrl ?>/storage/uploads/<?= htmlspecialchars($post->featured_image) ?>" alt="<?= htmlspecialchars(langField($post, 'title')) ?>">
                    <?php else: ?>
                        <img src="<?= $assetUrl ?>/images/gallery-1.jpg" alt="<?= htmlspecialchars(langField($post, 'title')) ?>">
                    <?php endif; ?>
                    <a href="<?= $baseUrl ?>/blog/<?= htmlspecialchars($post->slug) ?>" class="blog-card-link"></a>
                </div>
                <div class="blog-card-body">
                    <div class="blog-card-date">
                        <a href="<?= $baseUrl ?>/blog/<?= htmlspecialchars($post->slug) ?>">
                            <b><?= date('d', strtotime($post->created_at)) ?></b> <?= date('M', strtotime($post->created_at)) ?>
                        </a>
                    </div>
                    <span class="blog-card-category"><?= __('blog.spiritual') ?></span>
                    <h5 class="blog-card-title"><a href="<?= $baseUrl ?>/blog/<?= htmlspecialchars($post->slug) ?>"><?= htmlspecialchars(langField($post, 'title')) ?></a></h5>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center" style="margin-top: 3rem;">
            <a href="<?= $baseUrl ?>/blog" class="btn btn-primary"><?= __('btn.view_all_articles') ?></a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Contact Form Section -->
<section class="section contact-section" style="background-image: url('<?= $assetUrl ?>/images/hero-slider-2.jpg'); background-size: cover; background-position: center;">
    <div class="section-container">
        <div class="contact-row">
            <div class="contact-spacer"></div>
            <div class="contact-form-card animate-on-scroll">
                <span class="section-subtitle"><?= __('home.contact_subtitle') ?></span>
                <h2 class="section-title" style="text-align:left;"><?= __('home.contact_title') ?><br><?= __('home.contact_title2') ?></h2>

                <form id="srisai-contact-form" class="contact-form" method="POST">
                    <div class="form-row-group">
                        <div class="form-row">
                            <input type="text" name="name" placeholder="<?= htmlspecialchars(__('home.contact_name_ph')) ?>" required>
                        </div>
                        <div class="form-row">
                            <input type="text" name="last_name" placeholder="<?= htmlspecialchars(__('home.contact_lastname_ph')) ?>">
                        </div>
                    </div>
                    <div class="form-row-group">
                        <div class="form-row">
                            <input type="email" name="email" placeholder="<?= htmlspecialchars(__('home.contact_email_ph')) ?>" required>
                        </div>
                        <div class="form-row">
                            <input type="tel" name="phone" placeholder="<?= htmlspecialchars(__('home.contact_phone_ph')) ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <textarea name="message" placeholder="<?= htmlspecialchars(__('home.contact_message_ph')) ?>" rows="5" required></textarea>
                    </div>
                    <div class="form-row">
                        <button type="submit" class="btn btn-accent"><?= __('btn.send_message') ?></button>
                    </div>
                    <div class="form-message"></div>
                </form>
            </div>
        </div>
    </div>
</section>
