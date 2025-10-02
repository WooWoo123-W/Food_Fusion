const navToggle = document.querySelector('.nav-toggle');
const primaryMenu = document.getElementById('primary-menu');
const cookieBanner = document.getElementById('cookieBanner');
const acceptCookies = document.getElementById('acceptCookies');
const joinButtons = document.querySelectorAll('[data-join-modal]');
const modal = document.getElementById('joinModal');
const modalClose = document.getElementById('closeJoin');
const carousel = document.querySelector('.carousel');
let carouselIndex = 0;

if (navToggle) {
    navToggle.addEventListener('click', () => {
        const expanded = navToggle.getAttribute('aria-expanded') === 'true';
        navToggle.setAttribute('aria-expanded', String(!expanded));
        primaryMenu.classList.toggle('open');
    });
}

if (cookieBanner && acceptCookies) {
    const hasConsent = localStorage.getItem('foodfusion-cookie-consent');
    if (!hasConsent) {
        cookieBanner.classList.add('active');
    }
    acceptCookies.addEventListener('click', () => {
        localStorage.setItem('foodfusion-cookie-consent', 'yes');
        cookieBanner.classList.remove('active');
    });
}

if (joinButtons && modal) {
    joinButtons.forEach(btn => btn.addEventListener('click', () => modal.classList.add('active')));
}
if (modalClose) {
    modalClose.addEventListener('click', () => modal.classList.remove('active'));
}
if (modal) {
    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.classList.remove('active');
        }
    });
}

if (carousel) {
    const track = carousel.querySelector('.carousel-track');
    const items = carousel.querySelectorAll('.carousel-item');
    const prevBtn = carousel.querySelector('[data-carousel="prev"]');
    const nextBtn = carousel.querySelector('[data-carousel="next"]');
    const updateCarousel = () => {
        track.style.transform = `translateX(-${carouselIndex * 100}%)`;
    };
    if (prevBtn && nextBtn) {
        prevBtn.addEventListener('click', () => {
            carouselIndex = (carouselIndex - 1 + items.length) % items.length;
            updateCarousel();
        });
        nextBtn.addEventListener('click', () => {
            carouselIndex = (carouselIndex + 1) % items.length;
            updateCarousel();
        });
    }
    setInterval(() => {
        carouselIndex = (carouselIndex + 1) % items.length;
        updateCarousel();
    }, 7000);
}

const communityHero = document.querySelector('.community-hero');
if (communityHero) {
    setTimeout(() => communityHero.classList.add('active'), 300);
}

const resourceNav = document.querySelector('.resources-nav');
if (resourceNav) {
    resourceNav.addEventListener('click', (event) => {
        if (event.target.matches('button[data-resource-filter]')) {
            const filter = event.target.getAttribute('data-resource-filter');
            document.querySelectorAll('.resource-grid .card').forEach(card => {
                const category = card.getAttribute('data-category');
                card.style.display = filter === 'all' || filter === category ? 'block' : 'none';
            });
        }
    });
}

const recipeShareButtons = document.querySelectorAll('[data-share]');
recipeShareButtons.forEach(button => {
    button.addEventListener('click', () => {
        const url = button.getAttribute('data-url');
        const title = button.getAttribute('data-title');
        if (navigator.share) {
            navigator.share({ title, url }).catch(() => {});
        } else {
            window.open(url, '_blank');
        }
    });
});
