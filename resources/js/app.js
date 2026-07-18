import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const main = document.querySelector('main');

    if (!main) {
        return;
    }

    document.body.classList.add('premium-page-enter');
    document.querySelector('header')?.classList.add('premium-page-enter');

    const revealTargets = [
        ...main.querySelectorAll('section, aside > div, main .grid > div, main form'),
        ...document.querySelectorAll('footer > div, footer .grid > div'),
    ];

    const seen = new WeakSet();
    revealTargets.forEach((element, index) => {
        if (seen.has(element)) {
            return;
        }
        seen.add(element);

        element.classList.add('premium-reveal');
        element.style.setProperty('--reveal-delay', `${Math.min(index % 8, 7) * 55}ms`);

        if (element.matches('section')) {
            element.classList.add('premium-section-glow');
        }

        if (element.matches('aside > div')) {
            element.classList.add('premium-reveal-left');
        } else if (element.matches('main .grid > div:nth-child(even)')) {
            element.classList.add('premium-reveal-right');
        }
    });

    const textTargets = [
        ...main.querySelectorAll('h1, h2, h3, h4, h5, h6, p, li, label, blockquote, figcaption, form button, form a, .inline-flex'),
        ...document.querySelectorAll('footer h1, footer h2, footer h3, footer h4, footer p, footer li, footer a, header nav a'),
    ];

    const textSeen = new WeakSet();
    textTargets.forEach((element, index) => {
        if (textSeen.has(element) || element.closest('#mobile-drawer') || element.classList.contains('sr-only')) {
            return;
        }

        const text = element.textContent?.trim();
        if (!text || text.length < 2) {
            return;
        }

        textSeen.add(element);
        element.classList.add('premium-text-reveal');
        element.style.setProperty('--text-reveal-delay', `${Math.min(index % 10, 9) * 42}ms`);
    });

    const cardSelectors = [
        '.rounded-\\[24px\\]',
        '.rounded-\\[32px\\]',
        '.rounded-2xl',
        '.rounded-3xl',
    ].join(',');

    main.querySelectorAll(cardSelectors).forEach((element) => {
        if (!element.closest('header, footer, #mobile-drawer')) {
            element.classList.add('premium-card');
        }
    });

    main.querySelectorAll('a[class*="bg-"], button[class*="bg-"]').forEach((element) => {
        element.classList.add('premium-button');
    });

    document.querySelectorAll('footer a[class*="bg-"], footer button[class*="bg-"], header a[class*="bg-"]').forEach((element) => {
        element.classList.add('premium-button');
    });

    document.querySelectorAll('header nav a').forEach((element) => {
        element.classList.add('premium-nav-link');
    });

    main.querySelectorAll('img').forEach((image) => {
        const parent = image.parentElement;
        if (parent) {
            parent.classList.add('premium-media');
        }
    });

    document.querySelectorAll('a[href*="wa.me"], header .rounded-full.bg-\\[\\#006F5C\\]').forEach((element) => {
        element.classList.add('premium-pulse');
    });

    const heroBadge = main.querySelector('section:first-child span');
    if (heroBadge) {
        heroBadge.classList.add('premium-float');
    }

    // Make text immediately visible - reveal animations are just a bonus
    document.querySelectorAll('.premium-text-reveal').forEach((element) => {
        element.classList.add('is-visible');
    });

    if (prefersReducedMotion || !('IntersectionObserver' in window)) {
        document.querySelectorAll('.premium-reveal').forEach((element) => {
            element.classList.add('is-visible');
        });
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.12,
        rootMargin: '0px 0px -8% 0px',
    });

    document.querySelectorAll('.premium-reveal').forEach((element) => observer.observe(element));
});
