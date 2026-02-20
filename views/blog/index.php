<!-- Page Header -->
<section class="srisai-page-header">
    <div class="srisai-container">
        <h1><?= __('blog.title') ?></h1>
        <p><?= __('blog.subtitle') ?></p>
    </div>
</section>

<section class="srisai-section srisai-section--white">
    <div class="srisai-container">
        <?php if (empty($blogs)): ?>
            <p class="srisai-empty"><?= __('blog.empty') ?></p>
        <?php else: ?>
            <div class="srisai-blog-grid">
                <?php foreach ($blogs as $post): ?>
                <a href="<?= $baseUrl ?>/blog/<?= htmlspecialchars($post->slug) ?>" class="srisai-blog-card">
                    <?php if (!empty($post->featured_image)): ?>
                    <div class="srisai-blog-card__image"><img src="<?= $baseUrl ?>/storage/uploads/<?= htmlspecialchars($post->featured_image) ?>" alt="<?= htmlspecialchars(langField($post, 'title')) ?>"></div>
                    <?php else: ?>
                    <div class="srisai-blog-card__image srisai-blog-card__image--placeholder"></div>
                    <?php endif; ?>
                    <div class="srisai-blog-card__body">
                        <span class="srisai-blog-card__date"><?= date('M d, Y', strtotime($post->created_at)) ?></span>
                        <h4><?= htmlspecialchars(langField($post, 'title')) ?></h4>
                        <p><?= htmlspecialchars(langField($post, 'excerpt') ?: mb_substr(strip_tags(langField($post, 'content') ?? ''), 0, 150)) ?>...</p>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>

            <?php if (!empty($pagination) && $pagination['total_pages'] > 1): ?>
            <div class="srisai-pagination">
                <?php if ($pagination['page'] > 1): ?>
                <a href="?page=<?= $pagination['page'] - 1 ?>" class="srisai-btn srisai-btn--outline srisai-btn--sm">&laquo; <?= __('pagination.previous') ?></a>
                <?php endif; ?>
                <span class="srisai-pagination__info"><?= __('pagination.page_of', [$pagination['page'], $pagination['total_pages']]) ?></span>
                <?php if ($pagination['page'] < $pagination['total_pages']): ?>
                <a href="?page=<?= $pagination['page'] + 1 ?>" class="srisai-btn srisai-btn--outline srisai-btn--sm"><?= __('pagination.next') ?> &raquo;</a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>
