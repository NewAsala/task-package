export const BASE_URL = "http://localhost:8084/";
export const usersSelectTag = document.getElementById("users");
export const modelsSelectTag = document.getElementById('models');

export let actionOptions = []; //this is the array of actions that is received from backend
export let attributeOptions = []; //this is the same as the attributeOptions array in the ajax request.

export let complexConditionRows = []; //used to keep the indices of the rows that are present in the complex condition table
//useful for the hidden select tags in the complex condition table. when one row is added, its index is added. when it's removed, ditto.
// the items in this array will then appear in the hidden options tag