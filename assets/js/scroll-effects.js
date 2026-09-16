(() => {
  'use strict';

  const start = () => {
    const hasGsap = window.gsap && window.ScrollTrigger;
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const desktop = window.matchMedia('(min-width: 992px)').matches;

    if (!hasGsap || reduceMotion) return;

    const { gsap, ScrollTrigger } = window;
    gsap.registerPlugin(ScrollTrigger);

    if (window.Lenis) {
      const lenis = new window.Lenis({
        duration: 1.05,
        smoothWheel: true,
        syncTouch: false,
        wheelMultiplier: 0.9
      });
      window.siteLenis = lenis;
      lenis.on('scroll', ScrollTrigger.update);
      gsap.ticker.add((time) => lenis.raf(time * 1000));
      gsap.ticker.lagSmoothing(0);
    }

    const hero = document.querySelector('[data-hero-section]');
    const heroTitle = document.querySelector('[data-hero-title]');
    const heroMedia = document.querySelector('[data-hero-media]');
    if (hero && heroTitle) {
      gsap.to(heroTitle, {
        scale: 1.18,
        yPercent: 18,
        transformOrigin: 'center center',
        ease: 'none',
        scrollTrigger: { trigger: hero, start: 'top top', end: 'bottom top', scrub: 1 }
      });
    }
    if (hero && heroMedia) {
      gsap.to(heroMedia, {
        yPercent: -18,
        rotate: 2,
        ease: 'none',
        scrollTrigger: { trigger: hero, start: 'top top', end: 'bottom top', scrub: 1 }
      });
    }

    document.querySelectorAll('.split-heading').forEach((heading) => {
      const words = heading.textContent.trim().split(/\s+/);
      heading.setAttribute('aria-label', heading.textContent.trim());
      heading.textContent = '';
      words.forEach((word) => {
        const span = document.createElement('span');
        span.className = 'split-word';
        span.textContent = word;
        heading.appendChild(span);
      });
      gsap.fromTo(heading.querySelectorAll('.split-word'),
        { yPercent: 110, rotateX: -90, transformPerspective: 700, opacity: 0 },
        {
          yPercent: 0,
          rotateX: 0,
          opacity: 1,
          duration: 0.7,
          stagger: 0.045,
          ease: 'power3.out',
          scrollTrigger: { trigger: heading, start: 'top 82%', once: true }
        });
    });

    document.querySelectorAll('.split-supporting-text').forEach((text) => {
      const words = text.textContent.trim().split(/\s+/);
      text.setAttribute('aria-label', text.textContent.trim());
      text.textContent = '';
      words.forEach((word) => {
        const span = document.createElement('span');
        span.className = 'supporting-word';
        span.textContent = word;
        text.appendChild(span);
      });
      gsap.fromTo(text.querySelectorAll('.supporting-word'),
        { yPercent: 115, opacity: 0 },
        {
          yPercent: 0,
          opacity: 1,
          duration: 0.55,
          stagger: 0.025,
          ease: 'power2.out',
          scrollTrigger: { trigger: text, start: 'top 86%', once: true }
        });
    });

    document.querySelectorAll('[data-parallax-card]').forEach((card) => {
      gsap.to(card, {
        yPercent: -8,
        ease: 'none',
        scrollTrigger: { trigger: card, start: 'top bottom', end: 'bottom top', scrub: 1.2 }
      });
    });

    const priceSection = document.querySelector('[data-price-section]');
    const priceCards = priceSection?.querySelectorAll('[data-price-card]');
    if (priceSection && priceCards?.length) {
      gsap.fromTo(priceCards,
        { y: 72, opacity: 0 },
        {
          y: 0,
          opacity: 1,
          duration: 0.85,
          stagger: 0.12,
          ease: 'power3.out',
          scrollTrigger: { trigger: priceSection, start: 'top 72%', once: true }
        });
    }

    const processSection = document.querySelector('[data-process-section]');
    const processSteps = processSection?.querySelectorAll('[data-process-step]');
    if (processSection && processSteps?.length) {
      gsap.fromTo(processSteps,
        { y: 90, z: -120, rotateX: -28, opacity: 0, transformPerspective: 900 },
        {
          y: 0, z: 0, rotateX: 0, opacity: 1,
          duration: .9, stagger: .14, ease: 'back.out(1.35)',
          scrollTrigger: { trigger: processSection, start: 'top 74%', once: true }
        }
      );
    }

    document.querySelectorAll('[data-parallax-image]').forEach((image) => {
      gsap.fromTo(image,
        { yPercent: -5, scale: 1.08 },
        {
          yPercent: 5,
          scale: 1.12,
          ease: 'none',
          scrollTrigger: { trigger: image, start: 'top bottom', end: 'bottom top', scrub: 1.1 }
        });
    });

    const carouselImages = document.querySelectorAll('.inline-carousel img');
    if (carouselImages.length > 1) {
      let activeImage = 0;
      carouselImages.forEach((image, index) => gsap.set(image, { autoAlpha: index === 0 ? 1 : 0, x: index === 0 ? 0 : 18 }));
      window.setInterval(() => {
        const nextImage = (activeImage + 1) % carouselImages.length;
        gsap.to(carouselImages[activeImage], { autoAlpha: 0, x: -18, duration: 0.45, ease: 'power2.out' });
        gsap.fromTo(carouselImages[nextImage], { autoAlpha: 0, x: 18 }, { autoAlpha: 1, x: 0, duration: 0.55, ease: 'power2.out' });
        activeImage = nextImage;
      }, 2800);
    }

    document.querySelectorAll('[data-mode-card]').forEach((card) => {
      card.addEventListener('click', () => {
        document.querySelectorAll('[data-mode-card]').forEach((item) => item.classList.remove('is-active'));
        card.classList.add('is-active');
      });
    });

    if (desktop) {
      const horizontalSection = document.querySelector('[data-horizontal-scroll]');
      const track = horizontalSection?.querySelector('.horizontal-track');
      const cards = track?.querySelectorAll('.template-item');
      if (horizontalSection && track && cards?.length > 2) {
        const moveDistance = () => Math.max(0, track.scrollWidth - horizontalSection.clientWidth);
        gsap.to(track, {
          x: () => -moveDistance(),
          ease: 'none',
          scrollTrigger: {
            trigger: horizontalSection,
            start: 'top top+=72',
            end: () => `+=${moveDistance()}`,
            pin: true,
            scrub: 1,
            invalidateOnRefresh: true,
            anticipatePin: 1
          }
        });
      }
    }

    ScrollTrigger.refresh();
  };

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', start, { once: true });
  else start();
})();
