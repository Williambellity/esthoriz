<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Julien1619
 * @version	$Id: apps/brochure/front/main.php 0001 11-06-2011 Julien1619 $
 */

class gestionNewsletterController extends WController {

	public function __construct() {
		include 'model.php';
		$this->model = new gestionNewsletterModel();
		
		include 'view.php';
		$this->setView(new gestionNewsletterView($this->model));
	}
	

	
	public function launch() {
		$action = $this->getAskedAction();
		$this->forward($action, 'gestionNewsletter');
		
	}
	
	public function gestionNewsletter() {		
		WNote::treatNoteSession();
		
		
			$this->view->gestionNewsletter();
			$this->render('gestionNewsletter');
		
	}
	
	
}

?>
