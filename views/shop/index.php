<!-- Page Header -->
<div class="srisai-page-header">
    <div class="srisai-container">
        <h1><?= __('shop.title') ?></h1>
        <p><?= __('shop.subtitle') ?></p>
    </div>
</div>

<!-- Shop Content -->
<div class="srisai-section srisai-section--light">
    <div class="srisai-container">
        <!-- Category Filter -->
        <div class="srisai-filter-bar">
            <a href="<?= $baseUrl ?>/shop" class="srisai-filter-btn<?= $activeCategory === 'all' ? ' active' : '' ?>"><?= __('shop.all') ?></a>
            <a href="<?= $baseUrl ?>/shop?category=book" class="srisai-filter-btn<?= $activeCategory === 'book' ? ' active' : '' ?>"><?= __('shop.books') ?></a>
            <a href="<?= $baseUrl ?>/shop?category=pooja_item" class="srisai-filter-btn<?= $activeCategory === 'pooja_item' ? ' active' : '' ?>"><?= __('shop.pooja_items') ?></a>
            <a href="<?= $baseUrl ?>/shop?category=other" class="srisai-filter-btn<?= $activeCategory === 'other' ? ' active' : '' ?>"><?= __('shop.other') ?></a>
            <a href="<?= $baseUrl ?>/pooja-booking" class="srisai-filter-btn srisai-filter-btn--highlight"><?= __('shop.book_pooja') ?></a>
        </div>

        <?php if (empty($products)): ?>
            <div class="srisai-empty">
                <p><?= __('shop.empty') ?></p>
            </div>
        <?php else: ?>
            <div class="srisai-shop-grid">
                <?php foreach ($products as $product): ?>
                    <div class="srisai-product-card">
                        <a href="<?= $baseUrl ?>/shop/<?= htmlspecialchars($product->slug) ?>" class="srisai-product-card__image">
                            <?php if (!empty($product->featured_image)): ?>
                                <img src="<?= $baseUrl ?>/storage/uploads/<?= htmlspecialchars($product->featured_image) ?>" alt="<?= htmlspecialchars(langField($product, 'name')) ?>">
                            <?php else: ?>
                                <div class="srisai-product-card__placeholder">
                                    <span><?= $product->category === 'book' ? '📖' : '🪔' ?></span>
                                </div>
                            <?php endif; ?>
                        </a>
                        <div class="srisai-product-card__body">
                            <span class="srisai-product-card__category"><?= ucfirst(str_replace('_', ' ', $product->category)) ?></span>
                            <h4><a href="<?= $baseUrl ?>/shop/<?= htmlspecialchars($product->slug) ?>"><?= htmlspecialchars(langField($product, 'name')) ?></a></h4>
                            <div class="srisai-product-card__price">Rs.<?= number_format($product->price, 2) ?></div>
                            <?php if ((int) $product->stock_qty > 0): ?>
                                <span class="srisai-badge srisai-badge--success"><?= __('shop.in_stock') ?></span>
                            <?php else: ?>
                                <span class="srisai-badge srisai-badge--muted"><?= __('shop.out_of_stock') ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
