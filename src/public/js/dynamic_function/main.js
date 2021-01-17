import * as utility from "../utils/utils.js"; //required for the createDOMElement and createSelect helper functions
import ParameterRow from "./classes/ParameterRow.js"; //instead of populating the parameter's row in this file, i created a new class to abstract away the population details
import FormEventHandler from "./classes/FormSubmissionHandler.js";

const paramsButton = document.querySelector('#addParameter'); //Add Parameter button
const functionTable = document.querySelector('#functionTable');
const functionsForm = document.querySelector('#functionForm')

const formEventHandler = new FormEventHandler(functionsForm); //when the user submits the form, the event handler gathers all the data from input fields and populates the hidden 'function_data' input field

main();

// dunno if i should add this to the ParameterRow class.
function addNewParameter() {
	let tableBody = functionTable.querySelector('tbody');
	let tableBodyIsMissing = false;

	if (!tableBody) {
		tableBody = utility.createDOMElement("tbody");
		tableBodyIsMissing = true;
	}

	let tr = utility.createDOMElement("tr", {
		className: 'position-relative'
	});

	tr.append(...new ParameterRow().getCells());
	tableBody.append(tr);

	if (tableBodyIsMissing) {
		functionTable.append(tableBody);
	}
}

function registerEventListeners() {
	paramsButton.addEventListener('click', (event) => {
		event.preventDefault();
		addNewParameter();
	})

	functionsForm.addEventListener('submit', formEventHandler)
}

function main() {
	addNewParameter();	
	registerEventListeners();
}