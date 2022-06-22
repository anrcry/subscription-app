<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Notification</title>
</head>
<body>
    <p>Dear {{ $reciever }}, we have a new post.</p>
    <hr/>
    <h1>{{ $title }}</h1>
    <hr/>
    <p>{{ $contents }}</p>
</body>
</html>