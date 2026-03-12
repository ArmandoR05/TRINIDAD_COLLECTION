document.addEventListener('DOMContentLoaded', function () {
    /* =========================
       LOGO SCROLL
    ========================= */
    const siteHeader = document.querySelector('.site-header');

    if (siteHeader) {
        window.addEventListener('scroll', function () {
            if (window.scrollY > 60) {
                siteHeader.classList.add('scrolled');
            } else {
                siteHeader.classList.remove('scrolled');
            }
        }, { passive: true });
    }


    /* =========================
       MENÚ LATERAL
    ========================= */
    const menuToggle = document.getElementById('menuToggle');
    const menuPanel = document.getElementById('menuPanel');
    const menuOverlay = document.getElementById('menuOverlay');

    function openMenu() {
        if (menuPanel) menuPanel.classList.add('active');
        if (menuOverlay) menuOverlay.classList.add('active');
        document.body.classList.add('menu-open');
    }

    function closeMenu() {
        if (menuPanel) menuPanel.classList.remove('active');
        if (menuOverlay) menuOverlay.classList.remove('active');
        document.body.classList.remove('menu-open');
    }

    if (menuToggle) {
        menuToggle.addEventListener('click', function () {
            if (menuPanel && menuPanel.classList.contains('active')) {
                closeMenu();
            } else {
                openMenu();
            }
        });
    }

    if (menuOverlay) {
        menuOverlay.addEventListener('click', closeMenu);
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeMenu();
        }
    });

    /* =========================
       GALERÍA PRODUCTO
    ========================= */
    const mainImage = document.getElementById('mainProductImage');
    const thumbButtons = document.querySelectorAll('.thumb-btn');

    if (mainImage && thumbButtons.length > 0) {
        thumbButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                const newImage = this.getAttribute('data-image');

                if (newImage) {
                    mainImage.src = newImage;

                    thumbButtons.forEach(function (btn) {
                        btn.classList.remove('active');
                    });

                    this.classList.add('active');
                }
            });
        });
    }

    /* =========================
       BANNER INFINITO
    ========================= */
    const marquee = document.getElementById('topBannerMarquee');
    const track = document.getElementById('topBannerTrack');

    if (marquee && track) {
        const originalHTML = track.innerHTML.trim();

        if (originalHTML !== '') {
            let animationFrameId = null;
            let resizeTimeout = null;
            let paused = false;
            let position = 0;
            let speed = 1.3;
            let singleSetWidth = 0;

            function buildBannerTrack() {
                if (animationFrameId) {
                    cancelAnimationFrame(animationFrameId);
                    animationFrameId = null;
                }

                track.innerHTML = originalHTML;
                track.style.transform = 'translateX(0px)';

                let safety = 0;
                while (track.scrollWidth < marquee.offsetWidth * 2.0 && safety < 20) {
                    track.innerHTML += originalHTML;
                    safety++;
                }

                const duplicatedHTML = track.innerHTML;
                track.innerHTML += duplicatedHTML;

                singleSetWidth = track.scrollWidth / 2;
                position = 0;
            }

            function animateBanner() {
                if (!paused) {
                    position += speed;

                    if (position >= singleSetWidth) {
                        position = 0;
                    }

                    track.style.transform = `translateX(${-position}px)`;
                }

                animationFrameId = requestAnimationFrame(animateBanner);
            }

            marquee.addEventListener('mouseenter', function () {
                paused = true;
            });

            marquee.addEventListener('mouseleave', function () {
                paused = false;
            });

            window.addEventListener('resize', function () {
                clearTimeout(resizeTimeout);

                resizeTimeout = setTimeout(function () {
                    buildBannerTrack();
                }, 150);
            });

            buildBannerTrack();
            animateBanner();
        }
    }
});