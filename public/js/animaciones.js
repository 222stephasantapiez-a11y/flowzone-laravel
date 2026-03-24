// Intersection Observer — anima elementos al entrar en viewport
document.addEventListener('DOMContentLoaded', function () {

    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });

    document.querySelectorAll('.animate-on-scroll').forEach(function (el) {
        observer.observe(el);
    });

    // Fade-in escalonado para elementos hero
    document.querySelectorAll('.fade-in').forEach(function (el, i) {
        el.style.animationDelay = (i * 0.18) + 's';
    });
});

// Parallax suave en hero
window.addEventListener('scroll', function () {
    var hero = document.querySelector('.hero');
    if (hero) {
        hero.style.backgroundPositionY = (window.pageYOffset * 0.4) + 'px';
    }
});
