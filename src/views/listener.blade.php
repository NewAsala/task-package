@extends('layouts.master')

@section('content')

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-8 px-0 px-sm-3">
			<form id = "form" action="{{route('models')}}" method="post">
			@csrf

				<div class="row">
					<div class="col">
						<label for="events">Choose The Event:</label>

						<select class="form-control custom-select" name="events" id = "events">
							<option value="create">create</option>
							<option value="update">update</option>
							<option value="delete">delete</option>
						</select>
					</div>

					<div class="col">
						<label for="models">The Models:</label>

						<select name="models" id="models" class="form-control custom-select">
							@foreach($models as $model)
								<option class="option" value="{{$model}}"></option>
							@endforeach

						</select>
					</div>
				</div>

				<div class="row mb-2">
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

				<div class="row">
					<div class="col">
						<input class = "form-control" type="text" name="simple_condition_data" id="simple_condition_data" placeholder="Enter the condition's expression: ">
						<table id = "complex-condition-table" class="table">
							<thead>
								<tr>
									<th>Prefix</th>
									<th>Condition</th>
									<th>Operator</th>
									<th>User input</th>
									<th>Suffix</th>
									<th></th>
								</tr>
							</thead>

							<tbody id = "table-body">
							{{-- td elements are supposed to go here --}}
							</tbody>
						</table>
						<div class="buttons-container mb-2">
							<button id = "addAttribute" class="btn btn-dark">Add Attribute</button>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col">
						<label for="users">The Users:</label>
						<select name="users" id="users" class="form-control custom-select">
							<option class="userOPtion" id="user" value="">Users</option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<label for="status">Condition status</label>
						<select class="form-control" name="status" id="status">
							<option value="1">Activated</option>
							<option value="0">Not Activated</option>
						</select>
					</div>
				</div>

				<div class="row">
					<div class="col">
						<legend>Actions</legend>
						<div class="actions-container">

						</div>

						<button id = "addAction" class ="btn btn-dark">Add Action</button>
					</div>
				</div>
				
				<div class="d-hidden">
					@foreach($actions as $action)
						<div class="hidden-action-data" data-action-name="{{$action->name}}"></div>
					@endforeach
				</div>
				<input type="hidden" id = "complex_condition_data" name="complex_condition_data">
				<input type="hidden" name="action_data" id = "action_data">
				<button type="submit" class="btn btn-primary mt-3">Submit</button>
			</form>
		</div>
	</div>
</div>
 
<script type="module">   
// these files are in the public/assets/utils folder
import * as utility from "./assets/js/utils/utils.js";
import {sendFormData} from "./assets/js/utils/sendFormData.js";

const BASE_URL = "http://localhost:8084/";
const usersSelectTag = document.getElementById("users");
const modelsSelectTag = document.getElementById('models');

let actionOptions = []; //this is the array of actions that is received from backend
let attributeOptions = []; //this is the same as the attributeOptions array in the ajax request.

/*
	ADDITION FUNCTIONS START HERE
*/

/**
* adds a new attribute to the complex condition table.
* @param {Boolean} resetTable - A boolean to indicate whether the user changed the model name or not
* @returns {void} nothing
*/
function addAttribute(resetTable) {

	let tableBody = document.getElementById("table-body");

	// if the tbody element is missing, i will create a new one and append it to the table.
	let tableBodyIsMissing = false;

	if (!tableBody) {
		tableBody = utility.createDOMElement("tbody", {
			id: "table-body"
		});
		
		tableBodyIsMissing = true;
	}

	// when the user selects a model then changes it, the new attributes will be appended to the table. 
	// therefore, we have to empty the table before each change. this is when the resetTable boolean comes into play

	if (resetTable) {
		tableBody.innerHTML = "";
	}
	
	let tr = utility.createDOMElement("tr");
	tr.append(...populateTableRow());

	tableBody.append(tr);

	if (tableBodyIsMissing) {
		let table = document.getElementById("complex-condition-table");
		table.append(tableBody);
	}
}

function addAction() { 
	/*
	the structure of the actions container is something like this:
	div.actions-container
		.action-group
			select tags, inputs, and labels

		.action-group
			select tags, inputs, and labels

	.actions-container end

	*/

	let actionsContainer = document.querySelector('.actions-container');
	let actionGroup = utility.createDOMElement("div", {
		className: 'action-group'
	});
	
	let actionParameters = utility.createDOMElement("div", {
		className: 'row row-cols-2',
	});

	let actionNameLabel = utility.createDOMElement("label", {
		textContent: 'Action name: ',
		for: 'action'
	})

	let actionSelectTag = utility.createSelect({
		name: 'action',
		className: 'custom-select',
		options: actionOptions,
	});

	/* when this select tag changes, i fetch the suitable parameters for the function name that the user chose
		also, this function is defined below
	*/
	populateParameterFields();
	actionSelectTag.onchange = populateParameterFields;

	actionGroup.append(actionNameLabel, actionSelectTag, actionParameters);
	actionsContainer.append(actionGroup);

	function populateParameterFields() {
		// it's necessary to change all the input fields when the choice changes
		actionParameters.innerHTML = '';
		let index = actionSelectTag.selectedIndex;
		let action = actionSelectTag[index].value;

		$.ajax({
			url: BASE_URL + 'getParameter',
			type: 'POST',
			data: {
				functionName: action,
				"_token": "{{ csrf_token() }}"
			},

			success(result, loc) {
				result = result[action];
			
				// you have to console.log the result to understand the structure of the loops here
				$.each(result, function(key, value) {
					// each value here is an array. i think you can access the number of parameters for the function from value.length ?
					$.each(value, function(key, value) {

						// create an input tag for every key in the resulting array
						let {name, type, example} = value;

						let formColumn = utility.createDOMElement('div', {
							className: 'col'
						});

						let label = utility.createDOMElement('label', {
							textContent: name
						});

						let input = utility.createDOMElement('input', {
							className: 'form-control',
							placeholder: `Example: ${example}`,
							name: name,
						});

						formColumn.append(label, input);

						actionParameters.append(formColumn);
					})
				})

				let closeButtonColumn = utility.createDOMElement('div', {
					className: 'col-12 mt-4',
				})

				let closeButton = utility.createDOMElement('button', {
					className: 'btn btn-danger',
					textContent: 'Remove Action',
					onclick(event) {
						event.preventDefault();
						event.target.closest('.action-group').remove();
					}
				})

				closeButtonColumn.append(closeButton);
				actionParameters.append(closeButtonColumn);
				actionParameters.insertAdjacentHTML("afterbegin", "<h3 class='col-12 mt-2'>Function Parameters: </h3>");
			}
		})
	}

}

/*
	ADDITION FUNCTIONS END HERE
*/

/**
*	this is used in the 'populateComplexConditionTable' function
*	the attribute param is a key-value array. the key is the attribute name and the value is the array of valid options
*/
function populateTableRow() {
	// initial: create 6 td elements: 
	// 1: select tag for Suffix
	// 2: select tag for attribute names
	// 3: operator select tag for the attribute
	// 4: user input for the second attribute
	// 5: select tag for Prefix
	// 6: close button

	// initialization.
	const logicalOperators = ["(", ")", "AND", "OR"];
	let arrayOfTDs = [];
	for (let i = 0; i < 6; i++) {
		arrayOfTDs.push(utility.createDOMElement("td"));
	}

	// 1:

	let prefixSelectTag = utility.createSelect({
		name: 'prefix',
		className: 'custom-select',
		options: logicalOperators
	})

	arrayOfTDs[0].append(prefixSelectTag);

	// 2:
	let attributeSelectTag = utility.createSelect({
		name: 'attribute',
		className: 'custom-select'
	})

	for (let attribute of attributeOptions) {
		attributeSelectTag.append(utility.createOption(attribute[0], attribute[0]))
	}

	arrayOfTDs[1].append(attributeSelectTag);

	// 3: 
	let operatorSelectTag = utility.createSelect({
		name: 'operator',
		className: 'custom-select'
	})

	populateOptionsTag(operatorSelectTag);
	attributeSelectTag.onchange = () => populateOptionsTag(operatorSelectTag);

	arrayOfTDs[2].append(operatorSelectTag);

	// 4:
	let input = utility.createDOMElement("input", {
		placeholder: "Value: ",
		className: 'form-control',
		name: 'user_input'
	});

	arrayOfTDs[3].append(input);

	// 5:
	let suffixSelectTag = utility.createSelect({
		name: 'suffix',
		className: 'custom-select',
		options: logicalOperators
	})

	arrayOfTDs[4].append(suffixSelectTag);

	// 6:
	let button = utility.createDOMElement("button", {
		value: 'close',
		innerHTML: '<strong>x</strong>',
		className: "btn",
		onclick(event) {
			// removing this will cause the form to be submitted when the button is pressed.
			event.preventDefault();
		},
		
	});

	arrayOfTDs[5].append(button);
	arrayOfTDs[5].onclick = function(event) {
		// this is the tr element that contains the button inside the td
		arrayOfTDs[5].parentElement.remove();
	}

	return arrayOfTDs;

	/**
	*	this function is called whenever the attribute select tag is changed. it fetches the right operators from the attributeOptions array. 
	*/
	function populateOptionsTag(select) {
		select.innerHTML = "";

		let index = attributeSelectTag.selectedIndex;
		for (let operator of attributeOptions[index][1]) {
			select.append(utility.createOption(operator, operator))
		}
	}
}

/**
*	when the model changes, a table is populated for the complex condition choice. now if a model doesn't have an attributes, this function shouldn't be called
*/
function populateComplexConditionTable() {
	addAttribute(true);
}

/*
FETCHING LOGIC STARTS HERE 
*/

/**
* this is called when the document is ready. it only fetches users when the page loads
* @returns {void} 
*/
function fetchUsers() {
	$.ajax({
		url: BASE_URL + "assignes",
		success: function(result, loc) {
			let userOptions = [];

			$.each(result, function(i, val){

				$.each(val, (i, value) => {
					userOptions.push(utility.createOption(value.name, value.name))
				});
			});

			usersSelectTag.append(...userOptions);
		}
	});
}

function fetchAttributesForModels() {
	// index of the chosen model
	// defaults to 0 if nothing was chosen
	let index = modelsSelectTag.selectedIndex;
	let path = modelsSelectTag.options[index].value;
	// don't try to change the path variable. backend expects a variable with the exact name

	$.ajax({
		url: BASE_URL + "getAttribute",
		type: "POST",
		data: { 
			path,
			"_token": "{{ csrf_token() }}"
		},

		success: function(result, loc){
			// this resets the global variable in the script
			attributeOptions = [];
			
			// the result is an object. each key is the name of the attribute and the value of the key is the array of valid operators

			$.each(result, (key, val) => {
				attributeOptions.push([key, val])
			})

			populateComplexConditionTable();

		},

	});
}

function fetchActions() {
	// there's no way to fetch action names using ajax, so they're sent as the $actions variable in the blade. i'm populating a hidden div with all the data needed for the names
	for (let div of document.querySelectorAll('.hidden-action-data')) {
		actionOptions.push(div.dataset.actionName);
	}
	addAction();
}

/*
FETCHING LOGIC ENDS HERE 
*/


// the script actually starts here. sorry for all the mess above
$(document).ready(function() {

	// INITIALIZATION LOGIC
	utility.fixModelNames();
	fetchUsers();
	fetchAttributesForModels();
	fetchActions();

	// EVENT LISTENERS SECTION
	$("#models").on('change', fetchAttributesForModels)
	$('#form').on('submit', sendFormData);
	$('#addAction').on('click', function(event) {
		event.preventDefault();
		addAction();
	});

	$("#addAttribute").on('click', function(event) {
		event.preventDefault();
		// refer to the function definition to understand the purpose of the false boolean
		addAttribute(false);
	});

	$('input[name="condition_type"').on('change', function(event) {
			if (event.target.id == 'simple_condition') {
				$('#simple_condition_data').fadeIn();
				$('#complex-condition-table').fadeOut();
				$('.buttons-container').fadeOut();
			}

			else {
				$('#simple_condition_data').fadeOut();
				$('#complex-condition-table').fadeIn()
				$('.buttons-container').fadeIn();
			}
	})
});

// this was used to overcome a CORS error and it's placed at the top of the script
// $.ajaxSetup({
// 	headers: {
// 		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
// 	}
// });
</script>
@endsection

