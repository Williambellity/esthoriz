<?php



/**
 * Wity CMS
 * Syst�me de gestion de contenu pour tous.
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
		$result;
		unset($prep);
		
		$prep = $this->db->prepare('
			INSERT INTO visiteurs(nom, prenom, mail, ecole, connu1, connu2, 
			connu3, connu4, 
			connu5, connu6, autres, mailent, cle, photo_droit,Present) VALUES (:nom, :prenom, :mail, :ecole, 
			:connu1, :connu2, :connu3, :connu4, :connu5, :connu6, :autres, :mailent, :cle, :photo_droit,1)
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
		$prep->bindParam(':photo_droit', $data['photo_droit']);
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
		$tmp_name = $_FILES['cv']['tmp_name'];
		$mail_etu=strtolower($data['mail']);

		if($data['mailent']==1){
			
			// $mail = $data['mail']
			$sql = $this->db->prepare("SELECT COUNT(email) FROM newsletter WHERE email=:mail AND mailingactif='1'");
			$sql->bindParam(':mail',$data['mail']);
			$sql->execute();
			$presence = $sql->fetch(PDO::FETCH_ASSOC);
			if ($presence['COUNT(email)'] == 0){
			
				$adresseIP = $_SERVER['REMOTE_ADDR'];
				$mailing = TRUE;
				$date = date('Y-m-d h:i:s');
				$sql = $this->db->prepare("
					INSERT INTO newsletter(email,ip,dates,mailingactif)
					VALUES (:mail, :ip, :date, :mailingactif)");
				$sql->bindParam(':mail',$data['mail']);
				$sql->bindParam(':ip',$adresseIP);
				$sql->bindParam(':mailingactif',$mailing);
				$sql->bindParam(':date',$date);
				$sql->execute();
			}
		}	
		
 	include 'php-qrcode-master/lib/full/qrlib.php';
    include 'php-qrcode-master/examples/config.php';

    // how to save PNG codes to server
    
    $tempDir = EXAMPLE_TMP_SERVERPATH;
    $codeContents = 'http://www.est-horizon.com/CV2021/'.$data['mail'].'.pdf';
    
    // we need to generate filename somehow, 
    // with md5 or with database ID used to obtains $codeContents...
        
    $fileName = $data['mail'].'.png';
    
    $pngAbsoluteFilePath = $tempDir.$fileName;
    $urlRelativeFilePath = EXAMPLE_TMP_URLRELPATH.$fileName;
    
    // generating
    if (!file_exists($pngAbsoluteFilePath)) {
        QRcode::png($codeContents, $pngAbsoluteFilePath);
    } else {
    }
    copy($pngAbsoluteFilePath,"/home/esthoriz/www/Appli/INSCRIPTION/".$fileName);
    @copy($tmp_name,"/home/esthoriz/www/CV2021/".$data['mail'].".pdf");

		include LIBS_DIR.'phpmailer'.DS.'class.phpmailer.php';
						$mail = new PHPMailer();
						$mail->CharSet = 'utf-8';
						$mail->From = 'forum@est-horizon.com';
						$mail->FromName = 'Forum Est-Horizon';

						$mail->Subject = 'Confirmation de votre inscription au Forum Est-Horizon';
						$mail->Body = utf8_encode('Bonjour, </br></br>Nous vous remercions pour votre inscription au Forum Est-Horizon du jeudi 2 decembre 2021.</br>
								Pour confirmer votre inscription, cliquer sur le lien ci-dessous :</br></br>
						
						http://www.est-horizon.com/form/confirmation/?user='.utf8_encode($data['mail']).'&key='.urlencode($cle).'
						
						
						

						
						<p>Vous trouverez aussi en pi�ce jointe un QRcode � pr�senter le jour du forum. </p>
						L\'�quipe du Forum Est-Horizon
						');
						$mail->AddAttachment($pngAbsoluteFilePath,''.$data['mail'].'-qrcode.png');
						$mail->IsHTML(true);
						$mail->AddAddress($data['mail']);
						$mail->Send();


						$mail = new PHPMailer();
						$mail->CharSet = 'utf-8';
						$mail->From = 'forum@est-horizon.com';
						$mail->FromName = 'Forum Est-Horizon';
						$mail->Subject = '2 : cv de'.$data['nom'].' '.$data['prenom'];
						$mail->Body = utf8_encode('Voici le cv de '.$data['nom'].' '.$data['prenom'].' ayant pour mail: '.$data['mail']);
						$mail->AddAttachment($tmp_name,''.$data['mail'].'.pdf');
						$mail->IsHTML(true);
						$mail->AddAddress('cv@est-horizon.com');
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
