(function($, window, document, undefined) {
    'use strict';
    $('[data-component-timepicker]').each(function(i, item) {
        var defaultTime = '00:00';
        if($(item).data('default-time')) {
            defaultTime = $(item).data('default-time');
        }
        var name = '';
        if($(item).data('name')) {
            name = 'name="' + $(item).data('name') + '"';
        }
        var tabindex = ''
        if($(item).data('tabindex')) {
            tabindex = 'tabindex="' + $(item).data('tabindex') + '"';
        }
        $(item).addClass('bootstrap-timepicker input-group');
        $('<input ' + name + ' ' + tabindex + ' type="text" data-default-time="' + defaultTime + '" class="form-control dtinpt timepicker input-small" />').appendTo($(item));
        $('<span  class="input-group-addon add-on"><i class="glyphicon glyphicon-time"></i></span>').appendTo($(item));
        $(item).children('.timepicker').timepicker('setTime', defaultTime);
    });
})(jQuery, window, document);