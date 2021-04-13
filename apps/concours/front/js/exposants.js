EXPOSANTS = {};

(function(){ // Stand début de scope local
	EXPOSANTS.liste =
	EXPOSANTS.liste || {}; // création d'un sous namespace pour y stocker nos classes utilitaires si celuici n'est pas déjà créé
	// constructeur
	EXPOSANTS.liste.cat = function( id, name ) {
        this.id = id;
        this.name = name;
        this.constructor();
	};
    // trick JavaScript pour émuler le self:: en PHP : on utilise une variable locale
	var self = EXPOSANTS.liste.cat,
	privateVariable = 0; // variable privée visible par toutes les instances
	// variable statique partagée pour tout le code
	self.cats = [];
	// variables et méthodes publiques propres à chaque instance
	EXPOSANTS.liste.cat.prototype = {
		id:0,
        name:"",
		constructor:function() {            
            
		}
	};
})(); // fin de scope local

(function(){ // Pages début de scope local
	EXPOSANTS.system = EXPOSANTS.system || {};
	// déclaration de la classe de validation proprement dite
	EXPOSANTS.system.page = {
		// déclaration de nos variables statiques
		// déclaration de nos méthodes
        initTabs:function() {
        }
	}; // fin de classe
	// trick JavaScript pour émuler le self:: en PHP : on utilise une variable locale
	var self = EXPOSANTS.system.page;
})(); // fin de scope local