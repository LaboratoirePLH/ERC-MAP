(function ($) {
    $.fn.localisationForm = function (settings) {

        var settings = $.extend({
            // These are the defaults.
            errorMessage: "Error",
            notFoundErrorMessage: "Not Found",
            ambiguousErrorMassage: "Ambiguous Data",
        }, settings);
        if (!settings.hasOwnProperty('dataUrl')) {
            throw "Data URL was not specified";
        }

        return this.each(function () {
            var rootForm = $(this);

            if (rootForm.find('#' + rootForm.prop('id') + '_id').val() === "") {
                rootForm.find('.real_location_checkbox').prop('checked', true);
            }

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
                    resultsEl = rootForm.find('.citysearch-results'),
                    cityInput = $(this).parent().siblings('input[type=text]');

                if (cityInput.val() !== "") {
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
                    data.city = cityInput.val();

                    $.getJSON(settings.dataUrl, data)
                        .done(function (data) {
                            resultsEl.empty();
                            var ul = $('<ul>');
                            $.each(data, function (i, row) {
                                var a = $('<a>');

                                var rowData = [
                                    '#', row.id, ': ',
                                    row.nom, '(',
                                    row.latitude, ', ',
                                    row.longitude, ')'
                                ];
                                a.text(rowData.join(''));
                                a.prop('href', '#');
                                a.on('click', function (e) {
                                    e.preventDefault();
                                    fields.granderegion.on('dependent:updated', function () {
                                        fields.sousregion.val(row.sousregion).trigger('chosen:updated');
                                    }).val(row.granderegion).trigger('chosen:updated').trigger('change')
                                    fields.id.val(row.id);
                                    fields.longitude.val(row.longitude);
                                    fields.latitude.val(row.latitude);
                                    cityInput.val(row.nom);
                                    resultsEl.empty();
                                    return false;
                                });
                                ul.append($('<li>').append(a));
                            });
                            resultsEl.append(ul);
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
            rootForm.find('#localisation_site_nom')
                .siblings()
                .children('input[type=text]')
                .on('keyup', function (e) {
                    $(this).trigger('change');
                });
        });

    }
})(jQuery);