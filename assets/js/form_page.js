require('../css/form_page.scss');

require('./base.js');

require('./plugins/chosen.jquery.js');

require('./plugins/collection.js');
require('./plugins/dependent_fields.js');
require('./plugins/dependent_selects.js');
require('./plugins/localisation_details.js');
require('./plugins/localisation_form.js');
require('./plugins/selectorcreate.js');
require('./plugins/semitic_keyboard.js');
require('./plugins/precision_indicator.js');
require('./plugins/watch_form_dirty.js');

require('./plugins/wysiwyg_editor.js');

(function ($) {
    $('form').on('input', 'textarea.no-return-textarea', function (e) {
        $(this).val($(this).val().replace(/\r?\n|\r/g, ''));
    });

})(jQuery);