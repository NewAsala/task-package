/**
 * it gathers the data from every input field in the form and inserts it into a JSON object
 */
export function sendFormData() {
	
	document.getElementById('complex_condition_data').value = JSON.stringify(getConditionsData());
	document.getElementById('action_data').value = JSON.stringify(getActionsData());

	/**
	 * it fetches that data from the complex-condtion table
	 * @returns {Array} Array of conditions data to be sent to backend
	 */
	function getConditionsData() {
		let arr = [];
		let tableBody = document.getElementById('table-body');
		for (let row of tableBody.rows) {
			let obj = {};
		
			for (let cell of row.cells) {
				if (cell.firstElementChild.tagName != 'BUTTON') {
					obj[cell.firstElementChild.name] = cell.firstElementChild.value;
				}	
			}
		
			arr.push(obj);
		}

		return arr;
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
/*
	now this function might be the same as the one in the dynamic_function folder, but things are so cluttered in the listener.blade.php that i'm afraid i can't refactor code beyond this limit
*/
