NewItem = {
    '_tpl': {
        'itemIelected': {
            'none': '<br /><br /><div class="text-center"><div class="btn btn-default disabled text-center text-muted">Selecione um item</div></div>',
            'selected': ''
        }
    },
    'init': function() {
        NewItem.configTree();
        NewItem.configButtons();
        NewItem.configForm();
    },
    'configTree': function() {
        $("#menu-tree").jstree({
            "core": {
                "check_callback": true,
                'expand_selected_onload': true,
                'themes': {
                    'responsive': false,
                    'variant': 'small'
                }
            },
            'types': {
                'default': {
                    'icon': 'glyphicon glyphicon-th glyphicon-tree-use'
                }
            },
            "plugins": [
                "search", "types", "wholerow", "state", "json_data", "HTML_data"
            ]
        });

        NewItem._tpl.itemIelected.selected = '<fieldset>' +
                Global.html.getTplFormItem('Nome', Global.html.getTplFormInput('name')) +
                Global.html.getTplFormItem('Link', Global.html.getTplFormInput('link')) +
                Global.html.getTplFormItem('', Global.html.getTplFormSubmit('btn-save-menu-item', 'Salvar', 'btn-primary')) +
                Global.html.getTplFormItem('', Global.html.getTplFormSubmit('btn-menu-delete', 'Excluir', 'btn-danger')) +
                '</fieldset>';

        $("#block-menu-tree").bind('click.jstree', function(e, data) {
            /*var ref = $('#menu-tree').jstree(true);
             var sel = ref.get_selected();
             var opened = ref.get_node(sel[0]).state.opened;
             console.log(ref.get_node(sel[0]));
             ref.set_icon(sel[0], opened ? 'glyphicon glyphicon-folder-open' : 'glyphicon glyphicon-folder-close');*/

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

                $('#block-form-info').html(NewItem._tpl.itemIelected.none);
                $('#btn-menu-new-sibling').addClass('disabled');
                $('#btn-menu-new-sibling').attr('disabled', 'disabled');
                $('#btn-menu-rename').addClass('disabled');
                $('#btn-menu-rename').attr('disabled', 'disabled');
                $('#btn-menu-delete').addClass('disabled');
                $('#btn-menu-delete').attr('disabled', 'disabled');
            } else if ($(e.target).hasClass('jstree-anchor')) {
                $('#block-form-info').html(NewItem._tpl.itemIelected.selected);
                var ref = $('#menu-tree').jstree(true);
                $('#block-form-info :input[name=name]').val(ref.get_selected(true)[0].text);
                $('#block-form-info :input[name=link]').val(ref.get_node(ref.get_selected()[0], true).data('link'));
            }
        });
    },
    'configButtons': function() {
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
    },
    'configForm': function() {
        $('#fm-menu').on('submit', function() {
            var ref = $('#menu-tree').jstree(true);

            $.post(hostPath + 'admin/menu/new-item', {'cod': $(':input[name=cod]').val(), 'codename': $(':input[name=codename]').val(),'name': $(':input[name=name]').val(), 'menu': ref.get_json('root')}, function(result, status) {
                if (result.success) {
                    Global.alert.showSuccess(result.message);
                    window.location = hostPath + result.redirect;
                } else {
                    Global.alert.showError(result.message);
                }
            }, 'json');

            return false;
        });

        $('body').on('click', '#btn-save-menu-item', function(e) {
            var ref = $('#menu-tree').jstree(true);
            var sel = ref.get_selected();

            if (!sel.length) {
                return false;
            }

            sel = sel[0];
            ref.set_text(sel, $('#block-form-info :input[name=name]').val());
            ref.get_node(sel).data.link = $('#block-form-info :input[name=link]').val();
            
            return false;
        });
    }
};

$(document).ready(function() {
    NewItem.init();

    // Deselect all items from tree
    setTimeout(function() {
        var ref = $('#menu-tree').jstree(true);
        ref.deselect_all(true);
    }, 100);
});