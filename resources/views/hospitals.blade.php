@extends('layouts.frontend')

@section('content')
    <div class="container">
        <h1>Медицинские центры</h1>
        @if (isset($hospitals))
            <div class="hospitals">
                @foreach($hospitals as $hospital)
                    <div class="hospitals__item">
                        <img class="hospitals__item__cover" src="{{ $hospital->logo }}">
                        <div class="hospitals__item__info">
                            <a class="hospitals__item__name" href="hospitals/{{$hospital->id}}">{{$hospital->name}}</a>
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
                            <button class="btn btn-info checkin">Записаться</button>
                        </div>
                    </div>
                @endforeach
            </div>
            <?php echo $hospitals->render(); ?>
        @elseif(!isset($hospitals))

        @endif
    </div>
@endsection