TodasPerguntas = {
    'templates': {
        'descricao': '<div class="input-group"><input type="text" class="form-control" /><span class="input-group-btn"><button class="btn btn-success btn-save-descricao"><span class="glyphicon glyphicon-save"></span></button></span></div>',
        'alternativa': '<div class="input-group"><input type="text" class="form-control" /><span class="input-group-btn"><button class="btn btn-success btn-save-alternativa"><span class="glyphicon glyphicon-save"></span></button></span></div>'
    }
};

$(document).ready(function() {

    // Categoria
    $('.categoria-edit').on('click', function() {
        if ($(this).data('edit') == 'hidden') {
            var categoria = $(this).data('categoria');

            var $template = $('#categoria-template').clone();
            $template.removeAttr('id');
            $template.removeClass('hidden');

            $(this).html($template);
            $(this).data('edit', 'show');

            $(this).find('.form-control').val(categoria);
            $(this).find('.form-control').focus();
        }
    });

    $(document).on('click', '.btn-save-categoria', function() {
        var $td = $(this).parents('.categoria-edit');
        var cod = $td.data('cod');
        var categoria = $td.find('.form-control').val();
        var desc = $td.find('.form-control option:selected').text();

        $.post('/perguntas/salvar-pergunta', {'cod': cod, 'categoria': categoria}, function(result, status) {
            $td.html(desc);
            $td.data('categoria', result.categoria);
            $td.data('edit', 'hidden');
        }, 'json');
    });

    $(document).on('keyup', '.categoria-edit .form-control', function(e) {
        if (e.keyCode == 27) {
            var $td = $(this).parents('.categoria-edit');
            if ($td.find('.form-control option:selected').val()) {
                var desc = $td.find('.form-control option:selected').text();
                $td.html(desc);
            } else {
                $td.html('');
            }
            $td.data('edit', 'hidden');
        } else if (e.keyCode == 13) {
            $(this).siblings('.input-group-btn').find('.btn-save-categoria').trigger('click');
        }
    });

    // Descrição
    $(document).on('click', '.btn-save-descricao', function() {
        var $td = $(this).parents('.descricao-edit');
        var cod = $td.data('cod');
        var desc = $td.find(':input[type=text]').val();

        $.post('/perguntas/salvar-pergunta', {'cod': cod, 'descricao': desc}, function(result, status) {
            $td.html(result.descricao);
            $td.data('edit', 'hidden');
        }, 'json');
    });

    $(document).on('keyup', '.descricao-edit :input[type=text]', function(e) {
        if (e.keyCode == 27) {
            var $td = $(this).parents('.descricao-edit');
            $td.html($(this).val());
            $td.data('edit', 'hidden');
        } else if (e.keyCode == 13) {
            $(this).siblings('.input-group-btn').find('.btn-save-descricao').trigger('click');
        }
    });

    $('.descricao-edit').on('click', function() {
        if ($(this).data('edit') == 'hidden') {
            var desc = $(this).text();

            $(this).html(TodasPerguntas.templates.descricao);
            $(this).data('edit', 'show');
            $(this).find(':input[type=text]').val(desc);
            $(this).find(':input[type=text]').focus();
        }
    });

    // Alternativa
    $(document).on('click', '.btn-save-alternativa', function() {
        var $td = $(this).parents('.alternativa-edit');
        var cod = $td.data('cod');
        var desc = $td.find(':input[type=text]').val();

        $.post('/perguntas/salvar-alternativa', {'cod': cod, 'descricao': desc}, function(result, status) {
            $td.html(result.descricao);
            $td.data('edit', 'hidden');
        }, 'json');
    });

    $(document).on('keyup', '.alternativa-edit :input[type=text]', function(e) {
        if (e.keyCode == 27) {
            var $td = $(this).parents('.alternativa-edit');
            $td.html($(this).val());
            $td.data('edit', 'hidden');
        } else if (e.keyCode == 13) {
            $(this).siblings('.input-group-btn').find('.btn-save-alternativa').trigger('click');
        }
    });

    $('.alternativa-edit').on('click', function() {
        if ($(this).data('edit') == 'hidden') {
            var desc = $(this).text();

            $(this).html(TodasPerguntas.templates.alternativa);
            $(this).data('edit', 'show');
            $(this).find(':input[type=text]').val(desc);
            $(this).find(':input[type=text]').focus();
        }
    });

    // Alterar status
    $('.btn-change-status').on('click', function() {
        var cod = $(this).data('cod');
        var status = $(this).data('status') == '2' ? '1' : '2';
        var $that = $(this);

        $.post('/perguntas/salvar-pergunta', {'cod': cod, 'status': status}, function(result, status) {
            $that.data('status', result.status);
            if (result.status == '2') {
                $that.removeClass('btn-danger');
                $that.addClass('btn-success');
                $that.children().removeClass('glyphicon-thumbs-down');
                $that.children().addClass('glyphicon-thumbs-up');
                $that.parents('tr').removeClass('success');
            } else {
                $that.removeClass('btn-success');
                $that.addClass('btn-danger');
                $that.children().removeClass('glyphicon-thumbs-up');
                $that.children().addClass('glyphicon-thumbs-down');
                $that.parents('tr').addClass('success');
            }
        }, 'json');
    });

    $('.nivel').on('click', function() {
        var cod = $(this).data('cod');
        var nivel = $(this).data('nivel');
        if (nivel == $(this).siblings(':input').val()) {
            $(this).parent().find('.nivel').removeClass('glyphicon-star');
            $(this).parent().find('.nivel').addClass('glyphicon-star-empty');
            $(this).siblings(':input').val('');
            nivel = null;
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

        $.post('/perguntas/salvar-pergunta', {'cod': cod, 'nivel': nivel}, function(result, status) {

        });
    });
});