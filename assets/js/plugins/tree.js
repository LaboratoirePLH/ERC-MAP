(function ($) {
    $.fn.tree = function (data, filterPlaceholder) {
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
                li.append($('<a>').attr('href', item.link).attr('target', '_blank').html(item.text));
            } else {
                li.html(item.text);
            }

            // Add badge
            li.append(buildBadge(item.badge))
            li.prepend(buildIcon(item.icon));

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

        function filter(rootNode, filterValue) {
            if (filterValue === "") {
                buildTree(rootNode, data);
            } else {
                var filterData = function (dataArray, value) {
                    var copy = JSON.parse(JSON.stringify(dataArray));
                    return copy.filter(function (node) {
                        if (node.children && node.children.length > 0) {
                            // Not leaf
                            node.children = filterData(node.children, value);

                            if (node.children.length === 0) {
                                // If no children left, do not display this node
                                return false;
                            } else {
                                // Recompute badge value
                                node.badge = node.children.reduce((t, c) => t + c.badge, 0);
                            }
                        } else {
                            // Leaf
                            const nodeText = $('<div>').html(node.text).text();
                            return nodeText.toLowerCase().includes(value.toLowerCase());
                        }
                        return true;
                    });
                }
                // Filter the data according to the value
                var filteredData = filterData(data, filterValue);
                buildTree(rootNode, filteredData);
            }
        }

        function buildTree(rootNode, dataArray) {
            rootNode.children().not('.tree-filter').remove();

            dataArray.forEach(d => {
                rootNode.append(buildLi(d, 1));
            });

            rootNode.find('.collapse').on('show.bs.collapse', function () {
                var id = $(this).prop('id');
                var icons = $('span.collapsible-icon[data-target="#' + id + '"]').children();
                $(icons.get(0)).removeClass('d-inline').addClass('d-none');
                $(icons.get(1)).removeClass('d-none').addClass('d-inline');
            });
            rootNode.find('.collapse').on('hide.bs.collapse', function () {
                var id = $(this).prop('id');
                var icons = $('span.collapsible-icon[data-target="#' + id + '"]').children();
                $(icons.get(0)).removeClass('d-none').addClass('d-inline');
                $(icons.get(1)).removeClass('d-inline').addClass('d-none');
            });
        }

        return this.each(function () {
            var root = $(this);

            // Search input
            const searchField = '<div class="input-group">'
                + '<div class="input-group-prepend">'
                + '<span class="input-group-text" id="filter-icon-addon">'
                + buildIcon('filter').removeClass('mr-2').get(0).outerHTML
                + '</span>'
                + '</div>'
                + '<input type="text" id="corpus-state-filter" class="form-control" placeholder="' + filterPlaceholder + '" aria-describedby="filter-icon-addon">'
                + '</div>';
            var searchLi = $('<li>').addClass('list-group-item list-group-item-dark tree-filter').append(searchField);
            root.append(searchLi);

            buildTree(root, data);

            root.find('#corpus-state-filter').on('input', e => {
                filter(root, e.target.value);
            });
        });

    }
})(jQuery);