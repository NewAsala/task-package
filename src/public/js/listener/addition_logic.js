import * as globals from "./globals.js";
import * as utility from "../utils/utils.js";

/**
* adds a new attribute row to the complex condition table.
* @param {Boolean} resetTable - A boolean to indicate whether the user changed the model name or not
* @returns {void} nothing
*/
export function addAttribute(resetTable) {

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
	
	let tr = utility.createDOMElement("tr", {
		className: 'position-relative' //necessary for the right positioning of css counters.	
	});

	tr.append(...populateTableRow());

	tableBody.append(tr);

	if (tableBodyIsMissing) {
		let table = document.getElementById("complex-condition-table");
		table.append(tableBody);
	}
}
/**
 * adds a new action group
 */
export function addAction() { 
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
		options: globals.actionOptions,
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
			url: globals.BASE_URL + 'getParameter',
			type: 'POST',
			data: {
				functionName: action,
				// "_token": "{{ csrf_token() }}"
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

/**
* populates a new row in the complex condition table with proper td elements
* @returns {Array} array of td elements that belong to the row
*/
export function populateTableRow() {
	// initially: create 7 td elements and increase the number of rows of the complex condition table: 
	// 1: type of the condition, i.e. whether the user wants to combine two conditions or insert a new condition
	// 2: select tag for Suffix
	// 3: select tag for attribute names (the Condition column)
	// 4: operator select tag for the attribute
	// 5: user input for the second attribute (the Condition / User input column)
	// 6: select tag for Prefix
	// 7: close button

	
	// how hidden condition select tags work. each select tag contains every number row in the complex condition table except its own number
	
	let complexConditionTable = document.querySelector('#complex-condition-table');
	let numOfRows = complexConditionTable.rows.length;

	// push the new row number. the reason i'm not pushing numOfRows + 1 is that numOfRows already contains an additional row -- the one that contains th elements.
	globals.complexConditionRows.push(numOfRows);

	// initialization
	const logicalOperatorsForPrefix = ["(", ")", "AND", "OR", "- (NONE)"];
	const logicalOperatorsForSuffix = [")", "(", "AND", "OR", "- (NONE)"]; // it's more stylish to put the closing parenthesis first.
	const arrayOfTDs = [];

	for (let i = 0; i < 7; i++) {
		arrayOfTDs.push(utility.createDOMElement("td"));
	}

	// 1:
	arrayOfTDs[0].append(...generateConditionTypeInputs(numOfRows));

	// 2:
	let prefixSelectTag = utility.createSelect({
		name: 'prefix',
		className: 'custom-select',
		options: logicalOperatorsForPrefix
	})

	arrayOfTDs[1].append(prefixSelectTag);

	// 3:
	let attributeSelectTag = utility.createSelect({
		name: 'attribute',
		className: 'custom-select simple-condition'
	})

	for (let attribute of globals.attributeOptions) {
		attributeSelectTag.append(utility.createDOMElement('option', {
			text: attribute[0],
			value: attribute[0]
		}))
	}

	arrayOfTDs[2].append(attributeSelectTag);

	// 4: 
	let operatorSelectTag = utility.createSelect({
		name: 'operator',
		className: 'custom-select simple-condition'
	})

	populateOptionsTag(operatorSelectTag);
	attributeSelectTag.addEventListener('change', function(event) {
		populateOptionsTag(operatorSelectTag);
	})

	arrayOfTDs[3].append(operatorSelectTag);

	// 5:
	let input = utility.createDOMElement("input", {
		placeholder: "Value: ",
		className: 'form-control simple-condition',
		name: 'user_input'
	});

	arrayOfTDs[4].append(input);

	// 6:
	let suffixSelectTag = utility.createSelect({
		name: 'suffix',
		className: 'custom-select',
		options: logicalOperatorsForSuffix,
	})

	arrayOfTDs[5].append(suffixSelectTag);


	// 7:
	let closeButton = utility.createDOMElement("button", {
		value: 'close',
		innerHTML: '<strong>x</strong>',
		className: "btn",
		onclick(event) {
			// removing this will cause the form to be submitted when the button is pressed.
			event.preventDefault();
		},
	});

	arrayOfTDs[6].append(closeButton);
	arrayOfTDs[6].onclick = function(event) {
		// this is the tr element that contains the button inside the td
		arrayOfTDs[6].parentElement.remove();
		// pops that last number in the arrow because the row is now removed
		globals.complexConditionRows.pop();

		for (let selectTag of complexConditionTable.querySelectorAll('.combined-condition')) {
			updateOptions(selectTag);
		}
	}

	appendHiddenTags(arrayOfTDs, numOfRows);

	// each time you add a new attribute i want all combined-condition select tags to be updated. THIS EXCLUDES THE SELECT TAG FOR THE OPERATOR IN BETWEEN THE TWO CONDITIONS
	for (let selectTag of complexConditionTable.querySelectorAll('.combined-condition[name$="side"]')) {
		updateOptions(selectTag);
	}
	
	return arrayOfTDs;

	/**
	* this function is called whenever the attribute select tag is changed. it fetches the right operators from the globals.attributeOptions array. 
	*/
	function populateOptionsTag(select) {
		select.innerHTML = "";

		let index = attributeSelectTag.selectedIndex;
		for (let operator of globals.attributeOptions[index][1]) {
			select.append(utility.createDOMElement('option', {
				text: operator,
				value: utility.optionsMap.has(operator) ? utility.optionsMap.get(operator) : operator,
			}))
		}

		let additionalOptions = ["AND", "OR", "NOT"];

		select.append(
			...additionalOptions.map(option => utility.createDOMElement('option', {
				text: option,
				value: utility.optionsMap.has(option) ? utility.optionsMap.get(option) : option,
			}))
		)
		// example: id != 5 (NOT)
		// i also added AND and OR in case support of bitwise operations is required
	}
}

/**
 * generates two input fields so the user can specify the condition's type. these two input fields are appended to the beginning of each row in the complex condition table
 * @param {number} rowID - dynamic number to be assigned to the name/id property of the input fields.
 */
function generateConditionTypeInputs(rowID) {
	let simpleConditionDiv = utility.createDOMElement('div', {
		className: 'form-check table-condition-type'
	})

	let simpleConditionInput = utility.createDOMElement('input', {
		type: 'radio',
		className: 'form-check-input',
		checked: 'checked',
		name: 'input_group' + rowID,
		id: 'simple_input_group' + rowID,
	})

	simpleConditionInput.dataset.id = 'simple_condition';
	
	let simpleConditionLabel = utility.createDOMElement('label', {
		textContent: 'Simple Input',
		className: 'form-check-label',
	})
	simpleConditionLabel.setAttribute('for', 'simple_input_group' + rowID)


	simpleConditionDiv.append(simpleConditionInput);
	simpleConditionDiv.append(simpleConditionLabel);

	let combinedConditionDiv = utility.createDOMElement('div', {
		className: 'form-check table-condition-type'
	})

	let combinedConditionInput = utility.createDOMElement('input', {
		type: 'radio',
		className: 'form-check-input',
		name: 'input_group' + rowID ,
		id: 'complex_input_group' + rowID
	})

	combinedConditionInput.dataset.id = 'combined_condition';
	
	let combinedConditionLabel = utility.createDOMElement('label', {
		textContent: 'Combined Condition',
		className: 'form-check-label',
	})
	combinedConditionLabel.setAttribute('for', 'complex_input_group' + rowID);

	combinedConditionDiv.append(combinedConditionInput)
	combinedConditionDiv.append(combinedConditionLabel)

	return [simpleConditionDiv, combinedConditionDiv];
}

/**
 * updates select tags with values from the global complexConditionRows array
 * @param {HTMLSelectElement} selectTag - the select tag which the function will update
 */
function updateOptions(selectTag) {
	selectTag.innerHTML = '';
	let parentRowIndex = selectTag.closest('tr').rowIndex; // the index that i don't want to be included in the select tag
	let suitableOptions = globals.complexConditionRows.filter(option => option != parentRowIndex);

	selectTag.append(
		...suitableOptions.map(option => utility.createDOMElement('option', {
			text: option,
			value: option
		}))
	)
}

/**
 * appends the hidden tags responsible for combined conditions 
 * @param {Array} arrayOfTDs - the array into which the function will append the hidden tags
 */
function appendHiddenTags(arrayOfTDs, numOfRows) {
	// the three select tags below are hidden and only visible when the user checks the combined condition input

	// i don't need the number of the row that contains this select tag. in our case it happens to be numOfRows. why? because it already adds an additional row -- the one with th elements.
	let availableConditions = globals.complexConditionRows.filter(option => {
		return option != numOfRows;
	}); 

	// left-hand side condition
	let combinedConditionsSelectTagL = utility.createSelect({
		name: 'combined_condition_left_side',
		className: 'custom-select combined-condition',
		options: availableConditions
	})
	
	// right-hand side condition. only the name property is different from the left-hand side select tag
	let combinedConditionsSelectTagR = utility.createSelect({
		name: 'combined_condition_right_side',
		className: 'custom-select combined-condition',
		options: availableConditions
	})
		
	let hiddenOperatorSelectTag = utility.createSelect({
		name: 'combined_condition_operator',
		className: 'custom-select combined-condition',
		options: ['AND', 'OR']
	})

	arrayOfTDs[2].append(combinedConditionsSelectTagL)
	arrayOfTDs[3].append(hiddenOperatorSelectTag);
	arrayOfTDs[4].append(combinedConditionsSelectTagR);
}

/**
 * TODO: When one row references another row in the complex condition table, the other row cannot reference the first. Unless the user is an expert,  Not doing so will result in endless recursion.
 */