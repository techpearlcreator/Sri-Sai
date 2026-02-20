<section class="srisai-page-header">
    <div class="srisai-container">
        <h1><?= __('magazine.title') ?></h1>
        <p><?= __('magazine.subtitle') ?></p>
    </div>
</section>

<section class="srisai-section srisai-section--white">
    <div class="srisai-container">
        <?php if (empty($magazines)): ?>
            <p class="srisai-empty"><?= __('magazine.empty') ?></p>
        <?php else: ?>
            <div class="srisai-magazine-grid">
                <?php foreach ($magazines as $mag): ?>
                <a href="<?= $baseUrl ?>/magazine/<?= htmlspecialchars($mag->slug) ?>" class="srisai-magazine-card">
                    <?php if (!empty($mag->featured_image)): ?>
                    <div class="srisai-magazine-card__cover"><img src="<?= $baseUrl ?>/storage/uploads/<?= htmlspecialchars($mag->featured_image) ?>" alt="<?= htmlspecialchars(langField($mag, 'title')) ?>"></div>
                    <?php else: ?>
                    <div class="srisai-magazine-card__cover srisai-magazine-card__cover--placeholder"><span><?= __('magazine.issue') ?> <?= htmlspecialchars($mag->issue_number ?? '') ?></span></div>
                    <?php endif; ?>
                    <div class="srisai-magazine-card__info">
                        <h4><?= htmlspecialchars(langField($mag, 'title')) ?></h4>
                        <p><?= !empty($mag->issue_date) ? date('F Y', strtotime($mag->issue_date)) : '' ?></p>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>

            <?php if (!empty($pagination) && $pagination['total_pages'] > 1): ?>
            <div class="srisai-pagination">
                <?php if ($pagination['page'] > 1): ?><a href="?page=<?= $pagination['page'] - 1 ?>" class="srisai-btn srisai-btn--outline srisai-btn--sm">&laquo; <?= __('pagination.previous') ?></a><?php endif; ?>
                <span class="srisai-pagination__info"><?= __('pagination.page_of', [$pagination['page'], $pagination['total_pages']]) ?></span>
                <?php if ($pagination['page'] < $pagination['total_pages']): ?><a href="?page=<?= $pagination['page'] + 1 ?>" class="srisai-btn srisai-btn--outline srisai-btn--sm"><?= __('pagination.next') ?> &raquo;</a><?php endif; ?>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>
