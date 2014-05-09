$(document).ready(function() {
    $("#menu-tree").jstree({
        "core": {
            //"animation": 0,
            "check_callback": true,
            'expand_selected_onload': true,
            'themes': {
                'responsive': false,
                'variant': 'small'
                        //'dots': true
            }
        },
        'types': {
            'default': {
                'icon': 'glyphicon glyphicon-th glyphicon-tree-use'
            }
        },
        "plugins": [
            "search", "state", "types", "wholerow"
        ]
    });

    $("#block-menu-tree").bind('click.jstree', function(e, data) {
        $('#btn-menu-new-sibling').removeClass('disabled');
        $('#btn-menu-new-sibling').removeAttr('disabled');
        $('#btn-menu-rename').removeClass('disabled');
        $('#btn-menu-rename').removeAttr('disabled');
        $('#btn-menu-delete').removeClass('disabled');
        $('#btn-menu-delete').removeAttr('disabled');
        $('#btn-menu-new-child').removeClass('disabled');
        $('#btn-menu-new-child').removeAttr('disabled');
        
        if ($(e.target).attr('id') === 'block-menu-tree-aux' || $(e.target).hasClass('btn') || $(e.target).hasClass('jstree') || $(e.target).attr('id') === 'root-item') {
            
            if ($(e.target).attr('id') === 'block-menu-tree-aux' || $(e.target).hasClass('btn') || $(e.target).hasClass('jstree')) {
                $('#btn-menu-new-child').addClass('disabled');
                $('#btn-menu-new-child').attr('disabled', 'disabled');
                var ref = $('#menu-tree').jstree(true);
                ref.deselect_all(true);
            }
            
            $('#block-form-info').html('<br /><br /><div class="text-center"><div class="btn btn-default disabled text-center text-muted">Selecione um item</div></div>');
            $('#btn-menu-new-sibling').addClass('disabled');
            $('#btn-menu-new-sibling').attr('disabled', 'disabled');
            $('#btn-menu-rename').addClass('disabled');
            $('#btn-menu-rename').attr('disabled', 'disabled');
            $('#btn-menu-delete').addClass('disabled');
            $('#btn-menu-delete').attr('disabled', 'disabled');
        } else if($(e.target).hasClass('jstree-anchor')) {
            $('#block-form-info').html('ITEM SELECIONADO!');
        }
    });

    $('#btn-menu-new-child').on('click', function() {
        var ref = $('#menu-tree').jstree(true);
        var sel = ref.get_selected();

        if (!sel.length) {
            return false;
        }

        sel = sel[0];
        sel = ref.create_node(sel, {"type": "file", 'text': 'Filho de ' + ref.get_text(sel), 'icon': 'glyphicon glyphicon-folder-open glyphicon-tree-use'});
        if (sel) {
            ref.edit(sel);
        }
    });

    $('#btn-menu-new-sibling').on('click', function() {
        var ref = $('#menu-tree').jstree(true);
        var sel = ref.get_selected(true);

        if (!sel.length) {
            return false;
        }

        sel = sel[0];
        $('#menu-tree').jstree('create_node', ref.get_parent(sel.id), {'icon': 'glyphicon glyphicon-folder-open glyphicon-tree-use', 'text': 'Filho de ' + ref.get_text(ref.get_parent(sel.id))}, 'last');
    });

    $('#btn-menu-rename').on('click', function() {
        var ref = $('#menu-tree').jstree(true);
        var sel = ref.get_selected();

        if (!sel.length) {
            return false;
        }

        sel = sel[0];
        ref.edit(sel);
    });

    $('#btn-menu-delete').on('click', function() {
        var ref = $('#menu-tree').jstree(true);
        var sel = ref.get_selected();

        if (!sel.length) {
            return false;
        }

        ref.delete_node(sel);
    });
});