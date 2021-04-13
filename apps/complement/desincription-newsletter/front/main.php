<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Julien1619
 * @version	$Id: apps/brochure/front/main.php 0001 11-06-2011 Julien1619 $
 */

class connexionController extends WController {

	public function __construct() {
		include 'model.php';
		$this->model = new connexionModel();
		
		include 'view.php';
		$this->setView(new connexionView($this->model));
	}
	
	
	public function launch() {
		$action = $this->getAskedAction();
		$this->forward($action, 'connexion');
	}
	
	public function connexion() {		
		WNote::treatNoteSession();
		
		
			$this->view->connexion();
			$this->render('connexion');
		
	}
	
	
}

?>
