<?php


class EditionentModel {
	private $db;
	
	public function __construct() {
		$this->db = WSystem::getDB();
	}
	
	
	public function writeEditionent($firmid,$data) {
			return $this->insertEditionent($firmid,$data);
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
			SELECT *
			FROM facturation
			WHERE firmid=:firmid 
			ORDER BY id DESC
		');
	$prep->bindParam(":firmid",$_SESSION["firmid"]);
	$prep->execute();	
	$resultat=$prep->fetch();
		return $resultat;
	}
	
	
	private function insertEditionent($firmid,$data) {
		$result;
		unset($prep);
		

		
		$prep = $this->db->prepare('
			UPDATE entreprises SET name = :name, adress = :adress, city = :city, postal_code = :postal_code, country = :country  WHERE id = :firmid
		');
		
		$prep->bindParam(':name', $data['name']);
		$prep->bindParam(':adress', $data['adress']);
		$prep->bindParam(':city', $data['city']);
		$prep->bindParam(':postal_code', $data['postal_code'], PDO::PARAM_INT);
		$prep->bindParam(':country', $data['country']);
		$prep->bindParam(':firmid', $firmid, PDO::PARAM_INT);
		$result = $prep->execute();
		
		//$prep->closeCursor();
		unset($prep);
		
		$prep = $this->db->prepare('
			INSERT INTO facturation(firmid,adress, city, postal_code,country) VALUES (:firmid, :adress2, :city2, :postal_code2, 
			:country2)');
		/*$prep = $this->db->prepare('
			UPDATE facturation SET firmid = :firmid, adress = :adress2, city = :city2, postal_code = :postal_code2, country = :country2  WHERE firmid = :firmid
		');*/
		$prep->bindParam(':firmid', $firmid, PDO::PARAM_INT);
		$prep->bindParam(':adress2', $data['adress2']);
		$prep->bindParam(':city2', $data['city2']);
		$prep->bindParam(':postal_code2', $data['postal_code2']);
		$prep->bindParam(':country2', $data['country2']);
		$result = $prep->execute();
		
		
		unset($prep);
		
		$prep = $this->db->prepare('
			INSERT INTO entreprises_contacts(firmid,nom, prenom, tel_fixe,email, date) VALUES (:firmid, :nom, :prenom, :tel_fixe, 
			:email, NOW())');
		$prep->bindParam(':firmid', $firmid, PDO::PARAM_INT);
		$prep->bindParam(':nom', $data['nom']);
		$prep->bindParam(':prenom', $data['prenom']);
		$prep->bindParam(':tel_fixe', $data['tel_fixe']);
		$prep->bindParam(':email', $data['email']);
		$result = $prep->execute();
		
		return $result;
		
	}


}

?>
