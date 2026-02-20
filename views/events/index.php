<!-- Page Header -->
<div class="srisai-page-header">
    <div class="srisai-container">
        <h1><?= __('events.title') ?></h1>
        <p><?= __('events.subtitle') ?></p>
    </div>
</div>

<!-- Events Listing -->
<div class="srisai-section srisai-section--light">
    <div class="srisai-container">
        <?php if (empty($events)): ?>
            <div class="srisai-empty">
                <p><?= __('events.empty') ?></p>
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
                            <h4><?= htmlspecialchars(langField($event, 'title')) ?></h4>
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

            <?php if (!empty($pagination) && $pagination['total_pages'] > 1): ?>
                <div class="srisai-pagination">
                    <?php if ($pagination['page'] > 1): ?>
                        <a href="?page=<?= $pagination['page'] - 1 ?>" class="srisai-btn srisai-btn--outline srisai-btn--sm">&laquo; <?= __('pagination.previous') ?></a>
                    <?php endif; ?>
                    <span class="srisai-pagination__info"><?= __('pagination.page_of', [$pagination['page'], $pagination['total_pages']]) ?></span>
                    <?php if ($pagination['page'] < $pagination['total_pages']): ?>
                        <a href="?page=<?= $pagination['page'] + 1 ?>" class="srisai-btn srisai-btn--outline srisai-btn--sm"><?= __('pagination.next') ?> &raquo;</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
