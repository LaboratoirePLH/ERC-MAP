(function ($) {
    $.fn.tree = function (data) {
        var GROUP_ID = 0;

        function buildIcon(name) {
            return $('<i>').addClass('fas fa-fw fa-' + name + ' mr-2');
        }

        function buildBadge(value) {
            return $('<span>').addClass('badge badge-primary badge-pill float-right mr-3').text(value);
        }

        function buildLi(item, level) {
            // Create <li>
            var li = $('<li>').addClass('list-group-item pr-0');

            // Add link or text
            if (item.link !== undefined) {
                li.append($('<a>').attr('href', item.link).attr('target', '_blank').text(item.text));
            } else {
                li.text(item.text);
            }

            // Add badge
            li.append(buildBadge(item.badge))
            li.prepend(buildIcon(item.icon));

            // Indent left

            // If item has children, prepare for collapsible
            if (item.children !== undefined && item.children.length) {
                var group = ++GROUP_ID;

                var collapse_icon = $('<span>')
                    .addClass('collapsible-icon cursor-pointer text-muted mr-3')
                    .attr('data-toggle', 'collapse')
                    .attr('data-target', '#corpus-state-group-' + group)
                    .append(
                        buildIcon('plus').addClass('d-inline'),
                        buildIcon('minus').addClass('d-none')
                    );
                li.prepend(collapse_icon);

                var ul = $('<ul>').addClass('collapse list-group list-group-flush border-left border-color-map-yellow mt-2 ml-1 pl-2').prop('id', 'corpus-state-group-' + group);

                item.children.forEach(c => {
                    ul.append(buildLi(c, level + 1));
                });
                li.append(ul);
            }
            return li;
        }

        return this.each(function () {
            var root = $(this);
            data.forEach(d => {
                root.append(buildLi(d, 1));
            })

            root.find('.collapse').on('show.bs.collapse', function () {
                var id = $(this).prop('id');
                var icons = $('span.collapsible-icon[data-target="#' + id + '"]').children();
                $(icons.get(0)).removeClass('d-inline').addClass('d-none');
                $(icons.get(1)).removeClass('d-none').addClass('d-inline');
            });
            root.find('.collapse').on('hide.bs.collapse', function () {
                var id = $(this).prop('id');
                var icons = $('span.collapsible-icon[data-target="#' + id + '"]').children();
                $(icons.get(0)).removeClass('d-none').addClass('d-inline');
                $(icons.get(1)).removeClass('d-inline').addClass('d-none');
            });
        });

    }
})(jQuery);