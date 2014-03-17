TodasCategorias = {
    'templates': {
        'nome': '<div class="input-group"><input type="text" class="form-control" /><span class="input-group-btn"><button class="btn btn-success btn-save-nome"><span class="glyphicon glyphicon-save"></span></button></span></div>'
    }
};

$(document).ready(function() {
    // Categoria
    $('.pai-edit').on('click', function() {
        if ($(this).data('edit') == 'hidden') {
            var pai = $(this).data('pai');

            var $template = $('#categoria-template').clone();
            $template.removeAttr('id');
            $template.removeClass('hidden');

            $(this).html($template);
            $(this).data('edit', 'show');

            $(this).find('.form-control').val(pai);
            $(this).find('.form-control').focus();
        }
    });

    $(document).on('click', '.btn-save-pai', function() {
        var $td = $(this).parents('.pai-edit');
        var cod = $td.data('cod');
        var pai = $td.find('.form-control').val();
        var desc = $td.find('.form-control option:selected').text();

        $.post('/categorias/salvar-categoria', {'cod': cod, 'pai': pai}, function(result, status) {
            $td.html('<strong>' + desc + '</strong>');
            $td.data('pai', result.pai);
            $td.data('edit', 'hidden');
        }, 'json');
    });

    $(document).on('keyup', '.pai-edit .form-control', function(e) {
        if (e.keyCode == 27) {
            var $td = $(this).parents('.pai-edit');
            if ($td.find('.form-control option:selected').val()) {
                var desc = $td.find('.form-control option:selected').text();
                $td.html('<strong>' + desc + '</strong>');
            } else {
                $td.html('');
            }
            $td.data('edit', 'hidden');
        } else if (e.keyCode == 13) {
            $(this).siblings('.input-group-btn').find('.btn-save-pai').trigger('click');
        }
    });

    // Descrição
    $('.nome-edit').on('click', function() {
        if ($(this).data('edit') == 'hidden') {
            var desc = $.trim($(this).text());

            $(this).html(TodasCategorias.templates.nome);
            $(this).data('edit', 'show');
            $(this).find(':input[type=text]').val(desc);
            $(this).find(':input[type=text]').focus();
        }
    });

    $(document).on('click', '.btn-save-nome', function() {
        var $td = $(this).parents('.nome-edit');
        var cod = $td.data('cod');
        var desc = $td.find(':input[type=text]').val();

        $.post('/categorias/salvar-categoria', {'cod': cod, 'nome': desc}, function(result, status) {
            var nome = result.nome;
            if (!$td.data('pai')) {
                nome = '<strong>' + nome + '</strong>';
            }
            $td.html(nome);
            $td.data('edit', 'hidden');
        }, 'json');
    });

    $(document).on('keyup', '.nome-edit :input[type=text]', function(e) {
        if (e.keyCode == 27) {
            var $td = $(this).parents('.nome-edit');
            var nome = $.trim($(this).val());
            if (!$td.data('pai')) {
                nome = '<strong>' + nome + '</strong>';
            }
            $td.html(nome);
            $td.data('edit', 'hidden');
        } else if (e.keyCode == 13) {
            $(this).siblings('.input-group-btn').find('.btn-save-nome').trigger('click');
        }
    });

    // Excluir
    $('.btn-delete').on('click', function() {
        var cod = $(this).data('cod');
        var $that = $(this);

        $.post('/categorias/remover-categoria', {'cod': cod}, function(result, status) {
            $that.parents('tr').remove();
        }, 'json');
    });
});