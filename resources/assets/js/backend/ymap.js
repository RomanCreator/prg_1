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