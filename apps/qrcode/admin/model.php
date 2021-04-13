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
		$prep = $this->db->prepare('UPDATE visiteurs SET Present=1 WHERE visiteurs.mail=:mail
		');
		$prep->bindParam(':mail', $data);
		return $prep->execute();
	}

}

?>
