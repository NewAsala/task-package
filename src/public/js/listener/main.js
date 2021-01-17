import * as utility from "../utils/utils.js";
import * as globals from "./globals.js"; //this import might be necessary in the future
import {fetchActions, fetchAttributesForModels, fetchUsers} from './fetch_logic.js';
import { registerEventListeners } from "./registerEventListeners.js";

// used to overcome a CORS error
$.ajaxSetup({
	headers: {
		'X-CSRF-TOKEN': localStorage.getItem('csrf') // $('meta[name="csrf-token"]').attr('content'): using this way, you have to include the meta tag yourself into the beginning of the blade file
	}
});


// this basically initializes everything in the page and registers event listeners
function main() {
	utility.fixModelNames();
	fetchUsers();
	fetchAttributesForModels();
	fetchActions();
	registerEventListeners();
}

main();