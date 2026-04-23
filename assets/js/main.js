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
    };

    // --- 3b. MAGNETIC ELEMENTS ---
    const initMagnetic = () => {
        const magnets = document.querySelectorAll('.hero-title, .gnn-service-card');
        magnets.forEach((mag) => {
            mag.addEventListener('pointermove', (e) => {
                const rect = mag.getBoundingClientRect();
                const x = e.clientX - rect.left - rect.width / 2;
                const y = e.clientY - rect.top - rect.height / 2;
                gsap.to(mag, {
                    x: x * 0.35,
                    y: y * 0.35,
                    duration: 0.4,
                    ease: "power2.out"
                });
            });
            mag.addEventListener('pointerleave', () => {
                gsap.to(mag, {
                    x: 0,
                    y: 0,
                    duration: 0.6,
                    ease: "elastic.out(1.1, 0.4)"
                });
            });
        });
    };

    // --- 3c. GLITCH EFFECT ---
    const initGlitch = () => {
        const titles = document.querySelectorAll('.hero-title, .entry-title, .corner-nav.top-left a:not(.custom-logo-link)');
        titles.forEach(t => {
            t.classList.add('glitch');
            if (!t.hasAttribute('data-text')) {
                t.setAttribute('data-text', t.textContent.trim());
            }
        });
    };

    // --- 3d. MOBILE MENU ---
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
        };

        hamburger.addEventListener('click', toggleMenu);

        // Close menu when clicking links or the overlay itself
        mobileOverlay.addEventListener('click', (e) => {
            if (e.target.tagName === 'A' || e.target === mobileOverlay) {
                closeMenu();
            }
        });
        // --- MOBILE SUBMENU TOGGLE ---
        const submenuLinks = mobileOverlay.querySelectorAll(
            'li.menu-item-has-children > a'
        );

        submenuLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                // Sadece mobilde çalışsın
                if (window.innerWidth > 768) return;

                e.preventDefault();

                const parentLi = link.parentElement;
                parentLi.classList.toggle('submenu-open');
            });
        });


        // ESC key to close
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && mobileOverlay.classList.contains('is-active')) {
                closeMenu();
            }
        });
    };

    // --- 3e. BACK TO TOP ---
    const initBackToTop = () => {
        const btn = document.getElementById('gnn-back-to-top');
        if (!btn) return;

        // Prevent multiple attachments
        if (btn.getAttribute('data-gnn-init')) return;
        btn.setAttribute('data-gnn-init', 'true');

        // Show/hide based on scroll position
        const toggleVisibility = () => {
            const scrollY = window.scrollY || document.documentElement.scrollTop;
            if (scrollY > 400) {
                btn.classList.add('is-visible');
            } else {
                btn.classList.remove('is-visible');
            }
        };

        window.addEventListener('scroll', toggleVisibility, { passive: true });
        toggleVisibility(); // Check initial state

        // Scroll to top on click (use Lenis if available)
        btn.addEventListener('click', () => {
            if (typeof lenis !== 'undefined' && lenis.scrollTo) {
                lenis.scrollTo(0, { duration: 1.2 });
            } else {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    };

    // Initial Start
    initInteractionEngine();
    initBackToTop();
});
