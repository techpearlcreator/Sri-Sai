<!-- Page Header -->
<div class="srisai-page-header">
    <div class="srisai-container">
        <h1>Our Trustees</h1>
        <p>Meet the dedicated team serving Sri Sai Mission</p>
    </div>
</div>

<!-- Main Trustees -->
<?php if (!empty($mainTrustees)): ?>
    <div class="srisai-section srisai-section--white">
        <div class="srisai-container">
            <div class="srisai-section__header">
                <h2 class="srisai-section__title">Main Trustees</h2>
            </div>

            <div class="srisai-trustees-grid">
                <?php foreach ($mainTrustees as $trustee): ?>
                    <div class="srisai-trustee-card">
                        <div class="srisai-trustee-card__photo">
                            <?php if (!empty($trustee->photo)): ?>
                                <img src="<?= $baseUrl ?>/storage/uploads/<?= htmlspecialchars($trustee->photo) ?>" alt="<?= htmlspecialchars($trustee->name) ?>">
                            <?php else: ?>
                                <div class="srisai-trustee-card__photo--placeholder">
                                    <span><?= substr(htmlspecialchars($trustee->name), 0, 1) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <h4><?= htmlspecialchars($trustee->name) ?></h4>
                        <?php if (!empty($trustee->designation)): ?>
                            <p class="srisai-trustee-card__role"><?= htmlspecialchars($trustee->designation) ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Co-opted Trustees -->
<?php if (!empty($cooptedTrustees)): ?>
    <div class="srisai-section srisai-section--light">
        <div class="srisai-container">
            <div class="srisai-section__header">
                <h2 class="srisai-section__title">Co-opted Trustees</h2>
            </div>

            <div class="srisai-trustees-grid">
                <?php foreach ($cooptedTrustees as $trustee): ?>
                    <div class="srisai-trustee-card">
                        <div class="srisai-trustee-card__photo">
                            <?php if (!empty($trustee->photo)): ?>
                                <img src="<?= $baseUrl ?>/storage/uploads/<?= htmlspecialchars($trustee->photo) ?>" alt="<?= htmlspecialchars($trustee->name) ?>">
                            <?php else: ?>
                                <div class="srisai-trustee-card__photo--placeholder">
                                    <span><?= substr(htmlspecialchars($trustee->name), 0, 1) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <h4><?= htmlspecialchars($trustee->name) ?></h4>
                        <?php if (!empty($trustee->designation)): ?>
                            <p class="srisai-trustee-card__role"><?= htmlspecialchars($trustee->designation) ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (empty($mainTrustees) && empty($cooptedTrustees)): ?>
    <div class="srisai-section srisai-section--light">
        <div class="srisai-container">
            <div class="srisai-empty">
                <p>Trustee information will be available soon.</p>
            </div>
        </div>
    </div>
<?php endif; ?>
