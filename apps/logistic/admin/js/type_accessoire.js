var first = true;

function editAccessoire(id, nom, prix, description, for_all, add_to_lot) {
	// Affichage du bloc
	if (first) {
		document.getElementById('edition').style.display = 'block';
		first = false;
	}
	
	// Remplissage des données
	document.getElementById('idEdit').value = id;
	document.getElementById('nomEdit').value = nom;
	document.getElementById('prixEdit').value = prix;
	document.getElementById('descriptionEdit').value = description;
	document.getElementById('for_all'+for_all+'Edit').checked = true;
	if(for_all == 0) {
		for(i = 0; i < add_to_lot.length;i++) {
			for(j = 0; j < document.getElementById('add_to_lotEdit').length; j++) {
				if(document.getElementById('add_to_lotEdit').options[j].value == add_to_lot[i][0]) {
					document.getElementById('add_to_lotEdit').options[j].selected = true;
				}
			}
		}
	}
	return false;
}

function in_array (needle, haystack, argStrict) {
    // Checks if the given value exists in the array  
    // 
    // version: 1103.1210
    // discuss at: http://phpjs.org/functions/in_array
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: vlado houba
    // +   input by: Billy
    // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
    // *     example 1: in_array('van', ['Kevin', 'van', 'Zonneveld']);
    // *     returns 1: true
    // *     example 2: in_array('vlado', {0: 'Kevin', vlado: 'van', 1: 'Zonneveld'});
    // *     returns 2: false
    // *     example 3: in_array(1, ['1', '2', '3']);
    // *     returns 3: true
    // *     example 3: in_array(1, ['1', '2', '3'], false);
    // *     returns 3: true
    // *     example 4: in_array(1, ['1', '2', '3'], true);
    // *     returns 4: false
    var key = '',
        strict = !! argStrict;
 
    if (strict) {
        for (key in haystack) {
            if (haystack[key] === needle) {
                return true;
            }
        }
    } else {
        for (key in haystack) {
            if (haystack[key] == needle) {
                return true;
            }
        }
    }
 
    return false;
}