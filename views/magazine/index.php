<section class="srisai-page-header">
    <div class="srisai-container">
        <h1>Sri Sai Dharisanam</h1>
        <p>Our monthly spiritual magazine</p>
    </div>
</section>

<section class="srisai-section srisai-section--white">
    <div class="srisai-container">
        <?php if (empty($magazines)): ?>
            <p class="srisai-empty">No magazine issues available yet.</p>
        <?php else: ?>
            <div class="srisai-magazine-grid">
                <?php foreach ($magazines as $mag): ?>
                <a href="<?= $baseUrl ?>/magazine/<?= htmlspecialchars($mag->slug) ?>" class="srisai-magazine-card">
                    <?php if (!empty($mag->cover_image)): ?>
                    <div class="srisai-magazine-card__cover"><img src="<?= $baseUrl ?>/storage/uploads/<?= htmlspecialchars($mag->cover_image) ?>" alt="<?= htmlspecialchars($mag->title) ?>"></div>
                    <?php else: ?>
                    <div class="srisai-magazine-card__cover srisai-magazine-card__cover--placeholder"><span>Issue <?= htmlspecialchars($mag->issue_number ?? '') ?></span></div>
                    <?php endif; ?>
                    <div class="srisai-magazine-card__info">
                        <h4><?= htmlspecialchars($mag->title) ?></h4>
                        <p><?= htmlspecialchars(($mag->issue_month ?? '') . ' ' . ($mag->issue_year ?? '')) ?></p>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>

            <?php if (!empty($pagination) && $pagination['total_pages'] > 1): ?>
            <div class="srisai-pagination">
                <?php if ($pagination['page'] > 1): ?><a href="?page=<?= $pagination['page'] - 1 ?>" class="srisai-btn srisai-btn--outline srisai-btn--sm">&laquo; Prev</a><?php endif; ?>
                <span class="srisai-pagination__info">Page <?= $pagination['page'] ?> of <?= $pagination['total_pages'] ?></span>
                <?php if ($pagination['page'] < $pagination['total_pages']): ?><a href="?page=<?= $pagination['page'] + 1 ?>" class="srisai-btn srisai-btn--outline srisai-btn--sm">Next &raquo;</a><?php endif; ?>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>
