<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Julien1619
 * @version	$Id: apps/profil/front/model.php 0000 11-06-2011 Julien1619 $
 */

class ProfilModel {
	private $db;
	
	public function __construct() {
		$this->db = WSystem::getDB();
	}
	
	public function getContact() {
		$prep = $this->db->prepare('
			SELECT civilite, nom, prenom, poste, langue, adresse, tel_fixe, tel_portable, fax, email
			FROM entreprises_contacts
			WHERE userid = :userid
		');
		$prep->bindParam(':userid', $_SESSION['userid'], PDO::PARAM_INT);
		//$prep->bindParam(':admin', $_SESSION['nickname']);
		$prep->execute();
		return $prep->fetch(PDO::FETCH_ASSOC);
	}
	
	/**
	 * Mise à jour d'un contact
	 */
	public function updateContact($data) {
		$prep = $this->db->prepare('
			UPDATE entreprises_contacts SET civilite = :civilite, nom = :nom, prenom = :prenom, poste = :poste, langue = :langue, 
				adresse = :adresse, tel_fixe = :tel_fixe, tel_portable = :tel_portable, fax = :fax
			WHERE userid = :userid
		');
		$prep->bindParam(':civilite', $data['civilite']);
		$prep->bindParam(':nom', $data['nom']);
		$prep->bindParam(':prenom', $data['prenom']);
		$prep->bindParam(':poste', $data['poste']);
		$prep->bindParam(':langue', $data['langue']);
		$prep->bindParam(':adresse', $data['adresse']);
		$prep->bindParam(':tel_fixe', $data['tel_fixe']);
		$prep->bindParam(':tel_portable', $data['tel_portable']);
		$prep->bindParam(':fax', $data['fax']);
		$prep->bindParam(':userid', $_SESSION['userid'], PDO::PARAM_INT);
		
		include LIBS_DIR.'phpmailer'.DS.'class.phpmailer.php';
						$mail = new PHPMailer();
						$mail->CharSet = 'utf-8';
						$mail->From = 'forum@est-horizon.com';
						$mail->FromName = 'Forum Est-Horizon';
						$mail->Subject = 'Confirmation de votre inscription au Forum Est-Horizon';
						$mail->Body = utf8_encode('Bonjour, </br></br>Nous vous remercions pour votre inscription au Forum Est-Horizon du jeudi 2 decembre 2021.</br>
								Pour confirmer votre inscription, cliquer sur le lien ci-dessous :</br></br>
						
						http://www.est-horizon.com/form/confirmation/?user='.utf8_encode($data['mail']).'&key='.urlencode($cle).'
						</br></br>
						
						Pour information, avec votre adresse mail vous pourrez vous connecter depuis notre application "Forum Est-Horizon". </br>
						Elle est disponible sur le <strong>Google Play Store</strong> : https://play.google.com/store/apps/details?id=fr.feh2019.applicationfeh </br>
						</br></br> 
						Vous trouverez aussi en pièce jointe un QRcode renvoyant vers votre CV à présenter le jour du forum. Celui-ci ne fonctionne que si vous avez enregistré votre CV lors de votre inscription sur le site.
						L\'équipe du Forum Est-Horizon
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
		
		return $prep->execute();
	}
	
	public function getState() {
		return array(0, "Votre profil est enregistré.");
	}
}

?>
