var first = true;

function editOption(id, taille, prix, description, countable, categorie) {
	// Affichage du bloc
	if (first) {
		document.getElementById('edition').style.display = 'block';
		first = false;
	}
	
	// Remplissage des données
	document.getElementById('idEdit').value = id;
	document.getElementById('nomEdit').value = taille;
	document.getElementById('prixEdit').value = prix;
	document.getElementById('descriptionEdit').value = description;
	document.getElementById('countableEdit').selectedIndex = countable;
	document.getElementById('categorieEdit').selectedIndex = categorie;
	
	return false;
}