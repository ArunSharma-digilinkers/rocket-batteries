import './bootstrap';
import 'bootstrap';

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    const revealObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.15 }
    );

    document.querySelectorAll('.reveal, .reveal-stagger').forEach((el) => revealObserver.observe(el));

    initHeroSlider();
});

function initHeroSlider() {
    const slider = document.querySelector('#heroSlider');
    if (!slider) return;

    const restartProgress = () => {
        slider.classList.remove('hero-progress-restart');
        void slider.offsetWidth;
        slider.classList.add('hero-progress-restart');
    };

    slider.addEventListener('mouseenter', () => slider.classList.add('is-paused'));
    slider.addEventListener('mouseleave', () => slider.classList.remove('is-paused'));
    slider.addEventListener('slide.bs.carousel', () => slider.classList.add('is-changing'));
    slider.addEventListener('slid.bs.carousel', () => {
        slider.classList.remove('is-changing');
        restartProgress();
    });

    restartProgress();
}
