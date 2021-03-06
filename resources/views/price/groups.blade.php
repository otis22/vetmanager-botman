<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Выберите группу товаров</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet" type="text/css">
    <link href="/css/style.css" rel="stylesheet" type="text/css">
</head>
<body>
    <div class="container">
        <div class="content">
            @foreach($goodGroups as $goodGroup)
                <a class="good-group" href="{{ Request::url() }}/{{$goodGroup['id']}}">{{ $goodGroup['title'] }}</a>
            @endforeach
        </div>
    </div>
</body>
</html>