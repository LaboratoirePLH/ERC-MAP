(function ($) {
    $.fn.dependentFields = function (targets) {
        var toggleFields = function (main, targets, conditionCallback) {
            var display = conditionCallback.call(this, main);
            $(targets).each(function (i, target) {
                var d = display;
                if ($(target).hasClass('dependent_field_inverse')) {
                    d = !d;
                }
                targets.closest('.row')[(d ? "slideDown" : "slideUp")](300);
            })
        }
        $.each(targets, function (name, settings) {
            var main = $('.dependent_field_' + name + '_main').parent().find('input');
            main.filter(':not(.dependent_field_enabled)').each(function (i, mainItem) {
                var targets = settings.targetFinder.call(this, name, $(mainItem));
                $(mainItem).on('change', function () {
                    toggleFields(this, targets, settings.conditionCallback);
                });
                toggleFields($(mainItem), targets, settings.conditionCallback);
                $(mainItem).addClass('dependent_field_enabled');
            });
        });
    }
})(jQuery);