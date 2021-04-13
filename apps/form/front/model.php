<?php
/**
 * Wity CMS
 * Syst�me de gestion de contenu pour tous.
 *
 * @author Fofif <Johan Dufau>
 * @version	$Id: apps/brochure/front/model.php 0000 28-04-2011 Fofif $
 */

class FormModel {
	private $db;
	
	public function __construct() {
		$this->db = WSystem::getDB();
	}
	
	
	public function writeForm($data) {
			return $this->insertForm($data);
	}
	
	
	
	public function updateConfirmation($email,$cle) {
	
	$prep = $this->db->prepare('SELECT cle FROM visiteurs WHERE mail=:email
	');
	$prep->bindParam(':email', $email);
	$prep->execute();
	$data=$prep->fetch();
	
	if($data['cle']==$cle)
	{
		$prep = $this->db->prepare('
			UPDATE visiteurs SET clecheck = 1 WHERE  mail = :email
		');
		$prep->bindParam(':email', $email);

		$prep->execute();
		
		return true;
		}
	else
		return false;
	}
	
	
	private function insertForm($data) {
		$result;
		unset($prep);
		
		$prep = $this->db->prepare('
			INSERT INTO visiteurs(nom, prenom, mail, ecole, connu1, connu2, 
			connu3, connu4, 
			connu5, connu6, autres, mailent, cle) VALUES (:nom, :prenom, :mail, :ecole, 
			:connu1, :connu2, :connu3, :connu4, :connu5, :connu6, :autres, :mailent, :cle)
		');
		

		$prep->bindParam(':nom', $data['nom']);
		$prep->bindParam(':prenom', $data['prenom']);
		$prep->bindParam(':mail', $data['mail']);
		$prep->bindParam(':ecole', $data['ecole']);
		$prep->bindParam(':connu1', $data['connu1']);
		$prep->bindParam(':connu2', $data['connu2']);
		$prep->bindParam(':connu3', $data['connu3']);
		$prep->bindParam(':connu4', $data['connu4']);
		$prep->bindParam(':connu5', $data['connu5']);
		$prep->bindParam(':connu6', $data['connu6']);
		$prep->bindParam(':autres', $data['autres']);
		$prep->bindParam(':mailent', $data['mailent']);
		$cle=md5(microtime(TRUE)*457987);
		$prep->bindParam(':cle', $cle);
		if ($data['connu1']==null)
			$data['connu1'] = 0;
		if ($data['connu2']==null)
			$data['connu2'] = 0;
		if ($data['connu3']==null)
			$data['connu3'] = 0;
		if ($data['connu4']==null)
			$data['connu4'] = 0;
		if ($data['connu5']==null)
			$data['connu5'] = 0;
		if ($data['connu6']==null)
			$data['connu6'] = 0;
		if ($data['autres']==null)
			$data['autres'] = " ";	
		if ($data['mailent']==null)
			$data['mailent'] = 0;
		$result=$prep->execute();
		
		$mail_etu=strtolower($data['mail']);
		include LIBS_DIR.'phpmailer'.DS.'class.phpmailer.php';
						$mail = new PHPMailer();
						$mail->CharSet = 'utf-8';
						$mail->From = 'forum@est-horizon.com';
						$mail->FromName = 'Forum Est-Horizon';
						$mail->Subject = 'confirmation de votre inscription au Forum Est-Horizon';
						$mail->Body = utf8_encode('Bonjour, </br></br>Nous vous remercions pour votre inscription au Forum Est-Horizon du jeudi 22 octobre 2015.</br>
								Pour confirmer votre inscription, cliquer sur le lien ci-dessous :</br></br>
						
						http://www.est-horizon.com/form/confirmation/?user='.utf8_encode($data['mail']).'&key='.urlencode($cle).'
						</br></br>
						L\'�quipe du Forum Est-Horizon
						');
						$mail->IsHTML(true);
						$mail->AddAddress($data['mail']);
						$mail->Send();
						
		return $result;
	}
	
	/*
	public function getState() {
		if ($this->testBrochure($_SESSION['firmid'])) {
			return array(0, "Les donn�es concernant votre entreprise sont bien enregistr�es.");
		} else {
			return array(8, "Pour figurer dans la brochure Visiteurs, saisissez les informations demand�es.");
		}
	}*/


}

?>