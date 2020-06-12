(function ($) {
    $.fn.localisationDetails = function (settings) {

        if (!settings.hasOwnProperty('dataUrl')) {
            throw "Data URL was not specified";
        }

        if (!settings.hasOwnProperty('locale')) {
            throw "Locale was not specified";
        }

        if (!settings.hasOwnProperty('coordinatesButton')) {
            throw "Coordinates button template was not specified";
        }

        if (!settings.hasOwnProperty('labels')) {
            throw "Labels were not specified";
        }


        return this.each(function () {
            var displayInfos = function(target){
                $(target).siblings('.localisation_selection_info').remove();

                const localisationId = target.value;
                if(!localisationId){ return; }

                const url = settings.dataUrl.replace(encodeURIComponent('##ID##'), localisationId);


                var localisationInfoBox = $('<div class="alert alert-info localisation_selection_info p-0" role="alert"></div>');
                $(target).parent().append(localisationInfoBox);

                localisationInfoBox.append($('<div class="spinner-border" role="status"></div>'));
                $.getJSON(url).done(({data}) => {
                    localisationInfoBox.children('.spinner-border').remove();
                    var table = $('<table class="table table-sm table-striped mb-0"><tbody></tbody></table>');

                    var tableRows = [];
                    const nameField = "nom" + settings.locale;
                    if (data.topographies.length > 0) {
                        tableRows.push([
                            settings.labels.topographies, data.topographies.map(t => t[nameField]).join(', ')
                        ]);
                    }
                    if (data.fonctions.length > 0) {
                        tableRows.push([
                            settings.labels.fonctions, data.fonctions.map(t => t[nameField]).join(', ')
                        ]);
                    }
                    if (data.longitude !== null && data.latitude !== null) {
                        var button = settings.coordinatesButton.replace('COORDINATES_URL', encodeURIComponent(data.latitude + ',' + data.longitude)).replace('COORDINATES', data.latitude + ', ' + data.longitude);
                        tableRows.push([settings.labels.coordonnees, button]);
                    }
                    if (!!data.commentaireFr || !!data.commentaireEn) {
                        var commentaire = settings.locale.toLowerCase() == 'fr' ? (data.commentaireFr + '<hr/><small>' + data.commentaireEn + '</small>') : (data.commentaireEn + '<hr/><small>' + data.commentaireFr + '</small>');
                        tableRows.push([settings.labels.commentaires, commentaire])
                    }
                    tableRows.push([settings.labels.reel, data.reel ? settings.labels.oui : settings.labels.non])
                    table.find('tbody').append(... tableRows.map(([label, value]) => $('<tr>').append($('<th scope="row"></th>').text(label), $('<td></td>').html(value))));
                    localisationInfoBox.append(table);
                }).fail(() => {
                    $(target).siblings('.localisation_selection_info').remove();
                })
            };
            $(this).on('change', function(e){
                displayInfos(e.target);
            })
            if($(this).val() != ""){
                displayInfos(this);
            }
        });

    }
})(jQuery);