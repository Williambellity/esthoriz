<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author	Fofif <Johan Dufau>
 * @version	$Id: apps/newsletter/admin/main.php 0001 06-10-2011 Fofif $
 */

class exceltomailAdminController extends WController {
	/*
	 * Les opérations du module
	 */
	protected $actionList = array(
		'write' => "Envoyer un mail de masse",
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
	
	protected function write() {
		$newsletter = WRequest::get(array('nomfeuille','adresse', 'nom', 'objet', 'message'), null, 'POST', false);
		#----------------------------------------------------
		# Le formulaire a-t-il été envoyé ?
		#----------------------------------------------------
		if (!in_array(null, $newsletter, true)) {
			$erreurs = array();
			
			/*
			 * VARIABLES NEWSLETTER
			 */
			if (!empty($newsletter['adresse']) && !preg_match('#^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$#i', $newsletter['adresse'])) {
				$erreurs[] = "L'email de l'expéditeur est invalide.";
			}
			if (empty($newsletter['nom'])) {
				$erreurs[] = "Il manque le Prénom et le NOM de l'expéditeur.";
			}
			if (empty($newsletter['objet'])) {
				$erreurs[] = "Veuillez préciser un objet.";
			}
			if (empty($newsletter['message'])) {
				$erreurs[] = "Le message que vous tentez d'envoyer est vide.";
			}
			if (empty($_FILES['attachment']['name'])) {
				$erreurs[] = "Aucun excel n'a été uploadé.";
			}
			if (empty($newsletter['nomfeuille'])) {
				$erreurs[] = "Aucune feuille de l'excel n'a été renseignée.";
			}				
			#----------------------------------------------------
			# En cas d'erreur
			#----------------------------------------------------
			if (!empty($erreurs)) {
				WNote::error("Informations invalides", implode("<br />\n", $erreurs), 'assign');
				$this->view->write($newsletter);
				$this->render('write');
			} else {

				#----------------------------------------------------
				# Gestion de l'excel   
				#----------------------------------------------------			
				$newsletter['attachment'] = ''; // init
				$excel_array = array();
				
				if (!empty($_FILES['attachment']['name'])) {
					include HELPERS_DIR.'upload/upload.php';
					$upload = new Upload($_FILES['attachment']);
					$upload->file_overwrite = true;
					$upload->Process(WT_PATH.'upload/exceltomail/');
					// traitement excel si succès de l'upload
					if ($upload->processed) {
						$file_path = WT_PATH.'upload/exceltomail/'.$upload->file_dst_name;
						$excel_array = $this->model->excelToArray($file_path,$newsletter['nomfeuille']); 
						print_r($excel_array);

					}
				}			
					
				#----------------------------------------------------
				# Envois des adresses mails 
				#----------------------------------------------------
				include( "helpers/phpmailer/class.phpmailer.php"); 
				for($i = 1; $i <= count ($excel_array)-1;$i++){ //on commence à la deuxieme ligne de l'excel la premiere etant reservee aux titres des colones
					$mail = new PHPMailer();
					$mail->CharSet = 'utf-8';
					$mail->IsHTML(true);
					$mail->From = $newsletter['adresse'];
					$mail->FromName = $newsletter['nom'];
					$mail->Subject = $newsletter['objet'];
					$mail->Body = $this->model->arrayFillingEmail ($excel_array[$i], html_entity_decode($newsletter['message']));
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
			}
		} else {
			$this->view->write();
			$this->render('write');
		}
	}

}

?>
