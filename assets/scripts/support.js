$(function() { // wait for document ready

    $('.support .item').on('click', function() {

        if ($(this).hasClass('active'))
            $(this).removeClass('active')
        else
            $(this).addClass('active').siblings().removeClass('active')

        var speed = 300;

        $('.support .item:not(.active) .desc:visible').slideUp(speed)
        $('.support .item.active .desc').slideDown(speed)

    })

});
