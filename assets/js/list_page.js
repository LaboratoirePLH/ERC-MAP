require('../css/list_page.scss');

require('./base.js');

require('datatables.net-bs4')(window, $);
require('datatables.net-buttons/js/buttons.html5')(window, $);
require('datatables.net-buttons-bs4')(window, $);
require('datatables.net-responsive')(window, $);
require('datatables.net-responsive-bs4')(window, $);

function accents_supr(data) {
    return !data ?
        '' :
        typeof data === 'string' ?
            data
                .replace(/\n/g, ' ')
                .replace(/[\u0061\u24D0\uFF41\u1E9A\u00E0\u00E1\u00E2\u1EA7\u1EA5\u1EAB\u1EA9\u00E3\u0101\u0103\u1EB1\u1EAF\u1EB5\u1EB3\u0227\u01E1\u00E4\u01DF\u1EA3\u00E5\u01FB\u01CE\u0201\u0203\u1EA1\u1EAD\u1EB7\u1E01\u0105\u2C65\u0250]/g, 'a')
                .replace(/[\u0065\u24D4\uFF45\u00E8\u00E9\u00EA\u1EC1\u1EBF\u1EC5\u1EC3\u1EBD\u0113\u1E15\u1E17\u0115\u0117\u00EB\u1EBB\u011B\u0205\u0207\u1EB9\u1EC7\u0229\u1E1D\u0119\u1E19\u1E1B\u0247\u025B\u01DD]/g, 'e')
                .replace(/[\u0069\u24D8\uFF49\u00EC\u00ED\u00EE\u0129\u012B\u012D\u00EF\u1E2F\u1EC9\u01D0\u0209\u020B\u1ECB\u012F\u1E2D\u0268\u0131]/g, 'i')
                .replace(/[\u006F\u24DE\uFF4F\u00F2\u00F3\u00F4\u1ED3\u1ED1\u1ED7\u1ED5\u00F5\u1E4D\u022D\u1E4F\u014D\u1E51\u1E53\u014F\u022F\u0231\u00F6\u022B\u1ECF\u0151\u01D2\u020D\u020F\u01A1\u1EDD\u1EDB\u1EE1\u1EDF\u1EE3\u1ECD\u1ED9\u01EB\u01ED\u00F8\u01FF\u0254\uA74B\uA74D\u0275]/g, 'o')
                .replace(/[\u0075\u24E4\uFF55\u00F9\u00FA\u00FB\u0169\u1E79\u016B\u1E7B\u016D\u00FC\u01DC\u01D8\u01D6\u01DA\u1EE7\u016F\u0171\u01D4\u0215\u0217\u01B0\u1EEB\u1EE9\u1EEF\u1EED\u1EF1\u1EE5\u1E73\u0173\u1E77\u1E75\u0289]/g, 'u')
                .replace(/[\u0041\u24B6\uFF21\u00C0\u00C1\u00C2\u1EA6\u1EA4\u1EAA\u1EA8\u00C3\u0100\u0102\u1EB0\u1EAE\u1EB4\u1EB2\u0226\u01E0\u00C4\u01DE\u1EA2\u00C5\u01FA\u01CD\u0200\u0202\u1EA0\u1EAC\u1EB6\u1E00\u0104\u023A\u2C6F]/g, 'A')
                .replace(/[\u0045\u24BA\uFF25\u00C8\u00C9\u00CA\u1EC0\u1EBE\u1EC4\u1EC2\u1EBC\u0112\u1E14\u1E16\u0114\u0116\u00CB\u1EBA\u011A\u0204\u0206\u1EB8\u1EC6\u0228\u1E1C\u0118\u1E18\u1E1A\u0190\u018E]/g, 'E')
                .replace(/[\u0049\u24BE\uFF29\u00CC\u00CD\u00CE\u0128\u012A\u012C\u0130\u00CF\u1E2E\u1EC8\u01CF\u0208\u020A\u1ECA\u012E\u1E2C\u0197]/g, 'I')
                .replace(/[\u004F\u24C4\uFF2F\u00D2\u00D3\u00D4\u1ED2\u1ED0\u1ED6\u1ED4\u00D5\u1E4C\u022C\u1E4E\u014C\u1E50\u1E52\u014E\u022E\u0230\u00D6\u022A\u1ECE\u0150\u01D1\u020C\u020E\u01A0\u1EDC\u1EDA\u1EE0\u1EDE\u1EE2\u1ECC\u1ED8\u01EA\u01EC\u00D8\u01FE\u0186\u019F\uA74A\uA74C]/g, 'O')
                .replace(/[\u0055\u24CA\uFF35\u00D9\u00DA\u00DB\u0168\u1E78\u016A\u1E7A\u016C\u00DC\u01DB\u01D7\u01D5\u01D9\u1EE6\u016E\u0170\u01D3\u0214\u0216\u01AF\u1EEA\u1EE8\u1EEE\u1EEC\u1EF0\u1EE4\u1E72\u0172\u1E76\u1E74\u0244]/g, 'U')
                .replace(/ç/g, 'c')
                .replace(/Ç/g, 'C')
                .replace(/[\u0323\u0323\u030c\u0301\u02be\u02bf\u0323]/g, '') :
            data;
};

jQuery.fn.DataTable.ext.type.search['string'] = function (data) {
    return accents_supr(data);
};
jQuery.fn.DataTable.ext.type.search['html'] = function (data) {
    return accents_supr(data);
};

jQuery.fn.dataTable.ext.type.order['id-pre'] = function (d) {
    const [type, id] = d.split('#');
    var typeNumber;
    switch (type) {
        case 'source':
            typeNumber = 1;
            break;
        case 'attestation':
            typeNumber = 2;
            break;
        default:
            typeNumber = 3;
            break;
    }
    return (typeNumber * 1e10) + parseInt(id, 10);
};


(function ($) {
    $.fn.datatablesTemplateColumn = function (name, visibility) {
        switch (name) {
            case 'date_creation':
                return {
                    data: 'date_creation',
                    visible: visibility,
                    render: function (data, type, row) {
                        if (type === 'display') {
                            return data.display + '<br/><small><acronym title="' + row.createur.value + '">' + row.createur.display + '</acronym></small>';
                        }
                        if (type === 'filter') {
                            return [data.display, row.createur.display, row.createur.value].join(' ; ');
                        }
                        return data.timestamp;
                    }
                };
            case 'date_modification':
                return {
                    data: 'date_modification',
                    visible: visibility,
                    render: function (data, type, row) {
                        if (type === 'display') {
                            return data.display + '<br/><small><acronym title="' + row.editeur.value + '">' + row.editeur.display + '</acronym></small>';
                        }
                        if (type === 'filter') {
                            return [data.display, row.editeur.display, row.editeur.value].join(' ; ');
                        }
                        return data.timestamp;
                    }
                }
        }
    };
    $.fn.setupList = function (listId, tableOptions, searchPlaceholder) {

        // Replace tfoot cell values by search fields
        $('#' + listId + ' tfoot th').each(function () {
            var title = $(this).text();
            var value = $(this).data('value');
            if (title != "") {
                $(this).html('<input type="text" class="w-100 form-control form-control-sm" placeholder="' + searchPlaceholder + ' ' + title + '" />');
                if (value != null) {
                    $(this).children('input').val(value);
                }
            }
        });

        // Setup datatable
        var options = $.extend({
            processing: true, // Displays loader when table is processing
            serverSide: false, // Do local filtering/sorting
            responsive: true,
            autoWidth: true,
            lengthChange: true,
            scrollX: false,
            searching: true,
            ordering: true,
            stateSave: true,
            stateSaveParams: function (settings, data) {
                // Do not store global search
                data.search.search = "";
                // Do not store individual column search
                data.columns = data.columns.map(c => {
                    c.search.search = "";
                    return c;
                });
            }
        }, tableOptions);
        var tableRef = $('#' + listId).DataTable(options);

        // Remove class from page length selector
        $('#' + listId + '_wrapper select').removeClass('custom-select');

        // Setup events on column filters
        tableRef.columns().every(function () {
            var me = this;
            $('input', this.footer()).on('keyup change', function () {
                if (me.search() !== this.value) {
                    me.search(
                        // Remove accented characters from search string
                        jQuery.fn.DataTable.ext.type.search.string(this.value)
                    ).draw();
                }
            })
            $('input', this.footer()).each(function () {
                if ($(this).val() != "") {
                    $(this).trigger('change');
                }
            })
        });

        // Setup event for global search
        $('input[type=search]').on('keyup change', function () {
            tableRef.search(
                // Remove accented characters from search string
                jQuery.fn.DataTable.ext.type.search.string(this.value)
            ).draw();
        })

        // Wait for data being laoded before adding table buttons
        tableRef.on('init', () => {
            var target = $('.table-buttons').find('.help-btn');
            if(target){
                tableRef.buttons().container().insertBefore(target);
            } else {
                tableRef.buttons().container().appendTo('.table-buttons');
            }
        })

        // Setup event for clear filter button
        $('.clear-filter-button').on('click', function (e) {
            e.preventDefault();
            tableRef.columns().every(function () {
                $('input', this.footer()).val("").trigger('change');
            });
        })

        // Setup event for delete buttons
        $('#' + listId).on('click', 'button.delete-button', function () {
            var modal = $($(this).attr('data-target'));
            var rowId = $(this).attr('data-id');
            if (modal) {
                modal.find('form').attr('action', modal.find('form').attr('action').replace('__ID__', rowId));
            }
        })

        // Enable tooltips on table redraw
        tableRef.on('draw', () => {
            $('#' + listId + ' [data-toggle="tooltip"]').tooltip();
        })
    };

    $.fn.createListButton = function (tag, color, icon, text, href) {
        var btn = $(document.createElement(tag))
            .addClass('btn btn-sm btn-block my-1 btn-' + color)
            .text(text)
            .prepend($(document.createElement('i')).addClass("fas fa-fw fa-" + icon));

        if (tag === 'a') {
            btn.attr('href', href);
        } else {
            btn.attr('type', 'button')
        }
        return btn;
    }

    $.fn.createDeleteButton = function (text, rowId) {
        var btn = $.fn.createListButton('button', 'danger', 'trash', text);
        btn.addClass('delete-button').attr('data-id', rowId).attr('data-toggle', 'modal').attr('data-target', '#confirm-deletion-modal');
        return btn;
    }
})(jQuery);