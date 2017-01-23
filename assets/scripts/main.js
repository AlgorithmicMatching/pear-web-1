$('.content').css({ height: $(window).innerHeight() > 800 ? $(window).innerHeight() : 800 });

$(function() {
  //setting screen size

  var data = [];
  for (var i = 18; i < 100; i++) {
    data.push({ id: i, text: i });
  }
  //intitializing select
  $('.select-age-home').select2({
    placeholder: "Age",
    minimumResultsForSearch: Infinity,
    data: data
  });

  $('.select-age-home').css('visibility', 'visible');

  /*
    CAROUSEL BLOG
  */

  if ($('.slick-carousel')[0]) {
    $('.slick-carousel').slick({
      dots: false,
      speed: 300,
      slidesToShow: 3,
      slidesToScroll: 1,
      responsive: [{
          breakpoint: 1024,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 1,
            infinite: true,
            dots: true
          }
        }, {
          breakpoint: 600,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 1
          }
        }, {
          breakpoint: 480,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1
          }
        }
        // You can unslick at a given breakpoint now by adding:
        // settings: "unslick"
        // instead of a settings object
      ]
    });
  }

  /*
    BURGER MENU
  */

  var burgerOpen = false;
  $('.burger-menu').on('click', function(e) {

    if (!burgerOpen) {
      openBurger();
    } else {
      closeBurger();
    }


    e.preventDefault();

  });

  $(document).on("click", ".nav-item", function(e) {
    if (burgerOpen) {
      closeBurger();
    }
  });

  function openBurger() {
    $('.burger-menu').addClass('open');
    $('.burger-menu-overlay').fadeIn();
    $('.burger-menu-overlay').css('display', 'flex');
    $('.burger-menu-overlay').css('z-index', 9999);
    $('.burger-menu').css('z-index', 99999);
    $('body').css('overflow', 'hidden');
    burgerOpen = true;
  }

  function closeBurger() {
    $('.burger-menu').removeClass('open');
    $('.burger-menu-overlay').fadeOut();
    $('.burger-menu-overlay').css('z-index', -1);
    $('.burger-menu').css('z-index', 5);
    $('body').css('overflow', 'auto');
    burgerOpen = false;
  }

  $('.burger-menu-close').on('click', function() {
    $('.burger-menu-overlay').fadeOut(2000);
  });

  /*
    IMAGE SEQUENCE HERO
  */

  var heroImages = Array("images/pear_hero_1.png",
    "images/pear_hero_2.png",
    "images/pear_hero_3.png");

  var heroCurImage = 0;

  function loadimg() {

    $('#hero-cover').animate({ opacity: 1 }, 1000, function() {

      //finished animating, minifade out and fade new back in
      $('#hero-cover').animate({ opacity: 0.7 }, 200, function() {

        heroCurImage++;

        if (heroCurImage > heroImages.length - 1) {

          heroCurImage = 0;

        }

        var newimage = heroImages[heroCurImage];

        //swap out bg src
        $('#hero-cover').css("background", "url(" + newimage + ")");
        $('#hero-cover').css("background-color", "black");

        //animate fully back in
        $('#hero-cover').animate({ opacity: 1 }, 400, function() {

          //set timer for next
          setTimeout(loadimg, 5000);

        });

      });

    });

  }

  setTimeout(loadimg, 5000);

  /*
    NOBEL PRIZE HEIGHT
  */

  if ($('#nobel-prize')) {
    if ($('#nobel-prize').height() > 24) {
      $('#nobel-prize').css('margin-top', -9);
    }
  }
});

// blog



// Swiper Slider
var swiper = new Swiper('.swiper-container', {
  pagination: '.swiper-pagination',
  paginationClickable: true,
  nextButton: '.swiper-button-next',
  prevButton: '.swiper-button-prev',
  autoplay: 2500,
  // loop: true,

  keyboardControl: true,
  autoplayDisableOnInteraction: true,
  speed: 800,
  parallax: true
});
