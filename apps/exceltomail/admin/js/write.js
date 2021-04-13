
function checkForm() {
	return true;
}

$(document).ready(function() {
	$('#objet').click(function() {
		$(this).attr('class', '');
		if ($(this).attr('value') == 'Objet') {
			$(this).attr('value', '');
		}
	});
	$('#objet').blur(function() {
		if ($(this).attr('value') == '') {
			$(this).attr('class', 'empty');
			$(this).attr('value', 'Objet');
		}
	});
	
});