(() => {
    'use strict';

    const start = () => {
        const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const desktop = window.matchMedia('(min-width: 992px)').matches;
        if (reduced) return;

        document.querySelectorAll('[data-tilt]').forEach((card) => {
            card.addEventListener('pointermove', (event) => {
                if (!desktop) return;
                const bounds = card.getBoundingClientRect();
                const x = (event.clientX - bounds.left) / bounds.width;
                const y = (event.clientY - bounds.top) / bounds.height;
                card.style.setProperty('--pointer-x', `${x * 100}%`);
                card.style.setProperty('--pointer-y', `${y * 100}%`);
                card.style.transform = `rotateX(${(0.5 - y) * 7}deg) rotateY(${(x - 0.5) * 9}deg) translateZ(8px)`;
            });
            card.addEventListener('pointerleave', () => { card.style.transform = ''; });
        });

        document.querySelectorAll('[data-magnetic]').forEach((button) => {
            button.addEventListener('pointermove', (event) => {
                if (!desktop) return;
                const bounds = button.getBoundingClientRect();
                const x = event.clientX - (bounds.left + bounds.width / 2);
                const y = event.clientY - (bounds.top + bounds.height / 2);
                button.style.transform = `translate(${x * .12}px, ${y * .12}px)`;
            });
            button.addEventListener('pointerleave', () => { button.style.transform = ''; });
        });

        document.querySelectorAll('[data-ripple]').forEach((button) => {
            button.addEventListener('click', (event) => {
                const bounds = button.getBoundingClientRect();
                const ripple = document.createElement('span');
                ripple.className = 'motion-ripple';
                ripple.style.left = `${event.clientX - bounds.left}px`;
                ripple.style.top = `${event.clientY - bounds.top}px`;
                button.appendChild(ripple);
                ripple.addEventListener('animationend', () => ripple.remove(), { once: true });
            });
        });

        const section = document.querySelector('[data-hero-section]');
        if (desktop && section && window.THREE) {
            const canvas = document.createElement('canvas');
            canvas.className = 'hero-3d-canvas';
            section.prepend(canvas);
            let renderer;
            try {
                renderer = new THREE.WebGLRenderer({ canvas, alpha: true, antialias: true, powerPreference: 'high-performance' });
            } catch (error) {
                canvas.remove();
                return;
            }
            renderer.setPixelRatio(Math.min(window.devicePixelRatio, 1.5));
            const scene = new THREE.Scene();
            const camera = new THREE.PerspectiveCamera(35, 1, .1, 100);
            camera.position.z = 6;
            const group = new THREE.Group();
            const geometry = new THREE.IcosahedronGeometry(1.25, 1);
            const material = new THREE.MeshStandardMaterial({ color: 0x58a6ff, metalness: .55, roughness: .28, wireframe: true, transparent: true, opacity: .72 });
            group.add(new THREE.Mesh(geometry, material));
            scene.add(group);
            scene.add(new THREE.AmbientLight(0xffffff, .9));
            const light = new THREE.PointLight(0x8dbbff, 10, 15);
            light.position.set(2, 3, 4);
            scene.add(light);
            const pointer = { x: 0, y: 0 };
            section.addEventListener('pointermove', (event) => {
                const bounds = section.getBoundingClientRect();
                pointer.x = ((event.clientX - bounds.left) / bounds.width - .5) * 2;
                pointer.y = ((event.clientY - bounds.top) / bounds.height - .5) * 2;
            }, { passive: true });
            const resize = () => {
                const bounds = section.getBoundingClientRect();
                renderer.setSize(bounds.width, bounds.height, false);
                camera.aspect = bounds.width / Math.max(bounds.height, 1);
                camera.updateProjectionMatrix();
            };
            window.addEventListener('resize', resize, { passive: true });
            resize();
            let isVisible = true;
            let animationFrame = 0;
            const visibility = new IntersectionObserver(([entry]) => {
                isVisible = entry.isIntersecting;
                if (isVisible && !animationFrame) animate();
            }, { threshold: 0.05 });
            visibility.observe(section);
            const animate = () => {
                if (!isVisible) {
                    animationFrame = 0;
                    return;
                }
                group.rotation.y += .0025;
                group.rotation.x += (pointer.y * .18 - group.rotation.x) * .03;
                group.rotation.z += (pointer.x * .12 - group.rotation.z) * .03;
                renderer.render(scene, camera);
                animationFrame = window.requestAnimationFrame(animate);
            };
            animate();
        }
    };

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', start, { once: true });
    else start();
})();
