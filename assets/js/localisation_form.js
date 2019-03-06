(function ($) {
    $.fn.localisationForm = function (settings) {

        var settings = $.extend({
            // These are the defaults.
            errorMessage: "Error",
            notFoundErrorMessage: "Not Found",
            ambiguousErrorMassage: "Ambiguous Data"
        }, settings);
        if (!settings.hasOwnProperty('dataUrl')) {
            throw "Data URL was not specified";
        }

        return this.each(function () {
            var rootForm = $(this);

            rootForm.find('.pleiades_search').on('click', function (e) {
                e.preventDefault();
                var btn = $(this),
                    errorEl = rootForm.find('.pleiades-error'),
                    input = $(this).parent().siblings('input[type=number]');

                if (input.val() !== "") {
                    // Display loader
                    btn.prepend($('<i class="fas fa-spinner fa-pulse mr-2 loader"></i>'));
                    // Empty error message
                    errorEl.text('');

                    var labelId = $(this).parent().parent().parent().siblings('label').attr('id'),
                        labelSeg = labelId.split('_'),
                        fields = {};

                    // Get the fields to fill if the request is successful
                    rootForm.find('.pleiades_field').each(function () {
                        var seg = $(this).attr('id').split('_');
                        if (seg[0] == labelSeg[0] && seg[1] == labelSeg[1]) {
                            fields[seg[2]] = $(this).siblings().find('input');
                        }
                    });

                    $.getJSON("https://pleiades.stoa.org/places/" + input.val() + "/json")
                        .done(function (data) {
                            if (fields.hasOwnProperty('nom'))
                                fields.nom.attr('readonly', true).val(data.title).trigger('change');
                            if (fields.hasOwnProperty('longitude'))
                                fields.longitude.attr('readonly', true).val(data.reprPoint[0]).trigger('change');
                            if (fields.hasOwnProperty('latitude'))
                                fields.latitude.attr('readonly', true).val(data.reprPoint[1]).trigger('change');
                            input.attr('readonly', true);
                        })
                        .fail(function (jqXHR, textStatus, errorThrown) {
                            var error = settings.errorMessage;
                            if (errorThrown === "Not Found") {
                                error = settings.notFoundErrorMessage;
                            }
                            errorEl.text(error);
                            input.val('');
                        })
                        .always(function () {
                            btn.find('i.loader').remove();
                        });
                }

                return false;
            });
            rootForm.find('.pleiades_clear').on('click', function (e) {
                e.preventDefault();
                var labelId = $(this).parent().parent().parent().siblings('label').attr('id'),
                    labelSeg = labelId.split('_');

                rootForm.find('.pleiades_field').each(function () {
                    var seg = $(this).attr('id').split('_');
                    if (seg[0] == labelSeg[0] && seg[1] == labelSeg[1]) {
                        $(this).siblings().find('input').attr('readonly', false).val('');
                    }
                });
                $(this).parent().siblings('input[type=number]').val('').attr('readonly', false);
                rootForm.find('.pleiades-error').text('');
                return false;
            });
            rootForm.find('.pleiades_view').on('click', function (e) {
                var id = parseInt($(this).parent().siblings('input[type=number]').val(), 10);
                console.log(id);
                if (Number.isNaN(id) || id == 0) {
                    id = "";
                }
                $(this).attr('href', "https://pleiades.stoa.org/places/" + id);
            });

            rootForm.find('.pleiades_search').each(function (i, searchButton) {
                var idField = $(searchButton).parent().siblings('input[type=number]');
                if (idField.val() !== "") {
                    var labelId = $(searchButton).parent().parent().parent().siblings('label').attr('id'),
                        labelSeg = labelId.split('_');

                    rootForm.find('.pleiades_field').each(function () {
                        var seg = $(this).attr('id').split('_');
                        if (seg[0] == labelSeg[0] && seg[1] == labelSeg[1]) {
                            $(this).siblings().find('input').attr('readonly', true);
                        }
                    });
                    idField.attr('readonly', true);
                }
            })

            rootForm.find('.citysearch_search').on('click', function (e) {
                e.preventDefault();
                var btn = $(this),
                    errorEl = rootForm.find('.citysearch-error'),
                    cityInput = $(this).parent().siblings('input[type=text]'),
                    pleiadesInput = rootForm.find('.pleiades_search').parent().siblings('input[type=number]');

                if (cityInput.val() !== "" || pleiadesInput.val() !== "") {
                    // Display loader
                    btn.prepend($('<i class="fas fa-spinner fa-pulse mr-2 loader"></i>'));
                    // Empty error message
                    errorEl.text('');

                    var labelId = $(this).parent().parent().parent().siblings('label').attr('id'),
                        labelSeg = labelId.split('_'),
                        fields = {};

                    // Get the fields to fill if the request is successful
                    rootForm.find('.citysearch_field').each(function () {
                        var seg = $(this).attr('id').split('_');
                        if (seg[0] == labelSeg[0] && seg[1] == labelSeg[1]) {
                            fields[seg[2]] = $(this).siblings().find('input,select');
                        }
                    });

                    var data = {};
                    if (cityInput.val() !== "") { data.city = cityInput.val(); }
                    if (pleiadesInput.val() !== "") { data.pleiades = pleiadesInput.val(); }

                    $.getJSON(settings.dataUrl, data)
                        .done(function (data) {
                            $.each(data, function (fieldName, value) {
                                if (fields.hasOwnProperty(fieldName)) {
                                    if (fieldName == "granderegion" && fields.hasOwnProperty("sousregion")) {
                                        fields.granderegion.val(value).on('dependent:updated', function () {
                                            fields.sousregion.val(data.sousregion).trigger('chosen:updated');
                                        }).trigger('chosen:updated').trigger('change')
                                    }
                                    else if (fieldName == "sousregion") {
                                        return true;
                                    }
                                    else {
                                        fields[fieldName].val(value);
                                    }
                                }

                            })
                            cityInput.val(data.nom);
                        })
                        .fail(function (jqXHR, textStatus, errorThrown) {
                            var error = settings.errorMessage;
                            if (errorThrown === "Not Found") {
                                error = settings.notFoundErrorMessage;
                            }
                            if (errorThrown === "Not Acceptable") {
                                error = settings.ambiguousErrorMassage;
                            }
                            errorEl.text(error);
                        })
                        .always(function () {
                            btn.find('i.loader').remove();
                        });
                }

                return false;
            });
        });
    }
})(jQuery);