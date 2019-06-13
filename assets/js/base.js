window.Popper = require("popper.js").default;
var $ = require('jquery');
global.$ = global.jQuery = $;

require('bootstrap');

<<<<<<< HEAD
=======
decodeEntities = function (encodedString) {
    var textArea = document.createElement('textarea');
    textArea.innerHTML = encodedString;
    return textArea.value;
};

>>>>>>> b80eec144b0efb5ee9b73fee1666252f590354f7
(function ($) {
    if ($('#back-to-top').length) {
        var scrollTrigger = 100, // px
            backToTop = function () {
                var scrollTop = $(window).scrollTop();
                if (scrollTop > scrollTrigger) {
                    $('#back-to-top').addClass('show');
                } else {
                    $('#back-to-top').removeClass('show');
                }
            };
        backToTop();
        $(window).on('scroll', function () {
            backToTop();
        });
        $('#back-to-top').on('click', function (e) {
            e.preventDefault();
            $('html,body').animate({
                scrollTop: 0
            }, 700);
        });
    }

    $('[data-toggle="tooltip"]').tooltip();
})(jQuery);