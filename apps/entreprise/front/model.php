<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif <Johan Dufau>
 * @version	$Id: apps/entreprise/front/model.php 0000 28-04-2011 Fofif $
 */

class EntrepriseModel {
	private $db;
	
	public function __construct() {
		$this->db = WSystem::getDB();
	}
	
	/**
	 * Charge les informations d'un contact
	 */
	public function loadContact($userid) {
		$prep = $this->db->prepare('
			SELECT entreprises_contacts.id, userid, firmid, firm_asked, civilite, nom, prenom, poste, langue, adresse, tel_fixe, tel_portable, fax, email, DATE_FORMAT(entreprises_contacts.date, "%d/%m/%Y %H:%i") AS date, name
			FROM entreprises_contacts
			LEFT JOIN entreprises ON firmid = entreprises.id
			WHERE userid = :userid
		');
		$prep->bindParam(':userid', $userid, PDO::PARAM_INT);
		$prep->execute();
		return $prep->fetch(PDO::FETCH_ASSOC);
	}
	
	/**
	 * Création d'un contact
	 */
	public function createContact($data) {
		$prep = $this->db->prepare('
			INSERT INTO entreprises_contacts(userid, firm_asked, civilite, nom, prenom, poste, adresse, tel_fixe, tel_portable, fax, email)
			VALUES (:userid, :firm, :civilite, :nom, :prenom, :poste, :adresse, :tel_fixe, :tel_portable, :fax, :email)
		');
		$prep->bindParam(':userid', $data['userid'], PDO::PARAM_INT);
		$prep->bindParam(':firm', $data['firm']);
		$prep->bindParam(':civilite', $data['civi']);
		$prep->bindParam(':nom', $data['name']);
		$prep->bindParam(':prenom', $data['fname']);
		$prep->bindParam(':poste', $data['poste']);
		$prep->bindParam(':adresse', $data['address']);
		$prep->bindParam(':tel_fixe', $data['tel']);
		$prep->bindParam(':tel_portable', $data['mob']);
		$prep->bindParam(':fax', $data['fax']);
		$prep->bindParam(':email', $data['mail']);
		$prep2 = $this->db->prepare('
			INSERT INTO entreprises_complement(userid)
			VALUES (:userid)
		');
		$prep2->bindParam(':userid', $data['userid'], PDO::PARAM_INT);
		$prep2-> execute();
		//$prep->bindParam(':admin', $_SESSION['nickname']);
		return $prep->execute() or die(var_dump($prep->errorInfo()));
	}
	
	public function validateEmail($email) {
		$prep = $this->db->prepare('
			UPDATE users
			SET confirm = ""
			WHERE nickname = :email
		');
		$prep->bindParam(':email', $email);
		return $prep->execute();
	}
	
	public function getState() {
		return array(0, "Vous êtes bien inscrit dans nos bases de données.");
	}
}

?>
