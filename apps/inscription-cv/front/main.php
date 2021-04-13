<?php
/**
 * Wity CMS
 * Syst�me de gestion de contenu pour tous.
 *
 * @author Julien1619
 * @version	$Id: apps/brochure/front/main.php 0001 11-06-2011 Julien1619 $
 */

class Inscription_CvController extends WController {

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
		
		$data = WRequest::get(array(  'mail'), null, 'POST', false); 
		
		if ($data['mail']!=NULL){//!in_array(null, $data, true)) {
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
					WNote::success(utf8_encode("CV d�pos�"), utf8_encode("Nous vous donnons rendez-vous le 17 octobre au Centre Prouv�.<br /><br />

					Et n'oubliez pas de t�l�charger notre <strong>application</strong>
					"), 'display');
					/*WNote::success("Informations enregistr�es", "Nous vous remercions pour votre inscriptions. Vos informations ont bien �t�</br>
					prises en compte. Vous serez contact�s ult�rieurement par email", 'session');*/
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
			WNote::success(utf8_encode("Inscription valid�e"), utf8_encode("Nous vous donnons rendez-vous le 17 octobre au Centre Prouv�.<br /><br />

<strong>Le Forum Est-Horizon</strong>"), 'display');

		
			}
			else
				{
				WNote::error("Erreur invalide", utf8_encode("L'email ne correspond pas ou existe d�j�.</br> Il doit en effet y avoir une adresse unique pour chaque cas.</br>
				Merci de nous contacter afin de r�soudre le probl�me."), 'display');
		}
		} else {
			WNote::error("Erreur invalide", utf8_encode("L'email ne correspond pas.</br> Merci de nous contacter afin de r�soudre le probl�me."), 'display');
		}
	}
}

?>
