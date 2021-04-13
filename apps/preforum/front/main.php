<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif <Johan Dufau>
 * @version	$Id: apps/preforum/front/main.php 0001 19-05-2011 Julien1619 $
 */

class PreforumController extends WController {
	/*
	 * Chargement du modèle et de la view
	 */
	public function __construct() {
	}
	
	public function launch() {
		$action = $this->getAskedAction();
		$this->forward($action, 'preforum');
	}
	
	public function preforum() {
		$this->view->assign('pageTitle', 'Assistez au Pré-Forum du 10 Octobre 2013');
		$this->view->assign('css', '/apps/preforum/front/css/style.css');
		$this->render('preforum');
	}
}

?>