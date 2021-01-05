@extends('layouts.master')

@section('content')

    <h1> Set Function With Parameter </h1>

    <form  id="form" action="{{route('func')}}" method="post">
    @csrf

        <input type="text" name="func" placeholder="function name">

        <input type="text" name="returnFun" placeholder="return type">

        <input type="number" id="quantity" class="button" name="quantity" value="0">
        
        <table id="myTable">
        <tr>
            <td>parameter name</td>
            <td>parameter type</td>
            <td>parameter example</td>
        </tr>
        
        </table>
        <br>
        
        <input type="submit" value="Submit">        

    </form>
    </body>

</html>

<script>

    var numParameter = document.getElementById("quantity");
    var form = document.getElementById("form");

    let num = 0 ;
    $(".button").on("click", function() {

        var myTable = document.getElementById("myTable");
        var tr = document.createElement("tr");
        var tdInput = document.createElement("td");
        var tdType = document.createElement("td");
        var tdExample = document.createElement("td");
        
        var input = document.createElement("input");
        var type = document.createElement("input");
        var example = document.createElement("input");

        input.name = "parameter["+num+"][name]";
        type.name = "parameter["+num+"][type]";
        example.name = "parameter["+num+"][example]";

        myTable.appendChild(tr);
        tr.appendChild(tdInput);
        tr.appendChild(tdType);
        tr.appendChild(tdExample);

        tdInput.appendChild(input);
        tdType.appendChild(type);
        tdExample.appendChild(example);
        num++;

    });

</script>
@endsection