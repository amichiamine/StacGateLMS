document.addEventListener('DOMContentLoaded', () => {
    const hamburger = document.querySelector('.hamburger');
    const nav = document.querySelector('.main-header nav');
   

    hamburger.addEventListener('click', () => {
        nav.classList.toggle('active');
        backdrop.classList.toggle('active');
    });

    backdrop.addEventListener('click', () => {
        nav.classList.remove('active');
        backdrop.classList.remove('active');
    });
});
