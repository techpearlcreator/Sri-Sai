<footer class="site-footer">
    <div class="footer-main">
        <div class="section-container">
            <div class="footer-grid">
                <!-- Column 1: About -->
                <div class="footer-col">
                    <h3 class="footer-title"><?= __('footer.about') ?></h3>
                    <p class="footer-text"><?= __('footer.about_text') ?></p>
                </div>
                <!-- Column 2: Quick Links -->
                <div class="footer-col">
                    <h6 class="footer-heading"><?= __('footer.quick_links') ?></h6>
                    <ul class="footer-links">
                        <li><a href="<?= $baseUrl ?>/about"><?= __('nav.about') ?></a></li>
                        <li><a href="<?= $baseUrl ?>/events"><?= __('nav.events') ?></a></li>
                        <li><a href="<?= $baseUrl ?>/gallery"><?= __('nav.gallery') ?></a></li>
                        <li><a href="<?= $baseUrl ?>/blog"><?= __('nav.blog') ?></a></li>
                        <li><a href="<?= $baseUrl ?>/magazine"><?= __('nav.magazine') ?></a></li>
                        <li><a href="<?= $baseUrl ?>/donations"><?= __('nav.donations') ?></a></li>
                    </ul>
                </div>
                <!-- Column 3: Contact -->
                <div class="footer-col">
                    <h6 class="footer-heading"><?= __('footer.contact') ?></h6>
                    <p class="footer-text"><?= __('footer.address') ?><br>India</p>
                    <a href="<?= $baseUrl ?>/contact" class="footer-link-arrow"><?= __('footer.send_msg') ?></a>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="section-container">
            <div class="footer-bottom-inner">
                <nav class="footer-bottom-nav">
                    <a href="<?= $baseUrl ?>/blog"><?= __('footer.news') ?></a>
                    <a href="<?= $baseUrl ?>/donations"><?= __('nav.donations') ?></a>
                    <a href="<?= $baseUrl ?>/contact"><?= __('nav.contact') ?></a>
                </nav>
                <p class="footer-copyright"><?= __('footer.copyright', [date('Y')]) ?></p>
            </div>
        </div>
    </div>
</footer>
