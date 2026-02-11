  // Initialize Swiper
  const swiper = new Swiper('.swiper', {
    loop: true, 
    slidesPerView: 3, 
    spaceBetween: 10, 
    navigation: {
        nextEl: '.swiper-button-next', 
        prevEl: '.swiper-button-prev', 
    },
    pagination: {
        el: '.swiper-pagination', 
        clickable: true, 
    },
    breakpoints: {
        640: {
            slidesPerView: 1, 
            spaceBetween: 10,
        },
        768: {
            slidesPerView: 1,
            spaceBetween: 15,
        },
        1024: {
            slidesPerView: 1, 
            spaceBetween: 20,
        },
    },
});
