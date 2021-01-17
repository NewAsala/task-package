// the import path is this: public/assets/js/dynamic_function/utils/utils.js
import * as utility from "../../utils/utils.js";

export default class ParameterRow {
	/* Array of HTMLTableCellElement */
	_arrayOfTDs = [];

	constructor() {
		this._initializeTDs();
	}

	/**
	 * initializes the array of td elements with an input field for the parameter name,
	 * a select tag for the parameter's type,
	 * another input field for the parameter example,
	 *  and a close button 
	 */
	_initializeTDs() {

		for (let i = 0; i < 4; i++) {
			this._arrayOfTDs.push(utility.createDOMElement('td'));
		}

		this._arrayOfTDs[0].append(utility.createDOMElement('input', {
			type: 'text',
			className: 'form-control',
			name: 'name'
		}))

		this._arrayOfTDs[1].append(utility.createSelect({
			className: 'custom-select',
			options: ['integer', 'boolean', 'double', 'float', 'Object', 'Array'],
			name: 'type'
		}));

		this._arrayOfTDs[2].append(utility.createDOMElement('input', {
			type: 'text',
			className: 'form-control',
			name: 'example'
		}))

		this._arrayOfTDs[3].append(utility.createDOMElement('button', {
			value: 'close',
			innerHTML: '<strong>x</strong>',
			className: "btn",
			onclick(event) {
				event.preventDefault();
			}
		}))

		this._arrayOfTDs[3].onclick = (event) => {
			this._arrayOfTDs[3].parentElement.remove();
		}
	}

	/**
	 * returns the array of tds that was created in the constructor
	 */
	getCells() {
		return this._arrayOfTDs;
	}
}