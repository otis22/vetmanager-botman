<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Statistics</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <style>
        body {
            font-family: "Varela Round", sans-serif;
            margin: 0;
            padding: 0;
            background: radial-gradient(#57bfc7, #45a6b3);
        }

        .container {
            display: flex;
            height: 100vh;
            align-items: center;
            justify-content: center;
        }

        .content {
            width: 40%;
            background-color: white;
            text-align: center;
            justify-content: space-between;
        }

        .stats-block {
            justify-content: space-between;
            display: inline-block;
        }

        .stats-block:not(:first-child):not(:last-child) {
            margin-left: 60px;
            margin-right: 60px;
        }

    </style>
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