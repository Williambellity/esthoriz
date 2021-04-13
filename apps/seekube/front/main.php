<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Julien1619
 * @version	$Id: apps/brochure/front/main.php 0001 11-06-2011 Julien1619 $
 */

class seekubeController extends WController {

	public function __construct() {
		include 'model.php';
		$this->model = new seekubeModel();
		
		include 'view.php';
		$this->setView(new seekubeView($this->model));
	}
	
	
	public function launch() {
		$action = $this->getAskedAction();
		$this->forward($action, 'seekube');
	}
	
	public function seekube() {		
		WNote::treatNoteSession();
		
		
			$this->view->seekube();
			$this->render('seekube');
		
	}
	
	
}

?>
