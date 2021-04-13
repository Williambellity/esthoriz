LOGISTIC = {};

(function(){ // Stand début de scope local
	LOGISTIC.elements =
	LOGISTIC.elements || {}; // création d'un sous namespace pour y stocker nos classes utilitaires si celuici n'est pas déjà créé
	// constructeur
	LOGISTIC.elements.stand = function( id, prix, taille, nbstand) {
		this.id = id;
		this.prix = prix;
		this.taille = taille;		this.nbstand = nbstand;
		this.button = new LOGISTIC.buttons.button(this.id,"stand","toggler",this.prix,this.taille,"","",this, this.nbstand);
	};
	// variables et méthodes publiques propres à chaque instance
	LOGISTIC.elements.stand.prototype = {
		id:0,
		prix:0,
		taille:0,				nbstand: 0,
		button:null,
		select:function() {
			$("#end-button").attr("disabled",false);
			$(".nothing_selected").addClass('hidden');
			/*if (this.button.quantity == 0) {
				LOGISTIC.system.recap.unselectStand();
			} else {*/
				LOGISTIC.system.recap.selectStand(this.id);
				self.unselectAll(this.id);
			//}
		},
		unselect:function() {
			this.button.unselect();
		}
	};
	// trick JavaScript pour émuler le self:: en PHP : on utilise une variable locale
	var self = LOGISTIC.elements.stand,
	privateVariable = 0; // variable privée visible par toutes les instances
	// variable statique partagée pour tout le code
	self.stands = [];
	self.unselectAll = function( id ) {
		for (var s in self.stands) {
			if (self.stands[s].id!=id) {
				self.stands[s].unselect();
			}
		}
	}
})(); // fin de scope local

(function(){ // Lot début de scope local
	LOGISTIC.elements =
	LOGISTIC.elements || {}; // création d'un sous namespace pour y stocker nos classes utilitaires si celuici n'est pas déjà créé
	// constructeur
	LOGISTIC.elements.lot = function( id, prix, nom, icone, colors, description ) {
		this.id = id;
		this.prix = prix;
		this.nom = nom;
		this.colors = colors;
		this.description = description;
		this.button = new LOGISTIC.buttons.button(this.id,"lot","toggler",this.prix,this.nom,icone,description,this);
		$('#img_list').append("<div id='imgs_lot_"+this.id+"' class='hidden'></div>");
		this.addColors();
		this.addLotImg();
		this.accessoires = new Array();
		this.chaises = new Array();
		this.tables = new Array();
		this.tabourets = new Array();
		this.banques = new Array();
		this.presentoirs = new Array();
		this.accBasics = new Array();
		this.accBasics['chaises'] = this.chaises;
		this.accBasics['tables'] = this.tables;
		this.accBasics['tabourets'] = this.tabourets;
		this.accBasics['banques'] = this.banques;
		this.accBasics['presentoirs'] = this.presentoirs;
		
	};
	// variables et méthodes publiques propres à chaque instance
	LOGISTIC.elements.lot.prototype = {
		id:0,
		prix:0,
		nom:"",
		colors:[],
		description:"",
		button:null,
		chaises:[],
		nbChaises:0,
		tables:[],
		nbTables:0,
		tabourets:[],
		nbTabourets:0,
		banques:[],
		nbBanques:0,
		presentoirs:[],
		nbPresentoirs:0,
		accBasics:[],
		accessoires:[],
		select:function() {
			if(this.button.quantity == 0) {
				LOGISTIC.system.recap.unselectLot(this.id);
				$('#no_lot').fadeIn(500);
				$('#imgs_lot_'+this.id).hide(0);
				for (var accid in LOGISTIC.elements.accessoire.accessoires) {
					LOGISTIC.elements.accessoire.accessoires[accid].unselect();
				}
				for (var accType in this.accBasics) {
					for (var acc in this.accBasics[accType]) {
						this.accBasics[accType][acc].unselect();
					}
				}
			} else {
				$('#no_lot').hide(0);
				LOGISTIC.system.recap.selectLot(this.id);
				self.unselectAll(this.id);
				for (var typeAcc in this.accBasics) {
					for (var nomAcc in this.accBasics[typeAcc]) {
						this.accBasics[typeAcc][nomAcc].button.oShow();
					}
				}
				for (var accid in LOGISTIC.elements.accessoire.accessoires) {
					var test = false;
					for (var a in this.accessoires) {
						if(this.accessoires[a]==accid) {
							test = true;
						}
					}
					
					if(test==false) {
						LOGISTIC.elements.accessoire.accessoires[accid].unselect();
					} else {
						LOGISTIC.elements.accessoire.accessoires[accid].button.oShow();
					}
				}
				for (var c in LOGISTIC.buttons.buttonColor.colors) {
					var color_temp = LOGISTIC.buttons.buttonColor.colors[c];
					
					var test = false;
					
					for (var c2 in this.colors) {
						if (c2 == color_temp) {
							test = true;
						}
					}
					
					if (test) {
						LOGISTIC.buttons.buttonColor.buttonColors[color_temp].oShow();
					} else {
						LOGISTIC.buttons.buttonColor.buttonColors[color_temp].oHide();
					}
				}
				
				var test = false;
				var pot_color = "";
					
				for (var c2 in this.colors) {
					pot_color = c2;
					if (c2 == LOGISTIC.buttons.buttonColor.selectedColor) {
						test = true;
					}
				}
				
				if (!test) {
					LOGISTIC.buttons.buttonColor.select(pot_color);
				}
				$('#imgs_lot_'+this.id).show(0);
			}
		},
		unselect:function() {
			this.button.unselect();
			$('#imgs_lot_'+this.id).hide(0);
			for (var accType in this.accBasics) {
				for (var acc in this.accBasics[accType]) {
					this.accBasics[accType][acc].unselect();
				}
			}
		},
		addColors:function() {
			for (var c in this.colors) {
				if ($.inArray(c,LOGISTIC.buttons.buttonColor.colors) == -1) {
					LOGISTIC.buttons.buttonColor.buttonColors[c] = new LOGISTIC.buttons.buttonColor(c);
				}
			}
		},
		addLotImg:function() {
			for (var c in this.colors) {
				img = "<img src='"+this.colors[c]+"' class='color_lot_"+c+" hidden' />";
				$('#imgs_lot_'+this.id).append(img);
			}
		},
		addBasicAccessoire:function(type,prix,nom,colors,etat,nombre) {
			switch(type) {
				case "chaise":
					this.accBasics['chaises'][nom] = new LOGISTIC.elements.accBasic(this.id,type,prix,nom,colors,etat,nombre);
					this.nbChaises += nombre;
					break;
				case "table":
					this.accBasics['tables'][nom] = new LOGISTIC.elements.accBasic(this.id,type,prix,nom,colors,etat,nombre);
					this.nbTables += nombre;
					break;
				case "tabouret":
					this.accBasics['tabourets'][nom] = new LOGISTIC.elements.accBasic(this.id,type,prix,nom,colors,etat,nombre);
					this.nbTabourets += nombre;
					break;
				case "banque":
					this.accBasics['banques'][nom] = new LOGISTIC.elements.accBasic(this.id,type,prix,nom,colors,etat,nombre);
					this.nbBanques += nombre;
					break;
				case "presentoir":
					this.accBasics['presentoirs'][nom] = new LOGISTIC.elements.accBasic(this.id,type,prix,nom,colors,etat,nombre);
					this.nbPresentoirs += nombre;
					break;
			}
		}
	};
	// trick JavaScript pour émuler le self:: en PHP : on utilise une variable locale
	var self = LOGISTIC.elements.lot,
	privateVariable = 0; // variable privée visible par toutes les instances
	// variable statique partagée pour tout le code
	self.lots = [];
	self.unselectAll = function( id ) {
		for (var s in self.lots) {
			if (self.lots[s].id!=id) {
				self.lots[s].unselect();
			}
		}
	}
})(); // fin de scope local

(function(){ // AccBasic début de scope local
	LOGISTIC.elements =
	LOGISTIC.elements || {}; // création d'un sous namespace pour y stocker nos classes utilitaires si celuici n'est pas déjà créé
	// constructeur
	LOGISTIC.elements.accBasic = function( id_lot, type, prix, nom, colors, etat, nombre ) {
		this.id_lot = id_lot;
		this.type = type;
		this.nom = nom;
		this.nombre = nombre;
		this.prix = prix;
		this.colors = colors;
		this.nom_ws = LOGISTIC.system.recap.remplace(this.nom," ","_");
		this.id = this.id_lot+"_"+this.type+"_"+this.nom_ws;
		this.button	= new LOGISTIC.buttons.button(this.id,"accBasic",etat,this.prix,this.nom,this.colors,"",this);
	};
	// variables et méthodes publiques propres à chaque instance
	LOGISTIC.elements.accBasic.prototype = {
		id:"",
		type:"",
		prix:0,
		nom:"",
		mon_ws:"",
		colors:[],
		id_lot:0,
		nombre:0,
		button:null,
		select:function() {
			LOGISTIC.system.recap.selectAccBasic(this.type,this.nom,this.button.quantity,this.id,this.prix,this.button.type);
		},
		unselect:function() {
			this.button.resetHide(); //reset du button + hide
		}
	};
	// trick JavaScript pour émuler le self:: en PHP : on utilise une variable locale
	var self = LOGISTIC.elements.accBasic,
	privateVariable = 0; // variable privée visible par toutes les instances
	// variable statique partagée pour tout le code
	self.accBasics = [];
})(); // fin de scope local

(function(){ // Accessoire début de scope local
	LOGISTIC.elements =
	LOGISTIC.elements || {}; // création d'un sous namespace pour y stocker nos classes utilitaires si celuici n'est pas déjà créé
	// constructeur
	LOGISTIC.elements.accessoire = function( id, prix, nom, colors, etat, lots, description ) {
		this.id = id;
		this.prix = prix;
		this.nom = nom;
		this.colors = colors;
		this.lots = lots;
		this.button	= new LOGISTIC.buttons.button(this.id,"accessoire",etat,this.prix,this.nom,this.colors,description,this);
		for(var l in this.lots) {
			LOGISTIC.elements.lot.lots[this.lots[l]].accessoires.push(this.id);
		}
	};
	// variables et méthodes publiques propres à chaque instance
	LOGISTIC.elements.accessoire.prototype = {
		id:0,
		prix:0,
		nom:"",
		colors:[],
		lots:[],
		button:null,
		select:function() {
			LOGISTIC.system.recap.selectAccessoire(this.id,this.nom,this.button.quantity,this.prix);
		},
		unselect:function() {
			this.button.resetHide(); //reset du button + hide
			LOGISTIC.system.recap.selectAccessoire(this.id,this.nom,0,this.prix); //reset du choix
		}
	};
	// trick JavaScript pour émuler le self:: en PHP : on utilise une variable locale
	var self = LOGISTIC.elements.accessoire,
	privateVariable = 0; // variable privée visible par toutes les instances
	// variable statique partagée pour tout le code
	self.accessoires = [];
})(); // fin de scope local

(function(){ // Accfixe début de scope local
	LOGISTIC.elements =
	LOGISTIC.elements || {}; // création d'un sous namespace pour y stocker nos classes utilitaires si celuici n'est pas déjà créé
	// constructeur
	LOGISTIC.elements.accfixe = function( id, prix, nom, colors, etat, description ) {
		this.id = id;
		this.prix = prix;
		this.nom = nom;
		this.colors = colors;
		this.button	= new LOGISTIC.buttons.button(this.id,"accfixe",etat,this.prix,this.nom,this.colors,description,this);
	};
	// variables et méthodes publiques propres à chaque instance
	LOGISTIC.elements.accfixe.prototype = {
		id:0,
		prix:0,
		nom:"",
		colors:[],
		button:null,
		select:function() {
			LOGISTIC.system.recap.selectAccfixe(this.id,this.nom,this.button.quantity,this.prix);
		}
	};
	// trick JavaScript pour émuler le self:: en PHP : on utilise une variable locale
	var self = LOGISTIC.elements.accfixe,
	privateVariable = 0; // variable privée visible par toutes les instances
	// variable statique partagée pour tout le code
	self.accfixes = [];
})(); // fin de scope local

(function(){ // Option début de scope local
	LOGISTIC.elements =
	LOGISTIC.elements || {}; // création d'un sous namespace pour y stocker nos classes utilitaires si celuici n'est pas déjà créé
	// constructeur
	LOGISTIC.elements.option = function( id, prix, nom, image, etat, description, categorie ) {
		this.id = id;
		this.prix = prix;
		this.nom = nom;
		this.image = image;
		this.categorie = categorie;
		this.button	= new LOGISTIC.buttons.button(this.id,"option",etat,this.prix,this.nom,this.image,description,this);
	};
	// variables et méthodes publiques propres à chaque instance
	LOGISTIC.elements.option.prototype = {
		id:0,
		prix:0,
		nom:"",
		image:"",
		categorie:"",
		button:null,
		select:function() {
			LOGISTIC.system.recap.selectOption(this.id,this.nom,this.button.quantity,this.prix);
		}
	};
	// trick JavaScript pour émuler le self:: en PHP : on utilise une variable locale
	var self = LOGISTIC.elements.option,
	privateVariable = 0; // variable privée visible par toutes les instances
	// variable statique partagée pour tout le code
	self.options = [];
})(); // fin de scope local

(function(){ // Button début de scope local
	LOGISTIC.buttons =
	LOGISTIC.buttons || {}; // création d'un sous namespace pour y stocker nos classes utilitaires si celuici n'est pas déjà créé
	// constructeur
	LOGISTIC.buttons.button = function( id, elementType, buttonType, prix, nom, images, description, parent, nbstand ) {
		this.id = id;
		if (elementType == "option") {
			var test = false;
			var cat_ws = LOGISTIC.system.recap.remplace(parent.categorie," ","_");
			for (var c in self.cats_option) {
				if (parent.categorie == self.cats_option[c]) {
					test = true;
				}
			}
			if (test == false) {
				var cat_html = "<div class='option_cat'><div class='cat_option_denom'>"+parent.categorie+"</div><div id='cont_"+cat_ws+"_"+elementType+"'></div><div class='spacer'></div></div><div class='spacer'></div>";
				$('#cont_option').append(cat_html);
				self.cats_option.push(parent.categorie);
			}
			this.id_conteneur = '#cont_'+cat_ws+'_'+elementType;
		} else {
			this.id_conteneur = "#cont_"+elementType;
		}
		this.elementType = elementType;
		this.type = buttonType;
		this.prix = prix;
		this.nom = nom;
		this.description = description;
		this.parent_object = parent;		this.nbstand = nbstand;
		
		self.buttons[elementType+"_"+this.id] = this;
		
		var content = "";
		switch(buttonType) {
			case "toggler":
				if(elementType=="stand") {					if(this.nbstand==0) {										content = "";										}else{
					content = "<div id='"+elementType+"_"+this.id+"' class='button toggler'><h2 class='marg_top tcenter'>"+this.nom+"m<sup>2</sup></h2><img class='hidden button_center' src='/apps/logistic/front/images/yesorno.png' alt='Oui ou Non'/><img src='/apps/logistic/front/images/no.png' id='toggle_state_"+elementType+"_"+this.id+"' class='button_bottom_left' alt='Non' /><span class='button_price button_bottom_right'>"+this.prix+"€</span></div>";										}
				} else {
					content = "<div id='"+elementType+"_"+this.id+"' class='button toggler'><img class='hidden button_center' src='/apps/logistic/front/images/yesorno.png' alt='Oui ou Non'/><img src='/apps/logistic/front/images/no.png' id='toggle_state_"+elementType+"_"+this.id+"' class='button_bottom_left' alt='Non' /><span class='button_price button_bottom_right'>"+this.prix+"€</span></div>";
				}
			break;
			
			case "morer":
				content = "<div id='"+elementType+"_"+this.id+"' class='button morer'><span id='total_price_"+elementType+"_"+this.id+"' class='button_top button_sub_price'>0€</span><span class='hidden button_center'><img id='moins_"+elementType+"_"+this.id+"' class='button_moins' src='/apps/logistic/front/images/moins.png'/><span class='button_quantity quantity_"+elementType+"_"+this.id+"'>0</span><img id='plus_"+elementType+"_"+this.id+"' class='button_plus' src='/apps/logistic/front/images/plus.png'/></span><span class='button_bottom_right button_price'><span class='button_quantity_tiny quantity_"+elementType+"_"+this.id+"'>0</span>x<span class='button_price'>"+this.prix+"€</span></span></div>";
			break;
		}
		$(this.id_conteneur).append(content);
		if(elementType!="stand") {
			$('#'+elementType+'_'+this.id).tinyTips("<h3>"+this.nom+"</h3>"+this.description);
		}
		if (elementType=="accBasic" || elementType=="accessoire") {
			$('#'+elementType+'_'+this.id).addClass('hidden');
		}
		if($.isArray(images)) {
			for (var c in images) {
				$('#'+elementType+'_'+this.id).append("<img src='"+images[c]+"' class='image_button color_"+c+" hidden' />");
			}
		} else {
			if (elementType!="stand") {
				$('#'+elementType+'_'+this.id).append("<img src='"+images+"' class='image_button c_unique' />");
			}
		}
	};
	// variables et méthodes publiques propres à chaque instance
	LOGISTIC.buttons.button.prototype = {
		quantity:0,
		id:0,
		id_conteneur:"",
		elementType:"",
		type:"",
		prix:0,
		nom:"",
		description:"",
		parent_object:null,
		resetHide:function() {
			$('#'+this.elementType+"_"+this.id).removeClass('button_selected_shadow');
			$('#'+this.elementType+'_'+this.id).hide(0);
			this.quantity = 0;
			if (this.type == "toggler") {
				$('#toggle_state_'+this.elementType+"_"+this.id).attr('src','/apps/logistic/front/images/no.png');
				$('#toggle_state_'+this.elementType+"_"+this.id).attr('alt','Non');
			} else {
				$('.quantity_'+this.elementType+"_"+this.id).html(0);
				$('#total_price_'+this.elementType+"_"+this.id).html("0€");
			}
		},
		oShow:function() {
			$('#'+this.elementType+'_'+this.id).show(0);
		},
		select:function(x) {
			if (this.type == "toggler") {
				this.quantity = x;
				if (x!=0) {
					$('#toggle_state_'+this.elementType+"_"+this.id).attr('src','/apps/logistic/front/images/yes.png');
					$('#toggle_state_'+this.elementType+"_"+this.id).attr('alt','Oui');
					$('#'+this.elementType+"_"+this.id).addClass('button_selected_shadow');
				} else {
					$('#toggle_state_'+this.elementType+"_"+this.id).attr('src','/apps/logistic/front/images/no.png');
					$('#toggle_state_'+this.elementType+"_"+this.id).attr('alt','Non');
					$('#'+this.elementType+"_"+this.id).removeClass('button_selected_shadow');
				}
			} else {
				this.quantity = x;
				$('.quantity_'+this.elementType+"_"+this.id).html(x);
				var y = x*this.prix;
				$('#total_price_'+this.elementType+"_"+this.id).html(y+"€");
				if (x!=0) {
					$('#'+this.elementType+"_"+this.id).addClass('button_selected_shadow');
				} else {
					$('#'+this.elementType+"_"+this.id).removeClass('button_selected_shadow');
				}
			}
			this.parent_object.select();
		},
		unselect:function() {
			if (this.type == "toggler") {
				this.quantity = 0;
				$('#toggle_state_'+this.elementType+"_"+this.id).attr('src','/apps/logistic/front/images/no.png');
				$('#toggle_state_'+this.elementType+"_"+this.id).attr('alt','Non');
			} else {
				this.quantity = 0;
				$('.quantity_'+this.elementType+"_"+this.id).html(0);
				$('#total_price_'+this.elementType+"_"+this.id).html("0€");
			}
			$('#'+this.elementType+"_"+this.id).removeClass('button_selected_shadow');
		},
		toggle:function() {
			if (this.quantity == 0) {
				this.select(1);
			} else {
				if(this.id_conteneur != '#cont_stand') {				
					this.select(0);
				}
			}
		},
		plus:function() {
			this.select(this.quantity+1);
		},
		moins:function() {
			if (this.quantity != 0) {
				this.select(this.quantity-1);
			}
		}
	};
	// trick JavaScript pour émuler le self:: en PHP : on utilise une variable locale
	var self = LOGISTIC.buttons.button,
	privateVariable = 0; // variable privée visible par toutes les instances
	// variable statique partagée pour tout le code
	self.buttons = [];
	self.cats_option = [];
})(); // fin de scope local

(function(){ // ButtonColor début de scope local
	LOGISTIC.buttons =
	LOGISTIC.buttons || {}; // création d'un sous namespace pour y stocker nos classes utilitaires si celuici n'est pas déjà créé
	// constructeur
	LOGISTIC.buttons.buttonColor = function( color ) {
		this.color = color;
		self.colors.push(color);
		var content = "<div id='button_"+color+"' class='button b_color c_"+color+"'></div>";
		$('#conteneur_colors').append(content);
		$('#button_'+color).tinyTips("<h3>"+color+"</h3>");
	};
	// variables et méthodes publiques propres à chaque instance
	LOGISTIC.buttons.buttonColor.prototype = {
		color:"",
		select:function() {
			if (self.selectedColor != "") {
				$('#button_color_yes_selected').remove();
				color_class = ".color_"+self.selectedColor;
				$(color_class).addClass('hidden');
				$('.color_lot_'+self.selectedColor).addClass('hidden');
			}
			$('.c_'+this.color).html("<img id='button_color_yes_selected' src='/apps/logistic/front/images/yes_color.png' alt='ok' />");
			self.selectedColor = this.color;
			color_class = ".color_"+self.selectedColor;
			$(color_class).removeClass('hidden');
			$('.color_lot_'+self.selectedColor).removeClass('hidden');
			LOGISTIC.system.recap.fillform();
		},
		oHide:function() {
			$('.c_'+this.color).hide(0);
		},
		oShow:function() {
			$('.c_'+this.color).show(0);
		}
	};
	// trick JavaScript pour émuler le self:: en PHP : on utilise une variable locale
	var self = LOGISTIC.buttons.buttonColor,
	privateVariable = 0; // variable privée visible par toutes les instances
	self.selectedColor = "";
	// variable statique partagée pour tout le code
	self.buttonColors = [];
	self.colors = [];
	self.select = function(color) {
		if (self.selectedColor != "") {
			$('#button_color_yes_selected').remove();
			color_class = ".color_"+self.selectedColor;
			$(color_class).addClass('hidden');
			$('.color_lot_'+self.selectedColor).addClass('hidden');
		}
		$('.c_'+color).html("<img id='button_color_yes_selected' src='/apps/logistic/front/images/yes_color.png' alt='ok' />");
		self.selectedColor = color;
		color_class = ".color_"+self.selectedColor;
		$(color_class).removeClass('hidden');
		$('.color_lot_'+self.selectedColor).removeClass('hidden');
		LOGISTIC.system.recap.fillform();
	}
})(); // fin de scope local

(function(){ // Recap début de scope local
	LOGISTIC.system = LOGISTIC.system || {};
	// déclaration de la classe de validation proprement dite
	LOGISTIC.system.recap = {
		// déclaration de nos variables statiques
		stand: 0,
		lot: 0,
		more: [],
		accessoires: [],
		accfixes: [],
		options: [],
		reducs: [],
		prices: [],
		subprices: [],
		price: 0,
		isReduc: [],
		// déclaration de nos méthodes
		initTabs:function() {
			$('#resa2').show(0);
			$('#resa3').show(0);
			self.isReduc['stand'] = false;
			self.isReduc['mobilier'] = false;
			self.isReduc['options'] = false;
			self.isReduc['tout'] = false;
			self.more['chaise']= [];
			self.more['table']= [];
			self.more['tabouret']= [];
			self.more['banque']= [];
			self.more['presentoir']= [];
			self.prices['stand']= [];
			self.prices['lot']= [];
			self.prices['accbasics']= [];
			self.prices['accessoires']= [];
			self.prices['accfixes']= [];
			self.prices['options']= [];
			self.prices['stand'][0]= 0;
			self.prices['lot'][0]= 0;
			self.reducs['stand']= [];
			self.reducs['mobilier']= [];
			self.reducs['options']= [];
			self.reducs['tout']= [];
			self.reducs['stand'][0] = 0;
			self.reducs['mobilier'][0] = 0;
			self.reducs['options'][0] = 0;
			self.reducs['tout'][0] = 0;
		},
		selectStand:function( id ) {
			self.stand = id;
			$('#stand_state').html("<span class='denom_price'>"+LOGISTIC.elements.stand.stands[id].taille+" m<sup>2</sup></span><br/><span class='sub_total'>"+LOGISTIC.elements.stand.stands[id].prix+"€</span>");
			self.prices['stand'][0] = LOGISTIC.elements.stand.stands[id].prix;
			self.updatePrices();
		},
		unselectStand:function(  ) {
			self.stand = 0;
			$('#stand_state').html("");
			self.prices['stand'][0] = 0;
			self.updatePrices();
		},
		selectLot:function( id ) {
			self.lot = id;
			self.more['chaise']= [];
			self.more['table']= [];
			self.more['tabouret']= [];
			self.more['banque']= [];
			self.more['presentoir']= [];
						
			var lot_content = "contient ";
			if (LOGISTIC.elements.lot.lots[id].nbChaises == 1) {
				lot_content = lot_content + LOGISTIC.elements.lot.lots[id].nbChaises + " chaise ";
			} else if (LOGISTIC.elements.lot.lots[id].nbChaises > 1) {
				lot_content = lot_content + LOGISTIC.elements.lot.lots[id].nbChaises + " chaises ";
			}
			if (LOGISTIC.elements.lot.lots[id].nbTables == 1) {
				lot_content = lot_content + LOGISTIC.elements.lot.lots[id].nbTables + " table ";
			} else if (LOGISTIC.elements.lot.lots[id].nbTables > 1) {
				lot_content = lot_content + LOGISTIC.elements.lot.lots[id].nbTables + " tables ";
			}
			if (LOGISTIC.elements.lot.lots[id].nbTabourets == 1) {
				lot_content = lot_content + LOGISTIC.elements.lot.lots[id].nbTabourets + " tabouret ";
			} else if (LOGISTIC.elements.lot.lots[id].nbTabourets > 1) {
				lot_content = lot_content + LOGISTIC.elements.lot.lots[id].nbTabourets + " tabourets ";
			}
			if (LOGISTIC.elements.lot.lots[id].nbBanques == 1) {
				lot_content = lot_content + LOGISTIC.elements.lot.lots[id].nbBanques + " banque ";
			} else if (LOGISTIC.elements.lot.lots[id].nbBanques > 1) {
				lot_content = lot_content + LOGISTIC.elements.lot.lots[id].nbBanques + " banques ";
			}
			if (LOGISTIC.elements.lot.lots[id].nbPresntoirs == 1) {
				lot_content = lot_content + LOGISTIC.elements.lot.lots[id].nbPresntoirs + " presentoir ";
			} else if (LOGISTIC.elements.lot.lots[id].nbPresntoirs > 1) {
				lot_content = lot_content + LOGISTIC.elements.lot.lots[id].nbPresntoirs + " presentoirs ";
			}
						
			var lot_html = "<span class='denom_price'>Lot "+LOGISTIC.elements.lot.lots[id].nom+"</span><br/><span class='sub_denom'>"+lot_content+"</span><span class='detail_price'>"+LOGISTIC.elements.lot.lots[id].prix+"€</span><hr/>"
			$('#lot_state').html(lot_html);
			$('#accbasic_state').html("");
			self.prices['lot'][0] = LOGISTIC.elements.lot.lots[id].prix;
			self.prices['accbasics'] = [];
			self.updatePrices();
		},
		unselectLot:function( id ) {
			self.lot = "";
			self.more['chaise']= [];
			self.more['table']= [];
			self.more['tabouret']= [];
			self.more['banque']= [];
			self.more['presentoir']= [];
			$('#lot_state').html("");
			$('#accbasic_state').html("");
			self.prices['lot'][0] = 0;
			self.prices['accbasics'] = [];
			self.updatePrices();
		},
		selectAccBasic:function(type,nom,quantity,id,pu,bType) {
			if (self.more[type][nom]==undefined) {
				self.more[type][nom]=0;
			}
			
			var t1 = quantity+" ";
			var t2 = "";
			
			if (bType == "toggler") {
				t1 = "";
			} else {
				if (quantity > 1) {
					t2 = "s";
				}
			}
			
			var prix = pu*quantity;
			if (self.more[type][nom]!=0 && quantity != 0) {
				var accBasic_html = "<span class='denom_price'>+"+quantity+" "+type+t2+" "+nom+"</span><br/><span class='sub_denom'>Prix unitaire : "+pu+"€</span><span class='detail_price'>"+prix+"€</span><hr/>";
				$('#accbasic_'+id+'_state').html(accBasic_html);
			} else if (self.more[type][nom]==0 && quantity !=0){
				var accBasic_html = "<span class='denom_price'>+"+quantity+" "+type+t2+" "+nom+"</span><br/><span class='sub_denom'>Prix unitaire : "+pu+"€</span><span class='detail_price'>"+prix+"€</span><hr/>";
				$('#accbasic_state').append("<li id='accbasic_"+id+"_state'>"+accBasic_html+"</li>");
			} else if (self.more[type][nom]!=0 && quantity == 0) {
				$('#accbasic_'+id+'_state').remove();
			}
			self.more[type][nom] = quantity;
			self.prices['accbasics'][id] = prix;
			self.updatePrices();
		},
		selectAccessoire:function(id,nom,quantity,pu) {
			if (self.accessoires[id]==undefined) {
				self.accessoires[id]=0;
			}
			
			var t1 = quantity+" ";
			var t2 = "";
			
			if (LOGISTIC.elements.accessoire.accessoires[id].button.type == "toggler") {
				t1 = "";
			} else {
				if (quantity > 1) {
					t2 = "s";
				}
			}
			
			var prix = pu*quantity;
			if (self.accessoires[id]!=0 && quantity != 0) {
				var accessoire_html = "<span class='denom_price'>"+t1+nom+t2+"</span><br/><span class='sub_denom'>Prix unitaire : "+pu+"€</span><span class='detail_price'>"+prix+"€</span><hr/>";
				$('#accessoire_'+id+'_state').html(accessoire_html);
			} else if (self.accessoires[id]==0 && quantity !=0){
				var accessoire_html = "<span class='denom_price'>"+t1+nom+t2+"</span><br/><span class='sub_denom'>Prix unitaire : "+pu+"€</span><span class='detail_price'>"+prix+"€</span><hr/>";
				$('#accessoire_state').append("<li id='accessoire_"+id+"_state'>"+accessoire_html+"</li>");
			} else if (self.accessoires[id]!=0 && quantity == 0) {
				$('#accessoire_'+id+'_state').remove();
			}
			self.accessoires[id] = quantity;
			self.prices['accessoires'][id] = prix;
			self.updatePrices();
		},
		selectAccfixe:function(id,nom,quantity,pu) {
			if (self.accfixes[id]==undefined) {
				self.accfixes[id]=0;
			}
			 
			var t1 = quantity+" ";
			var t2 = "";
			
			if (LOGISTIC.elements.accfixe.accfixes[id].button.type == "toggler") {
				t1 = "";
			} else {
				if (quantity > 1) {
					t2 = "s";
				}
			}
			
			var prix = pu*quantity;
			if (self.accfixes[id]!=0 && quantity != 0) {
				var accfixe_html = "<span class='denom_price'>"+t1+nom+t2+"</span><br/><span class='sub_denom'>Prix unitaire : "+pu+"€</span><span class='detail_price'>"+prix+"€</span><hr/>";
				$('#accfixe_'+id+'_state').html(accfixe_html);
			} else if (self.accfixes[id]==0 && quantity !=0){
				var accfixe_html = "<span class='denom_price'>"+t1+nom+t2+"</span><br/><span class='sub_denom'>Prix unitaire : "+pu+"€</span><span class='detail_price'>"+prix+"€</span><hr/>";
				$('#accfixe_state').append("<li id='accfixe_"+id+"_state'>"+accfixe_html+"</li>");
			} else if (self.accfixes[id]!=0 && quantity == 0) {
				$('#accfixe_'+id+'_state').remove();
			}
			self.accfixes[id] = quantity;
			self.prices['accfixes'][id] = prix;
			self.updatePrices();
		},
		selectOption:function(id,nom,quantity,pu) {
			if (self.options[id]==undefined) {
				self.options[id]=0;
			}
			
			var t1 = quantity+" ";
			var t2 = "";
			
			if (LOGISTIC.elements.option.options[id].button.type == "toggler") {
				t1 = "";
			} else {
				if (quantity > 1) {
					t2 = "s";
				}
			}
			
			var prix = pu*quantity;
			if (self.options[id]!=0 && quantity != 0) {
				var option_html = "<span class='denom_price'>"+t1+nom+t2+"</span><br/><span class='sub_denom'>Prix unitaire : "+pu+"€</span><span class='detail_price'>"+prix+"€</span><hr/>";
				$('#option_'+id+'_state').html(option_html);
			} else if (self.options[id]==0 && quantity !=0){
				var option_html = "<span class='denom_price'>"+t1+nom+t2+"</span><br/><span class='sub_denom'>Prix unitaire : "+pu+"€</span><span class='detail_price'>"+prix+"€</span><hr/>";
				$('#option_state').append("<li id='option_"+id+"_state'>"+option_html+"</li>");
			} else if (self.options[id]!=0 && quantity == 0) {
				$('#option_'+id+'_state').remove();
			}
			self.options[id] = quantity;
			self.prices['options'][id] = prix;
			self.updatePrices();
		},
		setReduc:function(id,nom,taux,applyTo) {
			self.isReduc[applyTo] = true;
			self.reducs[applyTo][id] = taux;
			$("#reducs_"+applyTo).append("<li><span class='denom_price'>Remise "+nom+" -"+taux+"%</span><br/><span id='reduc_"+applyTo+"_"+id+"' class='sub_total'>0€</span></li>");
		},
		updatePrices:function() {			
			self.subprices[0] = self.prices['stand'][0];
			self.subprices[1] = self.prices['lot'][0];
			for (p in self.prices['accbasics']) {
				self.subprices[1] += self.prices['accbasics'][p];
			}
			for (p in self.prices['accessoires']) {
				self.subprices[1] += self.prices['accessoires'][p];
			}
			for (p in self.prices['accfixes']) {
				self.subprices[1] += self.prices['accfixes'][p];
			}
			self.subprices[2] = 0;
			for (p in self.prices['options']) {
				self.subprices[2] += self.prices['options'][p];
			}
			
			self.price = 0;
			
			if (self.subprices[0] != 0 && self.subprices[0] != undefined) {
				for(var r in self.reducs['stand']) {
					if(self.reducs['stand'][r]!=0) {
						self.subprices[0] = Math.round(self.subprices[0]*(1-(self.reducs['stand'][r]/100))*100)/100;
						$("#reduc_stand_"+r).html(self.subprices[0]+"€");
					}
				}
				self.price += self.subprices[0];
			} else {
				for(var r in self.reducs['stand']) {
					$("#reduc_stand_"+r).html("0€");
				}				
			}
			
			if (self.subprices[1] != 0 && self.subprices[1] != undefined) {
				$('#r_state2_sub_total').html(self.subprices[1]+"€");
				for(var r in self.reducs['mobilier']) {
					if(self.reducs['mobilier'][r]!=0) {
						self.subprices[1] = Math.round(self.subprices[1]*(1-(self.reducs['mobilier'][r]/100))*100)/100;
						$("#reduc_mobilier_"+r).html(self.subprices[1]+"€");
					}
				}
				self.price += self.subprices[1];
			} else {
				for(var r in self.reducs['mobilier']) {
					$("#reduc_mobilier_"+r).html("0€");
				}
				$('#r_state2_sub_total').html("");
			}
			if (self.subprices[2] != 0 && self.subprices[2] != undefined) {
				$('#r_state3_sub_total').html(self.subprices[2]+"€");
				for(var r in self.reducs['options']) {
					if(self.reducs['options'][r]!=0) {
						self.subprices[2] = Math.round(self.subprices[2]*(1-(self.reducs['options'][r]/100))*100)/100;
						$("#reduc_options_"+r).html(self.subprices[2]+"€");
					}
				}
				self.price += self.subprices[2];
			} else {
				for(var r in self.reducs['options']) {
					$("#reduc_options_"+r).html("0€");
				}
				$('#r_state3_sub_total').html("");
			}
			$('#big_total_price').html("Total : "+self.price+"€");
			for(var r in self.reducs['tout']) {
				if(self.reducs['tout'][r]!=0) {
					self.price = Math.round(self.price*(1-(self.reducs['tout'][r]/100))*100)/100;
					$("#reduc_tout_"+r).html(self.price+"€");
				}
			}
			if (self.isReduc['tout']) {
				$('#big_total_price_2').html("Total : "+self.price+"€");
			}
			self.fillform();
		},
		fillform:function() {
			$('#type_stand').val(self.stand);
			$('#type_lot').val(self.lot);
			$('#color').val(LOGISTIC.buttons.buttonColor.selectedColor);
			$('#more_chaise').val(self.implode2D(self.more['chaise']));
			$('#more_table').val(self.implode2D(self.more['table']));
			$('#more_tabouret').val(self.implode2D(self.more['tabouret']));
			$('#more_banque').val(self.implode2D(self.more['banque']));
			$('#more_presentoir').val(self.implode2D(self.more['presentoir']));
			var t_accessoires = self.implode2D(self.accessoires);			
			var t_accfixes = self.implode2D(self.accfixes);
			
			if (t_accessoires != "" && t_accfixes != "") {
				$('#accessoires').val(t_accessoires+"#"+t_accfixes);
			} else {
				$('#accessoires').val(t_accessoires+t_accfixes);
			}			
			$('#options').val(self.implode2D(self.options));
			$('#price').val(self.price);
		},
		implode2D:function(arrays) {
			var i = 0;
			var string = "";
			for (var key in arrays) {
				if(arrays[key]!=0) {
					if(i > 0) {
						string += "#";
					}
					string += key+"/"+arrays[key];
					i++;
				}
			}
			return string;
		},
		remplace:function(expr,a,b) {
			var i = 0;
			while (i!=-1) {
				i = expr.indexOf(a,i);
				if (i>=0) {
					expr = expr.substring(0,i)+b+expr.substring(i+a.length);
					i += b.length;
				}
			}
			return expr;
		}
	}; // fin de classe
	// trick JavaScript pour émuler le self:: en PHP : on utilise une variable locale
	var self = LOGISTIC.system.recap;
})(); // fin de scope local

(function(){ // Pages début de scope local
	LOGISTIC.system = LOGISTIC.system || {};
	// déclaration de la classe de validation proprement dite
	LOGISTIC.system.pages = {
		// déclaration de nos variables statiques
		nbPages:3,
		currentPage:1,
		prefixe:"resa",
		// déclaration de nos méthodes
		selectPage:function( p ) {
			var page = "#"+self.prefixe+self.currentPage;
			$(page).hide(0);
			self.currentPage = p;
			page = "#"+self.prefixe+self.currentPage;
			$(page).show(0);
		},
		nextPage:function() {
			if (self.currentPage != self.nbPages) {
				self.selectPage(self.currentPage+1);
			}
		},
		lastPage:function() {
			if (self.currentPage != 1) {
				self.selectPage(self.currentPage-1);
			}
		}
	}; // fin de classe
	// trick JavaScript pour émuler le self:: en PHP : on utilise une variable locale
	var self = LOGISTIC.system.pages;
})(); // fin de scope local