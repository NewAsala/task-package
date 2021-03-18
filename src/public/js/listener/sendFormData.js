import * as globals from "./globals.js";

/**
 * it gathers the data from every input field in the form and inserts it into a JSON object
 */
export function sendFormData() {
	let finalConditionInput = document.getElementById("finalConditionRowNumber");

	// the second operand checks if the user entered a number row that doesn't belong to the table. parseInt is required because the Array 'includes' function uses strict comparison
	if (finalConditionInput.value == '' || !(globals.complexConditionRows.includes(parseInt(finalConditionInput.value)))) {
		finalConditionInput.classList.add('missing-number-error');
		finalConditionInput.scrollIntoView(false); //refer to mdn docs to understand this boolean parameter
		finalConditionInput.addEventListener('input', removeErrorClass);
		$('.error-message')[0].innerHTML = 'Please enter a valid number.';


		function removeErrorClass(event) {
			event.target.classList.remove('missing-number-error');
			event.target.removeEventListener('input', removeErrorClass);
			$('.error-message')[0].innerHTML = '';
		}
		return false;
	}

	document.getElementById('complex_condition_data').value = JSON.stringify(getConditionsData());
	document.getElementById('action_data').value = JSON.stringify(getActionsData());
	return false;
	
	/**
	 * fetches data from the complex-condtion table
	 * @returns {Array} Array of conditions data to be sent to backend
	 */
	function getConditionsData() {
		let arr = [];
		let tableBody = document.getElementById('table-body');
		for (let row of tableBody.rows) {

			// get condition type to know what we need to query for. either the simple condition radio box is checked or the one for the combined condition is

			let conditionIsSimple = row.querySelector('input[data-id="simple_condition"]').checked;
			let className = conditionIsSimple ? 'simple-condition' : 'combined-condition'; // we're first checking the 'checked' property of the simple condition radio button
			let selector = `select[name="prefix"], select[name="suffix"], input.${className}, select.${className}`; //selecting the right input and select tags including the prefix and the suffix

			let obj = {};
			obj["condition_type"] = conditionIsSimple ? "simple": "combined";
			for (let cell of row.querySelectorAll(selector)) {
				obj[cell.name] = cell.value;
			}
			arr.push(obj);
		}

		// CODE TO MAP NUMBERS IN THE ARRAY TO THEIR RESPECTIVE CONDITIONS. THIS FUNCTION IS CALLED ONLY AFTER THE ABOVE LINES ARE EXECUTED
		for (let i = 0; i < arr.length; i++) {
			if (arr[i]["condition_type"] == 'combined') {
				arr[i]["combined_condition_left_side"] = traverseArray(arr, arr[i]["combined_condition_left_side"]);
				arr[i]["combined_condition_right_side"] = traverseArray(arr, arr[i]["combined_condition_right_side"]);

				// REQUIRED to prevent endless recursion. check chrome's debugger for an in-depth exploration of this case.
				arr[i]["condition_type"] = 'simple';
			}
		}
		
		return arr[finalConditionInput.value - 1];
	}

	/**
	 * recursive function to replace condition numbers with the objects that they are referring to
	 * @param {number} index - index of the object in the conditions array
	 */
	function traverseArray(arr, index) {
		index--; //array indices start at 0.
		if (arr[index]["condition_type"] == 'simple') return arr[index];

		arr[index]["combined_condition_left_side"] = traverseArray(arr, arr[index]["combined_condition_left_side"]);
		arr[index]["combined_condition_right_side"] = traverseArray(arr, arr[index]["combined_condition_right_side"]);
	}

	/**
	 * it fetches that data from the actions container
	 * @returns {Array} Array of actions data to be sent to backend
	 */
	function getActionsData() {
		let arr = [];

		let actionGroups = document.querySelectorAll('.action-group');
		for (let group of actionGroups) {
			let obj = {};
			obj['functionName'] = group.querySelector('select[name="action"]').value;
			obj['parameters'] = {};

			let inputs = group.querySelectorAll('input');

			for (let input of inputs) {
				obj.parameters[input.name] = input.value;
			}

			arr.push(obj);
		}

		return arr;
	}
}
