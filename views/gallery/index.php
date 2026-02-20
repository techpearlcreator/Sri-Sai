<!-- Page Header -->
<div class="srisai-page-header">
    <div class="srisai-container">
        <h1><?= __('gallery.title') ?></h1>
        <p><?= __('gallery.subtitle') ?></p>
    </div>
</div>

<!-- Gallery Listing -->
<div class="srisai-section srisai-section--light">
    <div class="srisai-container">
        <?php if (empty($albums)): ?>
            <div class="srisai-empty">
                <p><?= __('gallery.empty') ?></p>
            </div>
        <?php else: ?>
            <div class="srisai-gallery-grid">
                <?php foreach ($albums as $album): ?>
                    <a href="<?= $baseUrl ?>/gallery/<?= htmlspecialchars($album->slug) ?>" class="srisai-gallery-card">
                        <div class="srisai-gallery-card__image">
                            <?php if (!empty($album->cover_image)): ?>
                                <img src="<?= $baseUrl ?>/storage/uploads/<?= htmlspecialchars($album->cover_image) ?>" alt="<?= htmlspecialchars(langField($album, 'title')) ?>">
                            <?php else: ?>
                                <div class="srisai-gallery-card__image--placeholder"><?= __('gallery.no_image') ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="srisai-gallery-card__body">
                            <h4><?= htmlspecialchars(langField($album, 'title')) ?></h4>
                            <p><?= __('gallery.photos', [$album->image_count ?? 0]) ?></p>
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
