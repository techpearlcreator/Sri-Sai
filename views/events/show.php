<!-- Page Header -->
<div class="srisai-page-header">
    <div class="srisai-container">
        <div class="srisai-breadcrumb">
            <a href="<?= $baseUrl ?>/">Home</a> / <a href="<?= $baseUrl ?>/events">Events</a> / <span><?= htmlspecialchars($event->title) ?></span>
        </div>
        <h1><?= htmlspecialchars($event->title) ?></h1>
    </div>
</div>

<!-- Event Details -->
<div class="srisai-section srisai-section--white">
    <div class="srisai-container">
        <div style="max-width:900px; margin:0 auto;">
            <article class="srisai-article">
                <div class="srisai-article__header">
                    <div class="srisai-article__meta">
                        <?php if (!empty($event->event_date)): ?>
                            <span>📅 <?= date('F d, Y', strtotime($event->event_date)) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($event->event_time)): ?>
                            <span>⏰ <?= htmlspecialchars($event->event_time) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($event->location)): ?>
                            <span>📍 <?= htmlspecialchars($event->location) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (!empty($event->featured_image)): ?>
                    <div class="srisai-article__image">
                        <img src="<?= $baseUrl ?>/storage/uploads/<?= htmlspecialchars($event->featured_image) ?>" alt="<?= htmlspecialchars($event->title) ?>">
                    </div>
                <?php endif; ?>

                <div class="srisai-article__content">
                    <?= $event->description ?? '' ?>
                </div>

                <div class="srisai-article__nav">
                    <a href="<?= $baseUrl ?>/events" class="srisai-btn srisai-btn--outline">← Back to Events</a>
                </div>
            </article>
        </div>
    </div>
</div>
