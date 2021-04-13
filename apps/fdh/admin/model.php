<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author	Fofif <Johan Dufau>
 * @version	$Id: apps/fdh/admin/model.php 0000 10-11-2012 Fofif $
 */

class FdhModel {
	private $db;
	
	public function __construct() {
		$this->db = WSystem::getDB();
	}
	
	/**
	 * Récupère la capacité maxi d'un bus
	 */
	public function maxSeats($idb) {
		$prep = $this->db->prepare('
			SELECT max
			FROM fdh_bus
			WHERE idb = :idb
		');
		$prep->bindParam(':idb', $idb, PDO::PARAM_INT);
		$prep->execute();
		return intval($prep->fetchColumn());
	}
	//nb places  limites 2015
	public function limite2015() {
		$prep = $this->db->prepare('
			SELECT id
			FROM fdh_inscrits
		');
		return $prep->fetch();
	}
	
	/**
	 * Compte le nombre d'inscrits dans le bus $idb
	 */
	public function countRegistered($idb) {
		$prep = $this->db->prepare('
			SELECT SUM(somme)
			FROM(
				(SELECT COUNT(*) somme 
					FROM fdh_inscrits
					WHERE busaller = :idb)
				UNION ALL
				(SELECt COUNT(*) somme
					FROM fdh_inscrits
					WHERE busretour = :idb)
				) table_temp
		');
		$prep->bindParam(':idb', $idb, PDO::PARAM_INT);
		$prep->execute();
		return intval($prep->fetchColumn());
	}
	
	/**
	 * Compte le nombre de places restantes dans le bus $idb
	 */
	public function placesLeft($idb) {
		return max($this->maxSeats($idb) - $this->countRegistered($idb), 0);
	}
	
	public function getBusInfo($idb ) {
		$prep = $this->db->prepare('
			SELECT idb, Lieu, Heure, Numero, max, Depart
			FROM fdh_bus
			WHERE idb = :idb
		');
		$prep->bindParam(':idb', $idb, PDO::PARAM_INT);
		$prep->execute();
		$bus = $prep->fetch();
		if (!empty($bus)) {
			$bus['lieu'] = $bus['Lieu'];
			$bus['heure'] = date_format(date_create($bus['Heure']), 'H:i');
			$bus['numero'] = $bus['Numero'];
            $bus['depart'] = $bus['Depart'];
		}
		return $bus;
	}
	
	public function getAllBusInfo() {
		$prep = $this->db->prepare('
			SELECT idb, Lieu, Heure, Numero, max,Depart
			FROM fdh_bus
			ORDER BY Lieu, Heure, Numero
		');
		$prep->execute();
		return $prep->fetchAll();
	}
	
	public function getPerson($id) {
		$prep = $this->db->prepare('
			SELECT id, ecole, nom, prenom, busaller, busretour, pay_mod
			FROM fdh_inscrits
			WHERE id = :id
		');
		$prep->bindParam(':id', $id, PDO::PARAM_INT);
		$prep->execute();
		$data = $prep->fetch();
		if (!empty($data)) {
			$data['ecole'] = intval($data['ecole']);
		}
		return $data;
	}
	
	public function registerPerson($data, $idb) {
		$prep = $this->db->prepare('
			INSERT INTO fdh_inscrits(ecole, nom, prenom, busaller, busretour, pay_mod)
			VALUES (:ecole, :nom, :prenom, :busaller, :busretour, :paymod)
		');
		$prep->bindParam(':ecole', $_SESSION['userid']);
		$prep->bindParam(':nom', $data['nom']);
		$prep->bindParam(':prenom', $data['prenom']);
		$prep->bindParam(':busaller', $data['busaller']);
		$prep->bindParam(':busretour', $data['busretour']);
		$prep->bindParam(':paymod', $data['paymod']);
		return $prep->execute();
	}
	
	public function deletePerson($id) {
		$prep = $this->db->prepare('
			DELETE FROM fdh_inscrits
			WHERE id = :id
		');
		$prep->bindParam(':id', $id, PDO::PARAM_INT);
		return $prep->execute();
	}
	
	public function listPersons($ecole) {
		$prep = $this->db->prepare('
			SELECT fdh_inscrits.id AS id, nickname, busaller, busretour, nom, prenom, DATE_FORMAT(heure_inscription, "%d-%m-%Y %H:%i") AS heure, pay_mod, ecole
			FROM fdh_inscrits
			LEFT JOIN users
			ON fdh_inscrits.ecole = users.id
			'.(!empty($ecole) ? 'WHERE ecole = '.$ecole : '').'
			ORDER BY heure_inscription DESC, nickname DESC
		');
		$prep->bindParam(':idb', $idb, PDO::PARAM_INT);
		$prep->execute();
		return $prep->fetchAll();
	}
	
	public function listPersons_version2($ecole) {
		$prep = $this->db->prepare('
			SELECT nom, prenom, Lieu, Heure, `users`.nickname  FROM `fdh_inscrits`,`fdh_bus`,`users` 
			WHERE ( `fdh_inscrits`.busaller=`fdh_bus`.idb AND `fdh_inscrits`.ecole=`users`.id '.(!empty($ecole) ? 'AND `fdh_inscrits`.ecole = '.$ecole : '').') ORDER BY nom ASC
		');
/* 		$prep->bindParam(':idb', $idb, PDO::PARAM_INT); */
		$prep->execute();
		return $prep->fetchAll();
	}
    
    public function listallerByBus($bus) {
		$prep = $this->db->prepare('
			SELECT nom, prenom, Lieu, Heure, `users`.nickname  FROM `fdh_inscrits`,`fdh_bus`,`users` 
			WHERE ( `fdh_inscrits`.busaller=`fdh_bus`.idb AND `fdh_inscrits`.ecole=`users`.id AND `fdh_inscrits`.busaller= '.$bus.') ORDER BY nom ASC
		');
/* 		$prep->bindParam(':idb', $idb, PDO::PARAM_INT); */
		$prep->execute();
		return $prep->fetchAll();
	}
    
    public function listretourByBus($bus) {
        $prep = $this->db->prepare('
			SELECT nom, prenom, Lieu, Heure, `users`.nickname  FROM `fdh_inscrits`,`fdh_bus`,`users` 
			WHERE ( `fdh_inscrits`.busretour=`fdh_bus`.idb AND `fdh_inscrits`.ecole=`users`.id AND `fdh_inscrits`.busretour= '.$bus.') ORDER BY nom ASC
		');
/* 		$prep->bindParam(':idb', $idb, PDO::PARAM_INT); */
		$prep->execute();
		return $prep->fetchAll();
	}

	public function listPersons_version3($ecole) {
		$prep = $this->db->prepare('
			SELECT nom, prenom, Lieu, Heure, `users`.nickname  FROM `fdh_inscrits`,`fdh_bus`,`users` 
			WHERE ( `fdh_inscrits`.busretour=`fdh_bus`.idb AND `fdh_inscrits`.ecole=`users`.id '.(!empty($ecole) ? 'AND `fdh_inscrits`.ecole = '.$ecole : '').') ORDER BY nom ASC
		');
/* 		$prep->bindParam(':idb', $idb, PDO::PARAM_INT); */
		$prep->execute();
		return $prep->fetchAll();
	}
	
	public function deletePersons() {
		$prep = $this->db->prepare('
			DELETE FROM fdh_inscrits
		');
		return $prep->execute();
	}
	
	public function updateBus($idb, $data) {
		$prep = $this->db->prepare('
			UPDATE fdh_bus
			SET max = :max, Lieu = :lieu, Heure = :heure, Numero = :numero
			WHERE idb = :idb
		');
		$prep->bindParam(':idb', $idb, PDO::PARAM_INT);
		$prep->bindParam(':max', $data['max'], PDO::PARAM_INT);
		$prep->bindParam(':lieu', $data['lieu']);
		$prep->bindParam(':heure', $data['heure']);
		$prep->bindParam(':numero', $data['numero'], PDO::PARAM_INT);
		return $prep->execute();
	}
	
	public function createBus($data) {
		$prep = $this->db->prepare('
			INSERT INTO fdh_bus(max, Lieu, Heure, Numero)
			VALUES(:max, :lieu, :heure, :numero)
		');
		$prep->bindParam(':max', $data['max'], PDO::PARAM_INT);
		$prep->bindParam(':lieu', $data['lieu']);
		$prep->bindParam(':heure', $data['heure']);
		$prep->bindParam(':numero', $data['numero'], PDO::PARAM_INT);
		return $prep->execute() or die ($prep->errorInfo());
	}
	
	public function delBus($idb) {
		$prep = $this->db->prepare('
			DELETE FROM fdh_bus
			WHERE idb = :idb
		');
		$prep->bindParam(':idb', $idb, PDO::PARAM_INT);
		return $prep->execute();
	}
	
	public function getBDEList($from, $number) {
		$prep = $this->db->prepare("
			SELECT users.id AS uid, nickname, email, name AS groupe, (SELECT COUNT(*) FROM fdh_inscrits WHERE ecole = uid) AS registered, users.access, DATE_FORMAT(date, '%d/%m/%Y %H:%i') AS fdate, DATE_FORMAT(last_activity, '%d/%m/%Y %H:%i') AS factivity
			FROM users
			LEFT JOIN users_cats
			ON groupe = users_cats.id
			WHERE groupe = 6
			ORDER BY nickname ASC
			LIMIT :start, :number
		");
		$prep->bindParam(':start', $from, PDO::PARAM_INT);
		$prep->bindParam(':number', $number, PDO::PARAM_INT);
		$prep->execute();
		return $prep->fetchAll();
	}
	
	public function countBDEs() {		
		$prep = $this->db->prepare("
			SELECT COUNT(*)
			FROM users
			LEFT JOIN users_cats
			ON groupe = users_cats.id
			WHERE groupe = 6
		");
		$prep->execute();
		return intval($prep->fetchColumn());
	}
	
	public function countRegisteredFDH() {		
		$prep = $this->db->prepare("
			SELECT COUNT(*)
			FROM fdh_inscrits
		");
		$prep->execute();
		return intval($prep->fetchColumn());
	}
	
	public function getBDEData($userid) {
		$prep = $this->db->prepare('
			SELECT nickname, email
			FROM users
			WHERE id = :userid
		');
		$prep->bindParam(':userid', $userid, PDO::PARAM_INT);
		$prep->execute();
		return $prep->fetch();
	}
	
	/**
	 * Vérifie qu'un pseudo est disponible
	 */
	public function nicknameAvailable($nickname) {
		$prep = $this->db->prepare('
			SELECT * FROM users WHERE nickname LIKE :nickname
		');
		$prep->bindParam(':nickname', $nickname);
		$prep->execute();
		return $prep->rowCount() == 0;
	}
	
	/**
	 * Vérifie qu'une adresse email n'est pas déjà présente dans la base
	 */
	public function emailAvailable($email) {
		$prep = $this->db->prepare('
			SELECT * FROM users WHERE email LIKE :email
		');
		$prep->bindParam(':email', $email);
		$prep->execute();
		return $prep->rowCount() == 0;
	}
	
	/**
	 * Vérifie qu'une adresse email n'est pas déjà présente dans la base
	 */
	public function isIdBDE($id) {
		$prep = $this->db->prepare('
			SELECT * FROM users WHERE id = :id AND groupe = 6
		');
		$prep->bindParam(':id', $id, PDO::PARAM_INT);
		$prep->execute();
		return $prep->rowCount() == 1;
	}
	
	public function addBDE($data) {
		$prep = $this->db->prepare("
			INSERT INTO users(nickname, password, confirm, email, firstname, lastname, groupe, access, ip)
			VALUES (:nick, :pass, '', :email, '', '', 6, 'fdh|0', :ip)
		");
		$prep->bindParam(':nick', $data['nickname']);
		$prep->bindParam(':pass', $data['pass']);
		$prep->bindParam(':email', $data['email']);
		$prep->bindParam(':ip', $_SERVER['REMOTE_ADDR']);
		return $prep->execute();
	}
	
	public function updateBDE($data) {
		$id = $data['id'];
		unset($data['id']);
		
		$string = '';
		foreach ($data as $key => $value) {
			$string .= $key.' = '.$this->db->quote($value).', ';
		}
		$string = substr($string, 0, -2);
		
		return $this->db->query('
			UPDATE users
			SET '.$string.'
			WHERE id = '.$id
		);
	}
	
	public function deleteBDE($id) {
		$prep = $this->db->prepare('
			DELETE FROM users WHERE id = :id AND groupe = 6
		');
		$prep->bindParam(':id', $id, PDO::PARAM_INT);
		return $prep->execute();
	}
}

?>
