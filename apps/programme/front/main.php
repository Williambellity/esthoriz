<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Julien1619
 * @version	$Id: apps/brochure/front/main.php 0001 11-06-2011 Julien1619 $
 */

class programmeController extends WController {

	public function __construct() {
		include 'model.php';
		$this->model = new espace_etudiantsModel();
		
		include 'view.php';
		$this->setView(new espace_etudiantsView($this->model));
	}
	
	
	public function launch() {
		$action = $this->getAskedAction();
		$this->forward($action, 'espace_etudiants');
	}
	
	public function espace_etudiants() {		
		WNote::treatNoteSession();
		
		
			$this->view->espace_etudiants();
			$this->render('espace_etudiants');
		
	}
	
	
}

?>
