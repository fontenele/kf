$(document).ready(function() {
    $('.nivel').on('click', function() {
        var nivel = $(this).data('nivel');
        if (nivel == $(this).siblings(':input').val()) {
            $(this).parent().find('.nivel').removeClass('glyphicon-star');
            $(this).parent().find('.nivel').addClass('glyphicon-star-empty');
            $(this).siblings(':input').val('');
        } else {
            $(this).parent().find('.nivel').removeClass('glyphicon-star');
            $(this).parent().find('.nivel').addClass('glyphicon-star-empty');
            $.each($(this).parent().find('.nivel'), function(i, item) {
                var _nivel = i + 1;
                $(item).removeClass('glyphicon-star-empty');
                $(item).addClass('glyphicon-star');
                if (_nivel == nivel) {
                    return false;
                }
            });
            $(this).siblings(':input').val(nivel);
        }
    });
});