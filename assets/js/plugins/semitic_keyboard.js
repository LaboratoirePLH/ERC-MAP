require('./jquery.ui.position.js');

// "n(a):title_or_tooltip"; n = new key, (a) = actual key (optional), ":label" = title_or_tooltip (use an underscore "_" in place of a space " ")
SEMITIC_KEYS = [
    't\u0323:Tet',
    's\u0323:Tsade',
    's\u030c:Shin',
    's\u0301:Sin',
    '\u02be:Ayn',
    '\u02bf:Aleph',
    'h\u0323:Het',
];

(function ($) {
    $.fn.semiticKeyboard = function (positionEl, alignmentMy, alignmentAt, forceEvent) {
        return this.each(function () {
            var my,
                at = 'left bottom';
            if (positionEl === undefined || positionEl === null) {
                positionEl = $(this);
                my = 'left top';
            } else {
                my = 'left bottom';
            }
            if (alignmentMy !== undefined && alignmentMy !== null) {
                my = alignmentMy;
            }
            if (alignmentAt !== undefined && alignmentAt !== null) {
                at = alignmentAt;
            }
            var me = this;
            $(me).keyboard({
                layout: 'custom',
                customLayout: {
                    'normal': [
                        SEMITIC_KEYS.join(' ')
                    ]
                },
                position: {
                    of: positionEl,
                    my: my,
                    at: at,
                    at2: at
                },
                autoAccept: true,
                autoAcceptOnEsc: true,
                usePreview: false,
                useCombos: false,
                change: function (e, keyboard, el) {
                    if (forceEvent === true && keyboard.last.virtual) {
                        $(me).trigger("keyup", { which: 16 });
                    }
                }
            })
        })
    }
    if (typeof CKEDITOR !== "undefined") {
        CKEDITOR.on('instanceReady', function (e) {
            if ($(e.editor.element.$).hasClass('semitic_keyboard')) {
                var keyboardTarget = $(e.editor.ui.contentsElement.$).find('iframe').contents().find('body');
                keyboardTarget.semiticKeyboard($(e.editor.container.$));
                e.editor.on('focus', function () {
                    keyboardTarget.data('keyboard').reveal();
                });
                e.editor.on('blur', function () {
                    keyboardTarget.data('keyboard').accept();
                });
            }
            return true;
        });
    }
})(jQuery);