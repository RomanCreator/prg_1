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
                    <form class="form-horizontal" method="get">
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
            <div class="searchmap" id="searchmap" data-hospitals='{!! $hospitalsData !!}'>

            </div>
            <form action="/" method="get" class="search-panel">
                <div class="search-panel__elem col-lg-4 col-md-4">
                    <input type="text" name="district" class="form-element" placeholder="Введите метро или район"
                    @if($districtSelected)
                        value="{{ $districtSelected }}"
                    @endif
                    >
                </div>
                <div class="search-panel__elem col-lg-3 col-md-3">
                    <select name="type_equipment" class="form-element" data-toggle="jselect">
                        <option value="">Тип томографа</option>
                        @if(isset($tomographTypes))
                            @foreach($tomographTypes as $tomographType)
                                <option value="{{ $tomographType->id }}"
                                @if($typeEquipmentSelected)
                                    @if($typeEquipmentSelected == $tomographType->id)
                                        selected
                                    @endif
                                @endif

                                >{{ $tomographType->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="search-panel__elem col-lg-4 col-md-4">
                    <select name="type_research" class="form-element" data-toggle="jselect">
                        <option value="">Тип исследования</option>
                        @if(isset($researches))
                            @foreach($researches as $research)
                                <option value="{{ $research->id }}"
                                @if($typeResearchSelected)
                                    @if($typeResearchSelected == $research->id)
                                        selected
                                    @endif
                                @endif
                                >{{ $research->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-lg-1 col-md-1">
                    <button type="submit" class="btn btn-search"></button>
                </div>
            </form>
            @if (isset($hospitals))
                <div class="hospitals">
                @foreach($hospitals as $hospital)
                    <div class="hospitals__item">
                        <div class="hospitals__item__cover">
                            <img src="{{ $hospital->logo }}">
                            <div class="hospitals__item__prices_mobile">
                                @if (!empty($hospital->typeResearchesPrice()))
                                    @foreach($hospital->typeResearchesPrice() as $price)
                                        <span class="hospitals__item__action__type-price mobile">{{ $price }}</span>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="hospitals__item__info">
                            <a class="hospitals__item__name" href="hospitals/{{$hospital->id}}">{{$hospital->name}}</a>
                            <div class="hospitals__item__tags">
                                @if (!empty($hospital->getTags()))
                                    @foreach($hospital->getTags() as $tag)
                                        @if(!empty($tag))
                                            <span class="hospitals__item__tag">{{ $tag }}</span>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="hospitals__item__address">
                                <span class="hospitals__item__address__district">{{ !empty($hospital->getDistrict) ? $hospital->getDistrict->name : '' }}</span>
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
                            @if (!empty($hospital->typeResearchesPrice()))
                                @foreach($hospital->typeResearchesPrice() as $price)
                                    <span class="hospitals__item__action__type-price">{{ $price }}</span>
                                @endforeach
                            @endif
                            <button class="btn btn-info checkin" data-id="{{ $hospital->id }}">Записаться</button>
                        </div>
                    </div>
                @endforeach
                </div>

                {{ $hospitals->links() }}
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
