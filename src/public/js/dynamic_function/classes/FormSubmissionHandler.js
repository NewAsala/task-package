export default class EventHandler {

	_form; //not that this parameter really matters, no. i'm just keeping it for future maintenance
	
	/**
	 * @param {HTMLFormElement} form 
	 */
	constructor(form) {
		this._form = form;
	}

	/**
	 * handles the event when the form is submitted
	 * @param {Event} event 
	 */
	handleEvent(event) {
		this._sendFormData();
	}

	/**
	 * gathers the required data to be sent to backend and puts them in the hidden input field with the name 'function_data'
	 */
	_sendFormData() {
		const functionGroup = document.querySelector('.function-group');

		let jsonData = {}; //the final json object to be stringified
		// array of parameters to be sent to backend. 
		jsonData.parameters = [];

		// function name and return type
		for (let input of functionGroup.querySelectorAll('input')) {
			jsonData[input.name] = input.value;	
		}

		let tableBody = functionTable.querySelector('tbody');

		// parameter name, type, example
		for (let row of tableBody.rows) {
			let parameterObj = {};
			for (let item of row.querySelectorAll('input, select')) {
				parameterObj[item.name] = item.value;
			}

			jsonData.parameters.push(parameterObj);
		}

		jsonData = JSON.stringify(jsonData);
		document.querySelector('input[name="function_data"]').value = jsonData;
	}
}