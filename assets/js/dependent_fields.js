(function ($) {
    $.fn.dependentFields = function (targets) {
        var toggleFields = function (main, targets, conditionCallback) {
            var display = conditionCallback.call(this, main);
            $(targets).each(function (i, target) {
                var d = display;
                if ($(target).hasClass('dependent_field_inverse')) {
                    d = !d;
                }
                targets.parent('.row')[(d ? "slideDown" : "slideUp")](300);
            })
        }
        $.each(targets, function (name, callbacks) {
            var main = $('.dependent_field_' + name + '_main').parent().find('input');
            main.each(function (i, mainItem) {
                var targets = callbacks.targetFinder.call(this, name, $(mainItem));
                $(mainItem).on('change', function () {
                    toggleFields(this, targets, callbacks.conditionCallback);
                });
                toggleFields($(mainItem), targets, callbacks.conditionCallback);
            });
        });
    }
})(jQuery);