<header class="site-header">
    <div class="header-container">
        <!-- Logo -->
        <a href="<?= $baseUrl ?>/" class="site-logo">
            <img src="<?= $assetUrl ?>/images/logo.png?v=4" srcset="<?= $assetUrl ?>/images/logo-retina.png?v=4 2x" alt="Sri Sai Mission" width="115" height="72">
        </a>

        <!-- Desktop Navigation -->
        <nav class="main-navigation" id="main-nav">
            <ul>
                <li><a href="<?= $baseUrl ?>/"<?= ($pageClass ?? '') === 'home' ? ' class="active"' : '' ?>>Home</a></li>
                <li><a href="<?= $baseUrl ?>/about"<?= ($pageClass ?? '') === 'about' ? ' class="active"' : '' ?>>About</a></li>
                <li><a href="<?= $baseUrl ?>/events"<?= ($pageClass ?? '') === 'events' ? ' class="active"' : '' ?>>Events</a></li>
                <li><a href="<?= $baseUrl ?>/gallery"<?= ($pageClass ?? '') === 'gallery' ? ' class="active"' : '' ?>>Gallery</a></li>
                <li><a href="<?= $baseUrl ?>/blog"<?= ($pageClass ?? '') === 'blog' ? ' class="active"' : '' ?>>Blog</a></li>
                <li><a href="<?= $baseUrl ?>/magazine"<?= ($pageClass ?? '') === 'magazine' ? ' class="active"' : '' ?>>Magazine</a></li>
                <li><a href="<?= $baseUrl ?>/trustees"<?= ($pageClass ?? '') === 'trustees' ? ' class="active"' : '' ?>>Trustees</a></li>
                <li><a href="<?= $baseUrl ?>/contact"<?= ($pageClass ?? '') === 'contact' ? ' class="active"' : '' ?>>Contact</a></li>
            </ul>
        </nav>

        <!-- Header Actions -->
        <div class="header-actions">
            <a href="<?= $baseUrl ?>/donations" class="btn btn-donate">Donate</a>
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
                <li><a href="<?= $baseUrl ?>/">Home</a></li>
                <li><a href="<?= $baseUrl ?>/about">About</a></li>
                <li><a href="<?= $baseUrl ?>/events">Events</a></li>
                <li><a href="<?= $baseUrl ?>/gallery">Gallery</a></li>
                <li><a href="<?= $baseUrl ?>/blog">Blog</a></li>
                <li><a href="<?= $baseUrl ?>/magazine">Magazine</a></li>
                <li><a href="<?= $baseUrl ?>/trustees">Trustees</a></li>
                <li><a href="<?= $baseUrl ?>/donations">Donations</a></li>
                <li><a href="<?= $baseUrl ?>/contact">Contact</a></li>
            </ul>
        </nav>
    </div>
</div>
