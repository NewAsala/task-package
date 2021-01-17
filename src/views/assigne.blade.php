@extends('layouts.app')

@section('content')

    <h1> Create Assignee</h1>

    <form action="{{route('addAssigne')}}" method="post">
    @csrf
            <!-- @foreach($roles as $role)
                <input class="option" name="{{$role->name}}" type ="checkbox" value="{{$role->name}}">
                <label for="role">{{$role->name}}</label><br>
            @endforeach -->
                
            <div class="">
                <label><strong>Select Role :</strong></label><br/>
                    <select class="selectpicker" multiple data-live-search="true" name="name[]">
                    @foreach($roles as $role)
                        <option name="{{$role->name}}" id="{{$role->id}}" value="{{$role->name}}">{{$role->name}}</option>
                    @endforeach
                </select>
            </div>

        <input type="submit" value="Submit">        

    </form>
    </div>
    </body>

</html>

 
<script>  
// $('#roles').select2();

// $('#roles').on('select2:opening select2:closing', function( event ) {
//     var $searchfield = $(this).parent().find('.select2-search__field');
//     $searchfield.prop('disabled', true);
// });

</script>
@endsection