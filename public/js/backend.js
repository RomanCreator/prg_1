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
    $('[data-toggle="ymap"]').ymap();
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
+function ($) {


    var YMapElement = function (element, $options) {

        this.$templateModal = $(this.templateModal);
        this.$element = $(element);

        /* Инициализируем id самого модального окна */
        this.idModal = this.$element.data('id-modal');
        if (this.idModal == undefined || this.idModal == '') {
            throw new Error('Не задан атрибут data-id-modal');
        }

        /* Инициализируем id карты для дальнейшей работы с картой */
        this.idMap = this.$element.data('id-map');
        if (this.idMap == undefined || this.idMap == '') {
            throw new Error('Не задан атрибут data-id-map');
        }

        /* Проверим, подгружен ли объект ymaps и если нет то выкинем ошибку */
        if (!ymaps) {
            throw new Error('Не подключены яндекс карты');
        }

        this.nameTechInput = this.$element.data('hidden-name');
        if (this.nameTechInput == undefined || this.nameTechInput == '') {
            throw new Error('Не задан атрибут data-hidden-name');
        }

        /* Посмотрим технические данные и человекопонятный адрес в элементе */
        this.technicalData = this.$element.data('tech-data');
        this.userData = this.$element.val();

        /* Добавляем поле для сохранения технической информации */
        this.$hiddenInput = $('<input type="hidden">');
        this.$hiddenInput.attr('name', this.nameTechInput);
        this.$hiddenInput.val(this.technicalData);
        /* Преобразуем технические данные в массив для дальнейшей работы */
        this.technicalData = this.technicalData.split(',');
        this.$element.after(this.$hiddenInput);

        /* Текуший элемент только readonly */
        this.$element.attr('readonly', true);

        this.$templateModal.attr('id', this.idModal);
        this.$templateModal.find('.maps > div').attr('id', this.idMap);

        $('body').append(this.$templateModal);


        var self = this;
        this.$element.on('click', function() {
            /* инициализируем карту исходя из установленных значений */
            self.initMap();
            $('#'+self.idModal).modal('show');
        });

        /* Инизиализируем сохранение данных после выбора объекта на карте */
        this.$templateModal.find('form').on('submit', function () {
            self.saveSelectedObject();
            $('#'+self.idModal).modal('hide');
            return false;
        })

        /* Инициализируем карту */
        ymaps.ready(function () {
            self.map = new ymaps.Map(self.idMap, {
                center: self.defaults.defaultShowPoint,
                zoom: self.defaults.zoom,
                controls: [],
            });

            self.searchControl = new ymaps.control.SearchControl({
                options: {
                    provider: 'yandex#search'
                }
            });

            self.map.controls.add(self.searchControl);

            self.searchControl.events.add('resultselect', function (e) {
                var index = self.searchControl.getSelectedIndex(e);
                var result = self.searchControl.getResult(index);
                result.then(function (res) {
                    self.objectSelected (res);
                }, function(err) {
                    console.log(err);
                });
            });

        });


    };

    YMapElement.prototype.initMap = function () {
        /* Берем техническую информацию, создаем объект геолокации, добавляем его на карту */
        this.technicalData = this.$element.data('tech-data');
        this.selectedCoordinats = this.technicalData = this.technicalData.split(',');
        this.userData = this.$element.val();

        this.$templateModal.find('input[name="address"]').val(this.userData);
        if (ymaps && ymaps.Placemark && this.technicalData.length == 2) {
            var Placemark = new ymaps.Placemark(this.technicalData);
            this.map.geoObjects.add(Placemark);
            this.map.setCenter(this.technicalData, 16);
        }
    }

    /* Функция обработки выбранного объекта на карте */
    YMapElement.prototype.objectSelected = function (searchResult) {
        /* searchResult тип IGeoObject */
        /* Достаем человекопонятный адрес и координаты */
        this.$templateModal.find('input[name="address"]').val(searchResult.properties._data.address);
        this.selectedCoordinats = searchResult.geometry.getCoordinates();
    }

    /* Функция для присвоения полученного результата в соответствующие поля формы для дальнейшего сохранения */
    YMapElement.prototype.saveSelectedObject = function () {
        var coordinats = this.selectedCoordinats;
        var textOfAddress = this.$templateModal.find('input[name="address"]').val();

        if (coordinats != '' && textOfAddress != '') {
            this.$element.val(textOfAddress);
            this.$element.data('tech-data', coordinats.join(','));
            this.$hiddenInput.val(coordinats);
        }
    }

    /* Опции отображения карты */
    YMapElement.prototype.defaults = {
        defaultShowPoint: [59.939095, 30.315868], //Точка центрирования карты по умолчанию
        zoom: 10,
    };

    /* Шабон модального окна с картой */
    YMapElement.prototype.templateModal = '<div class="modal fade" id="" tabindex="-1" role="dialog">'+
                                                '<div class="modal-dialog">'+
                                                    '<div class="modal-content">'+
                                                        '<form class="form-horizontal">'+
                                                            '<div class="modal-header">'+
                                                                '<button type="button" class="close" data-dismiss="modal" aria-label="Close">'+
                                                                    '<span aria-hidden="true">&times;</span>'+
                                                                '</button>'+
                                                                '<h4 class="modal-title"></h4>'+
                                                            '</div>'+
                                                            '<div class="modal-body">'+
                                                                '<div class="form-group maps">'+
                                                                    '<div class="col-sm-12" id="" style="height:300px;"></div>'+
                                                                '</div>'+
                                                                '<div class="form-group">'+
                                                                    '<div class="col-sm-12">'+
                                                                        '<input type="text" class="form-control" name="address" readonly>'+
                                                                    '</div>'+
                                                                '</div>' +
                                                            '</div>'+
                                                            '<div class="modal-footer">'+
                                                                '<button type="submit" class="btn btn-primary">Выбрать</button>'+
                                                                '<button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button> '+
                                                            '</div>'+
                                                        '</form>'+
                                                    '</div>'+
                                                '</div>'+
                                            '</div>';

    var YMap = function (option) {
        return this.each(function () {
            //Тут каждый элемент из всей выборки
            new YMapElement(this, option);
        });
    };

    var old = $.fn.ymap;

    $.fn.ymap = YMap;
    $.fn.ymap.constructor = YMap;

    $.fn.ymap.noConflict = function () {
        $.fn.ymap = old;
        return this;
    }
} (jQuery);
//# sourceMappingURL=backend.js.map
