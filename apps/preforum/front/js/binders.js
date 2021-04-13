$(document).ready(function() { // Initialiseur
	$('.button_filter').click( function() {
        var length = $(this).attr('id').length;
        if($(this).attr('id') != 'categories_all' && $(this).attr('id') != 'concerne_all') {
        if($(this).attr('id').substr(0,3)=="cat") {
            TIMELINE.system.line.toggleCategorie($(this).attr('id').substr(4,length-4));
        } else if($(this).attr('id').substr(0,3)=="con") {
            TIMELINE.system.line.toggleConcerne($(this).attr('id').substr(5,length-5));
        }
        }
    });
	$('.button_filter').hover(
		function() {
			$(this).addClass('button_filter_hover');
		},
		function() {
			$(this).removeClass('button_filter_hover');
        });
    $('#concerne_all').click(function() {
        TIMELINE.system.line.allConcernes();
    });
    $('#categories_all').click(function() {
        TIMELINE.system.line.allCategories();
    });
    $('.cont_filter').append("<div class='spacer'></div>");
    TIMELINE.system.line.allConcernes();
    TIMELINE.system.line.allCategories();
    TIMELINE.system.line.display();
});