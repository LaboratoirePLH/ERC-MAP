(function ($) {
    $.fn.precisionIndicator = function (settings) {
        var settings = $.extend({
            // These are the defaults.
        }, settings);
        if (!settings.hasOwnProperty('displayEl')) {
            throw "Display Element was not specified";
        }
        if (!settings.hasOwnProperty('displayFn')) {
            throw "Display Function was not specified";
        }
        if (!settings.hasOwnProperty('calculatorFn')) {
            throw "Calculator function was not specified";
        }

        var calculateAndDisplay = function (data) {
            var value = settings.calculatorFn.call(this, data);
            settings.displayFn.call(this, settings.displayEl, value);
        }

        return this.each(function () {
            var form = $(this);
            form.find(':input').on('change', function () {
                calculateAndDisplay(form.serializeObject());
            });
            setTimeout(function () {
                form.find(':input').first().trigger('change');
            }, 500);
        });
    }
})(jQuery);