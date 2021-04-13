<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Julien1619
 * @version	$Id: apps/brochure/front/main.php 0001 11-06-2011 Julien1619 $
 */

class accueilController extends WController {

	public function __construct() {
		include 'model.php';
		$this->model = new accueilModel();
		
		include 'view.php';
		$this->setView(new accueilView($this->model));
	}
	
	
	public function launch() {
		$action = $this->getAskedAction();
		$this->forward($action, 'accueil');
	}
	
	public function accueil() {		
		WNote::treatNoteSession();
		
		
			$this->view->accueil();
			$this->render('accueil');
		
	}
	
	
}

?>
