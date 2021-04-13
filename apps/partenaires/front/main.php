<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Julien1619
 * @version	$Id: apps/brochure/front/main.php 0001 11-06-2011 Julien1619 $
 */

class partenairesController extends WController {

	public function __construct() {
		include 'model.php';
		$this->model = new partenairesModel();
		
		include 'view.php';
		$this->setView(new partenairesView($this->model));
	}
	
	
	public function launch() {
		$action = $this->getAskedAction();
		$this->forward($action, 'partenaires');
	}
	
	public function partenaires() {		
		WNote::treatNoteSession();
		
		
			$this->view->partenaires();
			$this->render('partenaires');
		
	}
	
	
}

?>
