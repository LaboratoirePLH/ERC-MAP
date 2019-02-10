(function ($) {
    $.fn.dependentFields = function (names) {
        var toggleFields = function (main, targets) {
            targets.parent('.row')[($(main).is(':checked') ? "slideDown" : "slideUp")](300);
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