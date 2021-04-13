<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif
 * @version	$Id: main.php 0000 28-04-2011 Fofif $
 */

class ParticiperController extends WController {
	public function launch() {
		$act = $this->getAskedAction();
		$this->forward($act, 'exposer');
	}
	
	public function exposer() {
		$this->view->assign('css', '/apps/participer/front/css/participer.css');
		$this->view->assign('pageTitle', "Exposer au Forum Est-Horizon");
		$this->render('exposer');
	}
	
	public function visiter() {
		$this->view->assign('css', '/apps/participer/front/css/participer.css');
		$this->view->assign('pageTitle', "Visiter le Forum Est-Horizon");
		$this->render('visiter');
	}	
	public function plan() {
		$this->view->assign('css', '/apps/participer/front/css/participer.css');
		$this->view->assign('pageTitle', "Visiter le Forum Est-Horizon");
		$this->render('plan');
	}
}

?>