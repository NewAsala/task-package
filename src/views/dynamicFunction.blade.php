@extends('layouts.app')

@section('content')

    <h1>Add new functions</h1>

    <div class="container-fluid">
		<div class="row">
			<div class="col-sm-8 px-0 px-sm-3">
				<form  id="functionForm" action="{{route('func')}}" method="post">
					@csrf
					<div class="row function-group">
						<div class="col">
							<label for="func">Function name: </label>
							<input class = "form-control" type="text" name="func" placeholder="Function name: ">
						</div>

						<div class="col">
							<label for="returnFun">Return type: </label>
							<input class = "form-control" type="text" name="returnFun" placeholder="Return type: ">
						</div>

						<div class="col d-flex align-items-center justify-content-center">
							<button class="btn btn-dark" id = "addParameter">
								Add Parameter
							</button>
						</div>
					</div>
	
					<table id="functionTable" class="table">
						<thead>
							<tr>
								<th>Parameter name</th>
								<th>Parameter type</th>
								<th>Example</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							{{-- this is where parameters go --}}
						</tbody>
					</table>
					
					<button type="submit" class="btn btn-dark">Submit Function</button>
					<input type="hidden" name="function_data">
				</form>
			</div>
		</div>	
	</div>
<script src ="./assets/js/dynamic_function/main.js" type="module"></script>
@endsection

{{-- i'm using css counters to achieve proper numbering of rows. check out app.css --}}