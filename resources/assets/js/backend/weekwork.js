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
                if (hours >= 0 && hours < 10) {
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
                if (minute >= 0 && minute < 10) {
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