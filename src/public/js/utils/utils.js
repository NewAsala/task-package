//Working with logical operators that are closer to php is easier for Assala, which is why I created this map.
export const optionsMap = new Map(
	[
		['AND', '&&'],
		['OR', '||'],
		['NOT', '!']
	]
)

/**
*	model names are received from the ajax request as file paths. this function extracts the last item in these paths and assigns it to the right option tag
*/
export function fixModelNames() {
	for (let option of document.querySelectorAll('select[name="models"] option')) {
		// value is this: \Modules\TMS\Entities\TasksList. it's a path.
		let arr = option.value.split("\\"); //we want the last token in this string
		option.textContent = arr[arr.length - 1];
	}
}


/* I opted out of using createDOMElement for select tags because appending options to it later would be a pain in the back. 
this one saves you a few keystrokes hehe */

export function createSelect({name = '', className, options, onchange}) {
	let select = document.createElement("select");
	select.name = name;
	select.className = className;

	if (options) {
		select.append(
			...options.map(item => {
				return createDOMElement('option', {
					text: item,
					value: optionsMap.has(item) ? optionsMap.get(item) : item,
				})
			})
		)
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
