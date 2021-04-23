<?php
/**
 * Wity CMS
 * Syst�me de gestion de contenu pour tous.
 *
 * @author Julien1619
 * @version	$Id: apps/brochure/front/main.php 0001 11-06-2011 Julien1619 $
 */

class concoursprojetsController extends WController {

	public function __construct() {
		include 'model.php';
		$this->model = new concours_projetsModel();
		
		include 'view.php';
		$this->setView(new concours_projetsView($this->model));
	}
	
	
	public function launch() {
		$action = $this->getAskedAction();
		$this->forward($action, 'concours_projets');
	}
	
	public function concours_projets() {		
		WNote::treatNoteSession();
		
		
			$this->view->concours_projets();
			$this->render('concours_projets');
		
	}
	
	
}

?>
