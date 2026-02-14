<!-- Page Header -->
<section class="srisai-page-header">
    <div class="srisai-container">
        <nav class="srisai-breadcrumb">
            <a href="<?= $baseUrl ?>">Home</a> &rsaquo; <a href="<?= $baseUrl ?>/blog">Blog</a> &rsaquo; <span><?= htmlspecialchars($blog->title) ?></span>
        </nav>
    </div>
</section>

<section class="srisai-section srisai-section--white">
    <div class="srisai-container srisai-content-narrow">
        <article class="srisai-article">
            <header class="srisai-article__header">
                <h1><?= htmlspecialchars($blog->title) ?></h1>
                <div class="srisai-article__meta">
                    <span><?= date('F d, Y', strtotime($blog->created_at)) ?></span>
                    <?php if (!empty($blog->category_name)): ?>
                    <span>&bull; <?= htmlspecialchars($blog->category_name) ?></span>
                    <?php endif; ?>
                    <span>&bull; <?= (int)($blog->view_count ?? 0) ?> views</span>
                </div>
            </header>

            <?php if (!empty($blog->featured_image)): ?>
            <div class="srisai-article__image">
                <img src="<?= $baseUrl ?>/storage/uploads/<?= htmlspecialchars($blog->featured_image) ?>" alt="<?= htmlspecialchars($blog->title) ?>">
            </div>
            <?php endif; ?>

            <div class="srisai-article__content">
                <?= $blog->content ?>
            </div>
        </article>

        <div class="srisai-article__nav">
            <a href="<?= $baseUrl ?>/blog" class="srisai-btn srisai-btn--outline">&laquo; Back to Blog</a>
        </div>
    </div>
</section>
