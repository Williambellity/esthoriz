<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif <Johan Dufau>
 * @version	$Id: apps/brochure/front/model.php 0000 28-04-2011 Fofif $
 */

class ApiModel {
	private $db;
	
	public function __construct() {
		$this->db = WSystem::getDB();
	}
        
        
        public function getUniversities(){
            $prep = $this->db->prepare('SELECT * from ecoles');
                
            $prep->execute();
            return $prep->fetchAll(PDO::FETCH_ASSOC);
        }
        
        public function authVisiteur($mail){
            $prep = $this->db->prepare('
			SELECT *
			FROM visiteurs
			WHERE (mail = :mail )
		');
            $prep->bindParam(':mail', $mail);
            $prep->execute();
            $result =  $prep->fetch();
            return $result;
        }
        public function authUser($login,$password){
			$originalPassword = $password;
            $password = sha1($password); // pour rester cohérent avec le hachage du cms. Attention c'est dangereux de ne pas saler le mdp
            
            $prep = $this->db->prepare('
			SELECT id, nickname, email, groupe, access, firstname, lastname
			FROM users
			WHERE (nickname = :nickname OR email = :nickname) AND password = :password
		');
		$prep->bindParam(':nickname', $login);
		$prep->bindParam(':password', $password);
		$prep->execute();
		//echo $login."--".$originalPassword;
		$result =  $prep->fetch();
                return $result;
                //echo ($result["id"]);
                /*
                if($result && $result["groupe"]=="4"){
                    //$prep = $this->db->prepare("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES");
                    $prep = $this->db->prepare("SELECT * FROM entreprises_contacts"); 
                    $prep->bindParam(':userId', $result["id"]);
                    $prep->execute();
                    return $prep->fetchAll(PDO::FETCH_ASSOC);
                    //echo json_encode($prep->fetchAll(PDO::FETCH_ASSOC));
                }
                 * 
                 */
        }

        public function getEntreprises(){
                $prep = $this->db->prepare('SELECT id,name,adress,city,postal_code,country,cat,choix_pack, phoning_step from entreprises');
                
                $prep->execute();
                return $prep->fetchAll(PDO::FETCH_ASSOC);
        }
        
        public function getEntreprisesCategories(){
            $prep = $this->db->prepare('SELECT id, cat_name as label from entreprises_cats ');
            $prep->execute();
            return $prep->fetchAll(PDO::FETCH_ASSOC);
        }
        
        public function getEntrepriseDetails($firmid){
            $prep = $this->db->prepare('SELECT id,name,adress,city,postal_code,country,cat from entreprises WHERE id = :firmid');
            
            $prep->bindParam(':firmid', $firmid, PDO::PARAM_INT);
            $prep->execute();
            $entreprise =  $prep->fetchAll(PDO::FETCH_ASSOC);
            if($entreprise) $entreprise = $entreprise[0];
            
            
            $prep = $this->db->prepare('SELECT * from brochure WHERE firmid = :firmid');
            $prep->bindParam(':firmid', $firmid, PDO::PARAM_INT);
            $prep->execute();
            $details=  $prep->fetchAll(PDO::FETCH_ASSOC);
            if($details) $entreprise["details"] = $details[0];
            return $entreprise;
        }
   
}

?>
