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
                    <a href="#" class="info-panel__link">Заказать обратный звонок</a>
                </div>
            </div>

            <div class="phone-panel enabled">
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
            <div class="searchmap">

            </div>
            @if (isset($hospitals))
                <div class="hospitals">
                @foreach($hospitals as $hospital)
                    <div class="hospitals__item">
                        <img class="hospitals__item__cover" src="{{ $hospital->logo }}">
                        <div class="hospitals__item__info">
                            <a class="hospitals__item__name" href="hospitals/{{$hospital->id}}">{{$hospital->name}}</a>
                            <div class="hospitals__item__address">
                                {{ !empty($hospital->getDistrict) ? $hospital->getDistrict->name : '' }}<br>
                                {{ $hospital->address }}<br>
                                {{ $hospital->subway }}
                            </div>
                            <ul class="hospitals__item__timeToWork">
                                @if (isset($hospital->timeWorks))
                                    @foreach($hospital->timeWorks as $time)
                                        <li>{{ $time }}</li>
                                    @endforeach
                                @endif
                            </ul>
                            <div class="hospitals__item__phone">(812) 490-75-73</div>
                        </div>
                        <div class="hospitals__item__action">
                            <button class="btn btn-info checkin">Записаться</button>
                        </div>
                    </div>
                @endforeach
                </div>
            @endif
        </div>
    </div>
    <div class="segmentpanel segmentpanel_blue">
        <div class="container">
            <h2>Почему же именно МРТ?</h2>
            <p>Основные преимущества МРТ - точность и безопасность.<br>
            МРТ дает возможность провести разные виды обследования головы, головного мозга, сосудов,
            позвоночника, суставов, органов - и получить послойное изображений исследуемой области
            в различных плоскостях. В отличие от рентгена, МРТ позволяет "увидеть" не только костную ткань,
            но и мягкие ткани.<br>
            Магнитно-резонансная томография не наносит никакого вреда организму пациента, чем выгодно
            отличается от ренгеновского излучения. Во многих случаях именно МРТ дает наиболее полную и
            точную картину происходящих в организме процессов о сравнению с любыми дргими
                лучевыми исследованиями.<br>
            Кроме того, в большинстве случаев исследование не требует никакой подготовки пациента и
                имеет небольшой список противопоказаний.</p>
            <h2>Как работает МРТ диагностика?</h2>
            <p>Метод МРТ основывается на физическом явлении магнитного резонанса. Впервые о возможности использования данного явления в медицине
            заговорили в 1973 году. Аппарат МРТ использует магнитное поле, чтобы &laquo;просканировать&raquo; организм пациента. В результате получаются
            детальные изображения любой необходимой структуры тела - головного мозга, сосудов, суставов, позвоночника, органов и систем. Изображение
            можно распечатать или посмотреть на экране компьютера.</p>
            <h2>Какие существуют противопоказания?</h2>
            <p>Магнитно-Резонансная Томография, в отличие от, к примеру, рентгеновского излучения не наносит вреда здоровью, а результаты исследования
            превосходят все остальные виды лучевой диагностики. Сравнительно небольшой спектр противопоказаний плюс различные варианты
            проведения обследования позволяют добиваться требуемых результатов при постановке самых сложных диагнозов.</p>
        </div>
    </div>
@endsection
