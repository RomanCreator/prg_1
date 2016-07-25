@extends('layouts.frontend')

@section('content')
    <div class="segmentpanel segmentpanel_promo">
        <div class="container">
            <div class="info-panel">
                <div class="info-panel__header">
                    Наш сайт поможет вам
                </div>
                <div class="info-panel__body">
                    <span class="as_h1">Запишитесь на МРТ <br> и КТ <span>без очередей</span></span>
                    <p>Подбор оптимальной клиники<br>и запись на обследование. <br>
                        Консультация по общим вопросам диагностики <br> Запись по всем районам города</p>
                    <a href="#" class="info-panel__link">Заказаьт обратный звонок</a>
                </div>
            </div>

            <div class="phone-panel">
                <div class="phone-panel__body">
                    <form class="form-horizontal" method="post">
                        <div class="form-group">
                            <input type="text" class="form-control" name="phone" placeholder="Введите номер телефона" required>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="name" placeholder="Ваше имя" required>
                        </div>
                    </form>
                </div>
                <div class="phone-panel__footer">
                    Заказать звонок
                </div>
            </div>
        </div>
    </div>
    <div class="segmentpanel segmentpanel_white">
        <div class="container">
            <h1>Медицинские центры в Cанкт-петербурге</h1>
        </div>
    </div>
    <div class="segmentpanel segmentpanel_blue">
        <div class="container">
            <h2>Почему же именно МРТ?</h2>

            <h2>Как работает МРТ диагностика?</h2>

            <h2>Какие существуют противопоказания?</h2>
        </div>
    </div>
@endsection
