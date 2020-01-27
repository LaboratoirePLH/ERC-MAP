window.Popper = require("popper.js").default;
var $ = require('jquery');
global.$ = global.jQuery = $;

require('bootstrap');

// Import Quill
window.Quill = require('quill');
// Add fonts to whitelist
var Font = Quill.import('attributors/style/font');
// We do not add Aref Ruqaa since it is the default
Font.whitelist = ['arial', 'ifaogreek'];
Quill.register(Font, true);

// Import semitic keyboard
require('./plugins/semitic_keyboard.js');

decodeEntities = function (encodedString) {
    var textArea = document.createElement('textarea');
    textArea.innerHTML = encodedString;
    return textArea.value;
};

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

    if ($('.quill').length) {

        $('.quill').each(function () {
            const quillContainer = $(this).get(0);
            var textareaId = $(quillContainer).data('id');
            var quill = new Quill(quillContainer, {
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'script': 'sub' }, { 'script': 'super' }],
                        [{ 'font': ['arial', 'ifaogreek'] }]
                    ],
                    keyboard: {
                        bindings: {
                            tab: {
                                key: 9,
                                handler: function () {
                                    $(quillContainer).closest(".form-group.row")
                                        .next(".form-group.row")
                                        .find(':input,.ql-editor')
                                        .first()
                                        .focus();
                                }
                            }
                        }
                    },
                },
                theme: 'snow'
            });
            quill.on('text-change', function (delta, oldDelta, source) {
                $("#" + textareaId).val(quill.root.innerHTML);//.replace(/"/g, '\''));
            });

            const keyboardTarget = $(this).find('.ql-editor');
            keyboardTarget.semiticKeyboard();
            // quill.on('selection-change', function (range, oldRange, source) {
            //     if (range === null && oldRange !== null) {
            //         // blur
            //         keyboardTarget.data('keyboard').accept();
            //     }
            //     else if (range !== null && oldRange === null) {
            //         // focus
            //         keyboardTarget.data('keyboard').reveal();
            //     }
            // });
            // $(this).on('focus', function () {
            //     keyboardTarget.data('keyboard').reveal();
            // });
            // $(this).on('blur', function () {
            //     keyboardTarget.data('keyboard').accept();
            // });
        });
    }
})(jQuery);