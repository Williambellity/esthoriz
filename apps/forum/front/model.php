<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif <Johan Dufau>
 * @version	$Id: apps/brochure/front/model.php 0000 28-04-2011 Fofif $
 */

class ForumModel {
	private $db;
	
	public function __construct() {
		$this->db = WSystem::getDB();
	}
    
	public function getExposants($year = 0) {
		if ($year == 0) {
			$year = date('Y',time());
		}
		$prep = $this->db->prepare('
			SELECT entreprises.id AS id, name, cat, website2 FROM entreprises
			WHERE (`phoning_step` =6 AND entreprises.id!=1254  AND SUBSTRING(name,1,1)!="-")ORDER BY name ASC
 		');
 		/*$prep = $this->db->prepare('
			SELECT firmid, date, site FROM entreprises_registered
			WHERE YEAR(`date`)=2014  ORDER BY firmid ASC
 		');*/
		$prep->execute();
		$result = $prep->fetchAll(PDO::FETCH_ASSOC);
		
		return $result;
		
		
	}

    public function getFiche($id) {
        $result;
		$prep = $this->db->prepare('
			SELECT firmid, name, adress, city, postal_code, country, `entreprises_cats`.`id` AS cat_id, cat_name, website2, creation_date, pdg, ca, implantation, effectifs, cadres, age_moyen, salaire_moyen, sNb, sProfil, sType, eNb, eProfil, eType, marques, presentation, savoir FROM entreprises, brochure, entreprises_cats WHERE `entreprises`.`id` <> 1211 AND `entreprises`.`id` = `brochure`.`firmid` AND `entreprises`.`cat` = `entreprises_cats`.`id` ORDER BY name ASC
 		');
		$prep->execute();
		$result = $prep->fetchAll(PDO::FETCH_ASSOC);
		
		return $result;
    }
}

?>
