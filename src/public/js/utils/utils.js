/**
*	model names are received from the ajax request as file paths. this function extracts the last item in these paths and assigns it to the right option tag
*/
export function fixModelNames() {
	Array.from(document.getElementsByClassName('option')).forEach(option => {
		// value is this: \Modules\TMS\Entities\TasksList. it's a path

		let arr = option.value.split("\\"); //we want the last token in this string
		option.textContent = arr[arr.length - 1];
	})
}

/**
* reusable function to create options for select tags
*/
export function createOption(text, value) {
	let option = document.createElement("option");
	option.text = text;
	option.value = value;
	
	return option;
}

export function createSelect({name, className, options, onchange}) {
	let select = document.createElement("select");
	select.name = name;
	select.className = className;

	if (options) {
		select.append(...options.map(item => createOption(item, item)));
	}

	return select;
}

/**
 * this function is just syntactic sugar for the original document.createElement function
 * @param {string} name name of the DON element to be created and returned
 * @param {Object} options an object of options for the element
 */
export function createDOMElement(name, options) {
	let element = document.createElement(name);
	if (options) {
		Object.entries(options).forEach(([option, value]) => {
			element[option] = value;
		});
	}

	return element;
}

/**
 * TODO: refactor createOption and createSelect
 */

