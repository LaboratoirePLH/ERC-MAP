(function ($) {
    $.fn.selectOrCreate = function () {
        var toggleCreationSelection = function (container, creation) {
            container.children('.selectorcreate_selection')[(creation === false ?
                "slideDown" :
                "slideUp")](300);
            container.children('.selectorcreate_creation')[(creation === true ?
                "slideDown" :
                "slideUp")](300);
        }

        return this.each(function () {
            var container = $(this);

            container.children('.selectorcreate_selection')
                .find('select.autocomplete')
                .chosen(CHOSEN_SETTINGS);

            var radio = container.children('.selectorcreate_decision')
                .find('input[type=radio]');

            radio.on('change', function (e) {
                var radioValue = container.children('.selectorcreate_decision')
                    .find('input[type=radio]:checked')
                    .val();
                if (radioValue) {
                    toggleCreationSelection(container, radioValue === 'create');
                } else {
                    toggleCreationSelection(container, null);
                }
            });
            var initialSelection = null;
            if (radio.filter(':checked').length === 1) {
                switch (radio.filter(':checked').val()) {
                    case 'create':
                        initialSelection = true;
                        break;
                    case 'select':
                        initialSelection = false;
                        break;
                }
            }
            toggleCreationSelection(container, initialSelection);
        });
    };
})(jQuery);