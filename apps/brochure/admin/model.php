<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author	Fofif <Johan Dufau>
 * @version	$Id: apps/brochure/admin/model.php 0000 25-10-2011 Fofif $
 */

class BrochureAdminModel {
	private $db;
	
	public function __construct() {
		$this->db = WSystem::getDB();
	}
	
	public function countBrochure() {
		$prep = $this->db->prepare('
			SELECT COUNT(*)
			FROM brochure
		');
		$prep->execute();
		return intval($prep->fetchColumn());
	}
	
	public function getBrochure($order = 'name', $asc = true) {
		if ($order == 'date') {
			$order = 'brochure.date';
		}
		
		$prep = $this->db->prepare('
			SELECT brochure.id, brochure.firmid, DATE_FORMAT(brochure.date, "%d/%m/%Y %H:%i") AS fdate, treatment, name, options
			FROM brochure
			LEFT JOIN entreprises ON brochure.firmid = entreprises.id
			LEFT JOIN logistic_firm ON brochure.firmid = logistic_firm.firmid
			ORDER BY '.$order.' '.($asc ? 'ASC' : 'DESC').'
		');
		$prep->execute();
		return $prep->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function isLogo($firmid) {
		if(file_exists(WT_PATH."upload/firms_logo/".$firmid.".png") && file_exists(WT_PATH."upload/firms_logo/thumb_".$firmid.".png")) {
			return true;
		} else {
			return false;
		}
	}
	
	public function updateTreatment($bid, $state) {
		$prep = $this->db->prepare('
			UPDATE brochure
			SET treatment = :treatment
			WHERE id = :bid
		');
		$prep->bindParam(':bid', $bid, PDO::PARAM_INT);
		$prep->bindParam(':treatment', intval($state), PDO::PARAM_INT);
		return $prep->execute();
	}
}

?>
