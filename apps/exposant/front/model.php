<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif <Johan Dufau>
 * @version	$Id: apps/brochure/front/model.php 0000 28-04-2011 Fofif $
 */

class evenementModel {
	private $db;
	
	public function __construct() {
		$this->db = WSystem::getDB();
	}
    
	public function getExposants($year = 0) {
		if ($year == 0) {
			$year = date('Y',time());
		}
		/*$prep = $this->db->prepare('
			SELECT `entreprises`.id AS id, `entreprises`.name, `entreprises`.cat, `brochure`.website AS website2 FROM `entreprises`,`brochure` 
			WHERE ( `entreprises`.id=`brochure`.firmid AND `phoning_step` =6 AND entreprises.id!=1254 AND SUBSTRING(name,1,1)!="-")ORDER BY name ASC 
 		');*/
		
        /*$prep = $this->db->prepare('
			SELECT `entreprises`.id AS id, `entreprises`.name, `entreprises`.cat, `brochure`.website AS website2 FROM `entreprises`,`brochure` 
			WHERE ( `entreprises`.id=`brochure`.firmid AND entreprises.id!=1254 AND SUBSTRING(name,1,1)!="-")ORDER BY name ASC 
 		');*/
        
        $prep = $this->db->prepare('
			SELECT id FROM `entreprises` WHERE id!=1254 AND SUBSTRING(name,1,1)!="-"
 		');
        
		$prep->execute();
		$result = $prep->fetchAll(PDO::FETCH_ASSOC);
//		print_r( $result);
		return $result;
		
		
	}

}

?>
