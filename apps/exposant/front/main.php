<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Julien1619
 * @version	$Id: apps/brochure/front/main.php 0001 11-06-2011 Julien1619 $
 */

class exposantController extends WController {

	public function __construct() {
		include 'model.php';
		$this->model = new evenementModel();
		
		include 'view.php';
		$this->setView(new evenementView($this->model));
	}
	
		public function exposants() {

        $this->view->exposants();

		$this->render('exposants');

	}
	
	public function launch() {
		$action = $this->getAskedAction();
		$this->forward($action, 'evenement');
		
	}
	
	public function evenement() {		
		WNote::treatNoteSession();
		
		
			$this->view->evenement();
			$this->render('evenement');
		
	}
	
	
}

?>
