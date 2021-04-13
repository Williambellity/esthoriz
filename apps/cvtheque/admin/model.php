<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author	Fofif <Johan Dufau>
 * @version	$Id: apps/cvtheque/admin/model.php 0000 15-11-2011 Fofif $
 */

class CvthequeAdminModel {
	private $db;
	
	public function __construct() {
		$this->db = WSystem::getDB();
	}
	
	/**
	 * Création d'un créneau
	 */
	public function addTimeSlot($data) {
		$prep = $this->db->prepare('
			INSERT INTO cvtheque_schedule(name, firm, firmid, `desc`, start, duration, number, schedule)
			VALUES (:name, :firm, :firmid, :desc, :start, :duration, :number, :schedule)
		');
		$prep->bindParam(':name', $data['name']);
		$prep->bindParam(':firm', $data['firm']);
		$prep->bindParam(':firmid', intval($data['firmid']), PDO::PARAM_INT);
		$prep->bindParam(':desc', $data['desc']);
		$prep->bindParam(':start', $data['start']);
		$prep->bindParam(':duration', intval($data['duration']), PDO::PARAM_INT);
		$prep->bindParam(':number', intval($data['number']), PDO::PARAM_INT);
		$prep->bindParam(':schedule', $data['schedule']);
		return $prep->execute();
	}
}

?>
