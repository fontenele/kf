Ponto = {
    'cod': null,
    'funcionario': null,
    'horario1': null,
    'horario2': null,
    'horario3': null,
    'horario4': null,
    'horario5': null,
    'horario6': null,
    'totalHs': null,
    'totalHsExtras': null,
    'init': function() {
        return this;
    },
    'tr2obj': function($tr) {
        var $td = $tr.children('td');
        this.cod = $tr.data('cod');
        this.funcionario = $td.eq(0).text();
        this.horario1 = $td.eq(1).text();
        this.horario2 = $td.eq(2).text();
        this.horario3 = $td.eq(3).text();
        this.horario4 = $td.eq(4).text();
        this.horario5 = $td.eq(5).text();
        this.horario6 = $td.eq(6).text();
        this.totalHs = $td.eq(7).text();
        this.totalHsExtras = $td.eq(8).text();
        return this;
    },
    'obj2tr': function($tr) {
        var $td = $tr.children('td');
        $td.eq(0).text(this.funcionario);
        $td.eq(1).text(this.horario1);
        $td.eq(2).text(this.horario2);
        $td.eq(3).text(this.horario3);
        $td.eq(4).text(this.horario4);
        $td.eq(5).text(this.horario5);
        $td.eq(6).text(this.horario6);
        $td.eq(7).text(this.totalHs);
        $td.eq(8).text(this.totalHsExtras);
    },
    'calcularTotalHs': function() {
        var timer = Timer.init();
        timer.appendDayTime(this.horario1, this.horario2);
        timer.appendDayTime(this.horario3, this.horario4);
        if (timer.hours > 8 || (timer.hours == 8 && timer.minutes > 0)) {
            return '08:00';
        }
        return ("0" + timer.hours).slice(-2) + ':' + ("0" + timer.minutes).slice(-2);
    },
    'calcularTotalHsExtras': function() {
        var timer = Timer.init();
        var hours = 0;
        var minutes = 0;
        timer.appendDayTime(this.horario1, this.horario2);
        timer.appendDayTime(this.horario3, this.horario4);
        if (timer.hours >= 8) {
            hours = timer.hours - 8;
            minutes = timer.minutes;
        }

        var timer = Timer.init();
        timer.appendHours(hours);
        timer.appendMinutes(minutes);
        timer.appendDayTime(this.horario5, this.horario6);
        return ("0" + timer.hours).slice(-2) + ':' + ("0" + timer.minutes).slice(-2);
    }
};

$(document).ready(function() {
    $('#md-ponto').on('shown.bs.modal', function(e) {
        $('#md-ponto input[name=horario1]').focus();
    });

    $('#tb-ponto tbody tr').on('click', function() {
        var obj = Ponto.tr2obj($(this));
        $('#md-ponto .lb-funcionario').text(obj.funcionario);
        $('#md-ponto .lb-data').text(("0" + $('select[name=dia]').val()).slice(-2) + '/' + ("0" + $('.lb-mes').text()).slice(-2) + '/' + $('.lb-ano').text());

        $('#md-ponto input[name=cod]').val($(this).data('cod'));
        $('#md-ponto input[name=horario1]').timepicker('setTime', obj.horario1);
        $('#md-ponto input[name=horario2]').timepicker('setTime', obj.horario2);
        $('#md-ponto input[name=horario3]').timepicker('setTime', obj.horario3);
        $('#md-ponto input[name=horario4]').timepicker('setTime', obj.horario4);
        $('#md-ponto input[name=horario5]').timepicker('setTime', obj.horario5);
        $('#md-ponto input[name=horario6]').timepicker('setTime', obj.horario6);
        $('#md-ponto input[name=total]').val(obj.calcularTotalHs());
        $('#md-ponto input[name=total_extra]').val(obj.calcularTotalHsExtras());

        $('#md-ponto .lb-total-hs').text(obj.calcularTotalHs());
        $('#md-ponto .lb-total-hs-extras').text(obj.calcularTotalHsExtras());
        $('#md-ponto').data('obj', obj);
        $('#md-ponto').data('tr-clicked', $(this));
        $('#md-ponto').modal('show');
        return false;
    });

    $('#md-ponto .btn-save').on('click', function() {
        Global.http.postJson('main/index/salvar-ponto', $('#md-ponto form').serializeArray(), function(result, status) {
            if (result.error) {
                Global.alert.showError(result.message);
                $('#md-ponto').modal('hide');
                return;
            }

            var obj = $('#md-ponto').data('obj');
            var $tr = $('#md-ponto').data('tr-clicked');
            obj.horario1 = $('#md-ponto input[name=horario1]').val();
            obj.horario2 = $('#md-ponto input[name=horario2]').val();
            obj.horario3 = $('#md-ponto input[name=horario3]').val();
            obj.horario4 = $('#md-ponto input[name=horario4]').val();
            obj.horario5 = $('#md-ponto input[name=horario5]').val();
            obj.horario6 = $('#md-ponto input[name=horario6]').val();
            obj.totalHs = $('#md-ponto .lb-total-hs').text();
            obj.totalHsExtras = $('#md-ponto .lb-total-hs-extras').text();
            obj.obj2tr($tr);
            $('#md-ponto').modal('hide');
        });

        return false;
    });

    $('select[name=dia]').on('change', function() {
        $('#fm-ponto').submit();
    });

    $('#md-ponto input[name=horario1],#md-ponto input[name=horario2],#md-ponto input[name=horario3],#md-ponto input[name=horario4],#md-ponto input[name=horario5],#md-ponto input[name=horario6], #md-redefinir-horarios input[name=horario1],#md-redefinir-horarios input[name=horario2],#md-redefinir-horarios input[name=horario3],#md-redefinir-horarios input[name=horario4],#md-redefinir-horarios input[name=horario5],#md-redefinir-horarios input[name=horario6]').on('changeTime.timepicker', function() {
        var $modal = $(this).parents('.modal');
        if ($('#md-ponto').data('obj')) {
            obj = Ponto.init();
            obj.horario1 = $modal.find('input[name=horario1]').val();
            obj.horario2 = $modal.find('input[name=horario2]').val();
            obj.horario3 = $modal.find('input[name=horario3]').val();
            obj.horario4 = $modal.find('input[name=horario4]').val();
            obj.horario5 = $modal.find('input[name=horario5]').val();
            obj.horario6 = $modal.find('input[name=horario6]').val();

            $modal.find('.lb-total-hs').text(obj.calcularTotalHs());
            $modal.find('.lb-total-hs-extras').text(obj.calcularTotalHsExtras());
            $modal.find('input[name=total]').val($modal.find('.lb-total-hs').text());
            $modal.find('input[name=total_extra]').val($modal.find('.lb-total-hs-extras').text());
        }
    });

    $('#btn-redefinir-horarios').on('click', function() {
        if ($('#md-redefinir-horarios .btn-save').data('confirmed')) {
            $('#md-redefinir-horarios .btn-save').removeClass('btn-danger');
            $('#md-redefinir-horarios .btn-save').addClass('btn-primary');
            $('#md-redefinir-horarios .btn-save').text('Redefinir horários');
            $('#md-redefinir-horarios .btn-save').data('confirmed', false);
        }
        $('#md-redefinir-horarios').modal('show');
        return false;
    });

    $('#md-redefinir-horarios .btn-save').on('click', function() {
        if (!$(this).data('confirmed')) {
            $(this).text('Tem certeza disso? Todos os horários desse mês serão redefinidos');
            $(this).data('confirmed', true);
            $(this).removeClass('btn-primary');
            $(this).addClass('btn-danger');
            return false;
        }
        Global.http.postJson('main/index/salvar-folha', $('#md-redefinir-horarios form').serializeArray(), function(result, status) {
            if (result.error) {
                Global.alert.showError(result.message);
                return;
            }
            Global.alert.showSuccess(result.message);
            Global.http.redirect(result.redirect);
        });
        return false;
    });

});