<!-- Page Header -->
<div class="srisai-page-header">
    <div class="srisai-container">
        <h1>Upcoming Events</h1>
        <p>Join us at our spiritual and charitable events</p>
    </div>
</div>

<!-- Events Listing -->
<div class="srisai-section srisai-section--light">
    <div class="srisai-container">
        <?php if (empty($events)): ?>
            <div class="srisai-empty">
                <p>No upcoming events at this time. Check back soon!</p>
            </div>
        <?php else: ?>
            <div class="srisai-events-grid">
                <?php foreach ($events as $event): ?>
                    <?php
                        $eventDate = strtotime($event->event_date ?? 'now');
                        $day = date('d', $eventDate);
                        $month = date('M', $eventDate);
                    ?>
                    <a href="<?= $baseUrl ?>/events/<?= htmlspecialchars($event->slug) ?>" class="srisai-event-card">
                        <div class="srisai-event-card__date">
                            <div class="srisai-event-card__day"><?= $day ?></div>
                            <div class="srisai-event-card__month"><?= $month ?></div>
                        </div>
                        <div class="srisai-event-card__info">
                            <h4><?= htmlspecialchars($event->title) ?></h4>
                            <p>
                                <?php if (!empty($event->event_time)): ?>
                                    <span>⏰ <?= htmlspecialchars($event->event_time) ?></span><br>
                                <?php endif; ?>
                                <?php if (!empty($event->location)): ?>
                                    <span>📍 <?= htmlspecialchars($event->location) ?></span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <?php if ($total > $perPage): ?>
                <div class="srisai-pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?= $page - 1 ?>" class="srisai-btn srisai-btn--outline srisai-btn--sm">← Previous</a>
                    <?php endif; ?>
                    <span class="srisai-pagination__info">Page <?= $page ?> of <?= ceil($total / $perPage) ?></span>
                    <?php if ($page < ceil($total / $perPage)): ?>
                        <a href="?page=<?= $page + 1 ?>" class="srisai-btn srisai-btn--outline srisai-btn--sm">Next →</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
