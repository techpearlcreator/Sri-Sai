<!-- Page Header -->
<div class="srisai-page-header">
    <div class="srisai-container">
        <h1>Support Our Mission</h1>
        <p>Your donations help us serve the community</p>
    </div>
</div>

<!-- Donation Content -->
<div class="srisai-section srisai-section--white">
    <div class="srisai-container">
        <div style="max-width:900px; margin:0 auto;">
            <?php if ($donationPage): ?>
                <div class="srisai-article__content">
                    <?= $donationPage->content ?? '' ?>
                </div>
            <?php else: ?>
                <div class="srisai-article__content">
                    <h2>Why Donate?</h2>
                    <p>Sri Sai Mission is a registered charitable trust dedicated to serving humanity through various noble activities:</p>
                    <ul>
                        <li><strong>Annadhanam:</strong> Providing free meals to the needy</li>
                        <li><strong>Temple Worship:</strong> Maintaining temples and spiritual activities</li>
                        <li><strong>Cow Saala:</strong> Protection and care of cows</li>
                        <li><strong>Education:</strong> Supporting educational initiatives</li>
                        <li><strong>Community Service:</strong> Various charitable programs</li>
                    </ul>

                    <h3 style="margin-top:40px;">Tax Benefits</h3>
                    <p>Donations to Sri Sai Mission are eligible for <strong>80G tax exemption</strong> under the Income Tax Act. You will receive a tax-deductible receipt for your contribution.</p>

                    <h3 style="margin-top:40px;">How to Donate</h3>
                    <p>For donation details and bank account information, please contact us:</p>
                    <ul>
                        <li><strong>Email:</strong> Contact us through our <a href="<?= $baseUrl ?>/contact">contact form</a></li>
                        <li><strong>Visit:</strong> Visit any of our temple locations</li>
                    </ul>
                </div>
            <?php endif; ?>

            <div style="margin-top:50px; text-align:center; padding:40px; background:var(--srisai-bg-light); border-radius:var(--srisai-radius);">
                <h3 style="margin:0 0 15px;">Ready to Make a Difference?</h3>
                <p style="margin:0 0 25px; font-size:17px; color:#666;">Contact us to learn more about donation opportunities</p>
                <a href="<?= $baseUrl ?>/contact" class="srisai-btn srisai-btn--primary">Get in Touch</a>
            </div>
        </div>
    </div>
</div>
