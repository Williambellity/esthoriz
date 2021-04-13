<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Julien1619
 * @version	$Id: apps/brochure/front/main.php 0001 11-06-2011 Julien1619 $
 */

class concoursprojetController extends WController {

	public function __construct() {
		include 'model.php';
		$this->model = new concours_projetModel();
		
		include 'view.php';
		$this->setView(new concours_projetView($this->model));
	}
	
	
	public function launch() {
		$action = $this->getAskedAction();
		$this->forward($action, 'concours_projet');
	}
	
	public function concours_projet() {		
		WNote::treatNoteSession();
		
		
			$this->view->concours_projet();
			$this->render('concours_projet');
		
	}
	
	
}

?>
