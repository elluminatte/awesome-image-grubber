/**
 * Created by salov on 03.09.16.
 */

$(function() {

    $content = $('.content');

    $container = $('.grub-form-container');

    var submitOptions = {
        error: function() {
            alert('An error has occurred while sending request. Please try again later')
        },
        complete: function() {
            $content.removeClass('-loading');
        },
        target: '.generated-content'
    };

    $('._grub-form', $container).submit(function() {
        $content.addClass('-loading');

        $(this).ajaxSubmit(submitOptions);

        return false;
    });
});