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
        <svg
                xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink" width="108" height="20" role="img" aria-label="$text: $num">
            <title></title>
            <linearGradient id="s" x2="0" y2="100%">
                <stop offset="0" stop-color="#bbb" stop-opacity=".1"/>
                <stop offset="1" stop-opacity=".1"/>
            </linearGradient>
            <clipPath id="r">
                <rect width="108" height="20" rx="3" fill="#fff"/>
            </clipPath>
            <g clip-path="url(#r)">
                <rect width="77" height="20" fill="#555"/>
                <rect x="77" width="31" height="20" fill="#97ca00"/>
                <rect width="108" height="20" fill="url(#s)"/>
            </g>
            <g fill="#fff" text-anchor="middle" font-family="Verdana,Geneva,DejaVu Sans,sans-serif" text-rendering="geometricPrecision" font-size="110">
                <text aria-hidden="true" x="395" y="150" fill="#010101" fill-opacity=".3" transform="scale(.1)" textLength="670">$text</text>
                <text x="395" y="140" transform="scale(.1)" fill="#fff" textLength="670">$text</text>
                <text aria-hidden="true" x="915" y="150" fill="#010101" fill-opacity=".3" transform="scale(.1)" textLength="210">{{ $weekVisits }}</text>
                <text x="915" y="140" transform="scale(.1)" fill="#fff" textLength="210">$num</text>
            </g>
        </svg>
    </div>
</div>
</body>
</html>