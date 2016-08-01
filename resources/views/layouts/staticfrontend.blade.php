<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @if(isset($keywords) && !empty($keywords))
        <meta name="keywords" content="{{ $keywords }}">
    @endif
    @if(isset($description) && !empty($description))
        <meta name="description" content="$description">
    @endif
    <title>{{ $title }}</title>


    @include('fragments.global.scriptsandstyles')





    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">-->
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}


</head>
<body id="app-layout">
@include('fragments.global.header')
@yield('content')
@include('fragments.global.footer')
</body>
</html>
