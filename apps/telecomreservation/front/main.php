<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif
 * @version	$Id: apps/test/front/main.php 0000 01-08-2011 Fofif $
 */

class telecomreservationController extends WController {
	/*
	 * Chargement du modèle et de la view
	 */
	public function __construct() {
		include 'model.php';
		$this->model = new testModel();
		
		include 'view.php';
		$this->setView(new testView($this->model));
	}
	
	public function launch() {
		//$this->view->test();
		$this->render('index');
	}
}

?>