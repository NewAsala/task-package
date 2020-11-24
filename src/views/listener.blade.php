<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Test Bridage</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        body {
            color: #566787;
            background: #f5f5f5;
            font-family: 'Varela Round', sans-serif;
            font-size: 13px;
        }
    </style>
</head>

    <body>

    <h1> Hiiii</h1>

    <form action="{{route('models')}}" method="post">
    @csrf

        <label for="models">The Models:</label>

        <select name="models" id="models">
            @foreach($models as $model)
                <option class="option" id="volvo" value="{{$model}}"></option>
            @endforeach
        
        </select>

        
        <label for="events">Choose The Event:</label>

        <select name="events" id="events">
        <option value="create">create</option>
        <option value="update">update</option>
        <option value="delete">delete</option>
        </select>
    
        <div class="row">

        <div class="col-6">

        <input type="text" name="title" placeholder="task name">
        <input type="text" name="group" placeholder="group name">

        </div>
        
        <input type="submit" value="Submit">        

    </form>
    </div>
    </body>

</html>

 
<script>  

for(var i=0 ;i< document.getElementsByClassName('option').length ;i++){
    
    var op = document.getElementsByClassName('option')[i].value;
    var result = op.split("\\App\\Models\\");
    document.getElementsByClassName('option')[i].value = result[1];
    document.getElementsByClassName('option')[i].innerHTML = result[1];
    
}
   
</script>