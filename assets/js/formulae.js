(function ($) {
    const parenthesisStyles = [
        'IndianRed', 'Crimson', 'DarkRed',
        'DeepPink', 'PaleVioletRed',
        'OrangeRed', 'DarkOrange',
        'Gold', 'DarkKhaki',
        'Orchid', 'MediumPurple', 'DarkMagenta', 'Indigo', 'SlateBlue',
        'GreenYellow', 'Lime', 'MediumSpringGreen', 'MediumSeaGreen', 'ForestGreen', 'DarkGreen', 'Olive', 'MediumAquamarine', 'Teal',
        'Cyan', 'CadetBlue', 'SteelBlue', 'RoyalBlue', 'MidnightBlue',
        'RosyBrown', 'Sienna', 'Maroon',
        'DimGray', 'SlateGray', 'DarkSlateGray', 'Black'
    ].sort(function () { return 0.5 - Math.random(); });
    const operatorStyles = {
        '+': 'btn-success',
        '/': 'btn-danger',
        '#': 'btn-warning',
        '=': 'btn-info'
    };

    $.fn.parseFormula = function (formula) {
        var formulaElements = [];
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
                formulaElements.push({
                    type: 'element',
                    id: parseInt(id.join(''), 10)
                });
            }
        }
        return formulaElements;
    }
    $.fn.formulaElementRenderer = function (formulaEl, elements) {
        var btn = $('<button type="button">').addClass("btn mx-1 btn-outline-dark");
        if (formulaEl.type == 'element') {
            var id = null, text = "???";
            if (elements.hasOwnProperty(formulaEl.id)) {
                id = formulaEl.id;
                text = elements[formulaEl.id];
            }
            btn.addClass('btn-primary')
                .data('element-id', id)
                .text(text);
        } else if (formulaEl.type == 'operator') {
            btn.addClass(operatorStyles[formulaEl.display])
                .text(formulaEl.display);
        } else if (formulaEl.type == 'parenthesis') {
            const color = Number.isInteger(formulaEl.id) ? parenthesisStyles[formulaEl.id % parenthesisStyles.length] : 'Black';
            btn.css('background-color', color)
                .css('color', 'White')
                .text(formulaEl.display);
        }
        return btn;
    }

    $.fn.formulaRenderer = function (formula, elements) {
        var formula = $.fn.parseFormula(formula);
        return formula.map(function (formulaEl) {
            return $.fn.formulaElementRenderer(formulaEl, elements)
        });
    }

    $.fn.formulaEditor = function (settings) {
        var settings = $.extend({
            labels: {
                formule: "Formula",
                elements: "Elements",
                operateurs: "Operators"
            }
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
            // Create overall wrapper
            var editor = $('<div class="formula-editor"></div>');

            // Display existing formule if any
            // or an empty div

            var formulaButtons = [];
            const formule = $(this).find("input[name$='[formule]']").val();
            if (formule != "") {
                formulaButtons = $.fn.formulaRenderer(
                    formule, settings.elements
                );
            }
            editor.append(
                blockRenderer(settings.labels.formule, 'formula-visualizer', formulaButtons)
            );

            if ($(this).find("input[name$='[id]']").val() == "") {
                // Operators / Parenthesis
                var operatorButtons = [
                    $.fn.formulaElementRenderer({ type: 'parenthesis', display: '(' }),
                    $.fn.formulaElementRenderer({ type: 'parenthesis', display: ')' }),
                    $.fn.formulaElementRenderer({ type: 'operator', display: '+' }),
                    $.fn.formulaElementRenderer({ type: 'operator', display: '/' }),
                    $.fn.formulaElementRenderer({ type: 'operator', display: '#' }),
                    $.fn.formulaElementRenderer({ type: 'operator', display: '=' }),
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
                        }, settings.elements)
                    );
                }
                editor.append(
                    blockRenderer(settings.labels.elements, 'formula-elements', elementButtons)
                );
            }

            editor.insertAfter($(this).find('input[type=hidden]').last());

            // Setup drag & drop

            // Setup validation
        });
    }
})(jQuery);