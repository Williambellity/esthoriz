<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif
 * @version	$Id: apps/news/front/model.php 0002 31-07-2011 Fofif $
 */

class TestModel {
	private $db;
	
	public function __construct() {
		$this->db = WSystem::getDB();
	}
	
	/*public function test() {
		$query = $this->db->prepare("SELECT COUNT(*) FROM news");
		$query->execute();
		return $query->fetchColumn();
	}*/
}

?>