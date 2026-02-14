<!-- Core JS -->
<script src="<?= $assetUrl ?>/js/jquery.min.js"></script>
<script src="<?= $assetUrl ?>/js/swiper.min.js"></script>
<script src="<?= $assetUrl ?>/js/magnific-popup.min.js"></script>

<!-- Sri Sai Custom JS -->
<script>
(function($) {
    'use strict';

    // Mobile menu toggle
    var $menuButton = $('.menu_mobile_button, .sc_layouts_menu_mobile_button');
    var $mobileMenu = $('.menu_mobile');
    var $menuOverlay = $('.menu_mobile_overlay');
    var $menuClose = $('.menu_mobile_close');

    // Open mobile menu
    $menuButton.on('click', function(e) {
        e.preventDefault();
        $mobileMenu.addClass('active');
        $menuOverlay.addClass('active');
        $('body').css('overflow', 'hidden');
    });

    // Close mobile menu
    $menuClose.on('click', function(e) {
        e.preventDefault();
        $mobileMenu.removeClass('active');
        $menuOverlay.removeClass('active');
        $('body').css('overflow', '');
    });

    // Close on overlay click
    $menuOverlay.on('click', function() {
        $mobileMenu.removeClass('active');
        $menuOverlay.removeClass('active');
        $('body').css('overflow', '');
    });

    // Scroll to top
    var $scrollTop = $('.scroll-to-top');
    $(window).on('scroll', function() {
        if ($(this).scrollTop() > 300) {
            $scrollTop.addClass('visible');
        } else {
            $scrollTop.removeClass('visible');
        }
    });

    $scrollTop.on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({ scrollTop: 0 }, 600);
    });

    // Smooth scroll for anchor links
    $('a[href^="#"]').on('click', function(e) {
        var target = $(this.getAttribute('href'));
        if (target.length) {
            e.preventDefault();
            $('html, body').animate({ scrollTop: target.offset().top - 80 }, 600);
        }
    });

    // Magnific Popup for gallery
    if ($('.gallery-item, [data-lightbox]').length) {
        $('.gallery-item, [data-lightbox]').magnificPopup({
            type: 'image',
            gallery: { enabled: true },
            zoom: { enabled: true, duration: 300 }
        });
    }

    // Contact form AJAX
    var $contactForm = $('#srisai-contact-form');
    if ($contactForm.length) {
        $contactForm.on('submit', function(e) {
            e.preventDefault();
            var $btn = $contactForm.find('button[type="submit"]');
            var $msg = $contactForm.find('.form-message');
            $btn.prop('disabled', true).text('Sending...');
            $msg.html('');

            $.ajax({
                url: '<?= $baseUrl ?>/contact/submit',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    name: $contactForm.find('[name="name"]').val(),
                    email: $contactForm.find('[name="email"]').val(),
                    phone: $contactForm.find('[name="phone"]').val(),
                    subject: $contactForm.find('[name="subject"]').val(),
                    message: $contactForm.find('[name="message"]').val()
                }),
                success: function(res) {
                    $msg.html('<div class="alert alert-success">Thank you! Your message has been sent.</div>');
                    $contactForm[0].reset();
                },
                error: function(xhr) {
                    var err = xhr.responseJSON;
                    var text = (err && err.error && err.error.message) || 'Failed to send. Please try again.';
                    $msg.html('<div class="alert alert-error">' + text + '</div>');
                },
                complete: function() {
                    $btn.prop('disabled', false).text('Send Message');
                }
            });
        });
    }

})(jQuery);
</script>
