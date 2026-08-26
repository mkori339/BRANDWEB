// Video Modal - global function called from onclick
function openVideoModal(youtubeId) {
    var modal = new bootstrap.Modal(document.getElementById('videoModal'));
    var iframe = document.getElementById('videoModalIframe');
    // Use nocookie domain for better privacy and shorts compatibility
    iframe.src = 'https://www.youtube-nocookie.com/embed/' + youtubeId + '?autoplay=1&rel=0';
    modal.show();
}

// Also support opening video modal from a full YouTube URL (handles shorts, watch, etc.)
function openVideoModalFromUrl(url) {
    var patterns = [
        /[?&]v=([a-zA-Z0-9_-]{11})/,
        /youtu\.be\/([a-zA-Z0-9_-]+)/,
        /youtube\.com\/embed\/([a-zA-Z0-9_-]+)/,
        /youtube\.com\/shorts\/([a-zA-Z0-9_-]+)/,
        /youtube\.com\/live\/([a-zA-Z0-9_-]+)/,
    ];
    for (var i = 0; i < patterns.length; i++) {
        var m = url.match(patterns[i]);
        if (m && m[1]) {
            openVideoModal(m[1]);
            return;
        }
    }
}

// Reset iframe src when modal is closed (stops video playback)
document.addEventListener('DOMContentLoaded', function() {
    var videoModal = document.getElementById('videoModal');
    if (videoModal) {
        videoModal.addEventListener('hidden.bs.modal', function () {
            document.getElementById('videoModalIframe').src = '';
        });
    }
});


(function ($) {
    "use strict";

    // Top progress bar on page load
    var bar = document.getElementById('progressBar');
    if (bar) {
        bar.style.width = '70%';
        setTimeout(function() {
            bar.style.width = '100%';
            setTimeout(function() {
                bar.style.opacity = '0';
                setTimeout(function() {
                    bar.style.width = '0%';
                    bar.style.opacity = '1';
                }, 300);
            }, 200);
        }, 100);
    }

    // Show progress bar on internal link clicks
    $(document).on('click', 'a[href]', function(e) {
        var href = $(this).attr('href');
        if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:') || $(this).attr('target') === '_blank' || href.includes('data-bs-toggle') || $(this).hasClass('back-to-top')) return;
        if (href.indexOf('admin/') !== -1 || href.indexOf('admin.php') !== -1) return;
        if (bar) {
            bar.style.width = '0%';
            bar.style.opacity = '1';
            bar.style.transition = 'none';
            setTimeout(function() {
                bar.style.transition = 'width 0.8s ease';
                bar.style.width = '90%';
            }, 10);
        }
    });

    // WOW animations
    new WOW().init();

    // Header carousel
    $(".header-carousel").owlCarousel({
        animateOut: 'fadeOut',
        items: 1,
        margin: 0,
        stagePadding: 0,
        autoplay: true,
        autoplayTimeout: 6000,
        smartSpeed: 1200,
        dots: false,
        loop: true,
        nav: true,
        navText: [
            '<i class="bi bi-arrow-left"></i>',
            '<i class="bi bi-arrow-right"></i>'
        ],
    });

    // Service carousel
    $(".service-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 2000,
        center: false,
        dots: false,
        loop: true,
        margin: 25,
        nav: true,
        navText: [
            '<i class="bi bi-arrow-left"></i>',
            '<i class="bi bi-arrow-right"></i>'
        ],
        responsiveClass: true,
        responsive: {
            0:    { items: 1 },
            576:  { items: 1 },
            768:  { items: 2 },
            992:  { items: 2 },
            1200: { items: 2 }
        }
    });

    // Testimonial carousel
    $(".testimonial-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1500,
        center: false,
        dots: true,
        loop: true,
        margin: 25,
        nav: false,
        responsiveClass: true,
        responsive: {
            0:    { items: 1 },
            768:  { items: 1 },
            1200: { items: 2 }
        }
    });

    // Back to top
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });

    $('.back-to-top').click(function () {
        $('html, body').animate({ scrollTop: 0 }, 1500, 'easeInOutExpo');
        return false;
    });

    // Navbar shadow on scroll
    $(window).scroll(function () {
        if ($(this).scrollTop() > 10) {
            $('.header-top').addClass('scrolled');
        } else {
            $('.header-top').removeClass('scrolled');
        }
    });

    // Smooth hover underline on nav items (desktop)
    if (window.innerWidth >= 992) {
        $('.navbar .navbar-nav .nav-item .nav-link').each(function () {
            $(this).css({
                'position': 'relative',
                'padding-bottom': '4px'
            });
        });
    }

    // Counter animation for stats
    function animateCounters() {
        $('.counter').each(function () {
            var $el = $(this);
            if ($el.data('animated')) return;
            $el.data('animated', true);

            var target = parseInt($el.text().replace(/[^0-9]/g, ''), 10);
            var suffix = $el.text().replace(/[0-9]/g, '').trim();
            var duration = 1800;
            var step = target / (duration / 16);
            var current = 0;

            var timer = setInterval(function () {
                current += step;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                $el.text(Math.floor(current) + suffix);
            }, 16);
        });
    }

    // Trigger counter when section is in view
    var counterSection = $('.stats-section');
    if (counterSection.length) {
        var triggered = false;
        $(window).scroll(function () {
            if (triggered) return;
            var top = counterSection.offset().top;
            if ($(window).scrollTop() + $(window).height() >= top + 100) {
                triggered = true;
                animateCounters();
            }
        });
    }

    // Lazy-load images that have data-src
    if ('IntersectionObserver' in window) {
        var imgObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    var img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                    }
                    imgObserver.unobserve(img);
                }
            });
        });
        document.querySelectorAll('img[data-src]').forEach(function (img) {
            imgObserver.observe(img);
        });
    }

})(jQuery);
