<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif <Johan Dufau>
 * @version	$Id: apps/brochure/front/model.php 0000 28-04-2011 Fofif $
 */

class BrochureModel {
	private $db;
	
	public function __construct() {
		$this->db = WSystem::getDB();
	}

    /**
	 * Fonction inverse de implode_data, selon $model = Array($key_tt,$key1,$key2...)
	*/
	private function explode_2D($string,$model = null) {
		$data = explode('#',$string);
		$return = array();
		$i = 0;
		foreach($data as $row) {			
			$data_row = explode('/',$row);
			if($model != null) {
				$j = 1;
				foreach($data_row as $the_data) {
					$tmp[$model[$j]] = $the_data;
					$return[strtolower($tmp[$model[0]])] = $tmp;
					$j++;
				}
			} else {
				$return[$i] = $data_row;
			}
			$i++;
		}
		return $return;
	}

    public function testPub($firmid) {
        $query = "SELECT options FROM logistic_firm WHERE firmid = :firm";
		$sth = $this->db->prepare($query);
		$sth->bindParam(':firm',$firmid);
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
        $test = false;
        if(!empty($result[0]) && $result[0]['options']!="") {
			$options_default = $this->explode_2D($result[0]['options'],Array('id','id','nombre'));
            foreach($options_default as $key=>$option) {
				if($option['nombre'] > 0 && $option['id']>=14 && $option['id']<=16) {
					$test = true;
				}
			}
        }
        return $test;
    }
	
	private function testBrochure($firmid) {
		//teste l'existence du firmid dans la base brochure, b true demande la vérification supplémentaire de la non viditude des champs important
		$prep = $this->db->prepare('
			SELECT count(*)
			FROM brochure
			WHERE firmid = :firmid
		');
		$prep->bindParam(':firmid', $firmid, PDO::PARAM_INT);
		$prep->execute();
		$result = $prep->fetchColumn();
		
		if(intval($result['count(*)']) > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function writeBrochure($firmid, $data) {
		if($this->testBrochure($firmid)) {
			return $this->updateBrochure($firmid, $data);
		} else {
			return $this->insertBrochure($firmid, $data);
		}
	}
	
	public function isLogo($firmid) {
		if(file_exists(WT_PATH."upload/firms_logo/".$firmid.".png") && file_exists(WT_PATH."upload/firms_logo/thumb_".$firmid.".png")) {
			return true;
		} else {
			return false;
		}
	}

    public function isPub($firmid) {
		if(file_exists(WT_PATH."upload/pubBrochure/2013/".$firmid.".pdf") && file_exists(WT_PATH."upload/pubBrochure/2013/".$firmid.".jpg")) {
			return true;
		} else {
			return false;
		}
	}
	
	public function getCatList() {
		$prep = $this->db->prepare('
			SELECT id, cat_name
			FROM entreprises_cats
			ORDER BY cat_name
		');
		$prep->execute();
		return $prep->fetchAll(PDO::FETCH_ASSOC);
	}
	
	/**
	 * Mise à jour d'une entreprise
	 */
	private function updateBrochure($firmid, $data) {
	
		$prep = $this->db->prepare('
			UPDATE entreprises SET name = :name, adress = :adress, city = :city, postal_code = :postal_code, country = :country, cat = :cat WHERE id = :firmid
		');
		$prep->bindParam(':name', $data['name']);
		$prep->bindParam(':adress', $data['adress']);
		$prep->bindParam(':city', $data['city']);
		$prep->bindParam(':postal_code', $data['postal_code'], PDO::PARAM_INT);
		$prep->bindParam(':country', $data['country']);
		$prep->bindParam(':cat', $data['cat'], PDO::PARAM_INT);
		$prep->bindParam(':firmid', $firmid, PDO::PARAM_INT);
		$prep->execute() or die(var_dump($prep->errorInfo()));
		
		$prep = $this->db->prepare('
			UPDATE brochure SET website = :website, creation_date = :creation_date, pdg = :pdg, ca = :ca, implantation = :implantation, effectifs = :effectifs, cadres = :cadres, age_moyen = :age_moyen, salaire_moyen = :salaire_moyen, sNb = :sNb, sProfil = :sProfil, sType = :sType, eNb = :eNb, eProfil = :eProfil, eType = :eType, marques = :marques, presentation = :presentation, savoir = :savoir, date = CURRENT_TIMESTAMP WHERE firmid = :firmid
		');
		$prep->bindParam(':website', $data['website']);
		$prep->bindParam(':creation_date', $data['creation_date']);
		$prep->bindParam(':pdg', $data['pdg']);
		$prep->bindParam(':ca', $data['ca']);
		$prep->bindParam(':implantation', $data['implantation']);
		$prep->bindParam(':effectifs', $data['effectifs']);
		$prep->bindParam(':cadres', $data['cadres']);
		$prep->bindParam(':age_moyen', $data['age_moyen']);
		$prep->bindParam(':salaire_moyen', $data['salaire_moyen']);
		$prep->bindParam(':sNb', $data['sNb']);
		$prep->bindParam(':sProfil', $data['sProfil']);
		$prep->bindParam(':sType', $data['sType']);
		$prep->bindParam(':eNb', $data['eNb']);
		$prep->bindParam(':eProfil', $data['eProfil']);
		$prep->bindParam(':eType', $data['eType']);
		$prep->bindParam(':marques', $data['marques']);
		$prep->bindParam(':presentation', $data['presentation']);
		$prep->bindParam(':savoir', $data['savoir']);
		$prep->bindParam(':firmid', $firmid, PDO::PARAM_INT);
		$prep->execute();
		
		return true;
	}
	
	private function insertBrochure($firmid, $data) {
		$result;
	
		$prep = $this->db->prepare('
			UPDATE entreprises SET name = :name, adress = :adress, city = :city, postal_code = :postal_code, country = :country, cat = :cat, date = CURRENT_TIMESTAMP WHERE id = :firmid
		');
		$prep->bindParam(':name', $data['name']);
		$prep->bindParam(':adress', $data['adress']);
		$prep->bindParam(':city', $data['city']);
		$prep->bindParam(':postal_code', $data['postal_code'], PDO::PARAM_INT);
		$prep->bindParam(':country', $data['country']);
		$prep->bindParam(':cat', $data['cat'], PDO::PARAM_INT);
		$prep->bindParam(':firmid', $firmid, PDO::PARAM_INT);
		$result = $prep->execute();
		
		unset($prep);
		
		$prep = $this->db->prepare('
			INSERT INTO brochure(firmid, website, creation_date, pdg, ca, implantation, 
			effectifs, cadres, age_moyen, salaire_moyen, 
			sNb, sProfil, sType, eNb, eProfil, eType, marques, 
			presentation, savoir, date) VALUES (:firmid, :website, :creation_date, :pdg, 
			:ca, :implantation, :effectifs, :cadres, :age_moyen, 
			:salaire_moyen, :sNb, :sProfil, :sType, :eNb, :eProfil, 
			:eType, :marques, :presentation, :savoir, CURRENT_TIMESTAMP)
		');
		$prep->bindParam(':firmid', $firmid, PDO::PARAM_INT);
		$prep->bindParam(':website', $data['website']);
		$prep->bindParam(':creation_date', $data['creation_date']);
		$prep->bindParam(':pdg', $data['pdg']);
		$prep->bindParam(':ca', $data['ca']);
		$prep->bindParam(':implantation', $data['implantation']);
		$prep->bindParam(':effectifs', $data['effectifs']);
		$prep->bindParam(':cadres', $data['cadres']);
		$prep->bindParam(':age_moyen', $data['age_moyen']);
		$prep->bindParam(':salaire_moyen', $data['salaire_moyen']);
		$prep->bindParam(':sNb', $data['sNb']);
		$prep->bindParam(':sProfil', $data['sProfil']);
		$prep->bindParam(':sType', $data['sType']);
		$prep->bindParam(':eNb', $data['eNb']);
		$prep->bindParam(':eProfil', $data['eProfil']);
		$prep->bindParam(':eType', $data['eType']);
		$prep->bindParam(':marques', $data['marques']);
		$prep->bindParam(':presentation', $data['presentation']);
		$prep->bindParam(':savoir', $data['savoir']);
		$result ^= $prep->execute();
		
		return $result;
	}
	
	public function getBrochure($firmid) {
		$out1; $out2; $result;
		$prep = $this->db->prepare('
			SELECT name, adress, city, postal_code, country, cat FROM entreprises WHERE id = :firmid
		');		
		$prep->bindParam(':firmid', $firmid, PDO::PARAM_INT);
		$prep->execute();
		$out1 = $prep->fetch(PDO::FETCH_ASSOC);
		
		$prep = $this->db->prepare('
			SELECT website, creation_date, pdg, ca, implantation, 
			effectifs, cadres, age_moyen, salaire_moyen, 
			sNb, sProfil, sType, eNb, eProfil, eType, marques, 
			presentation, savoir FROM brochure WHERE firmid = :firmid
		');		
		$prep->bindParam(':firmid', $firmid, PDO::PARAM_INT);
		$prep->execute();
		$out2 = $prep->fetch(PDO::FETCH_ASSOC);
		if(!empty($out2)) {
			$result = array_merge($out1,$out2);
		} else {
			$formData2 = array("website" => "", "creation_date" => "", "pdg" => "", "ca" => "", "implantation" => "",
			"effectifs" => "", "cadres" => "", "age_moyen" => "", "salaire_moyen" => "",
			"sNb" => "", "sProfil" => "", "sType" => "", "eNb" => "", "eProfil" => "", "eType" => "", "marques" => "",
			"presentation" => "", "savoir" => "");
			$result = array_merge($out1,$formData2);
		}
		
		return $result;
	}
	
	/*public function getState() {
		if (testBrochure(true)) {
			return array(2, "Vos informations sont bien enregistrées.");
		} else if (testBrochure()) {
			return array(1, "Les informations enregistrées sont incomplètes.");
		} else {
			return array(0, "Pour figurer dans la brochure, saisissez les informations demandées.");
		}
	}*/
	
	public function getState() {
		if ($this->testBrochure($_SESSION['firmid'])) {
			return array(0, "Les données concernant votre entreprise sont bien enregistrées.");
		} else {
			return array(8, "Pour figurer dans la brochure Visiteurs, saisissez les informations demandées.");
		}
	}

    public function getStatePub() {
		if (!$this->testPub($_SESSION['firmid'])) {
			return array(2, "Vous n'avez pas réservé de page de pub dans la brochure Visiteurs.");
		} else if (!$this->isPub($_SESSION['firmid'])){
			return array(9, "Vous avez réservé une page de pub mais vous ne l'avez pas encore fournie.");
		} else {
            return array(0, "Votre page de pub est réservée et enregistrée.");
        }
	}
}

?>
