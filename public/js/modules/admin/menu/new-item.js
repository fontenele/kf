$(document).ready(function() {
    $("#menu-tree").jstree({
        "core": {
            //"animation": 0,
            "check_callback": true,
            'themes': {
                'responsive': false,
                'variant': 'small'
            }
            //"themes": {"stripes": true},
            /*'data': {
             'url': function(node) {
             return node.id === '#' ?
             'ajax_demo_roots.json' : 'ajax_demo_children.json';
             },
             'data': function(node) {
             return {'id': node.id};
             }
             }*/
        },
        'types': {
            'default': {
                //'icon': '../../css/bootstrap/jsTree/themes/default/small-tiles.png'
            }
            /*'#': {
             'valid_children': ['root']
             },
             'root': {
             'icon': 'teste.png'
             }*/
        },
        "plugins": [
            "contextmenu", "dnd", "search",
            "state", "types", "wholerow"
        ]
    });

    $("#block-menu-tree").on('changed.jstree', function(e, data) {
        console.log(e, data);
    });

    /*$("#block-menu-tree").jstree({
     "plugins": ["themes", "html_data", "ui", "crrm", "hotkeys", "checkbox"],
     "core": {
     "animation": 100,
     "initially_open": ["phtml_1"]
     },
     "themes": {
     "theme": "classic",
     "url": hostPath + "css/bootstrap/jsTree/themes/classic/style.css"
     }
     });*/

    $('body').on('click', '.btn-add-item', function() {
        var $item = $('.snippets > .list-group-item').clone();

        $(this).parent().parent().after($item);

        $item.find('.btn-edit-item').tooltip({'title': 'Editar Item'});
        $item.find('.btn-add-item').tooltip({'title': 'Adicionar um filho'});

        $item.data('layer', $(this).parents('.list-group-item').data('layer') + 1);
        $item.find('label').css('paddingLeft', 20 * $(this).parents('.list-group-item').data('layer'));
        $item.find('label').text('Filho de ' + $.trim($(this).parent().siblings('label').text()));

        return false;
    });

    $('body').on('click', '.btn-del-item', function() {
        $('#md-del').modal('show');
        return false;
    });

    $('body').on('click', '.btn-edit-item', function() {
        $('#md-edit :input[name=name]').val($.trim($(this).parent().siblings('label').text()));
        $('#md-edit').data('target', $(this).parent().siblings('label'));
        $('#md-edit').modal('show');
        return false;
    });

    $('body').on('click', '#md-edit .btn-primary', function() {
        $('#md-edit').data('target').text($.trim($('#md-edit :input[name=name]').val()));
        $('#md-edit').modal('hide');
        return false;
    });


});