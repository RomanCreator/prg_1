+function ($) {
    'use strict';

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
        var Placemark = new ymaps.Placemark(this.technicalData);
        this.map.geoObjects.add(Placemark);
        this.map.setCenter(this.technicalData, 16);
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