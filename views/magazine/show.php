<section class="srisai-page-header">
    <div class="srisai-container">
        <nav class="srisai-breadcrumb">
            <a href="<?= $baseUrl ?>"><?= __('nav.home') ?></a> &rsaquo; <a href="<?= $baseUrl ?>/magazine"><?= __('nav.magazine') ?></a> &rsaquo; <span><?= htmlspecialchars(langField($magazine, 'title')) ?></span>
        </nav>
    </div>
</section>

<section class="srisai-section srisai-section--white">
    <div class="srisai-container srisai-content-narrow">
        <article class="srisai-article">
            <header class="srisai-article__header">
                <h1><?= htmlspecialchars(langField($magazine, 'title')) ?></h1>
                <div class="srisai-article__meta">
                    <?php if (!empty($magazine->issue_number)): ?><span><?= __('magazine.issue_hash') ?><?= htmlspecialchars($magazine->issue_number) ?></span><?php endif; ?>
                    <span><?= !empty($magazine->issue_date) ? date('F Y', strtotime($magazine->issue_date)) : '' ?></span>
                </div>
            </header>

            <?php if (!empty($magazine->featured_image)): ?>
            <div class="srisai-article__image"><img src="<?= $baseUrl ?>/storage/uploads/<?= htmlspecialchars($magazine->featured_image) ?>" alt="<?= htmlspecialchars(langField($magazine, 'title')) ?>"></div>
            <?php endif; ?>

            <?php if (!empty($magazine->pdf_file)): ?>
            <div style="margin: 1.5rem 0;">
                <a href="<?= $baseUrl ?>/storage/uploads/<?= htmlspecialchars($magazine->pdf_file) ?>" class="srisai-btn srisai-btn--primary" target="_blank"><?= __('btn.download_pdf') ?></a>
            </div>
            <?php endif; ?>

            <div class="srisai-article__content"><?= langField($magazine, 'excerpt') ?: langField($magazine, 'content') ?></div>
        </article>
        <div class="srisai-article__nav"><a href="<?= $baseUrl ?>/magazine" class="srisai-btn srisai-btn--outline"><?= __('btn.all_issues') ?></a></div>
    </div>
</section>
