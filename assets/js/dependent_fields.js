(function ($) {
    $.fn.dependentFields = function (names) {
        var toggleFields = function (main, targets) {
            var display = $(main).is(':checked');
            $(targets).each(function (i, target) {
                var d = display;
                if ($(target).hasClass('dependent_field_inverse')) {
                    d = !d;
                }
                targets.parent('.row')[(d ? "slideDown" : "slideUp")](300);
            })
        }
        $.each(names, function (i, name) {
            var main = $('.dependent_field_' + name + '_main').siblings('input[type=checkbox]'),
                targets = $('.dependent_field_' + name);
            main.on('change', function () {
                toggleFields(this, targets);
            });
            toggleFields(main, targets);
        });
    }
})(jQuery);