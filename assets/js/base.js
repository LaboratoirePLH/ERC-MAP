window.Popper = require("popper.js").default;
var $ = require('jquery');
global.$ = global.jQuery = $;

require('bootstrap');

var FroalaEditor = require('froala-editor');

// Load a plugin.
require('froala-editor/js/plugins/char_counter.min.js');
require('froala-editor/js/plugins/font_family.min.js');
require('froala-editor/js/plugins/special_characters.min.js');

FroalaEditor.DefineIcon('specialCharacters', { NAME: 'keyboard', template: 'font_awesome_5' });

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

    if ($('.froala').length) {

        $('.froala').each(function () {
            new FroalaEditor('#' + $(this).attr('id'), {
                charCounterCount: true,
                enter: FroalaEditor.ENTER_BR,
                fontFamily: {
                    'Arial': 'Arial',
                    'IFAOGreek': 'IFAO Greek'
                },
                pastePlain: true,
                pluginsEnabled: ['charCounter', 'fontFamily', 'specialCharacters'],
                specialCharactersSets: [
                    {
                        title: "Semitic", "char": "&scaron;", list: [
                            { "char": "ṭ", "desc": "Tet" },
                            { "char": "ṣ", "desc": "Tsade" },
                            { "char": "š", "desc": "Shin" },
                            { "char": "ś", "desc": "Sin" },
                            { "char": "ʾ", "desc": "Ayn" },
                            { "char": "ʿ", "desc": "Aleph" },
                            { "char": "ḥ", "desc": "Het" },
                        ]
                    }
                ],
                spellcheck: false,
                toolbarButtons: [
                    ['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript'],
                    ['fontFamily', 'specialCharacters', 'clearFormatting']
                ]
            });
        });
    }
})(jQuery);