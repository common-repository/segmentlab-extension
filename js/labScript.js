// JavaScript Document
function countas(s1, letter) {
	"use strict";
	if(s1) {
		var result = s1.split(letter);
		if(result && result.length >0){
			return result.length - 1;
		}
		else {
			return 0;
		}
	}
	else {
			return 0;						
	}
}
function new_user_manual (action) {
	if (action == 1) {
		document.getElementById("existing_user_id").style.display = "block";
		document.getElementById("new_user_id").style.display = "none";
		document.getElementById("existing_user_button_id").style.color = "#ec5500";
		document.getElementById("existing_user_button_id").style.fontWeight = "900";
		document.getElementById("new_user_button_id").style.color = "";
		document.getElementById("new_user_button_id").style.fontWeight = "";
		document.getElementById("auth_user_id").style.display = "none";

	}
	else if (action == 2) {
		document.getElementById("existing_user_id").style.display = "none";
		document.getElementById("new_user_id").style.display = "block";
		document.getElementById("new_user_button_id").style.color = "#ec5500";
		document.getElementById("existing_user_button_id").style.color = "";
		document.getElementById("existing_user_button_id").style.fontWeight = "";
		document.getElementById("new_user_button_id").style.fontWeight = "900";
		document.getElementById("auth_user_id").style.display = "none";
	}
	else if (action == 3) {
		document.getElementById("existing_user_id").style.display = "none";
		document.getElementById("new_user_id").style.display = "none";
		document.getElementById("new_user_button_id").style.color = "#ec5500";
		document.getElementById("existing_user_button_id").style.color = "";
		document.getElementById("existing_user_button_id").style.fontWeight = "";
		document.getElementById("new_user_button_id").style.fontWeight = "900";
		document.getElementById("auth_user_id").style.display = "block";

		
		

	}
	else {
	}
}

function validateEmail(email) { 
	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(email);
}

function validateLength (field, limit) {
	var fieldLength = field.length;
	var response;
	if (fieldLength >= limit) {
		response = true;
	}
	else {
		response = false;
	}
	return response;
}


