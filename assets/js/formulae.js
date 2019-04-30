FORMULA_EDITOR_UUID = 0;
(function ($) {
    const parenthesisStyles = [
        'Black',
        'CadetBlue',
        'Crimson',
        'Cyan',
        'DarkGreen',
        'DarkKhaki',
        'DarkMagenta',
        'DarkOrange',
        'DarkRed',
        'DarkSlateGray',
        'DeepPink',
        'DimGray',
        'ForestGreen',
        'GreenYellow',
        'IndianRed',
        'Indigo',
        'Lime',
        'Maroon',
        'MediumAquamarine',
        'MediumPurple',
        'MediumSeaGreen',
        'MediumSpringGreen',
        'MidnightBlue',
        'Olive',
        'OrangeRed',
        'Orchid',
        'PaleVioletRed',
        'RosyBrown',
        'RoyalBlue',
        'Sienna',
        'SlateBlue',
        'SlateGray',
        'SteelBlue',
        'Teal',
    ].sort(function () { return 0.5 - Math.random(); });

    $.fn.parseFormula = function (formula) {
        var formulaElements = [];
        var elementCpt = {};
        var parenthesisIndex = 0;
        for (var i = 0; i < formula.length; i++) {
            const chr = formula.charAt(i);
            if (chr != '[') {
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
                while (formula.charAt(++i) != ']') {
                    id.push(formula.charAt(i));
                }
                const elementId = parseInt(id.join(''), 10);
                if (!elementCpt.hasOwnProperty(elementId)) { elementCpt[elementId] = 0; }
                formulaElements.push({
                    type: 'element',
                    id: elementId,
                    index: ++elementCpt[elementId],
                    raw: '[' + elementId + ']'
                });
            }
        }
        for (var e of formulaElements) {
            if (e.type == 'element' && elementCpt[e.id] <= 1) {
                e.index = null;
            }
        }
        return formulaElements;
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
            btn.addClass(settings.operatorCls)
                .text(formulaEl.display);
        } else if (formulaEl.type == 'parenthesis') {
            const color = Number.isInteger(formulaEl.id) ? parenthesisStyles[formulaEl.id % parenthesisStyles.length] : 'Black';
            btn.css('background-color', color)
                .css('color', 'White')
                .text(formulaEl.display);
        }
        btn.data('raw', formulaEl.raw);
        return btn;
    }

    $.fn.formulaRenderer = function (formula, settings) {
        var formula = $.fn.parseFormula(formula);
        return formula.map(function (formulaEl) {
            return $.fn.formulaElementRenderer(formulaEl, settings)
        });
    }

    $.fn.formulaValidator = function (formulaElements, settings) {

        return true;
    }

    $.fn.formulaEditor = function (settings) {
        var settings = $.extend({
            labels: {
                formule: "Formula",
                elements: "Elements",
                operateurs: "Operators"
            },
            elementCls: 'btn-info',
            operatorCls: 'btn-warning'
            // These are the defaults.
        }, settings);

        var blockRenderer = function (label, blockClass, buttons) {
            var buttonWrapper = $('<div/>', {
                class: 'col-10 d-flex justify-content-center ' + blockClass
            });
            $.each(buttons, function (i, b) { buttonWrapper.append(b) });
            return $('<div/>', { class: 'row' })
                .append($('<div/>', { class: 'col-2 text-right label', html: label }))
                .append(buttonWrapper);
        }

        return this.each(function () {
            const uuid = "formula-editor-" + (++FORMULA_EDITOR_UUID);
            // Create overall wrapper
            var editor = $('<div class="formula-editor"></div>');
            editor.prop('id', uuid);

            // Display existing formule if any
            // or an empty div

            var formulaButtons = [];
            var me = this;
            const formule = $(me).find("input[name$='[formule]']").val();
            if (formule != "") {
                formulaButtons = $.fn.formulaRenderer(
                    formule, settings
                );
            }
            editor.append(
                blockRenderer(settings.labels.formule, 'formula-visualizer w-100', formulaButtons)
            );

            if ($(me).find("input[name$='[id]']").val() == "") {
                // Operators / Parenthesis
                var operatorButtons = [
                    $.fn.formulaElementRenderer({ type: 'parenthesis', display: '(', raw: '(' }, settings),
                    $.fn.formulaElementRenderer({ type: 'parenthesis', display: ')', raw: ')' }, settings),
                    $.fn.formulaElementRenderer({ type: 'operator', display: '+', raw: '+' }, settings),
                    $.fn.formulaElementRenderer({ type: 'operator', display: '/', raw: '/' }, settings),
                    $.fn.formulaElementRenderer({ type: 'operator', display: '#', raw: '#' }, settings),
                    $.fn.formulaElementRenderer({ type: 'operator', display: '=', raw: '=' }, settings),
                ];
                editor.append(
                    blockRenderer(settings.labels.operateurs, 'formula-operators', operatorButtons)
                );

                // Elements
                var elementButtons = [];
                for (var elementId in settings.elements) {
                    if (!settings.elements.hasOwnProperty(elementId)) { continue; }
                    elementButtons.push(
                        $.fn.formulaElementRenderer({
                            type: 'element',
                            id: elementId,
                            raw: '[' + elementId + ']'
                        }, settings)
                    );
                }
                editor.append(
                    blockRenderer(settings.labels.elements, 'formula-elements', elementButtons)
                );
            }

            editor.insertAfter($(me).find('input[type=hidden]').last());

            if ($(me).find("input[name$='[id]']").val() == "") {
                // Setup drag & drop
                sortableOperators = new Sortable($(me).find('.formula-operators').get(0), {
                    draggable: '.btn',
                    sort: false,
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
                sortableElements = new Sortable($(me).find('.formula-elements').get(0), {
                    draggable: '.btn',
                    sort: false,
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
                sortableFormula = new Sortable($(me).find('.formula-visualizer').get(0), {
                    draggable: '.btn',
                    sort: true,
                    group: {
                        name: uuid,
                        pull: [uuid],
                        put: [uuid],
                    },
                    onSort: function (evt) {
                        var formule = [];
                        $(evt.to).find('.btn').each(function (i, b) {
                            formule.push($(b).data('raw'));
                        });
                        $(me).find("input[name$='[formule]']").val(formule.join('')).trigger('change');

                    }
                })

                // Setup validation
                $(me).find("input[name$='[formule]']").on('change', function (e) {
                    const formula = $(this).val();
                    const validation = $.fn.formulaValidator(formula, settings);
                    if (validation !== true) {
                        alert('formula error');
                    } else {
                        var buttons = $.fn.formulaRenderer(formula, settings);
                        $(me).find('.formula-visualizer').empty().append(buttons);
                    }
                });
            }
        });
    }
})(jQuery);