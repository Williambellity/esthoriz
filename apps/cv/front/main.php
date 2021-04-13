<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Julien1619
 * @version	$Id: apps/brochure/front/main.php 0001 11-06-2011 Julien1619 $
 */

class cvController extends WController {

	public function __construct() {
		include 'model.php';
		$this->model = new cvModel();
		
		include 'view.php';
		$this->setView(new cvView($this->model));
	}
	
	
	public function launch() {
		$action = $this->getAskedAction();
		$this->forward($action, 'cv');
	}
	
	public function cvd() {		
		// Les notes
		WNote::treatNoteSession();
		
		
			$this->view->cvd();
			$this->render('cvd');
		
	}
	
	public function cv() {		
		// Les notes
		WNote::treatNoteSession();
		
		
			$this->view->cv();
			$this->render('cv');
		
	}
	
	public function cvl() {		
		// Les notes
		WNote::treatNoteSession();
		
		
			$this->view->cv();
			$this->render('cvl');
		
	}
	
	public function confirmation() {
		$email = $_GET['user']; 
		$cle = $_GET['key'];
		if (!empty($email)) {
			if($this->model->updateConfirmation($email,$cle))
			{
			WNote::success(utf8_encode("Inscription validée"), utf8_encode("Nous vous donnons rendez-vous le 22 octobre au Centre Prouvé.<br /><br />

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
