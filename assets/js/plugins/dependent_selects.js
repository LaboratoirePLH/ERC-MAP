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
                    selectLast.html('');
                    selectLast.attr('disabled', true);
                    if (selectLast.hasClass('autocomplete')) {
                        selectLast.trigger('chosen:updated');
                    }
                    return;
                }
                $.getJSON({
                    url: settings.data_url,
                    data: {
                        parentId: me.val()
                    },
                    success: function (rows) {
                        selectLast.html('');
                        selectLast.append('<option value selected></option>');
                        $.each(rows, function (key, row) {
                            selectLast.append('<option value="' + row.id + '">' + row.name + '</option>');
                        });
                        selectLast.attr('disabled', rows.length == 0);
                        if (selectLast.hasClass('autocomplete')) {
                            selectLast.trigger('chosen:updated');
                        }
                        selectFirst.trigger('dependent:updated');
                    },
                    error: function (err) {
                        alert("An error ocurred while loading data ...");
                    }
                });
            });

            selectFirst.val(selectFirst.val()).trigger('change');
        });
    }
})(jQuery);