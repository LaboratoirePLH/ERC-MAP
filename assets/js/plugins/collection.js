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
            autoAdd: true,
            persistedClass: "collection-persisted-item",
        }, options);

        var setupLinks = function (prototype, container, collapsed) {
            var me = this;
            var deleteLink = settings.deleteLinkGenerator.call(this, prototype);
            if (deleteLink !== "" && $(deleteLink).prop('tagName') == 'A') {
                deleteLink.click(function (e) {
                    e.preventDefault();
                    if (settings.confirmationModal !== null && settings.confirmationModal.length) {
                        settings.confirmationModal.off('show.bs.modal');
                        settings.confirmationModal.one('show.bs.modal', function () {
                            settings.confirmationModal.find('a.btn').on('click', function () {
                                settings.confirmationModal.find('a.btn').off('click');
                            });
                            settings.confirmationModal.find('.modal-accept-button').on('click', function () {
                                prototype.remove();
                                settings.deleteListener.call(me, prototype);
                                settings.confirmationModal.modal('hide');
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
                    .append(viewLink)
                    .append(deleteLink);
                prototype.children('.col-sm-10')
                    .removeClass('col-sm-10')
                    .addClass('col-sm-12 collapse' + (!collapsed ? ' show' : ''))
                    .attr('id', childId);
            }
            settings.setupListener.call(this, prototype);
        };

        var replacePlaceholders = function (prototype, label, name) {
            // Check if string contains "data-prototype" :
            if (prototype.match(/data-prototype="[^"]+"/) === null) {
                // If it doesn't, do it the simple way, global replace
                return prototype.replace(/__name__label__/g, label)
                    .replace(/__name__/g, name);
            }
            // Else :
            else {
                // Replace the first __name__label__
                prototype = prototype.replace(/__name__label__/, label);
                // Then look for the field name with brackets, e.g. element[theonymesImplicites][__name__]
                // (it's the first occurence of [__name__] after replacing the label)
                // Use a REGEX to get the part of the field before "[__name__]",
                // because we will also need to replace those in the sub-prototype
                var matches = prototype.match(/"([^"]+\[__name__\])[^"]+"/);
                if (matches === null || matches.length < 2) {
                    throw "Regex Error";
                }
                var strToReplace1 = matches[1];
                // Then deduce the field name with underscores, e.g. element_theonymesImplicites___name__
                var strToReplace2 = matches[1].replace(/\[/g, '_').replace(/\]/g, '');
                // In those, replace the __name__
                var replacement1 = strToReplace1.replace(/__name__/g, name),
                    replacement2 = strToReplace2.replace(/__name__/g, name);
                // Then replace the corresponding string in the prototype
                return prototype.replace(new RegExp(strToReplace1.replace(/[[\]]/g, '\\$&'), 'g'), replacement1)
                    .replace(new RegExp(strToReplace2, 'g'), replacement2);
            }
        }

        var addEntry = function (container) {
            var index = parseInt(container.attr('data-index'), 10),
                template = container.attr('data-prototype');

            template = replacePlaceholders(template, settings.blockTitle + (index + 1), index);
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
                if (settings.autoAdd === true) {
                    addEntry(container);
                }
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