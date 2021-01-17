import { sendFormData } from "../utils/sendFormData.js";
import { addAction, addAttribute } from './addition_logic.js';
import { fetchAttributesForModels } from './fetch_logic.js';

export function registerEventListeners() {
	$("#models").on('change', fetchAttributesForModels);
	$('#form').on('submit', sendFormData);

	$('.buttons-container').on('click', function (event) {
		event.preventDefault();
		let id = event.target.id;

		if (id == 'addAttribute') {
			addAttribute(false); // refer to the function definition to understand the purpose of the false boolean
		}

		else if (id == 'saveConditions') {
			alert('pressed');
		}
	});

	$('#addAction').on('click', function (event) {
		event.preventDefault();
		addAction();
	});

	$('input[name="condition_type"').on('change', function (event) {
		if (event.target.id == 'simple_condition') {
			$('#simple_condition_data').fadeIn();
			$('.complex-condition-container').fadeOut();
		}

		else {
			$('#simple_condition_data').fadeOut();
			$('.complex-condition-container').fadeIn();
		}
	});

	$(document).on('click', '.table-condition-type input', function(event) {
		let parentRow = event.target.closest('tr');

		if (event.target.dataset.id == 'combined_condition') {
			$(parentRow).find(".combined-condition").fadeIn();
			$(parentRow).find(".combined-condition-operator").fadeIn();
			$(parentRow).find(".simple-condition").fadeOut();

		}

		else {
			$(parentRow).find(".simple-condition").fadeIn();
			$(parentRow).find(".combined-condition").fadeOut();
			$(parentRow).find(".combined-condition-operator").fadeOut();
		}
	})
}
