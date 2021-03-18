@extends('layouts.app')

@section('content')

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-9 px-0 px-sm-3">
			<form id = "form" action="{{route('models')}}" method="post">
			@csrf
				<div class="row my-2">
					<div class="col">
						<label for="events">Choose The Event:</label>

						<select class="form-control custom-select" name="events" id = "events">
							<option value="created">created</option>
							<option value="retrieved">retrieved</option>
							<option value="creating">creating</option>
							<option value="updating">updating</option>
							<option value="updated">updated</option>
							<option value="saving">saving</option>
							<option value="saved">saved</option>
							<option value="restoring">restoring</option>
							<option value="restored">restored</option>
							<option value="deleting">deleting</option>
							<option value="updated">updated</option>
							<option value="deleted">deleted</option>
							<option value="forceDeleted">forceDeleted</option>
						</select>
					</div>

					<div class="col">
						<label for="models">The Models:</label>

						<select name="models" id="models" class="custom-select">
							@foreach($models as $model)
								<option class="option" value="{{$model}}"></option>
							@endforeach

						</select>
					</div>
				</div>

				<div class="row my-2">
					<div class="col">
						<legend>Conditions</legend>

						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="condition_type" id="simple_condition" value="simple_condition" checked>
							<label class="form-check-label" for="simple_condition">Simple</label>
						</div>

						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="condition_type" id="complex_condition" value="complex_condition">
							<label class="form-check-label" for="complex_condition">Complex</label>
						</div>

					</div>
				</div>

				<div class="row my-2">
					<div class="col">
						<input class = "form-control" type="text" name="simple_condition_data" id="simple_condition_data" placeholder="Enter the condition's expression: ">
						<div class="complex-condition-container">
							<table id = "complex-condition-table" class="table">
								<thead>
									<tr>
										<th>Type</th>
										<th>Prefix</th>
										<th>Condition</th>
										<th>Operator</th>
										<th>Condition / User input</th>
										<th>Suffix</th>
										<th></th>
										{{-- DO NOT REMOVE THIS EMPTY TH ELEMENT. THIS ENSURE PROPER SPACING FOR THE CLOSE BUTTON --}}
									</tr>
								</thead>

								<tbody id = "table-body">
								{{-- td elements are supposed to go here --}}
								{{-- row numbering is implemented using css counters. check out app.css for more information. make sure to support all browsers though, as chrome and edge do not support relative positioning for table rows --}}
								</tbody>
							</table>

							<div class="final-condition mb-3">
								<label for="">Enter the number of the row for the final condition: </label>
								<input type="text"
								 id = "finalConditionRowNumber"
								 placeholder="E.g. 2"
								 class="form-control"
								>
								<span class="error-message" style="color: red;"></span>
							</div>

							<div class="expected-output">
								<label for="">Expected Output: </label>
								<input type="text"
								 placeholder="Press Save Conditions to show the expected condition"
								 class="form-control" 
								 readonly
								 >
							</div>

							<div class="buttons-container mb-2">
								<button id = "addAttribute" class="btn btn-dark">Add Attribute</button>
								<button id = "saveConditions" class="btn btn-dark ml-2" style="background-color: #6c92b9; border-color: silver">Save Conditions</button>
							</div>
						</div>
					</div>
				</div>

				<div class="row my-2">
					<div class="col">
						<label for="users">The Users:</label>
						<select name="users" id="users" class="custom-select">
							<option class="userOPtion" id="user" value="">Users</option>
						</select>
					</div>
				</div>
				<div class="row my-2">
					<div class="col">
						<label for="status">Condition status</label>
						<select class="custom-select" name="status" id="status">
							<option value="1">Activated</option>
							<option value="0">Not Activated</option>
						</select>
					</div>
				</div>

				<div class="row my-2">
					<div class="col">
						<legend>Actions</legend>
						<div class="actions-container">
							{{-- new actions are supposed to go here --}}
						</div>

						<button id = "addAction" class ="btn btn-dark">Add Action</button>
					</div>
				</div>
				
				<div class="d-hidden">
					@foreach($actions as $action)
						<div class="hidden-action-data" data-action-name="{{$action->name}}"></div>
					@endforeach
				</div>

				{{-- this is what will eventually be sent to backend regarding conditions and actions data --}}
				<input type="hidden" id = "complex_condition_data" name="complex_condition_data">
				<input type="hidden" name="action_data" id = "action_data">
				
				<button type="submit" class="btn btn-primary mt-3">Submit</button>
			</form>
		</div>
	</div>
</div>
 
<script type="module" src="./assets/js/listener/main.js">   
</script>
{{-- to overcome the unknown status error for ajax requests, 
	check out this link: https://stackoverflow.com/questions/46466167/laravel-5-5-ajax-call-419-unknown-status
	the csrf token wasn't placed in the meta tag, so i fetched it from localStorage in the script above.
--}}
@endsection