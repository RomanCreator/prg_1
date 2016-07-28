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

        /* Тут инициализация действий при нажатии на кнопку */
    };

    var ImagePickerAddItem = function ($elem, $template) {
        var addBtnTpl = '<div class="imagepickermult__item add-toggle">'+
                            '<label class="imagepickermult__item__container">'+
                            '</label>'+
                        '</div>';

        $elem.after($template);
        $elem.css({
            'display':'none'
        });

        this.$addBtn = $(addBtnTpl);
        $template.append(this.$addBtn);

        this.$addBtn.find('label').append($elem);

        /* Тут инициализация действий при выборе изображения, изображений */
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
            var $hiddenInput = $(hiddenNamespace);
            $hiddenInput.attr('name', 'namespace');
            $hiddenInput.val(namespace);
            $template.append($hiddenInput);
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