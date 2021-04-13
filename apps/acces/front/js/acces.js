ACCES = {};

(function(){ // Pages début de scope local
	ACCES.system = ACCES.system || {};
	// déclaration de la classe de validation proprement dite
	ACCES.system.pages = {
		// déclaration de nos variables statiques
		nbPages:3,
		currentPage:1,
		prefixe_lien:"lien",
		prefixe_page:"acces",
		// déclaration de nos méthodes
		selectPage:function( p ) {
			var dir1;
			var dir2;
			if(self.currentPage>p) {
				dir1 = "right";
				dir2 = "left";
			} else {
				dir1 = "left";
				dir2 = "right";
			}
			var page = "#"+self.prefixe_page+self.currentPage;
			$(page).hide("drop",{direction: dir1}, 500, function() {
				$('#'+self.prefixe_lien+self.currentPage).removeClass('selected');
				self.currentPage = p;
				$('#'+self.prefixe_lien+self.currentPage).addClass('selected');
				page = "#"+self.prefixe_page+self.currentPage;
				$(page).show("drop",{direction: dir2}, 500);
			});
		}
	}; // fin de classe
	// trick JavaScript pour émuler le self:: en PHP : on utilise une variable locale
	var self = ACCES.system.pages;
})(); // fin de scope local

$(document).ready(function() {
	$('.button_acces').click( function() {
		var idB = $(this).attr('id');
		var l = idB.length;
		var lp = ACCES.system.pages.prefixe_lien.length;
		idB = idB.substr(lp,l);
		ACCES.system.pages.selectPage(idB);
	});
});