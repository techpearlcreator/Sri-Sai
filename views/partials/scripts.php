<!-- Core JS -->
<script src="<?= $assetUrl ?>/js/jquery.min.js"></script>
<script src="<?= $assetUrl ?>/js/swiper.min.js"></script>
<script src="<?= $assetUrl ?>/js/magnific-popup.min.js"></script>

<!-- Sri Sai Custom JS -->
<script>
(function($) {
    'use strict';

    // Mobile menu toggle
    var $menuToggle = $('.srisai-menu-toggle');
    var $mobileNav = $('.srisai-mobile-nav');
    var $overlay = $('.srisai-nav-overlay');

    $menuToggle.on('click', function() {
        $mobileNav.toggleClass('active');
        $overlay.toggleClass('active');
        $('body').toggleClass('menu-open');
    });

    $overlay.on('click', function() {
        $mobileNav.removeClass('active');
        $overlay.removeClass('active');
        $('body').removeClass('menu-open');
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
    if ($('.gallery-popup').length) {
        $('.gallery-popup').magnificPopup({
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
