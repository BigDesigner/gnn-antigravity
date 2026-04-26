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
        initCustomCursor();
        initMagnetic();
        initGlitch();
        initMobileMenu();
        initHeaderScroll();
        initDropdowns();
        initHeroSlider();
    };

    // --- 3a. CUSTOM CURSOR (UI-002) ---
    let cursorInitialized = false;
    
    const initCustomCursor = () => {
        if (typeof gnnSettings !== 'undefined' && !gnnSettings.customCursor) return;
        
        // Touch devices usually don't have a fine pointer, so CSS handles hiding it,
        // but we can also avoid running heavy JS on them.
        if (window.matchMedia("(pointer: coarse)").matches) return;

        const cursor = document.querySelector('.gnn-cursor');
        const follower = document.querySelector('.gnn-cursor-follower');
        
        if (!cursor || !follower) return;

        document.body.classList.add('has-custom-cursor');

        // Only attach mousemove once globally
        if (!cursorInitialized) {
            let xTo = gsap.quickTo(cursor, "x", {duration: 0.1, ease: "power3"});
            let yTo = gsap.quickTo(cursor, "y", {duration: 0.1, ease: "power3"});
            
            let fXTo = gsap.quickTo(follower, "x", {duration: 0.6, ease: "power3"});
            let fYTo = gsap.quickTo(follower, "y", {duration: 0.6, ease: "power3"});

            window.addEventListener("mousemove", (e) => {
                xTo(e.clientX);
                yTo(e.clientY);
                fXTo(e.clientX);
                fYTo(e.clientY);
            });
            cursorInitialized = true;
        }

        // Hover effect for links and buttons (re-attached on Swup transitions)
        const hoverElements = document.querySelectorAll('a, button, input, textarea, select, .gnn-service-card');
        hoverElements.forEach(el => {
            // Remove old listeners just in case
            const enter = () => {
                cursor.classList.add('is-hovering');
                follower.classList.add('is-hovering');
            };
            const leave = () => {
                cursor.classList.remove('is-hovering');
                follower.classList.remove('is-hovering');
            };
            
            // This ensures we don't duplicate on same elements during partial updates
            el.addEventListener('mouseenter', enter);
            el.addEventListener('mouseleave', leave);
            el.addEventListener('focusin', enter);
            el.addEventListener('focusout', leave);
        });
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
        
        // Keyboard Navigation
        sliderWrapper.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') {
                prevBtn?.click();
            } else if (e.key === 'ArrowRight') {
                nextBtn?.click();
            }
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

            let leaveTimeout;

            const showSubMenu = () => {
                clearTimeout(leaveTimeout);
                gsap.killTweensOf(subMenu);
                gsap.fromTo(subMenu, 
                    { opacity: 0, y: 10, display: 'none' },
                    { 
                        opacity: 1, 
                        y: 0, 
                        display: 'block', 
                        duration: 0.4, 
                        ease: "power3.out",
                        onStart: () => { subMenu.style.display = 'block'; }
                    }
                );
            };

            const hideSubMenu = () => {
                leaveTimeout = setTimeout(() => {
                    gsap.to(subMenu, { 
                        opacity: 0, 
                        y: 10, 
                        duration: 0.3, 
                        ease: "power3.in",
                        onComplete: () => { subMenu.style.display = 'none'; }
                    });
                }, 150); // Small delay to prevent flickering
            };

            item.addEventListener('mouseenter', showSubMenu);
            item.addEventListener('mouseleave', hideSubMenu);

            // Focus support for accessibility
            item.addEventListener('focusin', showSubMenu);
            item.addEventListener('focusout', (e) => {
                if (!item.contains(e.relatedTarget)) {
                    hideSubMenu();
                }
            });
        });
    };

    // --- 3e. MOBILE MENU ---
    const initMobileMenu = () => {
        const hamburger = document.getElementById('hamburger-menu');
        const mobileOverlay = document.getElementById('mobile-menu-overlay');

        if (!hamburger || !mobileOverlay) return;

        // Prevent multiple attachments during swup refreshes
        const newHamburger = hamburger.cloneNode(true);
        hamburger.parentNode.replaceChild(newHamburger, hamburger);

        const closeMenu = () => {
            newHamburger.classList.remove('is-active');
            mobileOverlay.classList.remove('is-active');
            newHamburger.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
            
            gsap.to(mobileOverlay, {
                opacity: 0,
                duration: 0.3,
                onComplete: () => {
                    mobileOverlay.style.display = 'none';
                }
            });
        };

        const toggleMenu = (e) => {
            if (e) e.preventDefault();
            const isActive = !newHamburger.classList.contains('is-active');
            
            newHamburger.classList.toggle('is-active', isActive);
            newHamburger.setAttribute('aria-expanded', isActive ? 'true' : 'false');
            document.body.style.overflow = isActive ? 'hidden' : '';

            if (isActive) {
                mobileOverlay.style.display = 'flex';
                gsap.to(mobileOverlay, {
                    opacity: 1,
                    duration: 0.4,
                    ease: "power2.out"
                });

                gsap.from('#mobile-menu-overlay li', {
                    y: 30,
                    opacity: 0,
                    stagger: 0.05,
                    duration: 0.5,
                    ease: "power3.out",
                    delay: 0.1
                });
            } else {
                closeMenu();
            }
        };

        newHamburger.addEventListener('click', toggleMenu);

        // Submenu logic for mobile
        const mobileLinks = mobileOverlay.querySelectorAll('.menu-item-has-children > a');
        mobileLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                // If it's a mobile view, toggle submenu
                if (window.innerWidth <= 768) {
                    const parentLi = link.parentElement;
                    const isOpen = parentLi.classList.contains('submenu-open');
                    
                    // Close other submenus
                    mobileOverlay.querySelectorAll('.menu-item-has-children').forEach(li => {
                        if (li !== parentLi) li.classList.remove('submenu-open');
                    });

                    if (!isOpen) {
                        e.preventDefault();
                        parentLi.classList.add('submenu-open');
                        
                        // Animate submenu items
                        gsap.from(parentLi.querySelectorAll('.sub-menu li'), {
                            x: -20,
                            opacity: 0,
                            stagger: 0.05,
                            duration: 0.4
                        });
                    }
                }
            });
        });

        // Close on escape
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && mobileOverlay.classList.contains('is-active')) {
                closeMenu();
            }
        });

        // Close on link click (non-submenu links)
        mobileOverlay.addEventListener('click', (e) => {
            if (e.target.tagName === 'A' && !e.target.parentElement.classList.contains('menu-item-has-children')) {
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
