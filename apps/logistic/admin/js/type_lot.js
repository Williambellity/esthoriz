	function nouvelleligne(id,type){
		return '<div id='+type+'_'+id+' style="margin:5px;">Nom : ' +			
		'<input type="text" name="'+ type +'_nom[]" value="" style="width: 25%;" />' +
		' Nombre : ' +
		'<select type="text" name="'+ type +'_number[]"  style="width: 15%;">' +
			'<option value="1">1</option>' +
			'<option value="2">2</option>' +
			'<option value="3">3</option>' +
			'<option value="4">4</option>' +
			'<option value="5">5</option>' +
		'</select>' +
		' Prix : ' +
		'<input type="text" name="'+type+'_prix[]" value=""  style="width: 15%;" /></div>';
	}
				
	// On affiche le nombre de ligne
	function ajouterligne(type){ 
			// On insert la nouvelle ligne
			var id = 1;
			var ligne = document.getElementById(type +'_'+ id);			
			while(ligne != null) {
				id++;
				ligne = document.getElementById(type +'_'+ id);		
			}
			var nouvelle_ligne = nouvelleligne(id,type);
			$(nouvelle_ligne).insertAfter('#'+type+'_'+ (id-1));
			//document.getElementById("addbutton").onclick = ajouterligne(id+1,type);
	}
	function suppligne(type){  
			// Si c'est la derière ligne	
			var id = 1;
			var ligne = document.getElementById(type +'_'+ id);			
			while(ligne != null) {
				id++;
				ligne = document.getElementById(type +'_'+ id);		
			}
			if(id> 2) {
				$('#'+ type +'_'+ (id-1)).remove();
			}		
	}