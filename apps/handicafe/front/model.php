<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Julien1619
 * @version	$Id: apps/profil/front/model.php 0000 11-06-2011 Julien1619 $
 */

class ProfilModel {
	private $db;
	
	public function __construct() {
		$this->db = WSystem::getDB();
	}
	
	public function getContact() {
		$prep = $this->db->prepare('
			SELECT civilite, nom, prenom, poste, langue, adresse, tel_fixe, tel_portable, fax, email
			FROM entreprises_contacts
			WHERE userid = :userid
		');
		$prep->bindParam(':userid', $_SESSION['userid'], PDO::PARAM_INT);
		//$prep->bindParam(':admin', $_SESSION['nickname']);
		$prep->execute();
		return $prep->fetch(PDO::FETCH_ASSOC);
	}
	
	/**
	 * Mise à jour d'un contact
	 */
	public function updateContact($data) {
		$prep = $this->db->prepare('
			UPDATE entreprises_contacts SET civilite = :civilite, nom = :nom, prenom = :prenom, poste = :poste, langue = :langue, 
				adresse = :adresse, tel_fixe = :tel_fixe, tel_portable = :tel_portable, fax = :fax
			WHERE userid = :userid
		');
		$prep->bindParam(':civilite', $data['civilite']);
		$prep->bindParam(':nom', $data['nom']);
		$prep->bindParam(':prenom', $data['prenom']);
		$prep->bindParam(':poste', $data['poste']);
		$prep->bindParam(':langue', $data['langue']);
		$prep->bindParam(':adresse', $data['adresse']);
		$prep->bindParam(':tel_fixe', $data['tel_fixe']);
		$prep->bindParam(':tel_portable', $data['tel_portable']);
		$prep->bindParam(':fax', $data['fax']);
		$prep->bindParam(':userid', $_SESSION['userid'], PDO::PARAM_INT);
		return $prep->execute();
	}
	
	public function getState() {
		return array(0, "Test");
	}
}

?>
