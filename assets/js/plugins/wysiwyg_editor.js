// Import Quill
window.Quill = require('quill');
// Add fonts to whitelist
// var Font = Quill.import('attributors/style/font');
// // We do not add Aref Ruqaa since it is the default
// Font.whitelist = ['arial', 'ifaogreek'];
// Quill.register(Font, true);

var Parchment = Quill.import("parchment");

let IfaoFont = new Parchment.Attributor.Style('ifao', 'font-family', {
    whitelist: ['ifaogreek'],
    scope: Parchment.Scope.INLINE
});
Parchment.register(IfaoFont);

SEMITIC_KEYS = [
    // ṭṣšśʾʿḥ
    'ṭ:Tet',
    'ṣ:Tsade',
    'š:Shin',
    'ś:Sin',
    'ʾ:Ayn',
    'ʿ:Aleph',
    'ḥ:Het',
];

function semiticHandler() {
    return function (value) {
        if (value) {
            const cursorPosition = this.quill.getSelection().index;
            this.quill.insertText(cursorPosition, value);
            this.quill.setSelection(cursorPosition + value.length);
        }
    }
}

(function ($) {
    $.fn.quill = function () {
        return this.each(function () {
            if ($(this).hasClass('ql-container')) {
                return;
            }

            const handlers = {};
            SEMITIC_KEYS.map(k => k.split(':').pop()).forEach(k => handlers[k] = semiticHandler());

            const quillContainer = $(this).get(0);
            const textareaId = $(quillContainer).data('id');
            let quillInstance = new Quill("#" + quillContainer.id, {
                modules: {
                    toolbar: {
                        container: [
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ 'script': 'sub' }, { 'script': 'super' }],
                            [{ 'ifao': 'ifaogreek' }],
                            SEMITIC_KEYS.map(k => Object.fromEntries([k.split(':').reverse()]))
                        ],
                        handlers
                    },
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
            quillInstance.on('text-change', function (delta, oldDelta, source) {
                $("#" + textareaId).val(quillInstance.root.innerHTML);//.replace(/"/g, '\''));
            });

            $(this).siblings('.ql-toolbar').find('.ql-formats:last-child').children().each(function () {
                $(this).text($(this).val());
                $(this).attr('style', 'padding-top: 0px;');
            });
        })
    }
})(jQuery);