<!-- Core JS -->
<script src="<?= $assetUrl ?>/js/jquery.min.js"></script>
<script src="<?= $assetUrl ?>/js/swiper-bundle.min.js"></script>
<script src="<?= $assetUrl ?>/js/jquery.magnific-popup.min.js"></script>
<script src="<?= $assetUrl ?>/js/odometer.min.js"></script>

<!-- Sri Sai Custom JS -->
<script>
(function($) {
    'use strict';

    // ========== Mobile Detection & Touch Support ==========
    var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    var supportsTouch = 'ontouchstart' in window || navigator.maxTouchPoints > 0;

    if (isMobile) {
        $('html').addClass('is-mobile');
    }

    if (supportsTouch) {
        $('html').addClass('touch-device');
    }

    // Passive event listeners for better scroll performance
    var passiveSupported = false;
    try {
        var options = {
            get passive() {
                passiveSupported = true;
                return false;
            }
        };
        window.addEventListener("test", null, options);
        window.removeEventListener("test", null, options);
    } catch(err) {
        passiveSupported = false;
    }

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

    // Close mobile menu on link click
    $('.mobile-nav a').on('click', function() {
        closeMenu();
    });

    // Swipe to close mobile menu
    if (supportsTouch) {
        var touchStartX = 0;
        var touchEndX = 0;
        $mobileMenu[0].addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        }, passiveSupported ? { passive: true } : false);

        $mobileMenu[0].addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            // Swipe right to close
            if (touchEndX - touchStartX > 80) {
                closeMenu();
            }
        }, passiveSupported ? { passive: true } : false);
    }

    // ========== Header Scroll Effect ==========
    var $header = $('.site-header');
    var $scrollTop = $('.scroll-to-top');
    $(window).on('scroll', function() {
        var scrollPos = $(this).scrollTop();
        $header.toggleClass('scrolled', scrollPos > 80);
        $scrollTop.toggleClass('visible', scrollPos > 300);
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

    // ========== Razorpay Donation Payment ==========
    var $donationForm = $('#srisai-donation-form');
    if ($donationForm.length) {
        $donationForm.on('submit', function(e) {
            e.preventDefault();

            var amount = parseInt($('#donation-amount').val());
            if (!amount || amount < 10) {
                $('.donation-message').html('<div class="alert alert-error">Minimum donation is Rs.10</div>');
                return;
            }

            var firstName = $donationForm.find('[name="first_name"]').val();
            var lastName  = $donationForm.find('[name="last_name"]').val();
            var email     = $donationForm.find('[name="email"]').val();
            var phone     = $donationForm.find('[name="phone"]').val() || '';
            var comment   = $donationForm.find('[name="comment"]').val() || '';

            var $btn = $donationForm.find('.donation-submit-btn');
            $btn.prop('disabled', true).text('Processing...');
            $('.donation-message').html('');

            // Step 1: Create Razorpay order
            $.ajax({
                url: '<?= $baseUrl ?>/donations/create-order',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ amount: amount, first_name: firstName, email: email, phone: phone, comment: comment }),
                success: function(order) {
                    // Step 2: Open Razorpay checkout
                    var options = {
                        key: order.key,
                        amount: order.amount * 100,
                        currency: order.currency,
                        name: order.name,
                        description: order.description,
                        order_id: order.order_id,
                        prefill: {
                            name: (firstName + ' ' + lastName).trim(),
                            email: email,
                            contact: phone
                        },
                        theme: { color: '#5F2C70' },
                        handler: function(response) {
                            // Step 3: Verify payment
                            $.ajax({
                                url: '<?= $baseUrl ?>/donations/verify',
                                type: 'POST',
                                contentType: 'application/json',
                                data: JSON.stringify({
                                    razorpay_order_id: response.razorpay_order_id,
                                    razorpay_payment_id: response.razorpay_payment_id,
                                    razorpay_signature: response.razorpay_signature,
                                    first_name: firstName,
                                    last_name: lastName,
                                    email: email,
                                    phone: phone,
                                    amount: amount,
                                    comment: comment
                                }),
                                success: function(result) {
                                    $('.donation-message').html('<div class="alert alert-success">' + result.message + '<br>Payment ID: ' + result.payment_id + '</div>');
                                    $donationForm[0].reset();
                                    $('#donation-total-display').text('100');
                                    $('.amount-btn').removeClass('active').first().addClass('active');
                                },
                                error: function() {
                                    $('.donation-message').html('<div class="alert alert-error">Payment verification failed. Please contact us with your payment details.</div>');
                                },
                                complete: function() {
                                    $btn.prop('disabled', false).text('Donate via Razorpay');
                                }
                            });
                        },
                        modal: {
                            ondismiss: function() {
                                $btn.prop('disabled', false).text('Donate via Razorpay');
                            }
                        }
                    };

                    var rzp = new Razorpay(options);
                    rzp.open();
                },
                error: function(xhr) {
                    var err = xhr.responseJSON;
                    var msg = (err && err.error) || 'Failed to create payment order. Please try again.';
                    $('.donation-message').html('<div class="alert alert-error">' + msg + '</div>');
                    $btn.prop('disabled', false).text('Donate via Razorpay');
                }
            });
        });
    }

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

    // ========== Prayer Card Slide-in Observer ==========
    var prayerGrid = document.querySelector('.prayer-grid');
    if (prayerGrid && 'IntersectionObserver' in window) {
        var prayerObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    // Right card appears first
                    var rightCard = entry.target.querySelector('.prayer-slide-right');
                    var leftCard = entry.target.querySelector('.prayer-slide-left');
                    if (rightCard) rightCard.classList.add('visible');
                    // Left card appears 400ms later
                    if (leftCard) {
                        setTimeout(function() {
                            leftCard.classList.add('visible');
                        }, 400);
                    }
                    prayerObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.2, rootMargin: '0px 0px -40px 0px' });

        prayerObserver.observe(prayerGrid);
    } else {
        var prayerCards = document.querySelectorAll('.prayer-slide-left, .prayer-slide-right');
        prayerCards.forEach(function(el) {
            el.classList.add('visible');
        });
    }

    // ========== Statistics Counter Animation ==========
    var $statNumbers = $('.stat-bg-number');
    if ($statNumbers.length) {
        var countersTriggered = false;
        var statsObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting && !countersTriggered) {
                    countersTriggered = true;
                    $statNumbers.each(function() {
                        var $el = $(this);
                        var finalCount = parseInt($el.data('count'));
                        var current = 0;
                        var duration = 2000;
                        var step = Math.ceil(finalCount / (duration / 30));
                        var timer = setInterval(function() {
                            current += step;
                            if (current >= finalCount) {
                                current = finalCount;
                                clearInterval(timer);
                            }
                            $el.text(current);
                        }, 30);
                    });
                    statsObserver.disconnect();
                }
            });
        }, { threshold: 0.3 });

        statsObserver.observe(document.querySelector('.stats-section') || $statNumbers[0]);
    }

    // ========== Video Play Button ==========
    $('#video-play-btn').on('click', function() {
        alert('Video functionality will be added after video upload.');
    });

    // ========== Lazy Loading Images ==========
    if ('loading' in HTMLImageElement.prototype) {
        // Native lazy loading
        $('img[data-src]').each(function() {
            $(this).attr('src', $(this).attr('data-src')).removeAttr('data-src');
        });
    } else if ('IntersectionObserver' in window) {
        // Polyfill with Intersection Observer
        var lazyImages = document.querySelectorAll('img[data-src]');
        var lazyImageObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    var img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    lazyImageObserver.unobserve(img);
                }
            });
        }, { rootMargin: '50px 0px' });

        lazyImages.forEach(function(img) {
            lazyImageObserver.observe(img);
        });
    } else {
        // Fallback for old browsers
        $('img[data-src]').each(function() {
            $(this).attr('src', $(this).attr('data-src')).removeAttr('data-src');
        });
    }

    // ========== Improved Touch Handling for Buttons ==========
    if (supportsTouch) {
        // Faster tap response on touch devices
        $('.btn, .amount-btn, .service-link, .blog-card, .event-card').on('touchstart', function() {
            $(this).addClass('touch-active');
        }).on('touchend touchcancel', function() {
            var $this = $(this);
            setTimeout(function() {
                $this.removeClass('touch-active');
            }, 150);
        });
    }

    // ========== Prevent Double-tap Zoom on Buttons ==========
    if (supportsTouch) {
        var lastTouchEnd = 0;
        $('.btn, button, a').on('touchend', function(e) {
            var now = Date.now();
            if (now - lastTouchEnd <= 300) {
                e.preventDefault();
            }
            lastTouchEnd = now;
        });
    }

    // ========== Optimize Images for Retina Displays ==========
    if (window.devicePixelRatio > 1) {
        $('img[data-2x]').each(function() {
            var src2x = $(this).attr('data-2x');
            if (src2x) {
                $(this).attr('src', src2x);
            }
        });
    }

    // ========== Auto-resize Text Inputs on Mobile ==========
    if (isMobile) {
        $('input[type="text"], input[type="email"], input[type="tel"], textarea').on('focus', function() {
            $(this).css('font-size', '16px'); // Prevent iOS zoom
        });
    }

    // ========== Debounced Resize Handler ==========
    var resizeTimer;
    $(window).on('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            // Handle orientation change
            if (isMobile) {
                closeMenu();
            }
        }, 250);
    });

    // ========== Performance: Reduce Motion for Low-Power Mode ==========
    var prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    if (prefersReducedMotion.matches) {
        $('html').addClass('reduce-motion');
    }

    // ========== Back Button Cache Fix (Safari) ==========
    $(window).on('pageshow', function(event) {
        if (event.originalEvent.persisted) {
            window.location.reload();
        }
    });

    // ========== Console Info ==========
    if (console && console.log) {
        console.log('%c🕉️ Sri Sai Mission', 'color: #5F2C70; font-size: 20px; font-weight: bold;');
        console.log('%cWebsite built with love and devotion', 'color: #9FA73E; font-size: 12px;');
        console.log('%cDevice: ' + (isMobile ? 'Mobile' : 'Desktop') + ' | Touch: ' + (supportsTouch ? 'Yes' : 'No'), 'color: #888;');
    }

})(jQuery);
</script>
