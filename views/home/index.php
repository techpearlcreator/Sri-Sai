<!-- Hero Section -->
<section class="srisai-hero" style="background-image: url('<?= $assetUrl ?>/images/image-6-copyright.jpg');">
    <div class="srisai-hero__overlay"></div>
    <div class="srisai-container srisai-hero__content">
        <h1 class="srisai-hero__title">Sri Sai Mission</h1>
        <p class="srisai-hero__subtitle">Religious & Charitable Trust</p>
        <p class="srisai-hero__desc">Serving humanity through Annadhanam, Temple Worship, Education & Spiritual Guidance</p>
        <div class="srisai-hero__buttons">
            <a href="<?= $baseUrl ?>/donations" class="srisai-btn srisai-btn--primary">Donate Now</a>
            <a href="<?= $baseUrl ?>/about" class="srisai-btn srisai-btn--outline">Learn More</a>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="srisai-section srisai-section--white" id="about">
    <div class="srisai-container">
        <div class="srisai-section__header">
            <span class="srisai-section__label">About Us</span>
            <h2 class="srisai-section__title">Sri Sai Mission Religious & Charitable Trust</h2>
            <p class="srisai-section__desc">Registered under Trust Act 106/2014, Chennai — serving the community through spiritual activities and charitable works.</p>
        </div>
        <?php if (!empty($aboutPage)): ?>
        <div class="srisai-about-content"><?= $aboutPage->content ?></div>
        <?php endif; ?>
    </div>
</section>

<!-- Activities / Services -->
<section class="srisai-section srisai-section--light">
    <div class="srisai-container">
        <div class="srisai-section__header">
            <span class="srisai-section__label">What We Do</span>
            <h2 class="srisai-section__title">Our Activities</h2>
        </div>
        <div class="srisai-services-grid">
            <div class="srisai-service-card">
                <div class="srisai-service-card__icon">&#x1F64F;</div>
                <h3>Annadhanam</h3>
                <p>Free food distribution to devotees and the needy as a sacred act of service.</p>
            </div>
            <div class="srisai-service-card">
                <div class="srisai-service-card__icon">&#x1F6D5;</div>
                <h3>Temple Pooja</h3>
                <p>Daily worship at Athma Sai Temple (Perungalathur) and Baba Temple (Keerapakkam).</p>
            </div>
            <div class="srisai-service-card">
                <div class="srisai-service-card__icon">&#x1F404;</div>
                <h3>Cow Saala</h3>
                <p>Maintaining a cow shelter (Gosala) as part of our dharmic duty.</p>
            </div>
            <div class="srisai-service-card">
                <div class="srisai-service-card__icon">&#x1F3B6;</div>
                <h3>Spiritual Events</h3>
                <p>Festivals, bhajans, satsangs, and pilgrimage tours for devotees.</p>
            </div>
            <div class="srisai-service-card">
                <div class="srisai-service-card__icon">&#x1F3D4;</div>
                <h3>Temple Tourism</h3>
                <p>Pilgrim tours to sacred temples across India for spiritual enrichment.</p>
            </div>
            <div class="srisai-service-card">
                <div class="srisai-service-card__icon">&#x1F4D6;</div>
                <h3>Sri Sai Dharisanam</h3>
                <p>Monthly magazine sharing spiritual wisdom, trust news, and devotee stories.</p>
            </div>
        </div>
    </div>
</section>

<!-- Temples Section -->
<section class="srisai-section srisai-section--dark">
    <div class="srisai-container">
        <div class="srisai-section__header srisai-section__header--light">
            <span class="srisai-section__label">Our Temples</span>
            <h2 class="srisai-section__title">Sacred Places of Worship</h2>
        </div>
        <div class="srisai-temples-grid">
            <div class="srisai-temple-card">
                <div class="srisai-temple-card__image"><img src="<?= $assetUrl ?>/images/image-44-copyright.jpg" alt="Athma Sai Temple"></div>
                <div class="srisai-temple-card__body">
                    <h3>Perungalathur Athma Sai Temple</h3>
                    <p>Our main temple dedicated to Sai Baba, conducting daily pooja and special event celebrations.</p>
                </div>
            </div>
            <div class="srisai-temple-card">
                <div class="srisai-temple-card__image"><img src="<?= $assetUrl ?>/images/image-13-copyright.jpg" alt="Keerapakkam Baba Temple"></div>
                <div class="srisai-temple-card__body">
                    <h3>Keerapakkam Baba Temple</h3>
                    <p>A serene temple in Keerapakkam village dedicated to Shirdi Sai Baba.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Temple Timings -->
<?php if (!empty($timings)): ?>
<section class="srisai-section srisai-section--white">
    <div class="srisai-container">
        <div class="srisai-section__header">
            <span class="srisai-section__label">Darshan</span>
            <h2 class="srisai-section__title">Temple Timings</h2>
        </div>
        <div class="srisai-timings-table">
            <table>
                <thead><tr><th>Pooja / Event</th><th>Day</th><th>Time</th><th>Temple</th></tr></thead>
                <tbody>
                <?php foreach ($timings as $t): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($t->pooja_name) ?></strong></td>
                        <td><?= ucfirst(str_replace('_', ' ', $t->day_type)) ?></td>
                        <td><?= htmlspecialchars($t->start_time) ?><?= !empty($t->end_time) ? ' – ' . htmlspecialchars($t->end_time) : '' ?></td>
                        <td><?= htmlspecialchars($t->location ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Donation CTA -->
<section class="srisai-section srisai-cta" style="background-image: url('<?= $assetUrl ?>/images/image-6-copyright.jpg');">
    <div class="srisai-cta__overlay"></div>
    <div class="srisai-container srisai-cta__content">
        <h2>Support Our Mission</h2>
        <p>Your contributions help us serve Annadhanam to thousands, maintain our temples, and organize spiritual events.</p>
        <p><strong>Tax exemption under Section 80G available.</strong></p>
        <a href="<?= $baseUrl ?>/donations" class="srisai-btn srisai-btn--primary">Donate Now</a>
    </div>
</section>

<!-- Trustees -->
<?php if (!empty($trustees)): ?>
<section class="srisai-section srisai-section--white">
    <div class="srisai-container">
        <div class="srisai-section__header">
            <span class="srisai-section__label">Leadership</span>
            <h2 class="srisai-section__title">Our Trustees</h2>
        </div>
        <div class="srisai-trustees-grid">
            <?php foreach (array_slice($trustees, 0, 6) as $tr): ?>
            <div class="srisai-trustee-card">
                <?php if (!empty($tr->photo)): ?>
                <div class="srisai-trustee-card__photo"><img src="<?= $baseUrl ?>/storage/uploads/<?= htmlspecialchars($tr->photo) ?>" alt="<?= htmlspecialchars($tr->name) ?>"></div>
                <?php else: ?>
                <div class="srisai-trustee-card__photo srisai-trustee-card__photo--placeholder"><span><?= strtoupper(substr($tr->name, 0, 1)) ?></span></div>
                <?php endif; ?>
                <h4><?= htmlspecialchars($tr->name) ?></h4>
                <p class="srisai-trustee-card__role"><?= htmlspecialchars($tr->designation ?? '') ?></p>
            </div>
            <?php endforeach; ?>
        </div>
        <div style="text-align:center; margin-top:2rem;">
            <a href="<?= $baseUrl ?>/trustees" class="srisai-btn srisai-btn--outline">View All Trustees</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Events -->
<?php if (!empty($events)): ?>
<section class="srisai-section srisai-section--light">
    <div class="srisai-container">
        <div class="srisai-section__header">
            <span class="srisai-section__label">Upcoming</span>
            <h2 class="srisai-section__title">Events & Programs</h2>
        </div>
        <div class="srisai-events-grid">
            <?php foreach ($events as $ev): ?>
            <a href="<?= $baseUrl ?>/events/<?= htmlspecialchars($ev->slug) ?>" class="srisai-event-card">
                <div class="srisai-event-card__date">
                    <span class="srisai-event-card__day"><?= date('d', strtotime($ev->event_date)) ?></span>
                    <span class="srisai-event-card__month"><?= date('M', strtotime($ev->event_date)) ?></span>
                </div>
                <div class="srisai-event-card__info">
                    <h4><?= htmlspecialchars($ev->title) ?></h4>
                    <p><?= htmlspecialchars($ev->location ?? '') ?></p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <div style="text-align:center; margin-top:2rem;">
            <a href="<?= $baseUrl ?>/events" class="srisai-btn srisai-btn--outline">All Events</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Blog -->
<?php if (!empty($blogs)): ?>
<section class="srisai-section srisai-section--white">
    <div class="srisai-container">
        <div class="srisai-section__header">
            <span class="srisai-section__label">Latest News</span>
            <h2 class="srisai-section__title">From Our Blog</h2>
        </div>
        <div class="srisai-blog-grid">
            <?php foreach ($blogs as $post): ?>
            <a href="<?= $baseUrl ?>/blog/<?= htmlspecialchars($post->slug) ?>" class="srisai-blog-card">
                <?php if (!empty($post->featured_image)): ?>
                <div class="srisai-blog-card__image"><img src="<?= $baseUrl ?>/storage/uploads/<?= htmlspecialchars($post->featured_image) ?>" alt="<?= htmlspecialchars($post->title) ?>"></div>
                <?php else: ?>
                <div class="srisai-blog-card__image srisai-blog-card__image--placeholder"></div>
                <?php endif; ?>
                <div class="srisai-blog-card__body">
                    <span class="srisai-blog-card__date"><?= date('M d, Y', strtotime($post->created_at)) ?></span>
                    <h4><?= htmlspecialchars($post->title) ?></h4>
                    <p><?= htmlspecialchars($post->excerpt ?? mb_substr(strip_tags($post->content ?? ''), 0, 120)) ?>...</p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <div style="text-align:center; margin-top:2rem;">
            <a href="<?= $baseUrl ?>/blog" class="srisai-btn srisai-btn--outline">View All Posts</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Contact CTA -->
<section class="srisai-section srisai-section--dark" style="text-align:center;">
    <div class="srisai-container">
        <h2 style="color:#fff; margin-bottom:1rem;">Get In Touch</h2>
        <p style="color:rgba(255,255,255,0.8); max-width:600px; margin:0 auto 2rem;">Have questions about our activities or want to get involved? We'd love to hear from you.</p>
        <a href="<?= $baseUrl ?>/contact" class="srisai-btn srisai-btn--primary">Contact Us</a>
    </div>
</section>
