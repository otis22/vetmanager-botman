<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Прайс-лист</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet" type="text/css">
    <link href="/css/style.css" rel="stylesheet" type="text/css">
</head>
<body>
    <div class="container">
        <div class="content">
            <table class="table">
                <thead>
                <tr>
                    <th>Название</th>
                    <th>Цена</th>
                </tr>
                </thead>
                <tbody>
                @foreach($goods as $good)
                    <tr>
                        <td>{{ $good['title'] }}</td>
                        <td>{{ $good['price'] }}</td>
                    </tr>
                @endforeach

                </tbody>

            </table>
        </div>
    </div>
</body>
</html>