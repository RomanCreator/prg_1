<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ isset($title) ? $title : '' }}</title>

    @if(isset($keywords) && !empty($keywords))
        <meta name="keywords" content="{{ $keywords }}">
    @endif
    @if(isset($description) && !empty($description))
        <meta name="description" content="$description">
    @endif


    @include('fragments.global.scriptsandstyles')


</head>
<body id="app-layout" data-key="{{ csrf_token() }}">
    @include('fragments.global.header')
    @yield('content')
    @include('fragments.global.footer')
</body>
</html>
