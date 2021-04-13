<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif <Johan Dufau>
 * @version	$Id: apps/cvtheque/front/model.php 0000 28-04-2011 Fofif $
 */

class CvthequeModel {
	private $db;
	
	public function __construct() {
		$this->db = WSystem::getDB();
	}
	
	public function getSchedule() {
		$prep = $this->db->prepare('
			SELECT id, name, firm, `desc`, start, duration, number, schedule, date
			FROM cvtheque_schedule
			ORDER BY  name ASC
		');
		$prep->execute();
		return $prep->fetchAll();
	}
	
	/**
	 * Récupère l'emploi du temps d'un intervenant précis
	 */
	public function getIntervenantData($interid) {
		$prep = $this->db->prepare('
			SELECT id, name, firm, `desc`, start, duration, number, schedule, date
			FROM cvtheque_schedule
			WHERE id = :interid
		');
		$prep->bindParam(':interid', $interid, PDO::PARAM_INT);
		$prep->execute();
		return $prep->fetch();
	}
	
	/**
	 * Récupère les réservations sous forme de tableau array('interId-15:00' => '')
	 */
	public function getBooking() {
		$prep = $this->db->prepare('
			SELECT regid, userid, firstname, lastname, email, ecole, diplome, secteur, recherche, interid, schedid, date
			FROM cvtheque_booking
		');
		$prep->execute();
		$booking = array();
		while ($line = $prep->fetch()) {
			$booking[$line['interid'].'-'.$line['schedid']] = '';
		}
		return $booking;
	}
	
	/**
	 * Récupère les réservations sous forme de tableau array('interId-15:00' => '')
	 */
	public function getBookingOfUser($userid) {
		$prep = $this->db->prepare('
			SELECT regid, userid, firstname, lastname, email, ecole, diplome, secteur, recherche, interid, schedid, date
			FROM cvtheque_booking
			WHERE userid = :userid
		');
		$prep->bindParam(':userid', $userid, PDO::PARAM_INT);
		$prep->execute();
		return $prep->fetch();
	}
	
	/**
	 * Récupère les réservations
	 */
	public function getBookingData($fecole, $frech) {
		$string = 'WHERE ';
		if (!empty($fecole)) {
			$string .= 'ecole = "'.$fecole.'" ';
		}
		if (!empty($frech)) {
			if (strlen($string) > 6) {
				$string .= 'AND ';
			}
			$string .= 'recherche = "'.$frech.'" ';
		}
		if (strlen($string) == 6) {
			$string = '';
		}
		
		$prep = $this->db->prepare('
			SELECT regid, userid, firstname, lastname, email, ecole, diplome, secteur, recherche, interid, schedid, b.date, cv, lettre, name
			FROM cvtheque_booking AS b
			LEFT JOIN cvtheque_schedule AS s ON interid = s.id
			'.$string.'
			ORDER BY interid, schedid
		');
		$prep->execute();
		return $prep->fetchAll();
	}
	
    private function testBook($userid) {
		//teste l'existence du booking
		$prep = $this->db->prepare('
			SELECT count(*)
			FROM cvtheque_booking
			WHERE userid = :userid
		');
		$prep->bindParam(':userid', $userid, PDO::PARAM_INT);
		$prep->execute();
		return intval($prep->fetchColumn()) > 0;
	}

    public function alreadyBooked() {
        if(!empty($_SESSION['userid'])) {
            return $this->testBook($_SESSION['userid']);
        } else {
            return false;
        }
    }
	
	/**
	 * Création ou update d'un contact
	 */
	public function bookIt($data) {
        if($this->testBook($data['userid'])) {
			$prep = $this->db->prepare('
			    UPDATE cvtheque_booking 
				SET lastname = :lastname, firstname = :firstname, email = :email, ecole = :ecole, diplome = :diplome, secteur = :secteur, recherche = :recherche, interid = :interid, schedid = :schedid, date = NOW(), cv = :cv, lettre = :lettre WHERE userid = :userid			    
		    ');
        } else {
            $prep = $this->db->prepare("
			    INSERT INTO cvtheque_booking(userid, lastname, firstname, email, ecole, diplome, secteur, recherche, interid, schedid, cv, lettre)
			    VALUES (:userid, :lastname, :firstname, :email, :ecole, :diplome, :secteur, :recherche, :interid, :schedid, :cv, :lettre)
		    ");
        }
		
		$prep->bindParam(':userid', $data['userid'], PDO::PARAM_INT);
		$prep->bindParam(':lastname', $data['lastname']);
		$prep->bindParam(':firstname', $data['firstname']);
		$prep->bindParam(':email', $data['mail']);
		$prep->bindParam(':ecole', $data['ecole']);
		$prep->bindParam(':diplome', $data['diplome']);
		$prep->bindParam(':secteur', $data['secteur']);
		$prep->bindParam(':recherche', $data['recherche']);
		$prep->bindParam(':interid', $data['interid'], PDO::PARAM_INT);
		$prep->bindParam(':schedid', $data['schedid'], PDO::PARAM_INT);
		$prep->bindParam(':cv', $data['cv']);
		$prep->bindParam(':lettre', $data['lettre']);
		return $prep->execute() or die(var_dump($prep->errorInfo()));
	}
}

?>
