 let description = document.querySelector('.d-bx');
 let information = document.querySelector('.a-information');
 

var swiper = new Swiper(".hero-slider", {
    slidesPerView: 1,
    spaceBetween: 30,
    loop: true,
    autoplay: {
        delay: 3000,
        disableOnInteraction: false,
      },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
  });

//   --------------------------

  
var swiper = new Swiper(".featured-slider", {
    slidesPerView: 1,
    spaceBetween: 30,
    loop: true,
    autoplay: {
        delay: 2500,
        disableOnInteraction: false,
      },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    
    breakpoints: {
        450: {
          slidesPerView: 2, 
        },
        768: {
          slidesPerView: 3, 
        },
        1024: {
          slidesPerView: 4, 
        }, 
      },
  });


//   --------------------------


var swiper = new Swiper(".newArrival-slider", {
    slidesPerView: 1,
    spaceBetween: 30,
    loop: true,
    autoplay: {
        delay: 2500,
        disableOnInteraction: false,
      },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    
    breakpoints: {
        450: {
          slidesPerView: 2, 
        },
        768: {
          slidesPerView: 3, 
        },
        1024: {
          slidesPerView: 4, 
        }, 
      },
  });


  
//   ------------testimonial--------------


var swiper = new Swiper(".client-review", {
  slidesPerView: 1,
  spaceBetween: 30,
  loop: true,
  autoplay: {
      delay: 2500,
      disableOnInteraction: false,
    },
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
  
  breakpoints: {
      0: {
        slidesPerView: 2, 
      },
      768: {
        slidesPerView: 3, 
      },
      1024: {
        slidesPerView: 4, 
      }, 
    },
});