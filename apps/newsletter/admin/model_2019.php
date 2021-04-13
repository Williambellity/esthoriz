<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author	Fofif <Johan Dufau>
 * @version	$Id: apps/newsletter/admin/model.php 0001 06-10-2011 Fofif $
 */

class NewsletterAdminModel {
	private $db;
	
	public function __construct() {
		$this->db = WSystem::getDB();
	}
	
	public function countNewsletters() {
		$prep = $this->db->prepare('
			SELECT COUNT(*)
			FROM newsletters
		');
		$prep->execute();
		return intval($prep->fetchColumn());
	}
	
	public function getNewsletters($from, $number, $order = 'date', $asc = true) {
		$prep = $this->db->prepare('
			SELECT newsletters.id, userid, objet, DATE_FORMAT(newsletters.date, "%d/%m/%Y %H:%i") AS date, destinataires, attachment, nickname
			FROM newsletters
			LEFT JOIN users ON userid = users.id
			ORDER BY newsletters.'.$order.' '.($asc ? 'ASC' : 'DESC').'
			LIMIT :from, :number
		');
		$prep->bindParam(':from', $from, PDO::PARAM_INT);
		$prep->bindParam(':number', $number, PDO::PARAM_INT);
		$prep->execute();
		return $prep->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function loadNewsletter($nid) {
		$prep = $this->db->prepare('
			SELECT newsletters.id, userid, objet, message, DATE_FORMAT(newsletters.date, "%d/%m/%Y %H:%i") AS date, destinataires, attachment, nickname
			FROM newsletters
			LEFT JOIN users ON userid = users.id
			WHERE newsletters.id = :nid
		');
		$prep->bindParam(':nid', $nid, PDO::PARAM_INT);
		$prep->execute();
		return $prep->fetch(PDO::FETCH_ASSOC);
	}
	
	public function saveNewsletter($data) {
		$prep = $this->db->prepare('
			INSERT INTO newsletters(userid, de, objet, message, destinataires, date, attachment)
			VALUES (:userid, :de, :objet, :message, :destinataires, NOW(), :attachment)
		');
		$prep->bindParam(':userid', $_SESSION['userid']);
		$prep->bindParam(':de', $data['de']);
		$prep->bindParam(':objet', $data['objet']);
		$prep->bindParam(':message', $data['message']);
		$prep->bindParam(':destinataires', $data['destinataires']);
		$prep->bindParam(':attachment', $data['attachment']);
		return $prep->execute();
	}
	
	public function getMailsArray($type) {
		switch ($type) {
			// Tout le monde
			case 'all':
				return $this->getAll();
			
			// Les inscrits de l'année en cours
			case 'registered':
				return $this->getRegistered();
			
			// Les inscrits qui n'ont pas choisi leur stand
			case 'stand':
				return $this->getWithoutStand();
			
			// Les inscrits qui n'ont pas rempli leur brochure
			case 'brochure':
				return $this->getWithoutBrochure();
			
			// Les inscrits qui n'ont pas rempli leur brochure ni choisi de stand
			case 'brochureAndStand':
				return $this->getWithoutBrochureAndStand();
		}
		return;
	}
	
	public function getAll() {
		// Pour les tests
		// return array(array('email' => 'johandufau@gmail.com'), array('email' => 'fofif_ii@hotmail.fr'));
		$prep = $this->db->prepare('
			SELECT email
			FROM entreprises_contacts
			WHERE email != ""
			ORDER BY email ASC
		');
		$prep->execute();
		return $prep->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function getRegistered() {
		$date = date('Y', time());
		$prep = $this->db->prepare('
			SELECT email
			FROM entreprises, entreprises_contacts, entreprises_registered
			WHERE entreprises.id = entreprises_registered.firmid AND entreprises_contacts.id = entreprises_registered.contactid AND userid<>0 AND phoning_step = 6 AND entreprises_registered.date BETWEEN \'2013-01-01\' AND \'2013-12-31\'
			ORDER BY name ASC
		');
		$prep->execute();
		return $prep->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function getWithoutStand() {
		$prep = $this->db->prepare('
			SELECT email
			FROM entreprises, entreprises_contacts, entreprises_registered
			WHERE entreprises.id = entreprises_registered.firmid AND entreprises_contacts.id = entreprises_registered.contactid AND userid<>0 AND phoning_step = 6 AND entreprises_registered.date BETWEEN \'2013-01-01\' AND \'2013-12-31\' AND entreprises.id NOT IN (Select firmid From logistic_firm)
			ORDER BY email ASC
		');
		$prep->execute();
		return $prep->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function getWithoutBrochure() {
		$date = date('Y', time());
		$prep = $this->db->prepare('
			SELECT email
			FROM entreprises, entreprises_contacts, entreprises_registered
			WHERE entreprises.id = entreprises_registered.firmid AND entreprises_contacts.id = entreprises_registered.contactid AND userid<>0 AND phoning_step = 6 AND entreprises_registered.date BETWEEN \'2013-01-01\' AND \'2013-12-31\' AND entreprises.id NOT IN (Select firmid From brochure)
			ORDER BY email ASC
		');
		$prep->execute();
		return $prep->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function getWithoutBrochureAndStand() {
		$date = date('Y', time());
		$prep = $this->db->prepare('
			SELECT email
			FROM entreprises, entreprises_contacts, entreprises_registered
			WHERE entreprises.id = entreprises_registered.firmid AND entreprises_contacts.id = entreprises_registered.contactid AND userid<>0 AND phoning_step = 6 AND entreprises_registered.date BETWEEN \'2013-01-01\' AND \'2013-12-31\' AND entreprises.id NOT IN (Select firmid From brochure) AND entreprises.id NOT IN (Select firmid From logistic_firm)
			ORDER BY email ASC
		');
		$prep->execute();
		return $prep->fetchAll(PDO::FETCH_ASSOC);
	}
}

?>
