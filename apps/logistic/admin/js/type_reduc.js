var first = true;

function editReduc(id, nom, taux, applyTo) {
	// Affichage du bloc
	if (first) {
		document.getElementById('edition').style.display = 'block';
		first = false;
	}
	
	// Remplissage des données
	document.getElementById('idEdit').value = id;
	document.getElementById('nomEdit').value = nom;
	document.getElementById('tauxEdit').value = taux;
	document.getElementById('applyToEdit').selectedIndex = applyTo;
	
	return false;
}