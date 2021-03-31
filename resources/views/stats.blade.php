<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Statistics</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet" type="text/css">
    <link href="/css/style.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container">
    <div class="content">
        <h1>Statistics</h1>
        <h2>Average rating: {{$avgReviewMark}}</h2>
        <div class="stats-block">
            <h6>Users Count</h6>
            <h1>{{$userCount}}</h1>
        </div>
        <div class="stats-block">
            <h6>Incoming notify</h6>
            <h1>{{$notifies}}</h1>
        </div>
        <canvas id="chart" width="400" height="400"></canvas>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>mark</th>
                    <th>best feature</th>
                </tr>
            </thead>
            @foreach($reviews as $review)
                <tr>
                    <td>{{$review->mark}}</td>
                    <td>{{$review->the_best_feature}}</td>
                </tr>
            @endforeach
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>

<script>
    var data = {!! json_encode($eventsLast10Days) !!}

    var ctx = document.getElementById('chart').getContext('2d');

    var chart = new Chart(ctx, {
        "type": 'line',
        "data": {
            "labels":
                data.labels,
            "datasets":
                [
                    {
                        "label":"Events",
                        "data":data.data,
                        "fill":false,"borderColor":"rgb(75, 192, 192)",
                        "lineTension":0.1
                    }
                ]
        },
        "options":{}
    })
</script>


</body>
</html>