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
            <p>Счетчик посетителей клиник для сайта</p>
            <p>
                День: <img src="https://vetmanager-botman.herokuapp.com/shield/{{$md5}}/today"alt="">
                <textarea id="area1"><img src="https://vetmanager-botman.herokuapp.com/shield/{{$md5}}/today"alt=""></textarea>
                <button onclick='document.querySelector("#area1").select();
            document.execCommand("copy");'>Копировать</button>
            </p>

            <p>
                Неделя: <img src="https://vetmanager-botman.herokuapp.com/shield/{{$md5}}/week"alt="">
                <textarea id="area2"><img src="https://vetmanager-botman.herokuapp.com/shield/{{$md5}}/week"alt=""></textarea>
                <button onclick='document.querySelector("#area2").select();
            document.execCommand("copy")'>Копировать</button>
            </p>
        </div>
    </div>
</body>
</html>