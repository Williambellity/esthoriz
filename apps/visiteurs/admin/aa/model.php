<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author	Fofif <Johan Dufau>
 * @version	$Id: apps/newsletter/admin/model.php 0001 06-10-2011 Fofif $
 */

class VisiteursAdminModel {
	private $db;
	
	public function __construct() {
		$this->db = WSystem::getDB();
	}
	
	public function countNewsletters() {
		$prep = $this->db->prepare('
			SELECT COUNT(*)
			FROM visiteurs
		');
		$prep->execute();
		return intval($prep->fetchColumn());
	}
	
	public function getNewsletters($from, $number, $order = 'ecole', $asc = true) {
		$prep = $this->db->prepare('
			SELECT nom, prenom, mail, ecole, connu1, connu2, connu3
			FROM visiteurs
			ORDER BY visiteurs.'.$order.' '.($asc ? 'ASC' : 'DESC').' 
			LIMIT :from, :number
		');//newsletters.'.$order.' '.($asc ? 'ASC' : 'DESC').'  
		//'.$order.' '.($asc ? 'ASC' : 'DESC').'
		$prep->bindParam(':from', $from, PDO::PARAM_INT);
		$prep->bindParam(':number', $number, PDO::PARAM_INT);
		$prep->execute();
		return $prep->fetchAll(PDO::FETCH_ASSOC);
	}
	

}

?>
