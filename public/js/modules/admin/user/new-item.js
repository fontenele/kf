$(document).ready(function() {
    $('#fm-user').on('submit', function() {
        if($(':input[name=password]').val() != $(':input[name=re-password]').val()) {
            Global.alert.showError('As senha não são iguais');
            return false;
        }
    });
});