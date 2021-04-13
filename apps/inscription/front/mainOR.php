<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Julien1619
 * @version	$Id: apps/brochure/front/main.php 0001 11-06-2011 Julien1619 $
 */

class InscriptionController extends WController {

	public function __construct() {
		include 'model.php';
		$this->model = new InscriptionModel();
		
		include 'view.php';
		$this->setView(new InscriptionView($this->model));
	}
	
	
	public function launch() {
		$action = $this->getAskedAction();
		$this->forward($action, 'inscription');
	}
	
	public function inscription() {		
		// Les notes
		WNote::treatNoteSession();
		
		$data = WRequest::get(array( 'nom', 'prenom', 'mail', 'ecole', 'connu1', 'connu2', 'connu3', 'connu4','connu5', 
		'connu6', 'autres', 'mailent', 'photo_droit'), null, 'POST', false); 
		
		if ($data['nom']!=NULL){//!in_array(null, $data, true)) {
			$erreurs = array();
			
			// En cas d'erreur
			if (!empty($erreurs)) {
				WNote::error("Informations invalides", implode("<br />\n", $erreurs), 'display');
			} else {
				/*if(WRequest::get('maudit_ie6')) {
					$this->uploadLogo($firmid);
				}*/
				if ($this->model->writeInscription($data)) {
					mb_internal_encoding('UTF-8');
					WNote::info(utf8_encode("Demande d'inscription réalisée"), utf8_encode(" Vous allez recevoir un mail 
					depuis lequel vous pourrez valider votre demande d'inscription.</br>
					"), 'display');
					/*WNote::success("Informations enregistrées", "Nous vous remercions pour votre inscriptions. Vos informations ont bien été</br>
					prises en compte. Vous serez contactés ultérieurement par email", 'session');*/
					//header('location: '.WRoute::getDir().'form/');//.(empty($_SESSION['firmid']) ? $firmid : ''));
		
				} 
				else {
					WNote::error("Erreur", "Une erreur s'est produite.", 'display');
				}
			}
		} 
		else {
			$this->view->inscription();
			$this->render('inscription');
		}
	}
	
	public function confirmation() {
		$email = $_GET['user']; 
		$cle = $_GET['key'];
		if (!empty($email)) {
			if($this->model->updateConfirmation($email,$cle))
			{
			WNote::success(utf8_encode("Inscription validée"), utf8_encode("Nous vous donnons rendez-vous le 26 Octobre au Centre Prouvé.<br /><br />

<strong>Le Forum Est-Horizon</strong>"), 'display');

		
			}
			else
				{
				WNote::error("Erreur invalide", utf8_encode("L'email ne correspond pas ou existe déjà.</br> Il doit en effet y avoir une adresse unique pour chaque cas.</br>
				Merci de nous contacter afin de résoudre le problème."), 'display');
		}
		} else {
			WNote::error("Erreur invalide", utf8_encode("L'email ne correspond pas.</br> Merci de nous contacter afin de résoudre le problème."), 'display');
		}
	}
}

?>
