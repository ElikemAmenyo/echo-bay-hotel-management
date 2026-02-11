let slideIndex = 0;
const slides = document.querySelectorAll('.image-slide');
const slideInterval = 3500; 
const transitionTime = 1000; 

slides.forEach((slide, index) => {
    slide.style.position = 'absolute';
    slide.style.width = '100%';
    slide.style.height = '100%';
    slide.style.opacity = index === 0 ? '1' : '0';
    slide.style.transition = `opacity ${transitionTime}ms`;
});

function showNextSlide() {
    slides[slideIndex].style.opacity = '0';

    slideIndex = (slideIndex + 1) % slides.length;

    slides[slideIndex].style.opacity = '1';
}

setInterval(showNextSlide, slideInterval);
