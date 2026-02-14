<!-- Page Header -->
<div class="srisai-page-header">
    <div class="srisai-container">
        <h1>Gallery</h1>
        <p>View our photo albums and memories</p>
    </div>
</div>

<!-- Gallery Listing -->
<div class="srisai-section srisai-section--light">
    <div class="srisai-container">
        <?php if (empty($albums)): ?>
            <div class="srisai-empty">
                <p>No albums available at this time.</p>
            </div>
        <?php else: ?>
            <div class="srisai-gallery-grid">
                <?php foreach ($albums as $album): ?>
                    <a href="<?= $baseUrl ?>/gallery/<?= htmlspecialchars($album->slug) ?>" class="srisai-gallery-card">
                        <div class="srisai-gallery-card__image">
                            <?php if (!empty($album->cover_image)): ?>
                                <img src="<?= $assetUrl ?>/uploads/<?= htmlspecialchars($album->cover_image) ?>" alt="<?= htmlspecialchars($album->title) ?>">
                            <?php else: ?>
                                <div class="srisai-gallery-card__image--placeholder">No Image</div>
                            <?php endif; ?>
                        </div>
                        <div class="srisai-gallery-card__body">
                            <h4><?= htmlspecialchars($album->title) ?></h4>
                            <p><?= htmlspecialchars($album->image_count ?? 0) ?> photos</p>
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
