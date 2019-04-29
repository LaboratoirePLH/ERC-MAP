(function ($) {
    FORMULA_EDITOR_UUID = 0;
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
                            display: '('
                        });
                        break;
                    case ')':
                        formulaElements.push({
                            type: 'parenthesis',
                            id: --parenthesisIndex,
                            display: ')'
                        });
                        break;
                    default:
                        formulaElements.push({
                            type: 'operator',
                            display: chr
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
                    index: ++elementCpt[elementId]
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
        return btn;
    }

    $.fn.formulaRenderer = function (formula, settings) {
        var formula = $.fn.parseFormula(formula);
        return formula.map(function (formulaEl) {
            return $.fn.formulaElementRenderer(formulaEl, settings)
        });
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
            const formule = $(this).find("input[name$='[formule]']").val();
            if (formule != "") {
                formulaButtons = $.fn.formulaRenderer(
                    formule, settings
                );
            }
            editor.append(
                blockRenderer(settings.labels.formule, 'formula-visualizer', formulaButtons)
            );

            if ($(this).find("input[name$='[id]']").val() == "") {
                // Operators / Parenthesis
                var operatorButtons = [
                    $.fn.formulaElementRenderer({ type: 'parenthesis', display: '(' }, settings),
                    $.fn.formulaElementRenderer({ type: 'parenthesis', display: ')' }, settings),
                    $.fn.formulaElementRenderer({ type: 'operator', display: '+' }, settings),
                    $.fn.formulaElementRenderer({ type: 'operator', display: '/' }, settings),
                    $.fn.formulaElementRenderer({ type: 'operator', display: '#' }, settings),
                    $.fn.formulaElementRenderer({ type: 'operator', display: '=' }, settings),
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
                            id: elementId
                        }, settings)
                    );
                }
                editor.append(
                    blockRenderer(settings.labels.elements, 'formula-elements', elementButtons)
                );
            }

            editor.insertAfter($(this).find('input[type=hidden]').last());

            if ($(this).find("input[name$='[id]']").val() == "") {
                // Setup drag & drop
                sortableOperators = new Sortable($(this).find('.formula-operators').get(0), {
                    draggable: '.btn',
                    sort: false,
                    group: {
                        name: uuid,
                        pull: 'clone',
                        put: false
                    }
                });
                sortableElements = new Sortable($(this).find('.formula-elements').get(0), {
                    draggable: '.btn',
                    sort: false,
                    group: {
                        name: uuid,
                        pull: 'clone',
                        put: false
                    }
                });
                sortableFormula = new Sortable($(this).find('.formula-visualizer').get(0), {
                    draggable: '.btn',
                    sort: true,
                    group: {
                        name: uuid,
                    }
                })

                // Setup validation
            }
        });
    }
})(jQuery);