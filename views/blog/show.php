<!-- Page Header -->
<section class="srisai-page-header">
    <div class="srisai-container">
        <nav class="srisai-breadcrumb">
            <a href="<?= $baseUrl ?>"><?= __('nav.home') ?></a> &rsaquo; <a href="<?= $baseUrl ?>/blog"><?= __('nav.blog') ?></a> &rsaquo; <span><?= htmlspecialchars(langField($blog, 'title')) ?></span>
        </nav>
    </div>
</section>

<section class="srisai-section srisai-section--white">
    <div class="srisai-container srisai-content-narrow">
        <article class="srisai-article">
            <header class="srisai-article__header">
                <h1><?= htmlspecialchars(langField($blog, 'title')) ?></h1>
                <div class="srisai-article__meta">
                    <span><?= date('F d, Y', strtotime($blog->created_at)) ?></span>
                    <?php if (!empty($blog->category_name)): ?>
                    <span>&bull; <?= htmlspecialchars($blog->category_name) ?></span>
                    <?php endif; ?>
                    <span>&bull; <?= (int)($blog->view_count ?? 0) ?> <?= __('blog.views') ?></span>
                </div>
            </header>

            <?php if (!empty($blog->featured_image)): ?>
            <div class="srisai-article__image">
                <img src="<?= $baseUrl ?>/storage/uploads/<?= htmlspecialchars($blog->featured_image) ?>" alt="<?= htmlspecialchars(langField($blog, 'title')) ?>">
            </div>
            <?php endif; ?>

            <div class="srisai-article__content">
                <?= langField($blog, 'content') ?>
            </div>
        </article>

        <div class="srisai-article__nav">
            <a href="<?= $baseUrl ?>/blog" class="srisai-btn srisai-btn--outline"><?= __('btn.back_blog') ?></a>
        </div>
    </div>
</section>
