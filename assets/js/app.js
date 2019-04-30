/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.scss');

window.Popper = require("popper.js").default;
var $ = require('jquery');
global.$ = global.jQuery = $;
var Sortable = require('sortablejs');
global.Sortable = Sortable;

require('./jquery.ui.position.js');
require('bootstrap');
require('datatables.net-bs4')(window, $);
require('datatables.net-buttons/js/buttons.html5')(window, $);
require('datatables.net-buttons-bs4')(window, $);
require('datatables.net-responsive')(window, $);
require('datatables.net-responsive-bs4')(window, $);
require('chosen-js');
require('form-serializer');
require('virtual-keyboard');

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

require('./collection.js');
require('./dependent_fields.js');
require('./dependent_selects.js');
require('./localisation_form.js');
require('./selectorcreate.js');
require('./precision_indicator.js');
require('./semitic_keyboard');
require('./formulae');
require('./watch_form_dirty');