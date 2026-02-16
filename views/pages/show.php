<!-- Page Header -->
<div class="srisai-page-header">
    <div class="srisai-container">
        <h1><?= htmlspecialchars($page->title) ?></h1>
    </div>
</div>

<!-- Page Content -->
<div class="srisai-section srisai-section--white">
    <div class="srisai-container">
        <div style="max-width:900px; margin:0 auto;">
            <article class="srisai-article">
                <?php if (!empty($page->featured_image)): ?>
                    <div class="srisai-article__image">
                        <img src="<?= $baseUrl ?>/storage/uploads/<?= htmlspecialchars($page->featured_image) ?>" alt="<?= htmlspecialchars($page->title) ?>">
                    </div>
                <?php endif; ?>

                <div class="srisai-article__content">
                    <?= $page->content ?? '' ?>
                </div>
            </article>
        </div>
    </div>
</div>
