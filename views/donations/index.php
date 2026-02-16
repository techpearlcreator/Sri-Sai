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

            <!-- Donation Form -->
            <div class="donation-form" style="margin-top:60px;">
                <h2 style="text-align:center; margin-bottom:40px; color:var(--color-text-dark);">Make Your Donation</h2>
                <form id="srisai-donation-form">
                    <div class="donation-amount-wrap">
                        <label class="donation-label">Donation Amount (&#8377;):</label>
                        <input class="donation-amount-input" id="donation-amount" name="amount" type="number" value="100" min="10" required>
                    </div>

                    <div class="donation-amounts">
                        <button type="button" class="amount-btn active" data-amount="100">&#8377;100</button>
                        <button type="button" class="amount-btn" data-amount="500">&#8377;500</button>
                        <button type="button" class="amount-btn" data-amount="1000">&#8377;1000</button>
                        <button type="button" class="amount-btn" data-amount="custom">Custom</button>
                    </div>

                    <fieldset class="donation-personal-info">
                        <legend>Personal Info</legend>
                        <div class="form-row-group">
                            <div class="form-row">
                                <label for="donor-first-name">First Name <span class="required">*</span></label>
                                <input type="text" id="donor-first-name" name="first_name" required>
                            </div>
                            <div class="form-row">
                                <label for="donor-last-name">Last Name</label>
                                <input type="text" id="donor-last-name" name="last_name">
                            </div>
                        </div>
                        <div class="form-row">
                            <label for="donor-email">Email Address <span class="required">*</span></label>
                            <input type="email" id="donor-email" name="email" required>
                        </div>
                        <div class="form-row">
                            <label for="donor-phone">Phone Number</label>
                            <input type="tel" id="donor-phone" name="phone">
                        </div>
                        <div class="form-row">
                            <label for="donor-comment">Comment</label>
                            <textarea id="donor-comment" name="comment" rows="4" placeholder="Leave a comment"></textarea>
                        </div>
                    </fieldset>

                    <div class="donation-total-wrap">
                        <span class="donation-total-label">Donation Total:</span>
                        <span class="donation-total-amount">&#8377;<span id="donation-total-display">100</span></span>
                    </div>

                    <div class="donation-submit-wrap">
                        <button type="submit" class="btn btn-accent donation-submit-btn">Donate via Razorpay</button>
                        <div class="donation-message"></div>
                        <p style="margin-top:10px; font-size:12px; color:#888;">Secure payment powered by Razorpay</p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
