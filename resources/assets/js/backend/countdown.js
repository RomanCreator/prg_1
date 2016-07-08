/**
 * Created by roman on 29.06.16.
 */
+function ($) {
    'use strict';

    var Countdown = function (options) {
        //вернуть надо объект jQuery
        return this.each(function () {

            Countdown.init(this, options);
        });
    };

    Countdown.prototype.VERSION = '1.0.0';

    /**
     * Дефолтные настройки плагина обратного отсчета
     *
     * параметр type может быть задан значением link в таком случае
     * развитие инициализации идет по сценарию ссылки с обратным отсчетом
     * иначе по кнопке submit в форме
     *
     * параметр countdownType задается в секундах
     *
     * callback должен быть функцией или bool
     * переменной в значении false если тело функции не
     * установлено
     *
     * @type {{type: string, countdownTime: number, callback: boolean}}
     */
    Countdown.prototype.DEFAULTS = {
        type: 'form',
        countdownTime: 3,
        callback: false,
    };

    /**
     * Основная функция инициализации обратного отсчета
     * @param elem
     */
    Countdown.prototype.init = function (elem, options) {
        var OptionsElem = this.getOption(options);

        var $elem = $(elem);
        $elem.data('countdownState', 'start');

        /* читаем опции, и идем по разным сценариям инициализации */
        switch (OptionsElem.type) {
            case 'form':
                /* перехватываем submit у ближайшй к элементу форме */
                /* внутри смотрим на текущее состояние countdownState элемента */
                /* если оно финально то отправляем форму в противном случае ничего не делаем */
                    var $form = $elem.closest('form');
                    $form.bind('submit.countdown', this.formSubmit);
                break;
            case 'link':
                break;
        }

        $elem.bind('click.countdown', function () {

        });

    };

    /**
     * Функция для обработки формы
     * @returns {boolean}
     */
    Countdown.prototype.formSubmit = function () {
        var state = $(this).find('[data-toggle="countdown"]').data('countdownState');
        switch (state) {
            case 'end':
                return true;
                break;
            default:
                return false;
        }
    }

    Countdown.prototype.action = function () {
        /**
         * Тут обратный отсчет
         */
    }

    /**
     * Возвращает дефолные и переданные опции слитые в
     * общие
     *
     * @param options
     */
    Countdown.prototype.getOption = function (options) {
        if (!options.type) {
            options.type = this.DEFAULTS.type;
        } else {
            switch (options.type) {
                case 'form':
                case 'link':
                    break;
                default:
                    throw new Error('Не верно указан тип элемента обратного отсчета');
            }
        }

        if (!options.countdownTime) {
            options.countdownTime = this.DEFAULTS.countdownTime;
        } else {
            if (isNaN(options.countdownTime)) {
                throw new Error('Не верно указано время обратного отсчета');
            }
        }

        if (!options.callback) {
            options.callback = this.DEFAULTS.callback;
        } else {
            switch (typeof options.callback) {
                case 'function':
                case 'boolean':
                    break;
                default:
                    throw new Error('CallBack имеет значение не разрешенного типа');
            }
        }

        return options;
    }




    var old = $.fn.countdown;

    $.fn.countdown = Countdown;
    $.fn.countdown.constructor = Countdown;

    $.fn.countdown.noConflict = function () {
        $.fn.countdown = old;
        return this;
    }

}(jQuery);