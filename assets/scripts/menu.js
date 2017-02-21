$(function() {

  if (!$('#menu-toggle-indicator')[0]) {
    return;
  }

  //new top nav transition

  $(window).on('scroll',function(){
      var top  = window.pageYOffset || document.documentElement.scrollTop;
      if (top>20)
          $('#top-nav').addClass('white')
      else {
          $('#top-nav').removeClass('white')
      }
  })

  if (window.pageYOffset || document.documentElement.scrollTop)
    $('#top-nav').addClass('white')

    //new top nav transition

  var controller = new ScrollMagic.Controller();
  controller.scrollTo(function(newScrollPos) {
    $("html, body").animate({ scrollTop: newScrollPos });
  });

  // build scenes
  // new ScrollMagic.Scene({ triggerElement: "#menu-toggle-indicator", })
  //   .setClassToggle("#top-nav", "white") // add class toggle
  //   .addTo(controller);

  new ScrollMagic.Scene({ triggerElement: "#menu-toggle-indicator", offset: $(window).innerHeight() / 2 - 50
 })
    .setClassToggle(".burger-line", 'green')
    .addTo(controller);



  new ScrollMagic.Scene({ triggerElement: "#nav-home-trigger", duration: $(".landing-home").height() + 32 })
    .setClassToggle("#nav-home", "active") // add class toggle
    .addTo(controller);


  //SHOULD BE THE SAME AS IN mobile.js (MAKE COMMON)

  new ScrollMagic.Scene({ triggerElement: "#nav-about-trigger", duration: $(".landing-blog").position().top - $('#nav-about-trigger').position().top})
    .setClassToggle("#nav-about", "active") // add class toggle
    .addTo(controller);

  new ScrollMagic.Scene({ triggerElement: "#nav-blog-trigger", duration: $("#nav-support-trigger").position().top-$("#nav-blog-trigger").position().top})
    .setClassToggle("#nav-blog", "active") // add class toggle
    .addTo(controller);

  // new ScrollMagic.Scene({ triggerElement: "#nav-media-trigger", duration: $(".landing-press").height() })
  //   .setClassToggle("#nav-media", "active") // add class toggle
  //   .addTo(controller);

  new ScrollMagic.Scene({ triggerElement: "#nav-support-trigger", duration: $("footer").position().top-$("#nav-support-trigger").position().top })
    .setClassToggle("#nav-support", "active") // add class toggle
    .addTo(controller);
  // new ScrollMagic.Scene({ triggerElement: "#nav-launch-trigger", duration: $(".landing-launch").height() })
  //   .setClassToggle("#nav-launch", "active") // add class toggle
  //   .addTo(controller);

  $(document).on("click", ".nav-item", function(e) {
    var id = $(this).attr("id");
    var triggerId = id + "-trigger";
    // trigger scroll
    controller.scrollTo("#" + triggerId);
  });

});
