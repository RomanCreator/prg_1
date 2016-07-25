/**
 * Created by roman on 25.07.16.
 */
var PhonePanel = function (selector) {

    this.$panel = $(selector);
    this.$buttonToggle = this.$panel.find('.phone-panel__footer');
    this.$body = this.$panel.find('.phone-panel__body');
    var self = this;
    this.$buttonToggle.bind('click', function(ev) {
        ev.stopPropagation();
        self.showForm();
    });

    $('body').bind('click', function (){
        self.hideForm();
    });
}

PhonePanel.prototype.showForm = function () {
    this.$body.show();
}

PhonePanel.prototype.hideForm = function () {
    this.$body.hide();
}

PhonePanel.prototype.sendForm = function () {
    console.log ('Отрпавляем форму');
}

$(document).ready (function (){
    new PhonePanel('.phone-panel');
});
//# sourceMappingURL=frontend.js.map
