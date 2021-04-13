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
        $uid = $_SESSION['userid'];
		$prep = $this->db->prepare('
			SELECT repas_nbr, repas_info, intervenant_nbr, intervenant_nom, parking, handicafe,`entreprises`.id,name,adress,postal_code,city,country,civilite,nom,prenom,tel_fixe,email 
			FROM `entreprises` INNER JOIN `entreprises_complement` ON `entreprises`.id=`entreprises_complement`.firmid 
			INNER JOIN entreprises_contacts on `entreprises`.id=`entreprises_contacts`.firmid 
			WHERE `entreprises_contacts`.userid = :userid ORDER BY repas_nbr DESC
		');
		$prep->bindParam(':userid', $uid, PDO::PARAM_INT);
		//$prep->bindParam(':admin', $_SESSION['nickname']);
		$prep->execute();
		return $prep->fetch(PDO::FETCH_ASSOC);
	}
	
    public function repairContact(){
        $prep = $this->db->prepare('
            UPDATE entreprises_complement SET firmid=:firmid WHERE userid=:userid
        ');
        $prep->bindParam(':firmid', $_SESSION['firmid'], PDO::PARAM_INT);
        $prep->bindParam(':userid', $_SESSION['userid'], PDO::PARAM_INT);
        $prep->execute();
    }
    
	/**
	 * Mise à jour d'un contact
	 */
	public function updateContact($data) {
		$prep = $this->db->prepare('
			UPDATE entreprises_complement SET repas_nbr = :repas_nbr, repas_info = :repas_info, intervenant_nbr = :intervenant_nbr, intervenant_nom = :intervenant_nom, parking = :parking, handicafe = :handicafe
			WHERE userid = :userid
		');
		$prep->bindParam(':repas_nbr', $data['repas_nbr']);
		$prep->bindParam(':repas_info', $data['repas_info']);
		$prep->bindParam(':intervenant_nbr', $data['intervenant_nbr']);
		$prep->bindParam(':intervenant_nom', $data['intervenant_nom']);
		$prep->bindParam(':parking', $data['parking']);
		$prep->bindParam(':handicafe', $data['handicafe']);
		$prep->bindParam(':userid', $_SESSION['userid'], PDO::PARAM_INT);
		return $prep->execute();
	}
	
	public function getState() {
		return array(0, "Vos données sont enregistrées.");
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
