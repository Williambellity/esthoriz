<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Julien1619
 * @version	$Id: apps/brochure/front/main.php 0001 11-06-2011 Julien1619 $
 */

class mesuresController extends WController {

	public function __construct() {
		include 'model.php';
		$this->model = new mesuresModel();
		
		include 'view.php';
		$this->setView(new mesuresView($this->model));
	}
	
	
	public function launch() {
		$action = $this->getAskedAction();
		$this->forward($action, 'mesures');
	}
	
	public function mesures() {		
		WNote::treatNoteSession();
		
		
			$this->view->mesures();
			$this->render('mesures');
		
	}
	
	
}

?>
