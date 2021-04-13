<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif
 * @version	$Id: apps/forum/front/main.php 0000 18-06-2011 Fofif $
 */

class ConcoursController extends WController {

    /*
	 * Chargement du modèle et de la view
	 */
	public function __construct() {
		include 'model.php';
		$this->model = new ConcoursModel();
		
		include 'view.php';
		$this->setView(new ConcoursView($this->model));
	}

	public function launch() {
		$action = $this->getAskedAction();
		$this->forward($action, 'presentation');
	}
	
	/*public function redirect() {
		header('location: '.WRoute::getDir().'preforum/');
	}
	*/
	public function presentation() {
		$this->view->assign('pageTitle', "Concours de projet | Présentation");
		//$this->view->assign('css', '/apps/forum/front/css/association.css');
		$this->render('presentation');
	}
	
	public function reglement() {		
		$this->view->assign('pageTitle', "Concours de projet | Règlement");		
		//$this->view->assign('css', '/apps/forum/front/css/association.css');		
		$this->render('reglement');	
	}					
	
	public function participer() {		
		$this->view->assign('pageTitle', "Concours de projet | Participer");
		//$this->view->assign('css', '/apps/forum/front/css/association.css');
		$this->render('participer');
	}
}
?>