$(document).ready(function() {
    $('a, .btn').tooltip();
    $('.form-control').tooltip({'placement': 'right'});
    
    // Paginator
    $('.pagination li a').on('click', function() {
        if (!$(this).parent().hasClass('active') && !$(this).parent().hasClass('disabled')) {
            var $form = $($(this).data('form'));
            $form.find(':input[name=kf-dg-p]').val($(this).data('page'));
            $form.submit();
        }
        return false;
    });
    
    // Navbar multiple dropdowns
    $('.navbar a.dropdown-toggle').on('click', function(e) {
        if (!$(this).parent().parent().hasClass('nav')) {
            var heightParent = parseInt($(this).parent().parent().css('height').replace('px', '')) / 2;
            var widthParent = parseInt($(this).parent().parent().css('width').replace('px', '')) - 10;
            $(this).parents('.navbar').find('.open > .dropdown-menu:not(:first)').parent().removeClass('open');
            $(this).parent().addClass('open');
            $(this).next().css('top', heightParent + 'px');
            $(this).next().css('left', widthParent + 'px');
            return false;
        }
    });

    // Change theme
    changeTheme = function(name) {
        $('#theme-css').attr('href', hostPath + 'css/bootstrap/bs-' + name + '.min.css');
    };
});