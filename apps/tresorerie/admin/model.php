<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author	Fofif <Johan Dufau>
 * @version	$Id: apps/entreprise/admin/model.php 0005 15-08-2011 Fofif $
 */

class EntrepriseAdminModel {
	private $db;
	
	public function __construct() {
		$this->db = WSystem::getDB();
	}
	
	public function countFirms() {
		$prep = $this->db->prepare('
			SELECT COUNT(*) FROM entreprises
		');
		$prep->execute();
		return intval($prep->fetchColumn());
	}
	
	/**
	 * Vérifie qu'un id donné correspond bien à une entreprise
	 */
	public function validFirmId($id) {
		$prep = $this->db->prepare('
			SELECT * FROM entreprises WHERE id = :id
		');
		$prep->bindParam(':id', $id, PDO::PARAM_INT);
		$prep->execute();
		return $prep->rowCount() == 1;
	}
	
	/**
	 * Vérifie qu'un nom d'entreprise est disponible
	 * (pas de redondance dans la table)
	 * @param $name Nom de l'entreprise recherchée
	 * @param $exact Vérification sensible à la casse ? (LIKE BINARY)
	 */
	public function firmNameAvailable($name, $exact = false) {
		$prep = $this->db->prepare('
			SELECT * FROM entreprises WHERE name '.($exact ? 'LIKE BINARY' : 'LIKE').' :name
		');
		$prep->bindParam(':name', $name);
		$prep->execute();
		return $prep->rowCount() == 0;
	}
	
	/**
	 * Obtenir le dernier id inséré dans la table
	 * Peut s'avérer utile pour d'autres apps
	 */
	public function getLastFirmId() {
		$prep = $this->db->prepare('
			SELECT id FROM entreprises ORDER BY id DESC LIMIT 1
		');
		$prep->execute();
		return intval($prep->fetchColumn());
	}
	
	public function searchFirmsByName($term, $from, $number, $order = 'name', $asc = true) {
		$prep = $this->db->prepare('
			SELECT entreprises.id, name, adress, city, postal_code, country, DATE_FORMAT(entreprises.date, "%d/%m/%Y %H:%i") AS date, cat_name, phoning_user, phoning_step, nickname, choix_pack
			FROM entreprises
			LEFT JOIN entreprises_cats
			ON cat = entreprises_cats.id
			LEFT JOIN users
			ON phoning_user = users.id
			WHERE name LIKE "'.(strlen($term) > 1 ? '%' : '').$term.'%"
			ORDER BY '.$order.' '.($asc ? 'ASC' : 'DESC').'
			LIMIT :from, :number
		');
		$prep->bindParam(':from', $from, PDO::PARAM_INT);
		$prep->bindParam(':number', $number, PDO::PARAM_INT);
		$prep->execute();
		return $prep->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function countFirmsByName($term) {
		$prep = $this->db->prepare('
			SELECT COUNT(*)
			FROM entreprises
			WHERE name LIKE "'.(strlen($term) > 1 ? '%' : '').$term.'%"
		');
		$prep->execute();
		return intval($prep->fetchColumn());
	}
	
	public function searchFirmsByYear($year, $order = 'name', $asc = true) {
		$year = intval($year);
		if ($order == 'date') {
			$order = 'entreprises_registered.date';
		}
		$prep = $this->db->prepare('
			SELECT firmid AS id, name, country, cat_name, phoning_user, phoning_step, 
				DATE_FORMAT(entreprises_registered.date, "%d/%m/%Y %H:%i") AS date, nickname, choix_pack
			FROM entreprises_registered
			LEFT JOIN entreprises ON firmid = entreprises.id
			LEFT JOIN entreprises_cats ON cat = entreprises_cats.id
			LEFT JOIN users ON phoning_user = users.id
			'.(!empty($year) ? 'WHERE entreprises_registered.date BETWEEN "'.$year.'-01-01" AND "'.$year.'-12-31"' : '').'
			ORDER BY '.$order.' '.($asc ? 'ASC' : 'DESC').'
		');
		$prep->execute();
		return $prep->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function countFirmsByStep($step) {
		$prep = $this->db->prepare('
			SELECT COUNT(*)
			FROM entreprises
			WHERE phoning_step = :step
		');
		$prep->bindParam(':step', $step, PDO::PARAM_INT);
		$prep->execute();
		return intval($prep->fetchColumn());
	}
	
	public function searchFirmsByStep($step, $from, $number, $order = 'name', $asc = true) {
		if ($order == 'date') {
			$order = 'entreprises_contacts.date';
		}
		$prep = $this->db->prepare('
			SELECT entreprises.id, name, adress, city, postal_code, country, cat_name, phoning_user, phoning_step, 
				DATE_FORMAT(entreprises.date, "%d/%m/%Y %H:%i") AS date, nickname, choix_pack
			FROM entreprises
			LEFT JOIN entreprises_cats
			ON cat = entreprises_cats.id
			LEFT JOIN users
			ON phoning_user = users.id
			WHERE phoning_step = :step
			ORDER BY '.$order.' '.($asc ? 'ASC' : 'DESC').'
			LIMIT :from, :number
		');
		$prep->bindParam(':step', $step, PDO::PARAM_INT);
		$prep->bindParam(':from', $from, PDO::PARAM_INT);
		$prep->bindParam(':number', $number, PDO::PARAM_INT);
		$prep->execute();
		return $prep->fetchAll(PDO::FETCH_ASSOC);
	}
		
	public function searchFirmsByOwner($userid, $order = 'name', $asc = true) {
		$prep = $this->db->prepare('
			SELECT entreprises.id, name, adress, city, postal_code, country, DATE_FORMAT(entreprises.date, "%d/%m/%Y %H:%i") AS date, cat_name, phoning_user, phoning_step, nickname, choix_pack
			FROM entreprises
			LEFT JOIN entreprises_cats
			ON cat = entreprises_cats.id
			LEFT JOIN users
			ON phoning_user = users.id
			WHERE phoning_user = :userid
			ORDER BY '.$order.' '.($asc ? 'ASC' : 'DESC').'
		');
		$prep->bindParam(':userid', $userid, PDO::PARAM_INT);
		$prep->execute();
		return $prep->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function searchFirmsByOwner2($userid, $order = 'name', $asc = true) {
		$prep = $this->db->prepare('
			SELECT entreprises.id, name, adress, city, postal_code, country, rappeler, DATE_FORMAT(entreprises.date, "%d/%m/%Y %H:%i") AS date, cat_name, phoning_user, phoning_step, nickname, choix_pack
			FROM entreprises
			LEFT JOIN entreprises_cats
			ON cat = entreprises_cats.id
			LEFT JOIN users
			ON phoning_user = users.id
			WHERE phoning_user = :userid
			ORDER BY '.$order.' '.($asc ? 'ASC' : 'DESC').'
		');
		$prep->bindParam(':userid', $userid, PDO::PARAM_INT);
		$prep->execute();
		return $prep->fetchAll(PDO::FETCH_ASSOC);
	}
	
	/**
	 * Obtenir la liste des entreprises
	 */
	public function getFirmList($from, $number, $order = 'name', $asc = true) {
		$prep = $this->db->prepare('
			SELECT entreprises.id, name, adress, city, postal_code, country, DATE_FORMAT(entreprises.date, "%d/%m/%Y %H:%i") AS date, cat_name, phoning_user, phoning_step, nickname, choix_pack
			FROM entreprises
			LEFT JOIN entreprises_cats
			ON cat = entreprises_cats.id
			LEFT JOIN users
			ON phoning_user = users.id
			ORDER BY '.$order.' '.($asc ? 'ASC' : 'DESC').'
			LIMIT :from, :number
		');
		$prep->bindParam(':from', $from, PDO::PARAM_INT);
		$prep->bindParam(':number', $number, PDO::PARAM_INT);
		$prep->execute();
		return $prep->fetchAll(PDO::FETCH_ASSOC);
	}
	
	/**
	 * Chargement des données liées à une entreprise
	 */
	public function loadFirm($id) {
		$prep = $this->db->prepare('
			SELECT id, name, adress, city, postal_code, country, cat, DATE_FORMAT(date, "%d/%m/%Y %H:%i") AS date, phoning_user, phoning_step, phoning_comment, choix_pack
			FROM entreprises
			WHERE id = :id
		');
		$prep->bindParam(':id', $id, PDO::PARAM_INT);
		$prep->execute();
		return $prep->fetch(PDO::FETCH_ASSOC);
	}
	
	/**
	 * Création d'entreprise presque vide
	 */
	public function createBlankFirm($name) {
		$prep = $this->db->prepare('
			INSERT INTO entreprises(name, date)
			VALUES (:name, NOW())
		');
		$prep->bindParam(':name', $name);
		return $prep->execute();
	}
	
	/**
	 * Création d'entreprise
	 */
	public function createFirm($data) {
		$prep = $this->db->prepare('
			INSERT INTO entreprises(name, adress, city, postal_code, country, cat, date)
			VALUES (:name, :adress, :city, :postal_code, :country, :cat, NOW())
		');
		$prep->bindParam(':name', $data['name']);
		$prep->bindParam(':adress', $data['adress']);
		$prep->bindParam(':city', $data['city']);
		$prep->bindParam(':postal_code', $data['postal_code']);
		$prep->bindParam(':country', $data['country']);
		$prep->bindParam(':cat', $data['cat']);
		return $prep->execute();
	}
	
	/**
	 * Mise à jour d'une entreprise
	 */
	public function updateFirm($id, $data) {
		$string = '';
		foreach ($data as $key => $value) {
			$string .= $key.' = '.$this->db->quote($value).', ';
		}
		$string = substr($string, 0, -2);
		
		return $this->db->query('
			UPDATE entreprises
			SET '.$string.'
			WHERE id = '.$id
		);
	}
	
	/**
	 * Suppression d'une entreprise
	 */
	public function deleteFirm($firmid) {
		$prep = $this->db->prepare('
			DELETE FROM entreprises WHERE id = :firmid
		');
		$prep->bindParam(':firmid', $firmid, PDO::PARAM_INT);
		return $prep->execute();
	}
	
	/**
	 * Compte le nombre de contacts
	 */
	public function countContacts() {
		$prep = $this->db->prepare('
			SELECT COUNT(*)
			FROM entreprises_contacts
		');
		$prep->execute();
		return intval($prep->fetchColumn());
	}
	
	/**
	 * Compte le nombre de contacts définis comme "en attente"
	 */
	public function countWaitingContacts() {
		$prep = $this->db->prepare('
			SELECT COUNT(*)
			FROM entreprises_contacts
			WHERE firm_asked != ""
		');
		$prep->execute();
		return intval($prep->fetchColumn());
	}
	
	/**
	 * Vérifie qu'un id de contact est valide
	 */
	public function validContactId($cid) {
		$prep = $this->db->prepare('
			SELECT * FROM entreprises_contacts WHERE id = :cid
		');
		$prep->bindParam(':cid', $cid, PDO::PARAM_INT);
		$prep->execute();
		return $prep->rowCount() == 1;
	}
	
	/**
	 * Vérifie qu'un id correspond bien à un contact en attente
	 */
	public function validWaitingContactId($cid) {
		$prep = $this->db->prepare('
			SELECT * FROM entreprises_contacts WHERE id = :cid AND firm_asked != ""
		');
		$prep->bindParam(':cid', $cid, PDO::PARAM_INT);
		$prep->execute();
		return $prep->rowCount() == 1;
	}
	
	/**
	 * Obtenir le dernier id inséré dans la table des contacts
	 */
	public function getLastContactId() {
		$prep = $this->db->prepare('
			SELECT id FROM entreprises_contacts ORDER BY id DESC LIMIT 1
		');
		$prep->execute();
		return intval($prep->fetchColumn());
	}
	
	/**
	 * Charge les contacts liés à une entreprise
	 */
	public function loadFirmContacts($firmid) {
		$prep = $this->db->prepare('
			SELECT id, userid, civilite, nom, prenom, poste, langue, adresse, tel_fixe, tel_portable, fax, email, DATE_FORMAT(date, "%d/%m/%Y %H:%i") AS date
			FROM entreprises_contacts
			WHERE firmid = :firmid AND firm_asked = ""
			ORDER BY entreprises_contacts.date DESC
		');
		$prep->bindParam(':firmid', $firmid, PDO::PARAM_INT);
		$prep->execute();
		return $prep->fetchAll(PDO::FETCH_ASSOC);
	}
	
	/**
	 * Charge les réductions
	 */
	public function loadReducs() {
		$prep = $this->db->prepare('
			SELECT id, nom, taux, applyTo
			FROM logistic_cat_reduc
			WHERE 1=1
		');
		$prep->execute();
		return $prep->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function loadFirmReduc($id) {
		$prep = $this->db->prepare('
			SELECT reducs_id
			FROM logistic_reduc
			WHERE firmid = :firmid
		');
		$prep->bindParam(':firmid', $id, PDO::PARAM_INT);
		$prep->execute();
		$tab = $prep->fetch();
		$tab = explode('#',$tab['reducs_id']);
		$a = array();
		foreach ($tab as $key => $v) {
			$a[$v] = true;
		}
		return $a;
	}
	
	public function setFirmReduc($id,$select) {
		$prep = $this->db->prepare('
			SELECT count(*)
			FROM logistic_reduc
			WHERE firmid = :firmid
		');
		$prep->bindParam(':firmid', $id, PDO::PARAM_INT);
		$prep->execute();
		$temp = $prep->fetch();
		if($temp['count(*)']!=0) {
			$prep = $this->db->prepare('
				UPDATE logistic_reduc SET reducs_id = :reducs_id WHERE firmid = :firmid
			');
			$prep->bindParam(':reducs_id', $select);
			$prep->bindParam(':firmid', $id, PDO::PARAM_INT);
			return $prep->execute();
		} else {
			$prep = $this->db->prepare('
				INSERT INTO logistic_reduc(firmid,reducs_id) VALUES (:firmid, :reducs_id)
			');
			$prep->bindParam(':reducs_id', $select);
			$prep->bindParam(':firmid', $id, PDO::PARAM_INT);
			return $prep->execute();
		}
	}
	
	public function delFirmReduc($id) {
		$prep = $this->db->prepare('
			DELETE FROM logistic_reduc WHERE firmid = :firmid
		');
		$prep->bindParam(':firmid', $id, PDO::PARAM_INT);
		return $prep->execute();
	}
	
	/**
	 * Obtient la liste des contacts définis comme "en attente"
	 */
	public function getWaitingContacts() {
		$prep = $this->db->prepare('
			SELECT c.id, userid, firmid, firm_asked, civilite, nom, prenom, poste, langue, adresse, tel_fixe, tel_portable, fax, c.email, DATE_FORMAT(c.date, "%d/%m/%Y %H:%i") AS date, confirm
			FROM entreprises_contacts AS c
			LEFT JOIN users
			ON userid = users.id
			WHERE firm_asked != ""
			ORDER BY date DESC
		');
		$prep->execute();
		return $prep->fetchAll(PDO::FETCH_ASSOC);
	}
	
	/**
	 * Charge les informations d'un contact
	 */
	public function loadContact($cid) {
		$prep = $this->db->prepare('
			SELECT contacts.id, userid, firmid, firm_asked, civilite, nom, prenom, poste, langue, adresse, tel_fixe, tel_portable, fax, contacts.email, DATE_FORMAT(contacts.date, "%d/%m/%Y %H:%i") AS date, nickname, confirm
			FROM entreprises_contacts AS contacts
			LEFT JOIN users
			ON userid = users.id
			WHERE contacts.id = :cid
		');
		$prep->bindParam(':cid', $cid, PDO::PARAM_INT);
		$prep->execute();
		return $prep->fetch(PDO::FETCH_ASSOC);
	}
	
	/**
	 * Création d'un contact
	 */
	public function createContact($data) {
		$prep = $this->db->prepare('
			INSERT INTO entreprises_contacts(userid, firmid, civilite, nom, prenom, poste, langue, adresse, tel_fixe, tel_portable, fax, email, date)
			VALUES (:userid, :firmid, :civilite, :nom, :prenom, :poste, :langue, :adresse, :tel_fixe, :tel_portable, :fax, :email, NOW())
		');
		$prep->bindParam(':userid', $data['userid'], PDO::PARAM_INT);
		$prep->bindParam(':firmid', $data['firmid'], PDO::PARAM_INT);
		$prep->bindParam(':civilite', $data['civilite']);
		$prep->bindParam(':nom', $data['nom']);
		$prep->bindParam(':prenom', $data['prenom']);
		$prep->bindParam(':poste', $data['poste']);
		$prep->bindParam(':langue', $data['langue']);
		$prep->bindParam(':adresse', $data['adresse']);
		$prep->bindParam(':tel_fixe', $data['tel_fixe']);
		$prep->bindParam(':tel_portable', $data['tel_portable']);
		$prep->bindParam(':fax', $data['fax']);
		$prep->bindParam(':email', $data['email']);
		return $prep->execute() or die(var_dump($prep->errorInfo()));
	}
	
	/**
	 * Mise à jour d'un contact
	 */
	public function updateContact($cid, $data) {
		$string = '';
		foreach ($data as $key => $value) {
			if (!is_int($value)) {
				$string .= $key.' = '.$this->db->quote($value).', ';
			} else {
				$string .= $key.' = '.$value.', ';
			}
		}
		$string = substr($string, 0, -2);
		return $this->db->query('
			UPDATE entreprises_contacts
			SET '.$string.'
			WHERE id = '.$cid
		) or die(var_dump($this->db->errorInfo()));
	}
	
	/**
	 * Nettoie des utilisateurs pré-existants avec un email donné appartenant au groupe Entreprise
	 */
	public function cleanOtherUsers($userid, $email) {
		$prep = $this->db->prepare('
			DELETE FROM users WHERE email = :email AND id != :id AND groupe = 4
		');
		$prep->bindParam(':id', $userid, PDO::PARAM_INT);
		$prep->bindParam(':email', $email);
		return $prep->execute() or die(var_dump($prep->errorInfo()));
	}
	
	public function resetUserConfirm($userid) {
		$prep = $this->db->prepare('
			UPDATE users
			SET confirm = 0
			WHERE id = :userid
		');
		$prep->bindParam(':userid', $userid, PDO::PARAM_INT);
		return $prep->execute();
	}
	
	public function deleteContact($cid) {
		$prep = $this->db->prepare('
			DELETE FROM entreprises_contacts WHERE id = :cid
		');
		$prep->bindParam(':cid', $cid, PDO::PARAM_INT);
		return $prep->execute();
	}
	
	/**
	 * Récupère la liste complète des catégories
	 */
	public function getCatList() {
		$prep = $this->db->prepare('
			SELECT id, cat_name
			FROM entreprises_cats
			ORDER BY cat_name
		');
		$prep->execute();
		return $prep->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function createCat($name) {
		$prep = $this->db->prepare('
			INSERT INTO entreprises_cats(cat_name)
			VALUES (:name)
		');
		$prep->bindParam(':name', $name);
		return $prep->execute() or die (var_dump($prep->errorInfo()));
	}
	
	public function updateCat($id, $name) {
		return $this->db->query('
			UPDATE entreprises_cats
			SET cat_name = '.$this->db->quote($name).'
			WHERE id = '.$id
		);
	}
	
	public function deleteCat($id) {
		$prep = $this->db->prepare('
			DELETE FROM entreprises_cats WHERE id = :id
		');
		$prep->bindParam(':id', $id, PDO::PARAM_INT);
		return $prep->execute();
	}
	
	public function history($firmid, $desc, $contactid = 0) {
		$prep = $this->db->prepare('
			INSERT INTO entreprises_history(`userid`, `firmid`, `contactid`, `desc`)
			VALUES (:userid, :firmid, :contactid, :desc)
		');
		$prep->bindParam(':userid', $_SESSION['userid'], PDO::PARAM_INT);
		$prep->bindParam(':firmid', $firmid, PDO::PARAM_INT);
		$prep->bindParam(':contactid', $contactid, PDO::PARAM_INT);
		$prep->bindParam(':desc', $desc);
		return $prep->execute();
	}
	
	public function loadHistory($firmid) {
		$prep = $this->db->prepare('
			SELECT entreprises_history.id, `desc`, nickname, DATE_FORMAT(entreprises_history.date, "%d/%m/%Y @ %H:%i") AS date, contactid
			FROM entreprises_history
			LEFT JOIN users ON userid = users.id
			WHERE firmid = :firmid
			ORDER BY entreprises_history.date DESC
		');
		$prep->bindParam(':firmid', $firmid, PDO::PARAM_INT);
		$prep->execute();
		return $prep->fetchAll();
	}
	
	public function getPhoningUsers() {
		$prep = $this->db->prepare('
			SELECT id, nickname
			FROM users
			WHERE access = "all" OR access LIKE "%entreprise%"
			ORDER BY nickname
		');
		$prep->execute();
		return $prep->fetchAll(PDO::FETCH_ASSOC);
	}
	
	/**
	 * Vérifie si une entreprise est inscrite
	 */
	public function isRegistered($firmid, $year = 0) {
		// Par défaut = cette année
		if (empty($year)) {
			$year = intval(date('Y', time()));
		}
		$prep = $this->db->prepare('
			SELECT *
			FROM entreprises_registered 
			WHERE firmid = :firmid AND date BETWEEN "'.$year.'-01-01" AND "'.$year.'-12-31"
		');
		$prep->bindParam(':firmid', $firmid, PDO::PARAM_INT);
		$prep->execute();
		return $prep->rowCount() > 0;
	}
	
	/**
	 * Ajoute une entreprise à la table des inscriptions
	 * si elle n'est pas déjà inscrite
	 */
	public function register($firmid, $contactid = 0) {
		if (!$this->isRegistered($firmid)) {
			$prep = $this->db->prepare('
				INSERT INTO entreprises_registered (firmid, contactid, date)
				VALUES (:firmid, :contactid, NOW())
			');
			$prep->bindParam(':firmid', $firmid, PDO::PARAM_INT);
			$prep->bindParam(':contactid', $contactid, PDO::PARAM_INT);
			return $prep->execute();
		} else {
			return false;
		}
	}
	
	public function unregister($firmid) {
		$prep = $this->db->prepare('
			DELETE FROM entreprises_registered
			WHERE firmid = :firmid
		');
		$prep->bindParam(':firmid', $firmid, PDO::PARAM_INT);
		return $prep->execute();
	}
	
	/********************
	 * Shoutbox
	 ********************/
	public function getShoutboxMessages() {
		$prep = $this->db->prepare('
			SELECT id, userid, nickname, message, DATE_FORMAT(date, "%d.%m.%Y @ %H:%i") AS date
			FROM shoutbox
			ORDER BY id DESC
			LIMIT 40
		');
		$prep->bindParam(':userid', $_SESSION['userid'], PDO::PARAM_INT);
		$prep->execute();
		return $prep->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function insertShoutboxMessage($message) {
		$prep = $this->db->prepare('
			INSERT INTO shoutbox(userid, nickname, message, date)
			VALUES (:userid, :nickname, :message, NOW())
		');
		$prep->bindParam(':userid', $_SESSION['userid'], PDO::PARAM_INT);
		$prep->bindParam(':nickname', $_SESSION['nickname']);
		$prep->bindParam(':message', $message);
		return $prep->execute();
	}
}

?>
