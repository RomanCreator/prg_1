/**
 * Created by roman on 29.06.16.
 */
+function ($) {
    'use strict';

    var Countdown = function (element, options) {
        this.$element = null;
        this.inState = null;
        this.type = null;
        this.timeout = null;
        this.options = null;
        this.innerHtml = null;
        this.timeout = null;

        this.init('delete_entity', element, options);
    }

    Countdown.VERSION = '1.0.0';

    Countdown.DEFAULTS = {
        timeout: 1000,
        time: 3,
        trigger: 'click',
    }

    Countdown.prototype.init = function (type, element, options) {
        this.type = type;
        this.$element = $(element);
        this.options = this.getOptions(options);
        this.inState = {
            click: false,
        }

        var triggers = this.options.trigger.split(' ');

        for (var i = triggers.length; i--;) {
            var trigger = triggers[i];
            if (trigger == 'click') {
                this.$element.on('click.'+ this.type, $.proxy(this.toggle, this));
            }
        }

    }

    Countdown.prototype.getDefaults = function () {
        return Countdown.DEFAULTS;
    }

    Countdown.prototype.getOptions = function (options) {
        options = $.extend({}, this.getDefaults(), this.$element.data(), options);

        if (options.timeout && !typeof options.timeout == 'number') {
            options.timeout = this.getDefaults().timeout;
        }

        return options;
    }

    Countdown.prototype.toggle = function() {
        if (this.inState === null) {
            this.inState = 'countdown';
            this.innerHtml = this.$element.html();
            this.$element.html(this.options.time);
            this.timeout = setInterval(this.toggle, this.options.timeout);
        } else {
            if (this.inState === 'countdown') {
                var time = 0+this.$element.text();
                if (time > 0 ) {
                    time--;
                    this.$element.text(time);
                } else {
                    this.$element.html(this.innerHtml);
                    this.inState = 'finish';
                    clearInterval(this.timeout);
                }
            }

            if (this.inState === 'finish') {
                return true;
            }
        }
        return false;
    }

    var old = $.fn.countdown;

    $.fn.countdown = Countdown;
    $.fn.countdown.constructor = Countdown;

    $.fn.countdown.noConflict = function () {
        $.fn.countdown = old;
        return this;
    }

}(jQuery);