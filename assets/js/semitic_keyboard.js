// "n(a):title_or_tooltip"; n = new key, (a) = actual key (optional), ":label" = title_or_tooltip (use an underscore "_" in place of a space " ")
var keys = [
    't\u0323:Tet',
    's\u0323:Tsade',
    's\u030c:Shin',
    's\u0301:Sin',
    '\u02be:Ayn',
    '\u02bf:Aleph',
    'h\u0323:Het',
];

(function ($) {
    if (CKEDITOR !== undefined) {
        CKEDITOR.on('instanceReady', function (e) {
            if ($(e.editor.element.$).hasClass('semitic_keyboard')) {
                var keyboardTarget = $(e.editor.ui.contentsElement.$).find('iframe').contents().find('body');
                keyboardTarget.keyboard({
                    layout: 'custom',
                    customLayout: {
                        'normal': [
                            keys.join(' ')
                        ]
                    },
                    position: {
                        of: $(e.editor.container.$),
                        my: 'left bottom',
                        at: 'left bottom',
                        at2: 'left bottom'
                    },
                    autoAccept: true,
                    autoAcceptOnEsc: true,
                    usePreview: false,
                });
                e.editor.on('focus', function () {
                    keyboardTarget.data('keyboard').reveal();
                });
                e.editor.on('blur', function () {
                    keyboardTarget.data('keyboard').accept();
                });
            }
        });
    }
})(jQuery);