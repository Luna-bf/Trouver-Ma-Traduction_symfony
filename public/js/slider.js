new Swiper('.cards-wrapper', {
    loop: true,

    // Flèches de navigation
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },

    // Responsive (min-width)
    breakpoints: {
        
        0: { // Ses paramètres s'appliquent lorsque la largeur de l'écran est supérieure ou égale à 0px (soit de 0px à 575px)
            slidesPerView: 1,
        },

        576: { // Ses paramètres s'appliquent lorsque la largeur de l'écran est supérieure ou égale à 576px (soit de 576px à 1023px)
            slidesPerView: 2,
            spaceBetween: 25,
        },

        1024: { // Ses paramètres s'appliquent lorsque la largeur de l'écran est supérieure ou égale à 1024px
            slidesPerView: 4,
            spaceBetween: 50,
        },
    }
});