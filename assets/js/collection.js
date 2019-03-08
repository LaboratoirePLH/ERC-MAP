(function ($) {
    $.fn.collectionField = function (options) {

        var settings = $.extend({
            // These are the defaults.
            blockTitle: "Item #",
            deleteLink: "Delete",
            addListener: $.noop(),
            confirmationModal: false
        }, options);

        var setupLinks = function (prototype) {
            var deleteLink = $(
                '<a href="#" class="btn btn-sm btn-danger ml-2 mb-1">' + settings.deleteLink + '</a>'
            );
            deleteLink.click(function (e) {
                e.preventDefault();
                if (settings.confirmationModal !== null && settings.confirmationModal.length) {
                    settings.confirmationModal.on('show.bs.modal', function () {
                        settings.confirmationModal.find('.modal-accept-button').on('click', function () {
                            prototype.remove();
                            settings.confirmationModal.modal('hide');
                            settings.confirmationModal.find('.modal-accept-button').off('click');
                        });
                    });
                    settings.confirmationModal.modal();
                }
                else {
                    prototype.remove();
                }
                return false;
            });
            var label = prototype.find('.remove_this_label');
            label.siblings('.col-sm-10').removeClass('col-sm-10').addClass('col-sm-12');
            label.hide();

            prototype.children('legend')
                .removeClass('col-sm-2 col-form-label')
                .addClass('col-sm-12 text-center h4')
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
            settings.addListener.call(this, container.children(':last-child'), index);
        };

        return this.each(function () {
            var container = $(this);
            var index = container.children('.form-group').length;

            container.attr('data-index', index);

            container.next().find('.collection-add-link').click(function (e) {
                addEntry(container);
                e.preventDefault();
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