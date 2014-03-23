$(document).ready(function() {
    // Paginação
    $('.pagination li a').on('click', function() {
        if(!$(this).parent().hasClass('active') && !$(this).parent().hasClass('disabled')) {
            var $form = $($(this).data('form'));
            $form.find(':input[name=kf-dg-p]').val($(this).data('page'));
            $form.submit();
        }
        return false;
    });
});