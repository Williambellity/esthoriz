<?php
/**
 * Wity CMS
 * Syst�me de gestion de contenu pour tous.
 *
 * @author Julien1619
 * @version	$Id: apps/brochure/front/main.php 0001 11-06-2011 Julien1619 $
 */

class espace_entreprisesController extends WController {

	public function __construct() {
		include 'model.php';
		$this->model = new espace_entreprisesModel();
		
		include 'view.php';
		$this->setView(new espace_entreprisesView($this->model));
	}
	
	
	public function launch() {
		$action = $this->getAskedAction();
		$this->forward($action, 'espace_entreprises');
	}
	
	public function espace_entreprises() {		
		WNote::treatNoteSession();
		
		
			$this->view->espace_entreprises();
			$this->render('espace_entreprises');
		
	}
	
	
}

?>
