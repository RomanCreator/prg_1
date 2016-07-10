/**
 * Created by roman on 29.06.16.
 */
$(document).ready(function () {
    /**
     * Инициализация всех тултипов
     */
    $('[data-toggle="tooltip"]').tooltip();
    var $CkEditor = $('[data-toggle="ckeditor"]');
    for (var i = $CkEditor.length; i--;) {
        /* Маленькая инициализационная область для CKEditor */
        var name = $CkEditor.eq(i).attr('name');
        var id = $CkEditor.eq(i).attr('id');

        var errMessage = false;
        if (name == undefined) {
             errMessage = 'Не задан атрибут name!';
        }

        if (id == undefined) {
            errMessage += ' Не задан атрибут id!';
        }

        if (CKEDITOR == undefined) {
            errMessage += 'Не определен CkEditor';
        }

        if (errMessage) {
            errMessage += ' Для элемента '+$CkEditor.eq(i)[0];
            console.log(errMessage);
            continue;
        }

        /* Если же все условия выполнены для замечательной работы CKEditor инициализируем его */
        CKEDITOR.replace(id);
    }


    $('[data-toggle="countdown"]').countdown();
    $('[data-toggle="imagepicker"]').imagepicker();
});
/**
 * Created by roman on 29.06.16.
 */
+function ($) {
    'use strict';

    var CountdownElemForm = function (element, options) {
        this.$element = $(element);
        this.CurrentOption = this.getOptions (options);
        this.$form = this.$element.closest('form');
        this.intervalId = null;
        this.countdownState = 'start';
        this.innerHtml = null;

        var self = this;
        this.$form.bind('submit.countdown', function () {
            return self.sendForm();
        });

        this.$element.bind('click.countdown', function () {
            if (self.countdownState == 'start') {
                self.countdownState = 'proccess';
                self.innerHtml = self.$element.html();
                self.$element.html(self.CurrentOption.countdownTime);
                self.intervalId = setInterval(function () {
                    self.action.call(self);
                }, self.CurrentOption.countdownIntervalSec*1000);
            }

            if (self.countdownState == 'end') {
                return true;
            }
            return false;
        });
    };

    /**
     * Сам непосредственный экшн для отсчета
     */
    CountdownElemForm.prototype.action = function () {
        var time = parseInt(this.$element.html());
        if (time > 0) {
            time--;
            this.$element.html(time);
        } else {
            this.$element.html(this.innerHtml);
            this.countdownState = 'end';
            clearInterval(this.intervalId);
        }
    }

    /**
     * Разршаем отправку формы тогда и только тогда, когда
     * заканчивается обратный отсчет, до этого форму не отрпавляем
     * @returns {boolean}
     */
    CountdownElemForm.prototype.sendForm = function () {
        if (this.countdownState !== 'end') {
            return false;
        }

        return true;
    }

    CountdownElemForm.prototype.options = {
        countdownTime: 3,
        countdownIntervalSec: 1,
    };

    CountdownElemForm.prototype.getOptions = function (options) {
        if (options && (typeof options) == 'Object') {
            for (var key in this.options) {
                if (!options[key]) {
                    options[key] = this.options[key];
                };
            }

            return options;
        }

        return this.options;
    };

    var Countdown = function (options) {
        //вернуть надо объект jQuery
        return this.each(function () {
            new CountdownElemForm(this, options);
        });
    };


    var old = $.fn.countdown;

    $.fn.countdown = Countdown;
    $.fn.countdown.constructor = Countdown;

    $.fn.countdown.noConflict = function () {
        $.fn.countdown = old;
        return this;
    }

}(jQuery);
/**
 * Created by roman on 10.07.16.
 */
+function ($) {
    'use strict';

    var ImagePickerElem = function (element, option) {
        this.template = '<div class="imagepicker__container">'+
                            '<label class="imagepicker">'+
                                '<div class="imagepicker__hover-place">'+
                                '</div>'+
                            '</label>'+
                        '</div>';
        this.inputClass = 'imagepicker__input';
        this.templateImg = '<img class="imagepicker__miniature img-responsive">';
        this.$element = $(element);

        this.init();

        var self = this;
        this.$element.on('change', function () {
            self.changedImage();
        });
    }

    ImagePickerElem.prototype.init = function () {
        this.$element.addClass(this.inputClass);
        var src = this.$element.data('src');
        this.$template = $(this.template);
        this.$element.after(this.$template);

        if (src != undefined) {
            var $img = $(this.templateImg).attr('src', src);
            this.$template.find('.imagepicker__hover-place').append($img);
        }

        this.$template.find('.imagepicker__hover-place').append(this.$element);
    }

    ImagePickerElem.prototype.changedImage = function () {
        if (this.$element[0] && this.$element[0].files[0]) {
            var file = this.$element[0].files[0];

            var reader = new FileReader();
            var self = this;
            reader.onload = (function (file){
                return function (e) {
                    var $img = self.$template.find('img');
                    if ($img.length == 0) {
                        $img = $(self.templateImg).attr('src', e.target.result);
                        self.$template.find('.imagepicker__hover-place').append($img);
                    } else {
                        self.$template.find('img.imagepicker__miniature').attr('src', e.target.result);
                    }
                };
            })(file);

            reader.readAsDataURL(file);
        }
    }

    var ImagePicker = function (option) {
        return this.each(function () {
            new ImagePickerElem(this, option);
        });
    };

    var old = $.fn.imagepicker;

    $.fn.imagepicker = ImagePicker;
    $.fn.imagepicker.constructor = ImagePicker;

    $.fn.imagepicker.noConflict = function () {
        $.fn.imagepicker = old;
        return this;
    }

}(jQuery);
//# sourceMappingURL=backend.js.map
