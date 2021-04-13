<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Julien1619
 * @version	$Id: apps/brochure/front/main.php 0001 11-06-2011 Julien1619 $
 */

class test20Controller extends WController {

	public function __construct() {
		include 'model.php';
		$this->model = new managerModel();
		
		include 'view.php';
		$this->setView(new managerView($this->model));
	}
	
		public function test20() {

        $this->view->test20();

		$this->render('test20');

	}
	
	public function launch() {
		$action = $this->getAskedAction();
		$this->forward($action, 'manager');
		
	}
	
	public function manager() {		
		WNote::treatNoteSession();
		
		
			$this->view->manager();
			$this->render('manager');
		
	}
	
	
}

?>
