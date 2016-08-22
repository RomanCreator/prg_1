/**
 * Created by roman on 25.07.16.
 */
var PhonePanel = function (selector) {

    this.$panel = $(selector);
    this.$buttonToggle = this.$panel.find('.phone-panel__footer');
    this.$body = this.$panel.find('.phone-panel__body');
    this.$body.find('input[name="phone"]').mask('0 (000) 000-00-00', {placeholder:"+_ (___) ___-__-__"});

    var self = this;
    if (this.$panel.hasClass('enabled')) {
        this.$buttonToggle.bind('click', function(ev) {
            ev.stopPropagation();
            self.showForm();
        });

        this.$body.bind('click', function (ev) {
            ev.stopPropagation();
        });

        $('body').bind('click', function (){
            self.hideForm();
        });
    } else {

        this.$buttonToggle.bind('click', function(ev) {
            self.sendForm();
            return false;
        });
    }

}

PhonePanel.prototype.showForm = function () {
    if  (this.$body.css('display') == 'block') {
        //Запускаем функцию проверки и отправки формы
        this.sendForm();
    } else {
        this.$body.show();
    }
}

PhonePanel.prototype.hideForm = function () {
    this.$body.hide();
}

PhonePanel.prototype.sendForm = function () {
    var phone = this.$body.find('input[name="phone"]').val();
    var name = this.$body.find('input[name="name"]').val();
    console.log (name);
    var window = new OrderWindow(name, phone);
    window.show();
}

$(document).ready (function (){
    new PhonePanel('.phone-panel:not(.enabled)');
    new PhonePanel('.phone-panel.enabled');
});