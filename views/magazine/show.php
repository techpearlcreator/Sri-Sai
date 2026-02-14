<section class="srisai-page-header">
    <div class="srisai-container">
        <nav class="srisai-breadcrumb">
            <a href="<?= $baseUrl ?>">Home</a> &rsaquo; <a href="<?= $baseUrl ?>/magazine">Magazine</a> &rsaquo; <span><?= htmlspecialchars($magazine->title) ?></span>
        </nav>
    </div>
</section>

<section class="srisai-section srisai-section--white">
    <div class="srisai-container srisai-content-narrow">
        <article class="srisai-article">
            <header class="srisai-article__header">
                <h1><?= htmlspecialchars($magazine->title) ?></h1>
                <div class="srisai-article__meta">
                    <?php if (!empty($magazine->issue_number)): ?><span>Issue #<?= htmlspecialchars($magazine->issue_number) ?></span><?php endif; ?>
                    <span><?= htmlspecialchars(($magazine->issue_month ?? '') . ' ' . ($magazine->issue_year ?? '')) ?></span>
                </div>
            </header>

            <?php if (!empty($magazine->cover_image)): ?>
            <div class="srisai-article__image"><img src="<?= $baseUrl ?>/storage/uploads/<?= htmlspecialchars($magazine->cover_image) ?>" alt="<?= htmlspecialchars($magazine->title) ?>"></div>
            <?php endif; ?>

            <?php if (!empty($magazine->pdf_file)): ?>
            <div style="margin: 1.5rem 0;">
                <a href="<?= $baseUrl ?>/storage/uploads/<?= htmlspecialchars($magazine->pdf_file) ?>" class="srisai-btn srisai-btn--primary" target="_blank">Download PDF</a>
            </div>
            <?php endif; ?>

            <div class="srisai-article__content"><?= $magazine->description ?? $magazine->content ?? '' ?></div>
        </article>
        <div class="srisai-article__nav"><a href="<?= $baseUrl ?>/magazine" class="srisai-btn srisai-btn--outline">&laquo; All Issues</a></div>
    </div>
</section>
