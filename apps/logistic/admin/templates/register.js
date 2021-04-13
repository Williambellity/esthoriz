$(document).ready(function() {

var index = $("#index"),
firm = $("#firm"),
civi = $("#civi"),
name = $( "#name" ),
fname = $( "#fname" ),
poste = $( "#poste" ),
address = $( "#address" ),
tel = $( "#tel" ),
fax = $( "#fax" ),
mob = $( "#mob" ),
mail = $( "#mail" ),
password = $( "#password" ),
allFields = $( [] ).add(index).add(firm).add(civi).add( name ).add(fname).add(poste).add(address).add(tel).add(fax).add(mob).add( mail ).add( password );

var page=1;
var max_page=3;

function validateFirm () {
	reg = new RegExp("^[a-zA-Z0-9&-':.ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ ]+$","gi");
	firm.removeClass("form-error-inputs");
	if(firm.val()=="") {
		$("#error-firm").text("");
		return false;
	} else if(!( reg.test( firm.val() ) ) ) {
		$("#error-firm").text("Seuls les caractères alphanumériques et '.-&: sont autorisés.");
		firm.addClass("form-error-inputs");
        return false;
	} else if(firm.val().length > 80) {
		$("#error-firm").text("80 caractères au maximum et 1 au minimum.");
		firm.addClass("form-error-inputs");
        return false;
	} else {
		$("#error-firm").text("");
        return true;
	}
}

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
		$("#error-name").text("50 caractères au maximum et 1 au minimum.");
		name.addClass("form-error-inputs");
        return false;
	} else {
		$("#error-name").text("");
        return true;
	}
}

function validateFname () {
    reg = new RegExp("^[a-zA-Z-.ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ ]+$","gi");
	fname.removeClass("form-error-inputs");
	if(fname.val()=="") {
		$("#error-fname").text("");
		return false;
	} else if(!( reg.test( fname.val() ) ) ) {
		$("#error-fname").text("Seuls les caractères alphanumériques et .- sont autorisés.");
		fname.addClass("form-error-inputs");
        return false;
	} else if(fname.val().length > 50) {
		$("#error-fname").text("50 caractères au maximum et 1 au minimum.");
		fname.addClass("form-error-inputs");
        return false;
	} else {
		$("#error-fname").text("");
        return true;
	}
}

function validatePoste () {
    reg = new RegExp("^[a-zA-Z0-9-.':ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ ]+$","gi");
	poste.removeClass("form-error-inputs");
	if(poste.val()=="") {
		$("#error-poste").text("");
		return false;
	} else if(!( reg.test( poste.val() ) ) ) {
		$("#error-poste").text("Seuls les caractères alphanumériques et ':.- sont autorisés.");
		poste.addClass("form-error-inputs");
        return false;
	} else if(poste.val().length > 250) {
		$("#error-poste").text("250 caractères au maximum et 1 au minimum.");
		poste.addClass("form-error-inputs");
        return false;
	} else {
		$("#error-poste").text("");
        return true;
	}
}

function validateAddress () {
    reg = new RegExp("^[a-zA-Z0-9-.':ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ\n ]+$","gi");
	address.removeClass("form-error-inputs");
	if(address.val()=="") {
		$("#error-address").text("");
		return false;
	} else if(!( reg.test( address.val() ) ) ) {
		$("#error-address").text("Seuls les caractères alphanumériques et ':.- sont autorisés.");
		address.addClass("form-error-inputs");
        return false;
	} else if(address.val().length > 500) {
		$("#error-address").text("500 caractères au maximum et 1 au minimum.");
		address.addClass("form-error-inputs");
        return false;
	} else {
		$("#error-address").text("");
        return true;
	}
}

function validateTel () {
    reg = new RegExp("^[0-9+]+$","gi");
	tel.removeClass("form-error-inputs");
	if(tel.val()=="") {
		$("#error-tel").text("");
		return false;
	} else if(!( reg.test( tel.val() ) ) ) {
		$("#error-tel").text("Seuls les caractères numériques et + sont autorisés.");
		tel.addClass("form-error-inputs");
        return false;
	} else if(tel.val().length > 15) {
		$("#error-tel").text("15 caractères au maximum et 1 au minimum.");
		tel.addClass("form-error-inputs");
        return false;
	} else {
		$("#error-tel").text("");
        return true;
	}
}

function validateMob () {
    reg = new RegExp("^[0-9+]+$","gi");
	mob.removeClass("form-error-inputs");
	if(mob.val()=="") {
		$("#error-mob").text("");
		return false;
	} else if(!( reg.test( mob.val() ) ) ) {
		$("#error-mob").text("Seuls les caractères numériques et + sont autorisés.");
		mob.addClass("form-error-inputs");
        return false;
	} else if(mob.val().length > 15) {
		$("#error-mob").text("15 caractères au maximum et 1 au minimum.");
		mob.addClass("form-error-inputs");
        return false;
	} else {
		$("#error-mob").text("");
        return true;
	}
}

function validateFax () {
    reg = new RegExp("^[0-9+]+$","gi");
	fax.removeClass("form-error-inputs");
	if(fax.val()=="") {
		$("#error-fax").text("");
		return true;
	} else if(!( reg.test( fax.val() ) ) ) {
		$("#error-fax").text("Seuls les caractères numériques et + sont autorisés.");
		fax.addClass("form-error-inputs");
        return false;
	} else if(fax.val().length > 15) {
		$("#error-fax").text("15 caractères au maximum et 1 au minimum.");
		fax.addClass("form-error-inputs");
        return false;
	} else {
		$("#error-fax").text("");
        return true;
	}
}

function validateAllTel () {
	if(validateTel()||validateMob()) {
		return true;
	} else {
		return false;
	}
}

function validateEmail () {
	reg = new RegExp("^[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$","gi");
    mail.removeClass("form-error-inputs");
	if(mail.val()=="") {
		$("#error-mail").text("");
		return false;
	} else if(!( reg.test( mail.val() ) ) ) {
		$("#error-mail").text("Seule un email valide est autorisé");
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
	if(n==1) {
		valid=validateFirm() && valid;
	} else if(n==2) {
		valid=validateName() && valid;
		valid=validateFname() && valid;
		valid=validatePoste() && valid;
		valid=validateAddress() && valid;
		valid=validateAllTel() && valid;
		valid=validateFax() && valid;
	} else if(n==3) {
		valid=validateEmail() && valid;
		valid=validatePassword() && valid;
	} else {
		return false;
	}
	
	if(validatePassword()&&validateEmail()&&validateAllTel()&&validateFax()&&validateAddress()&&validatePoste()&&validateFname()&&validateName()&&validateFirm()) {
		$("#reg-button").attr("disabled",false);
	} else {
		$("#reg-button").attr("disabled",true);
	}
	
	return valid;
}

function allowNext(n) {
	if(!$(".next").hasClass("active")) {
	if(n<max_page){
	n2=n+1;
	id="#p"+n2+"_status";
	reg=new RegExp("(_inactive.png)|(_hover.png)","g");
	$(id).removeClass("inactive")
		 .addClass("active");
	source=$(id).attr("src");
	$(id).attr("src",source.replace(reg,".png"));
	$(".next").removeClass("inactive")
			  .addClass("active");
	source2=$(".next").attr("src");
	$(".next").attr("src",source2.replace(reg,".png"));
	} else {
		forbidNext(n);
	}
	}
}

function forbidNext(n) {
	if(!$(".next").hasClass("inactive")) {
	reg=new RegExp("(_inactive.png)","g");
	reg2=new RegExp("(.png)","g");
	if(n<max_page) {
		n2=n+1;
		id="#p"+n2+"_status";
		$(id).removeClass("active")
			  .removeClass("inactive")
			  .addClass("inactive");
		source=$(id).attr("src");
		$(id).attr("src",source.replace(reg,".png"));
		source=$(id).attr("src");
		$(id).attr("src",source.replace(reg2,"_inactive.png"));
	}
	$(".next").removeClass("active")
			  .removeClass("inactive")
			  .addClass("inactive");
	source=$(".next").attr("src");
	$(".next").attr("src",source.replace(reg,".png"));
	source=$(".next").attr("src");
	$(".next").attr("src",source.replace(reg2,"_inactive.png"));
	}
}

function changePage(p1,p2) {
	if(p1<=max_page&&p2<=max_page) {
	class1=".page"+p1;
	class2=".page"+p2;
	$(class1).hide();
	$(class2).show();
	
	if(validatePage(p2)&&p2<max_page) {
		allowNext(p2);
	} else {
		forbidNext(p2);
	}
	
	page=p2;
	
	if(p2!=1) {
		reg=new RegExp("(_inactive.png)|(_hover.png)","g");
		$(".last").removeClass("inactive")
			      .addClass("active");
		source2=$(".last").attr("src");
		$(".last").attr("src",source2.replace(reg,".png"));
	} else {
		reg=new RegExp("(.png)|(_hover.png)","g");
		$(".last").removeClass("active")
			      .addClass("inactive");
		source2=$(".last").attr("src");
		$(".last").attr("src",source2.replace(reg,"_inactive.png"));
	}
	}
	$.fancybox.resize();
}

$(".status_form>a>img").click( function() {
	if($(this).hasClass('active')) {
		p2=parseInt($(this).attr("id").substring(1,2));
		changePage(page,p2);
	}	
});

$(".next").click( function() {
	if($(this).hasClass('active')) {
		if(page<max_page) {
		p2=page+1;
		changePage(page,p2);
		}
	}	
});

$(".last").click( function() {
	if($(this).hasClass('active')) {
		p2=page-1;
		changePage(page,p2);
	}	
});

/*$("#register_form a>img").hover(
	function() {
		if($(this).hasClass('active')) {
			reg=new RegExp("(.png)","g");
			source=$(this).attr("src");
			$(this).attr("src",source.replace(reg,"_hover.png"));
		}
	},
	function() {
		if($(this).hasClass('active')) {
			reg=new RegExp("(_hover.png)","g");
			source=$(this).attr("src");
			$(this).attr("src",source.replace(reg,".png"));
		}
});*/

$(firm).keyup(function () {if(validatePage(1)){allowNext(page);}else{forbidNext(page);};});
$(name).keyup(function () {if(validatePage(2)){allowNext(page);}else{forbidNext(page);};});
$(fname).keyup(function () {if(validatePage(2)){allowNext(page);}else{forbidNext(page);};});
$(poste).keyup(function () {if(validatePage(2)){allowNext(page);}else{forbidNext(page);};});
$(address).keyup(function () {if(validatePage(2)){allowNext(page);}else{forbidNext(page);};});
$(tel).keyup(function () {if(validatePage(2)){allowNext(page);}else{forbidNext(page);};});
$(mail).blur(function () {if(validatePage(3)){allowNext(page);}else{forbidNext(page);};});
$(password).keyup(function () {if(validatePage(3)){allowNext(page);}else{forbidNext(page);};});

});