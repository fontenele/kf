(function($, window, document, undefined) {
    'use strict';
    $('[data-component-datetimepicker]').each(function(i, item) {
        var options = {
            'language': 'pt-BR'
        };
        if($(item).data('default-date')) {
            options.defaultDate = $(item).data('default-date');
        }
        if($(item).data('picktime') === false) {
            options.pickTime = false;
        }
        if($(item).data('pickdate') === false) {
            options.pickDate = false;
        }
        var name = '';
        if($(item).data('name')) {
            name = 'name="' + $(item).data('name') + '"';
        }
        var tabindex = ''
        if($(item).data('tabindex')) {
            tabindex = 'tabindex="' + $(item).data('tabindex') + '"';
        }
        $(item).addClass('bootstrap-datetimepicker input-group date');
        $('<input ' + name + ' ' + tabindex + ' type="text" data-default-date="' + options.defaultDate + '" class="form-control input-small" />').appendTo($(item));
        $('<span  class="input-group-addon add-on"><i class="glyphicon glyphicon-calendar"></i></span>').appendTo($(item));
        $(item).datetimepicker(options);
    });
})(jQuery, window, document);