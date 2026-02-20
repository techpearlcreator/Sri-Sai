<!-- Page Header -->
<div class="srisai-page-header">
    <div class="srisai-container">
        <h1><?= __('donations.title') ?></h1>
        <p><?= __('donations.subtitle') ?></p>
    </div>
</div>

<!-- Donation Content -->
<div class="srisai-section srisai-section--white">
    <div class="srisai-container">
        <div style="max-width:900px; margin:0 auto;">
                <div class="srisai-article__content">
                    <h2><?= __('donations.why_title') ?></h2>
                    <p><?= __('donations.why_text') ?></p>
                    <ul>
                        <li><strong><?= __('home.annadhanam') ?>:</strong> <?= __('donations.annadhanam_item') ?></li>
                        <li><strong><?= __('home.temple_pooja') ?>:</strong> <?= __('donations.temple_item') ?></li>
                        <li><strong>Cow Saala:</strong> <?= __('donations.cow_item') ?></li>
                        <li><strong><?= __('donations.education_label') ?>:</strong> <?= __('donations.education_item') ?></li>
                        <li><strong>Community Service:</strong> <?= __('donations.community_item') ?></li>
                    </ul>

                    <h3 style="margin-top:40px;"><?= __('donations.tax_title') ?></h3>
                    <p><?= __('donations.tax_text') ?></p>

                    <h3 style="margin-top:40px;"><?= __('donations.how_title') ?></h3>
                    <p><?= __('donations.how_text') ?></p>
                    <ul>
                        <li><strong>Email:</strong> <?= __('donations.how_email') ?> <a href="<?= $baseUrl ?>/contact"><?= __('donations.contact_form') ?></a></li>
                        <li><strong>Visit:</strong> <?= __('donations.how_visit') ?></li>
                    </ul>
                </div>

            <!-- Donation Form -->
            <div class="donation-form" style="margin-top:60px;">
                <h2 style="text-align:center; margin-bottom:40px; color:var(--color-text-dark);"><?= __('donations.make_title') ?></h2>
                <form id="srisai-donation-form">
                    <div class="donation-amount-wrap">
                        <label class="donation-label"><?= __('donations.amount_label') ?></label>
                        <input class="donation-amount-input" id="donation-amount" name="amount" type="number" value="100" min="10" required>
                    </div>

                    <div class="donation-amounts">
                        <button type="button" class="amount-btn active" data-amount="100">&#8377;100</button>
                        <button type="button" class="amount-btn" data-amount="500">&#8377;500</button>
                        <button type="button" class="amount-btn" data-amount="1000">&#8377;1000</button>
                        <button type="button" class="amount-btn" data-amount="custom"><?= __('donations.custom') ?></button>
                    </div>

                    <fieldset class="donation-personal-info">
                        <legend><?= __('donations.personal_info') ?></legend>
                        <div class="form-row-group">
                            <div class="form-row">
                                <label for="donor-first-name"><?= __('donations.first_name') ?> <span class="required">*</span></label>
                                <input type="text" id="donor-first-name" name="first_name" required>
                            </div>
                            <div class="form-row">
                                <label for="donor-last-name"><?= __('donations.last_name') ?></label>
                                <input type="text" id="donor-last-name" name="last_name">
                            </div>
                        </div>
                        <div class="form-row">
                            <label for="donor-email"><?= __('donations.email') ?> <span class="required">*</span></label>
                            <input type="email" id="donor-email" name="email" required>
                        </div>
                        <div class="form-row">
                            <label for="donor-phone"><?= __('donations.phone') ?></label>
                            <input type="tel" id="donor-phone" name="phone">
                        </div>
                        <div class="form-row">
                            <label for="donor-comment"><?= __('donations.comment') ?></label>
                            <textarea id="donor-comment" name="comment" rows="4" placeholder="<?= htmlspecialchars(__('donations.comment_ph')) ?>"></textarea>
                        </div>
                    </fieldset>

                    <div class="donation-total-wrap">
                        <span class="donation-total-label"><?= __('donations.total_label') ?></span>
                        <span class="donation-total-amount">&#8377;<span id="donation-total-display">100</span></span>
                    </div>

                    <div class="donation-submit-wrap">
                        <button type="submit" class="btn btn-accent donation-submit-btn"><?= __('donations.btn') ?></button>
                        <div class="donation-message"></div>
                        <p style="margin-top:10px; font-size:12px; color:#888;"><?= __('donations.secure') ?></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
