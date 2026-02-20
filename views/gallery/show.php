<!-- Page Header -->
<div class="srisai-page-header">
    <div class="srisai-container">
        <div class="srisai-breadcrumb">
            <a href="<?= $baseUrl ?>/"><?= __('nav.home') ?></a> / <a href="<?= $baseUrl ?>/gallery"><?= __('nav.gallery') ?></a> / <span><?= htmlspecialchars(langField($album, 'title')) ?></span>
        </div>
        <h1><?= htmlspecialchars(langField($album, 'title')) ?></h1>
        <?php if (!empty($album->description)): ?>
            <p><?= htmlspecialchars(langField($album, 'description')) ?></p>
        <?php endif; ?>
    </div>
</div>

<!-- Album Images -->
<div class="srisai-section srisai-section--white">
    <div class="srisai-container">
        <?php if (empty($images)): ?>
            <div class="srisai-empty">
                <p><?= __('gallery.album_empty') ?></p>
            </div>
        <?php else: ?>
            <div class="srisai-image-grid">
                <?php foreach ($images as $image): ?>
                    <a href="<?= $baseUrl ?>/storage/uploads/<?= htmlspecialchars($image->file_path) ?>" class="gallery-item" data-lightbox="album-<?= $album->id ?>">
                        <img src="<?= $baseUrl ?>/storage/uploads/<?= htmlspecialchars($image->file_path) ?>" alt="<?= htmlspecialchars($image->caption ?? langField($album, 'title')) ?>">
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div style="margin-top:40px; text-align:center;">
            <a href="<?= $baseUrl ?>/gallery" class="srisai-btn srisai-btn--outline"><?= __('btn.back_gallery') ?></a>
        </div>
    </div>
</div>
