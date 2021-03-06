import Sortable from "sortablejs";

var get_browser = function () {
    var ua = navigator.userAgent, tem, M = ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
    if (/trident/i.test(M[1])) {
        tem = /\brv[ :]+(\d+)/g.exec(ua) || [];
        return { name: 'IE', version: (tem[1] || '') };
    }
    if (M[1] === 'Chrome') {
        tem = ua.match(/\bOPR|Edge\/(\d+)/)
        if (tem != null) { return { name: 'Opera', version: tem[1] }; }
    }
    M = M[2] ? [M[1], M[2]] : [navigator.appName, navigator.appVersion, '-?'];
    if ((tem = ua.match(/version\/(\d+)/i)) != null) { M.splice(1, 1, tem[1]); }
    return {
        name: M[0],
        version: M[1]
    };
};

(function ($) {
    var FORMULA_EDITOR_UUID = 0;

    // Color shades generated by https://gka.github.io/palettes/ (sequential, 15 colors)

    const parenthesisStyles = [
        '#610000',
        '#710000',
        '#820000',
        '#930000',
        '#a40000',
        '#b50000',
        '#c70000',
        '#da0000',
        '#ec0000',
        '#ff0000',
        '#ff4022',
        '#ff5c37',
        '#ff7349',
        '#ff8659',
        '#ff9868'
    ];

    const bracketsStyles = [
        '#002b00',
        '#003800',
        '#004500',
        '#005300',
        '#006100',
        '#007000',
        '#007f00',
        '#008e00',
        '#009e00',
        '#00ae00',
        '#00be00',
        '#00ce00',
        '#00df00',
        '#00f000',
        '#3dff2b'
    ];

    const operatorStyles = {
        '+': 'DarkOrange',
        '/': 'RosyBrown',
        '#': 'Indigo',
        '=': 'RoyalBlue'
    }

    const validationRegexes = [
        [/^(\+|\#|\/|\=).*/, 'operator_start'],
        [/^.*(\+|\#|\/|\=)$/, 'operator_end'],
        [/(\[|\()(\+|\#|\/|\=)/, 'operator_imbrication'],
        [/(\+|\#|\/|\=)(\]|\))/, 'operator_imbrication'],
        [/(\]\(|\)\[|\[\)|\(\]|\[\]|\(\)|\]\[|\)\()/, 'brackets_parenthesis_imbrication'],
        [/(\+|\#|\/|\=)(\+|\#|\/|\=)/, 'operator_twice'],
        [/\}\{/, 'element_twice']
    ]

    $.fn.parseFormula = function (formula, settings) {
        var formulaElements = [];
        var elementCpt = {};
        var parenthesisIndex = 0;
        var bracketsIndex = 0;
        var errors = [];
        for (var i = 0; i < formula.length; i++) {
            const chr = formula.charAt(i);
            if (chr != '{') {
                switch (chr) {
                    case '(':
                        formulaElements.push({
                            type: 'parenthesis',
                            id: parenthesisIndex++,
                            display: chr,
                            raw: chr
                        });
                        break;
                    case ')':
                        formulaElements.push({
                            type: 'parenthesis',
                            id: --parenthesisIndex,
                            display: chr,
                            raw: chr
                        });
                        if (parenthesisIndex < 0) {
                            errors.push(settings.errors.parenthesis)
                        }
                        break;
                    case '[':
                        formulaElements.push({
                            type: 'brackets',
                            id: bracketsIndex++,
                            display: chr,
                            raw: chr
                        });
                        break;
                    case ']':
                        formulaElements.push({
                            type: 'brackets',
                            id: --bracketsIndex,
                            display: chr,
                            raw: chr
                        });
                        if (bracketsIndex < 0) {
                            errors.push(settings.errors.brackets)
                        }
                        break;
                    default:
                        formulaElements.push({
                            type: 'operator',
                            display: chr,
                            raw: chr
                        });
                }
            } else {
                var id = [];
                while (formula.charAt(++i) != '}') {
                    id.push(formula.charAt(i));
                }
                const elementId = parseInt(id.join(''), 10);
                if (!elementCpt.hasOwnProperty(elementId)) { elementCpt[elementId] = 0; }
                if (!settings.elements.hasOwnProperty(elementId)) {
                    errors.push(settings.errors.unknown_element);
                }
                formulaElements.push({
                    type: 'element',
                    id: elementId,
                    index: ++elementCpt[elementId],
                    raw: '{' + elementId + '}'
                });
            }
        }
        var hasElements = false;
        for (var e of formulaElements) {
            if (e.type == 'element') {
                hasElements = true;
                if (elementCpt[e.id] <= 1) {
                    e.index = null;
                }
            }
        }

        // Formula must contain all the elements linked to the testimony
        if (!hasElements) {
            errors.push(settings.errors.no_element);
        }
        else if (Object.keys(elementCpt).length < Object.keys(settings.elements).length) {
            errors.push(settings.errors.not_all_elements);
        }

        if (parenthesisIndex > 0) {
            errors.push(settings.errors.parenthesis)
        }
        if (bracketsIndex > 0) {
            errors.push(settings.errors.brackets)
        }

        validationRegexes.forEach(([regex, errorCode]) => {
            if (formula.match(regex)) {
                errors.push(settings.errors[errorCode]);
            }
        })

        return { formulaElements, errors };
    }
    $.fn.formulaElementRenderer = function (formulaEl, settings) {
        var btn = $('<button type="button">').addClass("btn mx-1 btn-outline-dark");
        if (formulaEl.type == 'element') {
            var id = null, text = "???";
            if (settings.elements.hasOwnProperty(formulaEl.id)) {
                id = formulaEl.id;
                text = settings.elements[formulaEl.id];
                if (formulaEl.index != null) {
                    text += " <small><i>(" + formulaEl.index + ")</i></small>";
                }
            }
            btn.addClass(settings.elementCls)
                .data('element-id', id)
                .html(text);
        } else if (formulaEl.type == 'operator') {
            btn.css('background-color', operatorStyles[formulaEl.display])
                .css('color', 'White')
                .text(formulaEl.display);
        } else if (formulaEl.type == 'parenthesis') {
            const color = Number.isInteger(formulaEl.id) ? parenthesisStyles[formulaEl.id % parenthesisStyles.length] : 'Black';
            btn.css('background-color', color)
                .css('color', 'White')
                .text(formulaEl.display);
        } else if (formulaEl.type == 'brackets') {
            const color = (Number.isInteger(formulaEl.id)) ? bracketsStyles[bracketsStyles.length - 1 - (formulaEl.id % bracketsStyles.length)] : 'Black';
            btn.css('background-color', color)
                .css('color', 'White')
                .text(formulaEl.display);
        }
        btn.data('raw', formulaEl.raw);
        return btn;
    }

    $.fn.formulaRenderer = function (formula, settings) {
        const { formulaElements, errors } = $.fn.parseFormula(formula, settings);
        var formulaButtons = formulaElements.map(function (formulaEl) {
            return $.fn.formulaElementRenderer(formulaEl, settings)
        });
        return { formulaButtons, errors };
    }

    $.fn.formulaEditor = function (settings) {
        var settings = $.extend({
            labels: {
                formule: "Formula",
                elements: "Elements",
                operateurs: "Operators"
            },
            errors: {
                valid: "Formula is valid",
                unknown_element: "Unknown element",
                no_element: "No element in the formula",
                not_all_elements: "All elements must appear in the formula",
                element_twice: "Elements cannot be directly followed by another element",
                brackets: "Brackets order error",
                parenthesis: "Parenthesis order error",
                brackets_parenthesis_imbrication: "Brackets and parenthesis imbrication error",
                operator_start: "Formula cannot start with an operator",
                operator_end: "Formula cannot end with an operator",
                operator_twice: "Operator cannot be directly followed by another operator",
                operator_imbrication: "Brackets and parentheses cannot start or end with an operator"
            },
            help: 'Help',
            elementCls: 'btn-info',
            operatorCls: 'btn-warning',
            searchMode: false,
            formulaInputSelector: "input[name$='[formule]']"
            // These are the defaults.
        }, settings);

        var blockRenderer = function (label, blockClass, buttons, errors) {
            var buttonWrapper = $('<div/>', {
                class: 'col d-flex flex-wrap justify-content-center ' + blockClass
            });
            $.each(buttons, function (i, b) { buttonWrapper.append(b) });

            if (settings.searchMode) {
                return $('<div/>', { class: 'row' })
                    .append($('<div/>', { class: 'col-2 text-right label', html: label }))
                    .append(buttonWrapper);
            } else {
                if (errors !== false) {
                    errors = errors.filter(function (item, pos, self) {
                        return self.indexOf(item) == pos;
                    });
                    var statusWrapper = $('<div/>', {
                        class: 'col-1 text-center formula-status',
                        html: '<i class="fas fa-check-circle text-success' + (errors.length > 0 ? ' d-none' : '') + '" data-toggle="tooltip" data-html="true" data-placement="left" title="' + settings.errors.valid + '" ></i>'
                            + '<i class="fas fa-exclamation-triangle text-danger' + (errors.length == 0 ? ' d-none' : '') + '" data-toggle="tooltip" data-html="true" data-placement="left" title="' + errors.join('<hr/>') + '" ></i>'
                    })
                } else {
                    errors = "";
                }
                return $('<div/>', { class: 'row' })
                    .append($('<div/>', { class: 'col-2 text-right label', html: label }))
                    .append(buttonWrapper)
                    .append(statusWrapper);
            }
        }

        return this.each(function () {
            const uuid = "formula-editor-" + (++FORMULA_EDITOR_UUID);
            // Create overall wrapper
            var editor = $('<div class="formula-editor"></div>');
            editor.prop('id', uuid);

            // Display existing formule if any
            // or an empty div

            var formulaButtons = [];
            var help = "";
            var me = this;
            const formule = $(me).find(settings.formulaInputSelector).val();
            if (formule != "") {
                var { formulaButtons, errors } = $.fn.formulaRenderer(
                    formule, settings
                );
            }
            if (formule == "" || settings.searchMode == true) {
                help = '&nbsp; <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="' + settings.help + '"></i>';
            }

            editor.append(
                blockRenderer(settings.labels.formule + help, 'formula-visualizer w-100', formulaButtons, errors || [])
            );

            if (settings.searchMode === true || $(me).find("input[name$='[id]']").val() == "") {
                // Operators / Parenthesis
                var operatorButtons = [
                    $.fn.formulaElementRenderer({ type: 'parenthesis', display: '(', raw: '(' }, settings),
                    $.fn.formulaElementRenderer({ type: 'parenthesis', display: ')', raw: ')' }, settings),
                    $.fn.formulaElementRenderer({ type: 'brackets', display: '[', raw: '[' }, settings),
                    $.fn.formulaElementRenderer({ type: 'brackets', display: ']', raw: ']' }, settings),
                    $.fn.formulaElementRenderer({ type: 'operator', display: '+', raw: '+' }, settings),
                    $.fn.formulaElementRenderer({ type: 'operator', display: '/', raw: '/' }, settings),
                    $.fn.formulaElementRenderer({ type: 'operator', display: '#', raw: '#' }, settings),
                    $.fn.formulaElementRenderer({ type: 'operator', display: '=', raw: '=' }, settings),
                ];
                editor.append(
                    blockRenderer(settings.labels.operateurs, 'formula-operators', operatorButtons, false)
                );

                // Elements
                var elementButtons = [];
                for (var elementId in settings.elements) {
                    if (!settings.elements.hasOwnProperty(elementId)) { continue; }
                    elementButtons.push(
                        $.fn.formulaElementRenderer({
                            type: 'element',
                            id: elementId,
                            raw: '{' + elementId + '}'
                        }, settings)
                    );
                }
                editor.append(
                    blockRenderer(settings.labels.elements, 'formula-elements', elementButtons, false)
                );
            }

            // Remove existing editor
            $(me).find('.formula-editor').remove();

            editor.insertAfter($(me).find('input[type=hidden]').last());

            if (settings.searchMode === true || $(me).find("input[name$='[id]']").val() == "") {
                // Setup drag & drop
                const isOldFirefox = get_browser()['name'] == 'Firefox' && get_browser()['version'] < "64";
                new Sortable($(me).find('.formula-operators').get(0), {
                    draggable: '.btn',
                    sort: false,
                    forceFallback: isOldFirefox,
                    group: {
                        name: uuid,
                        pull: 'clone',
                        put: [uuid]
                    },
                    onAdd: function (evt) {
                        var el = evt.item;
                        el.parentNode.removeChild(el);
                    }
                });
                new Sortable($(me).find('.formula-elements').get(0), {
                    draggable: '.btn',
                    sort: false,
                    forceFallback: isOldFirefox,
                    group: {
                        name: uuid,
                        pull: 'clone',
                        put: [uuid]
                    },
                    onAdd: function (evt) {
                        var el = evt.item;
                        el.parentNode.removeChild(el);
                    }
                });
                new Sortable($(me).find('.formula-visualizer').get(0), {
                    draggable: '.btn',
                    sort: true,
                    forceFallback: isOldFirefox,
                    group: {
                        name: uuid,
                        pull: [uuid],
                        put: [uuid],
                    },
                    onSort: function (evt) {
                        var formule = [];
                        if ($(evt.to).hasClass('formula-visualizer')) {
                            var target = $(evt.to)
                        }
                        else if ($(evt.from).hasClass('formula-visualizer')) {
                            var target = $(evt.from)
                        }
                        else {
                            throw "Did not drag or drop from visualizer";
                        }
                        target.find('.btn').each(function (i, b) {
                            formule.push($(b).data('raw'));
                        });
                        $(me).find(settings.formulaInputSelector).val(formule.join('')).trigger('change');

                    }
                })

                // Setup validation
                $(me).find(settings.formulaInputSelector).on('change', function (e) {
                    const formula = $(this).val();
                    const { formulaButtons, errors } = $.fn.formulaRenderer(formula, settings);
                    $(me).find('.formula-visualizer').empty().append(formulaButtons);
                    if (errors.length == 0) {
                        $(me).find('.formula-status').find('.text-success').removeClass('d-none');
                        $(me).find('.formula-status').find('.text-danger').addClass('d-none');
                    } else {
                        $(me).find('.formula-status').find('.text-success').addClass('d-none');
                        $(me).find('.formula-status').find('.text-danger').removeClass('d-none').prop('title', errors.join('<br/>'));
                    }
                    $(me).find('.formula-status').find('[data-toggle="tooltip"]').tooltip('dispose').tooltip();
                });

            }
            // Setup tooltip for help
            $(me).find('[data-toggle="tooltip"]').tooltip();
        });
    }
})(jQuery);