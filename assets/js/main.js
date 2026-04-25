document.addEventListener('DOMContentLoaded', () => {
    /**
     * EXPERT REFACTOR: Interaction Module
     */

    // --- 1. SMOOTH SCROLL (Lenis) ---
    const lenis = new Lenis({
        duration: 1.2,
        easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
        smoothWheel: true
    });

    function raf(time) {
        lenis.raf(time);
        requestAnimationFrame(raf);
    }
    requestAnimationFrame(raf);

    // --- 2. AJAX PAGE LOADER (Swup) ---
    let swup;
    try {
        if (typeof Swup !== 'undefined') {
            swup = new Swup({
                containers: ['#swup'],
                cache: true,
                plugins: []
            });

            // Swup v4 uses a different event system (hooks)
            // swup.on('contentReplaced') -> swup.hooks.on('page:view') or 'content:replace'
            if (swup.hooks) {
                swup.hooks.on('page:view', () => {
                    lenis.scrollTo(0, { immediate: true });
                    initInteractionEngine();
                });
            } else if (typeof swup.on === 'function') {
                // Fallback for v3 just in case
                swup.on('contentReplaced', () => {
                    lenis.scrollTo(0, { immediate: true });
                    initInteractionEngine();
                });
            }
        }
    } catch (e) {
        console.error('Swup initialization failed:', e);
    }

    // --- 3. INTERACTION ENGINE (Expert Mode) ---
    const initInteractionEngine = () => {
        initMagnetic();
        initGlitch();
        initMobileMenu();
        initHeaderScroll();
        initDropdowns();
        initHeroSlider();
    };

    // --- 3a. HERO SLIDER (CUST-002) ---
    const initHeroSlider = () => {
        const sliderWrapper = document.querySelector('.gnn-hero-slider-wrapper');
        if (!sliderWrapper) return;

        const slides = sliderWrapper.querySelectorAll('.gnn-slide');
        const prevBtn = sliderWrapper.querySelector('.slider-prev');
        const nextBtn = sliderWrapper.querySelector('.slider-next');
        const dotsContainer = sliderWrapper.querySelector('.slider-dots');
        
        // Config from data attributes
        const speed = parseInt(sliderWrapper.getAttribute('data-speed')) || 6000;
        const pauseOnHover = sliderWrapper.getAttribute('data-pause') === 'true';

        if (slides.length <= 1) {
            if (slides[0]) slides[0].classList.add('is-active');
            if (prevBtn) prevBtn.style.display = 'none';
            if (nextBtn) nextBtn.style.display = 'none';
            return;
        }

        let currentIndex = 0;
        let isAnimating = false;
        let autoPlayTimer;

        // Generate dots
        if (dotsContainer) {
            slides.forEach((_, i) => {
                const dot = document.createElement('div');
                dot.classList.add('slider-dot');
                if (i === 0) dot.classList.add('is-active');
                dot.addEventListener('click', () => {
                    if (i !== currentIndex) showSlide(i, i > currentIndex ? 'next' : 'prev');
                });
                dotsContainer.appendChild(dot);
            });
        }

        const updateDots = (index) => {
            if (!dotsContainer) return;
            dotsContainer.querySelectorAll('.slider-dot').forEach((dot, i) => {
                dot.classList.toggle('is-active', i === index);
            });
        };

        const showSlide = (index, direction = 'next') => {
            if (isAnimating || index === currentIndex) return;
            isAnimating = true;

            const currentSlide = slides[currentIndex];
            const nextSlide = slides[index];

            updateDots(index);

            const tl = gsap.timeline({
                onComplete: () => {
                    currentSlide.classList.remove('is-active');
                    isAnimating = false;
                    currentIndex = index;
                }
            });

            // Content Out
            tl.to(currentSlide.querySelector('.hero-content-wrapper'), {
                y: direction === 'next' ? -40 : 40,
                opacity: 0,
                duration: 0.6,
                ease: "power2.inOut"
            });

            // Slide Out
            tl.to(currentSlide, {
                opacity: 0,
                duration: 0.8,
                ease: "expo.inOut"
            }, "-=0.4");

            // Slide In
            tl.set(nextSlide, { opacity: 0, visibility: 'visible', zIndex: 3 });
            nextSlide.classList.add('is-active');

            tl.fromTo(nextSlide, 
                { opacity: 0 },
                { opacity: 1, duration: 1, ease: "expo.inOut" },
                "-=0.6"
            );

            // Content In
            tl.fromTo(nextSlide.querySelector('.hero-content-wrapper'),
                { y: direction === 'next' ? 40 : -40, opacity: 0 },
                { y: 0, opacity: 1, duration: 1, ease: "power4.out" },
                "-=0.5"
            );
        };

        // Event Listeners
        if (nextBtn) nextBtn.addEventListener('click', () => {
            showSlide((currentIndex + 1) % slides.length, 'next');
            resetAutoPlay();
        });

        if (prevBtn) prevBtn.addEventListener('click', () => {
            showSlide((currentIndex - 1 + slides.length) % slides.length, 'prev');
            resetAutoPlay();
        });

        // Touch/Swipe Support
        let touchStartX = 0;
        sliderWrapper.addEventListener('touchstart', e => touchStartX = e.changedTouches[0].screenX, {passive: true});
        sliderWrapper.addEventListener('touchend', e => {
            const touchEndX = e.changedTouches[0].screenX;
            if (touchStartX - touchEndX > 50) nextBtn?.click();
            if (touchEndX - touchStartX > 50) prevBtn?.click();
        }, {passive: true});

        // Autoplay Logic
        const startAutoPlay = () => {
            if (speed > 0) {
                autoPlayTimer = setInterval(() => {
                    showSlide((currentIndex + 1) % slides.length, 'next');
                }, speed);
            }
        };

        const resetAutoPlay = () => {
            clearInterval(autoPlayTimer);
            startAutoPlay();
        };

        if (pauseOnHover) {
            sliderWrapper.addEventListener('mouseenter', () => clearInterval(autoPlayTimer));
            sliderWrapper.addEventListener('mouseleave', startAutoPlay);
        }

        startAutoPlay();

        // Init initial slide state
        slides[0].classList.add('is-active');
        gsap.set(slides[0], { opacity: 1, visibility: 'visible' });
    };


    // --- 3b. HEADER SCROLL ---
    const initHeaderScroll = () => {
        const header = document.querySelector('.site-top-bar');
        if (!header) return;

        const checkScroll = () => {
            if (window.scrollY > 50) {
                header.classList.add('is-scrolled');
            } else {
                header.classList.remove('is-scrolled');
            }
        };

        window.addEventListener('scroll', checkScroll, { passive: true });
        checkScroll();
    };

    // --- 3b. MAGNETIC ELEMENTS ---
    const initMagnetic = () => {
        const magnets = document.querySelectorAll('.hero-title, .gnn-service-card, .site-title');
        magnets.forEach((mag) => {
            mag.addEventListener('pointermove', (e) => {
                const rect = mag.getBoundingClientRect();
                const x = e.clientX - rect.left - rect.width / 2;
                const y = e.clientY - rect.top - rect.height / 2;
                gsap.to(mag, {
                    x: x * 0.25,
                    y: y * 0.25,
                    duration: 0.4,
                    ease: "power2.out"
                });
            });
            mag.addEventListener('pointerleave', () => {
                gsap.to(mag, {
                    x: 0,
                    y: 0,
                    duration: 0.6,
                    ease: "elastic.out(1, 0.3)"
                });
            });
        });
    };

    // --- 3c. GLITCH EFFECT ---
    const initGlitch = () => {
        const titles = document.querySelectorAll('.hero-title, .entry-title, .site-title');
        titles.forEach(t => {
            t.classList.add('glitch');
            if (!t.hasAttribute('data-text')) {
                t.setAttribute('data-text', t.textContent.trim());
            }
        });
    };

    // --- 3d. DROPDOWNS ---
    const initDropdowns = () => {
        const items = document.querySelectorAll('.corner-nav li.menu-item-has-children');
        items.forEach(item => {
            const subMenu = item.querySelector('.sub-menu');
            if (!subMenu) return;

            item.addEventListener('mouseenter', () => {
                gsap.fromTo(subMenu, 
                    { opacity: 0, y: 10, display: 'none' },
                    { opacity: 1, y: 0, display: 'block', duration: 0.4, ease: "power3.out" }
                );
            });

            item.addEventListener('mouseleave', () => {
                gsap.to(subMenu, { opacity: 0, y: 10, display: 'none', duration: 0.3, ease: "power3.in" });
            });
        });
    };

    // --- 3e. MOBILE MENU ---
    const initMobileMenu = () => {
        const hamburger = document.getElementById('hamburger-menu');
        const mobileOverlay = document.getElementById('mobile-menu-overlay');

        if (!hamburger || !mobileOverlay) return;

        // Prevent multiple attachments
        if (hamburger.getAttribute('data-gnn-init')) return;
        hamburger.setAttribute('data-gnn-init', 'true');

        const closeMenu = () => {
            hamburger.classList.remove('is-active');
            mobileOverlay.classList.remove('is-active');
            document.body.style.overflow = '';
        };

        const toggleMenu = (e) => {
            e.preventDefault();
            const isActive = hamburger.classList.toggle('is-active');
            mobileOverlay.classList.toggle('is-active', isActive);
            document.body.style.overflow = isActive ? 'hidden' : '';

            if (isActive) {
                gsap.from('#mobile-menu-overlay li', {
                    y: 30,
                    opacity: 0,
                    stagger: 0.1,
                    duration: 0.6,
                    ease: "power4.out",
                    delay: 0.2
                });
            }
        };

        hamburger.addEventListener('click', toggleMenu);

        mobileOverlay.addEventListener('click', (e) => {
            if (e.target.tagName === 'A' || e.target === mobileOverlay) {
                closeMenu();
            }
        });

        const submenuLinks = mobileOverlay.querySelectorAll('li.menu-item-has-children > a');
        submenuLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                if (window.innerWidth > 768) return;
                e.preventDefault();
                const parentLi = link.parentElement;
                parentLi.classList.toggle('submenu-open');
            });
        });

        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && mobileOverlay.classList.contains('is-active')) {
                closeMenu();
            }
        });
    };

    // --- 3f. BACK TO TOP ---
    const initBackToTop = () => {
        const btn = document.getElementById('gnn-back-to-top');
        if (!btn) return;

        if (btn.getAttribute('data-gnn-init')) return;
        btn.setAttribute('data-gnn-init', 'true');

        const toggleVisibility = () => {
            const scrollY = window.scrollY || document.documentElement.scrollTop;
            if (scrollY > 400) {
                btn.classList.add('is-visible');
            } else {
                btn.classList.remove('is-visible');
            }
        };

        window.addEventListener('scroll', toggleVisibility, { passive: true });
        toggleVisibility();

        btn.addEventListener('click', () => {
            if (typeof lenis !== 'undefined' && lenis.scrollTo) {
                lenis.scrollTo(0, { duration: 1.2 });
            } else {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    };

    initInteractionEngine();
    initBackToTop();
});
