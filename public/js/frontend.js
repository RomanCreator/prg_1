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
/**
 * Created by roman on 25.07.16.
 */
var PhonePanel = function (selector) {

    this.$panel = $(selector);
    this.$buttonToggle = this.$panel.find('.phone-panel__footer');
    this.$body = this.$panel.find('.phone-panel__body');
    this.$body.find('input[name="phone"]').mask('0 (000) 000-00-00', {placeholder:"+_ (___) ___-__-__"});

    var self = this;
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
    new PhonePanel('.phone-panel.enabled');
});
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
    this.$mapContainer.find('.'+$window.attr('class')).remove();
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
/**
 * Created by roman on 16.08.16.
 */
var simplegallery = function (element) {
    this.$gallery = $(element);
    this.$bigImg = this.$gallery.find('.gallery__big > .gallery__big__elem');
    this.$controlUp = this.$gallery.find('.gallery__nav > .gallery__nav__control.gallery__nav__control_up');
    this.$controlDown = this.$gallery.find('.gallery__nav > .gallery__nav__control.gallery__nav__control_down');
    this.$wrapper = this.$gallery.find('.gallery__nav > .gallery__nav__wrapper');
    this.miniature = this.$gallery.find('.gallery__nav > .gallery__nav__wrapper > .gallery__nav__elem');

    var self = this;

    this.miniature.bind('click', function () {
        self.changeMiniature(this);
        return false;
    });

    this.$controlUp.bind('click', function () {
        self.scrollTop();
        return false;
    });

    this.$controlDown.bind('click', function () {
        self.scrollDown();
        return false;
    });

};

simplegallery.prototype.changeMiniature = function (elem) {
    var bigImg = $(elem).data('orig');
    this.$bigImg.attr('src', bigImg);
};

simplegallery.prototype.scrollTop = function () {
    /* Вычислим размер миниатюры изображения, и текущий скролл */
    var currentScroll = this.$wrapper.scrollTop();
    var height = this.miniature.eq(1).outerHeight(true);
    if (currentScroll > 0 && (currentScroll-height >= 0)) {
        this.$wrapper.scrollTop(currentScroll-height);
    } else {
        this.$wrapper.scrollTop(0);
    }
};

simplegallery.prototype.scrollDown = function () {
    var currentScroll = this.$wrapper.scrollTop();
    var height = this.miniature.eq(1).outerHeight(true);
    if (currentScroll < (this.miniature.length*height)) {
        this.$wrapper.scrollTop(currentScroll+height);
    } else {
        this.$wrapper.scrollTop(this.miniature.length*height);
    }
}

$(document).ready(function () {
    new simplegallery($('[data-toggle="simple_gallery"]'));
});
//# sourceMappingURL=frontend.js.map
