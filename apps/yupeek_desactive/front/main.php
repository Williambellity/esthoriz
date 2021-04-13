<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif
 * @version	$Id: apps/yupeek/front/main.php 0002 11-10-2013 Fofif $
 */

class YupeekController extends WController { // Le nom du Controller est important : [AppName]Controller d'où YupeekController
	public function launch() {
		$act = $this->getAskedAction();
		$this->forward($act, 'presentation'); // Ici on appelle l'action 'presentation' par défaut
	}
	
	public function presentation() { // Notre action par défaut
		$this->view->assign('css', '/apps/yupeek/front/css/yupeek.css'); // Import de CSS
		$this->view->assign('pageTitle', "Partenaire Yupeek"); // Définition du titre de la page
		$this->render('yupeek'); // On cherche /apps/yupeek/front/templates/yupeek.html
	}
 
}

?>