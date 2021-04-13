$(document).ready(function() { // Initialiseur
	$('#resa2').hide(0);
	$('#resa3').hide(0);
	$('.left_last_button').click( function() {LOGISTIC.system.pages.lastPage();});
	$('.right_next_button').click( function() {LOGISTIC.system.pages.nextPage();});
	$('.button_plus').click( function() {
		var length = $(this).attr('id').length;
		LOGISTIC.buttons.button.buttons[$(this).attr('id').substr(5,length-5)].plus();
	});
	$('.button_moins').click( function() {
		var length = $(this).attr('id').length;
		LOGISTIC.buttons.button.buttons[$(this).attr('id').substr(6,length-6)].moins();
	});
	$('.toggler').click( function() {LOGISTIC.buttons.button.buttons[$(this).attr('id')].toggle();});
	$('.b_color').click( function() {
		var length = $(this).attr('id').length;
		LOGISTIC.buttons.buttonColor.select([$(this).attr('id').substr(7,length-7)]);
	});
	$('.button').hover(
		function() {
			var t = "#"+$(this).attr('id');
			$(t).addClass('button_hover');
			t += '>.button_center';
			$(t).show(0);
		},
		function() {
			var t = "#"+$(this).attr('id');
			$(t).removeClass('button_hover');
			t += '>.button_center';
			$(t).hide(0);
	});
	$('#lien_page1').click( function() {LOGISTIC.system.pages.selectPage(1);});
	$('#lien_page2').click( function() {LOGISTIC.system.pages.selectPage(2);});
	$('#lien_page3').click( function() {LOGISTIC.system.pages.selectPage(3);});
});