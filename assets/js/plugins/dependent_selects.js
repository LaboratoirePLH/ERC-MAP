(function ($) {
    $.fn.dependentSelects = function (settings) {

        var settings = $.extend({
            // These are the defaults.
        }, settings);
        if (!settings.hasOwnProperty('data_url')) {
            throw "Data URL was not specified";
        }

        return this.each(function () {
            var selectFirst = $(this).find('select').first(),
                selectLast = $(this).find('select').last();

            selectFirst.on('change', function () {
                var me = $(this);

                if (me.val() == "" || me.val() == null) {
                    selectLast.empty();
                    selectLast.attr('disabled', true);
                    if (selectLast.hasClass('autocomplete')) {
                        const options = selectLast.chosen().options;
                        selectLast.chosen("destroy");
                        selectLast.chosen(options);
                    }
                    return;
                }
                $.getJSON({
                    url: settings.data_url,
                    data: {
                        parentId: me.val()
                    },
                    success: function (rows) {
                        selectLast.empty();
                        selectLast.append('<option value ></option>');
                        $.each(rows, function (key, row) {
                            selectLast.append('<option value="' + row.id + '">' + row.name + '</option>');
                        });
                        selectLast.attr('disabled', rows.length == 0);
                        if (selectLast.hasClass('autocomplete')) {
                            const options = selectLast.chosen().options;
                            selectLast.chosen("destroy");
                            selectLast.chosen(options);
                        }
                        selectFirst.trigger('dependent:updated');
                    },
                    error: function (err) {
                        alert("An error ocurred while loading data ...");
                    }
                });
            });
            // If a value is select in the first list at render time,
            // Load the second list if and only if it doesn't already have values
            if (selectFirst.val() != "" &&
                (selectLast.find('option').length === 0 ||
                    (selectLast.find('option').length === 1 && selectLast.find('option').first().text() === '')
                )) {
                selectFirst.trigger('change');
            }
        });
    }
})(jQuery);