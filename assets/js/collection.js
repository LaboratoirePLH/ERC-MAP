(function ($) {
    $.fn.collectionField = function (options) {

        var settings = $.extend({
            // These are the defaults.
            blockTitle: "Item #",
            deleteLink: "Delete"
        }, options);

        var setupLinks = function (prototype) {
            var deleteLink = $(
                '<a href="#" class="btn btn-sm btn-danger">' + settings.deleteLink + '</a>'
            );
            deleteLink.click(function (e) {
                e.preventDefault();
                prototype.remove();
                return false;
            });
            var label = prototype.find('.remove_this_label');
            label.siblings('.col-sm-10').removeClass('col-sm-10').addClass('col-sm-12');
            label.hide();

            prototype.children('legend')
                .removeClass('col-sm-2 col-form-label')
                .addClass('col-sm-12 text-center h4')
                .append($('<br>'))
                .append(deleteLink);
            prototype.children('.col-sm-10')
                .removeClass('col-sm-10')
                .addClass('col-sm-12');
        };

        var addEntry = function (container) {
            var index = parseInt(container.attr('data-index'), 10),
                template = container.attr('data-prototype')
                    .replace(/__name__label__/g, settings.blockTitle + (index + 1))
                    .replace(/__name__/g, index);

            var prototype = $(template);

            setupLinks(prototype);
            container.append(prototype);
            index++;
            container.attr('data-index', index);
        };

        return this.each(function () {
            var container = $(this);
            var index = container.children('.form-group').length;

            container.attr('data-index', index);

            $('#add_sourcebiblio').click(function (e) {
                addEntry(container);
                e.preventDefault();
                container.trigger('collectionField.add', container.children(':last-child'));
                return false;
            });

            if (index == 0) {
                addEntry(container);
            } else {
                container.children('.form-group').each(function (index) {
                    $(this).children('legend').text(settings.blockTitle + (index + 1));
                    setupLinks($(this));
                });
            }
        });
    };
})(jQuery);