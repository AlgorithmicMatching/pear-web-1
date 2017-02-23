$(function() { // wait for document ready

    var speed = 300;

  $('.support .item').on('click', function() {

    if ($(this).hasClass('active'))
      $(this).removeClass('active')
    else
      $(this).addClass('active').siblings().removeClass('active')

    $('.support .item:not(.active) .desc:visible').slideUp(speed)
    $('.support .item.active .desc').slideDown(speed)

  })

  $(function() { $("#view-faq").click(function() {
      if ($(this).text() === 'View All') {
          $('.support .item').addClass('active')
          $(".desc").slideDown(speed);
      }
      else {
          $('.support .item').removeClass('active')
          $(".desc").slideUp(speed);
      }
  }) }),
    $(function() {
      $("#view-faq").click(function() {
        $(this).text(function(i, v) {
          return v === 'View All' ? 'Close All' : 'View All'
        })
      })
    })

});
