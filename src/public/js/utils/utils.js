/**
*	model names are received from the ajax request as file paths. this function extracts the last item in these paths and assigns it to the right option tag
*/
export function fixModelNames() {
	Array.from(document.getElementsByClassName('option')).forEach(option => {
		// value is this: \Modules\TMS\Entities\TasksList. it's a path.

		let arr = option.value.split("\\"); //we want the last token in this string
		option.textContent = arr[arr.length - 1];
	})
}


/* i opted out of using createDOMElement for select tags because appending options to it later would be a pain in the back. 
this one saves you a few keystrokes hehe */

export function createSelect({name = '', className, options, onchange}) {
	let select = document.createElement("select");
	select.name = name;
	select.className = className;

	if (options) {
		select.append(...options.map(item => createDOMElement('option', {
			text: item,
			value: item
		})));
	}
	return select;
}

export function createInputWithDatalist({name, className, options}) {
	let input = createDOMElement('input', {
		className,
		name,
	});

	input.setAttribute('list', name);

	let datalist = createDOMElement('datalist', {
		id: name,
	});

	options.forEach(option => {
		datalist.append(createDOMElement('option', {
			text: option,
			value: option
		}))
	})
	
	// useful for destructuring
	return [input, datalist];
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