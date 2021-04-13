<?php
class PackModel {
	private $db;
	
	public function __construct() {
		$this->db = WSystem::getDB();
	}
	
	/**
	 * Récupère la liste complète des packs
	 */
	public function getPackList() {
		$prep = $this->db->prepare('
			SELECT *
			FROM pack_liste
			ORDER BY prix
		');
		$prep->execute();
		return $prep->fetchAll();
	}
	
	public function getConfirmation() {
		$prep = $this->db->prepare('
			SELECT confirmation
			FROM entreprises
			WHERE id=:firmid
		');
		$prep->bindParam(':firmid', $_SESSION['firmid']);
		$prep->execute();
		$confirmation = $prep->fetch();
		$prep->closeCursor();
		return $confirmation;
	}
	public function getPackChoisi() {
		$prep = $this->db->prepare('
			SELECT choix_pack
			FROM entreprises
			WHERE id=:firmid
		');
		$prep->bindParam(':firmid', $_SESSION['firmid']);
		$prep->execute();
		$choix_pack = $prep->fetch();
		$prep->closeCursor();
		
		$prep = $this->db->prepare('
			SELECT *
			FROM pack_liste
			WHERE id=:choix_pack
		');
		$prep->bindParam(':choix_pack', $choix_pack['choix_pack']);
		$prep->execute();
		return $prep->fetch();
	}
	
	/**
	 * Prend en compte le choix de l'entreprise
	 */
	
	public function choisir($data) {
		$prep = $this->db->prepare('
			UPDATE entreprises
			SET choix_pack=:choix
			WHERE id=:id_entreprise
		');
		$prep->bindParam(':id_entreprise', $_SESSION['firmid']);
		$prep->bindParam(':choix', $data['choix']);
		return $prep->execute();
	}
}