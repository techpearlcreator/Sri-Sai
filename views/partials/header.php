<header class="top_panel top_panel_custom top_panel_custom_18287 top_panel_custom_header-home-style-1 without_bg_image">
    <div data-elementor-type="cpt_layouts" data-elementor-id="18287" class="elementor elementor-18287">
        <!-- Row 1: Desktop Header (hidden on tablet/mobile) -->
        <section class="elementor-section elementor-top-section elementor-element elementor-section-full_width elementor-section-content-middle sc_layouts_row sc_layouts_row_type_compact scheme_dark sc_layouts_hide_on_tablet sc_layouts_hide_on_mobile elementor-section-height-default sc_fly_static" style="background-color:#1D0427; padding: 20px 50px;">
            <div class="elementor-container elementor-column-gap-extended">
                <div class="elementor-column elementor-col-50 elementor-top-column sc_layouts_column_align_left">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <!-- Logo -->
                        <div class="sc_layouts_item elementor-widget">
                            <div class="elementor-widget-container">
                                <a href="<?= $baseUrl ?>/" class="sc_layouts_logo sc_layouts_logo_default">
                                    <span style="font-family:'Kumbh Sans',sans-serif; font-size:24px; font-weight:700; color:#fff;">Sri Sai Mission</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="elementor-column elementor-col-50 elementor-top-column sc_layouts_column_align_right">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <!-- Navigation -->
                        <div class="sc_layouts_item elementor-widget">
                            <div class="elementor-widget-container">
                                <nav class="sc_layouts_menu sc_layouts_menu_default sc_layouts_menu_dir_horizontal menu_hover_zoom_line">
                                    <ul class="sc_layouts_menu_nav">
                                        <li class="menu-item<?= ($pageClass ?? '') === 'home' ? ' current-menu-item' : '' ?>"><a href="<?= $baseUrl ?>/"><span>Home</span></a></li>
                                        <li class="menu-item<?= ($pageClass ?? '') === 'about' ? ' current-menu-item' : '' ?>"><a href="<?= $baseUrl ?>/about"><span>About</span></a></li>
                                        <li class="menu-item<?= ($pageClass ?? '') === 'events' ? ' current-menu-item' : '' ?>"><a href="<?= $baseUrl ?>/events"><span>Events</span></a></li>
                                        <li class="menu-item<?= ($pageClass ?? '') === 'gallery' ? ' current-menu-item' : '' ?>"><a href="<?= $baseUrl ?>/gallery"><span>Gallery</span></a></li>
                                        <li class="menu-item<?= ($pageClass ?? '') === 'blog' ? ' current-menu-item' : '' ?>"><a href="<?= $baseUrl ?>/blog"><span>Blog</span></a></li>
                                        <li class="menu-item<?= ($pageClass ?? '') === 'magazine' ? ' current-menu-item' : '' ?>"><a href="<?= $baseUrl ?>/magazine"><span>Magazine</span></a></li>
                                        <li class="menu-item<?= ($pageClass ?? '') === 'trustees' ? ' current-menu-item' : '' ?>"><a href="<?= $baseUrl ?>/trustees"><span>Trustees</span></a></li>
                                        <li class="menu-item<?= ($pageClass ?? '') === 'contact' ? ' current-menu-item' : '' ?>"><a href="<?= $baseUrl ?>/contact"><span>Contact</span></a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <!-- Donate Button -->
                        <div class="sc_layouts_item elementor-widget" style="margin-left: 20px;">
                            <div class="elementor-widget-container">
                                <div class="sc_item_button sc_button_wrap">
                                    <a href="<?= $baseUrl ?>/donations" class="sc_button sc_button_default sc_button_size_small sc_button_icon_left color_style_link2">
                                        <span class="sc_button_text"><span class="sc_button_title">Donate</span></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Row 2: Mobile Header (hidden on desktop) -->
        <section class="elementor-section elementor-top-section elementor-element elementor-section-content-middle sc_layouts_row sc_layouts_row_type_compact scheme_dark sc_layouts_hide_on_wide sc_layouts_hide_on_desktop sc_layouts_hide_on_notebook elementor-section-boxed sc_fly_static" style="background-color:#1D0427; padding: 15px 20px;">
            <div class="elementor-container elementor-column-gap-extended">
                <div class="elementor-column elementor-col-50 elementor-top-column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="sc_layouts_item elementor-widget">
                            <div class="elementor-widget-container">
                                <a href="<?= $baseUrl ?>/" class="sc_layouts_logo sc_layouts_logo_default">
                                    <span style="font-family:'Kumbh Sans',sans-serif; font-size:20px; font-weight:700; color:#fff;">Sri Sai Mission</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="elementor-column elementor-col-50 elementor-top-column sc_layouts_column_align_right">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="sc_layouts_item elementor-widget">
                            <div class="elementor-widget-container">
                                <div class="sc_layouts_iconed_text sc_layouts_menu_mobile_button_burger sc_layouts_menu_mobile_button without_menu">
                                    <a class="sc_layouts_item_link sc_layouts_iconed_text_link" href="#" role="button">
                                        <span class="sc_layouts_item_icon sc_layouts_iconed_text_icon trx_addons_icon-menu"></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</header>

<!-- Mobile Menu Overlay -->
<div class="menu_mobile_overlay scheme_dark"></div>
<div class="menu_mobile menu_mobile_fullscreen scheme_dark">
    <div class="menu_mobile_inner">
        <div class="menu_mobile_header_wrap">
            <a class="sc_layouts_logo" href="<?= $baseUrl ?>/">
                <span style="font-family:'Kumbh Sans',sans-serif; font-size:20px; font-weight:700; color:#fff;">Sri Sai Mission</span>
            </a>
            <span class="menu_mobile_close menu_button_close" tabindex="0">
                <span class="menu_button_close_text">Close</span>
                <span class="menu_button_close_icon"></span>
            </span>
        </div>
        <div class="menu_mobile_content_wrap content_wrap">
            <div class="menu_mobile_content_wrap_inner">
                <nav class="menu_mobile_nav_area">
                    <ul class="menu_mobile_nav">
                        <li class="menu-item"><a href="<?= $baseUrl ?>/"><span>Home</span></a></li>
                        <li class="menu-item"><a href="<?= $baseUrl ?>/about"><span>About</span></a></li>
                        <li class="menu-item"><a href="<?= $baseUrl ?>/events"><span>Events</span></a></li>
                        <li class="menu-item"><a href="<?= $baseUrl ?>/gallery"><span>Gallery</span></a></li>
                        <li class="menu-item"><a href="<?= $baseUrl ?>/blog"><span>Blog</span></a></li>
                        <li class="menu-item"><a href="<?= $baseUrl ?>/magazine"><span>Magazine</span></a></li>
                        <li class="menu-item"><a href="<?= $baseUrl ?>/trustees"><span>Trustees</span></a></li>
                        <li class="menu-item"><a href="<?= $baseUrl ?>/donations"><span>Donations</span></a></li>
                        <li class="menu-item"><a href="<?= $baseUrl ?>/contact"><span>Contact</span></a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<style>
/* Header Styling */
.top_panel { position: relative; z-index: 100; }
.header_desktop { background-color: #1D0427; }
.sc_layouts_menu_nav { display: flex; gap: 25px; list-style: none; margin: 0; padding: 0; align-items: center; }
.sc_layouts_menu_nav a { color: #fff; font-family: 'Kumbh Sans', sans-serif; font-size: 15px; font-weight: 500; text-decoration: none; transition: color 0.3s; }
.sc_layouts_menu_nav a:hover { color: #9FA73E; }
.menu-item.current-menu-item a { color: #9FA73E !important; }

/* Donate Button */
.sc_button { display: inline-block; padding: 10px 24px; background: #9FA73E; color: #fff; border-radius: 50px; font-family: 'Kumbh Sans', sans-serif; font-size: 14px; font-weight: 600; text-decoration: none; transition: background 0.3s; }
.sc_button:hover { background: #8a912f; color: #fff; }

/* Mobile Menu Overlay - HIDDEN BY DEFAULT */
.menu_mobile_overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.8);
    z-index: 9998;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s;
}

/* Mobile Menu - HIDDEN BY DEFAULT (OFF-SCREEN) */
.menu_mobile {
    position: fixed;
    top: 0;
    right: 0;
    width: 300px;
    height: 100vh;
    background: #1D0427;
    z-index: 9999;
    transform: translateX(100%);
    transition: transform 0.3s;
    overflow-y: auto;
}

/* Mobile Menu ACTIVE STATE (Visible) */
.menu_mobile.active { transform: translateX(0); }
.menu_mobile_overlay.active { opacity: 1; visibility: visible; }

/* Mobile Menu Inner Styling */
.menu_mobile_inner { padding: 20px; }
.menu_mobile_header_wrap {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.menu_mobile_close {
    background: none;
    border: none;
    color: #fff;
    font-size: 16px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
}

.menu_mobile_close_icon::before {
    content: '×';
    font-size: 32px;
    line-height: 1;
}

.menu_mobile_nav { list-style: none; padding: 0; margin: 20px 0; }
.menu_mobile_nav li { margin-bottom: 5px; }
.menu_mobile_nav a {
    display: block;
    padding: 12px 20px;
    color: #fff;
    text-decoration: none;
    font-family: 'Kumbh Sans', sans-serif;
    font-weight: 500;
    transition: background 0.3s;
}
.menu_mobile_nav a:hover { background: rgba(255,255,255,0.05); color: #9FA73E; }

/* Mobile burger button */
.menu_mobile_button { cursor: pointer; }
.trx_addons_icon-menu::before {
    content: '☰';
    font-size: 24px;
    color: #fff;
}

/* Hide desktop/mobile headers based on screen size */
@media (max-width: 1023px) {
    .sc_layouts_hide_on_tablet,
    .sc_layouts_hide_on_mobile { display: none !important; }
}

@media (max-width: 767px) {
    .sc_layouts_hide_on_mobile { display: none !important; }
    .menu_mobile { width: 100%; }
}

@media (min-width: 1024px) {
    .sc_layouts_hide_on_wide,
    .sc_layouts_hide_on_desktop,
    .sc_layouts_hide_on_notebook { display: none !important; }
}
</style>
