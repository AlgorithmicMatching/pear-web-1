// $('.content').css({ height: $(window).innerHeight() > 840 ? $(window).innerHeight() : 840 });


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

  var heroCurImage = -1;
  var heroCount = $('.hero-cover figure').length;

  function loadimg() {

    var last;

    if (heroCurImage >= 0)
      last = $('.hero-cover figure:eq(' + heroCurImage + ')')

    if (last)
      setTimeout(function() {
        last.hide()
      }, 1000 * 10)

    heroCurImage += 1;

    if (heroCurImage == heroCount) heroCurImage = 0;

    var nx = $('.hero-cover figure:eq(' + heroCurImage + ')')

    nx.show()

    to = setTimeout(loadimg, 1000 * 30)

  }

  loadimg()


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




// Fullpage
$(document).ready(function() {
  $('#fullpage').fullpage({
    anchors: ['firstPage', 'secondPage', 'thirdPage', 'forthPage'],
    scrollOverflow: true,

    // Navigation
    navigation: true,
    navigationPosition: 'right'
  });
});
