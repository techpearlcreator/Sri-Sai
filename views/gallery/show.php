<!-- Page Header -->
<div class="srisai-page-header">
    <div class="srisai-container">
        <div class="srisai-breadcrumb">
            <a href="<?= $baseUrl ?>/">Home</a> / <a href="<?= $baseUrl ?>/gallery">Gallery</a> / <span><?= htmlspecialchars($album->title) ?></span>
        </div>
        <h1><?= htmlspecialchars($album->title) ?></h1>
        <?php if (!empty($album->description)): ?>
            <p><?= htmlspecialchars($album->description) ?></p>
        <?php endif; ?>
    </div>
</div>

<!-- Album Images -->
<div class="srisai-section srisai-section--white">
    <div class="srisai-container">
        <?php if (empty($images)): ?>
            <div class="srisai-empty">
                <p>No images in this album yet.</p>
            </div>
        <?php else: ?>
            <div class="srisai-image-grid">
                <?php foreach ($images as $image): ?>
                    <a href="<?= $assetUrl ?>/uploads/<?= htmlspecialchars($image->image_path) ?>" class="gallery-item" data-lightbox="album-<?= $album->id ?>">
                        <img src="<?= $assetUrl ?>/uploads/<?= htmlspecialchars($image->image_path) ?>" alt="<?= htmlspecialchars($image->caption ?? $album->title) ?>">
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div style="margin-top:40px; text-align:center;">
            <a href="<?= $baseUrl ?>/gallery" class="srisai-btn srisai-btn--outline">← Back to Gallery</a>
        </div>
    </div>
</div>
