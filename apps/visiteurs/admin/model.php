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
	
	public function countVisiteurs($id) {
	if($id==3){
		$prep = $this->db->prepare('
			SELECT COUNT(ecole)
			FROM visiteurs WHERE clecheck=1
		');
		$prep->execute();
		return intval($prep->fetchColumn());
	}
	else{
		$prep = $this->db->prepare('
			SELECT COUNT(*)
			FROM visiteurs WHERE (ecole='.$id.' and clecheck=1)
		');
		$prep->execute();
		return intval($prep->fetchColumn());}
	}
	
	public function countPresents($id) {
	if($id==3){
		$prep = $this->db->prepare('
			SELECT COUNT(ecole)
			FROM stat_visiteurs WHERE present="X"
		');
		$prep->execute();
		return intval($prep->fetchColumn());
	}
	else{
		$prep = $this->db->prepare('
			SELECT COUNT(*)
			FROM stat_visiteurs WHERE (ecole='.$id.' and present="X")
		');
		$prep->execute();
		return intval($prep->fetchColumn());}
	}
	
	
	public function countVisiteurs2() {
		$prep = $this->db->prepare('
			SELECT COUNT(*)
			FROM visiteurs 
		');
		$prep->execute();
		return intval($prep->fetchColumn());
	}
	
	public function countVisiteursAutres() {
		$prep = $this->db->prepare('
			SELECT COUNT(*)
			FROM visiteurs WHERE ecole= 33
		');
		$prep->execute();
		return intval($prep->fetchColumn());
	}
	

	public function countVisiteursNonVal() {
		$prep = $this->db->prepare('
			SELECT COUNT(*)
			FROM visiteurs WHERE clecheck=0
		');
		$prep->execute();
		return intval($prep->fetchColumn());
	}
	
	public function countConnu($id,$i) {
	if($id==3){
		$prep = $this->db->prepare('
			SELECT COUNT(*)
			FROM visiteurs WHERE clecheck=1 and connu'.$i.'=1
		');
		$prep->execute();
		return intval($prep->fetchColumn());
	}
	else{
		$prep = $this->db->prepare('
			SELECT COUNT(*)
			FROM visiteurs WHERE (ecole='.$id.' and connu'.$i.'=1 and clecheck=1)
		');
		$prep->execute();
		return intval($prep->fetchColumn());}
	}
	
	
	public function getVisiteurs($from, $number, $order = 'ecole', $asc = true) {
		$prep = $this->db->prepare('
			SELECT nom, prenom, mail, ecole, connu1, connu2, connu3
			FROM visiteurs WHERE clecheck=1
			ORDER BY visiteurs.'.$order.' '.($asc ? 'ASC' : 'DESC').' 
			LIMIT :from, :number
		');//newsletters.'.$order.' '.($asc ? 'ASC' : 'DESC').'  
		//'.$order.' '.($asc ? 'ASC' : 'DESC').'
		$prep->bindParam(':from', $from, PDO::PARAM_INT);
		$prep->bindParam(':number', $number, PDO::PARAM_INT);
		$prep->execute();
		return $prep->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getVisiteursNonVal($from, $number, $order = 'ecole', $asc = true) {
		$prep = $this->db->prepare('
			SELECT nom, prenom, mail, ecole, connu1, connu2, connu3
			FROM visiteurs WHERE clecheck!=1
			ORDER BY visiteurs.'.$order.' '.($asc ? 'ASC' : 'DESC').' 
			LIMIT :from, :number
		');//newsletters.'.$order.' '.($asc ? 'ASC' : 'DESC').'  
		//'.$order.' '.($asc ? 'ASC' : 'DESC').'
		$prep->bindParam(':from', $from, PDO::PARAM_INT);
		$prep->bindParam(':number', $number, PDO::PARAM_INT);
		$prep->execute();
		return $prep->fetchAll(PDO::FETCH_ASSOC);
	}

	
	public function getAutres($from, $number, $order = 'autres', $asc = true) {
		$prep = $this->db->prepare('
			SELECT nom, prenom, autres, connu1, connu2, connu3
			FROM visiteurs WHERE clecheck=1 and ecole=33
			ORDER BY visiteurs.'.$order.' '.($asc ? 'ASC' : 'DESC').' 
			LIMIT :from, :number
		');//newsletters.'.$order.' '.($asc ? 'ASC' : 'DESC').'  
		//'.$order.' '.($asc ? 'ASC' : 'DESC').'
		$prep->bindParam(':from', $from, PDO::PARAM_INT);
		$prep->bindParam(':number', $number, PDO::PARAM_INT);
		$prep->execute();
		return $prep->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function getEcoles() {
		$prep = $this->db->prepare('
			SELECT id, ecole
			FROM ecoles ORDER BY id
		');
		$prep->execute();
		return $prep->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function listPersons() {
		$prep = $this->db->prepare('
			SELECT nom,prenom,mail,`visiteurs`.ecole,`ecoles`.ecole,mailent,photo_droit,connu1,connu2,connu3,connu4,connu5, connu6,clecheck  
			FROM `visiteurs`,`ecoles` WHERE (`visiteurs`.ecole = `ecoles`.id) ORDER BY nom ASC
		');
		$prep->execute();
		return $prep->fetchAll();
	}	
	
	

}

?>
