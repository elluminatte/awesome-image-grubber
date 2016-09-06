/**
 * Created by salov on 03.09.16.
 */

$(function () {
    $('._gallery-thumbnsils').flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: true,
        slideshow: false,
        itemWidth: 100,
        itemMargin: 5,
        asNavFor: '._gallery-slider',
        maxItems: 5
    });

    $('._gallery-slider').flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: true,
        slideshow: false,
        sync: "._gallery-thumbnsils",
        smoothHeight: true
    });
});