<?php



/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif <Johan Dufau>
 * @version	$Id: apps/brochure/front/model.php 0000 28-04-2011 Fofif $
 */

class InscriptionModel {
	private $db;
	
	public function __construct() {
		$this->db = WSystem::getDB();
	}
	
	
	public function writeInscription($data) {
			return $this->insertInscription($data);
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
	
	
	private function insertInscription($data) {
		$tmp_name = $_FILES['cv']['tmp_name'];
		$mail_etu=strtolower($data['mail']);

    $fileName = $data['mail'].'.png';
    @copy($tmp_name,"/home/esthoriz/www/Appli/CV/".$data['mail'].".pdf");

		include LIBS_DIR.'phpmailer'.DS.'class.phpmailer.php';
						$mail = new PHPMailer();
						$mail->CharSet = 'utf-8';
						$mail->From = 'forum@est-horizon.com';
						$mail->FromName = 'Forum Est-Horizon';
						$mail->Subject = '3 : cv de'.$data['mail'];
						$mail->Body = utf8_encode('Voici le cv de'.$data['mail']);
						$mail->AddAttachment($tmp_name,''.$data['mail'].'.pdf');
						$mail->IsHTML(true);
						$mail->AddAddress('cv@est-horizon.com');
						$mail->Send();



						
		return true;
	}
	
	/*
	public function getState() {
		if ($this->testBrochure($_SESSION['firmid'])) {
			return array(0, "Les données concernant votre entreprise sont bien enregistrées.");
		} else {
			return array(8, "Pour figurer dans la brochure Visiteurs, saisissez les informations demandées.");
		}
	}*/


}

?>
