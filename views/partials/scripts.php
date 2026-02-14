<!-- Core JS -->
<script src="<?= $assetUrl ?>/js/jquery.min.js"></script>
<script src="<?= $assetUrl ?>/js/swiper-bundle.min.js"></script>
<script src="<?= $assetUrl ?>/js/magnific-popup.min.js"></script>
<script src="<?= $assetUrl ?>/js/odometer.min.js"></script>

<!-- Sri Sai Custom JS -->
<script>
(function($) {
    'use strict';

    // ========== Mobile Menu ==========
    var $menuToggle = $('#mobile-menu-toggle');
    var $mobileMenu = $('#mobile-menu');
    var $overlay = $('#mobile-overlay');
    var $menuClose = $('#mobile-menu-close');

    function openMenu() {
        $mobileMenu.addClass('active');
        $overlay.addClass('active');
        $('body').css('overflow', 'hidden');
    }

    function closeMenu() {
        $mobileMenu.removeClass('active');
        $overlay.removeClass('active');
        $('body').css('overflow', '');
    }

    $menuToggle.on('click', openMenu);
    $menuClose.on('click', closeMenu);
    $overlay.on('click', closeMenu);

    // ========== Scroll to Top ==========
    var $scrollTop = $('.scroll-to-top');
    $(window).on('scroll', function() {
        $scrollTop.toggleClass('visible', $(this).scrollTop() > 300);
    });

    $scrollTop.on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({ scrollTop: 0 }, 600);
    });

    // ========== Smooth Scroll for Anchor Links ==========
    $('a[href^="#"]').on('click', function(e) {
        var target = $(this.getAttribute('href'));
        if (target.length) {
            e.preventDefault();
            $('html, body').animate({ scrollTop: target.offset().top - 80 }, 600);
        }
    });

    // ========== Magnific Popup for Gallery ==========
    if ($('.gallery-item, [data-lightbox]').length) {
        $('.gallery-item, [data-lightbox]').magnificPopup({
            type: 'image',
            gallery: { enabled: true },
            zoom: { enabled: true, duration: 300 }
        });
    }

    // ========== Contact Form AJAX ==========
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
                success: function() {
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

    // ========== Hero Slider (Swiper) ==========
    if ($('.hero-swiper').length) {
        new Swiper('.hero-swiper', {
            loop: true,
            autoplay: { delay: 5000, disableOnInteraction: false },
            effect: 'fade',
            fadeEffect: { crossFade: true },
            speed: 1000,
            pagination: { el: '.hero-slider-pagination', clickable: true },
            navigation: {
                nextEl: '.hero-slider-button-next',
                prevEl: '.hero-slider-button-prev'
            }
        });
    }

    // ========== Gallery Slider (Swiper) ==========
    if ($('.gallery-swiper').length) {
        new Swiper('.gallery-swiper', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            autoplay: { delay: 3000, disableOnInteraction: false },
            speed: 800,
            breakpoints: {
                640: { slidesPerView: 2, spaceBetween: 40 },
                1024: { slidesPerView: 3, spaceBetween: 60 }
            }
        });
    }

    // ========== Donation Form ==========
    $('.amount-btn').on('click', function() {
        var amount = $(this).data('amount');
        $('.amount-btn').removeClass('active');
        $(this).addClass('active');

        if (amount === 'custom') {
            $('#donation-amount').focus();
        } else {
            $('#donation-amount').val(amount);
            $('#donation-total-display').text(amount);
        }
    });

    $('#donation-amount').on('input', function() {
        var value = $(this).val();
        $('#donation-total-display').text(value);
        $('.amount-btn').removeClass('active');
    });

    // ========== Scroll Animation Observer ==========
    var animateElements = document.querySelectorAll('.animate-on-scroll');
    if (animateElements.length && 'IntersectionObserver' in window) {
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15, rootMargin: '0px 0px -50px 0px' });

        animateElements.forEach(function(el) {
            observer.observe(el);
        });
    } else {
        // Fallback: show all elements immediately
        animateElements.forEach(function(el) {
            el.classList.add('animated');
        });
    }

    // ========== Statistics Odometer Counters ==========
    var $odometerElements = $('.odometer');
    if ($odometerElements.length) {
        var countersTriggered = false;
        var statsObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting && !countersTriggered) {
                    countersTriggered = true;
                    $odometerElements.each(function() {
                        var $el = $(this);
                        var finalCount = $el.data('count');
                        setTimeout(function() {
                            $el.html(finalCount);
                        }, 300);
                    });
                    statsObserver.disconnect();
                }
            });
        }, { threshold: 0.3 });

        statsObserver.observe(document.querySelector('.stats-section') || document.querySelector('.stats-grid') || $odometerElements[0]);
    }

    // ========== Video Play Button ==========
    $('#video-play-btn').on('click', function() {
        alert('Video functionality will be added after video upload.');
    });

})(jQuery);
</script>
