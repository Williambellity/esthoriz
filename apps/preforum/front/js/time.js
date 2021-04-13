TIMELINE = {};

(function(){ // Stand début de scope local
	TIMELINE.events =
	TIMELINE.events || {}; // création d'un sous namespace pour y stocker nos classes utilitaires si celuici n'est pas déjà créé
	// constructeur
	TIMELINE.events.event = function( id, nom, descriptif, hdebut, mdebut, hfin, mfin, concerne, categories, z ) {
		this.id = id;
        this.nom = nom;
        this.descriptif = descriptif;
        this.hdebut = hdebut;
        this.mdebut = mdebut;
        this.hfin = hfin;
        this.mfin = mfin;
        this.concerne = concerne;
        this.categories = categories;
        this.z = z;
        this.constructor();
	};
    // trick JavaScript pour émuler le self:: en PHP : on utilise une variable locale
	var self = TIMELINE.events.event,
	privateVariable = 0; // variable privée visible par toutes les instances
	// variable statique partagée pour tout le code
	self.listEvents = [];
    self.concerne = [];
    self.categories = [];
    self.colors = {0:"blue",1:"red",2:"green",3:"purple"};
    self.iterator = 0;
    self.max_iterator = 3;
	// variables et méthodes publiques propres à chaque instance
	TIMELINE.events.event.prototype = {
		id:0,
        nom:"",
        descriptif:"",
        hdebut:0,
        mdebut:0,
        hfin:0,
        mfin:0,
        z:0,
        concerne:[],
        categories:[],
		constructor:function() {
            var minutesStart;
            var minutesEnd;
            if(this.mdebut < 10) {
                minutesStart = "0"+this.mdebut;
            } else {
                minutesStart = this.mdebut;
            }
            if(this.mfin < 10) {
                minutesEnd = "0"+this.mfin;
            } else {
                minutesEnd = this.mfin;
            }
			$('#up_fl').append("<div id='event_"+this.id+"' class='ti_event'><div class='ti_flag'><span class='ti_textflag'>"+this.nom+"</span></div><div class='ti_timing'><span class='ti_estart'>"+this.hdebut+"h"+minutesStart+"</span><span class='ti_eend'>"+this.hfin+"h"+minutesEnd+"</span></div></div>");
            switch(this.z) {
                case 0:
                    $('#event_'+this.id).css({'top':'0px','height':'225px'});
                    $('#event_'+this.id+' .ti_flag').css({'height':'200px'});
                    break;

                case 1:
                    $('#event_'+this.id).css({'top':'50px','height':'200px'});
                    $('#event_'+this.id+' .ti_flag').css({'height':'175px'});
                    break;

                case 2:
                    $('#event_'+this.id).css({'top':'100px','height':'175px'});
                    $('#event_'+this.id+' .ti_flag').css({'height':'150px'});
                    break;

                case 3:
                    $('#event_'+this.id).css({'top':'150px','height':'150px'});
                    $('#event_'+this.id+' .ti_flag').css({'height':'125px'});
                    break;
            }
            for (var key_conc in this.concerne) {
                if(self.concerne.indexOf(this.concerne[key_conc])==-1) {
                    self.concerne.push(this.concerne[key_conc]);
                    $('#conc_cont_filter').append("<a id='conc_"+self.concerne.indexOf(this.concerne[key_conc])+"' class='button_filter'>"+this.concerne[key_conc]+"</a>");
                }
            }
            for (var key_cat in this.categories) {
                if(self.categories.indexOf(this.categories[key_cat])==-1) {
                    self.categories[self.iterator] = this.categories[key_cat];
                    var iteTemp = self.iterator%(self.max_iterator+1);
                    $('#cat_cont_filter').append("<a id='cat_"+self.categories.indexOf(this.categories[key_cat])+"' class='button_filter bf_"+self.colors[iteTemp]+"'>"+this.categories[key_cat]+"</a>");
                    $('#event_'+this.id+' .ti_timing').addClass("ti_"+self.colors[iteTemp]);
                    self.iterator++;
                } else {
                    var idCat = self.categories.indexOf(this.categories[key_cat]);
                    var idColor = idCat%(self.max_iterator+1);
                    $('#event_'+this.id+' .ti_timing').addClass("ti_"+self.colors[idColor]);
                }
            }
            $('#event_'+this.id+' .ti_textflag').tinyTips(this.descriptif);
		},
        hideEvent:function() {
            $('#event_'+this.id).hide(0);
        },
        showEvent:function() {
            var x = 0;
            var l = 0;
            x = (TIMELINE.system.line.calculateTime(this.hdebut,this.mdebut)-TIMELINE.system.line.min)*TIMELINE.system.line.scale;
            l = TIMELINE.system.line.scale * (TIMELINE.system.line.calculateTime(this.hfin,this.mfin)-TIMELINE.system.line.calculateTime(this.hdebut,this.mdebut));
            $('#event_'+this.id).css({'left':x});
            $('#event_'+this.id+' .ti_timing').css({'width':l});
            $('#event_'+this.id).show(0);
        },
        detailEvent:function() {
            //Affiche le détail de l'event
        }

	};
})(); // fin de scope local

(function(){ // Pages début de scope local
	TIMELINE.system = TIMELINE.system || {};
	// déclaration de la classe de validation proprement dite
	TIMELINE.system.line = {
		// déclaration de nos variables statiques
		widthBox:$('#corps_fl').width(),
        //heightBox:$('#corps_fl').height(),
		scale:1,
        conc_selected : [],
        cat_selected : [],
        min:0,
        max:0,
		// déclaration de nos méthodes
        initTabs:function() {
            self.conc_selected = [];
            self.cat_selected = [];
        },
		calculateScale:function() {
			self.scale = self.widthBox/(self.max-self.min);
		},
		display:function() {
            var toDisplay = new Array();
            self.min = 0;
            self.max = 0;
            var Dmin = "";
            var Dmax = "";
            //Appelle tous les events et fait un hide dessus
            for (var key in TIMELINE.events.event.listEvents) {
                TIMELINE.events.event.listEvents[key].hideEvent();
                
                var temp1 = false;
                var temp2 = false;
                for(var key_conc in TIMELINE.events.event.listEvents[key].concerne) {
                    if(self.conc_selected.indexOf(TIMELINE.events.event.listEvents[key].concerne[key_conc])!=-1) {
                        temp1 = true;
                    }
                }
                
                for(var key_cat in TIMELINE.events.event.listEvents[key].categories) {
                    if(self.cat_selected.indexOf(TIMELINE.events.event.listEvents[key].categories[key_cat])!=-1) {
                        temp2 = true;
                    }
                }

                if(temp1&&temp2) {
                    toDisplay.push(TIMELINE.events.event.listEvents[key]);
                    var start = self.calculateTime(TIMELINE.events.event.listEvents[key].hdebut,TIMELINE.events.event.listEvents[key].mdebut);
                    var end = self.calculateTime(TIMELINE.events.event.listEvents[key].hfin,TIMELINE.events.event.listEvents[key].mfin);
                    if(self.min == 0 || self.min>start) {
                        self.min = start;
                        Dmin = TIMELINE.events.event.listEvents[key].hdebut+"h";
                        if(TIMELINE.events.event.listEvents[key].mdebut<10) {
                            Dmin += "0"+TIMELINE.events.event.listEvents[key].mdebut;
                        } else {
                            Dmin += TIMELINE.events.event.listEvents[key].mdebut;
                        }                        
                    }
                    if(self.max == 0 || self.max<end) {
                        self.max = end;
                        Dmax = TIMELINE.events.event.listEvents[key].hfin+"h";
                        if(TIMELINE.events.event.listEvents[key].mfin<10) {
                            Dmax += "0"+TIMELINE.events.event.listEvents[key].mfin;
                        } else {
                            Dmax += TIMELINE.events.event.listEvents[key].mfin;
                        } 
                    }
                }
            }
            self.calculateScale();
            $('#start_time').html(Dmin);
            $('#end_time').html(Dmax);
            for(var dkey in toDisplay) {
                toDisplay[dkey].showEvent();
            }
			//Liste les "pointeurs" vers les events concernés et récupère le min et le max du time afin de calculer le scale (rapport widthBox sur la longueur max-min du temps hh.mm°100)
            //Execute un showEvent sur chaque event concerné
		},
        calculateTime:function(h,m) {
            return (h+(m/60));
        },
        toggleCategorie:function(idcat) {
            var i = self.cat_selected.indexOf(TIMELINE.events.event.categories[idcat]);
            if(i!=-1) {
                self.cat_selected.splice(i,1);
                $("#cat_"+idcat).removeClass('button_filter_selected');
                $("#cat_"+idcat).addClass('button_filter_unselected');
            } else {
                self.cat_selected.push(TIMELINE.events.event.categories[idcat]);
                $("#cat_"+idcat).removeClass('button_filter_unselected');
                $("#cat_"+idcat).addClass('button_filter_selected');
            }
            self.display();
        },
        toggleConcerne:function(idconc) {
            var i = self.conc_selected.indexOf(TIMELINE.events.event.concerne[idconc]);
            if(i!=-1) {
                self.conc_selected.splice(i,1);
                $("#conc_"+idconc).removeClass('button_filter_selected');
                $("#conc_"+idconc).addClass('button_filter_unselected');
            } else {
                self.conc_selected.push(TIMELINE.events.event.concerne[idconc]);
                $("#conc_"+idconc).removeClass('button_filter_unselected');
                $("#conc_"+idconc).addClass('button_filter_selected');
            }
            self.display();
        },
        allCategories:function() {
            self.cat_selected = [];
            for(var c in TIMELINE.events.event.categories) {
                self.cat_selected.push(TIMELINE.events.event.categories[c]);
                $("#cat_"+c).removeClass('button_filter_unselected');
                $("#cat_"+c).addClass('button_filter_selected');
            }
            self.display();
        },
        allConcernes:function()  {
            self.conc_selected = [];
            for(var c in TIMELINE.events.event.concerne) {
                self.conc_selected.push(TIMELINE.events.event.concerne[c]);
                $("#conc_"+c).removeClass('button_filter_unselected');
                $("#conc_"+c).addClass('button_filter_selected');
            }
            self.display();
        }
	}; // fin de classe
	// trick JavaScript pour émuler le self:: en PHP : on utilise une variable locale
	var self = TIMELINE.system.line;
})(); // fin de scope local