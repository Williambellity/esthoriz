<?php
/**
 * Wity CMS
 * Syst�me de gestion de contenu pour tous.
 *
 * @author Fofif <Johan Dufau>
 * @version	$Id: apps/brochure/front/model.php 0000 28-04-2011 Fofif $
 */

class cvModel {
	private $db;
	
	public function __construct() {
		$this->db = WSystem::getDB();
	}
	
	
	public function writecv($data) {
			return $this->insertcv($data);
	}
	
	

}

?>
