var first = true;

function editStand(id, taille, prix, nbstand) {
	// Affichage du bloc
	if (first) {
		document.getElementById('edition').style.display = 'block';
		first = false;
	}
	
	// Remplissage des données
	document.getElementById('idEdit').value = id;
	document.getElementById('tailleEdit').value = taille;
	document.getElementById('prixEdit').value = prix;
	document.getElementById('nbstandEdit').value = nbstand;
	
	return false;
}