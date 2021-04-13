<?php


class BonusModel {
	private $db;
	
	public function __construct() {
		$this->db = WSystem::getDB();
	}
	
	
	public function writeBonus($data) {
			return $this->insertBonus($data);
	}
	//normal
	public function recup(){
	$prep= $this->db->prepare('
			SELECT *
			FROM entreprises
			WHERE id=:firmid 
		');
	$prep->bindParam(":firmid",$_SESSION["firmid"]);
	$prep->execute();	
	$prep1 = $prep->fetch();
	$prep->closeCursor();
	
	$prep= $this->db->prepare('
			SELECT *
			FROM pack_liste
			WHERE id= :prep 
		');
		$prep->bindParam(":prep",$prep1["choix_pack"]);
		$prep->execute();
		return $prep->fetch();
	}
	
	public function ent(){
	$prep= $this->db->prepare('
			SELECT *
			FROM entreprises
			WHERE id=:firmid 
		');
	$prep->bindParam(":firmid",$_SESSION["firmid"]);
	$prep->execute();	
		return $prep->fetch();
	}
	
	public function contact(){
	$prep= $this->db->prepare('
			SELECT * FROM entreprises_contacts 
			WHERE firmid=:firmid 
			ORDER BY date DESC
		');
		
	$prep->bindParam(":firmid",$_SESSION["firmid"]);
	$prep->execute();	
	return $prep->fetch();
	}
	
	public function factu(){
	$prep= $this->db->prepare('
			SELECT * FROM facturation 
			WHERE firmid=:firmid 
			ORDER BY id DESC
		');
		
	$prep->bindParam(":firmid",$_SESSION["firmid"]);
	$prep->execute();	
	return $prep->fetch();
	}
	
	//Facture au choix
	
	public function recup2(){
	$prep= $this->db->prepare('
			SELECT *
			FROM entreprises
			WHERE id=:firmid 
		');
	$prep->bindParam(":firmid",$_POST["Nathan"]);
	$prep->execute();	
	$prep1 = $prep->fetch();
	$prep->closeCursor();
	
	$prep= $this->db->prepare('
			SELECT *
			FROM pack_liste
			WHERE id= :prep 
		');
		$prep->bindParam(":prep",$prep1["choix_pack"]);
		$prep->execute();
		return $prep->fetch();
	}
	
	public function ent2(){
	$prep= $this->db->prepare('
			SELECT *
			FROM entreprises
			WHERE id=:firmid 
		');
	$prep->bindParam(":firmid",$_POST["Nathan"]);
	$prep->execute();	
		return $prep->fetch();
	}
	
	public function contact2(){
	$prep= $this->db->prepare('
			SELECT * FROM entreprises_contacts 
			WHERE firmid=:firmid 
			ORDER BY date DESC
		');
		
	$prep->bindParam(":firmid",$_POST["Nathan"]);
	$prep->execute();	
	return $prep->fetch();
	}
	
	public function factu2(){
	$prep= $this->db->prepare('
			SELECT * FROM facturation 
			WHERE firmid=:firmid 
			ORDER BY id DESC
		');
		
	$prep->bindParam(":firmid",$_POST["Nathan"]);
	$prep->execute();	
	return $prep->fetch();
	}
	
	
	private function insertBonus($data) {
		$result;
		unset($prep);
		
		$prep = $this->db->prepare('
			INSERT INTO bonus(firmid, pub1, pub2, pub3, pub4, pub5, ) VALUES (:firmid, :pub1, :pub2, :pub3, 
			:pub4, :pub5, :pub6)
		');
		

		$prep->bindParam(':firmid', $_SESSION['firmid']);
		$prep->bindParam(':pub1', $data['pub1']);
		$prep->bindParam(':pub2', $data['pub2']);
		$prep->bindParam(':pub3', $data['pub3']);
		$prep->bindParam(':pub4', $data['pub4']);
		$prep->bindParam(':pub5', $data['pub5']);
		$prep->bindParam(':pub6', $data['pub6']);
		if ($data['pub1']==null)
			$data['pub1'] = 0;
		if ($data['pub2']==null)
			$data['pub2'] = 0;
		if ($data['pub3']==null)
			$data['pub3'] = 0;
		if ($data['pub4']==null)
			$data['pub4'] = 0;
		if ($data['pub5']==null)
			$data['pub5'] = 0;	
		if ($data['pub6']==null)
			$data['pub6'] = 0;	
		$result=$prep->execute();
		
		return $result;
	}


}

?>
