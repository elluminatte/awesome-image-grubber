/**
 * Created by salov on 03.09.16.
 */

$(function () {
    $('._slick-slides').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        asNavFor: '._slick-thumbs',
        adaptiveHeight: true
    });

    $('._slick-thumbs').slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        asNavFor: '._slick-slides',
        dots: true,
        arrows: true,
        centerMode: true,
        focusOnSelect: true,
        adaptiveHeight: true
    });
});