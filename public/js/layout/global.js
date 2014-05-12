Global = {
    
    
};

Global.alert = {
    '_tplAlert': {
        'success': '<div class="alert alert-success alert-dismissable col-xs-12">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                        '<strong>Sucesso!</strong> {$msg}' +
                    '</div>',
        'error':    '<div class="alert alert-danger alert-dismissable col-xs-12">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                        '<strong>Erro!</strong> {$msg}' +
                    '</div>'
    },
    'show': function(type, msg) {
        switch(type) {
            case 'success':
                $('body > .container > .row').prepend(Global.alert._tplAlert.success.replace('{$msg}', msg));
                break;
            case 'error':
                $('body > .container > .row').prepend(Global.alert._tplAlert.error.replace('{$msg}', msg));
                break;
        }
    },
    'showSuccess': function(msg) {
        Global.alert.show('success', msg);
    },
    'showError': function(msg) {
        Global.alert.show('error', msg);
    }
};

Global.html = {
    '_tpl': {
        'form': {
            'item': '<div class="form-group"><label class="control-label col-xs-3">{$label}</label><div class="col-xs-8">{$input}</div></div>',
            'input': '<input type="text" name="{$name}" class="form-control" />',
            'select': '<select class="form-control" name="{$name}" >{$options}</select>',
            'submit': '<button class="btn {$class}" id="{$id}">{$label}</button>'
        }
    },
    'getTplFormItem': function(label, component) {
        return Global.html._tpl.form.item.replace('{$label}', label).replace('{$input}', component);
    },
    'getTplFormInput': function(name) {
        return Global.html._tpl.form.input.replace('{$name}', name);
    },
    'getTplFormSelect': function(name, options) {
        return Global.html._tpl.form.select.replace('{$name}', name).replace('{$options}', options);
    },
    'getTplFormSubmit': function(id, label, cssClass) {
        return Global.html._tpl.form.submit.replace('{$class}', cssClass).replace('{$id}', id).replace('{$label}', label);
    }
};