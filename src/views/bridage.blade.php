<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Test Bridage</title>
</head>

    <body>

    <h1> Hiiii</h1>

    <form action="{{route('bridage')}}" method="post">
        @csrf

        <input type="text" name="name" placeholder="your name">
        <input type="email" name="email" placeholder="your email">
        <input name="msg" placeholder="msg">
        <input type="submit" value="Submit">

    </form>
    </body>

</html>