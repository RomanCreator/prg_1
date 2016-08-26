/**
 * Created by roman on 16.08.16.
 */
var simplegallery = function (element) {
    this.$gallery = $(element);
    this.$bigImg = this.$gallery.find('.gallery__big > .gallery__big__elem');
    this.$controlUp = this.$gallery.find('.gallery__nav > .gallery__nav__control.gallery__nav__control_up');
    this.$controlDown = this.$gallery.find('.gallery__nav > .gallery__nav__control.gallery__nav__control_down');
    this.$controlLeft = this.$gallery.find('.gallery__nav > .gallery__nav__control.gallery__nav__control_left');
    this.$controlRight = this.$gallery.find('.gallery__nav > .gallery__nav__control.gallery__nav__control_right');
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

    this.$controlLeft.bind('click', function () {
        self.scrollLeft();
        return false;
    });

    this.$controlRight.bind('click', function () {
        self.scrollRight();
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
};

simplegallery.prototype.scrollLeft = function () {
    var currentScroll = this.$wrapper.scrollLeft();
    var width = this.miniature.eq(1).outerWidth(true);
    if (currentScroll > 0 && (currentScroll-width >= 0)) {
        this.$wrapper.scrollLeft(currentScroll-width);
    } else {
        this.$wrapper.scrollLeft(0);
    }
};

simplegallery.prototype.scrollRight = function () {
    var currentScroll = this.$wrapper.scrollLeft();
    var width = this.miniature.eq(1).outerWidth(true);
    if (currentScroll < (this.miniature.length*width)) {
        this.$wrapper.scrollLeft(currentScroll+width);
    } else {
        this.$wrapper.scrollTop(this.miniature.length*width);
    }
};

$(document).ready(function () {
    new simplegallery($('[data-toggle="simple_gallery"]'));
});