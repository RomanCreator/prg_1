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
    $('[data-toggle="imagepickermult"]').imagepickermult();
    $('[data-toggle="weekwork"]').weekwork();
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
                    self.$template.find('.imagepicker__hover-place').css({
                        'background':'transparent'
                    });
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
    'use strict';

    var ImagePickerMultItem = function ($pathToImage, $template) {
        var template = '<div class="imagepickermult__item">'+
                            '<div class="imagepickermult__item__container">'+
                                '<img>'+
                                '<span class="imagepickermult__ation-panel">'+
                                    '<button class="imagepickermult__btn imagepickermult__btn_remove"><i class="fa fa-trash-o" aria-hidden="true"></i></button>'+
                                '</span>'+
                            '</div>'+
                       '</div>';
        this.$elem = $(template);
        this.$elem.find('img').attr('src', $pathToImage);
        var nameOfFile = $pathToImage.split('/');
        nameOfFile = nameOfFile[nameOfFile.length-1];
        this.$elem.find('button.imagepickermult__btn_remove').data('name', nameOfFile);
        $template.prepend(this.$elem);
        this.$template = $template;

        var self = this;

        /* Тут инициализация действий при нажатии на кнопку */
        this.$elem.find('.imagepickermult__btn_remove').bind('click.imagepickermult', function () {
            self.deleteItem();
            return false;
        });
    };

    ImagePickerMultItem.prototype.deleteItem = function () {
        var templateInput = '<input type="hidden">';
        var nameSpace = this.$template.data('namespace');
        nameSpace = nameSpace+'[][remove]';

        var nameOfFile = this.$elem.find('button.imagepickermult__btn_remove').data('name');
        var $InputToRemove = $(templateInput);
        $InputToRemove.attr('name', nameSpace).val(nameOfFile);
        this.$elem.after($InputToRemove);
        this.$elem.remove();
    }

    var ImagePickerAddItem = function ($elem, $template) {
        var addBtnTpl = '<div class="imagepickermult__item add-toggle">'+
                            '<label class="imagepickermult__item__container">'+
                            '</label>'+
                        '</div>';

        this.templateImg = '<div class="imagepickermult__item">'+
                                '<div class="imagepickermult__item__container">'+
                                    '<img>'+
                                    '<span class="imagepickermult__ation-panel">'+
                                        '<button class="imagepickermult__btn imagepickermult__btn_remove"><i class="fa fa-trash-o" aria-hidden="true"></i></button>'+
                                    '</span>'+
                                '</div>'+
                            '</div>';
        this.deleteInputTmp = '<input type="hidden">';

        $elem.after($template);
        $elem.css({
            'display':'none'
        });

        this.$addBtn = $(addBtnTpl);
        this.$elem = $elem;
        this.$template = $template;

        $template.append(this.$addBtn);

        this.$addBtn.find('label').append($elem);

        var self = this;
        /* Тут инициализация действий при выборе изображения, изображений */
        this.$elem.bind('change', function () {
            self.addSelectedImage();
        });
    };

    ImagePickerAddItem.prototype.addSelectedImage = function () {
        /* Создаем миниатюру */
        /* Перемещаем туда наш $elem */
        /* Помещаем в кнопку добавления клон */
        var files = this.$elem[0].files;

        for (var i = 0; i < files.length; i++) {
            var reader = new FileReader();
            var self = this;
            reader.onload = (function (files){
                return function (e) {
                    console.log (files);
                    var $Image = $(self.templateImg);
                    $Image.find('img').attr('src', e.target.result);
                    $Image.find('.imagepicker__hover-place').css({
                        'background':'transparent'
                    });
                    $Image.data('file-name', files.name);
                    $Image.find('.imagepickermult__btn_remove').bind('click.imagepickermult', function () {
                        var name = $(this).closest('.imagepickermult__item').data('file-name');
                        var nameElem = self.$elem.attr('name')+"[notupload]";
                        var $input = $(self.deleteInputTmp);
                        $input.attr('name', nameElem);
                        $input.val(name);
                        $(this).closest('.imagepickermult__item').after($input);
                        $(this).closest('.imagepickermult__item').remove();

                    });

                    self.$template.find('.add-toggle').before($Image);
                };
            })(files[i]);
            reader.readAsDataURL(files[i]);
        }

    };

    var ImagePickerMult = function (option) {
        return this.each(function () {
            /* тут главный конструктор для элемента this */
            var template = '<div class="imagepickermult__container">'+
                            '</div>';
            var hiddenNamespace = '<input type="hidden">';



            /* Сам элемент в представлении jQuery */
            var $elem = $(this);

            /* Загруженные изображения */
            var images = $elem.data('upload-images');
            images = images.split(',');
            images.pop();


            /* Неймспейс в котором храняться изображения */
            var namespace = $elem.attr('id');

            var $template = $(template);
            $template.data('namespace', namespace);
            /*
            var $hiddenInput = $(hiddenNamespace);
            $hiddenInput.attr('name', 'namespace');
            $hiddenInput.val(namespace);
            $template.append($hiddenInput);*/
            /* Создаем кнопку добалвения элементов */
            new ImagePickerAddItem($elem, $template);


            for (var i = 0; i < images.length; i++) {
                new ImagePickerMultItem(images[i], $template);
            }



        });
    };

    var old = $.fn.imagepickermult;

    $.fn.imagepickermult = ImagePickerMult;
    $.fn.imagepickermult.constructor = ImagePickerMult;

    $.fn.imagepickermult.noConflict = function () {
        $.fn.imagepickermult = old;
        return this;
    }
}(jQuery);
+function ($) {


    var WeekWorkElem = function (dayName, $data, callbackAfterChangeTime, $elem) {
        this.template = '<div class="row">'+
                            '<div class="col-sm-2 name-of-day"></div>'+
                            '<div class="col-sm-3"><input class="form-control time-from" placeholder="00:00"></div>'+
                            '<div class="col-sm-1" style="text-align: center;">-</div>'+
                            '<div class="col-sm-3"><input class="form-control time-to" placeholder="24:00"></div>'+
                            '<div class="col-sm-3">'+
                                '<a href="#" class="btn btn-default all-day"><i class="fa fa-clock-o" aria-hidden="true"></i> 24</a>'+
                                '<a href="#" class="btn btn-danger clear-interval"><i class="fa fa-times" aria-hidden="true"></i></a>'+
                            '</div>'+
                        '</div>';

        this.$elem = $elem;
        this.$day = $(this.template);
        this.$day.find('.name-of-day').text(dayName);
        this.$timeFrom = this.$day.find('.time-from');
        this.$timeTo = this.$day.find('.time-to');
        this.$allDayWorkBtn = this.$day.find('.all-day');
        this.$clearIntervalBtn = this.$day.find('.clear-interval');
        this.dayName = dayName;

        this.callback = callbackAfterChangeTime;

        if ($data != undefined) {
            if ($data.timeFrom) {
                this.$timeFrom.val($data.timeFrom);
            }

            if ($data.timeTo) {
                this.$timeTo.val($data.timeTo);
            }
        }

        var self = this;

        this.$allDayWorkBtn.bind('click.weekwork', function () {
            self.setAllDay();
            self.changeTime();
            return false;
        });

        this.$clearIntervalBtn.bind('click.weekwork', function () {
            self.setClearTime();
            self.changeTime();
            return false;
        });

        this.$timeTo.bind('change.weekwork', function () {
            $(this).val(self._checkTime($(this).val()));
            self.changeTime();
        });

        this.$timeFrom.bind('change.weekwork', function () {
            $(this).val(self._checkTime($(this).val()));
            self.changeTime();
        });
    };

    /* Проверка введенного пользователем времени, возвращает время которое надо установить */
    WeekWorkElem.prototype._checkTime = function (time) {
        if (time != '') {
            time = time.split(':');
            if (time.length !== 2) {
                return '00:00';
            }

            var hours = time[0];
            if (isNaN(parseInt(hours))) {
                return '00:00';
            }
            hours = parseInt(hours);

            if (hours < 0 || hours > 23) {
                return '00:00';
            } else {
                if (hours > 0 && hours < 10) {
                    hours = '0'+hours;
                }
            }

            var minute = time[1];
            if (isNaN(parseInt(minute))) {
                return '00:00';
            }

            minute = parseInt(minute);

            if (minute < 0 || minute > 59) {
                return '00:00';
            } else {
                if (minute > 0 && minute < 10) {
                    minute = '0'+minute;
                }
            }

            return hours+':'+minute;
        }
    }


    /* Установить круглосуточный режим работы для данного дня */
    WeekWorkElem.prototype.setAllDay = function () {
        this.$timeFrom.val('00:00');
        this.$timeTo.val('23:59');
    }

    /* Очистить временной интервал для данного рабочего дня */
    WeekWorkElem.prototype.setClearTime = function () {
        this.$timeFrom.val('');
        this.$timeTo.val('');
    }

    /* Устанавлиает актуальное время для текущего дня */
    WeekWorkElem.prototype.changeTime = function () {
        var timingInterval = {};
        timingInterval.timeFrom = this.$timeFrom.val();
        timingInterval.timeTo = this.$timeTo.val();
        timingInterval.day = this.dayName;
        this.callback(timingInterval, this.$elem);
    }

    WeekWorkElem.prototype.getElem = function () {
        return this.$day;
    }

    var WeekWorkList = function (element) {
        this.days = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];

        this.$element = $(element);
        this.dataOfDays = this.$element.val();
        try {
            this.dataOfDays = JSON.parse(this.dataOfDays);
        } catch (Error) {
            this.dataOfDays = {};
        }
        this.$element.attr('type', 'hidden');
        this.$closestDiv = this.$element.closest('div');

        for (var i = 0; i < this.days.length; i++) {
            var tmp = new WeekWorkElem(this.days[i], this.dataOfDays[i], this.changedTimeDay, this.$element);
            this.$closestDiv.append(tmp.getElem());
        }

    };

    WeekWorkList.prototype.changedTimeDay = function (data, $elem) {
        var dataSet = $elem.val();
        try {
            dataSet = JSON.parse(dataSet);
        } catch (Error) {
            dataSet = {};
        }
        this.days = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];
        for (var i = 0; i < this.days.length; i++) {
            if (this.days[i] == data.day) {
                dataSet[i] = data;
            }
        }

        $elem.val(JSON.stringify(dataSet));
    };

    var WeekWork = function (option) {
        return this.each(function () {
            //Тут каждый элемент из всей выборки
            new WeekWorkList (this);
        });
    };

    var old = $.fn.weekwork;

    $.fn.weekwork = WeekWork;
    $.fn.weekwork.constructor = WeekWork;

    $.fn.weekwork.noConflict = function () {
        $.fn.weekwork = old;
        return this;
    }
} (jQuery);
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

        /* Проинициализируем поле с технической информацией об адресе */
        this.nameTechInput = this.$element.data('hidden-name');
        if (this.nameTechInput == undefined || this.nameTechInput == '') {
            throw new Error('Не задан атрибут data-hidden-name');
        }

        /* Посмотрим технические данные и человекопонятный адрес в элементе */
        try {
            this.technicalData = JSON.parse(this.$element.data('tech-data'));
        } catch (err) {
            this.technicalData = {};
        }

        this.userData = this.$element.val();

        /* Добавляем поле для сохранения технической информации */
        this.$hiddenInput = $('<input type="hidden">');
        this.$hiddenInput.attr('name', this.nameTechInput);
        this.$hiddenInput.val(JSON.stringify(this.technicalData));
        this.$element.after(this.$hiddenInput);

        /* Текуший элемент только readonly */
        this.$element.attr('readonly', true);

        this.$templateModal.attr('id', this.idModal);
        this.$templateModal.find('.maps > div').attr('id', this.idMap);

        $('body').append(this.$templateModal);


        var self = this;

        /* Инициализируем карту и поведение элемента */
        ymaps.ready(function () {

            self.$element.on('click', function() {
                /* инициализируем карту исходя из установленных значений */
                self.initMap();
                $('#'+self.idModal).modal('show');
            });

            /* Инизиализируем сохранение данных после выбора объекта на карте */
            self.$templateModal.find('form').on('submit', function () {
                self.saveSelectedObject();
                $('#'+self.idModal).modal('hide');
                return false;
            });

            self.map = new ymaps.Map(self.idMap, {
                center: self.defaults.defaultShowPoint,
                zoom: self.defaults.zoom,
                controls: [],
            });

            self.searchControl = new ymaps.control.SearchControl({
                options: {
                    provider: 'yandex#search',
                    kind: 'district'
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
        console.log ('initMap');
        /* Берем техническую информацию, создаем объект геолокации, добавляем его на карту */
        try {
            this.technicalData = this.$element.data('tech-data');
            if ((typeof this.technicalData) !== 'object') {
                this.technicalData = JSON.parse(this.technicalData);
            }
        } catch (err) {
            this.technicalData = {};
        }
        if (this.technicalData.coordinates) {
            this.selectedCoordinats = this.technicalData.coordinates;
        } else {
            this.selectedCoordinats = undefined;
        }

        if (this.technicalData.stops) {
            this.stops = this.technicalData.stops;
        } else {
            this.stops = undefined;
        }


        this.userData = this.$element.val();

        this.$templateModal.find('input[name="address"]').val(this.userData);
        if (ymaps && ymaps.Placemark && this.technicalData.coordinates && this.technicalData.coordinates.length == 2) {
            var Placemark = new ymaps.Placemark(this.technicalData.coordinates);
            this.map.geoObjects.add(Placemark);
            this.map.setCenter(this.technicalData.coordinates, 16);
        }
    }

    /* Функция обработки выбранного объекта на карте */
    YMapElement.prototype.objectSelected = function (searchResult) {
        /* searchResult тип IGeoObject */
        /* Достаем человекопонятный адрес и координаты */
        this.$templateModal.find('input[name="address"]').val(searchResult.properties.get('address'));//searchResult.properties._data.address);
        this.selectedCoordinats = searchResult.geometry.getCoordinates();
        this.stops = searchResult.properties.get('stops'); //Получили ближайшие станции метро, их может быть несколько
    }

    /* Функция для присвоения полученного результата в соответствующие поля формы для дальнейшего сохранения */
    YMapElement.prototype.saveSelectedObject = function () {
        var coordinats = this.selectedCoordinats;
        var textOfAddress = this.$templateModal.find('input[name="address"]').val();
        var stops = this.stops;

        if (coordinats != '' && textOfAddress != '') {
            this.$element.val(textOfAddress);
            var data;
            try {
                data = this.$element.data('tech-data');
                if ((typeof data) !== 'object') {
                    data = JSON.parse(data);
                }
            } catch (err) {
                data = {};
            }

            data.coordinates = coordinats;
            data.stops = stops;
            this.$element.data('tech-data', JSON.stringify(data));
            this.$hiddenInput.val(JSON.stringify(data));
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
                                                                'Выбор местаположения'+
                                                                '<button type="button" class="close" data-dismiss="modal" aria-label="Close">'+
                                                                    '<span aria-hidden="true">&times;</span>'+
                                                                '</button>'+
                                                                '<h4 class="modal-title"></h4>'+
                                                            '</div>'+
                                                            '<div class="modal-body">'+
                                                                '<div class="alert alert-info">Введите необходимый адрес в стрку поиска на карте и нажмите Enter<br>'+
                                                                'Убедитесь что соответствующий адрес отображается в поле под картой</div>'+
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
