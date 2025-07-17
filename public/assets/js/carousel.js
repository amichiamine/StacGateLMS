document.addEventListener('DOMContentLoaded', () => {
    const carousel = document.querySelector('.carousel');
    const slides = carousel.querySelectorAll('.slide');
    const sliderDots = carousel.querySelector('.carousel-dots');
    const leftArrow = carousel.querySelector('.carousel-arrow.left');
    const rightArrow = carousel.querySelector('.carousel-arrow.right');
    let currentSlide = 0;

    // Crée la navigation par points
    slides.forEach((_, idx) => {
        const dot = document.createElement('span');
        if (idx === 0) dot.classList.add('active');
        dot.addEventListener('click', () => goToSlide(idx));
        sliderDots.appendChild(dot);
    });

    // Démarre le défilement automatique
    let slideInterval = setInterval(nextSlide, 5000);

    // Fonctions de navigation
    function nextSlide() {
        goToSlide(currentSlide === slides.length - 1 ? 0 : currentSlide + 1);
    }

    function goToSlide(idx) {
        slides[currentSlide].classList.remove('active');
        slides[idx].classList.add('active');
        sliderDots.querySelector('.active').classList.remove('active');
        sliderDots.children[idx].classList.add('active');
        currentSlide = idx;
        clearInterval(slideInterval);
        slideInterval = setInterval(nextSlide, 5000);
    }

    leftArrow.addEventListener('click', () => goToSlide(currentSlide === 0 ? slides.length - 1 : currentSlide - 1));
    rightArrow.addEventListener('click', nextSlide);
});
