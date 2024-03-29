<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Hello, <?= $name?>!</h1>
    <ul>
        @foreach ($animals as $animal)
            <li> {{$animal}}</li>
        @endforeach
    </ul>
</body>

</html>