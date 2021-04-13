<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author	Fofif <Johan Dufau>
 * @version	$Id: apps/mail_masse/admin/main.php 0001 06-10-2011 Fofif $
 */

class ExceltomailAdminController extends WController {
	/*
	 * Les opérations du module
	 */
	protected $actionList = array(
		'write' => "Envoyer un mail de masse",
		'previsualisation' => "\previsualisation",
		'retour' => "\ retour",
	);
	
	public function __construct() {
		// Chargement des modèles
		include 'model.php';
		$this->model = new exceltomailAdminModel();
		
		include 'view.php';
		$this->setView(new exceltomailAdminView($this->model));
	}
	
	public function launch() {
		// Les notes
		WNote::treatNoteSession();
		
		$action = $this->getAskedAction();
		$this->forward($action, 'write');
	}
	
	public function extraction($mail_masse) {
		#----------------------------------------------------
		# Le formulaire a-t-il été envoyé ?
		#----------------------------------------------------
			$erreurs = array();
			
			/*
			 * VARIABLES NEWSLETTER
			 */
			if (!empty($mail_masse['adresse']) && !preg_match('#^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$#i', $mail_masse['adresse'])) {
				$erreurs[] = "L'email de l'expéditeur est invalide.";
			}
			if (empty($mail_masse['nom'])) {
				$erreurs[] = "Il manque le Prénom et le NOM de l'expéditeur.";
			}
			if (empty($mail_masse['objet'])) {
				$erreurs[] = "Veuillez préciser un objet.";
			}
			if (empty($mail_masse['message'])) {
				$erreurs[] = "Le message que vous tentez d'envoyer est vide.";
			}
			if (empty($_FILES['attachment']['name'])) {
				$erreurs[] = "Aucun excel n'a été uploadé.";
			}
			if (empty($mail_masse['nomfeuille'])) {
				$erreurs[] = "Aucune feuille de l'excel n'a été renseignée.";
			}				
			#----------------------------------------------------
			# En cas d'erreur
			#----------------------------------------------------
			if (!empty($erreurs)) {
				WNote::error("Informations invalides", implode("<br />\n", $erreurs), 'assign');
//				$this->view->write($mail_masse);
//				$this->render('write');
			} else {

				#----------------------------------------------------
				# Gestion de l'excel   
				#----------------------------------------------------			
				$mail_masse['attachment'] = ''; // init
				$excel_array = array();
				
				if (!empty($_FILES['attachment']['name'])) {
					include HELPERS_DIR.'upload/upload.php';
					$upload = new Upload($_FILES['attachment']);
					$upload->file_overwrite = true;
					$upload->Process(WT_PATH.'upload/exceltomail/');
					// traitement excel si succès de l'upload
					if ($upload->processed) {
						$file_path = WT_PATH.'upload/exceltomail/'.$upload->file_dst_name;
						$excel_array = $this->model->excelToArray($file_path,$mail_masse['nomfeuille']); 
					}
				}			
				#----------------------------------------------------
				# Return donnees    
				#----------------------------------------------------					
				return array($excel_array,$mail_masse['adresse'],$mail_masse['nom'],$mail_masse['objet'],$mail_masse['message']);		
			}

	}
	
	public function write() {
		$mail_masse = WRequest::get(array('nomfeuille','adresse', 'nom', 'objet', 'message'), null, 'POST', false);
		if (!in_array(null, $mail_masse, true)) {
				#----------------------------------------------------
				# Récupération des donnees 
				#----------------------------------------------------	
				$array = $this->extraction($mail_masse);
				$excel_array = $array[0];
				$mail_masse['adresse'] = $array[1];
				$mail_masse['nom'] = $array[2];
				$mail_masse['objet'] = $array[3];
				$mail_masse['message'] = $array[4];
				#----------------------------------------------------
				# Envois des adresses mails 
				#----------------------------------------------------
				include( "helpers/phpmailer/class.phpmailer.php"); 
				for($i = 1; $i <= count ($excel_array)-1;$i++){ //on commence à la deuxieme ligne de l'excel la premiere etant reservee aux titres des colones
					$mail = new PHPMailer();
					$mail->CharSet = 'utf-8';
					$mail->IsHTML(true);
					$mail->From = $mail_masse['adresse'];
					$mail->FromName = $mail_masse['nom'];
					$mail->Subject = $mail_masse['objet'];
					$mail->Body = $this->model->arrayFillingEmail ($excel_array[$i], html_entity_decode($mail_masse['message']));
					$mail->ClearAddresses();
					$mail->AddAddress($excel_array[$i][0]);// Par convention l'addresse mail est dans la premiere colonne
					$mail->Send();					
				}
				#----------------------------------------------------
				# Message de Succes
				#----------------------------------------------------
					WNote::success("Courriel envoyé", "Vos emails ont été envoyés avec succès.", 'session');
					// Redirection
					header('location: '.WRoute::getDir().'admin/exceltomail/');
				
		} else {
			$this->view->write();
			$this->render('write');
		}
	}
	
	public function previsualisation(){
		$mail_masse = WRequest::get(array('nomfeuille','adresse', 'nom', 'objet', 'message'), null, 'POST', false);
		print_r($mail_masse);
		if (!in_array(null, $mail_masse, true)) {
			$array = $this -> extraction($mail_masse);
			
			$this->view->previsualisation($array);
			$this->render('previsualisation');
		} else {
			$array=array(array(""),"","","","");
			$this->view->previsualisation($array);
			$this->render('previsualisation');
		}		
	}

}

?>
