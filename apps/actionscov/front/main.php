<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Julien1619
 * @version	$Id: apps/brochure/front/main.php 0001 11-06-2011 Julien1619 $
 */

class actionsCovController extends WController {

	public function __construct() {
		include 'model.php';
		$this->model = new actionsCovModel();
		
		include 'view.php';
		$this->setView(new actionsCovView($this->model));
	}
	
	
	public function launch() {
		$action = $this->getAskedAction();
		$this->forward($action, 'actionsCov');
	}
	
	public function actionsCov() {		
		WNote::treatNoteSession();
		
		
			$this->view->actionsCov();
			$this->render('actionsCov');
		
	}
	
	
}

?>
