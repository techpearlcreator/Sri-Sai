<!-- Page Header -->
<div class="srisai-page-header">
    <div class="srisai-container">
        <h1><?= __('tours.title') ?></h1>
        <p><?= __('tours.subtitle') ?></p>
    </div>
</div>

<!-- Tours Listing -->
<div class="srisai-section srisai-section--light">
    <div class="srisai-container">
        <?php if (empty($tours)): ?>
            <div class="srisai-empty">
                <p><?= __('tours.empty') ?></p>
            </div>
        <?php else: ?>
            <div class="srisai-tours-grid">
                <?php foreach ($tours as $tour): ?>
                    <?php
                        $seatsLeft = (int) $tour->max_seats - (int) $tour->booked_seats;
                        $startDate = strtotime($tour->start_date);
                        $endDate = strtotime($tour->end_date);
                    ?>
                    <div class="srisai-tour-card">
                        <div class="srisai-tour-card__image">
                            <?php if (!empty($tour->featured_image)): ?>
                                <img src="<?= $baseUrl ?>/storage/uploads/<?= htmlspecialchars($tour->featured_image) ?>" alt="<?= htmlspecialchars(langField($tour, 'title')) ?>">
                            <?php else: ?>
                                <div class="srisai-tour-card__placeholder">🏛️</div>
                            <?php endif; ?>
                            <span class="srisai-tour-card__status srisai-tour-card__status--<?= $tour->status ?>"><?= __('tours.status_' . $tour->status) ?></span>
                        </div>
                        <div class="srisai-tour-card__body">
                            <h4><a href="<?= $baseUrl ?>/tours/<?= htmlspecialchars($tour->slug) ?>"><?= htmlspecialchars(langField($tour, 'title')) ?></a></h4>
                            <div class="srisai-tour-card__meta">
                                <p>📍 <?= htmlspecialchars(langField($tour, 'destination')) ?></p>
                                <p>📅 <?= date('M d', $startDate) ?> – <?= date('M d, Y', $endDate) ?></p>
                                <p>💺 <?= $seatsLeft ?> <?= __('tours.seats_left') ?> <?= (int) $tour->max_seats ?></p>
                            </div>
                            <div class="srisai-tour-card__footer">
                                <span class="srisai-tour-card__price">Rs.<?= number_format($tour->price_per_person, 2) ?> <small><?= __('tours.price_per_person') ?></small></span>
                                <?php if ($seatsLeft > 0 && $tour->status === 'upcoming'): ?>
                                    <a href="<?= $baseUrl ?>/tours/<?= htmlspecialchars($tour->slug) ?>" class="srisai-btn srisai-btn--primary srisai-btn--sm"><?= __('btn.book_now') ?></a>
                                <?php else: ?>
                                    <span class="srisai-badge srisai-badge--muted"><?= $tour->status === 'upcoming' ? __('tours.fully_booked') : __('tours.status_' . $tour->status) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if (!empty($pagination) && $pagination['total_pages'] > 1): ?>
                <div class="srisai-pagination">
                    <?php if ($pagination['page'] > 1): ?>
                        <a href="?page=<?= $pagination['page'] - 1 ?>" class="srisai-btn srisai-btn--outline srisai-btn--sm">← <?= __('pagination.previous') ?></a>
                    <?php endif; ?>
                    <span class="srisai-pagination__info"><?= sprintf(__('pagination.page_of'), $pagination['page'], $pagination['total_pages']) ?></span>
                    <?php if ($pagination['page'] < $pagination['total_pages']): ?>
                        <a href="?page=<?= $pagination['page'] + 1 ?>" class="srisai-btn srisai-btn--outline srisai-btn--sm"><?= __('pagination.next') ?> →</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
