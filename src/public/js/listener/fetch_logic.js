import {addAction, addAttribute} from './addition_logic.js';
import * as globals from './globals.js';
import * as utility from '../utils/utils.js';

/**
* this is called when the document is ready. it only fetches users when the page loads
* @returns {void} 
*/
export function fetchUsers() {
	$.ajax({
		url: globals.BASE_URL + "assignes",
		success: function(result, loc) {
			let userOptions = [];

			$.each(result, function(i, val){

				$.each(val, (i, value) => {
					userOptions.push(utility.createDOMElement('option', {
						text: value.username,
						value: value.username
					}))
				});
			});

			globals.usersSelectTag.append(...userOptions);
		}
	});
}

export function fetchAttributesForModels() {
	// index of the chosen model
	// defaults to 0 if nothing was chosen
	let index = globals.modelsSelectTag.selectedIndex;
	let path = globals.modelsSelectTag.options[index].value;
	// don't try to change the path variable. backend expects a variable with the exact name

	$.ajax({
		url: globals.BASE_URL + "getAttribute",
		type: "POST",
		data: { 
			path,
			//"_token": "{{ csrf_token() }}"
		},

		success: function(result, loc) {
			// this resets the global variable in the script
			globals.attributeOptions.length = 0;
			
			// the result is an object. each key is the name of the attribute and the value of the key is the array of valid operators

			$.each(result, (key, val) => {
				globals.attributeOptions.push([key, val])
			})

			addAttribute(true);
		},
	});
}

export function fetchActions() {
	// there's no way to fetch action names using ajax, so they're sent as the $actions variable in the blade. i'm populating a hidden div with all the data needed for the names
	for (let div of document.querySelectorAll('.hidden-action-data')) {
		globals.actionOptions.push(div.dataset.actionName);
	}
	addAction();
}