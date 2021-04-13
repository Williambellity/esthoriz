<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Julien1619
 * @version	$Id: apps/profil/front/model.php 0000 11-06-2011 Julien1619 $
 */

class QuestionsModel {
	private $db;
	
	public function __construct() {
		$this->db = WSystem::getDB();
	}
    
    public function addMessage($data) {
		$req = $this->db->prepare("INSERT INTO questions(txt) VALUES (:txt)");
        $req->execute(array(
            "txt"=>$data
        ));
	}
}

?>
