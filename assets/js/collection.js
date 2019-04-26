(function ($) {
    $.fn.collectionField = function (options) {

        var settings = $.extend({
            // These are the defaults.
            blockTitle: "#",
            deleteLinkGenerator: function () {
                return $(
                    '<a href="#" class="btn btn-sm btn-danger ml-2 mb-1">Delete</a>'
                )
            },
            toggleButtonGenerator: function (childId, collapsed) {
                return $(
                    '<button class="btn btn-sm btn-outline-secondary expand-button float-left" type="button" data-toggle="collapse"' +
                    'data-target="#' + childId + '" aria-expanded="' +
                    (collapsed ? 'false' : 'true') + '"' +
                    'onclick = "' +
                    "$(this).children('i.fas').toggleClass('fa-rotate-90');" +
                    '"><i class="fas fa-caret-right fa-fw' +
                    (collapsed ? '' : ' fa-rotate-90') + '"></i>' +
                    '</button>'
                )
            },
            viewLinkGenerator: function () { return; },
            addListener: function () { return; },
            deleteListener: function () { return; },
            setupListener: function () { return; },
            confirmationModal: false,
            inline: false,
            addLink: null,
            persistedClass: "collection-persisted-item",
        }, options);

        var setupLinks = function (prototype, container, collapsed) {
            var me = this;
            var deleteLink = settings.deleteLinkGenerator.call(this, prototype);
            if (deleteLink !== "" && $(deleteLink).prop('tagName') == 'A') {
                deleteLink.click(function (e) {
                    e.preventDefault();
                    if (settings.confirmationModal !== null && settings.confirmationModal.length) {
                        settings.confirmationModal.on('show.bs.modal', function () {
                            settings.confirmationModal.find('.modal-accept-button').on('click', function () {
                                prototype.remove();
                                settings.deleteListener.call(me, prototype);
                                settings.confirmationModal.modal('hide');
                                settings.confirmationModal.find('.modal-accept-button').off('click');
                            });
                        });
                        settings.confirmationModal.modal();
                    }
                    else {
                        prototype.remove();
                        settings.deleteListener.call(me, prototype);
                    }
                    return false;
                });
            }
            var viewLink = settings.viewLinkGenerator.call(this, prototype);

            if (settings.inline === true) {
                prototype
                    .removeClass('form-group')
                    .addClass('collection-inline-item');
                prototype.children('legend')
                    .removeClass('col-sm-2')
                    .addClass('col-sm-1');
                prototype.children('col-sm-10')
                    .attr('id', container.attr('id') + '_item_' + prototype.index());

                var buttonContainer = $('<div class="text-right">');
                buttonContainer
                    .addClass('col-sm-1')
                    .append(deleteLink)
                    .append(viewLink)
                    .appendTo(prototype);
            }
            else {
                prototype.addClass('collection-item');
                var label = prototype.find('.remove_this_label');
                label.siblings('.col-sm-10').removeClass('col-sm-10').addClass('col-sm-12');
                label.hide();

                var childId = container.attr('id') + '_item_' + prototype.index();
                var toggleButton = settings.toggleButtonGenerator.call(this, childId, collapsed);


                prototype.children('legend')
                    .removeClass('col-sm-2 col-form-label')
                    .addClass('col-sm-12 text-center h5')
                    .prepend(toggleButton)
                    .append(deleteLink)
                    .append(viewLink);
                prototype.children('.col-sm-10')
                    .removeClass('col-sm-10')
                    .addClass('col-sm-12 collapse' + (!collapsed ? ' show' : ''))
                    .attr('id', childId);
            }
            settings.setupListener.call(this, prototype);
        };

        var addEntry = function (container) {
            var index = parseInt(container.attr('data-index'), 10),
                template = container.attr('data-prototype')
                    .replace(/__name__label__/g, settings.blockTitle + (index + 1))
                    .replace(/__name__/g, index);

            var prototype = $(template);

            container.append(prototype);
            setupLinks(prototype, container, false);
            index++;
            container.attr('data-index', index);
            settings.addListener.call(this, container.children(':last-child'), index);
        };

        return this.each(function () {
            var container = $(this);
            var index = container.children('.form-group').length;

            container.attr('data-index', index);
            container.addClass('collection-block');

            (settings.addLink || container.next().find('.collection-add-link'))
                .click(function (e) {
                    addEntry(container);
                    e.preventDefault();
                    return false;
                });

            if (index == 0) {
                addEntry(container);
            } else {
                container.children('.form-group').each(function (index) {
                    $(this).addClass(settings.persistedClass);
                    $(this).children('legend').text(settings.blockTitle + (index + 1));
                    setupLinks($(this), container, true);
                });
            }
        });
    };
})(jQuery);