$(document).ready(function() {
	var timeout    = 1000;
	var closetimer = 0;
	var focus = 0;
	
	$("a#button_form").fancybox({
		'hideOnContentClick': false,
		'transitionIn'	:	'elastic',
		'transitionOut'	:	'elastic',
		'speedIn'		:	600, 
		'speedOut'		:	200,
		'href'			:	"/apps/entreprise/front/templates/inscription.html"
	});
	
	$('a#a_cred').fancybox({
		'hideOnContentClick': false,
		'transitionIn'	:	'elastic',
		'transitionOut'	:	'elastic',
		'speedIn'		:	600, 
		'speedOut'		:	200,
		'href'			:	"/apps/about/front/templates/about.html"
	});
	
	$("#menu li.home a").hover(
		function() {
			$(this).css("background-image","../images/accueil_hover.png");
		},
		function() {
			$(this).css("background-image","../images/accueil.png");
	});
	
	function MenuDeroulant_open() {
		MenuDeroulant_canceltimer();
		focus=0;
		if ($("#connect_form").css('display') == 'none') {
			$("#connect_form").slideDown(400, 'swing',function () {
				$("#connect_box span").toggleClass("hidden");
			});
			$("#ecoles_c").animate({marginTop:'150px'},1000);
		}
	}
	
	function MenuDeroulant_close() {
		if($("#connect_form").css('display') != 'none') {
			$("#connect_form").slideUp(400, 'swing');
			$("#connect_box span").toggleClass("hidden");	
			$("#ecoles_c").animate({marginTop:'20px'},1000);
		}
	}
	
	function MenuDeroulant_exit() {
		if(focus==0) {
			MenuDeroulant_timer();
		}		
	}
	
	function MenuDeroulant_timer() {
		focus=0;
		closetimer = window.setTimeout(MenuDeroulant_close, timeout);
	}
	
	function MenuDeroulant_canceltimer() {
		focus=1;
		if(closetimer) {
			window.clearTimeout(closetimer);
			closetimer = null;
		}
	}
	
	$('#connect_box .inner').bind('mouseenter', MenuDeroulant_open);
	$('#connect_box .inner').bind('focusout',  MenuDeroulant_timer);
	$('#connect_box .inner').bind('focusin',  MenuDeroulant_canceltimer);
	$('#connect_box .inner').bind('mouseleave', MenuDeroulant_exit);
	
	var submenu = "";
	var menu_timeout = 1500;
	var menu_closetimer = null;
	
	$('.li_main').hover(
		function() {
			var id = $(this).attr('id');
			if (id != "" && submenu != '#'+id+'>.submenu') {
				closeMenu(submenu);
				submenu = '#'+id+'>.submenu';
				$('#'+id+'>.submenu').slideDown(600, 'swing');
			} else {
				stopTimer();
			}
		}, function() {
			startTimer();
	});
	
	function startTimer() {
		menu_closetimer = window.setTimeout(closeMenu, menu_timeout);
	}
	
	function stopTimer() {
		if(menu_closetimer) {
			window.clearTimeout(menu_closetimer);
			menu_closetimer = null;
		}
	}
	
	function closeMenu() {
		if (submenu != "") {
			$(submenu).slideUp(400, 'swing');
		}
		submenu="";
		stopTimer();
	}
});
