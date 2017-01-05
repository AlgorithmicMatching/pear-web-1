$(function () {
  //setting screen size
  $('.content').css({height: $(window).innerHeight() > 800 ? $(window).innerHeight() : 800});

  var data = [];
  for (var i = 18; i < 100; i++) {
    data.push({id: i, text: i});
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
      responsive: [
        {
          breakpoint: 1024,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 1,
            infinite: true,
            dots: true
          }
        },
        {
          breakpoint: 600,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 1
          }
        },
        {
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

  $('.burger-menu').on('click', function (e) {

    if (!burgerOpen) {
      openBurger();
    } else {
      closeBurger();
    }


    e.preventDefault();

  });

  $(document).on("click", ".nav-item", function (e) {
    if (burgerOpen) {
      closeBurger();
    }
  });

  $('.burger-menu-close').on('click', function () {
    $('.burger-menu-overlay').fadeOut(2000);
  });

  /*
   IMAGE SEQUENCE HERO
   */

  var heroImages = Array(wp_theme_home.uri + "/dist/images/pear_hero_1.png",
    wp_theme_home.uri + "/dist/images/pear_hero_2.png",
    wp_theme_home.uri + "/dist/images/pear_hero_3.png");

  var heroCurImage = 0;

  function loadimg() {

    $('#hero-cover').animate({opacity: 1}, 1000, function () {

      //finished animating, minifade out and fade new back in
      $('#hero-cover').animate({opacity: 0.7}, 200, function () {

        heroCurImage++;

        if (heroCurImage > heroImages.length - 1) {

          heroCurImage = 0;

        }

        var newimage = heroImages[heroCurImage];

        //swap out bg src
        $('#hero-cover').css("background", "url(" + newimage + ")");
        $('#hero-cover').css("background-color", "black");

        //animate fully back in
        $('#hero-cover').animate({opacity: 1}, 400, function () {

          //set timer for next
          setTimeout(loadimg, 5000);

        });

      });

    });

  }

  setTimeout(loadimg, 5000);


  window.startLoading = function () {
    $('.join-text').addClass('inactive');
    $('.loader').removeClass('inactive');
  };

  window.endLoading = function () {
    $('.join-text').removeClass('inactive');
    $('.loader').addClass('inactive');
  };

  /*
   NOBEL PRIZE HEIGHT
   */

  // if ($('#nobel-prize')) {
  //   if ($('#nobel-prize').height() > 24) {
  //     $('#nobel-prize').css('margin-top', -9);
  //   }
  // }

  window.cookieconsent.initialise({
    container: document.getElementById("main"),
    layout: 'pear-layout',
    layouts: {
      'pear-layout': '{{message}}{{compliance}}'
    },
    content: {
      message: 'This site uses cookies. By continuing to use this site, you are agreeing to our use of cookies.',
      dismiss: 'OK',
      allow: 'I agree',
      deny: 'Decline'
    },
    compliance: {
      'info': '<div class="cc-compliance col-xs-12 col-sm-4 col-md-3">{{dismiss}}</div>',
      'opt-in': '<div class="cc-compliance cc-highlight col-xs-12 col-sm-4 col-md-3">{{dismiss}}{{allow}}</div>',
      'opt-out': '<div class="cc-compliance cc-highlight col-xs-12 col-sm-4 col-md-3">{{deny}}{{dismiss}}</div>'
    },
    elements: {
      message: '<div class="col-xs-0 col-md-1"></div><div id="cookieconsent:desc" class="cc-message col-xs-12 col-sm-8 col-md-7"><p class="pull-left">{{message}}</p></div>',
      dismiss: '<div class="cc-btn cc-dismiss button green shadow text-uppercase button-join" tabindex="0">{{dismiss}}</div>',
      allow: '<div class="cc-btn cc-allow button green shadow text-uppercase button-join" tabindex="0">{{allow}}</div>',
      deny: '<div class="cc-btn cc-deny button green shadow text-uppercase button-join" tabindex="0">{{deny}}</div>',
      close: '<div class="cc-close button green shadow text-uppercase button-join" tabindex="0">{{close}}</div>'
    },
    window: '<div id="cookie-wrapper" role=”dialog” aria-label=”cookieconsent” aria-describedby=”cookieconsent:desc” class="cc-window {{classes}}"><div class="container"><div class="row flex--align-vertical">{{children}}</div></div></div>',
    onPopupOpen: function () {
      var didConsent = this.hasConsented();
      if (!didConsent) {
        $(document.body).addClass('cookie-notice');
      }
    },
    onStatusChange: function (status, chosenBefore) {
      var didConsent = this.hasConsented();
      if (!didConsent) {
        $('body').addClass('cookie-notice');
      } else {
        $('body').removeClass('cookie-notice');
      }
    }
  });

});

// blog
