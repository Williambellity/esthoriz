$(document).ready(function() {

var index = $("#index"),
name = $( "#name" ),
firstname = $( "#firstname" ),
mail = $( "#mail" ),
password = $( "#password" ),
allFields = $( [] ).add(index).add( name ).add(firstname).add( mail ).add( password );

function validateName () {
    reg = new RegExp("^[a-zA-Z-.ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ ]+$","gi");
	name.removeClass("form-error-inputs");
	if(name.val()=="") {
		$("#error-name").text("");
		return false;
	} else if(!( reg.test( name.val() ) ) ) {
		$("#error-name").text("Seuls les caractères alphanumériques et .- sont autorisés.");
		name.addClass("form-error-inputs");
        return false;
	} else if(name.val().length > 50) {
		$("#error-name").text("100 caractères au maximum et 1 au minimum.");
		name.addClass("form-error-inputs");
        return false;
	} else {
		$("#error-name").text("");
        return true;
	}
}

function validateFirstname () {
    reg = new RegExp("^[a-zA-Z-.ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ ]+$","gi");
	firstname.removeClass("form-error-inputs");
	if(firstname.val()=="") {
		$("#error-firstname").text("");
		return false;
	} else if(!( reg.test( firstname.val() ) ) ) {
		$("#error-firstname").text("Seuls les caractères alphanumériques et .- sont autorisés.");
		firstname.addClass("form-error-inputs");
        return false;
	} else if(firstname.val().length > 50) {
		$("#error-firstname").text("100 caractères au maximum et 1 au minimum.");
		firstname.addClass("form-error-inputs");
        return false;
	} else {
		$("#error-firstname").text("");
        return true;
	}
}

function validateEmail () {
	reg = new RegExp("^[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$","gi");
    mail.removeClass("form-error-inputs");
	if(mail.val()=="") {
		$("#error-mail").text("");
		return false;
	} else if(!( reg.test( mail.val() ) ) ) {
		$("#error-mail").text("Seul un email valide est autorisé");
		mail.addClass("form-error-inputs");
        return false;
	} else if(mail.val().length > 100) {
		$("#error-mail").text("100 caractères au maximum et 1 au minimum.");
		mail.addClass("form-error-inputs");
        return false;
	} else {
		$("#error-mail").text("");
        return true;
	}
}

function validatePassword () {
    reg = new RegExp("^[a-zA-Z0-9&-':.éàè]+$","gi");
	password.removeClass("form-error-inputs");
	if(password.val()=="") {
		$("#error-password").text("");
		return false;
	} else if(!( reg.test( password.val() ) ) ) {
		$("#error-password").text("Seuls les caractères alphanumériques et '.-&: sont autorisés.");
		password.addClass("form-error-inputs");
        return false;
	} else if(password.val().length > 80) {
		$("#error-password").text("80 caractères au maximum et 1 au minimum.");
		password.addClass("form-error-inputs");
        return false;
	} else {
		$("#error-password").text("");
        return true;
	}
}

function validatePage(n) {
	valid=true;
    valid=validateName() && valid;
	valid=validateFirstname() && valid;
    valid=validateEmail() && valid;
    valid=validatePassword() && valid;
	
	if(valid) {
		$("#reg-button").attr("disabled",false);
	} else {
		$("#reg-button").attr("disabled",true);
	}
	
	return valid;
}

$(name).keyup(function () {validatePage();});
$(firstname).keyup(function () {validatePage();});
$(mail).blur(function () {validatePage();});
$(password).keyup(function () {validatePage();});

});