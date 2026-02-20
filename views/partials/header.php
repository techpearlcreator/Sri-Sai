<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$publicUser  = $_SESSION['public_user'] ?? null;
$currentLang = App\Helpers\Lang::current();
$otherLang   = $currentLang === 'ta' ? 'en' : 'ta';
$otherLabel  = $currentLang === 'ta' ? 'EN' : 'தமிழ்';
// Lang-switch URL: keep current path + query params, just change lang
$switchBase  = strtok($_SERVER['REQUEST_URI'] ?? '/', '?');
$qs          = $_GET;
$qs['lang']  = $otherLang;
$switchUrl   = $switchBase . '?' . http_build_query($qs);
?>
<header class="site-header">
    <div class="header-container">
        <!-- Logo -->
        <a href="<?= $baseUrl ?>/" class="site-logo">
            <img src="<?= $assetUrl ?>/images/logo.png?v=4" srcset="<?= $assetUrl ?>/images/logo-retina.png?v=4 2x" alt="Sri Sai Mission" width="115" height="72">
        </a>

        <!-- Desktop Navigation -->
        <nav class="main-navigation" id="main-nav">
            <ul>
                <li><a href="<?= $baseUrl ?>/"<?= ($pageClass ?? '') === 'home' ? ' class="active"' : '' ?>><?= __('nav.home') ?></a></li>
                <li><a href="<?= $baseUrl ?>/about"<?= ($pageClass ?? '') === 'about' ? ' class="active"' : '' ?>><?= __('nav.about') ?></a></li>
                <li><a href="<?= $baseUrl ?>/events"<?= ($pageClass ?? '') === 'events' ? ' class="active"' : '' ?>><?= __('nav.events') ?></a></li>
                <li><a href="<?= $baseUrl ?>/gallery"<?= ($pageClass ?? '') === 'gallery' ? ' class="active"' : '' ?>><?= __('nav.gallery') ?></a></li>
                <li><a href="<?= $baseUrl ?>/shop"<?= ($pageClass ?? '') === 'shop' ? ' class="active"' : '' ?>><?= __('nav.shop') ?></a></li>
                <li><a href="<?= $baseUrl ?>/tours"<?= ($pageClass ?? '') === 'tours' ? ' class="active"' : '' ?>><?= __('nav.tours') ?></a></li>
                <li><a href="<?= $baseUrl ?>/blog"<?= ($pageClass ?? '') === 'blog' ? ' class="active"' : '' ?>><?= __('nav.blog') ?></a></li>
                <li><a href="<?= $baseUrl ?>/contact"<?= ($pageClass ?? '') === 'contact' ? ' class="active"' : '' ?>><?= __('nav.contact') ?></a></li>
            </ul>
        </nav>

        <!-- Header Actions -->
        <div class="header-actions">
            <!-- Language toggle -->
            <a href="<?= htmlspecialchars($switchUrl) ?>" class="btn-lang-toggle" title="<?= __('lang.toggle_title') ?>"><?= $otherLabel ?></a>

            <?php if ($publicUser): ?>
                <a href="<?= $baseUrl ?>/profile" class="btn btn-user" title="<?= __('nav.my_profile') ?>"><?= htmlspecialchars($publicUser['name']) ?></a>
                <a href="<?= $baseUrl ?>/logout" class="btn btn-user-logout"><?= __('nav.logout') ?></a>
            <?php else: ?>
                <a href="<?= $baseUrl ?>/login" class="btn btn-login"><?= __('nav.login') ?></a>
            <?php endif; ?>
            <a href="<?= $baseUrl ?>/donations" class="btn btn-donate"><?= __('btn.donate') ?></a>
            <button class="mobile-menu-toggle" id="mobile-menu-toggle" aria-label="Open menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</header>

<!-- Mobile Menu Overlay -->
<div class="mobile-overlay" id="mobile-overlay"></div>
<div class="mobile-menu" id="mobile-menu">
    <div class="mobile-menu-inner">
        <div class="mobile-menu-header">
            <a href="<?= $baseUrl ?>/" class="site-logo">
                <img src="<?= $assetUrl ?>/images/logo.png?v=4" srcset="<?= $assetUrl ?>/images/logo-retina.png?v=4 2x" alt="Sri Sai Mission" width="95" height="60">
            </a>
            <button class="mobile-menu-close" id="mobile-menu-close" aria-label="Close menu">&times;</button>
        </div>
        <nav class="mobile-nav">
            <ul>
                <li><a href="<?= $baseUrl ?>/"><?= __('nav.home') ?></a></li>
                <li><a href="<?= $baseUrl ?>/about"><?= __('nav.about') ?></a></li>
                <li><a href="<?= $baseUrl ?>/events"><?= __('nav.events') ?></a></li>
                <li><a href="<?= $baseUrl ?>/gallery"><?= __('nav.gallery') ?></a></li>
                <li><a href="<?= $baseUrl ?>/shop"><?= __('nav.shop') ?></a></li>
                <li><a href="<?= $baseUrl ?>/tours"><?= __('nav.tours') ?></a></li>
                <li><a href="<?= $baseUrl ?>/blog"><?= __('nav.blog') ?></a></li>
                <li><a href="<?= $baseUrl ?>/donations"><?= __('nav.donations') ?></a></li>
                <li><a href="<?= $baseUrl ?>/contact"><?= __('nav.contact') ?></a></li>
                <?php if ($publicUser): ?>
                    <li><a href="<?= $baseUrl ?>/profile"><?= __('nav.my_profile') ?></a></li>
                    <li><a href="<?= $baseUrl ?>/logout"><?= __('nav.logout') ?></a></li>
                <?php else: ?>
                    <li><a href="<?= $baseUrl ?>/login"><?= __('nav.login_register') ?></a></li>
                <?php endif; ?>
                <li><a href="<?= htmlspecialchars($switchUrl) ?>"><?= __('lang.toggle_title') ?>: <?= $otherLabel ?></a></li>
            </ul>
        </nav>
    </div>
</div>
