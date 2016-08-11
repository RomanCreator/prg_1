/**
 * Created by roman on 09.08.16.
 */
var SearchMap = function (selector) {
    this.defaultCenterMap = [59.939095, 30.315868];
    this.defaultZoom = 16;
    this.defaultPlacemark = '/img/icon_placemark.png';

    this.templateInfoWindow = '<div class="searchmap__window">'+
                                    '<div class="searchmap__window__header">'+
                                        '<span class="searchmap__window__header__label"></span>'+
                                    '</div>'+
                                    '<div class="searchmap__window__body">'+
                                        '<div class="searchmap__window__district"></div>'+
                                        '<div class="searchmap__window__address"></div>'+
                                        '<ul class="searchmap__window__workweek"></ul>'+
                                        '<div class="searchmap__window__phone">(812) 490-75-73</div>'+
                                        '<div class="searchmap__window__promophone">'+
                                            '<div class="searchmap__window__promophone__label">Запишитесь по номеру</div>'+
                                            '8 (800) 888-00-00'+
                                        '</div>'+
                                    '</div>'+
                              '</div>';

    this.$mapContainer = $(selector);
    this.map = null;
    this.MedicalCenters = this.$mapContainer.data('hospitals');
    if ((typeof this.MedicalCenters) !== 'object') {
        this.MedicalCenters = [];
    }

    var self = this;
    /* Активизируем карту и расставим тыцки на ней, за одно не забудем у пользователя запросить определение местонахождения */
    ymaps.ready(function () {
        /* Определяем пользователя */
        ymaps.geolocation.get().then (function (res) {
            var bounds = res.geoObjects.get(0).properties.get('boundedBy');
            var mapState = ymaps.util.bounds.getCenterAndZoom(
                    bounds,
                    [self.$mapContainer.width(), self.$mapContainer.height()]
            );
            mapState.controls = [];
            mapState.zoom = self.defaultZoom;
            self.map = new ymaps.Map(self.$mapContainer.attr('id'), mapState);
            self.setObjectsOnAMap();
        }, function (err) {
            self.map = new ymaps.Map(self.$mapContainer.attr('id'), {
                center: self.defaultCenterMap,
                zoom: self.defaultZoom,
                controls: []
            });
            self.setObjectsOnAMap();
        });

    });
};

/* Устанавливает все медицинские центры на карту */
SearchMap.prototype.setObjectsOnAMap = function () {
    var countObjects = this.MedicalCenters.length;
    var collectionOfMedicalCenters = new ymaps.GeoObjectCollection();
    var self = this;

    for (var i = 0; i < countObjects; i++) {
        if (this.MedicalCenters[i].technical_address) {
            if ( (typeof this.MedicalCenters[i].technical_address) !== 'object') {
                try {
                    this.MedicalCenters[i].technical_address = JSON.parse(this.MedicalCenters[i].technical_address);
                } catch (Err) {
                    continue;
                }
            }

            if (!this.MedicalCenters[i].technical_address.coordinates) {
                continue;
            }

            var placemark = new ymaps.Placemark(this.MedicalCenters[i].technical_address.coordinates, {
                        district: this.MedicalCenters[i].district,
                        address: this.MedicalCenters[i].address,
                        subway: this.MedicalCenters[i].subway,
                        name: this.MedicalCenters[i].name,
                        weekwork: this.MedicalCenters[i].weekwork,
                    },
                    {
                        iconLayout: 'default#image',
                        iconImageHref: this.defaultPlacemark,
                        iconImageSize: [57, 75],
                        iconImageOffset: [-29, -75],
                        draggable: false
                    });
            /* Навешиваем событие при клике на метку */
            placemark.events.add('click', function (e) {
                self.showInfo(e);
            });


            collectionOfMedicalCenters.add(placemark);

        }
    }

    this.map.geoObjects.add(collectionOfMedicalCenters);
}

/* Отображает информацию о медицинском центре */
SearchMap.prototype.showInfo = function (placemark) {
    var $window = $(this.templateInfoWindow);
    this.$mapContainer.find($window.attr('class')).remove();
    $window.find('.searchmap__window__header__label').text(placemark._cache.target.properties._data.name);
    $window.find('.searchmap__window__district').text(placemark._cache.target.properties._data.district);
    $window.find('.searchmap__window__address').html(placemark._cache.target.properties._data.address+'<br>'+'м. '+placemark._cache.target.properties._data.subway);
    var workWeek = '';
    var workWeekProp = placemark._cache.target.properties._data.weekwork;
    for (var i =0; i < workWeekProp.length; i++) {
        workWeek += '<li>'+workWeekProp[i]+'</li>';
    }
    $window.find('.searchmap__window__workweek').append(workWeek);
    this.$mapContainer.append($window);
}



$(document).ready(function () {
    new SearchMap('.searchmap');
});