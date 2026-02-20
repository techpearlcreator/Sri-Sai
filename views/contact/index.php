<!-- Page Header -->
<div class="srisai-page-header">
    <div class="srisai-container">
        <h1><?= __('contact.title') ?></h1>
        <p><?= __('contact.subtitle') ?></p>
    </div>
</div>

<!-- Contact Form + Info -->
<div class="srisai-section srisai-section--white">
    <div class="srisai-container">
        <div class="srisai-contact-grid">
            <!-- Contact Form -->
            <div>
                <h3><?= __('contact.send_heading') ?></h3>

                <?php if (isset($_SESSION['contact_success'])): ?>
                    <div class="alert alert-success">
                        <?= htmlspecialchars($_SESSION['contact_success']) ?>
                    </div>
                    <?php unset($_SESSION['contact_success']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['contact_error'])): ?>
                    <div class="alert alert-error">
                        <?= htmlspecialchars($_SESSION['contact_error']) ?>
                    </div>
                    <?php unset($_SESSION['contact_error']); ?>
                <?php endif; ?>

                <form action="<?= $baseUrl ?>/contact/submit" method="POST" id="contactForm">
                    <div class="srisai-form-group">
                        <label for="name"><?= __('contact.name') ?> <span style="color:red;">*</span></label>
                        <input type="text" id="name" name="name" required value="<?= htmlspecialchars($_SESSION['contact_data']['name'] ?? '') ?>">
                    </div>

                    <div class="srisai-form-group">
                        <label for="email"><?= __('contact.email') ?> <span style="color:red;">*</span></label>
                        <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_SESSION['contact_data']['email'] ?? '') ?>">
                    </div>

                    <div class="srisai-form-group">
                        <label for="phone"><?= __('contact.phone') ?></label>
                        <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($_SESSION['contact_data']['phone'] ?? '') ?>">
                    </div>

                    <div class="srisai-form-group">
                        <label for="subject"><?= __('contact.subject') ?> <span style="color:red;">*</span></label>
                        <input type="text" id="subject" name="subject" required value="<?= htmlspecialchars($_SESSION['contact_data']['subject'] ?? '') ?>">
                    </div>

                    <div class="srisai-form-group">
                        <label for="message"><?= __('contact.message') ?> <span style="color:red;">*</span></label>
                        <textarea id="message" name="message" required rows="6"><?= htmlspecialchars($_SESSION['contact_data']['message'] ?? '') ?></textarea>
                    </div>

                    <button type="submit" class="srisai-btn srisai-btn--primary"><?= __('btn.send_message') ?></button>
                </form>
                <?php unset($_SESSION['contact_data']); ?>
            </div>

            <!-- Contact Info -->
            <div class="srisai-contact-info">
                <h3><?= __('contact.get_in_touch') ?></h3>

                <div class="srisai-contact-item">
                    <div class="srisai-contact-item__icon">
                        <span class="icon-location"></span>
                    </div>
                    <div>
                        <h4><?= __('contact.address') ?></h4>
                        <p><?= __('contact.address_text') ?><br>India</p>
                    </div>
                </div>

                <div class="srisai-contact-item">
                    <div class="srisai-contact-item__icon">
                        <span class="icon-clock"></span>
                    </div>
                    <div>
                        <h4><?= __('contact.visit_us') ?></h4>
                        <p><?= __('contact.visit_text') ?></p>
                    </div>
                </div>

                <div class="srisai-contact-item">
                    <div class="srisai-contact-item__icon">
                        <span class="icon-mail"></span>
                    </div>
                    <div>
                        <h4><?= __('contact.online') ?></h4>
                        <p><?= __('contact.online_text') ?></p>
                    </div>
                </div>

                <div style="margin-top:30px;">
                    <h4><?= __('contact.follow_us') ?></h4>
                    <div class="socials_wrap" style="display:flex; gap:12px; margin-top:15px;">
                        <a href="#" style="width:45px; height:45px; border-radius:50%; background:var(--srisai-primary); display:flex; align-items:center; justify-content:center; color:#fff; text-decoration:none; transition:background 0.3s;">
                            <span class="icon-facebook-1"></span>
                        </a>
                        <a href="#" style="width:45px; height:45px; border-radius:50%; background:var(--srisai-primary); display:flex; align-items:center; justify-content:center; color:#fff; text-decoration:none; transition:background 0.3s;">
                            <span class="icon-instagram"></span>
                        </a>
                        <a href="#" style="width:45px; height:45px; border-radius:50%; background:var(--srisai-primary); display:flex; align-items:center; justify-content:center; color:#fff; text-decoration:none; transition:background 0.3s;">
                            <span class="icon-twitter-new"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
