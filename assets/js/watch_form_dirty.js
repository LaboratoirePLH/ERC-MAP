(function ($) {
    $.fn.watchFormDirty = function (settings) {
        if (!settings.hasOwnProperty('message')) { throw "Missing 'message' parameter"; }
        if (!settings.hasOwnProperty('modal')) { throw "Missing 'modal' parameter"; }
        if (!settings.hasOwnProperty('exitLink')) { throw "Missing 'exitLink' parameter"; }

        return this.each(function () {
            var form_old = $(this).serialize(),
                modal_visible = false,
                isFormDirty = function (form) { return $(form).serialize() !== form_old; },
                promptConfirmation = function (e) {
                    if (!modal_visible && isFormDirty($(this))) {
                        // Cancel the event
                        e.preventDefault();
                        // Chrome requires returnValue to be set
                        e.returnValue = settings.message;
                        return settings.message;
                    }
                };

            $(settings.exitLink).click(function (e) {
                e.preventDefault();
                if (isFormDirty($(this))) {
                    $(settings.modal).modal();
                    $(settings.modal).on('shown.bs.modal', function () { modal_visible = true; });
                    $(settings.modal).on('hidden.bs.modal', function () { modal_visible = false; });
                    return false;
                } else {
                    window.location = $(settings.exitLink).attr('href');
                }
            });
            window.addEventListener('beforeunload', promptConfirmation);
            window.addEventListener('unload', promptConfirmation);
            $(this).on('submit', function () {
                window.removeEventListener('beforeunload', promptConfirmation);
                window.removeEventListener('unload', promptConfirmation);
            });
        });
    }
})(jQuery);