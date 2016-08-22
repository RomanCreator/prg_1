/**
 * Created by roman on 22.08.16.
 */
var OrderWindow = function (name, phone, typeResearchesOptions) {
    this.template = '<div class="modal fade" id="callOrder" tabindex="-1" role="dialog">'+
                        '<div class="modal-dialog modal-sm order-window" role="document">'+
                            '<div class="modal-content">'+
                                '<form method="post" action="/callback_order">'+
                                    '<div class="modal-header">'+
                                        '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><img src="/img/icon_window_close.png"</button>'+
                                    '</div>'+
                                    '<div class="modal-body">'+
                                        '<div class="order-window__label">Запишитесь по телефону</div>'+
                                        '<div class="order-window__promophone">8 800 888-00-00</div>'+
                                        '<div class="order-window__label">или отправьте заявку online:</div>'+
                                        '<div class="form-group">'+
                                            '<input type="text" name="phone" class="form-element" required>'+
                                        '</div>'+
                                        '<div class="form-group">'+
                                            '<input type="text" name="name" class="form-element" placeholder="Ваше имя" required>'+
                                        '</div>'+
                                        '<div class="form-group">'+
                                            '<select name="type_research" class="form-element">'+
                                                '<option>Выберите исследование</option>'+
                                            '</select>'+
                                        '</div>'+
                                        '<div class="form-group">'+
                                            '<textarea class="form-element" class="form-element" name="message" placeholder="Текст сообщения"></textarea>'+
                                        '</div>'+
                                        '<div class="form-group order-window__action-group">'+
                                            '<button type="submit" class="btn btn-info">Отправить</button>'+
                                        '</div>'+
                                        '<input type="hidden" name="hospital_id">'+
                                        '<input type="hidden" name="_token">' +
                                    '</div>'+
                                    '<div class="modal-footer">'+
                                    '</div>'+
                                '</form>'+
                            '</div>'+
                        '</div>'+
                    '</div>';
    this.$template = $(this.template);


    if (name) {
        this.$template.find('input[name="name"]').val(name);
    }

    if (phone) {
        this.$template.find('input[name="phone"]').val(phone);
    }

    if  (typeResearchesOptions) {
        try {
            typeResearchesOptions = JSON.parse(typeResearchesOptions);
        } catch (e) {}
        this.fillResearches(typeResearchesOptions);
    }

    this.$template.find('input[name="phone"]').mask('0 (000) 000-00-00', {placeholder:"+_ (___) ___-__-__"});
    var self = this;

    /* Отправка данных на сервер */
    this.$template.find('form').bind('submit', function () {
        var name = self.$template.find('input[name="name"]').val();
        var phone = self.$template.find('input[name="phone"]').val();
        var research = self.$template.find('select[name="type_research"]').val();
        var message = self.$template.find('textarea[name="message"]').val();
        var token = self.$template.find('input[name="_token"]').val();
        self.$template.find('btn').css('display', 'none');
        $.ajax({
            method:'post',
            cache: false,
            url: '/callback_order',
            data: 'name='+name+'&phone='+phone+'&research='+research+'&message='+message+'&_token='+token,
            context: self,
            success: function (data) {
                console.log (data);
                this.$template.find('.modal-body').empty().html('Ваша заявка успешно отправлена.');
            },
            error: function (xhr) {
                console.log (xhr);
            }
        });
        return false;
    });

    this.$template.on('hidden.bs.modal', function (e) {
        self.$template.remove();
    });

    var key = $('body').data('key');
    this.$template.find('input[name="_token"]').val(key);

    $('body').append(this.$template);
};

OrderWindow.prototype.fillResearches = function (typeResearchesOptions) {
    var select = this.$template.find('select[name="type_research"]');
    for (var i = 0; i < typeResearchesOptions.length; i++) {
        select.append('<option value="'+typeResearchesOptions[i].val+'">'+typeResearchesOptions[i].name+'</option>');
    }
}

OrderWindow.prototype.show = function () {
    $.ajax({
        method:'get',
        context: this,
        url: '/allresearches',
        chache: false,
        success: function (data) {
            try {
                data = JSON.parse(data);
            } catch (e) {}
            this.fillResearches(data);
            this.$template.modal('show');
        },
        error: function (xhr) {
            console.log (xhr);
        }
    });
};